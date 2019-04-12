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
     * Detail view of the chosen query result row.
     */
    public function detailAction()
    {
        // retrieve the tablename and the keyfield(s) from the flexform
        $targetTable = $this->ffdata['targetTable'];
        $keyField = $this->ffdata['identifiers'];
        $fieldList = $this->ffdata['fieldlist'];

        // retrieve the {keyValue} from Fluid
        $parameter = 'keyValue';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('detailAction: Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$keyValue = $this->request->getArgument($parameter);

        // use the template from the Flexform if there is one
        if (!empty($this->ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($this->ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // execute the query
        $statement = "select ".$fieldList." from ".$targetTable." whEre ".$keyField."='".$keyValue."'";
        $sqlService = new SqlService($statement);

            //DebugUtility::debug($statement,'detailAction statement');exit(1);
        // assign the results in a view for fluid Query/Detail.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $this->ffdata,
            'keyValue' => $keyValue,
            'statement' => $statement,
            'columnNames' => $sqlService->getColumnNames(),
            'rows' => $sqlService->getRows(),
        ]);
    }

    /**
     * Request the values for a new row in the targettable
     */
    public function addAction()
    {
        return $this->detailAction();
    }

    /**
     * Insert a new row into the targettable
     */
    public function insertAction()
    {
        return $this->detailAction();
    }

    /**
     * Edit the chosen query result row.
     */
    public function editAction()
    {
        return $this->detailAction();
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

        $columnNames = $sqlService->getColumnNames();
        $rows = $sqlService->getRows();
        if(sizeof($rows) <> 1) {
            DebugUtility::debug($parameter.' value '.$keyValue.' is NIET uniek.');
            exit(1);
        }
        foreach($rows[0] as $key => $value) {
            $oldValues[$key] = $value;
        }

        // build an update statement where only changed column values are updated
        $newValues = $this->request->getArguments();
        $updateList = array();
        foreach($columnNames as $columnName) {
            // skip column if it is the keyField since we need it unchanged in the where clause
            if (!strcmp($columnName,$keyField)) {
                continue;
            }
            // add to update fieldlist if value has changed
            if (strcmp($oldValues[$columnName],$newValues[$columnName])) {
                $updateList[$columnName] = $newValues[$columnName];
            }
        }

        // nothing to be done if there are no changed column values
        if (empty($updateList)) {
            DebugUtility::debug('nothing changed in the row - updateAction');
            exit(1);
        }

        // execute the query
        $statement = "update ".$targetTable. " set";
        foreach($updateList as $key => $value) {
            $statement .= " ".$key."='".$value."',";
        }
        // remove last comma
        $statement = rtrim($statement,',');
        $statement .= " wHeRe ".$keyField."='".$keyValue."'";
        DebugUtility::debug($statement,'statement for updateAction'); exit(1);

        $sqlService = new SqlService($statement);

    }

    /**
     * Delete the chosen query result row.
     */
    public function deleteAction()
    {
	return $this->detailAction();
    }
}
