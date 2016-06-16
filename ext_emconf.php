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
    'version'          => '1.0.0',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Tim Lochmüller',
    'author_email'     => 'tim@fruit-lab.de',
    'constraints'      => [
        'depends' => [
            'php'         => '5.5.0-0.0.0',
            'typo3'       => '7.6.0-8.1.99',
            'calendarize' => '2.2.0-0.0.0',
            'news'        => '4.0.0-0.0.0',
        ],
    ],
];
