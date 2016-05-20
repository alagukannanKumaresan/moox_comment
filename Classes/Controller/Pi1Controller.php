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
use DCNGmbH\MooxComment\Domain\Model\Comment;
 
class Pi1Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
	 * @var \DCNGmbH\MooxComment\Domain\Repository\FrontendUserGroupRepository
	 * @inject
	 */
	protected $frontendUserGroupRepository;
	
	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\TemplateRepository
	 * @inject
	 */
	protected $templateRepository;
	
	/**
	 * @var \DCNGmbH\MooxComment\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;
		
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
		
		$this->fields['name'] = [
			'key' => 'email',
			'extkey' => 'moox_comment',
			'config' => [
				'type' => 'text',
				'validate' => 1,				
				'data' => [
					'data-type' => 'text',
					'data-id' => 'name',
					'data-label' => LocalizationUtility::translate(self::LLPATH.'form.name',$this->extensionName),		
				]
			]			
		];
		
		$this->fields['email'] = [
			'key' => 'email',
			'extkey' => 'moox_comment',			
			'config' => [
				'type' => 'email',
				'validate' => 1,
				'validator' => 'email',
				'data' => [
					'data-type' => 'email',
					'data-id' => 'email',
					'data-validator' => 'email',
					'data-label' => LocalizationUtility::translate(self::LLPATH.'form.email',$this->extensionName),		
				]
			]
		];
		
		$this->fields['title'] = [
			'key' => 'title',
			'extkey' => 'moox_comment',			
			'config' => [
				'type' => 'text',
				'validate' => 0,				
				'data' => [
					'data-type' => 'text',
					'data-id' => 'title',				
					'data-label' => LocalizationUtility::translate(self::LLPATH.'form.title',$this->extensionName),		
				]
			]
		];
		
		$this->fields['comment'] = [
			'key' => 'comment',
			'extkey' => 'moox_comment',
			'config' => [
				'type' => 'text',
				'validate' => 0,
				'rows' => 5,
				'data' => [
					'data-type' => 'text',
					'data-id' => 'comment',				
					'data-label' => LocalizationUtility::translate(self::LLPATH.'form.comment',$this->extensionName),	
				]
			]
		];
		
		if($this->settings['nameRequired']){
			$this->fields['name']['config']['required'] = 1;
			$this->fields['name']['config']['validate'] = 1;
			$this->fields['name']['config']['data']['data-required'] = 1;
		}
		
		if($this->settings['emailRequired']){
			$this->fields['email']['config']['required'] = 1;
			$this->fields['email']['config']['validate'] = 1;
			$this->fields['email']['config']['data']['data-required'] = 1;
		}
		
		if($this->settings['titleRequired']){
			$this->fields['title']['config']['required'] = 1;
			$this->fields['title']['config']['validate'] = 1;
			$this->fields['title']['config']['data']['data-required'] = 1;
		}
		
		if($this->settings['commentRequired']){
			$this->fields['comment']['config']['required'] = 1;
			$this->fields['comment']['config']['validate'] = 1;
			$this->fields['comment']['config']['data']['data-required'] = 1;
		}
		
		$this->pagination['pages'] = [10,25,50,100,250,500];
		
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
	 * action list
	 *
	 * @param array $filter filter
	 * @param int $offset filter
	 * @param int $limit filter
	 * @param array $orderings orderings
	 * @param string $settings settings
	 * @return void
	 */
	public function listAction($filter = [],$offset = 0, $limit = NULL, $orderings = NULL, $settings = NULL)
	{		
		// init action arrays and booleans
		$messages = [];
		$errors = [];	
		$moderators = [];
		$isModerator = false;
		$isViewable = false;
		$isCreateable = false;
		$isConfirmable = false;		
		$isDeleteable = false;
		
		// overwrite settings
		$this->settings = $this->helperService->overwriteSettings($this->settings,$settings);								
		
		// set orderings
		if(!$orderings){
			$orderings = ["crdate"=>"DESC"];
		}
		
		// set limit
		if(!$limit){
			if((int)$this->settings['itemsPerPage']>0){
				$limit = (int)$this->settings['itemsPerPage'];
			} else {
				$limit = 3;
			}
		}
		
		// get configuration
		if(isset($filter['uid_foreign']) && is_numeric($filter['uid_foreign']) && $filter['uid_foreign']>0 && isset($filter['tablenames']) && $filter['tablenames']!=""){			
			$configuration= [
				"uid_foreign" => $filter['uid_foreign'],
				"tablenames" => $filter['tablenames'] 
			];
		} else {
			$configuration = $this->helperService->getConfiguration("comment");
		}
		
		// check and get logged in user
		if(TRUE === $this->accessControllService->hasLoggedInFrontendUser()) {
			$feUser = $this->frontendUserRepository->findByUid($this->accessControllService->getFrontendUserUid());	
			if(is_object($feUser)){
				// get moderators
				if($this->settings['feGroupsAdmin']){
					$feGroupsAdmin = explode(",",$this->settings['feGroupsAdmin']);
					if(count($feGroupsAdmin)){
						$usergroups = [];
						foreach($this->frontendUserGroupRepository->findByUidList($feGroupsAdmin) AS $usergroup){
							$usergroups[] = $usergroup;
						}
						$moderators = $this->frontendUserRepository->findByUsergroups($usergroups);
						// check if user is moderator
						foreach($moderators AS $moderator){
							if($feUser->getUid()==$moderator->getUid()){
								$isModerator = true;
								$isConfirmable = true;
								$isDeleteable = true;
								break;
							}
						}
						
					}
				}
				if(!$isModerator && $this->settings['usersCanDeleteOwnComments']){
					$isDeleteable = true;
				}
			}
		}
		if(!$this->settings['onlyLoggedInUsersCanSeeComments'] || ($this->settings['onlyLoggedInUsersCanSeeComments'] && $feUser)){	
			$isViewable = true;			
		}
		if(!$this->settings['onlyLoggedInUsersCanComment'] || ($this->settings['onlyLoggedInUsersCanComment'] && $feUser)){	
			$isCreateable = true;			
		}
		
		// if configuration is valid
		if($configuration && $isViewable){
					
			// if user is logged in
			if($feUser){
				$this->view->assign('feUser', $feUser);				
			} else {
				$this->view->assign('feUser', NULL);
			}
			
			$filter['uid_foreign'] = $configuration['uid_foreign'];
			$filter['tablenames'] = $configuration['tablenames'];
			if($isModerator){
				$filter['isModerator'] = true;
			} else {
				$filter['confirmed'] = true;
			}
						
			// get items			
			$items = $this->commentRepository->findByFilter($filter,$orderings,$offset,$limit,"all");
			
			// get item count			
			$count = $this->commentRepository->findByFilter($filter,$orderings,NULL,NULL,"all")->count();
			
			$this->view->assign('configuration', $configuration);
			$this->view->assign('pagination', $this->pagination);
			$this->view->assign('count', $count);
			$this->view->assign('fields', $this->fields);
			$this->view->assign('filter', $filter);
			$this->view->assign('orderings', $orderings);
			$this->view->assign('items', $items);
			$this->view->assign('limit', $limit);
			$this->view->assign('offset', $offset);			
			$this->view->assign('uri', $this->uriBuilder->getRequest()->getRequestUri());
			$this->view->assign('ajaxSettings', $this->helperService->encrypt(json_encode($this->settings)));	
			$this->view->assign('isModerator', $isModerator);
			$this->view->assign('isViewable', $isViewable);
			$this->view->assign('isCreateable', $isCreateable);
			$this->view->assign('isConfirmable', $isConfirmable);			
			$this->view->assign('isDeleteable', $isDeleteable);
			$this->view->assign('action', 'list');
			
		} else {
			$this->view = NULL;
		}		
	}
	
	/**
	 * action add
	 *
	 * @param array $add form data
	 * @param bool $ajax is ajax request?
	 * @param string $settings settings
	 * @return void
	 */
	public function addAction($add = NULL, $ajax = FALSE, $settings = NULL)
	{
		// init action arrays and booleans
		$messages = [];
		$errors = [];	
		$moderators = [];
		$confirmed = false;
		$isModerator = false;
		$isConfirmable = false;
		
		// overwrite settings
		$this->settings = $this->helperService->overwriteSettings($this->settings,$settings);
		
		// get moderators
		if($this->settings['feGroupsAdmin']){
			$feGroupsAdmin = explode(",",$this->settings['feGroupsAdmin']);
			if(count($feGroupsAdmin)){
				$usergroups = [];
				foreach($this->frontendUserGroupRepository->findByUidList($feGroupsAdmin) AS $usergroup){
					$usergroups[] = $usergroup;
				}
				$moderators = $this->frontendUserRepository->findByUsergroups($usergroups);
			}
		}
		
		// check and get logged in user
		if(TRUE === $this->accessControllService->hasLoggedInFrontendUser()) {
			$feUser = $this->frontendUserRepository->findByUid($this->accessControllService->getFrontendUserUid());
			if(is_object($feUser) && count($moderators)){
				// check if user is moderator
				foreach($moderators AS $moderator){
					if($feUser->getUid()==$moderator->getUid()){
						$isModerator = true;
						$isConfirmable = true;					
						break;
					}
				}
			}
		}	
		
		// check fields
		$this->helperService->checkFields($this->fields,$add,$messages,$errors);	
		
		// no errors -> add comment
		if(!count($errors)){
		
			// set item values
			$item = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Model\Comment');					
			
			$item->setUidForeign($add['uid_foreign']);
			$item->setTitleForeign($add['title_foreign']);
			$item->setUrlForeign($add['url_foreign']);
			$item->setTablenames($add['tablenames']);
			
			if(!$feUser){
				$item->setName(trim($add['name']));
				$item->setEmail(trim($add['email']));
				if($this->settings['autoConfirmPublicUsers']){
					$item->setConfirmed(time());
					$confirmed = true;
				}
			} else {
				$item->setName($feUser->getName());
				$item->setEmail($feUser->getEmail());
				$item->setFeUser($feUser);
				if($this->settings['autoConfirmLoggedInUsers'] || $isConfirmable){
					$item->setConfirmed(time());
					$confirmed = true;
				}
			}
			
			$item->setTitle(trim($add['title']));
			$item->setComment(trim($add['comment']));
			
			// set pid (bugfix ignoring global setting);
			if(!$item->getPid()){
				$item->setPid($this->settings['storagePid']);
			}
			
			// save item to database and reload
			$this->commentRepository->add($item);
			$this->persistenceManager->persistAll();
			$item->setCrdate($GLOBALS['EXEC_TIME']);
			
			// need to send notifications?
			if(!$isConfirmable && count($moderators) && $this->settings['newEntryEmailTemplate']>0){
				
				// load mail template
				$template = $this->templateRepository->findByUid($this->settings['newEntryEmailTemplate']);
				
				// if mail template exists
				if($template){						
					
					// set user mail field
					if($feUser){
						$user = [];
						foreach(["username","first_name","last_name","auto_name","email","gender"] AS $fieldname){
							$getMethod = "get".GeneralUtility::underscoredToUpperCamelCase($fieldname);
							if(method_exists($feUser,$getMethod)){
								$user[$fieldname] = $feUser->$getMethod();
							}
						}
					} else {
						$user['auto_name'] = $item->getName();
						$user['email'] = $item->getEmail();
					}
					
					$subject = $this->helperService->prepareMailSubject($template->getSubject(),$user);
					$deleteUrl = $this->uriBuilder->reset()->setNoCache(true)->setTargetPageUid($GLOBALS["TSFE"]->id)->setCreateAbsoluteUri(TRUE)->uriFor('delete', array("uid" => $item->getUid(), "hash" => $item->getHash()), 'Pi1', 'MooxComment', 'Pi1');
					if(!$item->getConfirmed()){
						$confirmUrl = $this->uriBuilder->reset()->setNoCache(true)->setTargetPageUid($GLOBALS["TSFE"]->id)->setCreateAbsoluteUri(TRUE)->uriFor('confirm', array("uid" => $item->getUid(), "hash" => $item->getHash()), 'Pi1', 'MooxComment', 'Pi1');
					}
					
					foreach($moderators AS $moderator){
						
						if($moderator->getEmail()!=""){
							
							// set user mail field
							$receiver = [];
							foreach(["username","first_name","last_name","auto_name","email","gender"] AS $fieldname){
								$getMethod = "get".GeneralUtility::underscoredToUpperCamelCase($fieldname);
								if(method_exists($moderator,$getMethod)){
									$receiver[$fieldname] = $moderator->$getMethod();
								}
							}

							// set info mail array
							$mail = [
								'sender_name' => $this->settings['notificationsSenderName'],
								'sender_address' => $this->settings['notificationsSenderAddress'],
								'receiver_name' => ($moderator->getFirstName()!="")?$moderator->getFirstName()." ".$moderator->getLastName():$moderator->getLastName(),
								'receiver_address' => $moderator->getEmail(),
								'subject' => $subject,
								'pid' => $GLOBALS["TSFE"]->id,
								'confirm-url' => $confirmUrl,
								'delete-url' => $deleteUrl,
								'user' => $user,
								'receiver' => $receiver,
								'item' => [
									'name' => $item->getName(),
									'email' => $item->getEmail(),
									'title' => $item->getTitle(),
									'comment' => $item->getComment(),
								],
								'target' => [
									'type' => $item->getTablenames(),
									'uid' => $item->getUidForeign(),
									'title' => $item->getTitleForeign(),
									'url' => $item->getUrlForeign(),
								]								
							];
							
							// set mail body
							$mail['body'] = $this->helperService->prepareMailBody($template,$mail);
							
							// send mail
							$this->helperService->sendMail($mail);
						}
					}							
					
				} else {
					
					// add message
					$messages[] = [ 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add',$this->extensionName),
						"text" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add.error.no_template',$this->extensionName),
						"type" => FlashMessage::ERROR
					];							
				}
			}
			
			// add message
			if($confirmed){
				$messages[] = [ 
					"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
					"title" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add',$this->extensionName),
					"text" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add.success',$this->extensionName),
					"type" => FlashMessage::OK
				];
			} else {
				$messages[] = [ 
					"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
					"title" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add',$this->extensionName),
					"text" => LocalizationUtility::translate(self::LLPATH.'pi1.action_add.success_moderated',$this->extensionName),
					"type" => FlashMessage::OK
				];				
			}
		}
						
		// unset register object
		unset($add);
		
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
	 * action confirm
	 *
	 * @param int $uid uid
	 * @param string $hash hash
	 * @param bool $ajax is ajax request?
	 * @param string $settings settings
	 * @return void
	 */
	public function confirmAction($uid = 0, $hash = "", $ajax = FALSE, $settings = NULL)
	{		
		if($uid>0){
			
			$item = $this->commentRepository->findByUid($uid);
			
			if(is_object($item)){
				
				if($hash==$item->getHash()){
										
					$item->setConfirmed(time());
					
					// save item to database
					$this->commentRepository->update($item);
					$this->persistenceManager->persistAll();
					
					// add message
					$messages[] = [ 
						"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
						"title" => LocalizationUtility::translate(self::LLPATH.'pi1.action_confirm',$this->extensionName),
						"text" => LocalizationUtility::translate(self::LLPATH.'pi1.action_confirm.success',$this->extensionName),
						"type" => FlashMessage::OK
					];
					
					// set flash messages
					$this->helperService->setFlashMessages($this,$messages);
					
					// end request		
					if(!$ajax){
					
						// redirect to target url
						$this->redirectToUri($item->getUrlForeign()."#commentform");
						
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
	 * action delete
	 *
	 * @param int $uid uid
	 * @param string $hash hash
	 * @param bool $ajax is ajax request?
	 * @param string $settings settings
	 * @return void
	 */
	public function deleteAction($uid = 0, $hash = "", $ajax = FALSE, $settings = NULL)
	{				
		if($uid>0){
			
			$item = $this->commentRepository->findByUid($uid);
			
			if(is_object($item)){
				
				if($hash==$item->getHash()){
					
					$redirectUri = $item->getUrlForeign()."#commentform";
					
					// remove item from database
					$this->commentRepository->remove($item);
					$this->persistenceManager->persistAll();
					
					// add message
					$messages[] = [ 
						"icon" => '<span class="glyphicon glyphicon-ok icon-alert" aria-hidden="true"></span>',
						"title" => LocalizationUtility::translate(self::LLPATH.'pi1.action_delete',$this->extensionName),
						"text" => LocalizationUtility::translate(self::LLPATH.'pi1.action_delete.success',$this->extensionName),
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
	public function getStoragePids() {
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