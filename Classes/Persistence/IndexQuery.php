<?php

namespace HDNET\CalendarizeNews\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Query that returns an index based result (one item per calendarize index).
 */
class IndexQuery extends Query
{
    /**
     * Copy the state of the given (news) query into this query.
     */
    public function initializeFromQuery(Query $query): self
    {
        $this->setType($query->getType());
        $this->setSource($query->getSource());
        $this->setQuerySettings($query->getQuerySettings());
        $this->setParentQuery($query->getParentQuery());
        $this->setOrderings($query->getOrderings());
        if (null !== $query->getConstraint()) {
            $this->matching($query->getConstraint());
        }
        if (null !== $query->getLimit()) {
            $this->setLimit($query->getLimit());
        }
        $this->setOffset((int)$query->getOffset());

        return $this;
    }

    /**
     * @param bool $returnRawQueryResult avoids the object mapping by the persistence
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function execute($returnRawQueryResult = false)
    {
        if ($returnRawQueryResult) {
            return $this->persistenceManager->getObjectDataByQuery($this);
        }

        /** @var IndexResult $queryResult */
        $queryResult = $this->container->get(IndexResult::class);
        $queryResult->setQuery($this);

        return $queryResult;
    }

    /**
     * Query of the news records (without limit and offset) that are relevant for the index selection.
     */
    public function getNewsQuery(): QueryInterface
    {
        $query = clone $this;
        $query->unsetLimit();
        $query->setOffset(0);

        return $query;
    }
}
