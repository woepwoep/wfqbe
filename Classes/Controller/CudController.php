<?php
namespace RedSeadog\Wfqbe\Controller;

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
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use \RedSeadog\Wfqbe\Service\PluginService;
use \RedSeadog\Wfqbe\Service\FlexformInfoService;
use \RedSeadog\Wfqbe\Service\SqlService;


/**
 * CudController
 *
 * @author Ronald Wopereis <woepwoep@gmail.com>
 */
class CudController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Configuration Manager
     *
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var \RedSeadog\Wfqbe\Service\PluginService
     */
    protected $pluginSettings;

    /**
     * $ffdata
     */
    protected $ffdata;

    /**
     * $targetTable -- required in all CRUD actions
     */
    protected $targetTable;

    /**
     * $keyField -- required in all CRUD actions
     */
    protected $keyField;

    /**
     * $fieldlist -- optional in all CRUD actions
     */
    protected $fieldlist;

    /**
     * $templateFile -- optional in all CRUD actions
     */
    protected $templateFile;



    public function __construct()
    {
        $this->pluginSettings = new PluginService('tx_wfqbe');

        // retrieve the tablename and the keyfield(s) from the flexform
        $flexformInfoService = new FlexformInfoService();
	$this->targetTable = $flexformInfoService->getTargetTable();
        $this->keyField = $flexformInfoService->getKeyField();
        $this->fieldlist = $flexformInfoService->getFieldlist();
	$this->templateFile = $flexformInfoService->getTemplateFile();

	// retrieve the other required information from the flexform
        $this->ffdata = $flexformInfoService->getData();

    }


    /**
     * Show the chosen query result row.
     */
    public function showAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // retrieve the {keyValue} from Fluid
        $parameter = 'keyValue';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('showAction: Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$keyValue = $this->request->getArgument($parameter);

        // execute the query
        $statement = "select ".$this->fieldlist." from ".$this->targetTable." wHEre ".$this->keyField."='".$keyValue."'";
        $sqlService = new SqlService($statement);

        $rows = $sqlService->getRows();
        $flexformInfoService = new FlexformInfoService();
        $columnNames = $flexformInfoService->mergeFieldTypes();

            //DebugUtility::debug($statement,'showAction statement');exit(1);
        // assign the results in a view for fluid Query/Show.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'keyValue' => $keyValue,
            'statement' => $statement,
            'columnNames' => $columnNames,
            'row' => $rows[0],
            'request' => $this->request,
        ]);
    }

    /**
     * Request the values for a new row in the targettable
     */
    public function addFormAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        $flexformInfoService = new FlexformInfoService();
        $columnNames = $flexformInfoService->mergeFieldTypes();

        // assign the results in a view for fluid Query/Show.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'columnNames' => $columnNames,
            'request' => $this->request,
        ]);
    }

    /**
     * Insert a new row into the targettable
     */
    public function addAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        $sqlService = new SqlService('Show columns for '.$this->targetTable);
        $flexformInfoService = new FlexformInfoService();
        $columnNames = $flexformInfoService->mergeFieldTypes();

        // build an insert statement
        $newValues = $this->request->getArguments();
        $insertList = array();

        foreach($columnNames as $columnName) {

	    $columnName = $columnName['name'];

            // add to insert
            $insertList[$columnName] = $sqlService->convert($columnNames[$columnName]['type'],$newValues[$columnName]);
        }

        // nothing to be done if there are no changed column values
        if (empty($insertList)) {
            DebugUtility::debug('nothing changed in the row - addAction');
            exit(1);
        }

        $statement = "insert into ".$this->targetTable;
