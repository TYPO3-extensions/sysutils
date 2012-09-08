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
 * Commands dealing with system maintenance
 */
class Tx_Sysutils_Command_MaintenanceCommandController extends Tx_Sysutils_Command_AbstractCommandController {

	/**
	 * @var Tx_Sysutils_Service_MaintenanceService
	 */
	protected $maintenanceService;

	/**
	 * @param Tx_Sysutils_Service_MaintenanceService $maintenanceService
	 */
	public function injectMaintenanceService(Tx_Sysutils_Service_MaintenanceService $maintenanceService) {
		$this->maintenanceService = $maintenanceService;
	}

	/**
	 * Delete old tempfiles
	 *
	 * Deletes temporary files in typo3temp older than $limit days. If $pretend
	 * is enabled no files will be removed and a report will be output.
	 *
	 * @param integer $limit The maximum age as determined by filectime() in number of days
	 * @param boolean $clearCache If TRUE, clears caches after the operation. CURRENTLY UNUSED.
	 * @param boolean $pretend If TRUE, pretends actions instead of actually performing them
	 * @return void
	 */
	public function pruneTempFilesCommand($limit, $clearCache=TRUE, $pretend=FALSE) {
		$this->maintenanceService->pruneTempFiles($limit, $pretend);
	}

	/**
	 * Delete old backup files
	 *
	 * Deletes old backup files created by Sysutils according to $limit
	 *
	 * @param integer $limit Maximum age in number of days
	 * @param boolean $pretend If TRUE, pretends actions instead of actually performing them
	 * @return void
	 */
	public function pruneBackupFilesCommand($limit, $pretend=FALSE) {
		$this->maintenanceService->pruneBackupFiles($limit, $pretend);
	}

	/**
	 * Prune log
	 *
	 * Removes entries in sys_log older than $limit
	 *
	 * @param integer $limit The maximum age of entries in number of days
	 * @param boolean $pretend If TRUE, pretends actions instead of actually performing them
	 * @return void
	 */
	public function pruneSystemLogCommand($limit, $pretend=FALSE) {
		$this->maintenanceService->pruneSystemLog($limit, $pretend);
	}

}
