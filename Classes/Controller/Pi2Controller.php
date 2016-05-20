<?php
namespace DCNGmbH\MooxComment\Controller;

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
use TYPO3\CMS\Core\Messaging\FlashMessage; 
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use DCNGmbH\MooxComment\Domain\Model\Rating;
 
class Pi2Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{	
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager;
	
	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;	

	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\TemplateRepository
	 * @inject
	 */
	protected $templateRepository;
	
	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RatingRepository
	 * @inject
	 */
	protected $ratingRepository;
		
	/**
	 * @var \DCNGmbH\MooxComment\Service\AccessControlService
	 * @inject
	 */
	protected $accessControllService;
	
	/**
	 * @var \DCNGmbH\MooxComment\Service\HelperService
	 * @inject
	 */
	protected $helperService;
	
	/**
	 * @var bool
	 */
	protected $extConf;
	
	/**
	 * @var array 	
	 */
	protected $storagePids;
	
	/**
	 * @var array 	
	 */
	protected $fields;
	
	/**
	 * @var array 	
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
    public function initializeAction()
	{					
		// execute parent initialize action
		parent::initializeAction();
		
		// load extension configuration
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);		
		
		$this->fields['rating'] = [
			'key' => 'rating',
			'extkey' => 'moox_comment',
			'config' => [
				'required' => 1,
				'validate' => 1,
				'type' => 'text',				
				'data' => [
					'data-type' => 'text',
					'data-id' => 'rating',				
					'data-label' => LocalizationUtility::translate(self::LLPATH.'form.rating',$this->extensionName),
					'data-required' => 1,
					'data-name' => "tx_mooxcomment_pi2[rate][rating]"
				]
			]
		];
		
		if($this->settings['ratingMode']=="stars"){
			$this->fields['rating']['config']['stars'] = [];
			if($this->settings['stars']<5){
				$this->settings['stars'] = 5;
			}
			if($this->settings['allowHalfStars']){
				$step = 0.5;
			} else {
				$step = 1;
			}			
			$star = $step;
			while($star<=$this->settings['stars']){
				$this->fields['rating']['config']['stars'][] = $star;
				$star = $star+$step;
			}
		}
		if($this->settings['ratingRequired']){
						
		}
		
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
	protected function initializeStorageSettings()
	{			
		// get typoscript configuration
		$configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		
		// set storage pid if set by plugin
		if($this->settings['storagePid']!=""){
			$this->setStoragePids([$this->settings['storagePid']]);
		} else {
			$this->setStoragePids([]);
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
	 * @param array $filter filter
	 * @param string $settings settings
	 * @return void
	 */
	public function showAction($filter = [],$settings = NULL)
	{		
		// init action arrays and booleans
		$messages = [];
		$errors = [];	
		$info = [];		
		$isModerator = false;
		$isViewable = false;
		$isRateable = false;
		$isUnrateable = false;				
				
		// overwrite settings
		$this->settings = $this->helperService->overwriteSettings($this->settings,$settings);												
		
		// get configuration
		if(isset($filter['uid_foreign']) && is_numeric($filter['uid_foreign']) && $filter['uid_foreign']>0 && isset($filter['tablenames']) && $filter['tablenames']!=""){			
			$configuration= [
				"uid_foreign" => $filter['uid_foreign'],
				"tablenames" => $filter['tablenames'] 
			];
		} else {
			$configuration = $this->helperService->getConfiguration("rating");
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
					[
						'uid_foreign' => $configuration['uid_foreign'],
						'tablenames' => $configuration['tablenames'],
						'feUser' => $feUser->getUid(),						
					],NULL,NULL,NULL,"all");
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
			if(in_array($this->settings['ratingMode'],["like_dislike","stars"])){
				$info['rating'] = $this->ratingRepository->findRatingInfos([
					"uid_foreign" => $configuration['uid_foreign'],
					"tablenames" => $configuration['tablenames'],
				],$this->settings['ratingMode'])[0];			
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
			$this->view->assign('fields', $this->fields);
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
	 * @param array $rate form data
	 * @param bool $ajax is ajax request?
	 * @param string $settings settings
	 * @return void
	 */
	public function rateAction($rate = NULL, $ajax = FALSE, $settings = NULL)
	{		
		// init action arrays and booleans
		$messages = [];
		$errors = [];			
		
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
			$messages[] = [
				"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
				"title" => LocalizationUtility::translate(self::LLPATH.'pi2.action_rate',$this->extensionName),
				"text" => LocalizationUtility::translate(self::LLPATH.'pi2.action_rate.success',$this->extensionName),
				"type" => FlashMessage::OK
			];
		}
						
		// unset register object
		unset($rate);
		
		// end request		
		if($ajax){
			
			if(count($messages)){
				
				// set flash messages
				$this->helperService->setFlashMessages($this,$messages);
				
			} else {
							
				$this->view = NULL;
				header ("HTTP/1.0 200 Ok");
				exit();
			}
			
		} else {
			
			if(count($messages)){
				
				// set flash messages
				$this->helperService->setFlashMessages($this,$messages);
				
			} else {
							
				$this->view = NULL;
			}
			
		}		
	}
			
	/**
	 * action unrate
	 *
	 * @param int $uid uid
	 * @param string $hash hash
	 * @param bool $ajax is ajax request?
	 * @param string $settings settings
	 * @return void
	 */
	public function unrateAction($uid = 0, $hash = "", $ajax = FALSE, $settings = NULL)
	{				
		if($uid>0){
			
			$item = $this->ratingRepository->findByUid($uid);
			
			if(is_object($item)){
				
				if($hash==$item->getHash()){
					
					$redirectUri = $item->getUrlForeign()."#ratingform";
					
					// remove item from database
					$this->ratingRepository->remove($item);
					$this->persistenceManager->persistAll();
					
					// add message
					$messages[] = [ 
						"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
						"title" => LocalizationUtility::translate(self::LLPATH.'pi2.action_unrate',$this->extensionName),
						"text" => LocalizationUtility::translate(self::LLPATH.'pi2.action_unrate.success',$this->extensionName),
						"type" => FlashMessage::OK
					];
					
					// set flash messages
					$this->helperService->setFlashMessages($this,$messages);
					
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
	public function errorAction()
	{				
		
		$this->view->assign('action', 'error');		
	}		
	
	/**
	 * Returns storage pids
	 *
	 * @return array
	 */
	public function getStoragePids()
	{
		return $this->storagePids;
	}

	/**
	 * Set storage pids
	 *
	 * @param array $storagePids storage pids
	 * @return void
	 */
	public function setStoragePids($storagePids)
	{
		$this->storagePids = $storagePids;
	}
		
	/**
	 * Returns ext conf
	 *
	 * @return array
	 */
	public function getExtConf()
	{
		return $this->extConf;
	}

	/**
	 * Set ext conf
	 *
	 * @param array $extConf ext conf
	 * @return void
	 */
	public function setExtConf($extConf)
	{
		$this->extConf = $extConf;
	}
}
?>