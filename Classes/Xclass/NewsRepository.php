<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */

namespace HDNET\CalendarizeNews\Xclass;

use GeorgRinger\News\Domain\Model\DemandInterface;

/**
 * @todo General class information
 *
 */
class NewsRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository {

	/**
	 * Returns the class name of this class.
	 *
	 * @return string Class name of the repository.
	 */
	protected function getRepositoryClassName() {
		return get_parent_class($this);
	}

	/**
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param DemandInterface $demand
	 * @param boolean         $respectEnableFields
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(DemandInterface $demand, $respectEnableFields = TRUE) {
		$return = parent::findDemanded($demand, $respectEnableFields);
		$query = $return->getQuery();
		$query = $this->objectToObject($query, 'HDNET\\CalendarizeNews\\Persistence\\IndexQuery');
		return $query->execute();
	}

	/**
	 * Convert to another object (sub) type
	 *
	 * @param object $instance
	 * @param string $className
	 *
	 * @return object
	 */
	function objectToObject($instance, $className) {
		return unserialize(sprintf('O:%d:"%s"%s', strlen($className), $className, strstr(strstr(serialize($instance), '"'), ':')));
	}

}