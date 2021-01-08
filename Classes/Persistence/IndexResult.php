<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Persistence;

use GeorgRinger\News\Domain\Model\NewsDefault;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\Calendarize\Utility\DateTimeUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @todo General class information
 *
 */
class IndexResult extends QueryResult
{

    /**
     * The result of the index selection
     *
     * @var array
     */
    protected $indexResult;

    /**
     * Inject Repository
     *
     * @var \HDNET\Calendarize\Domain\Repository\IndexRepository
     *
     */
    protected $indexRepository;

    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    /**
     * @param \HDNET\Calendarize\Domain\Repository\IndexRepository $indexRepository
     */
    public function injectIndexRepository(\HDNET\Calendarize\Domain\Repository\IndexRepository $indexRepository): void
    {
        $this->indexRepository = $indexRepository;
    }

    /**
     * @param \GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository
     */
    public function injectNewsRepository(\GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository): void
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Loads the objects this QueryResult is supposed to hold
     *
     * @return void
     */
    protected function initializeIndex()
    {
        if (!is_array($this->indexResult)) {
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
                    $query->greaterThanOrEqual('startDate', DateTimeUtility::getNow()->format('Y-m-d'))
                ])
            )->execute()->toArray();
        }
    }

    /**
     * Loads the objects this QueryResult is supposed to hold
     *
     * @return void
     */
    protected function initialize()
    {
        if (!is_array($this->queryResult)) {
            $this->initializeIndex();
            $overwriteService = GeneralUtility::makeInstance(\HDNET\CalendarizeNews\Service\NewsOverwrite::class);
            $selection = array_slice($this->indexResult, (int)$this->query->getOffset(), (int)$this->query->getLimit());
            $this->queryResult = [];
            /** @var Index $item */
            foreach ($selection as $item) {
                /** @var NewsDefault $news */
                $news = $this->newsRepository->findByIdentifier($item->getForeignUid());
                if (is_object($news)) {
                    $customNews = clone $news;
                    $overwriteService->overWriteNewsPropertiesByIndex($customNews, $item);
                    $this->queryResult[] = $customNews;
                }
            }
        }
    }

    /**
     * Returns the first object in the result set
     *
     * @return object
     * @api
     */
    public function getFirst()
    {
        $this->initialize();
        $queryResult = $this->queryResult;
        reset($queryResult);
        $firstResult = current($queryResult);
        if ($firstResult === false) {
            $firstResult = null;
        }
        return $firstResult;
    }

    /**
     * Returns the number of objects in the result
     *
     * @return integer The number of matching objects
     * @api
     */
    public function count()
    {
        if ($this->numberOfResults === null) {
            $this->initializeIndex();
            $this->numberOfResults = count($this->indexResult);
        }
        return $this->numberOfResults;
    }
}
