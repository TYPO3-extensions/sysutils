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
 * Abstract Sysutils CommandController
 *
 * Base class for all Sysutils CommandControllers
 *
 * @package Sysutils
 * @subpackage Command
 */
class Tx_Sysutils_Command_AbstractCommandController extends Tx_Extbase_MVC_Controller_CommandController {

	/**
	 * @var Tx_Extbase_SignalSlot_Dispatcher
	 */
	protected $signalSlotDispatcher;

	/**
	 * @param Tx_Extbase_SignalSlot_Dispatcher $dispatcher
	 */
	public function injectSignalSlotDispatcher(Tx_Extbase_SignalSlot_Dispatcher $dispatcher) {
		$this->signalSlotDispatcher = $dispatcher;
	}

	/**
	 * Wrapper for $this->signalSlotDispatcher->dispatchSignal()
	 *
	 * @param string $signalName
	 * @param array $signalArguments
	 */
	public function dispatchSignal($signalName, $signalArguments=array()) {
		$this->signalSlotDispatcher->dispatch(get_class($this), $signalName, $signalArguments);
	}

}
?>