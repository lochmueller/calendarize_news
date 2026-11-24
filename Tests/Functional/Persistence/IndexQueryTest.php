<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Tests\Functional\Persistence;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use HDNET\Calendarize\Service\IndexerService;
use HDNET\CalendarizeNews\Persistence\IndexQuery;
use HDNET\CalendarizeNews\Persistence\IndexResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class IndexQueryTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'extbase',
        'fluid',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/news',
        'typo3conf/ext/calendarize',
        'typo3conf/ext/calendarize_news',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/NewsWithFutureEvents.csv');
        GeneralUtility::makeInstance(IndexerService::class)->reindexAll();
    }

    public function testIndexQueryReturnsOneNewsItemPerIndexOrderedByDate(): void
    {
        $result = $this->createIndexQuery()->execute();

        self::assertInstanceOf(IndexResult::class, $result);
        self::assertCount(2, $result);

        $items = $result->toArray();
        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(News::class, $items);
        self::assertSame(20, $items[0]->getUid());
        self::assertSame('2099-03-01', $items[0]->getDatetime()->format('Y-m-d'));
        self::assertSame('2099-05-02', $items[1]->getDatetime()->format('Y-m-d'));
        self::assertSame($items[0], $result->getFirst());
    }

    public function testIndexResultWorksWithPagination(): void
    {
        $paginator = new QueryResultPaginator($this->createIndexQuery()->execute(), 2, 1);

        self::assertSame(2, $paginator->getNumberOfPages());
        $items = array_values(iterator_to_array($paginator->getPaginatedItems()));
        self::assertCount(1, $items);
        self::assertSame('2099-05-02', $items[0]->getDatetime()->format('Y-m-d'));
    }

    private function createIndexQuery(): IndexQuery
    {
        $newsQuery = GeneralUtility::makeInstance(NewsRepository::class)->createQuery();
        $newsQuery->getQuerySettings()->setRespectStoragePage(false);
        self::assertInstanceOf(Query::class, $newsQuery);

        return GeneralUtility::makeInstance(IndexQuery::class)->initializeFromQuery($newsQuery);
    }
}
