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

use TYPO3\CMS\Core\Utility\GeneralUtility; 
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;  

class GetValueViewHelper extends AbstractViewHelper
{

	/**
	 * @param object $item current classified object
	 * @param string $field	
	 * @param bool $raw
	 * @return string
	 */
	public function render($item, $field, $raw = false)
	{
		$value = "";
		if($field != ""){
			$getCall = "get".GeneralUtility::underscoredToUpperCamelCase($field);
			if(method_exists($item, $getCall)){
				if($raw){
					$value = $item->$getCall();
				} else {
					$value = $item->$getCall(true);
				}
			} elseif(is_array($item)){
				$value = $item[$field];
			} 
		}
		
		return $value;
	}
}
