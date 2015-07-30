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
 *
 */
class NewsOverwrite implements SingletonInterface {

	/**
	 * Day seconds
	 *
	 * @var int
	 */
	const DAY = 86400;

	/**
	 * @param News  $news
	 * @param array $index
	 */
	public function overWriteNewsPropertiesByIndexArray(News $news, array $index) {
		ObjectAccess::setProperty($news, 'datetime', new \DateTime('@' . $this->getCombinedTime($index['start_date'], $index['start_time'])));
		ObjectAccess::setProperty($news, 'sorting', $index, TRUE);
		if (ExtensionManagementUtility::isLoaded('eventnews')) {
			ObjectAccess::setProperty($news, 'isEvent', TRUE, TRUE);
			ObjectAccess::setProperty($news, 'eventEnd', new \DateTime('@' . $this->getCombinedTime($index['end_date'], $index['end_time'])), TRUE);
			ObjectAccess::setProperty($news, 'fullDay', (bool)$index['all_day'], TRUE);
		}
	}

	/**
	 * @param News  $news
	 * @param Index $index
	 */
	public function overWriteNewsPropertiesByIndexObject(News $news, Index $index) {
		$array = array(
			'uid'        => $index->getUid(),
			'start_date' => $index->getStartDate()
				->getTimestamp(),
			'end_date'   => $index->getEndDate()
				->getTimestamp(),
			'start_time' => $index->isAllDay() ? 0 : $index->getStartTime(),
			'end_time'   => $index->isAllDay() ? 0 : $index->getEndTime(),
			'all_day'    => $index->isAllDay(),
		);
		$this->overWriteNewsPropertiesByIndexArray($news, $array);
	}

	/**
	 * Get combined time
	 *
	 * - Remove the time of the date information
	 * - Remove the date if the time information
	 * - combine both values
	 *
	 * @param int $dateTimestamp
	 * @param int $timeTimestamp
	 *
	 * @return int
	 */
	protected function getCombinedTime($dateTimestamp, $timeTimestamp) {
		$dateTimestamp = floor($dateTimestamp / self::DAY) * self::DAY;
		if ($timeTimestamp <= 0) {
			return $dateTimestamp;
		}
		if ($timeTimestamp < self::DAY) {
			return $dateTimestamp + $timeTimestamp;
		}
		return $dateTimestamp + ($timeTimestamp % self::DAY);
	}
}