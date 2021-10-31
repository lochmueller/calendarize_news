<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Xclass;

use GeorgRinger\News\ViewHelpers\LinkViewHelper;
use HDNET\Calendarize\Domain\Model\Index;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * @todo General class information
 */
class NewsLinkViewHelper extends LinkViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('index', 'int', 'index configuration', false, 0);
    }

    /**
     * Render link to news item or internal/external pages.
     *
     * @return string link
     */
    public function render(): ?string
    {
        try {
            $index = ObjectAccess::getProperty($this->arguments['newsItem'], 'sorting', true);
            if ($index instanceof Index) {
                $this->arguments['configuration']['additionalParams'] .= '&tx_news_pi1[index]=' . $index->getUid();
            }
            if ($this->arguments['index'] > 0) {
                $this->arguments['configuration']['additionalParams'] .= '&tx_news_pi1[index]=' . $this->arguments['index'];
            }
        } catch (\Exception $ex) {
        }

        return parent::render();
    }
}
