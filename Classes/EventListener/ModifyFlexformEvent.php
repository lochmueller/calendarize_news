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
    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
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
                $overwrite = $this->removeElementTceFormsRecursive($overwrite);

                if (\is_array($overwrite)) {
                    $parsedDataStructure = $event->getDataStructure();
                    ArrayUtility::mergeRecursiveWithOverrule($parsedDataStructure, $overwrite);
                    $event->setDataStructure($parsedDataStructure);
                }
            }
        }
    }

    /**
     * Remove "TCEforms" key from all elements in data structure to simplify further parsing.
     *
     * Example config:
     * ['config']['ds']['sheets']['sDEF']['ROOT']['el']['anElement']['TCEforms']['label'] becomes
     * ['config']['ds']['sheets']['sDEF']['ROOT']['el']['anElement']['label']
     *
     * and
     *
     * ['ROOT']['TCEforms']['sheetTitle'] becomes
     * ['ROOT']['sheetTitle']
     *
     * @internal This method serves as a compatibility layer and will be removed in TYPO3 v13.
     */
    public function removeElementTceFormsRecursive(array $structure): array
    {
        $newStructure = [];
        foreach ($structure as $key => $value) {
            if ($key === 'ROOT' && is_array($value) && isset($value['TCEforms'])) {
                trigger_error(
                    'The tag "<TCEforms>" should not be set under the FlexForm definition "<ROOT>" anymore. It should be omitted while the underlying configuration ascends one level up. This compatibility layer will be removed in TYPO3 v13.',
                    E_USER_DEPRECATED
                );
                $value = array_merge($value, $value['TCEforms']);
                unset($value['TCEforms']);
            }
            if ($key === 'el' && is_array($value)) {
                $newSubStructure = [];
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue) && count($subValue) === 1 && isset($subValue['TCEforms'])) {
                        trigger_error(
                            'The tag "<TCEforms>" was found in a FlexForm definition for the field "<' . $subKey . '>". It should be omitted while the underlying configuration ascends one level up. This compatibility layer will be removed in TYPO3 v13.',
                            E_USER_DEPRECATED
                        );
                        $newSubStructure[$subKey] = $subValue['TCEforms'];
                    } else {
                        $newSubStructure[$subKey] = $subValue;
                    }
                }
                $value = $newSubStructure;
            }
            if (is_array($value)) {
                $value = $this->removeElementTceFormsRecursive($value);
            }
            $newStructure[$key] = $value;
        }
        return $newStructure;
    }
}
