<?php
namespace RedSeadog\Wfqbe\UserFunc;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\DebugUtility;
use \RedSeadog\Wfqbe\Service\SqlService;


/**
 * QueryController
 *
 * @author Ronald Wopereis <woepwoep@gmail.com>
 */
class Field
{

    /**
     ** @param array $config configuration array
     ** @param $parentObject parent object
     ** @return array 
     */
    public function getColumnNames(array &$config, &$parentObject)
    {
        $targetTable = $config['row']['targetTable'];
        $statement = "SELECT * FROM ".$targetTable." LIMIT 1";
        $sqlService = new SqlService($statement);
        $fieldList=$sqlService->getColumnNames();
        $options = [];
        foreach($fieldList AS $name => $value)
        {
            $options[] = [$value,$value];
        }
        //DebugUtility::debug($options);exit(1);
	$config['items'] = $options;
    }
}
