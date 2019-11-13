<?php
namespace RedSeadog\Wfqbe\ViewHelpers;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GenderViewHelper extends AbstractViewHelper
{
   use CompileWithRenderStatic;

   public function initializeArguments()
   {
       parent::initializeArguments();
       $this->registerArgument('waarde', 'int', 'inhoud van het veld', $mandatory=true, $defaultValue="");
   }

   public static function renderStatic(
       array $arguments,
       \Closure $renderChildrenClosure,
       RenderingContextInterface $renderingContext
   ) {

	switch (intval($arguments['waarde'])) {
	default: $string = 'Onbekende gender'; break;
	case 0: $string = 'Man';break;
	case 1: $string = 'Vrouw';break;
	case 2: $string = 'Onbekend';break;
	case 3: $string = 'Castraat';break;
	case 8: $string = 'Midlife crisis';break;
	}
	return $string;
   }
}
