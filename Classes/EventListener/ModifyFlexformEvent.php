<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\EventListener;

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This adds additional flexform configurations to calendarize.
 * In this case a field for the news PID.
 */
final class ModifyFlexformEvent
{
    public function __invoke(AfterFlexFormDataStructureParsedEvent $event)
    {
        $identifier = $event->getIdentifier();
        if (
            'tca' === ($identifier['type'] ?? '')
            && 'tt_content' === ($identifier['tableName'] ?? '')
            && str_starts_with($identifier['dataStructureKey'] ?? '', 'calendarize_')
        ) {
            $file = GeneralUtility::getFileAbsFileName('EXT:calendarize_news/Configuration/FlexForms/Calendar.xml');
            $content = file_get_contents($file);
            if ($content) {
                $overwrite = GeneralUtility::xml2array($content);
                // Deprecated: remove when dropping TYPO3 v11 support and remove TCEforms & internal_type in Calendar.xml
                $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
                $overwrite = $flexFormTools->removeElementTceFormsRecursive($overwrite);

                if (\is_array($overwrite)) {
                    $parsedDataStructure = $event->getDataStructure();
                    ArrayUtility::mergeRecursiveWithOverrule($parsedDataStructure, $overwrite);
                    $event->setDataStructure($parsedDataStructure);
                }
            }
        }
    }
}
