<?php
namespace DCNGmbH\MooxComment\Controller;

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
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \DCNGmbH\MooxComment\Domain\Model\Rating;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Pi2Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {	
	
	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager;
	
	/**
	 * frontendUserRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;	

	/**
	 * frontendUserGroupRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\FrontendUserGroupRepository
	 * @inject
	 */
	protected $frontendUserGroupRepository;
	
	/**
	 * templateRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\TemplateRepository
	 * @inject
	 */
	protected $templateRepository;
	
	/**
	 * ratingRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RatingRepository
	 * @inject
	 */
	protected $ratingRepository;
		
	/**
	 * accessControllService
	 *
	 * @var \DCNGmbH\MooxComment\Service\AccessControlService
	 * @inject
	 */
	protected $accessControllService;
	
	/**
	 * helperService
	 *
	 * @var \DCNGmbH\MooxComment\Service\HelperService
	 * @inject
	 */
	protected $helperService;
	
	/**
	 * extConf
	 *
	 * @var \boolean
	 */
	protected $extConf;
	
	/**
	 * storagePids
	 *
	 * @var \array 	
	 */
	protected $storagePids;
	
	/**
	 * pagination
	 *
	 * @var \array 	
	 */
	protected $pagination;
				
	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang.xlf:';
		
	/**
     * initialize action
     *
     * @return void
     */
    public function initializeAction() {					
		
		// execute parent initialize action
		parent::initializeAction();
		
		// load extension configuration
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);		
		
		$this->helperService->setAutoDetectionOrder($this->settings['autoDetectionOrder']);
		$this->helperService->setForeignType($this->settings['foreignType']?$this->settings['foreignType']:'auto');
		if($this->settings['foreignType']=="tt_content" && $this->settings['contentElement']>0){
			$this->helperService->setContentUid($this->settings['contentElement']);
		} else {
			$this->helperService->setContentUid($this->configurationManager->getContentObject()->data['uid']);
		}
		if($this->settings['foreignType']=="pages" && $this->settings['page']>0){
			$this->helperService->setPageUid($this->settings['page']);
		}
		
		// initalize storage settings
		$this->initializeStorageSettings();		
    }

	/**
	 * initialize storage settings
	 *	 
	 * @return void
	 */
	protected function initializeStorageSettings() {
			
		// get typoscript configuration
		$configuration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		
		// set storage pid if set by plugin
		if($this->settings['storagePid']!=""){
			$this->setStoragePids(array($this->settings['storagePid']));
		} else {
			$this->setStoragePids(array());
		}
		$this->helperService->setStoragePids($this->getStoragePids());
		
		// overwrite extbase persistence storage pid
		if(!empty($this->settings['storagePid']) && $this->settings['storagePid']!="TS"){
			if (empty($configuration['persistence']['storagePid'])) {
				$storagePids['persistence']['storagePid'] = $this->settings['storagePid'];
				$this->configurationManager->setConfiguration(array_merge($configuration, $storagePids));
			} else {
				$configuration['persistence']['storagePid'] = $this->settings['storagePid'];
				$this->configurationManager->setConfiguration($configuration);
			}
		}		
	}
	
	/**
	 * action show
	 *	
	 * @param \array $filter filter
	 * @param \string $settings settings
	 * @return void
	 */
	public function showAction($filter = array(),$settings = NULL) {
		
		// init action arrays and booleans
		$messages = array();
		$errors = array();	
		$info = array();		
		$isModerator = false;
		$isViewable = false;
		$isRateable = false;
		$isUnrateable = false;				
				
		// overwrite settings
		$this->settings = $this->helperService->overwriteSettings($this->settings,$settings);												
		
		// get configuration
		if(isset($filter['uid_foreign']) && is_numeric($filter['uid_foreign']) && $filter['uid_foreign']>0 && isset($filter['tablenames']) && $filter['tablenames']!=""){			
			$configuration= array(
				"uid_foreign" => $filter['uid_foreign'],
				"tablenames" => $filter['tablenames'] 
			);
		} else {
			$configuration = $this->helperService->getConfiguration("review");
		}
		
		// check and get logged in user
		if(TRUE === $this->accessControllService->hasLoggedInFrontendUser()) {
			$feUser = $this->frontendUserRepository->findByUid($this->accessControllService->getFrontendUserUid());				
		}
		if(!$this->settings['onlyLoggedInUsersCanSeeRatings'] || ($this->settings['onlyLoggedInUsersCanSeeRatings'] && is_object($feUser))){	
			$isViewable = true;			
		}
		if(!$this->settings['onlyLoggedInUsersCanRate'] || ($this->settings['onlyLoggedInUsersCanRate'] && $feUser)){	
			$isRateable = true;
			if($configuration && $this->settings['allowOnlyOneRatingPerUserIfPossible'] && is_object($feUser)){
				$items = $this->ratingRepository->findByFilter(
					array(
						'uid_foreign' => $configuration['uid_foreign'],
						'tablenames' => $configuration['tablenames'],
						'feUser' => $feUser->getUid(),						
					),NULL,NULL,NULL,"all");
				if($items->count()>0){
					$isRateable = false;
					$rating = $items->getFirst();
					if(is_object($rating) && $this->settings['usersCanDeleteOrRerateOwnRatings']){						
						$isUnrateable = true;
					}
				}
			}
			
		}
		
		// if configuration is valid
		if($configuration && $isViewable){
					
			// if user is logged in
			if($feUser){
				$this->view->assign('feUser', $feUser);				
			} else {
				$this->view->assign('feUser', NULL);
			}
			
			$filter['uid_foreign'] 	= $configuration['uid_foreign'];
			$filter['tablenames'] 	= $configuration['tablenames'];			
						
			// get item count			
			//$count = $this->ratingRepository->findByFilter($filter,$orderings,NULL,NULL,"all")->count();
			
			// get rating info
			if(in_array($this->settings['ratingMode'],array("like_dislike","stars"))){
				$info['rating'] = $this->ratingRepository->findRatingInfos(array(
					"uid_foreign" => $configuration['uid_foreign'],
					"tablenames" => $configuration['tablenames'],
				),$this->settings['ratingMode'])[0];			
				if($this->settings['ratingMode']=="like_dislike"){
					if($info['rating']['likes']>0){
						$info['rating']['likes_percent'] = round(($info['rating']['likes']/$info['rating']['count'])*100);
					} 
					if($info['rating']['dislikes']>0){
						$info['rating']['dislikes_percent'] = round(($info['rating']['dislikes']/$info['rating']['count'])*100);
					}
				}				
			}
			if($this->settings['ratingMode']=="stars" && !$info['rating']['average']){
				$info['rating']['average'] = 0;
			}
			
			$this->view->assign('configuration', $configuration);
			$this->view->assign('info', $info);
			$this->view->assign('rating', $rating);
			$this->view->assign('uri', $this->uriBuilder->getRequest()->getRequestUri());
			$this->view->assign('ajaxSettings', $this->helperService->encrypt(json_encode($this->settings)));	
			$this->view->assign('isViewable', $isViewable);
			$this->view->assign('isRateable', $isRateable);
			$this->view->assign('isUnrateable', $isUnrateable);
			$this->view->assign('settings', $this->settings);			
			$this->view->assign('action', 'show');
			
		} else {
			$this->view = NULL;
		}		
	}
	
	/**
	 * action rate
	 *
	 * @param \array $rate form data
	 * @param \boolean $ajax is ajax request?
	 * @param \string $settings settings
	 * @return void
	 */
	public function rateAction($rate = NULL, $ajax = FALSE, $settings = NULL) {
		
		// init action arrays and booleans
		$messages = array();
		$errors = array();			
		
		// overwrite settings
		$this->settings = $this->helperService->overwriteSettings($this->settings,$settings);
				
		// check and get logged in user
		if(TRUE === $this->accessControllService->hasLoggedInFrontendUser()) {
			$feUser = $this->frontendUserRepository->findByUid($this->accessControllService->getFrontendUserUid());			
		}	
		
		// no errors -> add rating
		if(!count($errors)){
		
			// set item values
			$item = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Model\Rating');					
			
			$item->setUidForeign($rate['uid_foreign']);
			$item->setTitleForeign($rate['title_foreign']);
			$item->setUrlForeign($rate['url_foreign']);
			$item->setTablenames($rate['tablenames']);
			
			if($feUser){			
				$item->setFeUser($feUser);				
			}
			
			$item->setConfirmed(time());
			$item->setTitle($rate['title_foreign']." | Rating: ".trim($rate['rating']));
			$item->setRating(trim($rate['rating']));
			
			// set pid (bugfix ignoring global setting);
			if(!$item->getPid()){
				$item->setPid($this->settings['storagePid']);
			}
			
			// save item to database and reload
			$this->ratingRepository->add($item);
			$this->persistenceManager->persistAll();
			$item->setCrdate($GLOBALS['EXEC_TIME']);
			
			// add message
			$messages[] = array( 
				"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
				"title" => LocalizationUtility::translate(self::LLPATH.'pi2.action_rate',$this->extensionName),
				"text" => LocalizationUtility::translate(self::LLPATH.'pi2.action_rate.success',$this->extensionName),
				"type" => FlashMessage::OK
			);
		}
						
		// unset register object
		unset($rate);
		
		// end request		
		if($ajax){
			
			if(count($messages)){
				
				// set flash messages
				$this->helperService->setFlashMessages($this->flashMessageContainer,$messages);
				
			} else {
							
				$this->view = NULL;
				header ("HTTP/1.0 200 Ok");
				exit();
			}
			
		} else {
			
			if(count($messages)){
				
				// set flash messages
				$this->helperService->setFlashMessages($this->flashMessageContainer,$messages);
				
			} else {
							
				$this->view = NULL;
			}
			
		}		
	}
			
	/**
	 * action unrate
	 *
	 * @param \int $uid uid
	 * @param \string $hash hash
	 * @param \boolean $ajax is ajax request?
	 * @param \string $settings settings
	 * @return void
	 */
	public function unrateAction($uid = 0, $hash = "", $ajax = FALSE, $settings = NULL) {		
		
		if($uid>0){
			
			echo 
			
			$item = $this->ratingRepository->findByUid($uid);
			
			if(is_object($item)){
				
				if($hash==$item->getHash()){
					
					$redirectUri = $item->getUrlForeign()."#ratingform";
					
					// remove item from database
					$this->ratingRepository->remove($item);
					$this->persistenceManager->persistAll();
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
						"title" => LocalizationUtility::translate(self::LLPATH.'pi2.action_unrate',$this->extensionName),
						"text" => LocalizationUtility::translate(self::LLPATH.'pi2.action_unrate.success',$this->extensionName),
						"type" => FlashMessage::OK
					);
					
					// set flash messages
					$this->helperService->setFlashMessages($this->flashMessageContainer,$messages);
					
					// end request		
					if(!$ajax){
												
						// redirect to target url
						$this->redirectToUri($redirectUri);
						
					}
				}
			} else {
				$this->view = NULL;
			}
		} else {
			$this->view = NULL;
		}
	}
		
	/**
	 * action error
	 *
	 * @return void
	 */
	public function errorAction() {				
		
		$this->view->assign('action', 'error');		
	}		
	
	/**
	 * Returns storage pids
	 *
	 * @return \array
	 */
	public function getStoragePids() {
		return $this->storagePids;
	}

	/**
	 * Set storage pids
	 *
	 * @param \array $storagePids storage pids
	 * @return void
	 */
	public function setStoragePids($storagePids) {
		$this->storagePids = $storagePids;
	}
		
	/**
	 * Returns ext conf
	 *
	 * @return \array
	 */
	public function getExtConf() {
		return $this->extConf;
	}

	/**
	 * Set ext conf
	 *
	 * @param \array $extConf ext conf
	 * @return void
	 */
	public function setExtConf($extConf) {
		$this->extConf = $extConf;
	}
}
?>