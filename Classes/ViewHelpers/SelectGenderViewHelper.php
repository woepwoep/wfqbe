<?php
namespace RedSeadog\Wfqbe\ViewHelpers;

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SelectGenderViewHelper extends AbstractViewHelper
{
   use CompileWithRenderStatic;

   public function initializeArguments()
   {
       parent::initializeArguments();
       $this->registerArgument('waarde', 'string', 'inhoud van het veld', $mandatory=false, $defaultValue="");
   }

   public static function renderStatic(
       array $arguments,
       \Closure $renderChildrenClosure,
       RenderingContextInterface $renderingContext
   ) {

return array(
	0 => 'Man',
	1 => 'Vrouw',
	2 => 'Onbekend',
	3 => 'Castraat',
	8 => 'Midlife crisis',
);
   }
}
