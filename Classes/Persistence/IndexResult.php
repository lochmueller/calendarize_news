<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Persistence;

use HDNET\Calendarize\Service\IndexerService;
use HDNET\Calendarize\Utility\DateTimeUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

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
     * @inject
     */
    protected $indexRepository;

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

            $q = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(IndexerService::TABLE_NAME);
            $this->indexResult = $q->select('*')
                ->from(IndexerService::TABLE_NAME)
                ->where(
                    $q->expr()->andX(
                        $q->expr()->gte('start_date', $q->createNamedParameter(DateTimeUtility::getNow()->format('Y-m-d'))),
                        $q->expr()->eq('foreign_table', $q->createNamedParameter('tx_news_domain_model_news')),
                        $q->expr()->in('foreign_uid', $newsIds),
                    )
                )
                ->addOrderBy('start_date', 'ASC')
                ->addOrderBy('start_time', 'ASC')
                ->execute()
                ->fetchAll();
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
            /** @var NewsOverwrite $overwriteService */
            // @todo migrate
            $overwriteService = HelperUtility::create(\HDNET\CalendarizeNews\Service\NewsOverwrite::class);

            /** @var NewsRepository $newsRepository */
            // @todo migrate
            $newsRepository = HelperUtility::create(\GeorgRinger\News\Domain\Repository\NewsRepository::class);
            $selection = array_slice($this->indexResult, (int)$this->query->getOffset(), (int)$this->query->getLimit());
            $this->queryResult = [];
            foreach ($selection as $item) {
                $news = $newsRepository->findByIdentifier((int)$item['foreign_uid']);
                if (is_object($news)) {
                    $customNews = clone $news;
                    $overwriteService->overWriteNewsPropertiesByIndexArray($customNews, $item);
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
