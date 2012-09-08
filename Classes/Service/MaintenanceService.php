<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Claus Due <claus@wildside.dk>, Wildside A/S
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
 * Service dealing with site maintenance
 *
 * @package Sysutils
 * @subpackage Service
 */
class Tx_Sysutils_Service_MaintenanceService implements t3lib_Singleton {

	/**
	 * Delete old tempfiles
	 *
	 * Deletes temporary files in typo3temp older than $limit days. If $pretend
	 * is enabled no files will be removed and a report will be output.
	 *
	 * @param integer $limit The maximum age as determined by filectime() in number of days
	 * @param boolean $pretend If TRUE, pretends actions instead of actually performing them
	 * @return void
	 */
	public function pruneTempFiles($limit, $pretend=FALSE) {
		$before = time() - ($limit * 86400);
		$path = PATH_site . 'typo3temp/';
		$removedFiles = array();
		$this->deleteOldFilesRecursively($path, $before, $removedFiles, $pretend);
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
	public function pruneBackupFiles($limit, $pretend=FALSE) {
		$before = time() - ($limit * 86400);
		$path = PATH_site . Tx_Sysutils_Service_BackupService::DEFAULT_TARGET;
		$removedFiles = array();
		$this->deleteOldFilesRecursively($path, $before, $removedFiles, $pretend);
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
	public function pruneSystemLog($limit, $pretend=FALSE) {
		$before = time() - ($limit * 86400);
		if ((boolean) $pretend === FALSE) {
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('sys_log', "tstamp < '" . $before . "'");
		}
	}

	/**
	 * Deletes files older than $before (unixtime) from $path
	 *
	 * @param string $path Path from which to remove old files
	 * @param integer $before Unix timestamp limit, files older than this will be removed
	 * @param array $removedFiles Array of removed files, collected along the recursive calls
	 * @param boolean $pretend If TRUE, does not remove the file - just returns the file name of the file that would be removed
	 */
	protected function deleteOldFilesRecursively($path, $before, &$removedFiles, $pretend=FALSE) {
		$contents = scandir($path);
		foreach ($contents as $node) {
			$filename = $path . $node;
			if (is_file($filename) && filectime($filename) < $before) {
				if ((boolean) $pretend === FALSE) {
					unlink($filename);
				}
				array_push($removedFiles, $filename);
			} else if (is_dir($node) && substr($node, 0, 1) != '.') {
				$this->deleteOldFilesRecursively($filename . '/', $before, $removedFiles);
			}
		}
	}

}
