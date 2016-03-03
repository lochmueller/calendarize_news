<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Persistence;

use HDNET\Calendarize\Service\IndexerService;
use HDNET\Calendarize\Utility\HelperUtility;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use HDNET\CalendarizeNews\Xclass\NewsRepository;
use TYPO3\CMS\Core\Database\DatabaseConnection;
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

            $database = $this->getDatabaseConnection();
            $this->indexResult = $database->exec_SELECTgetRows('*', IndexerService::TABLE_NAME,
                'foreign_table = "tx_news_domain_model_news" AND foreign_uid IN (' . implode(',',
                    $newsIds) . ') AND start_date > ' . time(), '', 'start_date ASC');
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
            $overwriteService = HelperUtility::create('HDNET\\CalendarizeNews\\Service\\NewsOverwrite');

            /** @var NewsRepository $newsRepository */
            $newsRepository = HelperUtility::create('GeorgRinger\\News\\Domain\\Repository\\NewsRepository');
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

    /**
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}