<?php
defined('TYPO3_MODE') or die();

// set default language file as ll-reference
$ll = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang.xml:';

// hide news default fields for new news type 
//$hideFields = "";

$tx_mooxcomment_domain_model_news = array(
	'comment_active' => array(			
		'exclude' => 0,
		'label' => $ll.'form.news.comment_active',
		'config' => array(
			'type' => 'check',
			'default' => 1,				
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_news',
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
		'label' => $ll.'form.news.rating_active',
		'config' => array(
			'type' => 'check',
			'default' => 1,				
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_news',
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
		'label' => $ll.'form.news.review_active',
		'config' => array(
			'type' => 'check',
			'default' => 1,				
		),
		// special moox configuration		
		'moox' => array(
			'extkey' => 'moox_comment',		
			'variant' => 'moox_news',
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
if(true || \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('moox_news')){
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_mooxnews_domain_model_news', $tx_mooxcomment_domain_model_news,1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_mooxnews_domain_model_news', 'comment_active,rating_active,review_active','','after:exclude_from_rss');
}

?>