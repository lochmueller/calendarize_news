<?php
/**
 * NewsOverwriteTest
 */

namespace HDNET\CalendarizeNews\Tests\Unit\Persistence;

use GeorgRinger\News\Domain\Model\News;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * NewsOverwriteTest
 */
class NewsOverwriteTest extends UnitTestCase
{

    /**
     * @test
     */
    public function testOverWriteNewsPropertiesByIndexObject()
    {
        $service = new NewsOverwrite();

        $index = new Index();
        $index->setAllDay(true);
        $date = new \DateTime();
        $index->setStartDate($date);
        $index->setEndDate($date);

        $news = new News();
        $service->overWriteNewsPropertiesByIndexObject($news, $index);
        $this->assertSame(true, $news->getDatetime() == $date);
    }
}
