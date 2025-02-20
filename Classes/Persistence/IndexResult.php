<?php

namespace HDNET\CalendarizeNews\Persistence;

use GeorgRinger\News\Domain\Model\NewsDefault;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\Calendarize\Domain\Repository\IndexRepository;
use HDNET\Calendarize\Utility\DateTimeUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class IndexResult extends QueryResult
{
    /**
     * The result of the index selection.
     */
    protected ?array $indexResult = null;

    protected IndexRepository $indexRepository;

    protected NewsRepository $newsRepository;

    public function injectIndexRepository(IndexRepository $indexRepository): void
    {
        $this->indexRepository = $indexRepository;
    }

    public function injectNewsRepository(NewsRepository $newsRepository): void
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Loads the objects this QueryResult is supposed to hold.
     */
    protected function initializeIndex()
    {
        if (!\is_array($this->indexResult)) {
            $newsIds = [];
            $query = clone $this->query;
            $query->setLimit(99999999);
            $query->setOffset(0);
            $news = $query->execute(true);
            foreach ($news as $row) {
                $newsIds[] = (int)$row['uid'];
            }
            $newsIds[] = -1;

            $query = $this->indexRepository->createQuery();
            $query->setOrderings([
                'startDate' => QueryInterface::ORDER_ASCENDING,
                'startTime' => QueryInterface::ORDER_ASCENDING,
            ]);
            $this->indexResult = $query->matching(
                $query->logicalAnd([
                    $query->equals('foreignTable', 'tx_news_domain_model_news'),
                    $query->in('foreignUid', $newsIds),
                    $query->greaterThanOrEqual('startDate', DateTimeUtility::getNow()->format('Y-m-d')),
                ])
            )->execute()->toArray();
        }
    }

    /**
     * Loads the objects this QueryResult is supposed to hold.
     */
    protected function initialize()
    {
        if (!\is_array($this->queryResult)) {
            $this->initializeIndex();
            $overwriteService = GeneralUtility::makeInstance(\HDNET\CalendarizeNews\Service\NewsOverwrite::class);
            $selection = \array_slice($this->indexResult, $this->query->getOffset(), $this->query->getLimit());
            $this->queryResult = [];
            /** @var Index $item */
            foreach ($selection as $item) {
                /** @var NewsDefault $news */
                $news = $this->newsRepository->findByIdentifier($item->getForeignUid());
                if (\is_object($news)) {
                    $customNews = clone $news;
                    $overwriteService->overWriteNewsPropertiesByIndex($customNews, $item);
                    $this->queryResult[] = $customNews;
                }
            }
        }
    }

    /**
     * Returns the first object in the result set.
     *
     * @return object
     */
    public function getFirst()
    {
        $this->initialize();
        $queryResult = $this->queryResult;
        reset($queryResult);
        $firstResult = current($queryResult);
        if (false === $firstResult) {
            $firstResult = null;
        }

        return $firstResult;
    }

    /**
     * Returns the number of objects in the result.
     */
    public function count(): int
    {
        if (null === $this->numberOfResults) {
            $this->initializeIndex();
            $this->numberOfResults = \count($this->indexResult);
        }

        return $this->numberOfResults;
    }
}
