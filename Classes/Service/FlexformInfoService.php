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


    public function mergeFieldTypes($fieldlist = null)
    {
	if (empty($fieldlist)) {
	    $fieldlist = explode(',',$this->getFieldlist());
	}

	$fieldtypes = $this->getFieldtypes();
        $columnNames = array();
        foreach ($fieldlist as $field) {

            // name is important
            $columnNames[$field]['name'] = $field;

            // default is TEXT
            $columnNames[$field]['type'] = 'TEXT';

            // if user overrules, use the fieldtype provided by the user
            if ($fieldtypes[$field]) {
                $columnNames[$field]['type'] = $fieldtypes[$field];
            }
        }

        return $columnNames;
    }


    /**
     * retrieve the targetTable from the flexform data array
     * (targetTable is required in all CRUD actions)
     */
    public function getTargetTable()
    {
	return $this->getRequiredElement('targetTable');
    }

    /**
     * retrieve the keyField from the flexform data array
     * (keyField is required in all CRUD actions)
     */
    public function getKeyField()
    {
	return $this->getRequiredElement('identifiers');
    }

    /**
     * retrieve the fieldlist from the flexform data array
     * (fieldlist is required in all CRUD actions)
     */
    public function getFieldlist()
    {
	return $this->getRequiredElement('fieldlist');
    }

    /**
     * retrieve the fieldtypes from the flexform data array
     * (fieldtypes are optional in all CRUD actions)
     */
    public function getFieldtypes()
    {
        // introduce the fieldtype array
        $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
        $TSparserObject->parse($this->getOptionalElement('fieldtypes'));
        $fieldtypes = $TSparserObject->setup;

	return $fieldtypes;
    }

    /**
     * retrieve the templateFile from the flexform data array
     * (templateFile is optional in all CRUD actions)
     */
    public function getTemplateFile()
    {
	$templateFile = $this->getOptionalElement('templateFile');
        if (!empty($templateFile)) {
            $templateFile = GeneralUtility::getFileAbsFilename($templateFile);
	}
	return $templateFile;
    }

    /**
     * retrieve a required array element $key from the flexform data array
     */
    protected function getRequiredElement($key)
    {
	$value = $this->getOptionalElement($key);

        if (empty($value)) {
            DebugUtility::debug($this->ffdata,'Required key '.$key.' is empty or notfound in flexform.');
            exit(1);
        }

	return $value;
    }

    /**
     * retrieve an array element $key from the flexform data array
     * default value is returned if array element is not found
     */
    protected function getOptionalElement($key,$default='')
    {
	$value = $this->ffdata[$key];

        if (empty($value)) {
            $value = $default;
        }

	return $value;
    }
}
