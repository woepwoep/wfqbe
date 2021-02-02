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
use \RedSeadog\Wfqbe\Service\FlexformInfoService;
use \RedSeadog\Wfqbe\Service\PluginService;
use \RedSeadog\Wfqbe\Service\SqlService;

use \Cobweb\Expressions\ExpressionParser;

/**
 * QueryController
 *
 * @author Ronald Wopereis <woepwoep@gmail.com>
 */
class QueryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var $ffdata
     */
    protected $ffdata;

    /**
     * @var \RedSeadog\Wfqbe\Service\PluginService
     */
    protected $pluginService;

    /**
     * @var query
     */
    protected $query;


    /**
     * __construct() ... retrieve both the plugin settings and the flexform info
     */
    public function __construct()
    {
        $this->pluginService = new PluginService('tx_wfqbe');
        $flexformInfoService = new FlexformInfoService();

        // retrieve the query from the flexform ...
        $this->query = $flexformInfoService->getQuery();

        // ... and substitute where possible
	$expr = explode('ExpressionParser',$this->query);
	for ($i=1; $i<sizeof($expr); $i++) {
	    $joop = $this->parseExpression($expr[$i]);
	    $this->query = preg_replace('/ExpressionParser(.*)/',"'".$joop[0]."'",$this->query);
	    $this->query.= $joop[1];
	}

	// retrieve other ffdata
        $this->ffdata = $flexformInfoService->getData();
    }

    /**
     *  $expr contains {...expr...} then a space and then the rest
     */
    protected function parseExpression($expr)
    {
	$endpos = strpos($expr,' ');
	$parsedExpr = substr($expr,0,$endpos);
	$rest = substr($expr,$endpos);

	$newExpr = ExpressionParser::evaluateExpression($parsedExpr);
	return array($newExpr,$rest);
    }

    /**
     * Filter the results of a query
     */
    public function filterAction()
    {
	return $this->listAction();
    }

    /**
     * List the results of a query
     */
    public function detailFormAction()
    {
	return $this->listAction();
    }

    /**
     * List the results of a query
     */
    public function listAction()
    {
	// search the where-clause for filter arguments ###filter###
	$pattern = '/###[^#]*###/';
	$subject = $this->query;
	preg_match_all($pattern, $subject, $matches);
	$markerFields = array_unique($matches[0]);

	// replace the ### with empty string
	$search = '###';
	$replace = '';
	$subject = $markerFields;
	$markerFields = str_replace($search,$replace,$subject);

	// process RSRQ_* arguments
	$joop = $this->request->getArguments();
	foreach($joop as $rsrq_name => $rsrq_value) {
	    $rest = substr($rsrq_name,0,5);
	    if ($rest === 'RSRQ_') {
		$rsrq_names[substr($rsrq_name,5)] = $rsrq_value;
	    }
	}

	// the RSRQ_* arguments are substituted in the raw query
	if (!empty($rsrq_names)) foreach($rsrq_names as $rsrq_name => $rsrq_value) {
	    $replace = '###'.$rsrq_name.'###';
	    $nieuw = str_replace($replace,$rsrq_value,$this->query);
            $this->query = $nieuw;
	}

        // retrieve the {keyValue} from Fluid
        $parameter = 'keyValue';
        if ($this->request->hasArgument($parameter)) {
	    $keyValue = $this->request->getArgument($parameter);
        }

        // retrieve the {sortField} from Fluid
        $parameter = 'orderby';
        if ($this->request->hasArgument($parameter)) {
	    $sortField = $this->request->getArgument($parameter);
	    $nieuw.= ' ORDER BY '.$sortField;
            $this->query = $nieuw;
        }

	// insert right after the WHERE
        $flexformInfoService = new FlexformInfoService();
	$andWhere = $flexformInfoService->andWhere($rsrq_names);

	// replace the special marker ###filterFields### with andWhere
	$string = $this->query;
	$pattern = '/###filterFields###/';
	$replacement = $andWhere;
	$sjaak = preg_replace($pattern, $replacement, $string);
	$this->query = $sjaak;

	// remove any other marker from the query
	$string = $this->query;
	$pattern = '/###[^#]+###/';
	$replacement = '';
	$sjaak = preg_replace($pattern, $replacement, $string);
	$this->query = $sjaak;

        // execute the query and get the result set (rows)
        // DebugUtility::debug($this->query,'this->query in listAction');
        $sqlService = new SqlService($this->query);

        // use the template from the Flexform if there is one
        if (!empty($this->ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($this->ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

	// execute the query
        $rows = $sqlService->getRows();
	$columnNames = $sqlService->getColumnNamesFromResultRows($rows);
        $newColumns = $flexformInfoService->mergeFieldTypes($columnNames);
        $filterFieldList = $flexformInfoService->getFilterFieldList();

        // assign the results in a view for fluid Query/List.html
        $this->view->assignMultiple([
            'settings' => $this->pluginService->getSettings(),
            'flexformdata' => $this->ffdata,
            'query' => $this->query,
            'columnNames' => $newColumns,
            'rows' => $rows,
            'request' => $this->request,
            'user' => $GLOBALS["TSFE"]->fe_user->user,
	    'filterFields' => $markerFields,
	    'rsrq_names' => $rsrq_names,
	    'filterFieldList' => $filterFieldList,
        ]);
    }

    /**
     * param string $orderby
     */
    public function sortAction()
    {
        // retrieve the {sortField} from Fluid
        $sortField = '';
        $parameter = 'orderby';
        if ($this->request->hasArgument($parameter)) {
	    $sortField = $this->request->getArgument($parameter);
        }

        $this->redirect(
            'list',	// action
            null,	// controller
            null,	// extension
            [ 'orderby' => $sortField ]		// arguments
        );
    }

}
