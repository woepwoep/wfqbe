<?php

$extensionKey = 'wfqbe';
$pluginName = $extensionKey.'_pi1';

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginName] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginName,
    'FILE:EXT:'.$extensionKey.'/Configuration/FlexForms/flexform_ds.xml'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.Wfqbe',
    'Pi1',
    [
	'controller' => 'index,list,...'
    ],
    [],,
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
