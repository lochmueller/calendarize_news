<?php
/**
 * General ext_tables file and also an example for your own extension
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */
defined('TYPO3') or exit();

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'] .= ',calendarize';
