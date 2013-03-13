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
 * SendIncompleteWeekNotificationsTask
 * 
 * Notifies users and team leaders of "incomplete" weeks 
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class SendIncompleteWeekNotificationsTask extends CronTask {

	public function run($cronService) {
				
		// get managers/services
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$emailService = $this->getCtx()->getEmailService();
		
		// retrieve all enabled users
		$allUsers = $userManager->getAllEnabledUsers();
		
		// compile a digest of teams (team leaders) to notify of incomplete weeks
		$notifyTeamsDigest = array();
		
		//
		// for each user, we retrieve presently incomplete weeks
		//
		// if any are found, we send out a notification email to the concerned user and
		// add the user's primary team (if there is one) to the list of teams (i.e., team leaders) to notify
		// of this circumstance.
		//
		
		$cronService->log("info", "\t\tstarting user notification");
		
		
		foreach ( $allUsers as $curUser ) {
						
			$cronService->log("info", "\t\t\tprocessing user: " . $curUser->getFullName());
			
			// get weeks that presently have status "incomplete"
			$curUserIncompleteWeeks = $enforcementService->getIncompleteWeeksForUser($curUser);
			
			// we continue only if there are incomplete weeks
			if ( $curUserIncompleteWeeks->count() > 0 ) {
								
				$cronService->log("info", "\t\t\t\t" . $curUserIncompleteWeeks->count() . " incomplete weeks detected.");
				
				//
				// if the current user has a primary team, add the user to the team's digest of offending users
				$curUserPrimaryTeam = $curUser->getPrimaryTeam();
				if ( $curUserPrimaryTeam !== null ) {
					
					// retrieve the current user's highest order team membership
					// -- notifications always go to highest order team
					$highestOrderTeam = $teamManager->getHighestOrderTeamMembershipForUser($curUser, $curUserPrimaryTeam);
					
					// we add the user to the highest order team's digest, provided the user is not team leader of this team
					// -- i.e. we don't self-notify users that are also team leaders of the team that they have primary membership
					// on... this can arise if a team leader is not a member of a higher order team
					if ( ! $curUser->isUserTeamLeaderOfTeam($highestOrderTeam) ) {
						
						// if necessary, create user digest for concerned team
						if ( ! array_key_exists($highestOrderTeam->getId(), $notifyTeamsDigest) ) {
							$notifyTeamsDigest[$highestOrderTeam->getId()] = array(); 
						}
						
						// add user to team digest
						$notifyTeamsDigest[$highestOrderTeam->getId()][] = $curUser; 
					}			
				}
				
				//
				// notify user of the incomplete weeks
				
				// compile message map
				$messageMap = array();
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME] = $curUser->getFirstName();
				
				// drop list of affected weeks into message
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD] = "";
				
				foreach ( $curUserIncompleteWeeks as $curWeek ) {
					// add string for current week
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	.= "Week of: \t" . DateTimeHelper::formatDateTimeToPrettyDateFormat($curWeek->getDateOfDay());
					// issue line feed, provided we're not at last item already
					if ( $curUserIncompleteWeeks->getIterator()->valid() ) {
						$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	.= "\n";
					}
				}
			
				// send notification email
				$emailService->sendEmailFromTemplate(
										$this->getCI()->config->item("email_from_address", "momo"),
										$this->getCI()->config->item("email_from_name", "momo"),
										$curUser->getEmail(),
										EmailTemplates::$notifyUserOfIncompleteWeeksSubject,
										null,
										EmailTemplates::$notifyUserOfIncompleteWeeksMessage,
										$messageMap
									);

				$cronService->log("info", "\t\t\t\tnotification email sent to: " . $curUser->getEmail());
				
			}
					
		}
		
		$cronService->log("info", "\t\tcompleted user notification");
		
		$cronService->log("info", "\t\tstarting team leader notification");
		
		//
		// now notify the team leaders according to the contents of the notification digest
		foreach ( $notifyTeamsDigest as $curTeamId => $curTeamUsers ) {
			
			// get a reference to team
			$curTeam = $teamManager->getTeamById($curTeamId);
			
			// compile message map
			$messageMap = array();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_TEAM_NAME] = $curTeam->getName();
			
			// drop list of affected team/users into message
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	= "";
			$curTeamUsersIterator = new \CachingIterator(new \ArrayIterator($curTeamUsers));
			foreach ( $curTeamUsersIterator as $curUser ) {
				// add string for current week
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	.= $curUser->getFullName();
				// issue line feed, provided we're not at last item already
				if ( $curTeamUsersIterator->hasNext() ) {
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	.= "\n";
				}
			}
		
			// send notification email
			$emailService->sendEmailFromTemplate(
									$this->getCI()->config->item("email_from_address", "momo"),
									$this->getCI()->config->item("email_from_name", "momo"),
									implode(", ", $curTeam->getTeamLeaderEmailAddresses()),
									EmailTemplates::$notifyTeamLeadersOfIncompleteWeeksSubject,
									null,
									EmailTemplates::$notifyTeamLeadersOfIncompleteWeeksMessage,
									$messageMap
								);

			$cronService->log("info", "\t\t\t\tnotification email sent to: " . implode(", ", $curTeam->getTeamLeaderEmailAddresses()));
			
		}
		
		$cronService->log("info", "\t\tcompleted team leader notification");
		
	}

}