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
use \RedSeadog\Wfqbe\Domain\Repository\QueryRepository;
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
     * QueryRepository
     *
     * @var \RedSeadog\Wfqbe\Domain\Repository\QueryRepository
     */
    protected $queryRepository = null;

    /**
     * $ffdata
     */
    protected $ffdata;


    public function injectQueryRepository(QueryRepository $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    public function __construct()
    {
        $this->pluginSettings = new PluginService('tx_wfqbe');
        $flexformInfoService = new FlexformInfoService();
        $this->ffdata = $flexformInfoService->getData();
    }

    /**
     * Show the chosen query result row.
     */
    public function showAction()
    {
        // retrieve the tablename and the keyfield(s) from the flexform
        $targetTable = $this->ffdata['targetTable'];
        $keyField = $this->ffdata['identifiers'];
        $fieldList = $this->ffdata['fieldlist'];
        if (empty($fieldList)) $fieldList = '*';

        // retrieve the {keyValue} from Fluid
        $parameter = 'keyValue';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('showAction: Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$keyValue = $this->request->getArgument($parameter);

        // use the template from the Flexform if there is one
        if (!empty($this->ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($this->ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // execute the query
        $statement = "select ".$fieldList." from ".$targetTable." wHEre ".$keyField."='".$keyValue."'";
        $sqlService = new SqlService($statement);

        // introduce the fieldtype array
        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['fieldtypes']);
        $fieldtypes = $TSparserObject->setup;

        $rows = $sqlService->getRows();
	$fl = explode(',',$fieldList);
        $newColumns = $sqlService->mergeFieldTypes($fl,$fieldtypes);

            //DebugUtility::debug($statement,'showAction statement');exit(1);
        // assign the results in a view for fluid Query/Show.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'keyValue' => $keyValue,
            'statement' => $statement,
            'columnNames' => $newColumns,
            'row' => $rows[0],
            'request' => $this->request,
        ]);
    }

    /**
     * Request the values for a new row in the targettable
     */
    public function addFormAction()
    {
        // retrieve the tablename and the keyfield(s) from the flexform
        $targetTable = $this->ffdata['targetTable'];
        $keyField = $this->ffdata['identifiers'];
        $fieldList = $this->ffdata['fieldlist'];
        if (empty($fieldList)) $fieldList = '*';

        // use the template from the Flexform if there is one
        if (!empty($this->ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($this->ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // introduce the fieldtype array
        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['fieldtypes']);
        $fieldtypes = $TSparserObject->setup;

	$fl = explode(',',$fieldList);
        $sqlService = new SqlService('Show columns for '.$targetTable);
        $newColumns = $sqlService->mergeFieldTypes($fl,$fieldtypes);
        DebugUtility::debug($newColumns,'column names in addAction');

        // assign the results in a view for fluid Query/Show.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'keyValue' => $keyValue,
            'statement' => $statement,
            'columnNames' => $newColumns,
            'request' => $this->request,
        ]);
    }

    /**
     * Insert a new row into the targettable
     */
    public function addAction()
    {
        $targetTable = $this->ffdata['targetTable'];
        $fieldList = $this->ffdata['fieldlist'];

        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['fieldtypes']);
        $fieldtypes = $TSparserObject->setup;

	$fl = explode(',',$fieldList);
        $sqlService = new SqlService('Show columns for '.$targetTable);
        $newColumns = $sqlService->mergeFieldTypes($fl,$fieldtypes);

        // build an insert statement based on $fieldList
        $newValues = $this->request->getArguments();
        $insertList = array();

        foreach($newColumns as $newColumn) {

	    $columnName = $newColumn['name'];

            // add to insert fieldlist
            $insertList[$columnName] = $sqlService->convert($newColumns[$columnName]['type'],$newValues[$columnName]);
        }

        // nothing to be done if there are no changed column values
        if (empty($insertList)) {
            DebugUtility::debug('nothing changed in the row - addAction');
            exit(1);
        }

        $statement = "insert into ".$targetTable;
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

        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'rowsAffected' => $rowsAffected,
            'statement' => $statement,
            'columnNames' => $newColumns,
            'row' => $rows[0],
            'request' => $this->request,
        ]);
    }

    /**
     * Edit the chosen query result row.
     */
    public function updateFormAction()
    {
        return $this->showAction();
    }

    /**
     * Update the chosen query result row.
     */
    public function updateAction()
    {
        // retrieve the tablename and the keyfield(s) from the flexform
        $targetTable = $this->ffdata['targetTable'];
        $keyField = $this->ffdata['identifiers'];
        $fieldList = $this->ffdata['fieldlist'];
        if (empty($fieldList)) $fieldList = '*';

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
        $statement = "select ".$fieldList." from ".$targetTable." whEre ".$keyField."='".$keyValue."'";
        $sqlService = new SqlService($statement);

        $rows = $sqlService->getRows();
        if(sizeof($rows) <> 1) {
            DebugUtility::debug($parameter.' value '.$keyValue.' is NIET uniek.');
            exit(1);
        }

        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['fieldtypes']);
        $fieldtypes = $TSparserObject->setup;

	$fl = explode(',',$fieldList);
        $newColumns = $sqlService->mergeFieldTypes($fl,$fieldtypes);

        foreach($rows[0] as $key => $value) {
            $oldValues[$key] = $value;
        }
        //DebugUtility::debug($oldValues,'oldValues');

        // build an update statement where only changed column values are updated
        $newValues = $this->request->getArguments();
        $updateList = array();

        foreach($newColumns as $newColumn) {

	    $columnName = $newColumn['name'];

            // skip column if it is the keyField since we need it unchanged in the where clause
            if (!strcmp($columnName,$keyField)) {
                continue;
            }
            // add to update fieldlist if value has changed
            if (strcmp($oldValues[$columnName],$newValues[$columnName])) {
                $updateList[$columnName] = $sqlService->convert($newColumns[$columnName]['type'],$newValues[$columnName]);
            }
        }

        // nothing to be done if there are no changed column values
        if (empty($updateList)) {
            DebugUtility::debug('nothing changed in the row - updateAction');
            exit(1);
        }

        $statement = "update ".$targetTable. " set";
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
        $statement .= " wHeRe ".$keyField."='".$keyValue."'";
        //DebugUtility::debug($statement,'statement for updateAction');

        // execute the query
        $sqlService = new SqlService($statement);
        $rowsAffected = $sqlService->updateRow();
        //DebugUtility::debug($rowsAffected,'rowsAffected after updateAction');

        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'keyValue' => $keyValue,
            'statement' => $statement,
            'columnNames' => $newColumns,
            'row' => $rows[0],
            'request' => $this->request,
        ]);

/*

        // redirect to redirectPage
	$pageUid = $this->ffdata['redirectPage'];

	$uriBuilder = $this->uriBuilder;
	$uri = $uriBuilder
	   ->setTargetPageUid($pageUid)
	   ->build();
	$this->redirectToURI($uri);
*/
    }

    /**
     * Delete the chosen query result row.
     */
    public function deleteAction()
    {
	return $this->showAction();
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
