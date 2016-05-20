<?php
namespace DCNGmbH\MooxComment\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
 
class MooxRepository extends Repository
{	
	/**
	 * sets query orderings from given array/string
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface &$query
	 * @param array|string|null	 
	 * @return void
	 */
	protected function setQueryOrderings(QueryInterface &$query, $orderings = NULL)
	{		
		$setOrderings = [];
		
		if(!is_null($orderings) && is_string($orderings)){
			
			$orderings = [$orderings => QueryInterface::ORDER_ASCENDING];
			
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
	 * @param int $offset
	 * @param int $limit
	 * @return void
	 */
	protected function setQueryLimits(QueryInterface &$query, $offset = NULL, $limit = NULL)
	{	
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
	 * @param array|integer|string $storagePages
	 * @return void
	 */
	protected function setQueryStoragePages(QueryInterface &$query, $storagePages = NULL)
	{		
		$query->getQuerySettings()->setRespectStoragePage(TRUE);
		
		if(is_string($storagePages)){
			if($storagePages=="all"){
				$query->getQuerySettings()->setRespectStoragePage(FALSE);
			} elseif(strpos($storagePages, ",")!==false){
				$query->getQuerySettings()->setStoragePageIds(explode(",",$storagePages));
			}
		} elseif(is_array($storagePages)){
			
			$setStoragePages = [];
			
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
	 * @param array $filter
	 * @param array $orderings
	 * @param int $offset
	 * @param int $limit
	 * @param array|int $storagePages
	 * @param array|bool $enableFieldsToBeIgnored
	 * @param bool $rawMode if set to true, return is as an array
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByFilter($filter = NULL, $orderings = NULL, $offset = NULL, $limit = NULL, $storagePages = NULL, $enableFieldsToBeIgnored = NULL, $rawMode = FALSE)
	{		
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
		
		$constraints = $this->createFilterConstraints($query,$filter);
		
		if(is_array($constraints)){
			
			$result = $query->matching(
				$query->logicalAnd($constraints)
			)->execute($rawMode);
			
			return $result;
			
		} else {
			
			return $query->execute($rawMode);	
			
		}
	}
	
	/**
	 * Debugs a SQL query from a QueryResult
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult
	 * @param bool $explainOutput
	 * @return void
	 */
	public function debugQuery(\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult, $explainOutput = FALSE)
	{
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
