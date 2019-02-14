<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_credentials");

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_query");
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords("tx_wfqbe_query");

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_wfqbe_backend");

$TCA['tt_content']['types']['list']['subtypes_excludelist']['wfqbe_pi1'] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist']['wfqbe_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('wfqbe_pi1', 'FILE:EXT:wfqbe/flexform_ds.xml');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    Array('LLL:EXT:wfqbe/locallang_db.xml:tt_content.list_type_pi1', 'wfqbe_pi1'),
    'list_type',
    'wfqbe'
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('wfqbe', "pi1/static/", "DB Integration");


if (TYPO3_MODE == 'BE') {

    $TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_wfqbe_pi1_wizicon"] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('wfqbe') . 'pi1/static/class.tx_wfqbe_pi1_wizicon.php';


    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
        'web',
        'txwfqbeM1',
        '',
        '',
        array(
            'routeTarget' => tx_wfqbe_module1::class . '::mainAction',
            'access' => 'user,group',
            'name' => 'web_txwfqbeM1',
            'labels' => array(
                'tabs_images' => array(
                    'tab' => 'EXT:wfqbe/mod1/moduleicon.gif',
                ),
                'll_ref' => 'LLL:EXT:wfqbe/mod1/locallang_mod.xml',
            ),
        )
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
        'web',
        'txwfqbeM2',
        '',
        '',
        array(
            'routeTarget' => tx_wfqbe_module2::class . '::mainAction',
            'access' => 'user,group',
            'name' => 'web_txwfqbeM2',
            'labels' => array(
                'tabs_images' => array(
                    'tab' => 'EXT:wfqbe/mod2/moduleicon.gif',
                ),
                'll_ref' => 'LLL:EXT:wfqbe/mod2/locallang_mod.xml',
            ),
        )
    );
}
