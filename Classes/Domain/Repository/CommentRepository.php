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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DCNGmbH\MooxComment\Domain\Repository\MooxRepository;

class CommentRepository extends MooxRepository 
{
	
	protected $defaultOrderings = ['tstamp' => QueryInterface::ORDER_DESCENDING];
	
	/**
	 * Returns a constraint array created by a given filter array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $filter
	 * @param array $constraints	
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
	 */
	protected function createFilterConstraints(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query,$filter = NULL,$constraints = NULL)
	{			
		if(is_null($constraints)){			
			$constraints = [];			
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
	 * @param int $uid id of record
	 * @param bool $respectEnableFields if set to false, hidden records are shown
	 * @return \DCNGmbH\MooxComment\Domain\Model\Comment
	 */
	public function findByUid($uid, $respectEnableFields = TRUE)
	{		
		$query = $this->createQuery();
		
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setIgnoreEnableFields(!$respectEnableFields);

		return $query->matching(
			$query->logicalAnd(
				$query->equals('uid', $uid),
				$query->equals('deleted', 0)
			)
		)->execute()->getFirst();
	}	
}
?>