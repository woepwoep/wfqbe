<?php

########################################################################
# Extension Manager/Repository config file for ext "wfqbe".
#
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF['wfqbe'] = [
    'title' => 'DB Integration for TYPO3 v9',
    'description' => 'This extension is based on the DB Integration (wfqbe) extension using TYPO3 v9 standards.',
    'category' => 'plugin',
    'version' => '9.5.5',
    'state' => 'alpha',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Ronald Wopereis',
    'author_email' => 'woepwoep@gmail.com',
    'author_company' => 'Red-Seadog',
    'constraints' => [
        'depends' => [
            'php' => '7.2.0-7.2.99',
            'typo3' => '9.5.5-9.5.599',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
