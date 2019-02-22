<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wfqbe_domain_model_query',
    'EXT:wfqbe/Resources/Private/Language/locallang_csh_tx_wfqbe_domain_model_query.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wfqbe_domain_model_query');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wfqbe_domain_model_backend',
    'EXT:wfqbe/Resources/Private/Language/locallang_csh_tx_wfqbe_domain_model_backend.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wfqbe_domain_model_backend');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords("tx_wfqbe_domain_model_query");



if (TYPO3_MODE === 'BE') {

    $TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_wfqbe_query_wizicon"] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('wfqbe') . 'Configuration/TypoScript/class.tx_wfqbe_query_wizicon.php';

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'RedSeadog.wfqbe',
        'web',		// Main area
        'tx_wfqbe_m1',	// Name of the module
        '',		// Position of the module
        [               // Allowed controller action combinations
	     'Backend' => 'list'
        ],
        [               // Additional configuration
            'access' => 'user,group',
            'icon'   => 'EXT:wfqbe/Resources/Public/Icons/mod1_moduleicon.gif',
            'labels' => 'LLL:EXT:wfqbe/Resources/Private/Language/Backend.xlf',
        ]
    );
}
