<?php
/**
 * Register options
 *
 * @package HDNET\CalendarizeNews
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews;

/**
 * Register options
 *
 * @author Tim Lochmüller
 */

class Register {

	/**
	 * Get the autoloader configuration
	 *
	 * @return array
	 */
	static public function getAutoloaderConfiguration() {
		return array(
			'StaticTyposcript',
			'Xclass'
		);
	}

	/**
	 * Get the configuration
	 *
	 * @return array
	 */
	static public function getConfiguration() {
		return array(
			'uniqueRegisterKey' => 'News',
			'title'             => 'News Event',
			'modelName'         => 'GeorgRinger\\News\\Domain\\Model\\News',
			'partialIdentifier' => 'News',
			'tableName'         => 'tx_news_domain_model_news',
			'required'          => FALSE,
		);
	}

}