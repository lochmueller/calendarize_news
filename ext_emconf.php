<?php
/**
 * $EM_CONF
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */


$EM_CONF[$_EXTKEY] = [
    'title'            => 'Calendarize for News',
    'description'      => 'Add Event options to the news extension',
    'category'         => 'fe',
    'version'          => '2.0.0',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Tim Lochmüller',
    'author_email'     => 'tim@fruit-lab.de',
    'constraints'      => [
        'depends' => [
            'php'         => '7.0.0-0.0.0',
            'typo3'       => '7.6.0-8.7.99',
            'calendarize' => '3.1.0-0.0.0',
            'news'        => '6.0.0-0.0.0',
        ],
    ],
];
