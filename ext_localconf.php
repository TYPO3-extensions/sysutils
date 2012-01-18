<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Sysutils_Command_BackupCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Sysutils_Command_MaintenanceCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Sysutils_Command_ReportCommandController';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Info_DateReport';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Info_SiteNameReport';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Info_VersionReport';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Space_DiskReport';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Space_UploadsReport';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Space_DatabaseReport';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Security_FailedLoginCountReport';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Sysutils_Report_Security_FailedLoginsReport';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['backupSkipList'][] = 'uploads/tx_sysutils/backups/';

?>