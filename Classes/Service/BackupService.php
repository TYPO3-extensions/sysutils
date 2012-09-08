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
 * Service dealing with creating backups of site contents
 *
 * @package Sysutils
 * @subpackage Service
 */
class Tx_Sysutils_Service_BackupService implements t3lib_Singleton {

	/**
	 * @var string
	 */
	const DEFAULT_TARGET = 'uploads/tx_sysutils/backups/';

	/**
	 * @var string
	 */
	const DEFAULT_DATE_FORMAT = 'YmdHi';

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
	public function backup (
		$files=TRUE,
		$includeTempFiles=FALSE,
		$includeSystemFiles=FALSE,
		$database=TRUE,
		$includeCacheTables=FALSE,
		$target=self::DEFAULT_TARGET,
		$remoteCredentials=NULL,
		$keepLocalFiles=FALSE,
		$preventAccessProtection=FALSE,
		$dateFormat=self::DEFAULT_DATE_FORMAT
	) {
		// type-case; we may receive and must accept+convert integers or strings as booleans
		$files = (bool) $files;
		$database = (bool) $database;
		$preventAccessProtection = (bool) $preventAccessProtection;
		$keepLocalFiles = (bool) $keepLocalFiles;
		if ($target{0} !== '/' && strpos($target, ':') === FALSE) {
			$subPath = $target;
		} else {
			$subPath = 'uploads/tx_sysutils/backups/';
		}
		try {
			$basePathAndFilename = $subPath . date($dateFormat ? $dateFormat : self::DEFAULT_DATE_FORMAT);
			if ($target{0} !== '/') {
				$basePathAndFilename = PATH_site . $basePathAndFilename;
			}
			if ($files === TRUE) {
				$targetArchivePathAndFilename = $basePathAndFilename . '.files.tar.gz';
				$this->backupFiles($targetArchivePathAndFilename, $includeSystemFiles, $includeTempFiles);
			} else {
				$targetArchivePathAndFilename = NULL;
			}
			if ($database === TRUE) {
				$targetArchivePathAndFilenameDatabase = $basePathAndFilename . '.database.tar.gz';
				$this->backupDatabase($targetArchivePathAndFilenameDatabase, $includeCacheTables);
			} else {
				$targetArchivePathAndFilenameDatabase = NULL;
			}
			if (($database === TRUE || $files === TRUE) && $preventAccessProtection !== TRUE && strpos($basePathAndFilename, PATH_site) === 0) {
				// written to a public directory inside PATH_site - enforce access protection unless explicitly told not to do so
				$pathinfo = pathinfo($basePathAndFilename);
				$htaccessPathAndFilename = $pathinfo['dirname'] . '/.htaccess';
				$htaccessFileContents = "Order allow,deny\nDeny from all\n";
				t3lib_div::writeFile($htaccessPathAndFilename, $htaccessFileContents);
			}
			if (strpos($target, ':') !== FALSE) {
				if ($files === TRUE) {
					$this->transmit($targetArchivePathAndFilename, $target, $remoteCredentials);
				}
				if ($database === TRUE) {
					$this->transmit($targetArchivePathAndFilenameDatabase, $target, $remoteCredentials);
				}
				if ($keepLocalFiles === FALSE) {
					if ($files === TRUE) unlink($targetArchivePathAndFilename);
					if ($database === TRUE) unlink($targetArchivePathAndFilenameDatabase);
				}
			}
		} catch (Exception $error) {
				// Note: currently no error handling exists but it is planned
			throw $error;
		}
	}

	/**
	 * Transmits a file. Selects transmission protocol then uses wrappers to
	 * send the file.
	 *
	 * @param string $file
	 * @param string $target
	 * @param string $credentials
	 */
	protected function transmit($file, $target, $credentials) {
		// doing remote transmission - check protocol, use proper method
		if (strpos($target, '//') === FALSE) {
			$protocol = 'ssh';
		} else {
			$parts = explode('//', $target);
			$protocol = trim(array_shift($parts), ':');
			$target = array_pop($parts);
		}
		// TODO: provide support for FTP, RSYNC
		switch ($protocol) {
			case 'ssh':
			case 'scp':
			default:
				$this->transmitSsh($file, $target, $credentials);
				break;
		}

	}

