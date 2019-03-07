<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
    options.saveDocNew.tx_wfqbe_domain_model_query=1
');

## Extending TypoScript from static template uid=43 to set up userdefined tag:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'wfqbe',
    'setup',
    'tt_content.CSS_editor.ch.tx_wfqbe_domain_model_query = < plugin.tx_wfqbe_domain_model_query.CSS_editor',
    43
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.Wfqbe',
    'Piquery',
    [
        'Query' => 'show',
    ],
    // non-cacheable actions
    [
        'Query' => 'show',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.Wfqbe',
    'Picud',
    [
        'Cud' => 'detail,edit',
    ],
    // non-cacheable actions
    [
        'Cud' => 'detail,edit',
    ]
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:wfqbe/Configuration/PageTS/NewContentElementWizard.ts">'
);
