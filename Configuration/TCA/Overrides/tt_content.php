<?php

/**
 * Include FlexForm of plugin _pi1 of extension EXT:wfqbe
 */
$extensionKey = 'wfqbe';
$pluginName = $extensionKey.'_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginName] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginName] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginName,
    'FILE:EXT:'.$extensionKey.'/Configuration/FlexForms/Flexform_pi1.xml'
);