	/**
	 * Transmits files over SSH to remote host identified in $target, using
	 * $credentials to log in (type is detected from string length of $credentials)
	 *
	 * @param string $file
	 * @param string $target
	 * @param string $credentials
	 * @return string
	 */
	protected function transmitSsh($file, $target, $credentials) {
		$arguments = '';
		if (file_exists($credentials) === TRUE) {
			// private key file detected
			$arguments = ' -i ' . $credentials;
		}
		if (substr($target, -1) != '/') {
			$target .= '/';
		}
		$transmitCommand = 'scp' . $arguments . ' ' . $file . ' ' . $target . basename($file);
		$output = shell_exec($transmitCommand);
		return $output;
	}

	/**
	 * Create a TAR backup archive of all files excluding those defined in the
	 * skiplist (extension configuration)
	 *
	 * @param string $targetArchivePathAndFilename
	 * @param boolean $includeTempFiles
	 * @param boolean $includeSystemFiles
	 * @return boolean
	 */
	protected function backupFiles($targetArchivePathAndFilename, $includeTempFiles, $includeSystemFiles) {
		$skipFiles = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['backupSkipList'];
		$pathinfo = pathinfo($targetArchivePathAndFilename);
		$backupsLocation = str_replace(PATH_site, '', $pathinfo['dirname']);
		if (!in_array($backupsLocation, $skipFiles)) {
			array_push($skipFiles, $backupsLocation);
		}
		if ((bool) $includeSystemFiles === FALSE) {
			array_push($skipFiles, 'typo3/*');
			array_push($skipFiles, 't3lib/*');
			array_push($skipFiles, 'typo3_src/*');
			array_push($skipFiles, 'deprecation_*.log');
			array_push($skipFiles, 'temp_CACHED_*.ext_localconf.php');
			array_push($skipFiles, 'temp_CACHED_*.ext_tables.php');
		}
		if ((bool) $includeTempFiles === FALSE) {
			array_push($skipFiles, 'typo3temp/*');
		}
		foreach ($skipFiles as $index=>$path) {
			$skipFiles[$index] = $path;
		}
		$compressCommand = 'cd ' . PATH_site . ' && tar -czpf ' . $targetArchivePathAndFilename . ' --exclude=\'' . implode('\' --exclude=\'', $skipFiles) . '\' *';
		$output = shell_exec($compressCommand);
		if (trim($output) != '') {
			throw new Exception('Error during file backup: ' . $output, 1324819396);
		}
		return TRUE;
	}

	/**
	 * Create a TAR backup archive with an SQL dump (or append the dump to a
	 * full backup file if files were also backed up)
	 *
	 * @param string $targetArchivePathAndFilename
	 * @param boolean $includeCacheTables
	 */
	protected function backupDatabase($targetArchivePathAndFilename, $includeCacheTables) {
		$dumpBinary = trim(shell_exec('which mysqldump'));
		if (!is_executable($dumpBinary)) {
			throw new Exception('mysqldump command either not installed or not executable by current user', 1324780482);
		}
		$tables = array();
		$tablesResult = $GLOBALS['TYPO3_DB']->sql_query('SHOW TABLES');
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($tablesResult)) {
			$tableName = array_pop($row);
			if ((bool) $includeCacheTables === FALSE && strpos('cf_', $tableName) !== 0 && strpos('cache_', $tableName) !== 0 && strpos('caching_framework_', $tableName) !== 0) {
				array_push($tables, $tableName);
			}
		}
		$tempFile = PATH_site . 'typo3temp/' . TYPO3_db . '.sql';
		$dumpCommand = $dumpBinary . ' ' . TYPO3_db . ' -u ' . TYPO3_db_username . ' --password=' . TYPO3_db_password . ' -h ' . TYPO3_db_host . ' --tables ' . implode(' ', $tables);
		$dumpCommand .= ' > ' . $tempFile;
		$output = shell_exec($dumpCommand);
		if (trim($output) != '') {
			throw new Exception('Error during database dump: ' . $output, 1324822387);
		}
		$compressCommand = 'cd ' . PATH_site . 'typo3temp && tar -czpf ' . $targetArchivePathAndFilename . ' ' . basename($tempFile);
		$output = shell_exec($compressCommand);
		if (trim($output) != '') {
			throw new Exception('Error during database compression: ' . $output, 1324822398);
		}
		unlink($tempFile);
		return TRUE;
	}


}
