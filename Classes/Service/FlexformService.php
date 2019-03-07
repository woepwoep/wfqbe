<?php
namespace RedSeadog\Wfqbe\Service;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *  FlexformService
 */
class FlexformService extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    public function getData() {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');

        // Get the data object (contains the tt_content fields)
        $cObj = $configurationManager->getContentObject();

        // Retrieve flexform values
        $cObj->readFlexformIntoConf($cObj->data['pi_flexform'], $values);

        // Return the values
        return $values;
    }

}
