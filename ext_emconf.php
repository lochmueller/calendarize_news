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
    'category'         => 'misc',
    'version'          => '0.3.0',
    'state'            => 'beta',
    'clearcacheonload' => 1,
    'author'           => 'Tim Lochmüller',
    'author_email'     => 'tim@fruit-lab.de',
    'constraints'      => array(
        'depends' => array(
            'typo3'       => '6.2.0-7.99.99',
            'calendarize' => '1.4.1-0.0.0',
            'news'        => '3.2.0-0.0.0',
        ),
    ),
);
