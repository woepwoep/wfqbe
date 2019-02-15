<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

/**
 * Extension key
 */
$extensionKey = 'wfqbe';
$pluginName = $extensionKey.'_pi1';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_domain_model_credentials");

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_domain_model_query");
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords("tx_wfqbe_domain_model_query");

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_domain_model_backend");


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    Array('LLL:EXT:wfqbe/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi1', $pluginName),
    'list_type',
    $extensionKey
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $extensionKey,
    'Configuration/TypoScript',
    "DB Integration"
);


if (TYPO3_MODE === 'BE') {

    $TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_wfqbe_pi1_wizicon"] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . 'Configuration/TypoScript/class.tx_wfqbe_pi1_wizicon.php';

}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Wfqbe',
        'web',
        'web_txfwqbeM1',
        '',
        [
	     'controller' => 'index'
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:rx_extkey/Resources/Public/Icons/mod1_moduleicon.gif',
            'labels' => 'Resources/Private/Language/mod1_locallang_mod.xlf',
        ]
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Wfqbe',
        'web',
        'web_txfwqbeM2',
        '',
        [
	     'controller' => 'index'
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:rx_extkey/Resources/Public/Icons/mod2_moduleicon.gif',
            'labels' => 'Resources/Private/Language/mod2_locallang_mod.xlf',
        ]
    );
}
