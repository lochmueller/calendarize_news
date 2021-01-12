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
    'version' => '5.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Tim Lochmüller',
    'author_email' => 'tim@fruit-lab.de',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.6-10.4.99',
            'php' => '7.2.0-7.4.99',
            'calendarize' => '7.0.0-7.99.99',
            'autoloader' => '7.0.0-7.99.99',
            'news' => '8.4.0-8.99.99',
        ],
    ],
];
