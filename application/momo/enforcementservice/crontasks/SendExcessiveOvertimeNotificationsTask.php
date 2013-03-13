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

use momo\cronservice\CronTask;
use momo\emailservice\EmailTemplates;
use momo\emailservice\EmailService;


/**
 * SendExcessiveOvertimeNotificationsTask
 * 
 * Sends out notifications to users and team leader with regard to excessive overtime
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class SendExcessiveOvertimeNotificationsTask extends CronTask {

	public function run($cronService) {
		
		// get managers/services
		$enforcementService = $this->getCtx()->getEnforcementService();
		$emailService = $this->getCtx()->getEmailService();
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		
		// get a digest of users with excessive overtime
		$excessiveOvertimeDigest = $enforcementService->detectExcessiveOvertimeForAllUsers();
			
		// compile a digest of teams (team leaders) to notify of incomplete weeks
		$notifyTeamsDigest = array();
		
		//
		// send out a notification to each user concerned
		$cronService->log("info", "\t\tstarting user notification");
		
		foreach ( $excessiveOvertimeDigest as $curUserId => $curUserOvertimeValueInSec ) {
			
			$curUser = $userManager->getUserById($curUserId);
			
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
					$notifyTeamsDigest[$highestOrderTeam->getId()][$curUser->getId()] = $curUserOvertimeValueInSec; 
				}			
			}

			$cronService->log("info", "\t\t\t\t" . $curUser->getFullName() . " has excessive overtime: " . sprintf("%+.2f h", ($curUserOvertimeValueInSec / 3600)));
			
			// compile message map
			$messageMap = array();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME] = $curUser->getFirstName();
			
			// drop list of affected weeks into message
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_NUMBER] = sprintf("%+.2f", ($curUserOvertimeValueInSec / 3600));
			
			// send notification email
			$emailService->sendEmailFromTemplate(
									$this->getCI()->config->item("email_from_address", "momo"),
									$this->getCI()->config->item("email_from_name", "momo"),
									$curUser->getEmail(),
									EmailTemplates::$notifyUserOfExcessiveOvertimeSubject,
									null,
									EmailTemplates::$notifyUserOfExcessiveOvertimeMessage,
									$messageMap
								);

			$cronService->log("info", "\t\t\t\tnotification email sent to: " . $curUser->getEmail());
			
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
			foreach ( $curTeamUsersIterator as $curUserId => $curUserOvertimeValueInSec ) {
				// get a reference to current user
				$curUser = $userManager->getUserById($curUserId);
				
				// add string for current week
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD]	.= $curUser->getFullName() . ":\t" . sprintf("%+.2f hours", ($curUserOvertimeValueInSec / 3600));
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
									EmailTemplates::$notifyTeamLeadersOfExcessiveOvertimeSubject,
									null,
									EmailTemplates::$notifyTeamLeadersOfExcessiveOvertimeMessage,
									$messageMap
								);

			$cronService->log("info", "\t\t\t\tnotification email sent to: " . implode(", ", $curTeam->getTeamLeaderEmailAddresses()));
			
		}
		
		$cronService->log("info", "\t\tcompleted team leader notification");
		
	}

}