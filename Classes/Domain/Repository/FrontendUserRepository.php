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
 
class FrontendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
{	
	/**
	 * Override default findByUid function to enable also the option to turn of
	 * the enableField setting
	 *
	 * @param int $uid id of record
	 * @param bool $respectEnableFields if set to false, hidden records are shown
	 * @return \DCNGmbH\MooxComment\Domain\Model\FrontendUser
	 */
	/*
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
	*/
	
	/**
	 * find by usergroups array
	 *
	 * @param array $usergroups array of group
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUsergroups($usergroups)
	{		
		if(count($usergroups)){
						
			$query = $this->createQuery();
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			
			$constraints = [];
			foreach($usergroups AS $usergroup){
				$constraints[] = $query->contains('usergroup', $usergroup);
			}
			
			return $query->matching(
				$query->logicalOr($constraints)
			)->execute();
		} else {
			return [];
		}
	}	
}
?>