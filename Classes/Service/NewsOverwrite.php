<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Service;

use GeorgRinger\News\Domain\Model\News;
use HDNET\Autoloader\SingletonInterface;
use HDNET\Calendarize\Domain\Model\Index;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * @todo General class information
 */
class NewsOverwrite implements SingletonInterface
{
    /**
     * @param News  $news
     * @param Index $index
     */
    public function overWriteNewsPropertiesByIndex(News $news, Index $index)
    {
        ObjectAccess::setProperty(
            $news,
            'datetime',
            $index->getStartDateComplete(),
            true
        );
        ObjectAccess::setProperty($news, 'sorting', $index, true);
        if (ExtensionManagementUtility::isLoaded('eventnews')) {
            ObjectAccess::setProperty($news, 'isEvent', true, true);
            ObjectAccess::setProperty(
                $news,
                'eventEnd',
                $index->getEndDateComplete(),
                true
            );
            ObjectAccess::setProperty($news, 'fullDay', $index->isAllDay(), true);
        }
    }
}
