<?php
defined('TYPO3_MODE') or die();

/**
 * Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'wfqbe',
    'Pievent',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/Plugin.xlf:plugin.title'
);

/**
 * Remove unused fields
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['wfqbe_piquery'] = 'layout,select_key,pages,recursive';

/**
 * Add Flexform for query plugin
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['wfqbe_piquery'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'wfqbe_piquery',
    'FILE:EXT:wfqbe/Configuration/FlexForms/Flexform_plugin.xml'
);

/**
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'wfqbe',
    'Configuration/TypoScript',
    'DB iNtegration'
);
