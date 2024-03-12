<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Tests\Functional;

use HDNET\Calendarize\Service\IndexerService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class NewsIndexTest extends FunctionalTestCase
{
    public function __construct()
    {
        // Initialize inside constructor to support TYPO3 v10
        $this->coreExtensionsToLoad = ['extbase', 'fluid'];
        $this->testExtensionsToLoad = ['typo3conf/ext/news', 'typo3conf/ext/calendarize', 'typo3conf/ext/calendarize_news'];
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 12) {
            array_splice($this->testExtensionsToLoad, 1, 0, 'typo3conf/ext/autoloader');
        }
        parent::__construct();
    }

    public function testNewsIndexing(): void
    {
        $this->importCSVDataSet(__DIR__ . "/Fixtures/News.csv");

        /** @var IndexerService $indexerService */
        $indexerService = GeneralUtility::makeInstance(IndexerService::class);
        $indexerService->reindexAll();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_calendarize_domain_model_index');

        $rows = $queryBuilder
            ->select('*')
            ->from('tx_calendarize_domain_model_index')
            ->execute()->fetchAllAssociative();

        self::assertCount(1, $rows);
        self::assertEquals(20, $rows[0]['foreign_uid']);
        self::assertEquals('2026-05-02', $rows[0]['start_date']);
    }
}
