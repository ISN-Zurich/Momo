<?php

/** 
 * Copyright 2013, ETH Zürich
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

// ------------------------------------------------------------------------

namespace momo\emailservice;

use momo\core\exceptions\MomoException;
use momo\core\services\BaseService;

/**
 * EmailService
 * 
 * Single access point for all email related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.emailservice
 */
class EmailService extends BaseService {
	
	const SERVICE_KEY = "SERVICE_EMAIL";
	
	/**
	 * Sends an email based on the indicated template
	 * 
	 * @param string	$fromAddress
	 * @param string	$fromName
	 * @param string	$toAddress
	 * @param string	$subjectTemplate		- the subject line's template
	 * @param string	$subjectMap				- the subject line's replacement map
	 * @param string	$messageTemplate		- the message's template
	 * @param string	$messageMap				- the message's replacement map
	 * 
	 */
	public function sendEmailFromTemplate($fromAddress, $fromName, $toAddress, $subjectTemplate, $subjectMap, $messageTemplate, $messageMap) {
		
		// map subject / message to templates
		$subject = $this->formatTemplate($subjectTemplate, $subjectMap);
		$message = $this->formatTemplate($messageTemplate, $messageMap);
		
		// if we're not in a production environment, we override the email addresse appropriately
		if ( $this->getCI()->config->item("environment", "momo") != "production" ) {
			$toAddress = $this->getCI()->config->item("email_to_address_non_production", "momo");
		}
		
		// send off email, provided sending emails is enabled
		if ( $this->getCI()->config->item("sendemails", "momo") ) {
			$this->sendEmail($fromAddress, $fromName, $toAddress, $subject, $message);
		}
		
	}
	
	
	/**
	 * Sends an email.
	 * 
	 * @param string	$fromAddress
	 * @param string	$fromName
	 * @param string	$toAddress
	 * @param string	$subject
	 * @param string	$message
	 */
	public function sendEmail($fromAddress, $fromName, $toAddress, $subject, $message) {
	
		// load email library
		$this->getCI()->load->library('email');
		
		// configure email
		$this->getCI()->email->from($fromAddress, $fromName);
		$this->getCI()->email->to($toAddress);		
		$this->getCI()->email->subject($subject);
		$this->getCI()->email->message($message);
		
		// send it off
		$this->getCI()->email->send();
				
		//
		// generate log message
		$logMsg = "EmailHelper::sendEmail() - sent message with subject: '" . $subject . "' to: " . $toAddress;
			
		// log message sending at info level
		log_message("info", $logMsg);
		
		// full protocol dump at debug level
		log_message("debug", $this->getCI()->email->print_debugger());
	}
	
	
	
	/**
	 * Formats an email message from a template.
	 * 
	 * Given a template string, the method will look up replacement keys/values
	 * in the provided replacement map replace map keys that have matches in the
	 * the template with appropriate map values. 
	 * 
	 * If the replacement map is "null", the method will simply return the unaltered template. 
	 * 
	 * @param string	$template
	 * @param array		$replacementMap
	 * 
	 * @return 	the formatted message
	 * 
	 */
	private function formatTemplate($template, $replacementMap) {

		$result = $template;
		
		if ( $replacementMap !== null ) {
			foreach ( $replacementMap as $mapKey => $mapValue ) {
				$result = str_replace( $mapKey, $mapValue, $result );
			}
		}
		
		return $result;
	}
	
}