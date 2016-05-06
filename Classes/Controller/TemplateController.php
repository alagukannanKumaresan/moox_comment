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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
 
class TemplateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	
	/**
	 * templateRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\TemplateRepository
	 * @inject
	 */
	protected $templateRepository;		
	
	/**
	 * extConf
	 *
	 * @var bool
	 */
	protected $extConf;
	
	/**
	 * initialize the controller
	 *
	 * @return void
	 */
	protected function initializeAction()
	{
		parent::initializeAction();					
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);					
	}
	
	/**
	 * action index
	 *
	 * @return void
	 */
	public function indexAction()
	{					
		$this->view->assign('items', $this->templateRepository->findAll(false));
		$this->view->assign('action', 'index');
	}
	
	/**
	 * action add
	 *	
	 * @param array $add
	 * @return void
	 */
	public function addAction($add = array())
	{					
		if(isset($add['save']) || isset($add['saveAndClose']) ||  isset($add['saveAndNew'])){
			
			$item = $this->objectManager->get('DCNGmbH\\MooxComment\\Domain\\Model\\Template');
			$item->setTitle($add['title']);
			$item->setSubject($add['subject']);
			$item->setCategory($add['category']);
			$item->setTemplate($add['template']);
			
			$this->templateRepository->add($item);								
			$this->objectManager->get('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->persistAll();
			
			$this->addFlashMessage(
				'', 
				'Vorlage wurde erfolgreich gespeichert.', 
				FlashMessage::OK
			);
		}
		if(isset($add['save'])){
			$this->redirect("edit",NULL,NULL,array('uid' => $item->getUid()));
		} elseif(isset($add['saveAndClose'])){
			$this->redirect("index");
		} elseif(isset($add['saveAndNew'])){			
			$this->redirect("add");
		} else {			
			$this->view->assign('item', $add);
			$this->view->assign('categories',$this->getTemplateCategories());			
			$this->view->assign('action', 'add');
		}
	}
	
	/**
	 * action edit
	 *
	 * @param int $uid
	 * @param array $edit
	 * @return void
	 */
	public function editAction($uid = 0, $edit = array())
	{					
		if($uid>0){
		
			$item = $this->templateRepository->findByUid($uid);
			
			if(!count($edit)){
				$edit['title'] = $item->getTitle();
				$edit['subject'] = $item->getSubject();
				$edit['category'] = $item->getCategory();
				$edit['template'] = $item->getTemplate();				
				$edit['uid'] = $item->getUid();				
			}
						
			if(isset($edit['save']) || isset($edit['saveAndClose']) ||  isset($edit['saveAndNew'])){				
				
				$item->setTitle($edit['title']);
				$item->setSubject($edit['subject']);
				$item->setCategory($edit['category']);
				$item->setTemplate($edit['template']);
				
				$this->templateRepository->update($item);								
				$this->objectManager->get('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->persistAll();
				
				$this->addFlashMessage(
					'', 
					'Änderungen wurden erfolgreich gespeichert.', 
					FlashMessage::OK
				);
			}
			if(isset($edit['saveAndClose'])){
				$this->redirect("index");
			} elseif(isset($edit['saveAndNew'])){
				$this->redirect("add");
			} else {
				$this->view->assign('item', $item);
				$this->view->assign('categories',$this->getTemplateCategories());
				$this->view->assign('action', 'edit');
				$this->view->assign('uid', $uid);
			}
		} else {
			$this->redirect("index");
		}
	}
	
	/**
	 * action delete
	 *	
	 * @param int $uid
	 * @return void
	 */
	public function deleteAction($uid = 0)
	{					
		if($uid>0){
		
			$item = $this->templateRepository->findByUid($uid);
			
			$this->templateRepository->remove($item);
			
			$this->objectManager->get('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->persistAll();
			
			$this->addFlashMessage(
				'', 
				'Vorlage wurde gelöscht.', 
				FlashMessage::OK
			);
						
		} 
		
		$this->redirect("index");
	}
	
	/**
	 * action preview iframe
	 *
	 * @param int $uid
	 * @return void
	 */
	public function previewIframeAction($uid = 0)
	{									
		if($uid>0){
		
			$template = $this->templateRepository->findByUid($uid);
			
			$data['item']['name'] = "Hans Mustermann";
			$data['item']['email'] = "email@example.net";
			$data['item']['title'] = "Dies ist ein Kommentar";
			$data['item']['comment'] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
			$data['item']['rating'] = "5.3";
			
			$data['confirm-url'] = "http://example.net/confirm.html";
			$data['delete-url']	= "http://example.net/delete.html";
			
			$data['target']['type'] = "tx_mooxnews_domain_model_news";
			$data['target']['uid'] = "1234";
			$data['target']['title'] = "Eine Kommentarfunktion ist cool";
			$data['target']['url'] = "http://example.net/target.html";
			
			$data['user']['gender'] = 1;
			$data['user']['title'] = "Dr.";
			$data['user']['username'] = "hans.mustermann";
			$data['user']['name'] = "Hans Mustermann";
			$data['user']['auto_name'] = "Hans Mustermann";
			$data['user']['first_name'] = "Hans";
			$data['user']['middle_name'] = "Jürgen";
			$data['user']['last_name'] = "Mustermann";
			$data['user']['email'] = "email@example.net";
			
			$data['receiver']['gender'] = 1;
			$data['receiver']['title'] = "Dr.";
			$data['receiver']['username'] = "hans.mustermann";
			$data['receiver']['name'] = "Hans Mustermann";
			$data['receiver']['auto_name'] = "Hans Mustermann";
			$data['receiver']['first_name'] = "Hans";
			$data['receiver']['middle_name'] = "Jürgen";
			$data['receiver']['last_name'] = "Mustermann";
			$data['receiver']['email'] = "email@example.net";
			
			if (!empty($this->extConf['mailRenderingPartialRoot'])){
				$partialRootPath = GeneralUtility::getFileAbsFileName($this->extConf['mailRenderingPartialRoot']);
				if(!is_dir($partialRootPath)){
					unset($partialRootPath);	
				} 
			} 
			
			if($partialRootPath==""){
				$conf = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
				$partialRootPath = GeneralUtility::getFileAbsFileName(str_replace("Backend/","",$conf['view']['partialRootPaths'][0])."Mail");
			}
			
			$previewView = $this->objectManager->create('TYPO3\\CMS\\Fluid\\View\StandaloneView');
			$previewView->setFormat('html');     
			$previewView->setTemplateSource($template->getTemplate());
			if($partialRootPath!=""){
				$previewView->setPartialRootPath($partialRootPath);
			}
			$previewView->assignMultiple($data);
			$preview = $previewView->render();
			
			$this->view->assign('preview', $preview);
		} else {
			$this->view->assign('preview', "Vorschau kann nicht angezeigt werden.");
		}
	}
	
	/**
	 * Returns template categories
	 *
	 * @return array
	 */
	public function getTemplateCategories()
	{		
		$categories = array();	
		
		$categories['newentry'] = "Benachrichtiguns/Bestätigungs-Mail";		
		
		return $categories;
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