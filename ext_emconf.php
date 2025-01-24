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
            'typo3' => '12.4.0-13.4.99',
            'php' => '8.1.0-8.4.99',
            'calendarize' => '13.0.0-14.99.99',
            'news' => '12.0.0-12.99.99',
        ],
    ],
];
