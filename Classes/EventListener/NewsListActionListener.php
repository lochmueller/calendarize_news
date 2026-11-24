<?php

namespace HDNET\CalendarizeNews\EventListener;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Event\NewsListActionEvent;
use HDNET\CalendarizeNews\Persistence\IndexQuery;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Replace the news selection of the list view by an index based selection
 * (one item per calendarize index), if the ordering starts with "calendarize".
 */
final class NewsListActionListener
{
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
    ) {
    }

    public function __invoke(NewsListActionEvent $event): void
    {
        if (!$this->isIndexSelectionEnabled()) {
            return;
        }

        $assignedValues = $event->getAssignedValues();
        $demand = $assignedValues['demand'] ?? null;
        $news = $assignedValues['news'] ?? null;
        if (!$demand instanceof NewsDemand || !$news instanceof QueryResultInterface) {
            return;
        }
        if (!str_starts_with($demand->getOrder(), 'calendarize')) {
            return;
        }

        $query = $news->getQuery();
        if (!$query instanceof Query || $query instanceof IndexQuery) {
            return;
        }

        $assignedValues['news'] = GeneralUtility::makeInstance(IndexQuery::class)
            ->initializeFromQuery($query)
            ->execute();
        $event->setAssignedValues($assignedValues);
    }

    private function isIndexSelectionEnabled(): bool
    {
        try {
            return (bool)$this->extensionConfiguration->get('calendarize_news', 'replaceNewsRepositoryByIndexSelection');
        } catch (\Exception) {
            return false;
        }
    }
}
