<?php
namespace DCNGmbH\MooxComment\Domain\Repository;

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

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \TYPO3\CMS\Extbase\Persistence\QueryInterface;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class MooxRepository extends Repository {
	
	/**
	 * sets query orderings from given array/string
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface &$query
	 * @param \array|\string|null	 
	 * @return \void
	 */
	protected function setQueryOrderings(\TYPO3\CMS\Extbase\Persistence\QueryInterface &$query, $orderings = NULL){
		
		$setOrderings = array();
		
		if(!is_null($orderings) && is_string($orderings)){
			
			$orderings = array($orderings => QueryInterface::ORDER_ASCENDING);
			
		}
		
		if(is_array($orderings)){
			
			foreach($orderings AS $field => $direction){				
				
				if(strtolower($direction)=="desc"){
						
					$setOrderings[$field] = QueryInterface::ORDER_DESCENDING;				
					
				} else {
						
					$setOrderings[$field] = QueryInterface::ORDER_ASCENDING;	
				}								
			}					
			
			if(count($setOrderings)){
				
				$query->setOrderings($setOrderings);
				
			}
		}		
	}	
	
	/**
	 * sets query limits from given values
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface &$query
	 * @param \integer $offset
	 * @param \integer $limit
	 * @return \void
	 */
	protected function setQueryLimits(\TYPO3\CMS\Extbase\Persistence\QueryInterface &$query, $offset = NULL, $limit = NULL){
	
		if(is_numeric($offset)){
			
			$query->setOffset($offset);
			
		}
		
		if(is_numeric($limit)){
			
			$query->setLimit($limit);
			
		}
	}
	
	/**
	 * sets query storage page(s)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface &$query
	 * @param \array|\integer|\string $storagePages
	 * @return \void
	 */
	protected function setQueryStoragePages(\TYPO3\CMS\Extbase\Persistence\QueryInterface &$query, $storagePages = NULL){
		
		$query->getQuerySettings()->setRespectStoragePage(TRUE);
		
		if(is_string($storagePages)){
			if($storagePages=="all"){
				$query->getQuerySettings()->setRespectStoragePage(FALSE);
			} elseif(strpos($storagePages, ",")!==false){
				$query->getQuerySettings()->setStoragePageIds(explode(",",$storagePages));
			}
		} elseif(is_array($storagePages)){
			
			$setStoragePages = array();
			
			foreach($storagePages AS $storagePage){
				
				if(is_numeric($storagePage)){
					
					$setStoragePages[] = $storagePage;
					
				}
			}
			
			if(count($setStoragePages)){
				
				$query->getQuerySettings()->setStoragePageIds($setStoragePages);
				
			}			
		
		} elseif(is_numeric($storagePages)){
			
			$query->getQuerySettings()->setStoragePageIds(array($storagePages));
			
		}				
	}
	
	/**
	 * Finds all by filter (ordered)
	 *	
	 * @param \array $filter
	 * @param \array $orderings
	 * @param \integer $offset
	 * @param \integer $limit
	 * @param \array|\integer $storagePages
	 * @param \array|\boolean $enableFieldsToBeIgnored
	 * @param \boolean $rawMode if set to true, return is as an array
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByFilter($filter = NULL, $orderings = NULL, $offset = NULL, $limit = NULL, $storagePages = NULL, $enableFieldsToBeIgnored = NULL, $rawMode = FALSE) {
		
		$query = $this->createQuery();
		
		$this->setQueryStoragePages($query,$storagePages);			
		$this->setQueryOrderings($query,$orderings);		
		$this->setQueryLimits($query,$offset,$limit);
		
		if(is_array($enableFieldsToBeIgnored)){			
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
			$query->getQuerySettings()->setEnableFieldsToBeIgnored($enableFieldsToBeIgnored);
		} elseif(!is_null($enableFieldsToBeIgnored) && $enableFieldsToBeIgnored){			
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
			$query->getQuerySettings()->setEnableFieldsToBeIgnored(array("disabled","starttime","endtime"));
		}
		
		if($rawMode){
			$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		}
		
		$constraints = $this->createFilterConstraints($query,$filter);
		
		if(is_array($constraints)){
			
			$result = $query->matching(
				$query->logicalAnd($constraints)
			)->execute();
			
			//$this->debugQuery($result, TRUE);
			
			return $result;
			
		} else {
			
			return $query->execute();	
			
		}
	}
	
	/**
	* Debugs a SQL query from a QueryResult
	*
	* @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult
	* @param boolean $explainOutput
	* @return void
	*/
	public function debugQuery(\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult, $explainOutput = FALSE){
	  $GLOBALS['TYPO3_DB']->debugOuput = 2;
	  if($explainOutput){
		$GLOBALS['TYPO3_DB']->explainOutput = true;
	  }
	  $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;
	  $queryResult->toArray();
	  \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($GLOBALS['TYPO3_DB']->debug_lastBuiltQuery);
	 
	  $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = false;
	  $GLOBALS['TYPO3_DB']->explainOutput = false;
	  $GLOBALS['TYPO3_DB']->debugOuput = false;
	}
}
