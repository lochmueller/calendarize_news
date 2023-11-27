<?php
/**
 * Register options.
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews;

/**
 * Register options.
 *
 * @author Tim Lochmüller
 */
class Register
{
    /**
     * Get the configuration.
     *
     * @return array
     */
    public static function getConfiguration()
    {
        return [
            'uniqueRegisterKey' => 'News',
            'title' => 'News Event',
            'modelName' => \GeorgRinger\News\Domain\Model\News::class,
            'partialIdentifier' => 'News',
            'tableName' => 'tx_news_domain_model_news',
            'required' => false,
        ];
    }
}
