<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Claus Due <claus@wildside.dk>, Wildside A/S
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Commands dealing with creating backups of site contents
 *
 * @package Sysutils
 * @subpackage Command
 */
class Tx_Sysutils_Command_BackupCommandController extends Tx_Sysutils_Command_AbstractCommandController {

	/**
	 * @var Tx_Sysutils_Service_BackupService
	 */
	protected $backupService;

	/**
	 * @param Tx_Sysutils_Service_BackupService $backupService
	 */
	public function injectBackupService(Tx_Sysutils_Service_BackupService $backupService) {
		$this->backupService = $backupService;
	}

	/**
	 * Backup
	 *
	 * Backs up selected parts of the site
	 *
	 * @param boolean $files If TRUE, backups up files used by the site (excludes source and temp files)
	 * @param boolean $includeTempFiles If TRUE, includes typo3temp files
	 * @param boolean $includeSystemFiles If TRUE, includes typo3_src, typo3 and t3lib files
	 * @param boolean $database If TRUE, backups up the database contents (excluding cache table contents)
	 * @param boolean $includeCacheTables If TRUE, includes cache tables from the database
	 * @param string $target Defines the target destination. Supports SSH host:path syntax (requires private SSH key/password). Paths NOT beginning with / will be treated as relative to PATH_site
	 * @param string $remoteCredentials Use this private SSH key file (absolute path) when connecting to SSH hosts to transmit the backup output. Username will be the system user running the script/scheduler task or the one specified as username(@remote.host) in $target
	 * @param boolean $keepLocalFiles If TRUE, keeps local copies of files which would otherwise be deleted after successful transmission to a remote host. Only applies when remote transmission is selected
	 * @param boolean $preventAccessProtection If TRUE, DOES NOT WRITE a .htaccess file to the output directory. Default behavior is to write enforce that this file always exists and prevents access. Do NOT disable this unless you have manually protected the output directory!
	 * @param string $dateFormat Override this to set your own date format for the generated archives
	 * @return void
	 */
	public function backupCommand(
			$files=TRUE,
			$includeTempFiles=FALSE,
			$includeSystemFiles=FALSE,
			$database=TRUE,
			$includeCacheTables=FALSE,
			$target=Tx_Sysutils_Service_BackupService::DEFAULT_TARGET,
			$remoteCredentials=NULL,
			$keepLocalFiles=FALSE,
			$preventAccessProtection=FALSE,
			$dateFormat=Tx_Sysutils_Service_BackupService::DEFAULT_DATE_FORMAT
			) {
			// type-case; we may receive and must accept+convert integers or strings as booleans
		$this->backupService->backup($files, $includeTempFiles, $includeSystemFiles, $database, $includeCacheTables, $target, $remoteCredentials, $keepLocalFiles, $preventAccessProtection, $dateFormat);
	}
}
