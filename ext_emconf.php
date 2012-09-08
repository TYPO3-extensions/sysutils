<?php

########################################################################
# Extension Manager/Repository config file for ext "sysutils".
#
# Auto generated 11-01-2012 23:02
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'System Utilities',
	'description' => 'A set of utilities written in Extbase, designed for use in Scheduler. Maintains, optimizes, cleans, records metrics and reports status of your TYPO3 installation at regular intervals.',
	'category' => 'be',
	'shy' => 0,
	'version' => '1.0.4',
	'dependencies' => 'extbase,fluid,fed',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 1,
	'createDirs' => 'uploads/tx_sysutils/backups',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Claus Due',
	'author_email' => 'claus@wildside.dk',
	'author_company' => 'Wildside A/S',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.6-0.0.0',
			'extbase' => '1.4',
			'fluid' => '1.4',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:27:{s:21:"ExtensionBuilder.json";s:4:"6bf5";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"3478";s:14:"ext_tables.php";s:4:"b68b";s:14:"ext_tables.sql";s:4:"d41d";s:45:"Classes/Command/AbstractCommandController.php";s:4:"954a";s:43:"Classes/Command/BackupCommandController.php";s:4:"351c";s:48:"Classes/Command/MaintenanceCommandController.php";s:4:"23ba";s:43:"Classes/Command/ReportCommandController.php";s:4:"7949";s:29:"Classes/Error/BackupError.php";s:4:"3208";s:33:"Classes/Report/AbstractReport.php";s:4:"3248";s:34:"Classes/Report/ReportInterface.php";s:4:"9f8e";s:34:"Classes/Report/Info/DateReport.php";s:4:"ef71";s:38:"Classes/Report/Info/SiteNameReport.php";s:4:"2595";s:37:"Classes/Report/Info/VersionReport.php";s:4:"2c46";s:50:"Classes/Report/Security/FailedLoginCountReport.php";s:4:"33cb";s:46:"Classes/Report/Security/FailedLoginsReport.php";s:4:"7ac7";s:39:"Classes/Report/Space/DatabaseReport.php";s:4:"104b";s:35:"Classes/Report/Space/DiskReport.php";s:4:"d0cf";s:38:"Classes/Report/Space/UploadsReport.php";s:4:"bd95";s:34:"Classes/Signal/SignalInterface.php";s:4:"264a";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"d466";s:40:"Resources/Private/Language/locallang.xml";s:4:"abb4";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"ca2c";s:38:"Resources/Private/Templates/Report.txt";s:4:"f8c9";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:14:"doc/manual.sxw";s:4:"8cb0";}',
	'suggests' => array(
	),
);

?>