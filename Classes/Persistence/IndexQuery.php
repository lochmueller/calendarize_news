<?php
/**
 * @todo    General file information
 *
 * @author  Tim Lochmüller
 */
namespace HDNET\CalendarizeNews\Persistence;

/**
 * @todo General class information
 *
 */
class IndexQuery extends \TYPO3\CMS\Extbase\Persistence\Generic\Query {

	/**
	 * Executes the query against the database and returns the result
	 *
	 * @param $returnRawQueryResult boolean avoids the object mapping by the persistence
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 * @api
	 */
	public function execute($returnRawQueryResult = FALSE) {
		if ($returnRawQueryResult === TRUE || $this->getQuerySettings()
				->getReturnRawQueryResult() === TRUE
		) {
			return $this->persistenceManager->getObjectDataByQuery($this);
		} else {
			return $this->objectManager->get('HDNET\\CalendarizeNews\\Persistence\\IndexResult', $this);
		}
	}

}