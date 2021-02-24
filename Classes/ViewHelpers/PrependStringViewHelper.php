<?php
namespace RedSeadog\Wfqbe\ViewHelpers;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This ViewHelper takes two arrays and returns
 * the `array_merge`d result.
 */
class PrependStringViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('array','mixed','Array for which key is to be prepended by string',true);
        $this->registerArgument('string','string','String to be prepended',true);
    }

    /**
     * Merges the two arrays .
     *
     * @return array
     */
    public function render()
    {
        $array = $this->arguments['array'];
        $string = $this->arguments['string'];
        // DebugUtility::debug($array,'array in PrependStringViewHelper');
        // DebugUtility::debug($string,'string in PrependStringViewHelper');
		if (empty($array)) return $array;
		if (empty($string)) return $array;
		$newArray = array();
		foreach($array AS $key => $value) {
			$newArray['RSRQ_'.$key] = $value;
		}
        // DebugUtility::debug($newArray,'newArray in PrependStringViewHelper');
        return $newArray;
    }
}
