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
	$validators = $this->getValidators();
	$valutas = $this->getValutas();

        $columnNames = array();
        foreach ($fieldlist as $field) {

            // name is important
            $columnNames[$field]['name'] = $field;

	    // add select info from flexform
	    $parameter = 'typesection';
	    $columnNames[$field]['type'] = 'TEXT';
	    if (is_array($fieldtypes)) foreach($fieldtypes as $key => $value) {
		if (!strcasecmp($columnNames[$field]['name'],$value[$parameter]['name'])) {
		    $columnNames[$field]['type'] = $value[$parameter]['veldtype'];
		}
	    }

	    // add validation info from flexform
	    $parameter = 'validation';
	    $columnNames[$field][$parameter] = '';
	    if (is_array($validators)) foreach($validators as $key => $value) {
		if (!strcasecmp($columnNames[$field]['name'],$value[$parameter]['name'])) {
		    $columnNames[$field][$parameter] = $value[$parameter]['validator'];
		}
	    }
		
		// <<<EW>>> add valuta currency info from flexform
	    $parameter = 'valuta';
	    $columnNames[$field][$parameter] = '';
	    if (is_array($valutas)) foreach($valuta as $key => $value) {
		if (!strcasecmp($columnNames[$field]['name'],$value[$parameter]['name'])) {
		    $columnNames[$field][$parameter] = $value[$parameter]['valuta'];
		}
	    }
		
        }

        return $columnNames;
    }


    /**
     * retrieve the query from the flexform data array
     * (query is required in all QUERY actions)
     */
    public function getQuery()
    {
	return $this->getRequiredElement('query');
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
	return $this->getOptionalElement('fieldlist');
    }

    /**
     * retrieve the fieldtypes from the flexform data array
     * (fieldtypes are optional in all CRUD actions)
     */
    public function getFieldtypes()
    {
        // introduce the fieldtype array
/**
 * no longer TypoScript
 *      $TSparserObject = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
 *      $TSparserObject->parse($this->getOptionalElement('fieldtypes'));
 *      $fieldtypes = $TSparserObject->setup;
 */

	return $this->getOptionalElement('fieldtypes');
    }

    /**
     * retrieve the validators from the flexform data array
     * (validators is optional in all CRUD actions)
     */
    public function getValidators()
    {
	return $this->getOptionalElement('field');
    }
	
	//<<<EW>>>
	/**
     * retrieve the validators from the flexform data array
     * (validators is optional in all CRUD actions)
     */
    public function getValutas()
    {
	return $this->getOptionalElement('valuta');
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
