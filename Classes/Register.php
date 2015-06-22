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
	 * Get the configuration
	 *
	 * @return array
	 */
	static function getConfiguration() {
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