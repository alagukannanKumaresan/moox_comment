<?php
namespace DCNGmbH\MooxComment\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Dominic Martin <dm@dcn.de>, DCN GmbH
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

use \TYPO3\CMS\Core\SingletonInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility; 
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class HelperService implements SingletonInterface {
	
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
	 * flexFormService
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager	
	 */
	protected $flexFormService;
	
	/**
	 * pageRepository
	 *
	 * @var \TYPO3\CMS\Frontend\Page\PageRepository	
	 */
	protected $pageRepository;
	
	/**
	 * contentRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\ContentRepository	 
	 */
	protected $contentRepository;
	
	/**
	 * commentRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\CommentRepository	
	 */
	//protected $commentRepository;
	
	/**
	 * ratingRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RatingRepository	
	 */
	//protected $ratingRepository;
	
	/**
	 * reviewRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\ReviewRepository	
	 */
	//protected $reviewRepository;
	
	/**
	 * newsRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\CommentRepository	
	 */
	protected $newsRepository;
	
	/**
	 * configuration
	 *
	 * @var \array	
	 */
	protected $configuration;
	
	/**
	 * extConf
	 *
	 * @var boolean
	 */
	protected $extConf;
	
	/**
	 * storagePids
	 *
	 * @var array 	
	 */
	protected $storagePids;
	
	/**
	 * autoDetectionOrder
	 *
	 * @var string 	
	 */
	protected $autoDetectionOrder;
	
	/**
	 * foreignType
	 *
	 * @var string 	
	 */
	protected $foreignType;
	
	/**
	 * pageUid
	 *
	 * @var int 	
	 */
	protected $pageUid;
	
	/**
	 * contentUid
	 *
	 * @var int 	
	 */
	protected $contentUid;
	
	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang.xlf:';
		
	/**
     *
     * @return void
     */
    public function initialize() {								
		
		// initialize object manager
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');		
		
		// initialize configuration manager
		$this->configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface');
		
		// initialize flex form service
		$this->flexFormService = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\FlexFormService');
		
		// initialize page repository
		$this->pageRepository = $this->objectManager->get('TYPO3\CMS\Frontend\Page\PageRepository');
		
		// initialize content repository
		$this->contentRepository = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Repository\ContentRepository');
		
		// initialize comment repository
		//$this->commentRepository = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Repository\CommentRepository');
		
		// initialize rating repository
		//$this->ratingRepository = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Repository\RatingRepository');
		
		// initialize review repository
		//$this->reviewRepository = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Repository\ReviewRepository');
		
		// initialize news repository
		if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_news')){
			$this->newsRepository = $this->objectManager->get('DCNGmbH\MooxComment\Domain\Repository\NewsRepository');
		}
		
		// get typoscript configuration
		$this->configuration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,"MooxComment");		
		
		// get extensions's configuration
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);
				
    }	
		
	/**
	 * set flash messages
	 *
	 * @param \mixed &$flashMessageContainer
	 * @param \array $messages
	 * @return void
	 */
	public function setFlashMessages(&$flashMessageContainer = NULL, $messages = array()) {				
		
		if($flashMessageContainer){
		
			// set flash messages
			foreach($messages AS $message){
				if(!is_array($message)){
					$message = array();
				}
				if($message['text']==""){
					$message['text'] = "Unbekannter Fehler / Unknown error";
				}				
				if($message['icon']!="" && $message['title']!=""){
					$message['title'] = $message['icon'].$message['title'];
				}
				$flashMessageContainer->add($message['text'],($message['title']!="")?$message['title'].": ":"",$message['type'],true);				
			}
		}
	}
	
	/**
	 * check dynamic form fields
	 *
	 * @param \array $fields fields
	 * @param \array $item item
	 * @param \array &$messages messages
	 * @param \array &$errors errors
	 * @return void
	 */
	public function checkFields($fields = array(), $item = array(), &$messages, &$errors){
		
		// initialize
		$this->initialize();
				
		// check fields
		foreach($fields AS $field){
			
			$msgtitle = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'],$this->extensionName);
			// set fallback title
			if(!$msgtitle){
				$msgtitle = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'],$field['extkey']);
			}
				
			
			// check required fields only		
			if(!in_array($field['config']['type'],array("file")) && ($field['config']['required'] || isset($field['config']['maxlength']) || isset($field['config']['minlength']) || isset($field['config']['limit-low']) || isset($field['config']['limit-high']) || (!$field['config']['required'] && in_array($field['config']['validator'],array("email"))))){
				
				// check if field has a value
				if($field['config']['required'] && (trim($item[$field['key']])=="" || ($field['key']=="gender" && trim($item[$field['key']])==0))){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.empty',$this->extensionName);
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_news_frontend",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.empty',$field['extkey']);									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.empty',$this->extensionName);
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;	
				// check if field value smaller than maxlength
				} elseif(trim($item[$field['key']])!="" && isset($field['config']['maxlength']) && strlen(trim($item[$field['key']]))>$field['config']['maxlength']){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.too_long',$this->extensionName,array($field['config']['maxlength']));
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.too_long',$field['extkey'],array($field['config']['maxlength']));									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.too_long',$this->extensionName,array($field['config']['maxlength']));
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;
					
				// check if field value larger than minlength
				} elseif(trim($item[$field['key']])!="" && isset($field['config']['minlength']) && strlen(trim($item[$field['key']]))<$field['config']['minlength']){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.too_short',$this->extensionName,array($field['config']['minlength']));
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.too_short',$field['extkey'],array($field['config']['minlength']));									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.too_short',$this->extensionName,array($field['config']['minlength']));
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;
				
				// check if field value greater than lowlimit
				} elseif(trim($item[$field['key']])!="" && isset($field['config']['limit-low']) && trim($item[$field['key']])<$field['config']['limit-low']){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.too_small',$this->extensionName,array($field['config']['limit-low']));
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.too_small',$field['extkey'],array($field['config']['limit-low']));									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.too_small',$this->extensionName,array($field['config']['limit-low']));
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;
				
				// check if field value smaller than highlimit
				} elseif(trim($item[$field['key']])!="" && isset($field['config']['limit-high']) && trim($item[$field['key']])>$field['config']['limit-high']){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.too_large',$this->extensionName,array($field['config']['limit-high']));
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.too_large',$field['extkey'],array($field['config']['limit-high']));									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.too_large',$this->extensionName,array($field['config']['limit-high']));
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;
				
				// check if email is valid
				} elseif(trim($item[$field['key']])!="" && $field['config']['validator']=="email" && !GeneralUtility::validEmail(trim($item[$field['key']]))){
					
					// prepare message
					$message = LocalizationUtility::translate(self::LLPATH.'form.'.$field['key'].'.error.invalid',$this->extensionName);
					
					// set fallback message
					if(!$message){
						$message = LocalizationUtility::translate(str_replace("moox_comment",$field['extkey'],self::LLPATH).'form.'.$field['key'].'.error.invalid',$field['extkey']);									
					}
					if(!$message){
						$message = LocalizationUtility::translate(self::LLPATH.'form.error.invalid',$this->extensionName);
					}
					
					// add message
					$messages[] = array( 
						"icon" => '<span class="glyphicon glyphicon-warning-sign icon-alert" aria-hidden="true"></span>',
						"title" => $msgtitle,
						"text" => $message,
						"type" => FlashMessage::ERROR
					);	

					// set error
					$errors[$field['key']] = true;							
				} 			
			}
		}		
	}
	
	/**
	 * prepare mail template and render mail body
	 *
	 * @param \DCNGmbH\MooxCommunity\Domain\Mode\Template $template template
	 * @param array $variables variables
	 * @return string $emailBody email body
	 */
	public function prepareMailBody($template, $variables) {
		
		// initialize
		$this->initialize();
		
		if (!empty($this->extConf['mailRenderingPartialRoot'])){
			$partialRootPath = GeneralUtility::getFileAbsFileName($this->extConf['mailRenderingPartialRoot']);
			if(!is_dir($partialRootPath)){
				unset($partialRootPath);	
			} 
		} 
			
		if($partialRootPath==""){
			$partialRootPath = GeneralUtility::getFileAbsFileName(str_replace("Backend/","",$this->configuration['view']['partialRootPath'])."Mail");
		}
				
		$mailBody = $this->objectManager->create('TYPO3\\CMS\\Fluid\\View\StandaloneView');
        $mailBody->setFormat('html');
        $mailBody->setTemplateSource($template->getTemplate());
		if($partialRootPath!=""){
			$mailBody->setPartialRootPath($partialRootPath);
		}
        $mailBody->assignMultiple($variables);
        $mailBody = $mailBody->render();
		
        return $mailBody;
    }
	
	/**
	 * prepare mail subject
	 *
	 * @param string $subject subject
	 * @param array $variables $variables
	 * @return string $subject
	 */
	public function prepareMailSubject($subject, $variables = NULL) {
        
		$subject = str_replace("#KW#",date("W"),$subject);
		$subject = str_replace("#YEAR#",date("Y"),$subject);
		$subject = str_replace("#MONTH#",date("m"),$subject);
		$subject = str_replace("#DAY#",date("d"),$subject);
		$subject = str_replace("#TITLE#",$variables['title'],$subject);
		$subject = str_replace("#USERNAME#",$variables['username'],$subject);
		$subject = str_replace("#NAME#",$variables['name'],$subject);
		$subject = str_replace("#FIRSTNAME#",$variables['first_name'],$subject);
		$subject = str_replace("#MIDDLENAME#",$variables['middle_name'],$subject);
		$subject = str_replace("#LASTNAME#",$variables['last_name'],$subject);
		$subject = str_replace("#EMAIL#",$variables['email'],$subject);
				
		return $subject;
	}
	
	/**
	 * send mail
	 *
	 * @param array $mail mail
	 * @return void
	 */
	public function sendMail($mail) {
		
		// initialize
		$this->initialize();
		
		if($this->extConf['useSMTP']){
			$TYPO3_CONF_VARS['MAIL']['transport'] = "smtp";
			if($this->extConf['smtpEncrypt']!="" && $this->extConf['smtpEncrypt']!="none"){
				$TYPO3_CONF_VARS['MAIL']['transport_smtp_server'] = $this->extConf['smtpEncrypt'];
			}
			$TYPO3_CONF_VARS['MAIL']['transport_smtp_encrypt']  = $this->extConf['smtpServer'];
			$TYPO3_CONF_VARS['MAIL']['transport_smtp_username'] = $this->extConf['smtpUsername'];
			$TYPO3_CONF_VARS['MAIL']['transport_smtp_password'] = $this->extConf['smtpPassword'];
		}
		
		if($mail['sender_name']==""){
			$mail['sender_name'] = $mail['sender_address'];
		}
		
		if($mail['receiver_name']==""){
			$mail['receiver_name'] = $mail['receiver_address'];
		}
		
		$sendMail = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');				
		$sendMail->setFrom(array($mail['sender_address'] => $mail['sender_name']));
		$sendMail->setTo(array($mail['receiver_address'] => $mail['receiver_name']));						
		$sendMail->setSubject($mail['subject']);
		$sendMail->setBody(strip_tags($mail['body']));
		$sendMail->addPart($mail['body'], 'text/html');
		$sendMail->send();
	}
	
	/**
	 * overwrite settings
	 *
	 * @param \array $settings
	 * @param \string $overwriteSettings
	 * @return	\array folders
	 */
	public function overwriteSettings($settings,$overwriteSettings) {
		
		if($overwriteSettings){
			$overwriteSettings = $this->decrypt($overwriteSettings);
			$overwriteSettings = trim($overwriteSettings);			
			$overwriteSettings = json_decode($overwriteSettings,TRUE);
			if(is_array($overwriteSettings)){
				$settings = $overwriteSettings;
			}
		}
		
		return $settings;		
	}
	
	/**
	 * Get array of folders with special module
	 *
	 * @param \string $module
	 * @return	\array folders
	 */
	public function getFolders($module = "") {
		
		global $BE_USER;
		
		$folders = array();
		
		if($module!=""){
		
			$query = array(
				'SELECT' => '*',
				'FROM' => 'pages',
				'WHERE' => $BE_USER->getPagePermsClause(1).' AND deleted=0 AND doktype=254 AND module="'.$module.'"',
			);
			$pages = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($query);
			
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($pages)) {
				$folders[] = $row;
			}
		}
		
		return $folders;		
	}
	
	/**
	 * Get configuration
	 *
	 * @param \string $mode
	 * @return	\array configuration
	 */
	public function getConfiguration($mode = "comment") {
		
		// initialize
		$this->initialize();
		
		$configuration = NULL;
		
		$params = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		
		if($this->foreignType=="pages" && $this->pageUid>0){
			$pageUid = $this->pageUid;
		} else {
			$pageUid = $GLOBALS["TSFE"]->id;
		}
		
		if($this->foreignType=="self"){
			$target = $this->contentRepository->findByUid($this->contentUid);
			if(is_object($target)){
				$configuration= array(
					"uid_foreign" => $target->getUid(),
					"title_foreign" => $target->getHeader(),
					"tablenames" => 'tt_content' 
				);
				$this->autoDetectionOrder = NULL;
			}
		} elseif($this->foreignType=="tt_content"){
			$target = $this->contentRepository->findByUid($this->contentUid);
			if(is_object($target)){
				$configuration= array(
					"uid_foreign" => $target->getUid(),
					"title_foreign" => $target->getHeader(),
					"tablenames" => 'tt_content' 
				);
				$this->autoDetectionOrder = NULL;
			}
		} elseif(in_array($this->foreignType,array("tx_mooxnews_domain_model_news","pages"))){
			$this->autoDetectionOrder = $this->foreignType;
		} elseif($this->autoDetectionOrder==""){
			$this->autoDetectionOrder = "pages";
		}
		
		if($this->autoDetectionOrder){
			foreach(explode(",",$this->autoDetectionOrder) AS $tablenames){
				
				if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_news') && $tablenames=="tx_mooxnews_domain_model_news"){
					if(isset($params['tx_mooxnews_pi1']) && $params['tx_mooxnews_pi1']['action']=='detail' && $params['tx_mooxnews_pi1']['news']>0){
						$target = $this->newsRepository->findByUid($params['tx_mooxnews_pi1']['news']);
						if(is_object($target)){
							if(($mode=="comment" && $target->getCommentActive()) || ($mode=="rating" && $target->getRatingActive()) || ($mode=="review" && $target->getReviewActive())){
								$configuration= array(
									"uid_foreign" => $target->getUid(),
									"title_foreign" => $target->getTitle(),
									"tablenames" => $tablenames 
								);
							}
						}
					} 
					break;
				}
				if($tablenames=="pages"){
					if($GLOBALS["TSFE"]->id>0){
						$page = $this->pageRepository->getPage($pageUid);
						if($page['uid']>0){
							$configuration= array(
								"uid_foreign" => $page['uid'],
								"title_foreign" => $page['title'],
								"tablenames" => $tablenames 
							);	
						}
					} 
					break;
				}			
			}
		}

		return $configuration;
	}
	
	/**
	 * encrypt
	 *
	 * @param \string $text
	 * @return	\array settings
	 */
	public function encrypt($text) {
		
		//Verschlüsseln
		$text = utf8_encode(trim($text));
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
		$crypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']), $text, MCRYPT_MODE_ECB, $iv);
		$crypted = base64_encode($crypted);
		return $crypted; 		
	   		
	}
	
	/**
	 * decrypt
	 *
	 * @param \string $text
	 * @return	\array settings
	 */
	public function decrypt($text) {
		
		//Entschlüsseln	
		$decrypted = base64_decode($text);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']), $decrypted, MCRYPT_MODE_ECB, $iv);
		$decrypted = trim($decrypted);
		return $decrypted;				
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
	 * Returns auto detection order
	 *
	 * @return \string
	 */
	public function getAutoDetectionOrder() {
		return $this->autoDetectionOrder;
	}

	/**
	 * Set auto detection order
	 *
	 * @param \array $autoDetectionOrder auto detection order
	 * @return void
	 */
	public function setAutoDetectionOrder($autoDetectionOrder) {
		$this->autoDetectionOrder = $autoDetectionOrder;
	}
	
	/**
	 * Returns foreign type
	 *
	 * @return \string
	 */
	public function getForeignType() {
		return $this->foreignType;
	}

	/**
	 * Set foreign type
	 *
	 * @param \array $foreignType foreign type
	 * @return void
	 */
	public function setForeignType($foreignType) {
		$this->foreignType = $foreignType;
	}
	
	/**
	 * Returns page uid
	 *
	 * @return \string
	 */
	public function getPageUid() {
		return $this->pageUid;
	}

	/**
	 * Set page uid
	 *
	 * @param \array $pageUid page uid
	 * @return void
	 */
	public function setPageUid($pageUid) {
		$this->pageUid = $pageUid;
	}
	
	/**
	 * Returns content uid
	 *
	 * @return \string
	 */
	public function getContentUid() {
		return $this->contentUid;
	}

	/**
	 * Set content uid
	 *
	 * @param \array $contentUid content uid
	 * @return void
	 */
	public function setContentUid($contentUid) {
		$this->contentUid = $contentUid;
	}
}
?>