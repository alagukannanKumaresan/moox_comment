<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "moox_comment"
 *
 * Auto generated by Extension Builder 2015-10-16
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'MOOX Comments & Rating',
	'description' => 'Kommentare, Bewertungen und Rezensionen für TYPO3- und MOOX-Elemente (z.B. pages, tt_content, moox_news, moox_shop)',
	'category' => 'plugin',
	'author' => 'MOOX Team',
	'author_email' => 'moox@dcn.de',
	'author_company' => 'DCN GmbH',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '1',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '7.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.9.99',
			'moox_core' => '0.9.9-1.9.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'moox_news' => ''
		),
	),
);

?>