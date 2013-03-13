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

use momo\core\helpers\DateTimeHelper;
use momo\core\security\Permissions;
use momo\emailservice\EmailTemplates;
use momo\emailservice\EmailService;
use momo\core\helpers\FormHelper;

/**
 * EnforcementController
 * 
 * Exposes enforcement related client actions.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class EnforcementController extends Momo_Controller {
	
	/**
	 * Unlocks the week to which the indicated date belongs
	 * 
	 * @param 	string 		$unlockDate		- the date of a day that lies in the week to unlock
	 * @param	integer		$userId			- the user for which to unlock the week
	 * @param	string		$returnUrl		- the url to return to after completing the action
	 */
	public function unlockWeekForUser($unlockDate, $userId, $returnUrl) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_UNLOCK_ALL_USERS_WEEKS,
										Permissions::USERS_UNLOCK_ASSIGNED_USERS_WEEKS
									)	
								 );
		
		// get reference to managers
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$emailService = $this->getCtx()->getEmailService();
		
		// get Day reference for indicated unlock date
		$unlockWeekDay = $workplanManager->getDayForDate(DateTimeHelper::getDateTimeFromStandardDateFormat($unlockDate));
		// figure out start of week date for the indicated date
		$startOfWeekDate = DateTimeHelper::getStartOfWeek($unlockWeekDay->getDateOfDay());
		
		// figure out target and active users
		$targetUser = $userManager->getUserById($userId);
		$activeUser = $this->getCtx()->getUser();
		
		// if we do not the permission to unlock all users, we need to ensure that the indicated
		// user is assigned to the active session
		if ( ! $this->authorize(Permissions::USERS_UNLOCK_ALL_USERS_WEEKS, true) ) {
			
			// get indicated user's possible primary team as well as possible secondary teams
			$userPrimaryTeam = $targetUser->getPrimaryTeam();
			$userSecondaryTeams = $targetUser->getSecondaryTeams();
			
			// the user is considered "assigned" if the active user is team leader of either
			// the primary team, or one of the secondary teams, provided such team assignments
			// exist
			$userIsAssigned = false;
			
			// test for primary team assignment
			if ( 	($userPrimaryTeam !== null)
				 && $activeUser->isUserTeamLeaderOfTeam($userPrimaryTeam) ) {
				$userIsAssigned = true;
			}
			
			// test for secondary team assignment
			if ( 	($userSecondaryTeams !== null)
				 && $activeUser->isUserTeamLeaderOfTeam($userSecondaryTeams) ) {
				$userIsAssigned = true;
			}
			
			// if the user turns out not to be assigned, we simply return to team summary
			if ( ! $userIsAssigned ) {
				redirect("/manageusers/displayteamsummary");
			}
		}
		
		//
		// unlock the week
		$enforcementService->unlockWeekForUser(	
										$userManager->getUserById($userId),
										$unlockWeekDay
									);

		
		//
		// notify target user											   
											   
		// compile message map
		$messageMap = array();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]			= $targetUser->getFirstName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_ACTOR_FULL_NAME]		= $activeUser->getFullName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_DATE]				= DateTimeHelper::formatDateTimeToPrettyDateFormat($startOfWeekDate);
												
		// send notification email
		$emailService->sendEmailFromTemplate(
								$this->config->item("email_from_address", "momo"),
								$this->config->item("email_from_name", "momo"),
								$targetUser->getEmail(),
								EmailTemplates::$timetrackerWeekUnlockedSubject,
								null,
								EmailTemplates::$timetrackerWeekUnlockedMessage,
								$messageMap
							);

		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Unlock Incomplete Week";
		$data["component_message"] = "<p>" . $targetUser->getFullName() . " will be notified that the week of <strong>" . DateTimeHelper::formatDateTimeToPrettyDateFormat($startOfWeekDate) . "</strong> has been unlocked.</p>";
		$data["component_mode"] = "info";
		$data["component_target"] = FormHelper::decodeUriSegment($returnUrl);					

		// render view
		$this->renderView($data);						
											   	
	}
	
}