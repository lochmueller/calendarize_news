<?php

namespace HDNET\CalendarizeNews\EventListener;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Event\NewsDetailActionEvent;
use HDNET\Calendarize\Domain\Model\Index;
use HDNET\CalendarizeNews\Service\NewsOverwrite;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Property\PropertyMapper;

/**
 * Overwrite the date information of the news detail view by the given calendarize index.
 */
final class NewsDetailActionListener
{
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly PropertyMapper $propertyMapper,
        private readonly NewsOverwrite $newsOverwrite,
    ) {
    }

    public function __invoke(NewsDetailActionEvent $event): void
    {
        if (!$this->isIndexSelectionEnabled()) {
            return;
        }

        $request = $event->getRequest();
        $assignedValues = $event->getAssignedValues();
        $news = $assignedValues['newsItem'] ?? null;
        if (!$news instanceof News || !$request->hasArgument('index')) {
            return;
        }

        try {
            $index = $this->propertyMapper->convert($request->getArgument('index'), Index::class);
        } catch (\Exception) {
            return;
        }
        if (!$index instanceof Index
            || 'tx_news_domain_model_news' !== $index->getForeignTable()
            || $index->getForeignUid() !== $news->getUid()
        ) {
            return;
        }

        $customNews = clone $news;
        $this->newsOverwrite->overWriteNewsPropertiesByIndex($customNews, $index);
        $assignedValues['newsItem'] = $customNews;
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
