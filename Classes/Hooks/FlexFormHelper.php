<?php
namespace DCNGmbH\MooxComment\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FlexFormHelper {
	
	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager	
	 */
	protected $objectManager;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface	
	 */
	protected $configurationManager;
		
	/**
	 * @var \DCNGmbH\MooxComment\Service\HelperService	
	 */
	protected $helperService;
	
	/**
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository	
	 */
	protected $pageRepository;
	
	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RemplateRepository
	 */
	protected $templateRepository;
	
	/**
	 * @var array	
	 */
	protected $configuration;
	
	/**
	 * @var array	
	 */
	protected $extConf;
	
	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang_be.xlf:';
	
	/**
     * initialize action
	 *
     * @return void
     */
    public function initialize()
	{							
		// initialize object manager
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		
		// initialize configuration manager
		$this->configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		
		// init helper service
		$this->helperService = $this->objectManager->get('DCNGmbH\\MooxComment\\Service\\HelperService');
		
		// initialize page repository
		$this->pageRepository = $this->objectManager->get('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
		
		// initialize template repository
		$this->templateRepository = $this->objectManager->get('DCNGmbH\\MooxComment\\Domain\\Repository\\TemplateRepository');
		
		// get typoscript configuration
		$this->configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,"MooxComment")['plugin.']['tx_mooxcomment.']['settings.'];
		
		// get extensions's configuration
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);
    }		
	
	/**
	 * Itemsproc function to generate the selection of target types
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @return void
	 */
	public function foreignType(array &$config, &$pObj)
	{		
		// initialize
		$this->initialize();
		
		// init items array
		$config['items'] = [];
		
		// get allowed types
		$types = [];
		if($this->configuration['autoDetectionOrder']!=""){
			$autoDetectionOrder = explode(",",$this->configuration['autoDetectionOrder']);
			foreach($autoDetectionOrder AS $type){
				if(in_array($type, ["tx_mooxnews_domain_model_news","tx_mooxshop_domain_model_product","pages"])){
					$types[] = $type;
				}
			}
		}
		
		// set items
		if(count($types)){
			$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.auto'),'auto'];
		}
		$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.self'),'self'];
		foreach($types AS $type){
			if($type!="tx_mooxnews_domain_model_news" || ExtensionManagementUtility::isLoaded('moox_news')){
				$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.'.$type),$type];
			} elseif($type!="tx_mooxshop_domain_model_product" || ExtensionManagementUtility::isLoaded('moox_shop')){
				$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.'.$type),$type];
			}
		}
		$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.tt_content'),'tt_content'];		
	}
	
	/**
	 * Itemsproc function to extend the selection of storage pid in flexform
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @return void
	 */
	public function storagePid(array &$config, &$pObj)
	{		
		// initialize
		$this->initialize();		
		
		// if valid persistence storage pid is set within typoscript setup
		if($this->configuration['persistence']['storagePid']!=""){						
			
			// get page info for storage pid set by typoscript
			$page = $this->pageRepository->getPage($configuration['persistence']['storagePid']);
			$definedByTs = [["[Defined by TS]: ".$page['title']." [PID: ".$this->configuration['persistence']['storagePid']."]","TS"]];
		} 
		
		// add pid postfix to item array element and remove invalid pids from array
		for($i=0;$i<count($config['items']);$i++){
			if($config['items'][$i][1]!=""){
				$config['items'][$i][0] = $config['items'][$i][0]." [PID: ".$config['items'][$i][1]."]";
			} 			
		}
		
		// if available add defined by ts value to items array
		if($definedByTsTxt){
			$config['items'] = array_merge($definedByTs,$config['items']);
		}
	}		
	
	/**
	 * Itemsproc function to process the selection of fe groups in flexform
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @return void
	 */
	public function feGroups(array &$config, &$pObj)
	{		
		// add pid postfix to item array element and remove invalid pids from array
		for($i=0;$i<count($config['items']);$i++){
			if($config['items'][$i][1]!=""){
				$config['items'][$i][0] = $config['items'][$i][0]." [PID: ".$config['items'][$i][1]."]";
			} 			
		}
	}	

	/**
	 * Itemsproc function to generate the selection of email templates for given category
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @param string $category template category
	 * @return void
	 */
	public function getTemplateItems(array &$config, &$pObj, $category = "")
	{		
		// initialize
		$this->initialize();
		
		// init items array
		$items = [];
		
		// if category is given
		if($category!=""){
			
			// get email templates from database
			$templates = $this->templateRepository->findAll(FALSE);
			
			// add templates with matching keys to item array
			foreach($templates AS $template){
				if($template->getCategory()==$category){
					$items[] = [0 => $template->getTitle(), 1 => $template->getUid()];
				}
			}
		}
		
		return $items;
	}
	
	/**
	 * Itemsproc function to generate the selection of new entry email templates
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @return void
	 */
	public function newEntryEmailTemplate(array &$config, &$pObj)
	{		
		// init items array
		$config['items'] = [];
		
		// set empty item
		$config['items'][] = [$GLOBALS['LANG']->sL(self::LLPATH.'pi1.new_entry_email_template.none'),0];
		
		// get items for category "commentnotifications"
		foreach($this->getTemplateItems($config,$pObj,"newentry") AS $template){
			$config['items'][] = [$template[0],$template[1]];
		}
	}
}