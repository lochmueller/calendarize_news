<?php

namespace HDNET\CalendarizeNews;

/**
 * Register options.
 */
class Register
{
    /**
     * Get the configuration.
     */
    public static function getConfiguration(): array
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
