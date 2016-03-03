<?php
/**
 * General ext_localconf file and also an example for your own extension
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */


if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\HDNET\Autoloader\Loader::extLocalconf('HDNET', 'calendarize_news',
    \HDNET\CalendarizeNews\Register::getAutoloaderConfiguration());
\HDNET\Calendarize\Register::extLocalconf(\HDNET\CalendarizeNews\Register::getConfiguration());

$extensionConfiguration = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['calendarize_news']);
if (isset($extensionConfiguration['replaceNewsRepositoryByIndexSelection']) && (bool)$extensionConfiguration['replaceNewsRepositoryByIndexSelection']) {
    $xclasses = [
        'NewsController'     => 'GeorgRinger\\News\\Controller\\NewsController',
        'NewsRepository'     => 'GeorgRinger\\News\\Domain\\Repository\\NewsRepository',
        'NewsLinkViewHelper' => 'GeorgRinger\\News\\ViewHelpers\\LinkViewHelper',
    ];
    foreach ($xclasses as $key => $value) {
        \HDNET\Autoloader\Utility\ExtendedUtility::addXclass($value, 'HDNET\\CalendarizeNews\\Xclass\\' . $key);
    }
}