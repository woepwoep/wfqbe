<?php
namespace RedSeadog\Wfqbe\Service;


use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 *  PluginService
 */
class PluginService implements \TYPO3\CMS\Core\SingletonInterface
{
    protected $extName;
    protected $pluginSettings;
    protected $fullTsArray;

    /**
     * Constructs an instance of PluginService.
     *
     * @param string $extName
     */
    public function __construct($extName)
    {
        $this->extName = $extName;

        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        $this->fullTsConf = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $tsService = new TypoScriptService();
        $this->fullTsArray = $tsService->convertTypoScriptArrayToPlainArray($this->fullTsConf);
        $this->pluginSettings = $this->fullTsArray['plugin'][$extName];
        if (!is_array($this->pluginSettings)) {
            \TYPO3\CMS\Core\Utility\DebugUtility::debug('PluginService: no such extension plugin found: '.$extName);
            //exit(1);
        }
    }

    /**
     * Returns the plugin settings
     */
    public function getSettings()
    {
        return $this->pluginSettings;
    }
}
