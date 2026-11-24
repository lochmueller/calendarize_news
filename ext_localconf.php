<?php
/**
 * General ext_localconf file and also an example for your own extension
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */

defined('TYPO3') or exit();

\HDNET\Calendarize\Register::extLocalconf(\HDNET\CalendarizeNews\Register::getConfiguration());

$xclasses = [
    \HDNET\CalendarizeNews\Xclass\NewsLinkViewHelper::class => \GeorgRinger\News\ViewHelpers\LinkViewHelper::class,
];
foreach ($xclasses as $target => $source) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$source] = [
        'className' => $target,
    ];
}
