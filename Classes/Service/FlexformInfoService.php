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
    public function getData() {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');

        // Get the data object (contains the tt_content fields)
        $cObj = $configurationManager->getContentObject();

        // Retrieve flexform values
        $flexformService = new FlexformService();
	$values = $flexformService->convertFlexFormContentToArray($cObj->data['pi_flexform']);

        // Return the values
        return $values;
    }
}
