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

use momo\audittrailmanager\EventDescription;
use momo\entrymanager\EntryManager;
use momo\oomanager\OOManager;
use momo\core\helpers\DebugHelper;
use momo\core\helpers\SettingsHelper;
use momo\core\helpers\DateTimeHelper;
use momo\core\helpers\StringHelper;
use momo\core\security\Permissions;
use momo\usermanager\exceptions\UserNotFoundException;
use momo\core\exceptions\MomoException;
use momo\workplanmanager\exceptions\DayDoesNotExistException;

/**
 * TimetrackerController
 * 
 * Exposes the client actions needed to work with the Timetracker.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class TimetrackerController extends Momo_Controller {
	
	/**
	 * Default action
	 * 
	 */
	public function index() {		
		$this->display();
	}
	
	
	/**
	 * Displays the timetracker for the indicated date and user.
	 * 
	 * Calling the method switches the timetracker's "display user" state to the indicated user.
	 * 
	 * If an invalid user id is passed, the active session's user is set as the "display user".
	 * 
	 * Note: 	- any date can be passed, with the Timetracker view always displaying the
	 * 			  entire week that contains the date in question.
	 * 
	 * 			- if the indicated date cannot be validated, the timetracker will default
	 * 			  to the current date.
	 * 
	 * @param 	string	$day		
	 * @param 	string	$month	
	 * @param 	string	$year
	 * @param 	string	$displayUserId		- id of the user to set as the display user
	 * 
	 */
	public function displayForUser($day=null, $month=null, $year=null, $displayUserId=null) {
		
		// set the indicated user id as the display user
		$this->setDisplayUser($displayUserId);
		
		// display the timetracker
		$this->display($day, $month, $year);
	}
	
	
	
	/**
	 * Displays the timetracker for the indicated date and currently active display user
	 * 
	 * Note: 	- any date can be passed, with the Timetracker view always displaying the
	 * 			  entire week that contains the date in question.
	 * 
	 * 			- if the indicated date cannot be validated, the timetracker will default
	 * 			  to the current date.
	 * 
	 * @param 	string	$day		
	 * @param 	string	$month	
	 * @param 	string	$year
	 */
	public function display($day=null, $month=null, $year=null) {
		
		// get refs to managers/services needed
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$projectManager = $this->getCtx()->getProjectManager();
		$entryManager = $this->getCtx()->getEntryManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		$compService = $this->getCtx()->getComputationService();
		$enforcementService = $this->getCtx()->getEnforcementService();
		
		// the active user
		$activeUser = $this->getCtx()->getUser();
		
		//
		// get the current display user
		$displayUser = $this->getDisplayUser();
		
		// if there is no valid display user, we default to the active user
		if ( $displayUser == null ) {
			$this->setDisplayUser();
			$displayUser = $this->getDisplayUser();
		}
		
		//
		// get date of last day of last workplan in existence
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		
		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
			
		// set allowed timetracker access range based on the retrieved data
		//
		// -- the start of the allowed access range corresponds to the later of the two dates "app use start date" and "user entry date"
		// -- likewise, the end of the allowed access range corresponds to the earlier of the two dates "last workplan date" and "user exit date"
		//
		$allowedAccessRangeStartDate = ($displayUser->getEntryDate() > $appUseStartDate) ? $displayUser->getEntryDate() : $appUseStartDate;
		$allowedAccessRangeEndDate = ($displayUser->getExitDate() < $lastWorkplanDate) ? $displayUser->getExitDate() : $lastWorkplanDate;
		
		//
		// test and process the call parameters
		//
		// note, in the following:
		//						   "selectedDate" 		is the date with which the controller was called, i.e. the user's date selection
		//						   "displayStartDate"  	is the first date displayed in the resulting view - this is always the monday of the week containing the user's selected date
		//						   "displayEndDate"	  	is the last date displayed in the resulting view - this is always the sunday of the week containing the user's selected date
		//	

		// cast call params to integer so as to drop leading zeros
		// -->  momo standard date format has no leading zeroes (see DateTimeHelper)
		$day = (integer) $day;
		$month = (integer) $month;
		$year = (integer) $year;
		
		// check if call date is valid, and within the allowed access date range,
		// -- if so, instantiate DateTime representations of dates described above
		if ( 		DateTimeHelper::isValidDate($day, $month, $year) 
				&& (DateTimeHelper::getDateTimeFromStandardDateFormat($day . "-" . $month . "-" . $year) >=  $allowedAccessRangeStartDate)
				&& (DateTimeHelper::getDateTimeFromStandardDateFormat($day . "-" . $month . "-" . $year) <=  $allowedAccessRangeEndDate)
			) {
			
			// the week's start date
			$displayStartDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeFromStandardDateFormat($day . "-" . $month . "-" . $year));
			
			// the week's end date
			$displayEndDate = DateTimeHelper::getEndOfWeek(DateTimeHelper::getDateTimeFromStandardDateFormat($day . "-" . $month . "-" . $year));
			
			// the selected date
			$selectedDate = DateTimeHelper::getDateTimeFromStandardDateFormat($day . "-" . $month . "-" . $year);
			
		}
		else {
			
			//
			// call date is invalid, or out of range
			
			//
			// if current date is within accessible range, default to it otherwise, we proceed as follows:
			//
			// - if the user's first accessible date lies in the future, we jump to the user's first accessible date
			// - if the user's first accessible date lies in the past, we jump to the user's last accessible date
			
			// default to the current date, if it lies within the accessible range
			if ( 	(DateTimeHelper::getDateTimeForCurrentDate() >= $allowedAccessRangeStartDate)
				 && (DateTimeHelper::getDateTimeForCurrentDate() <= $allowedAccessRangeEndDate) ) {
				 	
				// set selected date to current date
				$selectedDate = DateTimeHelper::getDateTimeForCurrentDate(); 		 		 	
			}
			else if ( $allowedAccessRangeStartDate > DateTimeHelper::getDateTimeForCurrentDate() ) {	
				// first accessible date is in future, hence we set selected date to first accessible date
				$selectedDate = $allowedAccessRangeStartDate; 		 		 	
			}
			else {
				// first accessible date is in past, we set selected date to last accessible date
				$selectedDate = $allowedAccessRangeEndDate;
			}
			
			// the week's start date
			$displayStartDate = DateTimeHelper::getStartofWeek($selectedDate);
				
			// the week's end date
			$displayEndDate = DateTimeHelper::getEndOfWeek($selectedDate);
			
		}
		
		//
		// initialize work week
		// -- method is idempotent
		$entryManager->initWorkWeek($displayUser, $displayStartDate);
		
		// get workplan applicable to selected date
		$workPlanForSelectedDate = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($selectedDate));
		
		//
		// initialize workplan for selected date
		// -- method is idempotent
		$workplanManager->initWorkplanForUser($displayUser, $workPlanForSelectedDate);
		
		// figure out if the display week lies beyond the current week
		$weekIsInFuture = true;
		if ( DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate()) >= DateTimeHelper::getStartOfWeek($displayStartDate) ) {
			// if we're in the same year
			$weekIsInFuture = false;
		}
		
		//
		// retrieve indicated week's regular entries and compile them into the week data structure
		$timeTrackerWeekData = array();
		$curDayDate = $displayStartDate;
		for ( $weekDayIndex = 0; $weekDayIndex <= 6;  $weekDayIndex++ ) {
				
			try {

				//
				// get a reference to Day object for current day
				//
				// in case the display week extends from/into a workplan that does not exist, this operation can throw an exception.
				//
				$curDay = $workplanManager->getDayForDate($curDayDate);
				
				//
				// compile day's information, which consists of:
				//
				//	- regular entries
				//  - project entries
				//	- total worktime credit
				//  - weekday index (zero based 0=Mon, 6=Sun)
				//	- if applicable, holiday information for the day
				//
				$timeTrackerWeekData[$weekDayIndex]["weekday_index"] 					= $weekDayIndex;
				
				$timeTrackerWeekData[$weekDayIndex]["regular_entries"] 					= $entryManager->getRegularEntriesForDateAndUser($displayUser, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["project_entries"] 					= $entryManager->getProjectEntriesForDateAndUser($displayUser, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["oo_entry"] 						= $entryManager->getAllotmentOOEntryForDateAndUser($displayUser, $curDayDate);
				
				$timeTrackerWeekData[$weekDayIndex]["total_time_credit"] 				= $compService->computeTotalTimeCreditForUser($displayUser, $curDayDate, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["regularentry_worktime_credit"] 	= $compService->computeRegularEntryWorktimeCreditForUser($displayUser, $curDayDate, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["oobooking_worktime_credit"] 		= $compService->computeOOBookingWorktimeCreditForUser($displayUser, $curDayDate, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["total_worktime_credit"] 			= $compService->computeTotalWorktimeCreditForUser($displayUser, $curDayDate, $curDayDate);
				$timeTrackerWeekData[$weekDayIndex]["total_project_time_credit"] 		= $compService->computeProjectTimeCreditForUser($displayUser, $curDayDate, $curDayDate);
				
				$timeTrackerWeekData[$weekDayIndex]["day"] 								= $curDay;
				$timeTrackerWeekData[$weekDayIndex]["day_off_day_tag"]					= $this->getOffDayTag($curDay, $displayUser);
				$timeTrackerWeekData[$weekDayIndex]["day_islocked"]						= $this->isDayLocked($curDay, $displayUser);
				$timeTrackerWeekData[$weekDayIndex]["day_is_full_off"]					= $this->isDayFullOff($curDay, $displayUser);
				$timeTrackerWeekData[$weekDayIndex]["day_allows_new_regular_entries"]	= $this->dayAllowsNewRegularEntries($curDay, $displayUser);
				$timeTrackerWeekData[$weekDayIndex]["day_allows_off_day_control"]		= $this->dayAllowsOffDayControl($curDay, $displayUser);
				$timeTrackerWeekData[$weekDayIndex]["day_is_weekend_day"]				= DateTimeHelper::getWeekDayNumberFromDateTime($curDayDate) > 5 ? true : false;
					
				$timeTrackerWeekData[$weekDayIndex]["day_holiday"] 						= $workplanManager->getHolidayForDay($curDay);
					
			}
			catch (DayDoesNotExistException $ex) {
				// NOOP
			}

			// increment the currently processed date by one day
			$curDayDate = DateTimeHelper::addDaysToDateTime($curDayDate, 1);
		}
			
		//
		// obtain time stats
		
		// global values
		$totalWorkTimeCreditInSec 		=     $compService->computeTotalWorktimeCreditForUser($displayUser, $displayUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate())
											+ $compService->computeWorktimeAdjustmentsForUser($displayUser, $displayUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
									
		$totalPlanTimeInSec 			= $compService->computePlanTimeForUser($displayUser, $displayUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
		
		// week scope values
		$weekWorkTimeCreditInSec 		= $compService->computeTotalWorktimeCreditForUser($displayUser, $displayStartDate, $displayEndDate);
		$weekPlanTimeInSec 				= $compService->computePlanTimeForUser($displayUser, $displayStartDate, $displayEndDate);
		
		//
		// obtain a digest of vacation stats, this will contain all vacation related information as actually recorded in the system
		$vacationStatisticsDigest = $compService->computeVacationStatisticsDigestForUser($displayUser);

/**

	2013-11-11  MKY @ ISN
	
	Not the neatest modification, but the least potentially destructive to current system.
	Added extra calculation of vacation stats using knowledge of the displayUser's contract end date set as the target date.
	
*/
		//
		// obtain a digest of vacation stats with respect to the user's exit date.
		$mky_temp_displayUserExitDate = $displayUser->getExitDate() ;
		$mky_temp_vacationStatisticsDigest = $compService->computeVacationStatisticsDigestForUser($displayUser , $mky_temp_displayUserExitDate);

		//
		//overwrite the effective balance value
		$vacationStatisticsDigest["aggregate_results"]["global_vacation_days_balance_effective"] = $mky_temp_vacationStatisticsDigest["aggregate_results"]["global_vacation_days_balance_effective"];

// var_dump just used while testing
// var_dump( $mky_temp_vacationStatisticsDigest ) ;

/**

	2013-11-11  MKY @ ISN
	
	With the value for effective vacation balance overwritten, our changes have finished
	
*/
		
		// 
		// set data points that are dependent on what timetracker we're looking at
		if ( $displayUser->getId() == $activeUser->getId() ) {
			// when viewing own timetracker, we set the title to the user's first name
			$timetrackerTitle = StringHelper::possessivizeString($displayUser->getFirstName()) . " Timetracker";
			
			// set flag to indicate that we're viewing active user's own timetracker
			$viewingOwnTimetracker = true;
		}
		else {
			// when viewing other user's timetracker, we we set the title to that user's full name
			$timetrackerTitle = StringHelper::possessivizeString($displayUser->getFullName())  . " Timetracker";
			
			// set flag to indicate that we're viewing some other user's timetracker
			$viewingOwnTimetracker = false;
		}
					
		//
		// set up view data
		$data["component"] = "components/component_container_timetracker.php";
		
		$data["timetracker_timedelta_total_insec"] 			= $totalWorkTimeCreditInSec - $totalPlanTimeInSec;
		$data["timetracker_worktime_credit_week_in_sec"] 	= $weekWorkTimeCreditInSec;
		$data["timetracker_plantime_week_in_sec"] 			= $weekPlanTimeInSec;
		$data["timetracker_timedelta_week_insec"] 			= $weekWorkTimeCreditInSec - $weekPlanTimeInSec;
		
		$data["timetracker_vacation_taken"] 				= $vacationStatisticsDigest["vacation_days_consumed_by_workplan"][DateTimeHelper::getCurrentYear()];
		$data["timetracker_vacation_booked"] 				= $vacationStatisticsDigest["aggregate_results"]["global_vacation_days_booked"];
		$data["timetracker_vacation_balance_effective"] 	= $vacationStatisticsDigest["aggregate_results"]["global_vacation_days_balance_effective"];
		$data["timetracker_vacation_balance_projected"] 	= $vacationStatisticsDigest["aggregate_results"]["global_vacation_days_balance_projected"];
		
		$data["timetracker_week_data"] 						= $timeTrackerWeekData;
		$data["timetracker_week_is_in_future"] 				= $weekIsInFuture;
		$data["timetracker_entrytypes"] 					= $entryTypeManager->getAllActiveTypes();
		$data["timetracker_projects"] 						= $projectManager->getProjectsAccessibleToUser($displayUser);
		$data["timetracker_incomplete_weeks"] 				= $enforcementService->getIncompleteWeeksForUser($displayUser);
		$data["timetracker_unlocked_weeks"] 				= $enforcementService->getUnlockedWeekMondaysForUser($displayUser);
		
		$data["timetracker_date_min"] 						= $allowedAccessRangeStartDate;
		$data["timetracker_date_max"] 						= $allowedAccessRangeEndDate;
		$data["timetracker_nominal_daily_worktime_in_sec"] 	= 3600 * ($workPlanForSelectedDate->getWeeklyWorkHours() / 5);
		
		$data["timetracker_selected_date"] 					= $selectedDate;
		$data["timetracker_today_date"] 					= DateTimeHelper::getDateTimeForCurrentDate();
		
		$data["timetracker_displayuser"] 					= $displayUser;
		$data["timetracker_activeuser"] 					= $activeUser;
		$data["timetracker_viewing_own_timetracker"] 		= $viewingOwnTimetracker;
		
		$data["timetracker_title"] 							= $timetrackerTitle;
		
		// render view
		$this->renderView($data);
	
	}
	
	
	/**
	 * Creates a new regular entry
	 */
	public function createRegularEntry() {
		
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_CREATE_ENTRY);
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get display user
		$displayUser = $this->getDisplayUser();
		
		// proceed, if we have a valid display user
		if ( $displayUser !== null ) {
			
			// get db connection
			$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {
			
				//
				// create entry
				$newEntry = $entryManager->createRegularEntry(	
														$displayUser,
														$this->input->post('typeId'),
														DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('entryDate')),
														new DateTime($this->input->post('from')),
														new DateTime($this->input->post('until')),
														html_entity_decode(trim($this->input->post('comment')))
												);
												
				// recompute the overtime lapses
				$enforcementService->recomputeAllOvertimeLapses($displayUser);	
	
				// write event to audit trail
				$eventDescription = new EventDescription();
				$eventDescription->addDescriptionItemDigest($newEntry->compileStateDigest());	
					
				$auditManager->addAuditEvent(	
										$this->getCtx()->getUser(),
										EntryManager::MANAGER_KEY,
										"Created Time Entry",
										$eventDescription
									);	

				// commit TX
				$con->commit();
				
				// redisplay concerned date in timetracker
				$redirectionTargetUrl = $this->getDisplayActionUrl($newEntry->getDay()->getDateOfDay());
					
			}
			catch (\Exception $ex) {
				// rollback
				$con->rollback();
				// rethrow			
				throw new MomoException("TimetrackerController:createRegularEntry() - an error occurred while while attempting to create a time entry for user: " . $displayUser->getFullName(), 0, $ex);
			}							
																			
		}
		else {
			// no valid display user, let timetracker default action figure it out
			$redirectionTargetUrl = "/timetracker";
		}
		
		// redirect as indicated
		redirect($redirectionTargetUrl);
	}
	
	
	/**
	 * Creates a new project entry
	 */
	public function createProjectEntry() {
		
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_CREATE_ENTRY);
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$projectManager = $this->getCtx()->getProjectManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get display user
		$displayUser = $this->getDisplayUser();
		
		// proceed, if we have a valid display user
		if ( $displayUser !== null ) {
			
			// get db connection
			$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {
		
				// create entry
				$newEntry = $entryManager->createProjectEntry(
													$displayUser,
													DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('entryDate')),
													$projectManager->getProjectById($this->input->post('projectId')),
													$this->input->post('hoursNormalized')
												);
												
				//
				// write event to audit trail
				$eventDescription = new EventDescription();
				$eventDescription->addDescriptionItemDigest($newEntry->compileStateDigest());	
															
				$auditManager->addAuditEvent(	
										$this->getCtx()->getUser(),
										OOManager::MANAGER_KEY,
										"Created Project Entry",
										$eventDescription
									);	

				// commit TX
				$con->commit();
				
				// redisplay concerned date in timetracker
				$redirectionTargetUrl = $this->getDisplayActionUrl($newEntry->getDay()->getDateOfDay());					

			}
			catch (\Exception $ex) {
				// rollback
				$con->rollback();
				// rethrow			
				throw new MomoException("TimetrackerController:createProjectEntry() - an error occurred while while attempting to create a project entry for user: " . $displayUser->getFullName(), 0, $ex);
			}							
																	
		}
		else {
			// no valid display user, let timetracker default action figure it out
			$redirectionTargetUrl = "/timetracker";
		}
		
		redirect($redirectionTargetUrl);
	}
	
	
	/**
	 * Updates a regular entry
	 */
	public function updateRegularEntry() {
		
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_UPDATE_ENTRY);
		
		// get managers
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		$entryManager = $this->getCtx()->getEntryManager();
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		
		// get db connection
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get pre-update state for later use
			$updateTarget = $entryManager->getEntryById($this->input->post('entryId'))->getChildObject();
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// update entry
			$updateTarget = $entryManager->updateRegularEntry(	
														$this->input->post('entryId'),
														$this->input->post('typeId'),
														new DateTime($this->input->post('from')),
														new DateTime($this->input->post('until')),
														html_entity_decode(trim($this->input->post('comment')))
													);
											
			// recompute the overtime lapses
			$enforcementService->recomputeAllOvertimeLapses($updateTarget->getUser());									
																							
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());		
		
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									"Updated Time Entry",
									$eventDescription
								);
								
			// commit TX
			$con->commit();					
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow			
			throw new MomoException("TimetrackerController:updateRegularEntry() - an error occurred while while attempting to update a time entry for user: " . $this->getDisplayUser()->getFullName(), 0, $ex);
		}									
											
		// redisplay concerned date in timetracker
		redirect($this->getDisplayActionUrl($updateTarget->getDay()->getDateOfDay()));
	}
	
	
	/**
	 * Updates a project entry
	 */
	public function updateProjectEntry() {
		
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_UPDATE_ENTRY);
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$projectManager = $this->getCtx()->getProjectManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// save a copy of the pre-update state
			$updateTarget = $entryManager->getEntryById($this->input->post('entryId'))->getChildObject();
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// update entry
			$updateTarget = $entryManager->updateProjectEntry(
														$this->input->post('entryId'),
														$projectManager->getProjectById($this->input->post('projectId')),
														$this->input->post('hoursNormalized')
												);
												
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());		
					
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									"Updated Project Entry",
									$eventDescription
								);
			// commit TX
			$con->commit();					
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow			
			throw new MomoException("TimetrackerController:updateProjectEntry() - an error occurred while while attempting to update a project entry for user: " . $this->getDisplayUser()->getFullName(), 0, $ex);
		}					

		// redisplay concerned date in timetracker
		redirect($this->getDisplayActionUrl($updateTarget->getDay()->getDateofDay()));
	}
	
	
	/**
	 * Deletes a timetracker entry.
	 */
	public function deleteEntry() {
		
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_DELETE_ENTRY);
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$tagManager = $this->getCtx()->getTagManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TagPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {	
			// get a reference to entry to delete
			$deleteTarget = $entryManager->getEntryById($this->input->post('entryId'))->getChildObject();
			$deleteTargetUser = $deleteTarget->getUser();
			
			//
			// the operation might invalidate the week's completeness state
			// we therefore need to clear any possible "week complete" flag on the week that the entry resides in

			// determine start of week date, as week completeness state tag resides there
			$startOfWeekDate = DateTimeHelper::getStartOfWeek($deleteTarget->getDay()->getDateOfDay());
			$startOfWeekDay = $workplanManager->getDayForDate($startOfWeekDate);
			
			// delete possible "week complete" tag
			$tagManager->deleteDayTagByType($startOfWeekDay, \Tag::TYPE_WEEK_COMPLETE, $deleteTarget->getUser());
			
			// delete entry
			$entryManager->deleteEntryById($this->input->post('entryId'));
			
			// write delete target state information to event description
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($deleteTarget->compileStateDigest());	
			
			//
			// perform some type specific actions
			if ( $deleteTarget instanceof \RegularEntry ) {
				//
				// if we're deleting a regular entry, we need to recompute the overtime lapses
				$enforcementService->recomputeAllOvertimeLapses($deleteTargetUser);		
				
				// set event description
				$eventAction = "Deleted Time Entry";
			}
			else {
				// for project entries, we just set the event description
				$eventAction = "Deleted Project Entry";
			}

			// write event to audit trail
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									$eventAction,
									$eventDescription
								);
			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			//roll back
			$con->rollback();
			// rethrow
			throw new MomoException("TimetrackerController:deleteEntry() - an error has occurred while while attempting to an delete the Entry instance with id: " . $this->input->post('entryId'), 0, $ex);
		}

		// call display action for day we operated on
		redirect($this->getDisplayActionUrl($deleteTarget->getDay()->getDateofDay()));
	}
	
	
	/**
	 * Set a day's "off day" tag.
	 * 
	 * Days can be tagged as full, or half-day off days.
	 * A day with no such tag is considered a normal workday.
	 * 
	 * Setting these tags has implications for OOBookings that fall
	 * into the same week as the day tagged. Consequently, the oo manager
	 * is instructed to recalculate affected bookings.
	 * 
	 * 
	 * @param	integer		$dayId
	 * @param	integer		$offDayType		- indicates what type of off day tag to set
	 */
	public function setOffDayTag($dayId, $offDayType) {
			
		// authorize call
		$this->authorize(Permissions::TIMETRACKER_CREATE_ENTRY);
		
		// get refs to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$tagManager = $this->getCtx()->getTagManager();
		$ooManager = $this->getCtx()->getOOManager();
		
		// get display user
		$displayUser = $this->getDisplayUser();
		
		// proceed, if we have a valid display user
		if ( $displayUser !== null ) {
		
			// get a reference to the day in question
			$day = $workplanManager->getDayById($dayId);
			
			// get db connection (for TX operations, it is best practice to specify connection explicitly)
			$con = \Propel::getConnection(\TagPeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {	
			
				// first off, delete any existing off-day tag
				if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_DAY_OFF_FULL, $displayUser) ) {
					$tagManager->deleteDayTagByType($day, \Tag::TYPE_DAY_DAY_OFF_FULL, $displayUser);
				}
				else if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $displayUser) ) {
					$tagManager->deleteDayTagByType($day, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $displayUser);
				}
				else if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $displayUser) ) {
					$tagManager->deleteDayTagByType($day, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $displayUser);
				}
				
				// create off day tag as indicated
				if ( $offDayType == "full" ) {
					$tagManager->createDayTag($day, \Tag::TYPE_DAY_DAY_OFF_FULL, $displayUser);
				}
				else if ( $offDayType == "half-am" ) {
					$tagManager->createDayTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $displayUser);
				}
				else if ( $offDayType == "half-pm" ) {
					$tagManager->createDayTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $displayUser);
				}
			
				// recalculate affected oo bookings
				$ooManager->recalculateOOBookingsForDateRange($displayUser, $day->getDateOfDay(), $day->getDateOfDay());
				
				// commit TX
				$con->commit();
				
			}
			catch (\Exception $ex) {
			    //
				// oops, rollback TX
				$con->rollback();
				
				// write error to log and rethrow
				$errorMsg = "Timetracker:setOffDayTag() - an error has occurred while while attempting to set the day type for day with date: ";
				$errorMsg .= DateTimeHelper::formatDateTimeToPrettyDateFormat($day->getDateOfDay());
				$errorMsg .= ", for user: " . $displayUser->getFullName() . ".";
				
				throw new MomoException($errorMsg, 0, $ex);
			}
		
			// redisplay concerned day in timetracker
			redirect($this->getDisplayActionUrl($day->getDateofDay()));
		}
		else {
			// no valid display user, let timetracker default action figure it out
			redirect("/timetracker");
		}
		
	}
	
	
	/**
	 * Attempts to sets the current display user as indicated.
	 * 
	 * If an invalid user id (or no user id) is passed, the method will set the active user as the display user.
	 *
	 * @param integer	$displayUserId		- id of the desired display user
	 */
	private function setDisplayUser($displayUserId=null) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::TIMETRACKER_ACCESS_ALL_TIMETRACKERS,
										Permissions::TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER,
										Permissions::TIMETRACKER_ACCESS_OWN_TIMETRACKER
									)	
								);
		
		
		// get managers
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$securityService = $this->getCtx()->getSecurityService();
		
		// the active user
		$activeUser = $this->getCtx()->getUser();
		
		if ( $displayUserId == null ) {
			// as there is no active display user stored in the session, we default to active user
			$displayUserId = $this->getCtx()->getUser()->getId();
		}
		
		//
		// attempt to retrieve the indicated display user
		try {
			$displayUser = $userManager->getUserById($displayUserId);
		}
		catch ( UserNotFoundException $ex ) {
			//
			// if unable to find the indicated user, we default to the active user
			$displayUser = $activeUser;
		}
		
		//
		// authorize access to the indicated display user
		$displayUserAuthorized = false;
		
		// access to active session's own timetracker
		if ( $this->authorize(Permissions::TIMETRACKER_ACCESS_OWN_TIMETRACKER, true) ) {
			
			if ( $displayUser->getId() == $this->getCtx()->getUser()->getId() ) {
				$displayUserAuthorized = true;
			}
		}
		
		// access to timetrackers of users assigned to active session by means of team membership
		if ( $this->authorize(Permissions::TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER, true) ) {
			
			// get all teams led by active session
			$ledTeams = $teamManager->getAllTeamsLedByUser($activeUser);

			// from this, get all assigned users
			$assignedUsers = $userManager->getAllUsersMembersOfTeams($ledTeams);
			
			// the permission level allows access to users of lower roles than the active session
			// we reduce the permission level accordingly
			$nextLowerRoleMap = $securityService->getNextLowerRoleMap($activeUser->getRole());
			$allowedUsers = $userManager->reduceUserCollectionToMaximumRole($assignedUsers, key($nextLowerRoleMap));
			
			//
			// authorize the display user, if it is contained in the collection of allowed users
			if ( $allowedUsers->contains($displayUser) ) {
				$displayUserAuthorized = true;
			}
			
		}
		
		// this permission level always autorizes access
		if ( $this->authorize(Permissions::TIMETRACKER_ACCESS_ALL_TIMETRACKERS, true) ) {
			$displayUserAuthorized = true;
		}
		
		// set display user in accordance with authorization result
		if ( $displayUserAuthorized ) {
			// persist display user
			$this->session->set_userdata('timetrackercontroller.displayuserid', $displayUser->getId());
		}
		else {
			// invalidate display user 
			$this->session->set_userdata('timetrackercontroller.displayuserid', null);
		}
		
	}
	
	
	
	/**
	 * Retrieves the current display user from the session
	 * 
	 * @return	User (or null)
	 */
	private function getDisplayUser() {
		
		$displayUser = null;

		// get user manager
		$userManager = $this->getCtx()->getUserManager();
			
		//
		// if the session knows about a display user, retrieve it
		if ( $this->session->userdata('timetrackercontroller.displayuserid') !== false ) {		
			
			$displayUserId = $this->session->userdata('timetrackercontroller.displayuserid');
			
			//
			// attempt to retrieve the indicated display user
			try {
				$displayUser = $userManager->getUserById($displayUserId);
			}
			catch ( UserNotFoundException $ex ) {
				// NOOP
			}
		}
		
		return $displayUser;
	}

	
	/**
	 * Generates the display action url for the indicated DateTime
	 * 
	 * @param $dateTime		the DateTime for which to generate the display url
	 */
	private function getDisplayActionUrl($dateTime) {	
		
		// obtain day, month and year from DateTime
		$day = date("d", $dateTime->getTimestamp());
		$month = date("m", $dateTime->getTimestamp());
		$year = date("Y", $dateTime->getTimestamp());
		
		// call timetracker display action
		return "/timetracker/display/" . $day . "/" . $month . "/" . $year;
	}

	
	/**
	 * Indicates whether or not a given day is locked against edits for the indicated user
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function isDayLocked($day, $user) {
		
		$result = false;
		
		// get refs to managers needed
		$tagManager = $this->getCtx()->getTagManager();
				
		// retrieve timetracker lockout setting
		$lockoutDays = SettingsHelper::getKeyValue(Setting::KEY_TIMETRACKER_LOCKOUT_DAYS);

		// compute unlocked horizon
		$unlockedHorizon = DateTimeHelper::addDaysToDateTime( DateTimeHelper::getDateTimeForCurrentDate(), ( -1 * $lockoutDays) );
		
		if ( $day->getDateOfDay() <= $unlockedHorizon ) {
			
			$result = true;
			
			// date lies beyond unlocked horizon
			// -- see whether it is tagged as "unlocked"
			if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_UNLOCKED, $user) ) {
				$result = false;
			}

		}
		
		return $result;
			
	}
	
	
	/**
	 * Indicates whether or not a given day allows new regular entries for the indicated user.
	 * 
	 * Regular entries may be created, if:
	 * 
	 * 		- the day lies within the display user's employment range (i.e., within entry- and exit-date)
	 * 		- *and* the day does not lie in a future week
	 * 		- *and* the day is not locked
	 * 		- *and* the day is not marked as "full off"
	 * 		- *and* the day does not carry a OOEntry signifying a "full-day allotment" of type "paid, auto worktime credit", or "unpaid".
	 * 	      	    such an OOEntry is deemed "blocking", i.e. it blocks other entries from being made on the day
	 * 		- *and* the day is not marked as "half-day off" with an OOEntry signifying a "half-day allotment" of type "paid, auto worktime credit", or "unpaid"
	 *              such a combination is also deemed "blocking"
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function dayAllowsNewRegularEntries($day, $user) {
		
		$result = true;
		
		// check whether the indicated day fall's in user's employment period
		if ( ! $this->isDayInEmploymentPeriod($day, $user) ) {
			$result = false;
		}
		// check whether the indicated day lies in a future week
		else if ( $this->isDayInFutureWeek($day) ) {
			$result = false;
		}
		// check whether the indicated day is locked
		else if ( $this->isDayLocked($day, $user) ) {
			$result = false;
		}
		// check whether the indicated day is marked as "full day off"
		else if ( $this->isDayFullOff($day, $user) ) {
			$result = false;
		}
		// check whether the indicated day carries an oo booking that is deemed "blocking" (see header)
		else if ( $this->hasDayFullDayOOAllotmentOfBlockingType($day, $user) ) {
			$result = false;
		}
		// check whether the indicated day carries a half-day oo booking in combination with the other half marked off
		else if (  	$this->isDayHalfOff($day, $user)
			 	 && $this->hasDayHalfDayOOAllotmentOfBlockingType($day, $user) ) {
			$result = false;
		}
		
		return $result;
	}
	
	
	/**
	 * Indicates whether or not a given day allows the off day control to be displayed for the indicated user.
	 * 
	 * The control is displayed, if:
	 * 
	 * 		- the indicated user is of type "staff"
	 * 		- *and* the indicated user has a part-time workload
	 * 		- *and* the day is not locked
	 * 		- *and* the day is not a weekend day
	 * 		- *and* the day does not belong to a future week
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function dayAllowsOffDayControl($day, $user) {
		
		$result = true;
		
		// ensure that the user is of type staff
		if ( $user->getType() != \User::TYPE_STAFF ) {
			$result = false;
		}
		// ensure that the user has a part-time workload
		else if ( $user->getWorkload() == 1.0 ) {
			$result = false;
		}
		// ensure that the indicated day is not locked
		else if ( $this->isDayLocked($day, $user) ) {
			$result = false;
		}
		// ensure that we're not looking at a weekend day
		else if ( DateTimeHelper::getWeekDayNumberFromDateTime($day->getDateOfDay()) > 5 ) {
			$result = false;
		}
		// ensure that we're not looking at a future week
		else if ( $this->isDayInFutureWeek($day) ) {
			$result = false;
		}
		
		return $result;
	}
	
	
	/**
	 * Indicates whether or not a given day is within the indicted user's employment period
	 * 
	 * @param 	Day		$day
	 * 
	 * @return	boolean
	 */
	private function isDayInEmploymentPeriod($day, $user) {
		
		$result = false;
		
		if ( 	$day->getDateOfDay() >= $user->getEntryDate()
			 &&	$day->getDateOfDay() <= $user->getExitDate() ) {
			
				$result = true;
		}
		
		return $result;
	}
	
	
	/**
	 * Indicates whether a given day lies in a future week
	 * 
	 * @param 	Day		$day
	 * 
	 * @return	boolean
	 */
	private function isDayInFutureWeek($day) {
		
		// the week for the current date 
		$nowWeekMonday = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
		
		// the week for the day to test
		$testWeekMonday = DateTimeHelper::getStartOfWeek($day->getDateOfDay());
		
		return ( $nowWeekMonday < $testWeekMonday ) ? true : false;
	
	}
	
	
	/**
	 * Indicates whether or not a given day is marked as "full off" for the indicated user
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function isDayFullOff($day, $user) {
		
		// get refs to managers needed
		$tagManager = $this->getCtx()->getTagManager();
		
		return $tagManager->dayHasTag($day, \Tag::TYPE_DAY_DAY_OFF_FULL, $user);
		
	}
	
	
   /**
	* Indicates whether or not a given day is marked as "half-day off" for the indicated user
	* 
	* @param 	Day		$day
	* @param 	User	$user
	* 
	* @return	boolean
	*/
	private function isDayHalfOff($day, $user) {
		
		$result = false;
		
		// get refs to managers needed
		$tagManager = $this->getCtx()->getTagManager();
		
		if (    $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user)
			 || $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
		   	
			$result = true;
		}
		
		return $result;
		
	}
	
	
	/**
	 * Indicates whether or not a given day is assigned a full-day oo allotment of type "paid, autocredit" or "unpaid"
	 * for the indicated user - such an entry blocks other entries from being made.
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function hasDayFullDayOOAllotmentOfBlockingType($day, $user) {
		
		$result = false;
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		
		// query for allotment oo entry on the indicated day
		$ooEntry = $entryManager->getAllotmentOOEntryForDateAndUser($user, $day->getDateOfDay());
		
		if (   ($ooEntry !== null) 
			&& ($ooEntry->getType() == \OOEntry::TYPE_ALLOTMENT_FULL_DAY) ) {
			
			if (  	( ($ooEntry->getOOBooking()->getOOBookingType()->getPaid() == true) && ($ooEntry->getOOBooking()->getAutoAssignWorktimeCredit() == true) )	
				 ||	  ($ooEntry->getOOBooking()->getOOBookingType()->getPaid() == false) ) {
			
				$result = true;			
			}
	
		}
		
		return $result;
	}
	
	
	/**
	 * Indicates whether or not a given day is assigned a half-day oo allotment of type "paid, autocredit" or "unpaid"
	 * in combination with a "half-day off" tag for the indicated user - such a combination blocks other entries from being made
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	boolean
	 */
	private function hasDayHalfDayOOAllotmentOfBlockingType($day, $user) {
		
		$result = false;
		
		// get refs to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		
		// query for allotment oo entry on the indicated day
		$ooEntry = $entryManager->getAllotmentOOEntryForDateAndUser($user, $day->getDateOfDay());
		
		if (   ($ooEntry !== null)
			&& ( ($ooEntry->getType() == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM) || ($ooEntry->getType() == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM) ) ) {
		
		   if (  	( ($ooEntry->getOOBooking()->getOOBookingType()->getPaid() == true) && ($ooEntry->getOOBooking()->getAutoAssignWorktimeCredit() == true) )	
			 	||	  ($ooEntry->getOOBooking()->getOOBookingType()->getPaid() == false) ) {
		
				$result = true;			
			}
			   		
		}
		
		return $result;
	}
	
	
	/**
	 * Retrieves the off day tag for the indicated day and user
	 * 
	 * @param 	Day		$day
	 * @param 	User	$user
	 * 
	 * @return	string (or null)	
	 */
	private function getOffDayTag($day, $user) {
		
		$result = null;
		
		// get refs to managers needed
		$tagManager = $this->getCtx()->getTagManager();
		
		if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_DAY_OFF_FULL, $user) ) {
			$result = \Tag::TYPE_DAY_DAY_OFF_FULL;
		}
		else if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user) ) {
			$result = \Tag::TYPE_DAY_HALF_DAY_OFF_AM;
		}
		else if ( $tagManager->dayHasTag($day, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
			$result = \Tag::TYPE_DAY_HALF_DAY_OFF_PM;
		}
		
		return $result;
	}
	
}