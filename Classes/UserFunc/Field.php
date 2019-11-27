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
use \RedSeadog\Wfqbe\Service\FlexformInfoService;


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
	$rows = $this->showColumns($targetTable);
        $fieldList = array();
        $options = [];
        foreach($rows AS $row)
        {
            //$options[] = [$value,$value];
            $options[] = [$row['Field'],$row['Field']];
        }
        //DebugUtility::debug($options);exit(1);
	$config['items'] = $options;
    }

    /**
     * @param array $config
     *
     * @return void
     */
    public function joop(array &$config)
    {
        $targetTable = $config['row']['targetTable'];
	$rows = $this->showColumns($targetTable);
	$itemList = array();
        foreach($rows AS $row)
        {
	    $itemList[] = array($row['Field']);
	}
	$config['items'] = $itemList;
    }

    protected function showColumns($targetTable)
    {
	$rows = array();
	if (!empty($targetTable)) {
            $statement = "SHOW COLUMNS FROM ".$targetTable;
            $sqlService = new SqlService($statement);
	    $rows = $sqlService->getRows();
	}
	return $rows;
    }
}
