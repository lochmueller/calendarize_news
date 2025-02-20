<?php

namespace HDNET\CalendarizeNews\Service;

use GeorgRinger\News\Domain\Model\News;
use HDNET\Calendarize\Domain\Model\Index;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

class NewsOverwrite implements SingletonInterface
{
    public function overWriteNewsPropertiesByIndex(News $news, Index $index)
    {
        ObjectAccess::setProperty(
            $news,
            'datetime',
            $index->getStartDateComplete(),
        );
        ObjectAccess::setProperty($news, 'sorting', $index);
        if (ExtensionManagementUtility::isLoaded('eventnews')) {
            ObjectAccess::setProperty($news, 'isEvent', true);
            ObjectAccess::setProperty(
                $news,
                'eventEnd',
                $index->getEndDateComplete(),
            );
            ObjectAccess::setProperty($news, 'fullDay', $index->isAllDay());
        }
    }
}
