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

class FrontendUserGroupRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
{
	
	protected $defaultOrderings = ['title' => QueryInterface::ORDER_ASCENDING];
		
	/**
	 * Find user groups by uid(list)
	 *
	 * @param array $uids uids	
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUidList($uids = [])
	{		
		$uids 	= (is_array($uids))?$uids:[$uids];		
		
		$query = $this->createQuery();
				
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		
		$constraints = [];
		foreach($uids AS $uid){
			$constraints[] = $query->equals('uid', $uid);
		}
			
		return $query->matching(
			$query->logicalOr($constraints)
		)->execute();
		
	}		
}
?>