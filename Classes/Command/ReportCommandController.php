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
 * Commands dealing with reporting of system information such as DB size and
 * file storage usage, number of temporary files etc.
 */
class Tx_Sysutils_Command_ReportCommandController extends Tx_Sysutils_Command_AbstractCommandController {

	/**
	 * Send email report
	 *
	 * Gathers and sends a report to $recipient
	 *
	 * @param string $recipient Email address that should receive the report message
	 * @param string $subject Subject for the email
	 * @param string $emailTemplateFile You can override the template file used to render the email text. The file must be a Fluid template - if your template file extension is .html an HTML email will be sent, if .txt a plaintext email is sent.
	 * @param string $partialRootPath If your email template requires Partial templates enter the root path here
	 * @param string $layoutRootPath If your email template requires a Layout enter the root path here
	 * @return string
	 */
	public function sendReportCommand(
			$recipient,
			$subject='TYPO3 System report',
			$emailTemplateFile='EXT:sysutils/Resources/Private/Templates/Report.txt',
			$partialRootPath='EXT:sysutils/Resources/Private/Partials/',
			$layoutRootPath='EXT:sysutils/Resources/Private/Layouts/'
			) {
		$templatePathAndFilename = t3lib_div::getFileAbsFileName($emailTemplateFile);
		$partialRootPath = t3lib_div::getFileAbsFileName($partialRootPath);
		$layoutRootPath = t3lib_div::getFileAbsFileName($layoutRootPath);
		$pathinfo = pathinfo($templatePathAndFilename);
		$view = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
		$view->setTemplatePathAndFilename($templatePathAndFilename);
		$view->setPartialRootPath($partialRootPath);
		$view->setLayoutRootPath($layoutRootPath);
		$view->assign('reports', $this->getReports());
		$subject .= ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
		$body = $view->render();
		$mail = new t3lib_mail_Message();
		if ($pathinfo['extension'] === 'html') {
			$type = 'text/html';
		} else {
			$type = 'text/plain';
		}
		$this->dispatchSignal(Tx_Sysutils_Signal_SignalInterface::REPORT_SEND, array(
			'recipient' => &$recipient,
			'subject' => &$subject,
			'body' => &$body,
			'type' => &$type
		));
		$mail->setTo($recipient);
		$mail->setSubject($subject);
		$mail->setBody($body, $type);
		$mail->send();
		return $body;
	}

	/**
	 * Gets all reports to display
	 *
	 * @return array
	 */
	protected function getReports() {
		$reports = array();
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'] as $reportClassName) {
				$report = $this->objectManager->get($reportClassName);
				array_push($reports, $report);
			}
		}
		$this->dispatchSignal(Tx_Sysutils_Signal_SignalInterface::REPORT_EXECUTE, array(
			'reports' => &$reports
		));
		foreach (array_keys($reports) as $index) {
			$reports[$index]->execute();
		}
		return $reports;
	}

}
?>