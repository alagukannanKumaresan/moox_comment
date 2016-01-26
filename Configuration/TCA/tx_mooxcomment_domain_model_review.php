<?php
// Set language source file
$ll = 'LLL:EXT:moox_comment/Resources/Private/Language/locallang.xlf:';

// Get the extensions's configuration
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['moox_comment']);

// tca configuration array
$tx_mooxcomment_domain_model_review = array(
	'ctrl' => array(
		'title'	=> 'Review',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),		
		'searchFields' => 'title,comment',		
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('moox_comment').'Resources/Public/Icons/tx_mooxcomment_domain_model_review.gif',
		'hideTable' => FALSE,
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, fe_user, title, name, email, comment, rating, confirmed',
	),
	'types' => array(
		'1' => array('showitem' => 
			//'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,'. 
			'fe_user, title, name, email, comment, rating, confirmed'.						
			'--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,hidden, starttime, endtime'
		),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_mooxcomment_domain_model_review',
				'foreign_table_where' => 'AND tx_mooxcomment_domain_model_review.pid=###CURRENT_PID### AND tx_mooxcomment_domain_model_review.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),			
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),			
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),			
		),
		'cruser_id' => array(
			'label' => 'cruser_id',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'uid' => array(
			'label' => 'uid',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'pid' => array(
			'label' => 'pid',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'crdate' => array(
			'label' => 'crdate',
			'config' => array(
				'type' => 'passthrough',
			),			
		),
		'tstamp' => array(
			'label' => 'tstamp',
			'config' => array(
				'type' => 'passthrough',
			),			
		),
		'parent' => array(
			'label' => 'parent',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'uid_foreign' => array(
			'label' => 'uid_foreign',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'title_foreign' => array(
			'label' => 'title_foreign',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'url_foreign' => array(
			'label' => 'url_foreign',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'tablenames' => array(
			'label' => 'tablenames',
			'config' => array(
				'type' => 'passthrough'
			),			
		),
		'fe_user' => array(			
			'exclude' => 0,
			'label' => $ll.'form.fe_user',
			'config' => array(
				'type' => 'select',
				'allowNonIdValues' => 1,
				'default' => '',
				'foreign_table' => 'fe_users',
				'foreign_table_where' => 'ORDER BY fe_users.last_name',
        		'size' => 1,				
				'maxitems' => 1,
				'minitems' => 0,
				'multiple' => 0,        		
				'items' => array(
					array('Keine Auswahl', ''),
				),								
			),
			// special moox configuration		
			'moox' => array(
				'extkey' => 'moox_community',				
			),
		),		
		'title' => array(
			'exclude' => 0,
			'label' => $ll.'form.title',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'required,trim'
			),				
		),	
		'name' => array(
			'exclude' => 0,
			'label' => $ll.'form.name',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),				
		),
		'email' => array(
			'exclude' => 0,
			'label' => $ll.'form.email',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),				
		),
		'comment' => array(
			'exclude' => 0,
			'label' => $ll.'form.comment',			
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 10,
				'eval' => 'trim'
			),			
		),
		'rating' => Array (
			'exclude' => 1,
			'label' => $ll.'form.rating',
			'config' => Array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '13',
				'eval'     => 'double2',
				'checkbox' => '0',
				'default'  => '0'
			),			
		),		
		'confirmed' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => $ll.'form.confirmed',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,				
			),			
		),		
	),
);

// return tca configuration
return $tx_mooxcomment_domain_model_review;
?>