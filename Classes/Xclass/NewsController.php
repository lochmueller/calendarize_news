<?php

namespace HDNET\CalendarizeNews\Xclass;

use HDNET\Calendarize\Domain\Model\Index;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMapper;

class NewsController extends \GeorgRinger\News\Controller\NewsController
{
    /**
     * @var Index
     */
    protected $index;

    public function initializeDetailAction()
    {
        if ($this->request->hasArgument('index')) {
            $index = $this->request->getArgument('index');
            $this->index = GeneralUtility::makeInstance(PropertyMapper::class)
                ->convert($index, Index::class);
        }
    }

    /**
     * Single view of a news record.
     *
     * @param \GeorgRinger\News\Domain\Model\News $news        news item
     * @param int                                 $currentPage current page for optional pagination
     */
    public function detailAction(?\GeorgRinger\News\Domain\Model\News $news = null, $currentPage = 1): ResponseInterface
    {
        $result = parent::detailAction($news, $currentPage);

        if (null !== $news && null !== $this->index) {
            /** @var NewsOverwrite $overwriteService */
            $overwriteService = GeneralUtility::makeInstance(NewsOverwrite::class);
            $overwriteService->overWriteNewsPropertiesByIndex($news, $this->index);
        }

        return $result;
    }
}
