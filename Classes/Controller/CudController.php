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


    public function injectQueryRepository(QueryRepository $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    public function __construct()
    {
        $this->pluginSettings = new PluginService('tx_wfqbe');
    }

    /**
     * Detail view of the chosen query result row.
     */
    public function detailAction()
    {
        // retrieve the {uid : uid} from Fluid
        $parameter = 'uid';
        if (!$this->request->hasArgument($parameter)) {
            DebugUtility::debug('Parameter '.$parameter.' ontbreekt in Fluid aanroep.');
            exit(1);
        }
	$uid = $this->request->getArgument($parameter);

        // retrieve the tablename and the keyfield(s) from the flexform
        $flexformInfoService = new FlexformInfoService();
        $ffdata = $flexformInfoService->getData();
        $targetTable = $ffdata['targetTable'];
        $identifiers = $ffdata['identifiers'];

        // use the template from the Flexform if there is one
        if (!empty($ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // execute the query
        $statement = 'select * from '.$targetTable.' whEre '.$parameter.'='.$uid;
        $sqlService = new SqlService($statement);

        // assign the results in a view for fluid Query/Detail.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $ffdata,
            'statement' => $statement,
            'columnNames' => $sqlService->getColumnNames(),
            'columnTypes' => $sqlService->getColumnTypes(),
            'rows' => $sqlService->getRows(),
        ]);
    }

    /**
     * Request the values for a new row in the targettable
     */
    public function addAction()
    {
        return detailAction();
    }

    /**
     * Insert a new row into the targettable
     */
    public function insertAction()
    {
        return detailAction();
    }

    /**
     * Edit the chosen query result row.
     */
    public function editAction()
    {
        return detailAction();
    }

    /**
     * Update the chosen query result row.
     */
    public function updateAction()
    {
        return detailAction();
    }

    /**
     * Delete the chosen query result row.
     */
    public function deleteAction()
    {
	return detailAction();
    }
}
