<?php
/**
 * General ext_tables file and also an example for your own extension
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\HDNET\Autoloader\Loader::extTables('HDNET', 'calendarize_news', \HDNET\CalendarizeNews\Register::getAutoloaderConfiguration());
\HDNET\Calendarize\Register::extTables(\HDNET\CalendarizeNews\Register::getConfiguration());

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] = 'tstamp,datetime,crdate,title,calendarize';