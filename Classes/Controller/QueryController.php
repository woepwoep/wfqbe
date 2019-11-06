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
use \RedSeadog\Wfqbe\Service\FlexformInfoService;
use \RedSeadog\Wfqbe\Service\PluginService;
use \RedSeadog\Wfqbe\Service\RowsService;


/**
 * QueryController
 *
 * @author Ronald Wopereis <woepwoep@gmail.com>
 */
class QueryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \RedSeadog\Wfqbe\Domain\Repository\QueryRepository
     */
    protected $queryRepository = null;

    /**
     * @var $ffdata
     */
    protected $ffdata;

    /**
     * @var \RedSeadog\Wfqbe\Service\PluginService
     */
    protected $pluginService;

    /**
     * @var \RedSeadog\Wfqbe\Domain\Model\Query $query
     */
    protected static $query = null;

    /**
     * RowsService ... the placeholder for the fluid template to work with (the array of rows[] matching the query)
     *
     * @var \RedSeadog\Wfqbe\Service\RowsService
     */
    protected $rowsService;


    public function injectQueryRepository(QueryRepository $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    /**
     * __construct() ... retrieve both the plugin settings and the flexform info
     */
    public function __construct()
    {
        $this->pluginService = new PluginService('tx_wfqbe');
        $flexformInfoService = new FlexformInfoService();
        $this->ffdata = $flexformInfoService->getData();
    }

    /**
     * List the results of a query
     */
    public function listAction()
    {
        // retrieve the query from the flexform only the first time
        $this->query = $this->queryRepository->findByUid($this->ffdata['queryObject']);

        // is query filled out in FlexForm?
        if (!$this->query) {
            DebugUtility::debug($this->ffdata,'QueryController/listAction: Query ID is empty in FlexForm!');
            exit(1);
        }

        // retrieve the {keyValue} from Fluid
        $parameter = 'koppelveld';
        if ($this->request->hasArgument($parameter)) {
	    $koppelveldWaarde = $this->request->getArgument($parameter);
        }

        // the {keyValue} is substituted in the raw query
	$nieuw = str_replace('$koppelveldWaarde',$koppelveldWaarde,$this->query->getQuery());
        $this->query->setQuery($nieuw);

        // retrieve the {sortField} from Fluid
        $parameter = 'orderby';
        if ($this->request->hasArgument($parameter)) {
	    $sortField = $this->request->getArgument($parameter);
            DebugUtility::debug($sortField,'sortField in listAction');
	    $nieuw.= ' ORDER BY '.$sortField;
            $this->query->setQuery($nieuw);
        }

        // execute the query and get the result set (rows)
        $this->rowsService = new RowsService($this->query);
        $rows = $this->rowsService->getRows();

        // use the template from the Flexform if there is one
        if (!empty($this->ffdata['templateFile'])) {
            $templateFile = GeneralUtility::getFileAbsFilename($this->ffdata['templateFile']);
            $this->view->setTemplatePathAndFilename($templateFile);
        }

        // introduce the fieldtype array
        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->ffdata['fieldtypes']);
        $fieldtypes = $TSparserObject->setup;

        // assign the results in a view for fluid Query/List.html
            //DebugUtility::debug($fieldtypes,'fieldtypes');
        $this->view->assignMultiple([
            'settings' => $this->pluginService->getSettings(),
            'flexformdata' => $this->ffdata,
            'query' => $this->query,
            'rowsService' => $this->rowsService,
            'columnNames' => $this->rowsService->getColumnNames(),
            'rows' => $rows,
            'request' => $this->request,
            'fieldtypes' => $fieldtypes,
            'user' => $GLOBALS["TSFE"]->fe_user->user,
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
