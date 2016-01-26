<?php
namespace DCNGmbH\MooxComment\ViewHelpers\Widget\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Dominic Martin <dm@dcn.de>, DCN GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Extbase\Persistence\QueryInterface; 
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PaginateController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {

	/**
	 * @var array
	 */
	protected $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'maximumNumberOfLinks' => 99, 'templatePath' => '');

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	protected $objects;

	/**
	 * @var integer
	 */
	protected $currentPage = 1;

	/**
	 * @var string
	 */
	protected $templatePath = '';

	/**
	 * @var integer
	 */
	protected $numberOfPages = 1;

	/**
	 * @var integer
	 */
	protected $maximumNumberOfLinks = 99;
	
	/**
	 * @var array
	 */
	protected $order = array();

	/** @var integer */
	protected $initialOffset = 0;
	
	/** @var integer */
	protected $initialLimit = 0;

	/**
	 * Initialize the action and get correct configuration
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->objects = $this->widgetConfiguration['objects'];
		\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
			$this->configuration,
			(array)$this->widgetConfiguration['configuration'], TRUE);

		$itemsPerPage = (integer)$this->configuration['itemsPerPage'];
		if ($itemsPerPage === 0) {
			throw new \RuntimeException('The itemsPerPage is 0 which is not allowed. Please also add "list.paginate.itemsPerPage" to the TS setting settings.overrideFlexformSettingsIfEmpty!', 1400741142);
		}
		
		$this->numberOfPages = intval(ceil(count($this->objects) / $itemsPerPage));
		$this->maximumNumberOfLinks = (integer)$this->configuration['maximumNumberOfLinks'];
		$this->order = $this->configuration['order'];
		$this->templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->configuration['templatePath']);

		if (isset($this->widgetConfiguration['initial']['offset'])) {
			$this->initialOffset = (int)$this->widgetConfiguration['initial']['offset'];
		}
		if (isset($this->widgetConfiguration['initial']['limit'])) {
			$this->initialLimit = (int)$this->widgetConfiguration['initial']['limit'];
		}
	}

	/**
	 * Main action
	 *
	 * @param integer $currentPage
	 * @param array $order
	 * @return void
	 */
	public function indexAction($currentPage = 1, $order = NULL) {
		// set current page
		$this->currentPage = (integer)$currentPage;
		if ($this->currentPage < 1) {
			$this->currentPage = 1;
		}

		if ($this->currentPage > $this->numberOfPages) {
			// set $modifiedObjects to NULL if the page does not exist
			$modifiedObjects = NULL;
		} else {
			// modify query
			$itemsPerPage = (integer)$this->configuration['itemsPerPage'];
			$query = $this->objects->getQuery();
			
			if(is_array($order) && count($order)){
				if(strtolower($order['dir'])=="desc"){
					$dir = QueryInterface::ORDER_DESCENDING;
				} else {
					$dir = QueryInterface::ORDER_ASCENDING;
				}
				$setOrderings[$order['by']] = $dir;
				
				$lookupTcaTable = $this->configuration['orderByFields'][$order['by']]['config']['model'];
				
				if($lookupTcaTable!="" && isset($GLOBALS['TCA'][$lookupTcaTable]['columns'][$order['by']]['moox']['sortable']['additional_sorting']) && $GLOBALS['TCA'][$lookupTcaTable]['columns'][$order['by']]['moox']['sortable']['additional_sorting']!=""){
					
					foreach(explode(",",$GLOBALS['TCA'][$lookupTcaTable]['columns'][$order['by']]['moox']['sortable']['additional_sorting']) AS $additionalSorting){
						
						$additionalSorting = explode(" ",$additionalSorting);
						$field = $additionalSorting[0];
						$direction = $additionalSorting[1];
						
						if(strtolower($direction)=="desc"){
						
							$setOrderings[$field] = QueryInterface::ORDER_DESCENDING;				
							
						} else {
								
							$setOrderings[$field] = QueryInterface::ORDER_ASCENDING;	
						}	
					}
				}
				
				$query->setOrderings($setOrderings);
				$this->order = $order;
			} else {
				if(strtolower($this->order['dir'])=="desc"){
					$dir = QueryInterface::ORDER_DESCENDING;
				} else {
					$dir = QueryInterface::ORDER_ASCENDING;
				}
				$query->setOrderings(
						array(
							$this->order['by'] => $dir,							
						)
				);
			}			
			if($this->currentPage === $this->numberOfPages && $this->initialLimit > 0) {
				$difference = $this->initialLimit - ((integer)($itemsPerPage * ($this->currentPage - 1)));
				if ($difference > 0) {
					$query->setLimit($difference);
				} else {
					$query->setLimit($itemsPerPage);
				}
			} else {
				$query->setLimit($itemsPerPage);
			}

			if ($this->currentPage > 1) {
				$offset = (integer)($itemsPerPage * ($this->currentPage - 1));
				$offset = $offset + $this->initialOffset;
				$query->setOffset($offset);
			} elseif($this->initialOffset > 0) {
				$query->setOffset($this->initialOffset);
			}
			$modifiedObjects = $query->execute();
		}

		$this->view->assign('contentArguments', array(
			$this->widgetConfiguration['as'] => $modifiedObjects
		));
		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination($this->order));
		
		if (!empty($this->templatePath)) {
			$this->view->setTemplatePathAndFilename($this->templatePath);
		}
	}

	/**
	 * Returns an array with the keys "pages", "current", "numberOfPages", "nextPage" & "previousPage"
	 *
	 * @param array $order
	 * @return array
	 */
	protected function buildPagination($order = array()) {
		$this->calculateDisplayRange();
		$pages = array();
		for ($i = $this->displayRangeStart; $i <= $this->displayRangeEnd; $i++) {
			$pages[] = array('number' => $i, 'isCurrent' => $i === $this->currentPage);
		}
		$pagination = array(
			'order' => $order,
			'pages' => $pages,
			'current' => $this->currentPage,
			'numberOfPages' => $this->numberOfPages,
			'displayRangeStart' => $this->displayRangeStart,
			'displayRangeEnd' => $this->displayRangeEnd,
			'hasLessPages' => $this->displayRangeStart > 2,
			'hasMorePages' => $this->displayRangeEnd + 1 < $this->numberOfPages
		);
		if ($this->currentPage < $this->numberOfPages) {
			$pagination['nextPage'] = $this->currentPage + 1;
		}
		if ($this->currentPage > 1) {
			$pagination['previousPage'] = $this->currentPage - 1;
		}
		return $pagination;
	}

	/**
	 * If a certain number of links should be displayed, adjust before and after
	 * amounts accordingly.
	 *
	 * @return void
	 */
	protected function calculateDisplayRange() {
		$maximumNumberOfLinks = $this->maximumNumberOfLinks;
		if ($maximumNumberOfLinks > $this->numberOfPages) {
			$maximumNumberOfLinks = $this->numberOfPages;
		}
		$delta = floor($maximumNumberOfLinks / 2);
		$this->displayRangeStart = $this->currentPage - $delta;
		$this->displayRangeEnd = $this->currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
		if ($this->displayRangeStart < 1) {
			$this->displayRangeEnd -= $this->displayRangeStart - 1;
		}
		if ($this->displayRangeEnd > $this->numberOfPages) {
			$this->displayRangeStart -= $this->displayRangeEnd - $this->numberOfPages;
		}
		$this->displayRangeStart = (integer)max($this->displayRangeStart, 1);
		$this->displayRangeEnd = (integer)min($this->displayRangeEnd, $this->numberOfPages);
	}
}
