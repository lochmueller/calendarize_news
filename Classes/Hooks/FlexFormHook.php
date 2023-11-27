<?php

declare(strict_types=1);

namespace HDNET\CalendarizeNews\Hooks;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @deprecated Remove when dropping TYPO3 v11
 */
class FlexFormHook
{
    /**
     * Returns a modified flexform data array.
     *
     * This adds the list of existing form definitions to the form selection drop down
     * and adds sheets to override finisher settings if requested.
     *
     * @param array $dataStructure
     * @param array $identifier
     *
     * @return array
     */
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
    {
        if ('tca' === $identifier['type'] && 'tt_content' === $identifier['tableName'] && 'calendarize_calendar,list' === $identifier['dataStructureKey']) {
            $file = GeneralUtility::getFileAbsFileName('EXT:calendarize_news/Configuration/FlexForms/Calendar.xml');
            $content = file_get_contents($file);
            if ($content) {
                $overwrite = GeneralUtility::xml2array($content);
                if (\is_array($overwrite)) {
                    ArrayUtility::mergeRecursiveWithOverrule($dataStructure, $overwrite);
                }
            }
        }

        return $dataStructure;
    }
}
