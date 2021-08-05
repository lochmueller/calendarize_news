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

\HDNET\Autoloader\Loader::extLocalconf(
    'HDNET',
    'calendarize_news',
    \HDNET\CalendarizeNews\Register::getAutoloaderConfiguration()
);
\HDNET\Calendarize\Register::extLocalconf(\HDNET\CalendarizeNews\Register::getConfiguration());

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get('calendarize_news');

$xclasses = [
    \HDNET\CalendarizeNews\Xclass\NewsLinkViewHelper::class => \GeorgRinger\News\ViewHelpers\LinkViewHelper::class,
];
if (isset($extensionConfiguration['replaceNewsRepositoryByIndexSelection']) && (bool)$extensionConfiguration['replaceNewsRepositoryByIndexSelection']) {
    $xclasses[\HDNET\CalendarizeNews\Xclass\NewsController::class] = \GeorgRinger\News\Controller\NewsController::class;
    $xclasses[\HDNET\CalendarizeNews\Xclass\NewsRepository::class] = \GeorgRinger\News\Domain\Repository\NewsRepository::class;
}
foreach ($xclasses as $key => $value) {
    \HDNET\Autoloader\Utility\ExtendedUtility::addXclass($value, $key);
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][\HDNET\CalendarizeNews\Hooks\FlexFormHook::class] = \HDNET\CalendarizeNews\Hooks\FlexFormHook::class;
