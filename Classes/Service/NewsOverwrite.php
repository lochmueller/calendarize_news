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
	 * @param News  $news
	 * @param array $index
	 */
	public function overWriteNewsPropertiesByIndexArray(News $news, array $index) {
		ObjectAccess::setProperty($news, 'datetime', new \DateTime('@' . $index['start_date']));
		ObjectAccess::setProperty($news, 'sorting', $index, TRUE);
		if (ExtensionManagementUtility::isLoaded('eventnews')) {
			ObjectAccess::setProperty($news, 'isEvent', TRUE, TRUE);
			ObjectAccess::setProperty($news, 'eventEnd', new \DateTime('@' . $index['end_date']), TRUE);
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
			'all_day'    => $index->isAllDay(),
		);
		$this->overWriteNewsPropertiesByIndexArray($news, $array);
	}
}