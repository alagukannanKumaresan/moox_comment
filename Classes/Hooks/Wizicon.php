<?php
namespace DCNGmbH\MooxComment\Hooks;

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

/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
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
	public function proc($wizardItems) {
		
		// set wizard entry for plugin pi1
		$wizardItems['plugins_tx_' . self::KEY . 'pi1'] = array(
			'icon'			=> \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi1/wizard.png',
			'title'			=> $GLOBALS['LANG']->sL(self::LLPATH.'pi1.title'),
			'description'	=> $GLOBALS['LANG']->sL(self::LLPATH.'pi1.description'),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mooxcomment_pi1'
		);		
		
		// set wizard entry for plugin pi2
		$wizardItems['plugins_tx_' . self::KEY . 'pi2'] = array(
			'icon'			=> \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi2/wizard.png',
			'title'			=> $GLOBALS['LANG']->sL(self::LLPATH.'pi2.title'),
			'description'	=> $GLOBALS['LANG']->sL(self::LLPATH.'pi2.description'),
			'params'		=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=mooxcomment_pi2'
		);	
		
		// set wizard entry for plugin pi3
		$wizardItems['plugins_tx_' . self::KEY . 'pi3'] = array(
			'icon'			=> \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath(self::KEY) . 'Resources/Public/Icons/Pi3/wizard.png',
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
