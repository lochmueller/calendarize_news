<?php
/**
 * $EM_CONF
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */


$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Calendarize for News',
    'description'      => 'Add Event options to the news extension',
    'category'         => 'fe',
    'version'          => '0.3.1',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Tim Lochmüller',
    'author_email'     => 'tim@fruit-lab.de',
    'constraints'      => array(
        'depends' => array(
            'typo3'       => '6.2.0-7.99.99',
            'calendarize' => '1.4.1-0.0.0',
            'news'        => '4.0.0-0.0.0',
        ),
    ),
);
