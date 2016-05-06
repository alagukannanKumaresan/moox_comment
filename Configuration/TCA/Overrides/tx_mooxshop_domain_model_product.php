<?php
defined('TYPO3_MODE') or die();

// set default language file as ll-reference
$ll = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang.xml:';

// Get the extensions's configuration
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);

// hide news default fields for new news type 
//$hideFields = "";

$tx_mooxcomment_domain_model_product = array(
	'comment_active' => array(			
		'exclude' => 0,
		'label' => $ll.'form.product.comment_active',
		'config' => array(
			'type' => 'check',
			'default' => ($extConf['commentActive'])?1:0,				
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_shop',
			'plugins' => array(
				'mooxnewsfrontend' => array(
					'add','edit'
				),
			),
			'sortable' => 0,
		),
	),
	'rating_active' => array(			
		'exclude' => 0,
		'label' => $ll.'form.product.rating_active',
		'config' => array(
			'type' => 'check',
			'default' => ($extConf['ratingActive'])?1:0,			
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_shop',
			'plugins' => array(
				'mooxnewsfrontend' => array(
					'add','edit'
				),
			),
			'sortable' => 0,
		),
	),
	'review_active' => array(			
		'exclude' => 0,
		'label' => $ll.'form.product.review_active',
		'config' => array(
			'type' => 'check',
			'default' => ($extConf['reviewActive'])?1:0,		
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_shop',
			'plugins' => array(
				'mooxnewsfrontend' => array(
					'add','edit'
				),
			),
			'sortable' => 0,
		),
	),
);

// extend moox_marketplace tca with new fields from this extension
if(true || \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_shop')){
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_mooxshop_domain_model_product', $tx_mooxcomment_domain_model_product,1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_mooxshop_domain_model_product', 'comment_active,rating_active,review_active','','after:endtime');
}

?>