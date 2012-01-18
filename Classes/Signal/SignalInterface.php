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
 * SignalInterrace
 *
 * Contains signal type definitions
 *
 * @package Sysutils
 * @subpackage Signal
 */
class Tx_Sysutils_Signal_SignalInterface {

	/**
	 * Backup: PRE. Sent before backup begins, provided with arguments configuring
	 * which backups will be performed.
	 *
	 * @var string
	 */
	const BACKUP_PRE = 'Sysutils.Backup.Pre';

	/**
	 * Backup: POST. Sent after backup completes. Like PRE, provided with arguments
	 * defining the backup performed and an additional "archive" argument which
	 * contains the filename which was generated.
	 *
	 * @var string
	 */
	const BACKUP_POST = 'Sysutils.Backup.Post';

	/**
	 * Backup: PRE SkipList. If you need additional skipped files, you have a
	 * chance to dynamically adding them to the global variable
	 * $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['backupSkipList']
	 * which is read and processed immediately after this Signal finishes.
	 *
	 * @var string
	 */
	const BACKUP_SKIPLIST_PRE = 'Sysutils.Backup.SkipList.Pre';

	/**
	 * Backup: POST SkipList. If you want to inspect the generated skip list
	 * use this Signal.
	 *
	 * @var string
	 */
	const BACKUP_SKIPLIST_POST = 'Sysutils.Backup.SkipList.Post';

	/**
	 * Backup: ERROR. Dispatched if errors occurred during the backup process.
	 *
	 * @var string
	 */
	const BACKUP_ERROR = 'Sysutils.Backup.Error';

	/**
	 * Backup: SEND. Dispatched just before files are to be transmitted to a
	 * remote host (if a remote host transmit has been requested). You can use
	 * this signal to manipulate the archive files before transmission.
	 *
	 * @var string
	 */
	const BACKUP_SEND = 'Sysutils.Backup.Send';

	/**
	 * Report: SEND. Dispatched before a report is sent, allowing you to modify
	 * the recipient, subject and body of the email generated (these are passed
	 * by refefence as array members)
	 *
	 * @var string
	 */
	const REPORT_SEND = 'Sysutils.Report.Send';

	/**
	 * Report: EXECUTE. Dispatched just before reports are executed to gather
	 * report information. Manipulate the reports array member in arguments
	 * (passed by reference as array member). You can add or remove reports
	 * dynamically this way.
	 *
	 * @var string
	 */
	const REPORT_EXECUTE = 'Sysutils.Report.Execute';
}

?>