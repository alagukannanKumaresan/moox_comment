<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// register frontend plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DCNGmbH.' . $_EXTKEY,
	'Pi1',
	array(
		'Pi1' => 'list,add,confirm,delete',
	),
	// non-cacheable actions
	array(
		'Pi1' => 'list,add,confirm,delete',
	)
);

// register frontend plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DCNGmbH.' . $_EXTKEY,
	'Pi2',
	array(
		'Pi2' => 'show,rate,unrate',
	),
	// non-cacheable actions
	array(
		'Pi2' => 'show,rate,unrate',
	)
);

// register frontend plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DCNGmbH.' . $_EXTKEY,
	'Pi3',
	array(
		'Pi3' => 'list,add,confirm,delete',
	),
	// non-cacheable actions
	array(
		'Pi3' => 'list,add,confirm,delete',
	)
);

// extend moox_news model classes
$GLOBALS['TYPO3_CONF_VARS']['EXT']['moox_news']['classes']['Domain/Model/News'][] = 'moox_comment';

// extend moox_shop model classes
$GLOBALS['TYPO3_CONF_VARS']['EXT']['moox_shop']['classes']['Domain/Model/Product'][] = 'moox_comment';
?>