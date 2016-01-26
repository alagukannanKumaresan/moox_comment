<?php
namespace DCNGmbH\MooxComment\Domain\Repository;

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

/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ContentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	protected $defaultOrderings = array ('sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);	
	
	/**
	 * Find by list type
	 *	
	 * @param string $listType of record	 
	 * @return \DCNGmbH\MooxComment\Domain\Model\Content
	 */
	public function findByListType($listType = "") {
		
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