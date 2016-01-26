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

use DCNGmbH\MooxComment\Domain\Repository\MooxRepository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CommentRepository extends MooxRepository {
	
	protected $defaultOrderings = array ('tstamp' => QueryInterface::ORDER_DESCENDING);
	
	/**
	 * Returns a constraint array created by a given filter array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \array $filter
	 * @param \array $constraints	
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
	 */
	protected function createFilterConstraints(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query,$filter = NULL,$constraints = NULL){
				
		if(is_null($constraints)){			
			$constraints = array();			
		}
		
		if(isset($filter['uid_foreign']) && is_numeric($filter['uid_foreign']) && $filter['uid_foreign']>0 && isset($filter['tablenames']) && $filter['tablenames']!=""){			
			$constraints[] = $query->equals('uidForeign', $filter['uid_foreign']);
			$constraints[] = $query->equals('tablenames', $filter['tablenames']);
			
		} else {
			$constraints[] = $query->equals('uidForeign', 0);
			$constraints[] = $query->equals('tablenames', "dfgdfgjrezu67q348673463");
		}
		
		if(isset($filter['feUser']) && is_numeric($filter['feUser']) && $filter['feUser']>0){			
			$constraints[] = $query->equals('feUser', $filter['feUser']);			
		}
		
		if(!(isset($filter['isModerator']) && is_bool($filter['isModerator']) && $filter['isModerator']==true)){
			if(isset($filter['confirmed']) && is_bool($filter['confirmed']) && $filter['confirmed']==true){			
				$constraints[] = $query->greaterThan('confirmed', 0);			
			} else {
				$constraints[] = $query->logicalOr(
					$query->equals('confirmed', 0),
					$query->equals('confirmed', ''),
					$query->equals('confirmed', NULL)
				);
			}
		}
		
		if(count($constraints)<1){
			
			$constraints = NULL;
			
		}
		
		return $constraints;
	}	
	
	/**
	 * Override default findByUid function to enable also the option to turn of
	 * the enableField setting
	 *
	 * @param \integer $uid id of record
	 * @param \boolean $respectEnableFields if set to false, hidden records are shown
	 * @return \DCNGmbH\MooxComment\Domain\Model\Classified
	 */
	public function findByUid($uid, $respectEnableFields = TRUE) {
		
		$query = $this->createQuery();
		
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setIgnoreEnableFields(!$respectEnableFields);

		return $query->matching(
			$query->logicalAnd(
				$query->equals('uid', $uid),
				$query->equals('deleted', 0)
			))->execute()->getFirst();
	}	
}
?>