$columns = '';
$values = '';
        foreach($insertList as $key => $value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }

	// default values
        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['defaultvalues']);
        $defaultvalues = $TSparserObject->setup;

	if (!empty($defaultvalues)) foreach ($defaultvalues as $field => $value) {
		$columns .= $field.",";
		if (!strncmp($value,'php',3)) {
			$output = $this->evalPHP(substr($value,3,strlen($value)-3));
			$values .= "'".$output."',";
		}
	}

        // remove last comma
        $columns = rtrim($columns,',');
        $values = rtrim($values,',');

        $statement .= " (".$columns.") VALUES (".$values.")";
        // DebugUtility::debug($statement,'statement in addAction');

        // execute the query
        $sqlService = new SqlService($statement);
        $rowsAffected = $sqlService->insertRow();

        // redirect to redirectPage
	$pageUid = $this->ffdata['redirectPage'];

	$uriBuilder = $this->uriBuilder;
	$uri = $uriBuilder
	   ->setTargetPageUid($pageUid)
	   ->build();
	$this->redirectToURI($uri);
    }

    /**
     * Edit the chosen query result row.
     */
    public function updateFormAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        return $this->showAction();
    }

    /**
     * Update the chosen query result row.
     */
    public function updateAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // retrieve the {keyField : keyValue} from Fluid
        $parameter = 'keyValue';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('updateAction: Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$keyValue = $this->request->getArgument($parameter);

        // retrieve the new values for this row
        $argList = $this->request->getArguments();
        //DebugUtility::debug($argList,'Argument list for updateAction'); exit(1);

        // retrieve the row to see what columns have changed
        $statement = "select ".$this->fieldlist." from ".$this->targetTable." whEre ".$this->keyField."='".$keyValue."'";
        $sqlService = new SqlService($statement);

        $rows = $sqlService->getRows();
        if(sizeof($rows) <> 1) {
            DebugUtility::debug($parameter.' value '.$keyValue.' is NIET uniek.');
            exit(1);
        }

        $flexformInfoService = new FlexformInfoService();
        $columnNames = $flexformInfoService->mergeFieldTypes();

        foreach($rows[0] as $key => $value) {
            $oldValues[$key] = $value;
        }
        //DebugUtility::debug($oldValues,'oldValues');

        // build an update statement where only changed column values are updated
        $newValues = $this->request->getArguments();
        $updateList = array();

        foreach($columnNames as $columnName) {

	    $columnName = $columnName['name'];

            // skip column if it is the keyField since we need it unchanged in the where clause
            if (!strcmp($columnName,$this->keyField)) {
                continue;
            }
            // add to update statement if value has changed
            if (strcmp($oldValues[$columnName],$newValues[$columnName])) {
                $updateList[$columnName] = $sqlService->convert($columnNames[$columnName]['type'],$newValues[$columnName]);
            }
        }

        // update changed column values
        if (!empty($updateList)) {

	    $statement = "update ".$this->targetTable. " set";
            foreach($updateList as $key => $value) {
        	$statement .= " ".$key."='".$value."',";
            }

	    // default values
            $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
            $TSparserObject->parse($this->ffdata['defaultvalues']);
            $defaultvalues = $TSparserObject->setup;

	    if (!empty($defaultvalues)) foreach ($defaultvalues as $key => $value) {
		if (!strncmp($value,'php',3)) {
			$output = $this->evalPHP(substr($value,3,strlen($value)-3));
			$statement .= " ".$key."='".$output."',";
		}
	    }
            // remove last comma
            $statement = rtrim($statement,',');
            $statement .= " wHeRe ".$this->keyField."='".$keyValue."'";
            //DebugUtility::debug($statement,'statement for updateAction');

            // execute the query
            $sqlService = new SqlService($statement);
            $rowsAffected = $sqlService->updateRow();
            //DebugUtility::debug($rowsAffected,'rowsAffected after updateAction');

	}

        // redirect to redirectPage
	$pageUid = $this->ffdata['redirectPage'];

	$uriBuilder = $this->uriBuilder;
	$uri = $uriBuilder
	   ->setTargetPageUid($pageUid)
	   ->build();
	$this->redirectToURI($uri);
    }

    /**
     * Ask permission to delete the chosen query result row.
     */
    public function deleteFormAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

	return $this->showAction();
    }

    /**
     * Delete the chosen query result row.
     */
    public function deleteAction()
    {
        // use the template from the Flexform if there is one
        if (!empty($templateFile)) {
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // retrieve the {keyField : keyValue} from Fluid
        $parameter = 'keyValue';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('updateAction: Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$keyValue = $this->request->getArgument($parameter);

	// build delete statement
        $statement = "delete from ".$this->targetTable;
        $statement.= " where ".$this->keyField."='".$keyValue."'";
        // DebugUtility::debug($statement,'statement for deleteAction');exit(1);

        // execute the query
        $sqlService = new SqlService($statement);
        $rowsAffected = $sqlService->deleteRow();
        // DebugUtility::debug($rowsAffected,'rowsAffected after deleteAction');exit(1);

        // redirect to redirectPage
	$pageUid = $this->ffdata['redirectPage'];

	$uriBuilder = $this->uriBuilder;
	$uri = $uriBuilder
	   ->setTargetPageUid($pageUid)
	   ->build();
	$this->redirectToURI($uri);
    }


    protected function evalPHP($code)
    {
	ob_start();
	eval($code);
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

}
