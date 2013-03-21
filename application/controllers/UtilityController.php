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

use momo\_meta\DatabaseInitializer;
use momo\core\exceptions\MomoException;
use momo\core\helpers\DateTimeHelper;
use momo\core\helpers\FormHelper;
use momo\emailservice\EmailService;
use momo\emailservice\EmailTemplates;
use momo\core\security\Permissions;

/**
 * UtilityController
 * 
 * A collection of miscellaneous "utility" client actions.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class UtilityController extends Momo_Controller {
	
	/**
	 * Sends an email to a given user
	 * 
	 * TODO differentiate permissions by management vs. teamleader
	 */
	public function sendMessage() {
		
		// authorize
		$this->authorize(Permissions::USERS_SEND_EMAIL_TO_USER);
		
		// get reference to managers
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();
		$emailService = $this->getCtx()->getEmailService();
		
		// obtain a reference to the user that the message is addressed to
		$targetUser = $userManager->getUserById($this->input->post('userId'));
		
		// obtain a reference to the active user
		$activeUser = $this->getCtx()->getUser();
		
		
		// compile message map
		$messageMap = array();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]				= $targetUser->getFirstName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_ACTOR_FULL_NAME]			= $activeUser->getFullName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]					= trim($this->input->post('message'));
												
		// send notification email
		$emailService->sendEmailFromTemplate(
								$this->config->item("email_from_address", "momo"),
								$this->config->item("email_from_name", "momo"),
								$targetUser->getEmail(),
								EmailTemplates::$managementOriginatedMessageSubject,
								null,
								EmailTemplates::$managementOriginatedMessage,
								$messageMap
							);
						
		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Send Message";
		$data["component_message"] = "<p>The message has been sent to " . $targetUser->getFullName() . ".</p>";
		$data["component_mode"] = "info";
		$data["component_target"] = FormHelper::decodeUriSegment($this->input->post('returnUrl'));					

		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 * Displays the "send message" form
	 * 
	 * @param	integer		$userId			- the user to send the message to
	 * @param	string		$returnUrl		- the url to return to after completing the action
	 *  
	 * TODO differentiate permissions by management vs. teamleader
	 */
	public function displayMessageForm($userId, $returnUrl) {
			
		// authorize call
		$this->authorize(Permissions::USERS_SEND_EMAIL_TO_USER);
		
		// get reference to managers
		$userManager = $this->getCtx()->getUserManager();
		
		// target user
		$targetUser = $userManager->getUserById($userId);
		
		// prepare view data
		$data["component"] = "components/component_form_message.php";
		$data["component_title"] = "Send Message to " . $targetUser->getFullName();
		$data["component_return_url"] = FormHelper::decodeUriSegment($returnUrl);
		$data["component_target_user"] = $targetUser;
	
		// render view
		$this->renderView($data);
	}
	
		
	/**
	 * Dumps the complete time stats for given user and time period.
	 * 
	 * Note: controller action is accessible from CLI only.
	 * 
	 * @param	string		$userlogin		
	 * @param	string		$startDateString		- the start date ("d-m-yyyy" format)
	 * @param	string		$endDateString			- the end date ("d-m-yyyy" format)
	 */
	public function computeTimeStatsForUser($userlogin, $startDateString, $endDateString) {
		
		// CLI use only.
      	if ( ! $this->input->is_cli_request() ) {
     		echo "'computeTimeStatsForUser()' may only be called from command line.";
      		die;   	
        }
		
		// get services needed
		$compService = $this->getCtx()->getComputationService();
		$userManager = $this->getCtx()->getUserManager();

		// get ref to user
		$targetUser = $userManager->getUserByLogin($userlogin);
		
		// get DateTime reps for indicated dates
		$startDate = DateTimeHelper::getDateTimeFromStandardDateFormat($startDateString);
		$endDate = DateTimeHelper::getDateTimeFromStandardDateFormat($endDateString);
		
		// get the total work time credit in the time range
		$totalWorkTimeCreditInSec = $compService->computeTotalWorktimeCreditForUser($targetUser, $startDate, $endDate);
		
		// get the total work time adjustments in the time range
		$totalWorkTimeAdjustmentsInSec = $compService->computeWorktimeAdjustmentsForUser($targetUser, $startDate, $endDate);

		// compute the total adjusted worktime credit
		$totalAdjustedWorktimeCreditInSec = $totalWorkTimeCreditInSec + $totalWorkTimeAdjustmentsInSec;
		
		// compute plan time for the time range
		$planTimeInSec = $compService->computePlanTimeForUser($targetUser, $startDate, $endDate);

		// compute the resulting time delta
		$workTimeDeltaInSec = $totalAdjustedWorktimeCreditInSec - $planTimeInSec; 
		
		// compute total time credit for the time range
		$totalTimeCreditInSec = $compService->computeTotalTimeCreditForUser($targetUser, $startDate, $endDate);
		
		// dump to console		
		echo "\n\n\n\n";
		echo "-----------------------------------------------------------------------------------\n\n";																
		echo "work time stats for user '" . $targetUser->getFullName() . "'";
		echo " from '" . DateTimeHelper::formatDateTimeToPrettyDateFormat($startDate) . "'";
		echo " to '" . DateTimeHelper::formatDateTimeToPrettyDateFormat($endDate) . "'\n";
		echo "\n\n";		
		echo "total work-time credit:\t\t\t" . sprintf("%+08.2f h", $totalWorkTimeCreditInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($totalWorkTimeCreditInSec, true) . " h)\n\n";
		echo "total time credit:\t\t\t" . sprintf("%+08.2f h", $totalTimeCreditInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($totalTimeCreditInSec, true) . " h)\n\n";
		echo "total work-time adjustments:\t\t" . sprintf("%+08.2f h", $totalWorkTimeAdjustmentsInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($totalWorkTimeAdjustmentsInSec, true) . " h)\n\n";
		echo "total adjusted work-time credit:\t" . sprintf("%+08.2f h", $totalAdjustedWorktimeCreditInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($totalAdjustedWorktimeCreditInSec, true) . " h)\n\n";
		
		echo "plan time:\t\t\t\t" . sprintf("%+08.2f h", $planTimeInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($planTimeInSec, true) . " h)\n\n";
		echo "work time delta:\t\t\t" . sprintf("%+08.2f h", $workTimeDeltaInSec / 3600) . "\t(" . DateTimeHelper::formatTimeValueInSecondsToHHMM($workTimeDeltaInSec, true) . " h)\n";
		echo "\n\n";
		echo "-----------------------------------------------------------------------------------\n\n";			
		
	}
	
	
	/**
	 * Dumps the complete vacation stats for given user and time point.
	 * 
	 * The vacation information returned will encompass the vacation data commencing
	 * from the user's entry date up to the indicated point in time
	 * 
	 * Note: controller action is accessible from CLI only.
	 * 
	 * @param	string		$userlogin		
	 * @param	string		$pointInTime		- the point in time up to which the vacation stats are to be returned ("d-m-yyyy" format)
	 */
	public function computeVacationStatsForUser($userlogin, $pointInTime) {
		
		// CLI use only.
      	if ( ! $this->input->is_cli_request() ) {
     		echo "'computeVacationStatsForUser()' may only be called from command line.";
      		die;   	
        }
		
		// get services needed
		$compService = $this->getCtx()->getComputationService();
		$userManager = $this->getCtx()->getUserManager();

		// get ref to user
		$targetUser = $userManager->getUserByLogin($userlogin);
		
		// get DateTime reps for indicated dates
		$pointInTimeDate = DateTimeHelper::getDateTimeFromStandardDateFormat($pointInTime);
		
		// get the vacation digest for the user up to the indicated point in time
		$vacationDigest = $compService->computeVacationStatisticsDigestForUser($targetUser, $pointInTimeDate);
		
		// dump to console		
		echo "\n\n\n\n";
		echo "-----------------------------------------------------------------------------------\n\n";																
		echo "vacation stats for user '" . $targetUser->getFullName() . "'";
		echo " from '" . DateTimeHelper::formatDateTimeToPrettyDateFormat($targetUser->getEntryDate()) . "'";
		echo " to '" . DateTimeHelper::formatDateTimeToPrettyDateFormat($pointInTimeDate) . "'\n";
		echo "\n\n";		
		
		// dump vacation days credited by workplan
		echo "vacation days credited, by workplan:\n\n";
		
		foreach ( $vacationDigest["vacation_days_credited_by_workplan"] as $curWorkplanYear => $curValue ) {
			echo "\t" . $curWorkplanYear . "\t" . sprintf("%+06.2f", $curValue) . " days\n";
		}
		echo "\n\n";
		
		// dump vacation days consumed by workplan
		echo "vacation days consumed, by workplan:\n\n";
		
		foreach ( $vacationDigest["vacation_days_consumed_by_workplan"] as $curWorkplanYear => $curValue ) {
			echo "\t" . $curWorkplanYear . "\t" . sprintf("%+06.2f", $curValue) . " days\n";
		}
		echo "\n\n";
		
		// dump vacation days adjusted by workplan
		echo "vacation days adjusted, by workplan:\n\n";
		
		foreach ( $vacationDigest["vacation_days_adjusted_by_workplan"] as $curWorkplanYear => $curValue ) {
			echo "\t" . $curWorkplanYear . "\t" . sprintf("%+06.2f", $curValue) . " days\n";
		}
		echo "\n\n";
		
		// dump vacation days in "booked" state by workplan
		echo "vacation days in 'booked' state, by workplan:\n\n";
		
		foreach ( $vacationDigest["vacation_days_booked_by_workplan"] as $curWorkplanYear => $curValue ) {
			echo "\t" . $curWorkplanYear . "\t" . sprintf("%+06.2f", $curValue) . " days\n";
		}
		echo "\n\n";
		
		// dump aggregate sums
		echo "vacation days in aggregate\n\n";
		echo "\tvacation credited in period:\t\t" . sprintf("%+06.2f", $vacationDigest["aggregate_results"]["global_vacation_days_credit"]) . " days\n";
		echo "\tvacation consumed in period:\t\t" . sprintf("%+06.2f", $vacationDigest["aggregate_results"]["global_vacation_days_consumed"]) . " days\n";
		echo "\tvacation adjustments in period:\t\t" . sprintf("%+06.2f", $vacationDigest["aggregate_results"]["global_vacation_days_adjustment"]) . " days\n";
		echo "\tvacation in 'booked' state:\t\t" . sprintf("%+06.2f", $vacationDigest["aggregate_results"]["global_vacation_days_booked"]) . " days\n";
		echo "\tvacation balance\t\t\t" . sprintf("%+06.2f", $vacationDigest["aggregate_results"]["global_vacation_days_balance_effective"]) . " days\n";
		

		echo "\n\n";
		echo "-----------------------------------------------------------------------------------\n\n";			
		
	}
	
	
	/**
	 * Initializes the database for first use
	 * 
	 * Note: the method will create an administrator account and an initial
	 * 		 workplan from the data configured in "_shellscripts/setup/initdata.xml"
	 * 
	 */
	public function initDatabase() {
			
		// CLI use only.
      	if ( ! $this->input->is_cli_request() ) {
     		echo "'initDatabase()' may only be called from command line.";
      		die;   	
        }
 
       	// hand off to database initializer
        $dbInitializer = new DatabaseInitializer();
        $dbInitializer->initDatabase();
	}

}
//