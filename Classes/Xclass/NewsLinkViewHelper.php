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

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('index', 'int', 'index configuration', false, 0);
    }

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
            if ($this->arguments['index'] > 0) {
                $this->arguments['configuration']['additionalParams'] .= '&tx_news_pi1[index]=' . $this->arguments['index'];
            }
        } catch (\Exception $ex) {
        }
        return parent::render();
    }
}
