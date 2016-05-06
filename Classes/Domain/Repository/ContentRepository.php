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

class ContentRepository extends Repository
{
	
	protected $defaultOrderings = array ('sorting' => QueryInterface::ORDER_ASCENDING);	
	
	/**
	 * Find by list type
	 *	
	 * @param string $listType of record	 
	 * @return \DCNGmbH\MooxComment\Domain\Model\Content
	 */
	public function findByListType($listType = "")
	{		
		if($listType!=""){
			
			$query = $this->createQuery();
			
			$query->getQuerySettings()->setRespectStoragePage(FALSE);
			$query->getQuerySettings()->setRespectSysLanguage(FALSE);
			$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
			
			return $query->matching(
				$query->logicalAnd(				
					$query->equals('listType', $listType),
					$query->equals('deleted', 0)
				))->execute();
		} else {
			return NULL;
		}
	}	
}
?>