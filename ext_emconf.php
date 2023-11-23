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
    'version' => '7.1.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Tim Lochmüller',
    'author_email' => 'tim@fruit-lab.de',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.6-12.4.99',
            'php' => '7.4.0-8.2.99',
            'calendarize' => '10.0.0-13.99.99',
            'news' => '8.5.0-11.99.99',
        ],
    ],
];
