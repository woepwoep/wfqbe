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
 * QueryController
 *
 * @author Ronald Wopereis <woepwoep@gmail.com>
 */
class QueryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * @var \RedSeadog\Wfqbe\Service\FlexformInfoService
     */
    protected $flexformSettings;

    /**
     * @var \RedSeadog\Wfqbe\Domain\Repository\QueryRepository
     */
    protected $queryRepository = null;

    /**
     * @var array $rows
     */
    protected $rows = null;


    public function injectQueryRepository(QueryRepository $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    public function __construct()
    {
        $this->pluginSettings = new PluginService('tx_wfqbe');
        $this->flexformSettings = new FlexformInfoService();
    }

    /**
     * List the results of a query
     */
    public function listAction()
    {
        // retrieve the query from the flexform
        $ffdata = $this->flexformSettings->getData();
        $query = $this->queryRepository->findByUid($ffdata['queryObject']);

        // is query filled out in FlexForm?
        if (!$query) {
            DebugUtility::debug('QueryController/listAction: Query ID is empty in FlexForm!');
            exit(1);
        }

        // retrieve the {keyValue} from Fluid
        $parameter = 'koppelveld';
        if ($this->request->hasArgument($parameter)) {
	    $koppelveldWaarde = $this->request->getArgument($parameter);
        }
	$nieuw = str_replace('$koppelveldWaarde',$koppelveldWaarde,$query->getQuery());

        // execute the query
        $sqlService = new SqlService($nieuw);
        $this->rows = $sqlService->getRows();

        // use the template from the Flexform if there is one
        if (!empty($ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // assign the results in a view for fluid Query/List.html
        $this->view->assignMultiple([
            'settings' => $this->pluginSettings->getSettings(),
            'flexformdata' => $ffdata,
            'query' => $query,
            'columnNames' => $sqlService->getColumnNames(),
            'rows' => $this->rows,
        ]);
    }

    /**
     * param string $orderby
     */
    public function sortAction(string $orderby)
    {
$joop = 'Achternaam';
        $this->rows = $this->array_msort($this->rows, array($joop=>SORT_DESC));
        $this->redirect(
            'list',
            null,
            null,
            []
        );
    }

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;
}
}
