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

use momo\teammanager\TeamManager;
use momo\core\helpers\FormHelper;
use momo\core\helpers\DateTimeHelper;
use momo\core\security\Roles;
use momo\core\security\Permissions;
use momo\core\exceptions\MomoException;
use momo\audittrailmanager\EventDescription;

/**
 * ManageTeamsController
 * 
 * Exposes the client actions needed to work with teams.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageTeamsController extends Momo_Controller {
	
	/**
	 *  Default action
	 */
	public function index() {
		$this->listAllTeams();
	}
	
	
	/**
	 * Displays a list of all the active teams
	 */
	public function listAllTeams() {
		
		// authorize call
		$this->authorize(Permissions::TEAMS_LIST_ALL_TEAMS);
		
		// get ref to managers needed
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		
		// retrieve all existing teams
		$teams = $teamManager->getActiveTeams();
		
		// retrieve all enabled users (for membership info)
		$allUsers = $userManager->getAllEnabledUsers();

		// prepare view data
		$data["component"] = "components/component_list_teams.php";
		$data["component_title"] = "Manage Teams";
		
		$data["component_teams_all"] = $teams;
		$data["component_users_all"] = $allUsers;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 * Displays a summary view of teams with each team member listed
	 * along with essential user stats.
	 */
	public function displayTeamSummary() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::TEAMS_LIST_ALL_TEAMS,
										Permissions::TEAMS_LIST_ASSIGNED_TEAMS
									)	
								 );
								
		// get ref to managers/services needed
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$compService = $this->getCtx()->getComputationService();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$securityService = $this->getCtx()->getSecurityService();
		$ooManager = $this->getCtx()->getOOManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		
		//
		// prepare post parameter for call to manager
		
		// determine target team
		$targetTeam = null;
		if ( 	 $this->input->post('targetTeamId')
			 && ($this->input->post('targetTeamId') != -1) ) {
			
			// get a reference to selected team
			$targetTeam = $teamManager->getTeamById($this->input->post('targetTeamId'));
			
			// persist selected team to session
			$this->session->set_userdata('manageteamsscontroller.target.team_id', $this->input->post('targetTeamId'));
		}
		// there is no valid target team indicated
		// we'll try to restore the value from the session
		else if ( $this->session->userdata('manageteamsscontroller.target.team_id') !== false ) {
			$targetTeam = $teamManager->getTeamById($this->session->userdata('manageteamsscontroller.target.team_id'));
		}
		
		//
		// ready view data in accordance with permissions		
		
		if ( 	$this->authorize(Permissions::TEAMS_LIST_ALL_TEAMS, true)
			&&  $this->authorize(Permissions::USERS_LIST_ALL_USERS, true) ) {
			//
			// the permission indicates that we can return a list of all teams
			// hence, the determined target team is also ok
			$allowedTeams = $teamManager->getActiveTeams();
			
			//
			// retrieve team members for the target team
			$summarizeMembers = new PropelCollection(array());
			if ( $targetTeam !== null ) {
				// the users to summarize are simply the members (both primary and secondary) of the target tean
				$summarizeMembers = $targetTeam->getTeamMembers();
			}
			
		}
		else if ( 		$this->authorize(Permissions::TEAMS_LIST_ASSIGNED_TEAMS, true) 
					&&  $this->authorize(Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE, true)) {

			// for this permission level, we need to restrict the team selection to those for which
			// the active user is designated as team leader

			// get active user
			$activeUser = $this->getCtx()->getUser();

			// obtain a list of users assigned to teams for which the active user is team leader
			$allowedTeams = $teamManager->getAllTeamsLedByUser($activeUser);

			//
			// retrieve team members for the target team
			$summarizeMembers = new PropelCollection(array());
			
			if ( $targetTeam !== null ) {
				
				if ( $activeUser->isUserTeamLeaderOfTeam($targetTeam) ) {
					
					// get all members that of the target team
					$summarizeMembers = $targetTeam->getTeamMembers();
					
					//
					// with this permission level, we may only list users that belong to roles lower than the role of the active session
					// --> reduce the user collection accordingly
					
					// obtain the next lower role to active user's role
					$nextLowerRoleMap = $securityService->getNextLowerRoleMap($activeUser->getRole());
		
					// if there is a lower role, reduce user collection accordingly
					if ( count($nextLowerRoleMap) != 0 ) {
						$summarizeMembers = $userManager->reduceUserCollectionToMaximumRole($summarizeMembers, key($nextLowerRoleMap));
					}
					// there is no lower role, hence there are no users at all that may be listed
					else {
						// there are no lower roles, we set the allowedusers collection to an empty collection
						$summarizeMembers = $userManager->getUsersByIdList("-1");
					}
				}
				else {
					// insufficient permissions, throw exception
					throw new MomoException("ManageTeamsController:displayTeamSummary() - Unable to display team summary as the active user does not have the requisite permissions.");
				}
			}		
		}
		else {
			// insufficient permissions, throw exception
			throw new MomoException("ManageTeamsController:displayTeamSummary() - Unable to display team summary as the active user does not have the requisite permissions.");
		}
	
		//
		// build a digest consisting of summary members and their summary data points
		$summaryDigest = array();
		$digestIndex = 0;
		
		$currentWeekStartDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
		$currentWeekEndDate = DateTimeHelper::getEndOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
		
		// determine the threshold to excessive overtime from settings and convert it to seconds
		$overtimeExcessiveThresholdInSec = $settingsManager->getSettingValue(\Setting::KEY_OVERTIME_EXCESSIVE_IN_SEC);
		
		foreach ( $summarizeMembers as $curMember ) {
			
			//
			// obtain time stats
			
			// current workplan
			$currentWorkplanStartDate = $workplanManager->getFirstDayInPlan($workplanManager->getPlanByYear(DateTimeHelper::getCurrentYear()))->getDateOfDay();
			
			$currentWorkplanWorkTimeCreditInSec = $compService->computeTotalWorktimeCreditForUser($curMember, $currentWorkplanStartDate, DateTimeHelper::getDateTimeForCurrentDate())
												+ $compService->computeWorktimeAdjustmentsForUser($curMember, $currentWorkplanStartDate, DateTimeHelper::getDateTimeForCurrentDate());
			
			// global values
			$totalWorkTimeCreditInSec =   $compService->computeTotalWorktimeCreditForUser($curMember, $curMember->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate())
										+ $compService->computeWorktimeAdjustmentsForUser($curMember, $curMember->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
			
			$totalPlanTimeInSec = $compService->computePlanTimeForUser($curMember, $curMember->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
			
			// week
			$weekWorkTimeCreditInSec = $compService->computeTotalWorktimeCreditForUser($curMember, $currentWeekStartDate, $currentWeekEndDate);
			$weekPlanTimeInSec = $compService->computePlanTimeForUser($curMember, $currentWeekStartDate, $currentWeekEndDate);
			
			$summaryDigest[$digestIndex]["user"] = $curMember;
			
			//
			// compile the user's time/vacation related information
			$summaryDigest[$digestIndex]["worktime_credit_current_workplan_in_sec"] = $currentWorkplanWorkTimeCreditInSec;
			$summaryDigest[$digestIndex]["timedelta_total_insec"] = $totalWorkTimeCreditInSec - $totalPlanTimeInSec;
			$summaryDigest[$digestIndex]["worktime_credit_week_in_sec"] = $weekWorkTimeCreditInSec;
			$summaryDigest[$digestIndex]["plantime_week_in_sec"] = $weekPlanTimeInSec;
			$summaryDigest[$digestIndex]["timedelta_week_insec"] = $weekWorkTimeCreditInSec - $weekPlanTimeInSec;
			$summaryDigest[$digestIndex]["vacation_digest"] = $compService->computeVacationStatisticsDigestForUser($curMember);
			
			//
			// compile a digest of the user's potential "issue" points
			//
			// the various digest elements are only created if there is an issue
			// related to them that needs to be reported.
			
			$summaryDigest[$digestIndex]["issues"] = array();
			
			$incompleteWeeks = $enforcementService->getIncompleteWeeksForUser($curMember);
			if ( $incompleteWeeks->count() > 0 ) {
				$summaryDigest[$digestIndex]["issues"]["incomplete_weeks_count"] = $incompleteWeeks->count();
				$summaryDigest[$digestIndex]["issues"]["incomplete_weeks"] = $incompleteWeeks;
			}
			
			$openRequests = $ooManager->getOORequestsByStatusForUser($curMember, \OORequest::STATUS_OPEN);
			if ( $openRequests->count() > 0 ) {
				$summaryDigest[$digestIndex]["issues"]["open_requests_count"] = $openRequests->count();
			}
			
			$pendingRequests = $ooManager->getOORequestsByStatusForUser($curMember, \OORequest::STATUS_PENDING);
			if ( $pendingRequests->count() > 0 ) {
				$summaryDigest[$digestIndex]["issues"]["pending_requests_count"] = $pendingRequests->count();
			}
			
			if ( ($totalWorkTimeCreditInSec - $totalPlanTimeInSec) > $overtimeExcessiveThresholdInSec ) {
				$summaryDigest[$digestIndex]["issues"]["has_excessive_overtime"] = true;
			}
			
			$digestIndex++;

		}
		
		// prepare view data
		$data["component"] = "components/component_list_team_summary.php";
		$data["component_title"] = "Team Summary";
		
		$data["component_teams"] = $allowedTeams;
		$data["component_targetteam"] = $targetTeam;
		$data["component_summary_digest"] = $summaryDigest;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new team
	 */
	public function createTeam() {
		
		// authorize call
		$this->authorize(Permissions::TEAMS_CREATE_TEAM);
		
		// get ref to managers needed
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();

		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// retrieve the users as indicated by the form
			$teamLeaders 		= $userManager->getUsersByIdList($this->input->post('teamLeaders'));
			$primaryMembers 	= $userManager->getUsersByIdList($this->input->post('primaryMembers'));
			$secondaryMembers 	= $userManager->getUsersByIdList($this->input->post('secondaryMembers'));
				
			// create the team
			$newTeam = $teamManager->createTeam(	($this->input->post('parentTeam') != -1 ? $this->input->post('parentTeam') : null),
													 $this->input->post('teamName'),
													 $primaryMembers,
													 $secondaryMembers,
													 $teamLeaders
												);
									
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newTeam->compileStateDigest());							
												
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									TeamManager::MANAGER_KEY,
									"Created Team",
									$eventDescription
								);
								
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageTeamsController:createTeam() - a database error has occurred while while attempting to create the team: " . $this->input->post('teamName'), 0, $ex);
		}					
		
		// redisplay team list
		redirect("/manageteams");
	}
	
	
	/**
	 *  Updates a team
	 */
	public function updateTeam() {

		// authorize call
		$this->authorize(Permissions::TEAMS_EDIT_TEAM);
		
		// get ref to managers needed
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// obtain a copy of the updatetarget
			$updateTarget = $teamManager->getTeamById($this->input->post('teamId'));
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// retrieve the users indicated as part of the form
			$teamLeaders 		= $userManager->getUsersByIdList($this->input->post('teamLeaders'));
			$primaryMembers 	= $userManager->getUsersByIdList($this->input->post('primaryMembers'));
			$secondaryMembers 	= $userManager->getUsersByIdList($this->input->post('secondaryMembers'));							
		
			// update the team
			$updateTarget = $teamManager->updateTeam(
													$updateTarget,
													($this->input->post('parentTeam') != -1 ? $this->input->post('parentTeam') : null),
													 $this->input->post('teamName'),
													 $primaryMembers,
													 $secondaryMembers,
													 $teamLeaders
												);
										 				 
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());					

			// write event to audit trail
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									TeamManager::MANAGER_KEY,
									"Updated Team",
									$eventDescription
								);							 
										 
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageTeamsController:updateTeam() - a database error has occurred while while attempting to update the team with id: " . $this->input->post('teamId'), 0, $ex);
		}										 
									 
		// redisplay team list
		redirect("/manageteams");
	}
	
	
	/**
	 *  "Deletes" the indicated team
	 *  (see TeamManager for further information)
	 *  
	 *  @param teamId	integer		the id of the team to archive
	 */
	public function deleteTeam($teamId) {
		
		// authorize call
		$this->authorize(Permissions::TEAMS_ARCHIVE_TEAM);
		
		// get ref to team manager
		$teamManager = $this->getCtx()->getTeamManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			// archive the team
			$archivedTeam = $teamManager->archiveTeam($teamManager->getTeamById($teamId));
	
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("Team Name", $archivedTeam->getName());
																																	
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									TeamManager::MANAGER_KEY,
									"Deleted Team",
									$eventDescription
								);
			
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageTeamsController:deleteTeam() - a database error has occurred while while attempting to delete the team with id: " . $teamId, 0, $ex);
		}			
		
		// redisplay team list
		redirect("/manageteams");
	}
	
	
	/**
	 *  Displays the "create new team" form
	 */
	public function displayNewTeamForm() {
		
		// authorize call
		$this->authorize(Permissions::TEAMS_CREATE_TEAM);
		
		// get ref to managers
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		
		// retrieve all existing teams as possible parent teams
		$possibleParentTeams = $teamManager->getActiveTeams();
		
		// retrieve all users that are permitted to act as team leaders (i.e., ROLE_TEAMLEADER and above)
		$teamLeaders = $userManager->getUsersOfRoleAndAbove(Roles::ROLE_TEAMLEADER);
	
		// retrieve all enabled users (for memberships)
		$allUsers = $userManager->getAllEnabledUsers();
		
		// prepare view data
		$data["component"] = "components/component_form_team.php";
		$data["component_title"] = "New Team";
		$data["component_mode"] = "new";
	
		$data["component_teams_possible_parents"] = $possibleParentTeams;
		$data["component_users_teamleaders"] = $teamLeaders;
		$data["component_users_all"] = $allUsers;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit team" form
	 *  
	 *  @param integer	$teamId		- the team for which to display the edit form
	 */
	public function displayEditTeamForm($teamId) {
		
		// authorize call
		$this->authorize(Permissions::TEAMS_EDIT_TEAM);
		
		// get ref to managers
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();

		// query for edit target
		$editTarget = $teamManager->getTeamById($teamId);
		
		// retrieve the possible parent teams for this team
		$possibleParentTeams = $teamManager->getAllActiveTeamsWithoutIndicatedTeamAndItsChildren($editTarget);
		
		// retrieve all users that are permitted to act as team leaders (i.e., ROLE_TEAMLEADER and above)
		$teamLeaders = $userManager->getUsersOfRoleAndAbove(Roles::ROLE_TEAMLEADER);
	
		// retrieve all enabled users (for memberships)
		$allUsers = $userManager->getAllEnabledUsers();
		
		// prepare view data
		$data["component"] = "components/component_form_team.php";
		$data["component_title"] = "Edit Team (" . $editTarget->getName() . ")";
		$data["component_mode"] = "edit";
		
		$data["component_edit_target"] = $editTarget;
		$data["component_teams_possible_parents"] = $possibleParentTeams;
		$data["component_users_teamleaders"] = $teamLeaders;
		$data["component_users_all"] = $allUsers;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "unlock specific week" form
	 *  
	 *  @param integer	$userId		- the user for which to display the form
	 *  @param	string	$returnUrl	- the url to return to after completing the action
	 */
	public function displayUnlockSpecificWeekForm($userId, $returnUrl) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_UNLOCK_ALL_USERS_WEEKS,
										Permissions::USERS_UNLOCK_ASSIGNED_USERS_WEEKS
									)	
								 );
		
		// get reference to managers
		$userManager = $this->getCtx()->getUserManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// target user
		$targetUser = $userManager->getUserById($userId);
		
		// if we do not the permission to unlock all users, we need to ensure that the indicated
		// user is assigned to the active session
		if ( ! $this->authorize(Permissions::USERS_UNLOCK_ALL_USERS_WEEKS, true) ) {
			//
			// get active user
			$activeUser = $this->getCtx()->getUser();
			
			// get indicated user's possible primary team as well as possible secondary teams
			$userPrimaryTeam = $targetUser->getPrimaryTeam();
			$userSecondaryTeams = $targetUser->getSecondaryTeams();
			
			// the user is considered "assigned" if the active user is team leader of either
			// the primary team, or one of the secondary teams, provided such team assignments
			// exist
			$userIsAssigned = false;
			
			// test for primary team assignment
			if ( 	($userPrimaryTeam !== null)
				 && $activeUser->isUserTeamLeaderOfTeam($userPrimaryTeam)) {
				$userIsAssigned = true;
			}
			
			// test for secondary team assignment
			if ( 	($userSecondaryTeams !== null)
				 && $activeUser->isUserTeamLeaderOfTeam($userSecondaryTeams)) {
				$userIsAssigned = true;
			}
			
			// if the user turns out not to be assigned, we simply return to team summary
			if ( ! $userIsAssigned ) {
				redirect("/manageusers/displayteamsummary");
			}
		}
		
		// today's date
		$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
		
		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		// last day of last workplan in existence
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		
		// weeks may be unlocked, either from "entry date" or "application use start date" onwards, we use whichever is larger
		$earliestUnlockDate = ($targetUser->getEntryDate() > $appUseStartDate) ? $targetUser->getEntryDate() : $appUseStartDate;
		
		// request may unlocked up to latest day of last workplan, provided employment period does not end before that time
		$latestUnlockDate  = ($targetUser->getExitDate() < $lastWorkplanDate) ? $targetUser->getExitDate() : $lastWorkplanDate;
		
		// prepare the determined date values for easy view use
		$earliestUnlockDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($earliestUnlockDate),
											"month" => DateTimeHelper::getMonthFromDateTime($earliestUnlockDate),
											"day" => DateTimeHelper::getDayFromDateTime($earliestUnlockDate)
										 );			
							
		$latestUnlockDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestUnlockDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestUnlockDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestUnlockDate)
										);		
		
		
		// prepare view data
		$data["component"] = "components/component_form_unlock_specific_week.php";
		$data["component_title"] = "Unlock Specific Week (" . $targetUser->getFullName() . ")";
		$data["component_target_user"] = $targetUser;
		$data["component_date_elems_earliest_unlock"] = $earliestUnlockDateElems;
		$data["component_date_elems_latest_unlock"] = $latestUnlockDateElems;
		$data["component_target"] = FormHelper::decodeUriSegment($returnUrl);
		
		// render view
		$this->renderView($data);
	}
	
}