<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
    options.saveDocNew.tx_wfqbe_credentials=1
');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
    options.saveDocNew.tx_wfqbe_query=1
');

## Extending TypoScript from static template uid=43 to set up userdefined tag:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'wfqbe',
    'setup',
    'tt_content.CSS_editor.ch.tx_wfqbe_query = < plugin.tx_wfqbe_query.CSS_editor',
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


$TYPO3_CONF_VARS['BE']['AJAX']['tx_wfqbe_mod1_ajax::fieldTypeHelp'] = 'typo3conf/ext/wfqbe/mod1/class.tx_wfqbe_mod1_ajax.php:tx_wfqbe_mod1_ajax->ajaxFieldTypeHelp';


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:wfqbe/Configuration/PageTS/NewContentElementWizard.ts">'
);

// Configure plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.Wfqbe',
    'Piquery',
        [
                'Query' => 'show'
        ],
        // non-cacheable actions
        [
                'Query' => 'show'
        ]
);
