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
use \RedSeadog\Wfqbe\Service\FlexformService;
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
     * @var \RedSeadog\Wfqbe\Service\FlexformService
     */
    protected $flexformSettings;

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
        $this->flexformSettings = new FlexformService();
    }

    /**
     * Detail query
     *
     * @param \RedSeadog\Wfqbe\Domain\Model\Query $query
     */
    public function detailAction(\RedSeadog\Wfqbe\Domain\Model\Query $query = null)
    {
        // retrieve the query from the flexform
        $ffdata = $this->flexformSettings->getData();
        $query = $this->queryRepository->findByUid($ffdata['queryObject']);

        // is query filled out in FlexForm?
        if (!$query) {
            DebugUtility::debug('CudController/detailAction: Query ID is empty in FlexForm!');
            exit(1);
        }

        // execute the query
        $sqlService = new SqlService($query->getQuery());

        // assign the results in a view for fluid Query/Detail.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $ffdata,
            'query' => $query,
            'columnNames' => $sqlService->getColumnNames(),
            'columnTypes' => $sqlService->getColumnTypes(),
            'rows' => $sqlService->getRows(),
        ]);
    }

    /**
     * Edit query
     *
     * @param \RedSeadog\Wfqbe\Domain\Model\Query $query
     */
    public function editAction(\RedSeadog\Wfqbe\Domain\Model\Query $query = null)
    {
        // retrieve the query from the flexform
        $ffdata = $this->flexformSettings->getData();
        $query = $this->queryRepository->findByUid($ffdata['queryObject']);

        // is query filled out in FlexForm?
        if (!$query) {
            DebugUtility::debug('CudController/editAction: Query ID is empty in FlexForm!');
            exit(1);
        }

        // execute the query
        $sqlService = new SqlService($query->getQuery());

        // assign the results in a view for fluid Query/Edit.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $ffdata,
            'query' => $query,
            'columnNames' => $sqlService->getColumnNames(),
            'columnTypes' => $sqlService->getColumnTypes(),
            'rows' => $sqlService->getRows(),
        ]);
    }

    /**
     * Delete query
     *
     * @param \RedSeadog\Wfqbe\Domain\Model\Query $query
     */
    public function deleteAction(\RedSeadog\Wfqbe\Domain\Model\Query $query = null)
    {
        // retrieve the query from the flexform
        $ffdata = $this->flexformSettings->getData();
        $query = $this->queryRepository->findByUid($ffdata['queryObject']);

        // is query filled out in FlexForm?
        if (!$query) {
            DebugUtility::debug('CudController/deleteAction: Query ID is empty in FlexForm!');
            exit(1);
        }

        // execute the query
        $sqlService = new SqlService($query->getQuery());

        // assign the results in a view for fluid Query/Delete.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $ffdata,
            'query' => $query,
            'columnNames' => $sqlService->getColumnNames(),
            'columnTypes' => $sqlService->getColumnTypes(),
            'rows' => $sqlService->getRows(),
        ]);
    }
}