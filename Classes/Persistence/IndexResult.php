<?php

namespace HDNET\CalendarizeNews\Persistence;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\Calendarize\Domain\Repository\IndexRepository;
use HDNET\Calendarize\Utility\DateTimeUtility;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Query result with one (overwritten) news item per upcoming calendarize index.
 */
class IndexResult extends QueryResult
{
    /**
     * The result of the index selection.
     *
     * @var Index[]|null
     */
    protected ?array $indexResult = null;

    public function __construct(
        DataMapper $dataMapper,
        PersistenceManagerInterface $persistenceManager,
        protected IndexRepository $indexRepository,
        protected NewsRepository $newsRepository,
        protected NewsOverwrite $newsOverwrite,
    ) {
        parent::__construct($dataMapper, $persistenceManager);
    }

    /**
     * Loads the index records of the selected news.
     */
    protected function initializeIndex(): void
    {
        if (\is_array($this->indexResult)) {
            return;
        }

        $newsIds = [-1];
        $newsQuery = $this->query instanceof IndexQuery ? $this->query->getNewsQuery() : $this->query;
        foreach ($newsQuery->execute(true) as $row) {
            $newsIds[] = (int)$row['uid'];
        }

        $query = $this->indexRepository->createQuery();
        $query->setOrderings([
            'startDate' => QueryInterface::ORDER_ASCENDING,
            'startTime' => QueryInterface::ORDER_ASCENDING,
        ]);
        /** @var Index[] $indexResult */
        $indexResult = $query->matching(
            $query->logicalAnd(
                $query->equals('foreignTable', 'tx_news_domain_model_news'),
                $query->in('foreignUid', array_unique($newsIds)),
                $query->greaterThanOrEqual('startDate', DateTimeUtility::getNow()->format('Y-m-d')),
            )
        )->execute()->toArray();
        $this->indexResult = $indexResult;
    }

    /**
     * Loads the objects this QueryResult is supposed to hold.
     */
    protected function initialize(): void
    {
        if (\is_array($this->queryResult)) {
            return;
        }

        $this->initializeIndex();
        $selection = \array_slice(
            $this->indexResult,
            (int)$this->query->getOffset(),
            $this->query->getLimit() ?: null
        );
        $this->queryResult = [];
        foreach ($selection as $item) {
            $news = $this->newsRepository->findByIdentifier($item->getForeignUid());
            if ($news instanceof News) {
                $customNews = clone $news;
                $this->newsOverwrite->overWriteNewsPropertiesByIndex($customNews, $item);
                $this->queryResult[] = $customNews;
            }
        }
    }

    public function getFirst()
    {
        $this->initialize();
        $firstResult = reset($this->queryResult);

        return false === $firstResult ? null : $firstResult;
    }

    public function count(): int
    {
        if (null === $this->numberOfResults) {
            if (\is_array($this->queryResult)) {
                $this->numberOfResults = \count($this->queryResult);
            } else {
                $this->initializeIndex();
                $total = \count($this->indexResult) - (int)$this->query->getOffset();
                $limit = (int)$this->query->getLimit();
                $this->numberOfResults = max(0, $limit > 0 ? min($limit, $total) : $total);
            }
        }

        return $this->numberOfResults;
    }

    public function __unserialize(array $data): void
    {
        parent::__wakeup();
        $this->indexRepository = GeneralUtility::makeInstance(IndexRepository::class);
        $this->newsRepository = GeneralUtility::makeInstance(NewsRepository::class);
        $this->newsOverwrite = GeneralUtility::makeInstance(NewsOverwrite::class);
    }
}
