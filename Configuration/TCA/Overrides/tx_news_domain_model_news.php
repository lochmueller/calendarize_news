<?php

defined('TYPO3') or exit();

// Add an own "Calendarize" tab, so the calendarize fields are not placed in the "Extended" tab of news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Calendarize'
);

\HDNET\Calendarize\Register::createTcaConfiguration(\HDNET\CalendarizeNews\Register::getConfiguration());
