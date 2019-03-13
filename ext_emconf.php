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
    'description'      => 'Add Event options to the news extension.',
    'category'         => 'fe',
    'version'          => '4.1.0',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Tim Lochmüller',
    'author_email'     => 'tim@fruit-lab.de',
    'constraints'      => [
        'depends' => [
            'php'         => '7.0.0-0.0.0',
            'typo3'       => '8.7.0-9.5.99',
            'calendarize' => '4.0.0-0.0.0',
            'autoloader'  => '4.0.0-0.0.0',
            'news'        => '7.0.0-7.99.99',
        ],
    ],
];
