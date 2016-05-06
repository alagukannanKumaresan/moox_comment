<?php
namespace DCNGmbH\MooxComment\Hooks;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
 
class Wizicon {
	
	/**
	 * Extension key
	 * @var string
	 */
	const KEY = 'moox_comment';
	
	/**
	 * Path to the locallang file
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang_be.xlf:';
	
	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems The wizard items
	 * @return array array with wizard items
	 */
	public function proc($wizardItems)
	{		
		// set wizard entry for plugin pi1
		$wizardItems['plugins_tx_' . self::KEY . 'pi1'] = array(
			'icon'			=> ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi1/wizard.png',
			'title'			=> $GLOBALS['LANG']->sL(self::LLPATH.'pi1.title'),
			'description'	=> $GLOBALS['LANG']->sL(self::LLPATH.'pi1.description'),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mooxcomment_pi1'
		);		
		
		// set wizard entry for plugin pi2
		$wizardItems['plugins_tx_' . self::KEY . 'pi2'] = array(
			'icon'			=> ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi2/wizard.png',
			'title'			=> $GLOBALS['LANG']->sL(self::LLPATH.'pi2.title'),
			'description'	=> $GLOBALS['LANG']->sL(self::LLPATH.'pi2.description'),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mooxcomment_pi2'
		);	
		
		// set wizard entry for plugin pi3
		$wizardItems['plugins_tx_' . self::KEY . 'pi3'] = array(
			'icon'			=> ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi3/wizard.png',
			'title'			=> $GLOBALS['LANG']->sL(self::LLPATH.'pi3.title'),
			'description'	=> $GLOBALS['LANG']->sL(self::LLPATH.'pi3.description'),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mooxcomment_pi3'
		);	

		return $wizardItems;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/moox_comment/Classes/Hooks/Wizicon.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/moox_comment/Classes/Hooks/Wizicon.php']);
}
