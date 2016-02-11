<?php
namespace DCNGmbH\MooxComment\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Dominic Martin <dm@dcn.de>, DCN GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FlexFormHelper {
	
	/**
	 * objectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager	
	 */
	protected $objectManager;
	
	/**
	 * configurationManager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface	
	 */
	protected $configurationManager;
		
	/**
	 * helperService
	 *
	 * @var \DCNGmbH\MooxComment\Service\HelperService	
	 */
	protected $helperService;
	
	/**
	 * pageRepository
	 *
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository	
	 */
	protected $pageRepository;
	
	/**
	 * templateRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RemplateRepository
	 */
	protected $templateRepository;
	
	/**
	 * configuration
	 *
	 * @var \array	
	 */
	protected $configuration;
	
	/**
	 * extConf
	 *
	 * @var \array	
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
    public function initialize() {					
		
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
	public function foreignType(array &$config, &$pObj) {
		
		// initialize
		$this->initialize();
		
		// init items array
		$config['items'] = array();
		
		// get allowed types
		$types = array();
		if($this->configuration['autoDetectionOrder']!=""){
			$autoDetectionOrder = explode(",",$this->configuration['autoDetectionOrder']);
			foreach($autoDetectionOrder AS $type){
				if(in_array($type, array("tx_mooxnews_domain_model_news","pages"))){
					$types[] = $type;
				}
			}
		}
		
		// set items
		if(count($types)){
			$config['items'][] = array($GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.auto'),'auto');
		}
		$config['items'][] = array($GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.self'),'self');
		foreach($types AS $type){
			if($type!="tx_mooxnews_domain_model_news" || \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_news')){
				$config['items'][] = array($GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.'.$type),$type);
			}
		}
		$config['items'][] = array($GLOBALS['LANG']->sL(self::LLPATH.'pi1.foreign_type.tt_content'),'tt_content');
		
		// get flex form data array
		/*
		if(in_array($action,array("add","edit"))){
			$flexformData = $this->flexFormService->convertFlexFormContentToArray($config['row']['pi_flexform']);							
		}
		*/
		
	}
	
	/**
	 * Itemsproc function to extend the selection of storage pid in flexform
	 *
	 * @param array &$config configuration array
	 * @param mixed &$pObj configuration array
	 * @return void
	 */
	public function storagePid(array &$config, &$pObj) {
		
		// initialize
		$this->initialize();		
		
		// if valid persistence storage pid is set within typoscript setup
		if($this->configuration['persistence']['storagePid']!=""){						
			
			// get page info for storage pid set by typoscript
			$page = $this->pageRepository->getPage($configuration['persistence']['storagePid']);
			$definedByTs = array(array("[Defined by TS]: ".$page['title']." [PID: ".$this->configuration['persistence']['storagePid']."]","TS"));
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
	public function feGroups(array &$config, &$pObj) {
		
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
	public function getTemplateItems(array &$config, &$pObj, $category = "") {
		
		// initialize
		$this->initialize();
		
		// init items array
		$items = array();
		
		// if category is given
		if($category!=""){
			
			// get email templates from database
			$templates = $this->templateRepository->findAll(FALSE);
			
			// add templates with matching keys to item array
			foreach($templates AS $template){
				if($template->getCategory()==$category){
					$items[] = array(0 => $template->getTitle(), 1 => $template->getUid());
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
	public function newEntryEmailTemplate(array &$config, &$pObj) {
		
		// init items array
		$config['items'] = array();
		
		// set empty item
		$config['items'][] = array($GLOBALS['LANG']->sL(self::LLPATH.'pi1.new_entry_email_template.none'),0);
		
		// get items for category "commentnotifications"
		foreach($this->getTemplateItems($config,$pObj,"newentry") AS $template){
			$config['items'][] = array($template[0],$template[1]);
		}
	}
}