<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Set language source file
$ll = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang_be.xlf:';

// Register Plugins
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	$ll.'pi1.title'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi2',
	$ll.'pi2.title'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi3',
	$ll.'pi3.title'
);

if (TYPO3_MODE === 'BE') {

    /***************
     * Register Main Module
     */
	$mainModuleName = "moox";
	if (!isset($TBE_MODULES[$mainModuleName])) {
        $temp_TBE_MODULES = [];
        foreach ($TBE_MODULES as $key => $val) {
            if ($key == 'web') {
                $temp_TBE_MODULES[$key] = $val;
                $temp_TBE_MODULES[$mainModuleName] = '';
            } else {
                $temp_TBE_MODULES[$key] = $val;
            }
        }
        $TBE_MODULES = $temp_TBE_MODULES;
		if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_core')) {
			$mainModuleKey 		= "DCNGmbH.moox_core";
			$mainModuleIcon 	= 'EXT:moox_core/Resources/Public/Icons/module-mooxcore-white.svg';
			$mainModuleLabels 	= 'LLL:EXT:moox_core/Resources/Private/Language/Module.xlf:moox_main_module_tab';
		} else {
			$mainModuleKey 		= "DCNGmbH.".$_EXTKEY;
			$mainModuleIcon 	= 'EXT:'.$_EXTKEY.'/Resources/Public/Moox/module-mooxcore-white.svg';
			$mainModuleLabels 	= 'LLL:EXT:'.$_EXTKEY.'/Resources/Public/Moox/Module.xlf:moox_main_module_tab';
		}
		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
			$mainModuleKey,
			$mainModuleName,
			'',
			'',
			[],
			[
				'access' => 'user,group',
				'icon'   => $mainModuleIcon,
				'labels' => $mainModuleLabels,
			]
		);
    } 

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'DCNGmbH.' . $_EXTKEY,
		$mainModuleName,	// Make module a submodule of 'moox'
		'administration',	// Submodule key
		'',	// Position
		[
			'Template' => 'index,add,edit,delete,previewIframe',			
		],
		[
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/module-mooxcomment.svg',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_init.xlf',
		]
	);
}

// Add flexforms
$pluginSignature = str_replace('_','',$_EXTKEY) . '_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi1.xml');
$pluginSignature = str_replace('_','',$_EXTKEY) . '_pi2';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi2.xml');
$pluginSignature = str_replace('_','',$_EXTKEY) . '_pi3';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi3.xml');

// Add typoscripts
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'MOOX Comment');

// Add Wizard Icons
if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['DCNGmbH\MooxComment\Hooks\Wizicon'] =
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/Wizicon.php';
}

// Icon in page tree
$TCA['pages']['columns']['module']['config']['items'][] = ['MOOX-Comments', 'mxcomment', 'EXT:moox_comment/ext_icon.svg'];
\TYPO3\CMS\Backend\Sprite\SpriteManager::addTcaTypeIcon('pages', 'contains-mxcomment', '../typo3conf/ext/moox_comment/ext_icon.svg');
?>