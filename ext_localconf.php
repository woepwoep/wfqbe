<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.wfqbe',
    'Piquery',
    [
        'Query' => 'list,sort',
    ],
    // non-cacheable actions
    [
        'Query' => 'list,sort',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'RedSeadog.wfqbe',
    'Picud',
    [
        'Cud' => 'show,addForm,add,updateForm,update,confirmDelete,delete',
    ],
    // non-cacheable actions
    [
        'Cud' => 'show,addForm,add,updateForm,update,confirmDelete,delete',
    ]
);
