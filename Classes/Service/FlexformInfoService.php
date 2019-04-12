<?php
namespace RedSeadog\Wfqbe\Service;

use TYPO3\CMS\Core\Service\FlexformService;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *  FlexformInfoService
 */
class FlexformInfoService extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $ffdata;

    public function __construct()
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');

        // Get the data object (contains the tt_content fields)
        $cObj = $configurationManager->getContentObject();

        // Retrieve flexform values
        $flexformService = new FlexformService();
	$this->ffdata = $flexformService->convertFlexFormContentToArray($cObj->data['pi_flexform']);
    }

    public function getData()
    {
        // Return the values
        return $this->ffdata;
    }

    public function getTargetTable()
    {
        return $this->ffdata['targetTable'];
    }

    public function getKeyField()
    {
        return $this->ffdata['identifiers'];
    }

    public function getFieldList()
    {
        return $this->ffdata['fieldlist'];
    }
}
