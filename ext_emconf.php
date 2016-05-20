<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'MOOX Comment & Rating',
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
	'constraints' => [
		'depends' => [
			'typo3' => '6.2.0-7.9.99',
			'moox_core' => '7.0.0-7.9.99'
		],
		'conflicts' => [],
		'suggests' => [
			'moox_news' => '',
			'moox_shop' => ''
		],
	],
];
?>