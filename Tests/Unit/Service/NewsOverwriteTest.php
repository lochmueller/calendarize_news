<?php
/**
 * NewsOverwriteTest.
 */

namespace HDNET\CalendarizeNews\Tests\Unit\Persistence;

use GeorgRinger\News\Domain\Model\News;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * NewsOverwriteTest.
 */
class NewsOverwriteTest extends UnitTestCase
{
    public function testOverWriteNewsPropertiesByIndexObject()
    {
        $service = new NewsOverwrite();

        $index = new Index();
        $index->setAllDay(true);
        $date = (new \DateTime())->setTime(0, 0);
        $index->setStartDate($date);
        $index->setEndDate($date);

        $news = new News();
        $service->overWriteNewsPropertiesByIndex($news, $index);
        self::assertEquals($date, $news->getDatetime());
    }
}
