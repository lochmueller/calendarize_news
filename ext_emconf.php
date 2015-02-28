<?php
/**
 * $EM_CONF
 *
 * @category   Extension
 * @package    Calendarize
 * @author     Tim Lochmüller
 */


$EM_CONF['calendarize_news'] = array(
	'title'            => 'Calendarize for News',
	'description'      => 'Add Event options to the news extension',
	'category'         => 'misc',
	'version'          => '0.1.0',
	'state'            => 'alpha',
	'clearcacheonload' => 1,
	'author'           => 'Tim Lochmüller',
	'author_email'     => 'tim@fruit-lab.de',
	'constraints'      => array(
		'depends' => array(
			'calendarize' => '1.2.0-0.0.0',
			'news'        => '3.0.0-0.0.0',
		),
	),
);
