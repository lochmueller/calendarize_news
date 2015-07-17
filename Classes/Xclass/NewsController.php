<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Xclass;

use HDNET\Calendarize\Domain\Model\Index;
use HDNET\Calendarize\Utility\HelperUtility;
use HDNET\CalendarizeNews\Service\NewsOverwrite;

/**
 * @todo General class information
 *
 */
class NewsController extends \GeorgRinger\News\Controller\NewsController {

	/**
	 * Single view of a news record
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news        news item
	 * @param Index                               $index
	 * @param integer                             $currentPage current page for optional pagination
	 *
	 */
	public function detailAction(\GeorgRinger\News\Domain\Model\News $news = NULL, \HDNET\Calendarize\Domain\Model\Index $index = NULL, $currentPage = 1) {
		parent::detailAction($news, $currentPage);

		if ($index !== NULL) {
			/** @var NewsOverwrite $overwriteService */
			$overwriteService = HelperUtility::create('HDNET\\CalendarizeNews\\Service\\NewsOverwrite');
			$overwriteService->overWriteNewsPropertiesByIndexObject($news, $index);
		}
	}

}