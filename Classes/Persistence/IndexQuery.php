<?php

namespace HDNET\CalendarizeNews\Persistence;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;

class IndexQuery extends Query
{
    /**
     * Executes the query against the database and returns the result.
     *
     * @param bool $returnRawQueryResult avoids the object mapping by the persistence
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
     *
     * @api
     */
    public function execute($returnRawQueryResult = false)
    {
        $querySettings = $this->getQuerySettings();
        $deprecatedRawResult = method_exists(
            $querySettings,
            'getReturnRawQueryResult'
        ) && true === $querySettings->getReturnRawQueryResult();
        if (true === $returnRawQueryResult || $deprecatedRawResult) {
            return $this->persistenceManager->getObjectDataByQuery($this);
        }

        return GeneralUtility::makeInstance(\HDNET\CalendarizeNews\Persistence\IndexResult::class, $this);
    }
}
