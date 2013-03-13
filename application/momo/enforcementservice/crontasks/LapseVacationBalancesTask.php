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

namespace momo\enforcementservice\crontasks;

use momo\core\helpers\DateTimeHelper;

use momo\cronservice\CronTask;
use momo\emailservice\EmailTemplates;
use momo\emailservice\EmailService;


/**
 * LapseVacationBalancesTask
 * 
 * Lapses vacation balances
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class LapseVacationBalancesTask extends CronTask {
	
	
	public function run($cronService) {
					
		// get managers/services
		$emailService = $this->getCtx()->getEmailService();
		$userManager = $this->getCtx()->getUserManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		
		// get all enabled users
		$allUsers = $userManager->getAllEnabledUsers();
		
		// look at each enabled user and perform pending overtime lapses
		foreach ( $allUsers as $curUser ) {
						
			$cronService->log("info", "\t\tprocessing user: " . $curUser->getFullName());
			
			// perform the pending vacation lapses, operation returns a digest detailing the lapses performed
			$lapseDigest = $enforcementService->performPendingVacationLapsesForUser($curUser);

			// if the lapse digest is non-empty, we notify the user
			if ( count($lapseDigest) > 0 ) {
				
				// compile lapse detail information (message payload)
				// note: we only report non-zero lapse amounts
				$msgPayload = "";
				foreach ( $lapseDigest as $curLapseInfo ) {
				
					if ( $curLapseInfo["lapseValueInDays"] > 0 ) {
						
						$msgPayload .= "Unconsumed vacation credit carried over from " . $curLapseInfo["lapseFromWorkplanYear"] . ":\t\t" . sprintf("%.2f", $curLapseInfo["lapseValueInDays"]) . " days";
						$msgPayload .= "\n\n";
						$msgPayload .= "Resulting adjustment to vaction balance:\t\t\t-" . sprintf("%.2f", $curLapseInfo["lapseValueInDays"]) . " days";
						$msgPayload .= "\n\n";
						$msgPayload .= "Effective per:\t" . DateTimeHelper::formatDateTimeToPrettyDateFormat($curLapseInfo["lapseDate"]);
						$msgPayload .= "\n\n\n\n";
						
						
						$cronService->log("info", "\t\t\tUnconsumed vacation credit carried over from " . $curLapseInfo["lapseFromWorkplanYear"] . ": " . sprintf("%.2f", $curLapseInfo["lapseValueInDays"]) . " days");
						$cronService->log("info", "\t\t\tResulting adjustment to vaction balance: -" . sprintf("%.2f", $curLapseInfo["lapseValueInDays"]) . " days");
						$cronService->log("info", "\t\t\tEffective per: " . DateTimeHelper::formatDateTimeToPrettyDateFormat($curLapseInfo["lapseDate"]));
						$cronService->log("info", "");
							
					}

				}
							
				//
				// if there is a message payload, send email to user
				if ( $msgPayload != "" ) {
					// compile message map
					$messageMap = array();
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME] 	= $curUser->getFirstName();
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD] 	= $msgPayload;
				
					// send notification email
					$emailService->sendEmailFromTemplate(
											$this->getCI()->config->item("email_from_address", "momo"),
											$this->getCI()->config->item("email_from_name", "momo"),
											$curUser->getEmail(),
											EmailTemplates::$notifyUserOfVacationBalanceLapseSubject,
											null,
											EmailTemplates::$notifyUserOfVacationBalanceLapseMessage,
											$messageMap
										);
				}
				
			}
			
		}
			
	}

}