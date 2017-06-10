<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Xclass;

use GeorgRinger\News\ViewHelpers\LinkViewHelper;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * @todo General class information
 *
 */
class NewsLinkViewHelper extends LinkViewHelper
{

    /**
     * Render link to news item or internal/external pages
     *
     * @return string link
     */
    public function render()
    {
        try {
            $config = ObjectAccess::getProperty($this->arguments['newsItem'], 'sorting', true);
            if (is_array($config)) {
                $this->arguments['configuration']['additionalParams'] .= '&tx_news_pi1[index]=' . $config['uid'];
            }
        } catch (\Exception $ex) {
        }
        return parent::render();
    }
}
