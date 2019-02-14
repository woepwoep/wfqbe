<?php

$extensionKey = 'wfqbe';
$pluginName = $extensionKey.'_pi1';

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginName] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginName] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginName,
    'FILE:EXT:'.$extensionKey.'/Configuration/FlexForms/flexform_ds.xml'
);

