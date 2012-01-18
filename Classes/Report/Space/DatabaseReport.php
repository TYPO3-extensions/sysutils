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
 * Database space usage Report
 *
 * @package Sysutils
 * @subpackage Report/Space
 */
class Tx_Sysutils_Report_Space_DatabaseReport extends Tx_Sysutils_Report_AbstractReport implements Tx_Sysutils_Report_ReportInterface {

	/**
	 * Fills data of the Report
	 *
	 * @return void
	 */
	public function execute() {
		$databaseSizeResult = $GLOBALS['TYPO3_DB']->sql_query("SELECT SUM( data_length + index_length ) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = '" . TYPO3_db . "'");
		$databaseSizeRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($databaseSizeResult);
		$databaseSize = array_pop($databaseSizeRow);
		$value = number_format($databaseSize, ($databaseSize > 10 ? 0 : 1)) . 'M';

		$this->setGroup('space');
		$this->setLabel('Database size');
		$this->setValue($value);
	}

}

?>