<?php
namespace DCNGmbH\MooxComment\ViewHelpers;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper; 

class GetCommentsInfoViewHelper extends AbstractViewHelper
{
	
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
	 * @param int $uid
	 * @param string $tablenames
	 * @param string $as output variable	
	 * @return array info
	 */
	public function render($uid,$tablenames,$as = NULL)
	{		
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
