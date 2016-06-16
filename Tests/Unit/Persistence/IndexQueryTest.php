<?php
/**
 * IndexQueryTest
 */

namespace HDNET\CalendarizeNews\Tests\Unit\Persistence;

use HDNET\CalendarizeNews\Persistence\IndexQuery;
use HDNET\CalendarizeNews\Persistence\IndexResult;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * IndexQueryTest
 */
class IndexQueryTest extends UnitTestCase
{

    /**
     * @test
     */
    public function testValidResultOfExecute()
    {
        $objectManager = new ObjectManager();
        $query = $objectManager->get(IndexQuery::class, 'test');
        $this->assertSame(true, $query->execute() instanceof IndexResult);
    }
}
