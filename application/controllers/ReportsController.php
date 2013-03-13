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

/**
 * ReportsController
 * 
 * Exposes the client actions needed to work with reports.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ReportsController extends Momo_Controller {
	
	// timetracker report related constants
	public static $TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY 	= "REG_ENTRY_ITEM";
	public static $TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY 		= "OO_ENTRY_ITEM";
		
	// projecttime report related constants
	public static $PROJECTTIME_REPORT_REPORT_BY_USER_KEY 		= "REPORT_BY_USER";
	public static $PROJECTTIME_REPORT_REPORT_BY_TEAM_KEY 		= "REPORT_BY_TEAM";
	public static $PROJECTTIME_REPORT_REPORT_BY_PROJECT_KEY 	= "REPORT_BY_PROJECT";
	
	public static $PROJECTTIME_REPORT_REPORT_BY_OPTIONS_MAP 	= array(
																			"REPORT_BY_USER" 	=> "User",
																			"REPORT_BY_TEAM"	=> "Team",
																			"REPORT_BY_PROJECT" => "Project"
																		);
															
	/**
	 * Displays the "project time by user" report
	 */
	public function displayProjectTimeReport() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS,
										Permissions::REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT,
										Permissions::REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
										Permissions::REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT,
										Permissions::REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT,
										Permissions::REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
										Permissions::REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT
									)	
								 );
		
		//
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$projectManager = $this->getCtx()->getProjectManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$reportingService = $this->getCtx()->getReportingService();	
	
		// the active user
		$activeUser = $this->getCtx()->getUser();
		
		//
		// see if there is an active selection indicating what we are to report by
		$reportByKey = null;
		if ( 	 $this->input->post('reportByKey')
			 && ($this->input->post('reportByKey') != "-1") ) {
			
			$reportByKey = $this->input->post('reportByKey');

			// if the report by key has changed, we clear any lingering "report by" session value
			if ( $this->session->userdata('reportscontroller.projecttime_report.reportbykey') !== $reportByKey ) {
				$this->session->unset_userdata('reportscontroller.projecttime_report.reporttargetkey');
			}
			
			// persist report by key to session
			$this->session->set_userdata('reportscontroller.projecttime_report.reportbykey', $reportByKey);
		}
		// we'll try to restore the value from the session
		else if ( $this->session->userdata('reportscontroller.projecttime_report.reportbykey') !== false ) {
			$reportByKey = $this->session->userdata('reportscontroller.projecttime_report.reportbykey');
		}
		
		//
		// if we know what to report by, now buil catalog of valid report targets
		$reportTargets = array();	
		if ( $reportByKey == ReportsController::$PROJECTTIME_REPORT_REPORT_BY_USER_KEY ) {
			//
			// determine users accessible to active user
			
			// initialize the allowed users var to an empty propel collection
			$allowedUsers = new PropelCollection(array());
			
			if ( $this->authorize(Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS, true) ) {
				//
				// for this permission level, all users are accessible
				$allowedUsers = $userManager->getAllEnabledUsers();
			}
			else if ( $this->authorize(Permissions::REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT, true) )  {
				
				//
				// the permission indicates that the active user can access reports of users
				// of team for which he is team leader - we populate the allowed user collection accordingly
				
				// obtain a list of users assigned to teams for which the active user is team leader
				$ledTeams = $teamManager->getAllTeamsLedByUser($activeUser);		
				$allowedUsers = $userManager->getAllUsersMembersOfTeams($ledTeams);
				
				//
				// with this permission level, the active user may not list their own record.
			
				// get active user
				$activeUser = $this->getCtx()->getUser();
				
				// --> remove active user from allowed users	
				if ( $key = $allowedUsers->search($activeUser) ) {	
					$allowedUsers->remove($key);
				}
				
			}
			
			//
			// if indicated by permissions, add active user's own record to allowed users
			if ( $this->authorize(Permissions::REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT, true) )  {
			
				// get active user
				$activeUser = $this->getCtx()->getUser();
				
				// if not already present, prepend active user's own user record to list of allowed users
				if ( ! $allowedUsers->contains($activeUser) ) {
					$allowedUsers->prepend($activeUser);
				}				
			}

						
			//
			// finally, build digest of report targets
			foreach ( $allowedUsers as $curUser ) {
				$reportTargets[] = array(
											"target_key" 	=>	$curUser->getId(),
											"target_value" 	=>	$curUser->getFullName(),
										);
			}
			
		}
		else if ( $reportByKey == ReportsController::$PROJECTTIME_REPORT_REPORT_BY_PROJECT_KEY ) {
			
			//
			// determine projects accessible to active user
			
			// initialize the allowed projects var to an empty propel collection
			$allowedProjects = new PropelCollection(array());
			
			if ( $this->authorize(Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS, true) ) {
				//
				// for this permission level, all projects are accessible
				$allowedProjects = $projectManager->getAllProjects();
			}
			else if ( $this->authorize(Permissions::REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT, true) )  {
				
				//
				// the permission indicates that the active user can access project time reports for projects
				// assigned to teams for which he is team leader - we populate the allowed user collection accordingly
				
				// obtain a list of teams for which the active user is team leader
				$ledTeams = $teamManager->getAllTeamsLedByUser($activeUser);
				
				// compile a table of the project id's accessible to active user
				$allowedProjectIds = array();
				foreach ( $ledTeams as $curTeam ) {						
					foreach ( $curTeam->getProjects() as $curProject ) {
						$allowedProjectIds[$curProject->getId()] = $curProject->getId();
					}
				}
				
				//
				// query for a collection containing the determined projets
				$allowedProjects = $projectManager->getProjectsByIdList($allowedProjectIds);		
			}
			
			
			//
			// if indicated by permissions, add active user's "own" projects to collection of allowed projects
			//
			if ( $this->authorize(Permissions::REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT, true) )  {
				
				// the active user
				$activeUser = $this->getCtx()->getUser();
				
				$projectsAccssibleToUser = $projectManager->getProjectsAccessibleToUser($activeUser);
				
				//
				// compile a table of the project id's accessible to active user
				$allowedProjectIds = array();
				
				// first, we process values already present in the array of allowed projects 
				foreach ( $allowedProjects as $curProject ) {						
					$allowedProjectIds[$curProject->getId()] = $curProject->getId();
				}
				
				// next, we append the projects accessible to user by virtue of primary team membership, or direct assignment
				foreach ( $projectsAccssibleToUser as $curProject ) {						
					$allowedProjectIds[$curProject->getId()] = $curProject->getId();
				}
				
				//
				// query for a collection containing the determined projets
				$allowedProjects = $projectManager->getProjectsByIdList($allowedProjectIds);
					
			}
			
			
			// build digest of report targets
			foreach ( $allowedProjects as $curProject ) {
				$reportTargets[] = array(
											"target_key" 	=>	$curProject->getId(),
											"target_value" 	=>	$curProject->getName(),
										);
			}
			
		}
		else if ( $reportByKey == ReportsController::$PROJECTTIME_REPORT_REPORT_BY_TEAM_KEY ) {
			
			//
			// determine teams accessible to active user
			
			// initialize the allowed teams var to an empty propel collection
			$allowedTeams = new PropelCollection(array());
			
			if ( $this->authorize(Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS, true) ) {
				//
				// for this permission level, all teams are accessible
				$allowedTeams = $teamManager->getActiveTeams();
			}
			else if ( $this->authorize(Permissions::REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT, true) )  {
				
				//
				// the permission indicates that the active user can access project time reports for teams
				// for which he is team leader - we populate the allowed user collection accordingly
				
				// the active user
				$activeUser = $this->getCtx()->getUser();
				
				// obtain a list of teams for which the active user is team leader
				$allowedTeams = $teamManager->getAllTeamsLedByUser($activeUser);	
			}
			
			
			//
			// if indicated by permissions, add active user's "own" team to collection of allowed teams
			//
			if ( $this->authorize(Permissions::REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT, true) )  {
				
				// set digest of report targets to active user
				$activeUser = $this->getCtx()->getUser();

				// if the user does not have a primary team, there's nothing we need to do
				if ( $activeUser->getPrimaryTeam() !== null ) {
					
					//
					// compile a table of the team id's accessible to active user
					$allowedTeamIds = array();
					
					// first, we process values already present in the array of allowed teams 
					foreach ( $allowedTeams as $curTeam ) {						
						$allowedTeamIds[$curTeam->getId()] = $curTeam->getId();
					}
					
					// we append the user's primary team id to the table of allowed teams ids
					$allowedTeamIds[$activeUser->getPrimaryTeam()->getId()] = $activeUser->getPrimaryTeam()->getId();
					
					//
					// last, we query for a collection containing the determined teams
					$allowedTeams = $teamManager->getTeamsByIdList($allowedTeamIds);
				}
		
			}
			
			// build digest of report targets
			foreach ( $allowedTeams as $curTeam ) {
				$reportTargets[] = array(
											"target_key" 	=>	$curTeam->getId(),
											"target_value" 	=>	$curTeam->getName(),
										);
			}
		}
		
		//
		// see if there is a report target defined
		$reportTargetKey = null;
		if ( 	 $this->input->post('reportTargetKey')
			 && ($this->input->post('reportTargetKey') != "-1") ) {
			
			$reportTargetKey = $this->input->post('reportTargetKey');	
			 	
			// persist report target key to session
			$this->session->set_userdata('reportscontroller.projecttime_report.reporttargetkey', $reportTargetKey);
		}
		// if the report target has not been reset, we'll try to restore the value from the session
		else if ( $this->session->userdata('reportscontroller.projecttime_report.reporttargetkey') !== false ) {
			// restore value from session				
			$reportTargetKey = $this->session->userdata('reportscontroller.projecttime_report.reporttargetkey');
		}
		

		//
		// "from" date
		$fromDate = null;
		if ( 	   ($this->input->post('fromDate') !== false)
				&& ($this->input->post('fromDate') != "") ) {
			//
			// if there is a valid date passed, we accept it and persist it to the session
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('fromDate'));
			
			// persist to session
			$this->session->set_userdata('reportscontroller.projecttime_report.fromdate', $fromDate);
		}
		else if ( $this->input->post('fromDate') !== false ) {
			// if there is an empty date passed, we default to the start of the current month
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat("1-" . DateTimeHelper::getCurrentMonth() . "-" . DateTimeHelper::getCurrentYear());
			
			// persist to session
			$this->session->set_userdata('reportscontroller.projecttime_report.fromdate', $fromDate);
		}
		else {
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('reportscontroller.projecttime_report.fromdate') !== false ) {
				$fromDate = $this->session->userdata('reportscontroller.projecttime_report.fromdate');
			}
			else {
				// if there is no session value to restore, we set the from date to the start of the current month		
				$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat("1-" . DateTimeHelper::getCurrentMonth() . "-" . DateTimeHelper::getCurrentYear());
			}			
		}
		
		
		//
		// "until" date
		$untilDate = null;
		if ( 	   ($this->input->post('untilDate') !== false)
				&& ($this->input->post('untilDate') != "") ) {
			//		
			// if there is a valid date passed, we accept it and persist it to the session
			$untilDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			
			// persist to session
			$this->session->set_userdata('reportscontroller.projecttime_report.untildate', $untilDate);
		}
		else if ( $this->input->post('untilDate') !== false ) {
				
			// if there is an empty date passed, we default to a one month range (based on current from date)	
			$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, (DateTimeHelper::getMonthDaysFromDateTime($fromDate) - 1));
			
			// persist to session
			$this->session->set_userdata('reportscontroller.projecttime_report.untildate', $untilDate);
		}
		else {
			
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('reportscontroller.projecttime_report.untildate') !== false ) {
				$untilDate = $this->session->userdata('reportscontroller.projecttime_report.untildate');
			}
			else {
				// if there is no session value to restore, we default to a one month range (based on current from date)	
				$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, (DateTimeHelper::getMonthDaysFromDateTime($fromDate) - 1));
			}	
		}

		// the compilation of the report data occurs if we
		// have a valid report target
		$reportData = array();
		$reportTargetPrettyName = null;
		if ( $reportTargetKey !== null ) {
			
			// retrieve instance of report target object
			if ( $reportByKey == \ReportsController::$PROJECTTIME_REPORT_REPORT_BY_USER_KEY ) {	
				//
				// as report target is a user object, we retrieve concerned user instance
				$reportTarget = $userManager->getUserById($reportTargetKey);
				$reportTargetPrettyName = $reportTarget->getFullName();
				
				//
				// we make sure the indicated user is contained in the list of allowed users
				// if it does not, we invalidate the report target
				if ( ! $allowedUsers->contains($reportTarget) ) {
					$reportTarget = null;
					$reportTargetPrettyName = null;
				}
				
			}
			else if ( $reportByKey == \ReportsController::$PROJECTTIME_REPORT_REPORT_BY_TEAM_KEY ) {	
				//
				// as report target is a team object, we retrieve concerned team instance
				$reportTarget = $teamManager->getTeamById($reportTargetKey);
				$reportTargetPrettyName = $reportTarget->getName();
				
				//
				// we make sure the indicated team is contained in the list of allowed teams
				// if it does not, we invalidate the report target
				if ( ! $allowedTeams->contains($reportTarget) ) {
					$reportTarget = null;
					$reportTargetPrettyName = null;
				}
				
			}
			else if ( $reportByKey == \ReportsController::$PROJECTTIME_REPORT_REPORT_BY_PROJECT_KEY ) {	
				//
				// as report target is a project object, we retrieve concerned project instance
				$reportTarget = $projectManager->getProjectById($reportTargetKey);
				$reportTargetPrettyName = $reportTarget->getName();
				
				//
				// we make sure the indicated team is contained in the list of allowed teams
				// if it does not, we invalidate the report target
				if ( ! $allowedProjects->contains($reportTarget) ) {
					$reportTarget = null;
					$reportTargetPrettyName = null;
				}
			}
			
			//
			// compile report data
			$reportData = $reportingService->compileProjectTimeReport(
														$reportTarget,
														$fromDate,
														$untilDate
													);
			
		}

		
		//
		// prepare view data
		$data["component"] = "components/component_other_projecttime_report.php";
		$data["component_title"] = "Project Time Report";
		
		$data["component_report_data"] = $reportData;
		$data["component_report_targets"] = $reportTargets;
		$data["component_report_target_key"] = $reportTargetKey;
		$data["component_report_target_pretty_name"] = $reportTargetPrettyName;
		
		$data["component_reportbys"] = ReportsController::$PROJECTTIME_REPORT_REPORT_BY_OPTIONS_MAP;
		$data["component_reportby_key"] = $reportByKey;
		$data["component_reportby_pretty_name"] = ($reportByKey !== null ? strtolower(ReportsController::$PROJECTTIME_REPORT_REPORT_BY_OPTIONS_MAP[$reportByKey]) : null);
		
		$data["component_fromdate"] = $fromDate;
		$data["component_untildate"] = $untilDate;
		
		
		// render view
		$this->renderView($data);			 
	}
	
	
	/**
	 * Displays the timetracker report as parametrized via post
	 */
	public function displayTimetrackerReport() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::REPORTS_VIEW_OWN_TIMETRACKER_REPORT,
										Permissions::REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT,
										Permissions::REPORTS_VIEW_ALL_TIMETRACKER_REPORTS
									)	
								 );
		
		//
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$reportingService = $this->getCtx()->getReportingService();
		$compService = $this->getCtx()->getComputationService();
		
		//
		// initialize parameters and runtime vars
		$reportData = array();
		$allowedUsers = array();
		
		$fromDate = null;
		$untilDate = null;
		$groupBy = null;
		$targetReportItemKey = null;
		$targetUser = null;
		$reportedRangeTotalTimeCredit = null;			
		$reportedRangeWorkTimeCredit = null;		
		
		//
		// compile digest of reportable items
		
		// add regular entry types to digest
		$regularEntryTypes = $entryTypeManager->getAllTypes();
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY] = array();
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY]["group_name"] = "Timetracker";
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY]["group_items"] = array();
		foreach ( $regularEntryTypes as $curEntryType ) {
			$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY]["group_items"][ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY . "-" . $curEntryType->getId()] = $curEntryType->getType();
		}
		
		// add oo booking types to digest
		$ooBookingTypes = $bookingTypeManager->getAllTypes();
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY] = array();
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY]["group_name"] = "Out-of-Office";
		$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY]["group_items"] = array();
		foreach ( $ooBookingTypes as $curBookingType ) {
			// the bookings "name" is given by its type designation
			$bookingTypeName = $curBookingType->getType();
			
			// system created booking types need to be mapped to a pretty name 
			if ( $curBookingType->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
				$bookingTypeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$bookingTypeName];
			}
			
			$reportableItems[ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY]["group_items"][ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY . "-" . $curBookingType->getId()] = $bookingTypeName;
		}
		
		//
		// determine target user from passed params or, if available, from session value
		if ( 	 $this->input->post('targetUserId')
			 && ($this->input->post('targetUserId') != -1) ) {
			
			// obtain a reference to the target user
			$targetUser = $userManager->getUserById($this->input->post('targetUserId'));
			
			// persist selected user to session
			$this->session->set_userdata('reportscontroller.timetracker_report.target_user.id', $this->input->post('targetUserId'));
		}
		// we'll try to restore the value from the session
		else if ( $this->session->userdata('reportscontroller.timetracker_report.target_user.id') !== false ) {
			$targetUser = $userManager->getUserById($this->session->userdata('reportscontroller.timetracker_report.target_user.id'));
		}
		
		
		//
		// determine the set of users accessible for reporting
		$allowedUsers = new \PropelCollection(array());
		
		if ( $this->authorize(Permissions::REPORTS_VIEW_ALL_TIMETRACKER_REPORTS, true) ) {
			//
			// the permission indicates that the active user can access all reports
			// by extension, the determined target user is also ok
			$allowedUsers = $userManager->getAllUsers();
		}
		else if ( $this->authorize(Permissions::REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT, true) ) {
			
			//
			// the permission indicates that the active user can access reports of users for which he is team leader
			// we populate the allowed user collection accordingly
			
			// get active user
			$activeUser = $this->getCtx()->getUser();
			
			// obtain a list of users assigned to teams for which the active user is team leader
			$ledTeams = $teamManager->getAllTeamsLedByUser($activeUser);		
			$allowedUsers = $userManager->getAllUsersMembersOfTeams($ledTeams);
			
			// this permission does not allow access to the active user's own record
			// hence, we remove the record if it pops up in the list of team members
			if ( $key = $allowedUsers->search($activeUser) ) {	
				$allowedUsers->remove($key);
			}
					
		}
		
		//
		// if indicated by permissions, add active user's "own" user record to collection of allowed users
		//
		if ( $this->authorize(Permissions::REPORTS_VIEW_OWN_TIMETRACKER_REPORT, true) ) {
			
			//
			// the permission indicates that the active user can access their own report
			// we populate the allowed user collection accordingly
			
			// get active user
			$activeUser = $this->getCtx()->getUser();
		
			// if not already present, prepend active user's own user record to list of allowed users
			if ( ! $allowedUsers->contains($activeUser) ) {
				$allowedUsers->prepend($activeUser);
			}	
		}
	
		
		//
		// figure out date range to work with
		
		//
		// "from" date
		$fromDate = null;
		if ( 	   ($this->input->post('fromDate') !== false)
				&& ($this->input->post('fromDate') != "") ) {
			//
			// if there is a valid date passed, we accept it and persist it to the session
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('fromDate'));
			
			// persist to session
			$this->session->set_userdata('reportscontroller.timetracker_report.fromdate', $fromDate);
		}
		else if ( $this->input->post('fromDate') !== false ) {
			// if there is an empty date passed, we default to the start of the current week
			$fromDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
			
			// persist to session
			$this->session->set_userdata('reportscontroller.timetracker_report.fromdate', $fromDate);
		}
		else {
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('reportscontroller.timetracker_report.fromdate') !== false ) {
				$fromDate = $this->session->userdata('reportscontroller.timetracker_report.fromdate');
			}
			else {
				// if there is no session value to restore, we set the from date to the start of the current week		
				$fromDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
			}			
		}
		
		
		//
		// "until" date
		$untilDate = null;
		if ( 	   ($this->input->post('untilDate') !== false)
				&& ($this->input->post('untilDate') != "") ) {
			//		
			// if there is a valid date passed, we accept it and persist it to the session
			$untilDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			
			// persist to session
			$this->session->set_userdata('reportscontroller.timetracker_report.untildate', $untilDate);
		}
		else if ( $this->input->post('untilDate') !== false ) {
			// if there is an empty date passed, we default to a one week range (based on current from date)	
			$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, 6);
			
			// persist to session
			$this->session->set_userdata('reportscontroller.timetracker_report.untildate', $untilDate);
		}
		else {
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('reportscontroller.timetracker_report.untildate') !== false ) {
				$untilDate = $this->session->userdata('reportscontroller.timetracker_report.untildate');
			}
			else {
				// if there is no session value to restore, we default to a one week range (based on current from date)	
				$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, 6);
			}	
		}
	
		
		//
		// if a target user is defined, make sure it is contained in the list of allowed users
		// if it does not, we invalidate the target user
		if (	($targetUser !== null)
			 && (! $allowedUsers->contains($targetUser)) ) {
			 	
			$targetUser = null;
		}
		
		//
		// further processing is dependent on having a target user
		
		$reportedRangeTotalTimeCredit = null;			
		$reportedRangeTotalWorkTimeCredit = null;	
		$reportedRangeTimetrackerWorkTimeCredit = null;
		$reportedRangeOOBookingWorkTimeCredit = null;
		
		if ( $targetUser !== null ) {
			
			//
			// determine target report item from passed params or, if available, from session value
			if ( $this->input->post('reportItemKey') ) {
				// get report item key
				$targetReportItemKey = $this->input->post('reportItemKey');
				 	
				// persist selected user to session
				$this->session->set_userdata('reportscontroller.timetracker_report.target_report.item_id', $this->input->post('reportItemKey'));
			}
			// we'll try to restore the value from the session
			else if ( $this->session->userdata('reportscontroller.timetracker_report.target_report.item_id') !== false ) {
				$targetReportItemKey = $this->session->userdata('reportscontroller.timetracker_report.target_report.item_id');
			}
			
			// "groupBy" defaults to "day"
			$groupBy = "day";
			if ( $this->input->post('groupBy') !== false ) {
				$groupBy = $this->input->post('groupBy');
			}

			// get timetracker report data
			$reportData = $reportingService->compileTimetrackerReport(	
															$targetUser,
															$targetReportItemKey !== "-1" ? $targetReportItemKey : null ,
															$groupBy,
															$fromDate,
															$untilDate
														  );
														  
			// compute total time and worktime for the indicated time range
			$reportedRangeTotalTimeCredit = $compService->computeTotalTimeCreditForUser($targetUser, $fromDate, $untilDate);			
			$reportedRangeTotalWorkTimeCredit = $compService->computeTotalWorktimeCreditForUser($targetUser, $fromDate, $untilDate);
			$reportedRangeTimetrackerWorkTimeCredit = $compService->computeRegularEntryWorktimeCreditForUser($targetUser, $fromDate, $untilDate);
			$reportedRangeOOBookingWorkTimeCredit = $compService->computeOOBookingWorktimeCreditForUser($targetUser, $fromDate, $untilDate);							  
		}
		
		//
		// prepare data for view
		$data["component"] = "components/component_other_timetracker_report.php";
		$data["component_title"] = "Timetracker Report";
		
		$data["component_report_data"] = $reportData;
		$data["component_report_total_time"] = $reportedRangeTotalTimeCredit;
		$data["component_report_total_work_time"] = $reportedRangeTotalWorkTimeCredit;
		$data["component_report_timetracker_work_time"] = $reportedRangeTimetrackerWorkTimeCredit;
		$data["component_report_oobooking_work_time"] = $reportedRangeOOBookingWorkTimeCredit;
		
		$data["component_targetuser"] = $targetUser;
		$data["component_target_report_item_key"] = $targetReportItemKey;
		$data["component_allowed_users"] = $allowedUsers;
		$data["component_reportable_items"] = $reportableItems;
		$data["component_fromdate"] = $fromDate;
		$data["component_untildate"] = $untilDate;
		$data["component_groupby"] = $groupBy;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 * Displays the oo summary as parametrized via post
	 */
	public function displayOOSummary() {
		
		// authorize
		$this->authorize(Permissions::REPORTS_VIEW_ALL_OO_SUMMARIES);

		// get ref to managers/services needed
		$reportingService = $this->getCtx()->getReportingService();
		$userManager = $this->getCtx()->getUserManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		//
		// process post params
		
		// determine target year from passed params or, if available, from session value
		$targetWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getCurrentYear());	
		if ( $this->input->post('targetYear') ) {
			$targetWorkplan = $workplanManager->getPlanByYear($this->input->post('targetYear'));
			
			// persist selected year to session
			$this->session->set_userdata('reportscontroller.oo_summary.target_year', $this->input->post('targetYear'));
		}
		// we'll try to restore the value from the session
		else if ( $this->session->userdata('reportscontroller.oo_summary.target_year') !== false ) {
			$targetWorkplan = $workplanManager->getPlanByYear($this->session->userdata('reportscontroller.oo_summary.target_year'));
		}
		
		// determine target user from passed params or, if available, from session value
		$targetUser = null;
		if ( 	 $this->input->post('targetUserId')
			 && ($this->input->post('targetUserId') != -1) ) {
			
			// obtain a reference to the target user
			$targetUser = $userManager->getUserById($this->input->post('targetUserId'));
			
			// persist selected user to session
			$this->session->set_userdata('reportscontroller.oo_summary.target_user.id', $this->input->post('targetUserId'));
		}
		// we'll try to restore the value from the session
		else if ( $this->session->userdata('reportscontroller.oo_summary.target_user.id') !== false ) {
			$targetUser = $userManager->getUserById($this->session->userdata('reportscontroller.oo_summary.target_user.id'));
		}
		
		// we compile the oo summary if a user is indicated
		$ooSummary = array();
		if ( $targetUser !== null ) {
			// get oo summary for user and workplan
			$ooSummary = $reportingService->compileOOSummary(	
													$targetUser,
													$targetWorkplan
												  );
		}
				
		//
		// prepare data for view
		$data["component"] = "components/component_other_oo_summary.php";
		$data["component_title"] = "Out of Office Summary";
		
		$data["component_oosummary"] = $ooSummary;
		$data["component_users"] = $userManager->getAllUsers();
		$data["component_workplans"] = $workplanManager->getAllPlans();
		$data["component_targetuser"] = $targetUser;
		$data["component_targetworkplan"] = $targetWorkplan;
		
		// render view
		$this->renderView($data);
	}
	
}