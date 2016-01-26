<?php
namespace DCNGmbH\MooxComment\ViewHelpers;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper; 
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class GetCommentsInfoViewHelper extends AbstractViewHelper {
	
	/**
	 * commentRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\CommentRepository
	 * @inject	 
	 */
	protected $commentRepository;		
	
	/**
	 * Get Info
	 *
	 * @param integer $uid
	 * @param string $tablenames
	 * @param string $as output variable	
	 * @return array info
	 */
	public function render($uid,$tablenames,$as = NULL) {
		
		$info = array();

		$filter['uid_foreign'] 	= $uid;
		$filter['tablenames'] 	= $tablenames;
		$filter['confirmed']	= true;
		
		// get items			
		$items = $this->commentRepository->findByFilter($filter,NULL,NULL,NULL,"all");
		
		$info['count'] = $items->count();
		
		if (TRUE === empty($as)) {
			return $info;
		} else {			
			$this->templateVariableContainer->add($as, $info);			
		}		
		
	}
}
