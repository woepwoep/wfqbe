<?php

########################################################################
# Extension Manager/Repository config file for ext "wfqbe".
#
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF['wfqbe'] = array(
    'title' => 'DB Integration',
    'description' => 'This extension is based on the DB Integration (wfqbe) extension. It uses raw queries only, with Fluid for templating.'
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
