<?php
namespace RedSeadog\Wfqbe\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class LogoViewHelper extends AbstractViewHelper
{
   use CompileWithRenderStatic;

   public static function renderStatic(
       array $arguments,
       \Closure $renderChildrenClosure,
       RenderingContextInterface $renderingContext
   ) {
        //$result = $GLOBALS['TSFE']->tmpl->setup['lib.']['RSname.']['data.'],1));
        $result = $GLOBALS['TSFE']->fe_user->user['username'];
$result = \Cobweb\Expressions\ExpressionParser::evaluateExpression('tsfe:fe_user|user|uid');

	   return 'Hello, '.$result;
   }
}
