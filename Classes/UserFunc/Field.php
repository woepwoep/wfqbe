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
use TYPO3\CMS\Core\Core\Environment;
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
     * @param array $config configuration array
     * @param $parentObject parent object
     * @return array 
     */
    public function getColumnNames(array &$config, &$parentObject)
    {
        $targetTable = $config['row']['targetTable'][0];
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
	
	/* <<<EW>>> */
	/** <<<EW>>> Poging tot een lijst van tables. Gebaseerd op de dataviewer
	/**
	 * Populate flexform tables
	 *
	 * @param array $config Configuration Array
	 * @param array $parentObject Parent Object
	 * @return array
	 */
	public function populateTablesAction(array &$config, &$parentObject)
	{
		$options = [];

		/** $label = Locale::translate("please_select", \RedSeadog\wfqbe\Configuration\ExtensionConfiguration::EXTENSION_KEY); **/
		$label = "Select targetTable";
		$options[] = [0 => $label, 1 => ""];

		$sqlService = new SqlService('SHOW TABLES');
		$tables = $sqlService->getRows();
		
		foreach($tables as $_table)
		{
			$tableName = reset($_table);
			$options[] = [0 => $tableName, 1 => $tableName];
		}
		
		$config["items"] = $options;

		return $config;
	}
	
	/**
	 * Populate flexform fieldlist
	 *
	 * @param array $config Configuration Array
	 * @param array $parentObject Parent Object
	 * @return array
	 */
	public function populateFieldsAction(array &$config, &$parentObject)
	{
		$options = [];

		$label = "--- Please Select field ---";
		$options[] = [0 => $label, 1 => ""];


		$fieldlist = explode(',',$config['flexParentDatabaseRow']['pi_flexform']['data']['database']['lDEF']['fieldlist']['vDEF']);

		foreach($fieldlist as $_field)
		{
			$fieldName = $_field;
			$options[] = [0 => $fieldName, 1 => $fieldName];
		}

		$config["items"] = $options;

		return $config;
	}

	/** Dit zou het moeten zijn..... **/
	
	/**
	 * Display a rendered template from a
	 * given path by parameters -> template
	 *
	 * @param array $config Configuration Array
	 * @param array $parentObject Parent Object
	 * @return array
	 */
	public function displayTemplate(array &$config, &$parentObject)
	{
		$parameters = $config["parameters"];
		$template = (isset($parameters["template"]))?$parameters["template"]:null;
		
		if(!is_null($template))
		{
			$templateFile = GeneralUtility::getFileAbsFileName($template);
			if(file_exists($templateFile))
			{
				/* @var StandaloneView $view */
				//$view = $this->objectManager->get(StandaloneView::class);
				//$view->setTemplatePathAndFilename($templateFile);
				//$view->assignMultiple($parameters);
				//$view->assign("config", $config);
				
				//return $view->render();
				$tekst = "Hier moet de inhoud van een template staan";
				return $tekst;
			}
		}
		
		return;
	}

	/**
	 * @param array $config Configuration Array
	 * @param array $parentObject Parent Object
	 * @return array
	 */
	public function showQueryColumns(array &$config, &$parentObject)
	{
		$statement = $config['flexParentDatabaseRow']['pi_flexform']['data']['database']['lDEF']['query']['vDEF'];

		// remove WHERE part because we need the column names
		$wherepos = strripos($statement,'WHERE');
		if ($wherepos !== FALSE) {
			$newstatement = substr($statement,0,$wherepos);
			$newstatement.= ' WHERE 1=1';
		} else {
			$newstatement = $statement;
		}
		$newstatement.= ' LIMIT 1';

		$sqlService = new SqlService($newstatement);
		$rows = $sqlService->getRows();
		$columnNames = $sqlService->getColumnNamesFromResultRows($rows);
		$options = [];
		foreach($columnNames AS $columnName)
		{
			$options[] = [0 => $columnName, 1 => $columnName];
		}
		$config['items'] = $options;
	}

	/**
	 * Populate flexform fieldtypes
	 *
	 * @param array $config Configuration Array
	 * @param array $parentObject Parent Object
	 * @return array
	 */
	public function populateFieldTypes(array &$config, &$parentObject)
	{
		$options = [];

		$label = "--- Please Select field ---";
		$options[] = [0 => $label, 1 => ""];

		$partialDir = Environment::getPublicPath().'/typo3conf/ext/wfqbe/Resources/Private/Partials/Fieldtypes/';
		$filelist = array_diff(scandir($partialDir), array('..', '.'));

		foreach($filelist as $_file)
		{
			$fileName = basename($_file,'.html');
			$options[] = [0 => $fileName, 1 => $fileName];
		}

		$config["items"] = $options;

		return $config;
	}
}
