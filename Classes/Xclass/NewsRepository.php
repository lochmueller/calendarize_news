<?php

namespace HDNET\CalendarizeNews\Xclass;

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;


class NewsRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository
{
    /**
     * Returns the class name of this class.
     *
     * @return string class name of the repository
     */
    protected function getRepositoryClassName()
    {
        return get_parent_class($this);
    }

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param DemandInterface $demand
     * @param bool            $respectEnableFields
     * @param bool            $disableLanguageOverlayMode
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true, $disableLanguageOverlayMode = false)
    {
        $return = parent::findDemanded($demand, $respectEnableFields, $disableLanguageOverlayMode);
        if (!($demand instanceof NewsDemand)) {
            return $return;
        }
        if (!str_starts_with($demand->getOrder(), 'calendarize')) {
            return $return;
        }
        $query = $return->getQuery();
        $query = $this->objectToObject($query, \HDNET\CalendarizeNews\Persistence\IndexQuery::class);

        return $query->execute();
    }

    /**
     * Convert to another object (sub) type.
     */
    public function objectToObject(object $instance, string $className): object
    {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            \strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }
}
