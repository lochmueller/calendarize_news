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
use HDNET\Calendarize\Utility\DateTimeUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * @todo General class information
 *
 */
class NewsOverwrite implements SingletonInterface
{

    /**
     * @param News $news
     * @param array $index
     */
    public function overWriteNewsPropertiesByIndexArray(News $news, array $index)
    {
        ObjectAccess::setProperty(
            $news,
            'datetime',
            $this->getCombinedTimeAsDatetime($index['start_date'], $index['start_time'])
        );
        ObjectAccess::setProperty($news, 'sorting', $index, true);
        if (ExtensionManagementUtility::isLoaded('eventnews')) {
            ObjectAccess::setProperty($news, 'isEvent', true, true);
            ObjectAccess::setProperty(
                $news,
                'eventEnd',
                $this->getCombinedTimeAsDatetime($index['end_date'], $index['end_time']),
                true
            );
            ObjectAccess::setProperty($news, 'fullDay', (bool)$index['all_day'], true);
        }
    }

    /**
     * @param News $news
     * @param Index $index
     */
    public function overWriteNewsPropertiesByIndexObject(News $news, Index $index)
    {
        $array = [
            'uid' => $index->getUid(),
            'start_date' => $index->getStartDate()->getTimestamp(),
            'end_date' => $index->getEndDate()->getTimestamp(),
            'start_time' => $index->isAllDay() ? 0 : $index->getStartTime(),
            'end_time' => $index->isAllDay() ? 0 : $index->getEndTime(),
            'all_day' => $index->isAllDay(),
        ];
        $this->overWriteNewsPropertiesByIndexArray($news, $array);
    }

    /**
     * Get combined time as datetime
     *
     * - combine both values
     *
     * @param int $dateTimestamp
     * @param int $timeTimestamp
     *
     * @return \DateTime
     */
    protected function getCombinedTimeAsDatetime($dateTimestamp, $timeTimestamp)
    {
        $time = DateTimeUtility::normalizeDateTimeSingle($timeTimestamp);
        $date = new \DateTime(MathUtility::canBeInterpretedAsInteger($dateTimestamp) ? '@' : '' . $dateTimestamp);
        return \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $time->format('H:i'));
    }
}
