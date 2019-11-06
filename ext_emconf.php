<?php
/**
 * $EM_CONF
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */


$EM_CONF[$_EXTKEY] = [
    'title' => 'Calendarize for News',
    'description' => 'Add Event options to the news extension.',
    'category' => 'fe',
    'version' => '4.1.1',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Tim Lochmüller',
    'author_email' => 'tim@fruit-lab.de',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.13-9.5.99',
            'php' => '7.0.0-0.0.0',
            'calendarize' => '4.2.0-0.0.0',
            'autoloader' => '6.0.0-0.0.0',
            'news' => '7.2.0-7.99.99',
        ],
    ],
];
