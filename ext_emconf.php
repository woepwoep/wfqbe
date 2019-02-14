<?php

########################################################################
# Extension Manager/Repository config file for ext "wfqbe".
#
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'DB Integration',
    'description' => 'This extension allows to generate queries (with a little sql knowledge), search forms and insert forms to generic databases through a wizard. The results visualization is template-based and fully configurable via TS.',
    'category' => 'plugin',
    'version' => '9.5.4',
    'state' => 'alpha',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Mauro Lorenzutti',
    'author_email' => 'support@webformat.com',
    'author_company' => 'WEBFORMAT Srl',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'php' => '7.2.0-7.2.99',
                    'typo3' => '9.5.4-9.5.499',
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
);
