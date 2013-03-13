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

namespace momo\enforcementservice;

use momo\core\helpers\DebugHelper;

use momo\core\helpers\DateTimeHelper;
use momo\core\security\Permissions;

use momo\core\exceptions\MomoException;
use momo\core\services\BaseService;

/**
 * EnforcementService
 * 
 * Responsible for all enforcement related application aspects.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class EnforcementService extends BaseService {
	
	// service key
	const SERVICE_KEY = "SERVICE_ENFORCEMENT";
	
	/**
	 * Unlocks the week that a given day belongs to.
	 * 
	 * Unlocking an incomplete week means to:
	 * 		- remove a possible "complete week" tag from the week
	 * 		- placing "unlocked" tags on all days within the week
	 *	
	 * @param  User		$user 
	 * @param  Day		$dayInWeek		- a day belonging to the week that is to be unlocked
	 *  
	 */
	public function unlockWeekForUser($user, $dayInWeek) {
		
		// get references to managers
		$tagManager = $this->getCtx()->getTagManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		
		// determine date of first day in week that the day belongs to
		$startOfWeekDate = DateTimeHelper::getStartOfWeek($dayInWeek->getDateOfDay());
		
		// get Day that represents the start of the week
		$startOfWeekDay = $workplanManager->getDayForDate($startOfWeekDate);
		
		// get relock timespan from settings
		$relockTimeSpanInDays = $settingsManager->getSettingValue(\Setting::KEY_TIMETRACKER_RELOCK_DAYS);
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TagPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// place "unlock" tags on all days in week
			$curUnlockDayDate = $startOfWeekDate;
			
			for ( $dayIndex = 1; $dayIndex <= 7; $dayIndex++ ) {
				
				// get a reference to current weekday
				$curUnlockDay = $workplanManager->getDayForDate($curUnlockDayDate);
				
				// tag day as "unlocked", with timeout as indicated by settings
				$tagManager->createDayTag(	$curUnlockDay,
											\Tag::TYPE_DAY_UNLOCKED,
											$user,
											DateTimeHelper::addDaysToDateTime(new \DateTime(), $relockTimeSpanInDays));
											
											
				// step current unlock day one day forward
				$curUnlockDayDate = DateTimeHelper::addDaysToDateTime($curUnlockDayDate, 1);
			}
			
			// commit TX
			$con->commit();	
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			$errorMsg = "EnforcementService:unlockWeekForUser() - a database error has occurred while while attempting to unlock the week starting on: ";
			$errorMsg .= DateTimeHelper::formatDateTimeToPrettyDateFormat($startOfWeekDate); 
			$errorMsg .= " for user: " . $user->getFullName();
			
			throw new MomoException($errorMsg, 0, $ex);
		}
		
	}
	
	
	/**
	 * Retrieves the mondays to weeks that are unlocked for the indicated user
	 * 
	 * @param 	User 	$user		
	 * 
	 * @return	PropelCollection	- a collection of Days (mondays) indicating weeks that are unlocked 
	 */
	public function getUnlockedWeekMondaysForUser($user) {
			
		// get managers
		$tagManager = $this->getCtx()->getTagManager();
		
		// retrieve all weeks which are tagged as "complete" and compile to an array
		// -- this tag is only ever applied to the monday of a given week
		$unlockedWeekDays = $tagManager->getAllDaysWithTag(\Tag::TYPE_DAY_UNLOCKED, $user);
		
		// retrieve all weeks (mondays) for the set of unlocked days
		return \DayQuery::create()
						->filterByWeekDayName("Mon")
						->filterById($unlockedWeekDays->toKeyValue("id", "id"))
						->find();								
	}
	
	
	/**
	 * Returns the overtime lapse date for the workplan of the indicated year.
	 * 
	 * Note: Due to the fact that part-timers routinely acquire overtime/undertime over the course of a work-week,
	 * 		 overtime must always lapse per the end of a full work-week.
	 * 
	 * 		 Accordingly, the lapse date specified in "settings" is interpreted as the date specifying the week
	 * 		 at the end of which overtime lapses. The actual AdjustmentEntry for the lapse is then applied to the
	 * 		 following monday.
	 *   	
	 * @param 	integer 	$workplanYear		
	 * 
	 * @return	DateTime 
	 */
	public function getOvertimeLapseDateForWorkplanYear($workplanYear) {
		
		// get manager
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// retrieve overtime lapse date components
		$lapseDay = $settingsManager->getSettingValue(\Setting::KEY_OVERTIME_LAPSE_DAY);
		$lapseMonth = $settingsManager->getSettingValue(\Setting::KEY_OVERTIME_LAPSE_MONTH);
		
		// compute datetime representation for indicated lapse date
		$indicatedLapseDate = DateTimeHelper::getDateTimeFromStandardDateFormat($lapseDay . "-" . $lapseMonth . "-" . $workplanYear);
		
		// the actual lapse date is given by the monday of the week following the the indicated lapse date
		$actualLapseDate = DateTimeHelper::getStartOfWeek($indicatedLapseDate);
		$actualLapseDate = DateTimeHelper::addDaysToDateTime($actualLapseDate, 7);
		
		return $actualLapseDate;
									
	}
	
	
	/**
	 * Recomputes all overtime lapses for the indicated user.
	 * 
	 * Note: Certain system state changes require the recomputation of users' overtime lapses.
	 * 		 An example would be a timetracker entry made for a date prior to an existing overtime lapse adjustment.
	 * 
	 * 		 As this can conceivably invalidate the affected lapse adjustment, said lapse adjustment needs
	 *  	 to be recomputed.
	 * 		 
	 * 		 The method effects this with a minimum of algorithmic complication by simply recomputing
	 * 		 the overtime lapses across all workplans.
	 *        
 	 * @param	User	$user 
	 */
	public function recomputeAllOvertimeLapses($user) {
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// global query for the user's overtime lapse adjustment entries
			$overtimeLapseAdjustmentEntries = $entryManager->getAdjustmentEntries(
																				$user,
																				\AdjustmentEntry::TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS,
																				null,
																				null
																			);
			
			// delete all overtime lapse adjustment entries
			$overtimeLapseAdjustmentEntries->delete();
			
			// as there are no more overtime lapse adjustment entries, all the lapses just deleted are now "pending lapses"
			// we can therefore recreate them by a call to "performPendingOvertimeLapsesForUser()"
			$this->performPendingOvertimeLapsesForUser($user);
			
			// commit TX
			$con->commit();		

		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("EnforcementService:recomputeAllOvertimeLapses() - a database error has occurred while while attempting to recompute overtime lapses for the user with login: " . $user->getLogin(), 0, $ex);
		}	
		
	}
	
	
	/**
	 * Calling the method will perform pending overtime lapses for the indicated user
	 * 
	 * The method returns a digest of the overtime lapse operations performed 
	 * 
	 * @param $user
	 * 
	 * @return array(
	 * 
	 * 				array(
	 * 					"lapseFromWorkplanYear"			=> {INTEGER},
	 * 					"lapseOnWorkplanYear"			=> {INTEGER},
	 * 					"overTimeSetToLapseInSeconds"	=> {INTEGER},
	 * 					"totalOvertimeInSeconds"		=> {INTEGER},
	 * 				)
	 * 
	 * 				...
	 * 
	 * 				array(
	 * 					"lapseFromWorkplanYear"			=> {INTEGER},
	 * 					"lapseOnWorkplanYear"			=> {INTEGER},
	 * 					"overTimeSetToLapseInSeconds"	=> {INTEGER},
	 * 					"totalOvertimeInSeconds"		=> {INTEGER},
	 * 				)
	 * 
	 * 			)
	 * 
	 */
	public function performPendingOvertimeLapsesForUser($user) {
		
		// operation digest
		$opDigest = array();
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// the current date
		$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
				
		// retrieve all existing workplans
		$workplans = $workplanManager->getAllPlans();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// examine each plan and see whether the user has any pending overtime lapses
			foreach ( $workplans as $curWorkplan ) {
				
				// do not process workplans that lie in future
				if ( DateTimeHelper::getYearFromDateTime($todayDate) >= $curWorkplan->getYear() ) {
					
					// figure out dates of first and last days of the current workplan
					$firstDayInCurWorkplanDate = $workplanManager->getFirstDayInPlan($curWorkplan)->getDateOfDay();
					$lastDayInCurWorkplanDate = $workplanManager->getLastDayInPlan($curWorkplan)->getDateOfDay();
					
					// query for the current workplan's overtime lapse entry
					$curWorkplanLapseAdjustmentEntries = $entryManager->getAdjustmentEntries(
																						$user,
																						\AdjustmentEntry::TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS,
																						$firstDayInCurWorkplanDate,
																						$lastDayInCurWorkplanDate
																					);																					
																					
					// get a reference to the "lapse from" workplan (the one immediately preceding the current one)
					$lapseFromWorkplan = $workplanManager->getPlanByYear($curWorkplan->getYear() - 1);																
					
					// if the current workplan has no vacation lapse entry and if there exists a "lapse from" workplan,
					// we possibly have a pending lapse in the current workplan
					if ( 	($curWorkplanLapseAdjustmentEntries->count() == 0)
						 && ($lapseFromWorkplan !== null) ) {

						// get lapse date for current workplan
						$curWorkplanOvertimeLapseDate = $this->getOvertimeLapseDateForWorkplanYear($curWorkplan->getYear());
						
						// perform lapse, provided the lapse date has been reached or passed and
						// the lapse date is contained in the user's employment period
						if ( 	($todayDate >= $curWorkplanOvertimeLapseDate)
							 &&	($user->getEntryDate() <= $curWorkplanOvertimeLapseDate)
							 && ($user->getExitDate() >= $curWorkplanOvertimeLapseDate) ) {
							 	
							 			 	
							// lapse digest
							$curLapseOperationDigest = array();
								
							// compute the overtime set to lapse
							$overtimeToLapseDigest = $this->computeOvertimeHoursSetToLapseForUserAndDate($user, $curWorkplanOvertimeLapseDate);
							
							// extract the overtime set to lapse
							$lapseValueInSec = $overtimeToLapseDigest["overTimeSetToLapseInSeconds"];
			
							// lapse the indicated overtime by means of an adjustment entry
							$entryManager->createAdjustmentEntry(
															$curWorkplanOvertimeLapseDate,
															$user,
															\AdjustmentEntry::TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS,
															\AdjustmentEntry::CREATOR_SYSTEM,
															(-1 * $lapseValueInSec),
															"Overtime lapse for overtime carried over from " . $lapseFromWorkplan->getYear()
														);
					
							// populate the lapse digest
							$curLapseOperationDigest["lapseFromWorkplanYear"] = $lapseFromWorkplan->getYear();					
							$curLapseOperationDigest["lapseOnWorkplanYear"] = $curWorkplan->getYear();
							$curLapseOperationDigest["lapseDate"] = $curWorkplanOvertimeLapseDate;
							$curLapseOperationDigest["lapseValueInSeconds"] = $overtimeToLapseDigest["overTimeSetToLapseInSeconds"];
							$curLapseOperationDigest["totalOvertimeInSeconds"] = $overtimeToLapseDigest["totalOvertimeInSeconds"];
							
							// add result to overall operation digest
							$opDigest[] = $curLapseOperationDigest;
						}
						
					}
																																					
				}
	
			}
			
			// commit TX
			$con->commit();		

		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			$errorMsg = "EnforcementService:performPendingOvertimeLapsesForUser() - a database error has occurred while while attempting to lapse overtime entry for user with login: " . $user->getLogin();
			$errorMsg = ", in workplan: " . $curWorkplan->getYear();
		
			throw new MomoException($errorMsg);
		}	
		
		return $opDigest;
		
	}
	
	
	/**
	 * Given a user and a point in time, the method computes the user's overtime hours subject to expiration at
	 * the indicated time.
	 * 
	 * Note: 	Performing this calculation is subject to the following considerations:
	 *  
	 *  	 		- If the current date lies within the "lapse from" workplan, the number of hours in danger of
	 *  	   	  	  lapsing is given by the overtime accrued up to the current date.
	 *  	
	 * 		 		- If the current date lies within the "lapse on" workplan, the number of hours in danger of lapsing
	 * 		   	  	  are given by the number of overtime hours accrued at the end of the final week in the "lapse from"
	 * 		   	  	  workplan plus the signed undertime accrued up to the current point in time.
	 * 
	 * 			As outlined in the documentation block for "getOvertimeLapseDateForWorkplanYear()", it is important to
	 * 			work on week-boundaries in the context of these calculations/operations.
	 * 
	 * 			This method makes for this by evaluating the overtime set to lapse relative to the nearest elapsed week.
	 * 		
	 * @param 	User 		$user					- the user to consider
	 * @param 	DateTime	$pointInTime			- the point in time at which we want to evaluate the overtime set to lapse
	 * 
	 * @return	array (
	 * 					"relativeToDate"				=> {DATE VALUE}			// a DateTime value indicating the nearest elapsed week that the value applies to		
	 * 					"overTimeSetToLapseInSeconds"	=> {INTEGER VALUE}		// the sought value, expressed in seconds (value is >= 0)
	 * 				  )
	 */
	public function computeOvertimeHoursSetToLapseForUserAndDate($user, $pointInTime) {
		
		$result = array();
		
		// get managers/services
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$compService = $this->getCtx()->getComputationService();
		
		// examine the indicated point in time, if it happens to be a sunday, we can evaluate the overtime
		// to expire relative to it. if the indicated point in time falls on a weekday, we need to evaluate
		// relative to the prior sunday.
		$evaluateOvertimeRelativeToDate = $pointInTime;
		if ( DateTimeHelper::getWeekDayNumberFromDateTime($evaluateOvertimeRelativeToDate) != 7 ) {
			$tmpDate = DateTimeHelper::addDaysToDateTime($evaluateOvertimeRelativeToDate, -7);
			$evaluateOvertimeRelativeToDate = DateTimeHelper::getEndOfWeek($tmpDate);
		}

		//
		// determine which lapse date applies for the evaluation time point
		
		// retrieve the lapse date that lies in the same year as the evaluation time point
		$nextOvertimeLapseDate = $this->getOvertimeLapseDateForWorkplanYear(DateTimeHelper::getYearFromDateTime($evaluateOvertimeRelativeToDate));
		
		// the next year's lapse date applies if the evaluation time point lies beyond the lapse date of the same year
		if ( $evaluateOvertimeRelativeToDate > $nextOvertimeLapseDate ) {
			$nextOvertimeLapseDate = $this->getOvertimeLapseDateForWorkplanYear(DateTimeHelper::getYearFromDateTime($evaluateOvertimeRelativeToDate) + 1);
		}

		//
		// determine the workplans we are lapsing "from" and "on"
		//
		// 	- where "lapse from workplan" is the workplan in which the overtime set to expire was accrued
		// 	- where "lapse on workplan" is the workplan in which the overtime from the "lapse from workplan" actually is made
		//	  to expire (by means on an AdjustmentEntry).
		$lapseFromWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($nextOvertimeLapseDate) - 1);
		$lapseOnWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($nextOvertimeLapseDate));
		
		// the final amount to roll over from the "lapse from" is evaluated at the end of the week containing the 31st of december
		// i.e., overtime accretion from the  "lapse from workplan" runs up to the end of the week containing "december 31st".
		$lapseFromWorkplanLastWeekSundayDate = DateTimeHelper::getEndOfWeek($workplanManager->getLastDayInPlan($lapseFromWorkplan)->getDateOfDay());
		
		//
		// compute the number of overtime hours in danger of expiry
		//	
		// - if the evaluation time point lies before, or on, the end of overtime accretion, the overtime in danger of expiry
		//	 is simply given by the overtime that has accumulated up to the evaluation time point
		//
		// - if the evaluation time point lies beyond the end of overtime accretion, the overtime in danger of expiry is
		//	 given by the sum of the overtime accrued in the "lapse from workplan" and the (signed) undertime accrued in
		//	 the "lapse on workplan" - where the latter value relative to the evaluation time point
		
		if ( $evaluateOvertimeRelativeToDate <= $lapseFromWorkplanLastWeekSundayDate ) {
			
			// the evaluation time point lies before, or on, the end of overtime accretion
			// hence, the overtime subject to expiry is given simply by the overtime at the evaluation time point
			
			$totalWorkTimeCreditAtCurrentTimePointInSec 		=     $compService->computeTotalWorktimeCreditForUser($user, $user->getEntryDate(), $evaluateOvertimeRelativeToDate)
																	+ $compService->computeWorktimeAdjustmentsForUser($user, $user->getEntryDate(), $evaluateOvertimeRelativeToDate);
						
			$totalPlanTimeAtCurrentTimePointInSecInSec 			= $compService->computePlanTimeForUser($user, $user->getEntryDate(), $evaluateOvertimeRelativeToDate);
			
			
			// compute the total overtime
			$totalOvertimeInSeconds = $totalWorkTimeCreditAtCurrentTimePointInSec - $totalPlanTimeAtCurrentTimePointInSecInSec;
			
			// the overtime set to expire is any non-negative time delta and zero otherwise
			$overtimeToExpireInSec = max(0, $totalOvertimeInSeconds);
			
			// users may carry over 8 hours or less of overtime and we limit the expiry amount accordingly
			
			// TODO: get the carry over value from system parametrization
			if ( $overtimeToExpireInSec <= (8 * 3600) ) {
				// overtime that is to expire is within carry over allowance, hence
				$overtimeToExpireInSec = 0;
			}
			else {
				$overtimeToExpireInSec = $overtimeToExpireInSec - (8 * 3600);
			}
			
		}
		else {
		
			// the evaluation time point lies beyond the end of overtime accretion
				
			// to determine the sought figure, we first compute the time delta accreted in the "lapse from workplan"
			$totalWorkTimeCreditInLapseFromWorkplanInSec 		=     $compService->computeTotalWorktimeCreditForUser($user, $user->getEntryDate(), $lapseFromWorkplanLastWeekSundayDate)
																	+ $compService->computeWorktimeAdjustmentsForUser($user, $user->getEntryDate(), $lapseFromWorkplanLastWeekSundayDate);
						
			$totalPlanTimeInLapseFromWorkplanInSec 				= $compService->computePlanTimeForUser($user, $user->getEntryDate(), $lapseFromWorkplanLastWeekSundayDate);
			
			// compute the total overtime accreted in the "lapse from workplan"		
			$totalOvertimeInSeconds = $totalWorkTimeCreditInLapseFromWorkplanInSec - $totalPlanTimeInLapseFromWorkplanInSec;
			
			//
			// compute the time delta accrued in "lapse on" workplan (up to the evaluation time point)
	
			// the computation commences on the monday after the end of ovetime accretion in the "lapse from workplan"
			$mondayAfterFromWorkplanLastWeekSundayDate = DateTimeHelper::addDaysToDateTime($lapseFromWorkplanLastWeekSundayDate, 1);

			// we seek to compute the overtime between the end of overtime accretion and the evaluation time point.
			// we perform the computation, provided this is a sensical time range. boundary conditions can cause nonsensical
			// time ranges here, in which case the time delta sought is simply zero.
			$lapseOnWorkplanTimeDeltaInSec = 0;
			if ( $mondayAfterFromWorkplanLastWeekSundayDate < $evaluateOvertimeRelativeToDate ) {
				
				$totalWorkTimeCreditInLapseOnWorkplanInSec 		= 	  $compService->computeTotalWorktimeCreditForUser($user, $mondayAfterFromWorkplanLastWeekSundayDate, $evaluateOvertimeRelativeToDate)
																	+ $compService->computeWorktimeAdjustmentsForUser($user, $mondayAfterFromWorkplanLastWeekSundayDate, $evaluateOvertimeRelativeToDate);
						
				$totalPlanTimeInLapseOnWorkplanInSec 			= $compService->computePlanTimeForUser($user, $mondayAfterFromWorkplanLastWeekSundayDate, $evaluateOvertimeRelativeToDate);
				
				// compute the time delta accrued in the "lapse on" workplan
				$lapseOnWorkplanTimeDeltaInSec = $totalWorkTimeCreditInLapseOnWorkplanInSec - $totalPlanTimeInLapseOnWorkplanInSec;
			}
			
				
			//
			// with time deltas in hand, compute the overtime in danger of expiry.
			
			// at its most basic, this is simply the *overtime* accrued in the "lapse from workplan"
			// summed with whatever *undertime* has accrued in the "lapse on" workplan
			$overtimeToExpireInSec = max(0, $totalOvertimeInSeconds) + min(0, $lapseOnWorkplanTimeDeltaInSec);
				
			// if we've accrued undertime in the "lapse on workplan" that is in excess of overtime accrued in the "lapse on workplan"
			// the computed value can turn out negative. in this case, the overtime subject to expiry is simply zero.
			$overtimeToExpireInSec = max(0, $overtimeToExpireInSec);	
			
			// users may carry over 8 hours or less of overtime.
			// we limit the expiry amount accordingly
			
			// TODO: get the carry over value from system parametrization
			if ( $overtimeToExpireInSec <= (8 * 3600) ) {
				// overtime that is to expire is within carry over allowance, hence
				$overtimeToExpireInSec = 0;
			}
			else {
				$overtimeToExpireInSec = $overtimeToExpireInSec - (8 * 3600);
			}
	
		}
				
		// pack data points into result array
		$result["pertainsToDate"] = $evaluateOvertimeRelativeToDate;
		$result["overTimeSetToLapseInSeconds"] = $overtimeToExpireInSec;
		$result["totalOvertimeInSeconds"] = $totalOvertimeInSeconds;
		
		return $result;												
	}
	
	
	/**
	 * Returns the vacation lapse date for the workplan of the indicated year
	 * 
	 * @param 	integer 	$workplanYear		
	 * 
	 * @return	DateTime 
	 */
	public function getVacationLapseDateForWorkplanYear($workplanYear) {
		
		// get manager
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// retrieve overtime lapse date components
		$lapseDay = $settingsManager->getSettingValue(\Setting::KEY_VACATION_LAPSE_DAY);
		$lapseMonth = $settingsManager->getSettingValue(\Setting::KEY_VACATION_LAPSE_MONTH);
		
		// the indicated year's overtime lapse date
		return DateTimeHelper::getDateTimeFromStandardDateFormat($lapseDay . "-" . $lapseMonth . "-" . $workplanYear);
									
	}
	
	
	/**
	 * Recomputes all vacation lapses for the indicated user.
	 * 
	 * Note: Certain system state changes require the recomputation of users' vacation lapses.
	 * 
	 * 		 An example would be an oo booking made for a date prior to an existing vacation lapse adjustment.
	 *
	 * 		 As this can conceivably invalidate the affected lapse adjustment, said lapse adjustment needs
	 *  	 to be recomputed.
	 * 		 
	 * 		 The method effects this with a minimum of algorithmic complication by simply recomputing
	 * 		 the vacation lapses across all workplans.
	 *        
 	 * @param	User	$user 
	 */
	public function recomputeAllVacationLapses($user) {
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// global query for the user's vacation lapse adjustment entries
			$vacationLapseAdjustmentEntries = $entryManager->getAdjustmentEntries(
																				$user,
																				\AdjustmentEntry::TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS,
																				null,
																				null
																			);
			
			// delete all vacation lapse adjustment entries
			$vacationLapseAdjustmentEntries->delete();
			
			// as there are no more vacation lapse adjustment entries, all the lapses just deleted are now "pending lapses"
			// we can therefore recreate them by a call to "performPendingVacationLapsesForUser()"
			$this->performPendingVacationLapsesForUser($user);
			
			// commit TX
			$con->commit();		

		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("EnforcementService:recomputeAllVacationLapses() - a database error has occurred while while attempting to recompute vacation lapses for the user with login: " . $user->getLogin());
		}							
	}
	
	
	/**
	 * Calling the method will perform pending vacation lapses for the indicated user
	 * 
	 * The method returns a digest of the vacation lapse operations performed 
	 * 
	 * @param $user
	 * 
	 * @return array(
	 * 
	 * 				array(
	 * 					"lapseFromWorkplanYear"			=> {INTEGER},
	 * 					"lapseOnWorkplanYear"			=> {INTEGER},
	 * 					"lapseDate"						=> {DATETIME},
	 * 					"vacationDaysLapsedInDays"		=> {FLOAT}
	 * 				)
	 * 
	 * 				...
	 * 
	 * 				array(
	 * 					"lapseFromWorkplanYear"			=> {INTEGER},
	 * 					"lapseOnWorkplanYear"			=> {INTEGER},
	 * 					"lapseDate"						=> {DATETIME},
	 * 					"vacationDaysLapsedInDays"		=> {FLOAT}
	 * 				)
	 * 
	 * 			)
	 * 
	 */
	public function performPendingVacationLapsesForUser($user) {
		
		// operation digest
		$opDigest = array();
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// the current date
		$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
				
		// retrieve all existing workplans
		$workplans = $workplanManager->getAllPlans();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// examine each plan and see whether the user has any pending vacation lapses
			foreach ( $workplans as $curWorkplan ) {
				
				// do not process workplans that lie in future
				if ( DateTimeHelper::getYearFromDateTime($todayDate) >= $curWorkplan->getYear() ) {
					
					// figure out dates of first and last days of the current workplan
					$firstDayInCurWorkplanDate = $workplanManager->getFirstDayInPlan($curWorkplan)->getDateOfDay();
					$lastDayInCurWorkplanDate = $workplanManager->getLastDayInPlan($curWorkplan)->getDateOfDay();
					
					// query for the current workplan's vacation lapse entry
					$curWorkplanLapseAdjustmentEntries = $entryManager->getAdjustmentEntries(
																						$user,
																						\AdjustmentEntry::TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS,
																						$firstDayInCurWorkplanDate,
																						$lastDayInCurWorkplanDate
																					);																					
																					
					// get a reference to the "lapse from" workplan (the one immediately preceding the current one)
					$lapseFromWorkplan = $workplanManager->getPlanByYear($curWorkplan->getYear() - 1);																
					
					// if the current workplan has no vacation lapse entry and if there exists a "lapse from" workplan,
					// we possibly have a pending lapse in the current workplan
					if ( 	($curWorkplanLapseAdjustmentEntries->count() == 0)
						 && ($lapseFromWorkplan !== null) ) {

						// get lapse date for current workplan
						$curWorkplanVacationLapseDate = $this->getVacationLapseDateForWorkplanYear($curWorkplan->getYear());
						
						// perform lapse, provided the lapse date has been reached or passed and
						// the lapse date is contained in the user's employment period
						if ( 	($todayDate >= $curWorkplanVacationLapseDate)
							 &&	($user->getEntryDate() <= $curWorkplanVacationLapseDate)
							 && ($user->getExitDate() >= $curWorkplanVacationLapseDate) ) {
							 	
							 			 	
							// lapse digest
							$curLapseOperationDigest = array();
								
							// compute the number of days set to lapse
							$lapseValueInDays = $this->computeVacationDaysSetToLapseForForUserAndDate($user, $curWorkplanVacationLapseDate);

							// lapse the indicated vacation days by means of an adjustment entry
							$entryManager->createAdjustmentEntry(
															$curWorkplanVacationLapseDate,
															$user,
															\AdjustmentEntry::TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS,
															\AdjustmentEntry::CREATOR_SYSTEM,
															(-1 * $lapseValueInDays),
															"Vacation lapse for vacation carried over from " . $lapseFromWorkplan->getYear()
														);
					
							// populate the lapse digest
							$curLapseOperationDigest["lapseFromWorkplanYear"] = $lapseFromWorkplan->getYear();					
							$curLapseOperationDigest["lapseOnWorkplanYear"] = $curWorkplan->getYear();
							$curLapseOperationDigest["lapseDate"] = $curWorkplanVacationLapseDate;
							$curLapseOperationDigest["lapseValueInDays"] = $lapseValueInDays;
							
							// add result to overall operation digest
							$opDigest[] = $curLapseOperationDigest;
						}
						
					}
																																					
				}
	
			}
			
			// commit TX
			$con->commit();		

		}
		catch (\PropelException $ex) {
			// rollback TX
			$con->rollback();
			// rethrow
			$errorMsg = "EnforcementService:performPendingVacationLapsesForUser() - a database error has occurred while while attempting to lapse vacation days for user with login: " . $user->getLogin();
			$errorMsg = ", in workplan: " . $curWorkplan->getYear();
			
			throw new MomoException($errorMsg);
		}	
		
		return $opDigest;
	}
	
	
	/**
	 * Given a user and a point in time, the method computes the user's vacation days subject to expiration at
	 * the indicated time.
	 * 
	 * Note: 	Performing this calculation is subject to the following considerations:
	 *  
	 *  	 		- If the current date lies within the "lapse from" workplan, the number of vacation days in danger of
	 *  	   	  	  lapsing is given by the overtime accrued up to the current date.
	 *  	
	 * 		 		- If the current date lies within the "lapse on" workplan, the number of hours in danger of lapsing
	 * 		   	  	  are given by the number of overtime hours accrued at the end of the final week in the "lapse from"
	 * 		   	  	  workplan plus the signed undertime accrued up to the current point in time.
	 * 
	 * 			As outlined in the documentation block for "getOvertimeLapseDateForWorkplanYear()", it is important to
	 * 			work on week-boundaries in the context of these calculations/operations.
	 * 
	 * 			This method makes for this by evaluating the overtime set to lapse relative to the nearest elapsed week.
	 * 		
	 * @param 	User 		$user					- the user to consider
	 * @param 	DateTime	$pointInTime			- the point in time at which we want to evaluate the vacation days set to lapse
	 * 
	 * @return	float		- the number of days set to lapse
	 */
	public function computeVacationDaysSetToLapseForForUserAndDate($user, $pointInTime) {
		
		$vacationDaysToExpire = 0;
		
		// get managers/services
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$compService = $this->getCtx()->getComputationService();
			
		//
		// determine which lapse date applies for the evaluation time point
		
		// retrieve the lapse date that lies in the same year as the evaluation time point
		$nextVacationLapseDate = $this->getVacationLapseDateForWorkplanYear(DateTimeHelper::getYearFromDateTime($pointInTime));
		
		// the next year's lapse date applies if the evaluation time point lies beyond the lapse date of the same year
		if ( $pointInTime > $nextVacationLapseDate ) {
			$nextVacationLapseDate = $this->getVacationLapseDateForWorkplanYear(DateTimeHelper::getYearFromDateTime($pointInTime) + 1);
		}
		
		//
		// determine the workplans we are lapsing "from" and "on"
		//
		// 	- where "lapse from workplan" is the workplan in which the vacation set to expire was accrued
		// 	- where "lapse on workplan" is the workplan in which the vacation from the "lapse from workplan" actually is made
		//	  to expire (by means on an AdjustmentEntry).
		$lapseFromWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($nextVacationLapseDate) - 1);
		$lapseOnWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($nextVacationLapseDate));
		
		// get date of last day in workplan we're rolling over from
		$lastDayInLapseFromWorkplanDate = $workplanManager->getLastDayInPlan($lapseFromWorkplan)->getDateOfDay();
		
		// obtain vacation data evaluated at last day in workplan that we're lapsing from
		$lapseFromWorkplanVacationDigest = $compService->computeVacationStatisticsDigestForUser($user, $lastDayInLapseFromWorkplanDate);
					
		// get vacation data up to, but not including, the lapse date
		$lapseOnWorkplanVacationDigest = $compService->computeVacationStatisticsDigestForUser($user, DateTimeHelper::addDaysToDateTime($nextVacationLapseDate, -1));

		// retrieve the vacation days consumed in the "lapse on" workplan that need to be applied to the vacation day balance carried
		// over from "lapse from" workplan
		$vacationDaysConsumedInLapseOnWorkplan = $lapseOnWorkplanVacationDigest["vacation_days_consumed_by_workplan"][$lapseOnWorkplan->getYear()];
		
		//
		// compute the number of vaction days in danger of expiring
		
		// we compute this in two steps
		//
		// first, we compute the number of days in the "lapse from" workplan that are in danger of expiry
		//
		// - this figure is given by the vacation credit, minus the days consumed, plus the vacation adjustments in the "lapse from" workplan,
		//   with all these figures evaluated on the last day in the "lapse from" workplan.
		//
		// - if we're presently in the "lapse on" workplan, we then need to subtract whatever amount of vacation is booked/consumed in the
		//   "lapse on" workplan - where we only consider bookings up to and (not including) the lapse date
		//
		// - if the computed value is negative, all carried over vacation credit has been consumed
		//   and the number of days in danger of lapsing is zero.
		
		// compute the number of days in the "lapse from" workplan that are in danger of expiry
		$vacationDaysToExpire = 	$lapseFromWorkplanVacationDigest["aggregate_results"]["global_vacation_days_credit"]
								  - $lapseFromWorkplanVacationDigest["aggregate_results"]["global_vacation_days_consumed"]
								  + $lapseFromWorkplanVacationDigest["aggregate_results"]["global_vacation_days_adjustment"];
								  

		// subtract vacation days consumed in "lapse on" workplan and restrict minimal value to zero
		
		// note that "$lapseOnWorkplanVacationDigest" is evaluated at the vacation lapse date. hence, if the present time point lies before that date,
		// "$vacationDaysConsumedInLapseOnWorkplan" amounts to a lookahead, with the figure obtained reflecting the vacation days that will be consumed
		// in the "lapse on" workplan at the "lapse date" provided oo bookings in the "lapse on" workplan that lie between "jan 1st" and the "lapse date"
		// do not change.
		$vacationDaysToExpire = max(0, ( $vacationDaysToExpire - $vacationDaysConsumedInLapseOnWorkplan) );						  
								  
		return $vacationDaysToExpire;
														
	}
	
	
	/**
	 * Retrieves the incomplete weeks for the indicated user
	 * 
	 * Incomplete weeks are all those which do not have a monday that carries the tag "Tag::TYPE_WEEK_COMPLETE".
	 * Note: this tag, as all tags with week scope, is always placed on the monday of a given week
	 * 
	 * @param 	User 	$user		
	 * 
	 * @return	PropelCollection	- a collection of Days (mondays) marked incomplete 
	 */
	public function getIncompleteWeeksForUser($user) {
			
		// get managers
		$settingsManager = $this->getCtx()->getSettingsManager();
		$tagManager = $this->getCtx()->getTagManager();
		
		// retrieve all weeks which are tagged as "complete" and compile to an array
		// -- this tag is only ever applied to the monday of a given week
		$completeWeekDays = $tagManager->getAllDaysWithTag(\Tag::TYPE_WEEK_COMPLETE, $user);
		$completeWeekDates = array();
		foreach ( $completeWeekDays as $curCompleteWeekDay ) {
			$completeWeekDates[] = $curCompleteWeekDay->getDateOfDay();
		}
		
		// retrieve application use start date
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		//
		// figure out weeks considered for this operation
		//
		// start of range is given by:
		// 	- the date of first week of application, or the start of the user's employment period, whichever is larger
		// 
		// the end of the range is given by:
		//  - the date one week prior to present day's week, or the end date of the user's employment period, whichever is smaller
		//
		// note: the computation can result in $rangeEndDate < $rangeStartDate, this is intended behavior (will occur only in first weeks of app use)
		//
		$appUseStartWeekDate = DateTimeHelper::getStartOfWeek($appUseStartDate);
		$userEntryWeekDate = DateTimeHelper::getStartOfWeek($user->getEntryDate());
		$rangeStartDate = $appUseStartWeekDate > $userEntryWeekDate ? $appUseStartWeekDate : $userEntryWeekDate;
		
		$oneWeekPriorToTodayWeekDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::addDaysToDateTime(DateTimeHelper::getDateTimeForCurrentDate(), -7));
		$userExitWeekDate = DateTimeHelper::getStartOfWeek($user->getExitDate());
		$rangeEndDate = $oneWeekPriorToTodayWeekDate < $userExitWeekDate ? $oneWeekPriorToTodayWeekDate : $userExitWeekDate;
	
		// retrieve all weeks (mondays) that lie in determined range and that are no marked as "complete"
		// -- these indicate the "incomplete" weeks
		return \DayQuery::create()
						->filterByWeekDayName("Mon")
						->filterByDateOfDay($rangeStartDate, \DayQuery::GREATER_EQUAL)
						->filterByDateOfDay($rangeEndDate, \DayQuery::LESS_EQUAL)
						->filterByDateOfDay($completeWeekDates, \DayQuery::NOT_IN)				
						->find();
	}
	
	
	/**
	 * Detects and marks incomplete weeks for all active users
	 * 
 	 * User records are processed as follows:
 	 * 
 	 * 		- for all users of type "staff":
 	 * 
 	 * 			- a week is a candidate for being flagged as incomplete, if we detect a workday within that:
 	 * 
	 *						has no regular entry
	 *				*and*   is not marked as a "full day" off day
	 *				*and*   has no "full day" oo entry of type "paid, autocredit"
	 *				*and*   has no "full day" oo entry of type "unpaid"
	 *				*and*   is not a "full day" holiday
	 *
	 *			- if the user works a part-time workload:
	 *
	 * 				- days identified as problematic from above are examined for completened based on a combination
	 * 			  	  of half-day attributes:
	 * 
	 * 			  		- if the day is marked as a "half day (am/pm)" off day and has:
	 * 
	 * 								a (pm/am) holiday								
	 *						*or* 	a (pm/am) "paid, autocredit" oo entry						
	 *						*or*	a (pm/am) "unpaid" oo entry
	 *
	 *						...it is is deemed complete
	 *
	 *					- if the day is a "half-day" holiday (these always occur in the pm), and has:
	 *
	 *						 		an (am) paid, autocredit oo entry
	 *						*or*	an (am) unpaid oo entry
	 *
	 *						...it is is deemed complete			  
	 * 
	 * 				  if there are days left over that do not pass muster the week is considered incomplete.
	 * 
 	 * 		- in addition, for part-time users of type "staff"
 	 * 
 	 * 			- a week is flagged incomplete, if the number of days marked "off" exceeds the number
 	 * 			  of off days allowed as per the user's workload. (This restriction is in place so as to force
 	 * 			  users to make a Timetracker entry when compensating overtime.)
	 *  
 	 * 		- for all users of type "student":
 	 * 
 	 * 			- student employees are not subject to incomplete week detection
 	 * 
 	 * 
 	 * Note: fractional work weeks that arise from an employee starting or ending work in the middle of a workweek
 	 * 		 (entry and exit dates in user profile) are presently not subject to incomplete week detection.
 	 * 
	 * 	
	 * @return array 	- an associative array mapping user id's to arrays containing the Day instances identifying weeks deemed incomplete
	 * 					  note: a given week id identified/referenced by the date/Day corresponding to the monday of said week.
	 * 
	 * 					  the return result is structured like so:
	 * 					
	 * 					  array(
	 * 								user_id_1	=> array(
	 * 													Day_1,
	 * 													...,
	 * 													Day_n
	 * 												 )
	 * 
	 * 								...
	 * 
	 * 					  )
	 */
	public function detectIncompleteWeeksForAllActiveUsers() {
		
		// will hold weeks determined incomplete
		$result = array();
		
		// get managers
		$userManager = $this->getCtx()->getUserManager();
		$tagManager = $this->getCtx()->getTagManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// retrieve all enabled users
		$users = $userManager->getAllEnabledUsers();
		
		foreach ( $users as $curUser ) {
			
			// we store the current users result here
			$curUserResult = array();
			
			// process only staff employees as students are not subject to incomplete week detection
			if ( $curUser->getType() == \User::TYPE_STAFF ) {
				
				// retrieve all incomplete weeks for the current user
				$incompleteWeeks = $this->getIncompleteWeeksForUser($curUser);
				
				// pre-process part time staff
				if ( $curUser->getWorkload() < 1 ) {
					
					// determine the max number of off days for user
					// -- we round() prior to int cast to get around approximation values that arise due to limitations in base-2 representation of certain decimal fractions
					//    e.g. 0.2 base-10 comes out as  0.00111... base-2, which is 0.19999... base-10, which will cause issues when compared to canonical value of 0.2, for example.
					//    this also is why we're basing the off-day related calculations on half-day values
					$maxOffHalfDays = (int) round(2 * 5 * (1 - $curUser->getWorkload()));	
					
					//
					// check each incomplete week for adherence to maximum off day limit
					foreach ( $incompleteWeeks as $curInCompleteWeek ) {
						
						// skip weeks not fully contained in employment period
						if ( $this->isWeekFullyContainedInUserEmploymentPeriod($curInCompleteWeek, $curUser) ) {
							//
							// query for full off days in current week
							$fullOffDays = \DayQuery::create()
												->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
												->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
												->useTagQuery()
													->filterByType(\Tag::TYPE_DAY_DAY_OFF_FULL)
													->filterByUser($curUser)
												->endUse()			
												->find();
											
							// query for half off days in current week			
							$halfOffDays = \DayQuery::create()
												->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
												->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
												->useTagQuery()
													->filterByType(\Tag::TYPE_DAY_HALF_DAY_OFF_AM)
													->_or()
													->filterByType(\Tag::TYPE_DAY_HALF_DAY_OFF_PM)
													->filterByUser($curUser)
												->endUse()			
												->find();				
											
							// compute off day count for current week
							$curWeekOffHalfDayCount = (2 * $fullOffDays->count() + $halfOffDays->count());		
		
							// if off day count exceeds maxoffdays, we add the week to the array of incomplete weeks
							// note: we use the array as a hashmap: day_id -> day object
							if ( $curWeekOffHalfDayCount > $maxOffHalfDays ) {
								$curUserResult[$curInCompleteWeek->getId()] = $curInCompleteWeek;
							}		
						}						
					}
				}
				
				//
				// from here on general completeness checking for staff users occurs
				// see earlier for a detailed description of same
				foreach ( $incompleteWeeks as $curInCompleteWeek ) {
						
					// skip weeks not fully contained in employment period
					if ( $this->isWeekFullyContainedInUserEmploymentPeriod($curInCompleteWeek, $curUser) ) {
					
						//
						// determine days in current week that:
						
						//	 - are not marked as "full-day" off
						//	 - that do not carry regular entry
						// 	 - that do not carry a full-day paid oo booking entry of type "automatic worktime credit"
						// 	 - that do not carry a full-day unpaid oo-booking entry
						
						//
						// first off, query for full off days in current week
						// -- days marked as full off will not be tested for completeness
						$fullOffDays = \DayQuery::create()
											->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
											->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
											->useTagQuery()
												->filterByType(\Tag::TYPE_DAY_DAY_OFF_FULL)
												->filterByUser($curUser)
											->endUse()	
											->find();
						
						// query for the days that carry regular entries, i.e. have been explicitly completed by the user
						$regularEntryDays = \DayQuery::create()
													->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
													->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
													->useRegularEntryQuery()
														->filterByUser($curUser)
													->endUse()
													->distinct()
													->find();						
	
						// query for the days in the current week that belong to a full-day paid oo booking with automatic worktime credit assignment
						// -- if the booking is request originated, we consider it only if the request has status "approved"
						$ooPaidAutoCreditBookingFullDays = \DayQuery::create()
																->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
																->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
																->useOOEntryQuery()
																	->filterByUser($curUser)
																	->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
																	->useOOBookingQuery()
																		->useOORequestQuery("oorequest", \OORequestQuery::LEFT_JOIN)
																			->filterByStatus(\OORequest::STATUS_APPROVED)
																			->_or()
																			->filterByStatus(null)
																		->endUse()
																		->filterByAutoassignWorktimeCredit(true)
																		->useOOBookingTypeQuery()
																			->filterByPaid(true)
																		->endUse()
																	->endUse()
																->endUse()
																->distinct()
																->find();
	
										
						// query for the days in the current week that belong to a full-day unpaid oo booking
						// -- if the booking is request originated, we consider it only if the request has status "approved"
						$ooUnpaidBookingFullDays = \DayQuery::create()
														->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
														->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
														->useOOEntryQuery()
															->filterByUser($curUser)
															->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
															->useOOBookingQuery()
																->useOORequestQuery("oorequest", \OORequestQuery::LEFT_JOIN)
																	->filterByStatus(\OORequest::STATUS_APPROVED)
																	->_or()
																	->filterByStatus(null)
																->endUse()
																->useOOBookingTypeQuery()
																	->filterByPaid(false)
																->endUse()
															->endUse()
														->endUse()
														->distinct()
														->find();
													
													
						// query for the days in the current week that are full day holidays							
						$fullDayHolidays = $workplanManager->getFullDayHolidaysInDateRange(	$curInCompleteWeek->getDateOfDay(),
																							DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6));
																							
						// now query for days (excluding weekends) that do not match one of the determined classes 
						// --> the days that come up are candidates for incomplete days
						$incompleteDayCandidates = \DayQuery::create()
														->filterByDateOfDay($curInCompleteWeek->getDateOfDay(), \DayQuery::GREATER_EQUAL)
														->filterByDateOfDay(DateTimeHelper::addDaysToDateTime($curInCompleteWeek->getDateOfDay(), 6), \DayQuery::LESS_EQUAL)
														->filterById($fullOffDays->toKeyValue("id", "id"), \DayQuery::NOT_IN)
														->filterById($regularEntryDays->toKeyValue("id", "id"), \DayQuery::NOT_IN)
														->filterById($ooPaidAutoCreditBookingFullDays->toKeyValue("id", "id"), \DayQuery::NOT_IN)
														->filterById($ooUnpaidBookingFullDays->toKeyValue("id", "id"), \DayQuery::NOT_IN)
														->filterByDateOfDay($fullDayHolidays->toKeyValue("id", "dateOfHoliday"), \DayQuery::NOT_IN)
														->filterByWeekdayName(array("Sat", "Sun"), \DayQuery::NOT_IN)
														->find();

														
						//								
						// we check the leftover incomplete candidates for a combination of half-day attributes
						// that would exempt them from completion
						//
						// for a day marked as an 1/2 off day (am/pm), the day would need:
						//
						//		- a (pm/am) holiday								
						//		- a (pm/am) paid, autocredit oo booking						
						//		- a (pm/am) unpaid oo booking
						//
						// for a day not marked as a 1/2 off day, but that carries a 1/2 day holiday (always falls on PM), the day would need:
						//
						//		- an (am) paid, autocredit oo booking									
						//		- an (am) unpaid oo booking
						
						// process each incomplete day candidate and check appropriately
						$exoneratedCandidateIdArray = array();
						
						foreach ( $incompleteDayCandidates as $curDay ) {
						
							// retrieve possible half-day holiday for the day
							$halfDayHolidays = $workplanManager->getHalfDayHolidaysInDateRange($curDay->getDateOfDay(), $curDay->getDateOfDay());
							
							// process according to whether the day carries an AM/PM off day marking
							if ( $tagManager->dayHasTag($curDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $curUser) ) {

								// if the day carries a half-day holiday, it is exonerated
								if ( $halfDayHolidays->count() == 1 ) {
									$exoneratedCandidateIdArray[] = $curDay->getId();
									continue;
								}
								
								// except for parametrization, the further tests are identical for those of the PM half day
								// accordingly, we set the appropriate parameters and then test in one single code block
								$queryAllotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM;
							}
							else if ( $tagManager->dayHasTag($curDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $curUser) ) {
								//
								// this candidate cant be saved by a half-day holiday as those always fall on the PM side
								
								// (see above)
								$queryAllotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;
							}
							else if ( $halfDayHolidays->count() == 1 ) {
								//
								// the day is not marked with a half-day off day, but carries a half-day holiday (pm side)
								// we set the parmetrization accordingly (see earlier remarks)
								$queryAllotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;		
							}
							else {
								// in all other cases there is no hope for exoneration
								continue;						
							}
	
							// test for a half-day paid, autocredit oo booking on the half day side just determined (open requests do not count)
							// if one is found, we exonerate the day and continue processing
							$ooPaidAutoCreditBookingHalfDay = \DayQuery::create()
																	->filterById($curDay->getId())
																	->useOOEntryQuery()
																		->filterByUser($curUser)
																		->filterByType($queryAllotmentType)
																		->useOOBookingQuery()
																			->useOORequestQuery("oorequest", \OORequestQuery::LEFT_JOIN)
																				->filterByStatus(\OORequest::STATUS_APPROVED)
																				->_or()
																				->filterByStatus(null)
																			->endUse()
																			->filterByAutoassignWorktimeCredit(true)
																			->useOOBookingTypeQuery()
																				->filterByPaid(true)
																			->endUse()
																		->endUse()
																	->endUse()
																	->distinct()
																	->find();
							
							if ( $ooPaidAutoCreditBookingHalfDay->count() == 1 ) {
								$exoneratedCandidateIdArray[] = $curDay->getId();
								continue;
							}
							
							
							// test for a half-day unpaid, oo booking on the half day side just determined (open requests do not count)
							// if one is found, we exonerate the day and continue processing		
							$ooUnpaidBookingHalfDay = \DayQuery::create()
																->filterById($curDay->getId())
																->useOOEntryQuery()
																	->filterByUser($curUser)
																	->filterByType($queryAllotmentType)
																	->useOOBookingQuery()
																		->useOORequestQuery("oorequest", \OORequestQuery::LEFT_JOIN)
																			->filterByStatus(\OORequest::STATUS_APPROVED)
																			->_or()
																			->filterByStatus(null)
																		->endUse()
																		->useOOBookingTypeQuery()
																			->filterByPaid(false)
																		->endUse()
																	->endUse()
																->endUse()
																->distinct()
																->find();										
																	
							if ( $ooUnpaidBookingHalfDay->count() == 1 ) {
								$exoneratedCandidateIdArray[] = $curDay->getId();
								continue;
							}
						
						}								
														
						// reduce the candidate list from earlier by the days just exonerated
						$incompleteDays = \DayQuery::create()
														->filterById($incompleteDayCandidates->toKeyValue("id", "id"), \DayQuery::IN)								
														->filterById($exoneratedCandidateIdArray, \DayQuery::NOT_IN);
														
						// if we detected incomplete days in the current week, we add the week to the list of still incomplete weeks
						if ( $incompleteDays->count() > 0 ) {
							$curUserResult[$curInCompleteWeek->getId()] = $curInCompleteWeek;
						}
						
					}
					
				}
				
			}
			
			// add the current users incomplete weeks to the overall digest
			$result[$curUser->getId()] = $curUserResult;
			
		}
		
		//
		// debug
		if ( false ) {
			echo "\n\n\n";
			foreach ( $result as $curUserId => $curWeekArray ) {
				
				echo "\nuser: " . $curUserId . "\n\n";
				foreach ( $curWeekArray as $curDay ) {
					echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curDay->getDateOfDay()) . "\n";
				}
				echo "------------------------------------\n";
				
			}	
		}
		
		return $result;
		
	}
	
	
	/**
	 * 
	 * Detects excessive overtime for all active users
	 * 
	 * @return array 	- an associative array of all users that carry excessive overtime.
	 * 					  the array maps user id's to the user's overtime amount expressed in seconds.
	 * 					
	 */
	public function detectExcessiveOvertimeForAllUsers() {
		
		// digest of users with excessive OT
		$excessiveOvertimeDigest = array();
		
		// get managers/services
		$userManager = $this->getCtx()->getUserManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		$computationService = $this->getCtx()->getComputationService();
		
		// retrieve all enabled users
		$users = $userManager->getAllEnabledUsers();
		
		// determine the threshold to excessive overtime from settings and convert it to seconds
		$overtimeExcessiveThresholdInSec = $settingsManager->getSettingValue(\Setting::KEY_OVERTIME_EXCESSIVE_IN_SEC);
		
		// compute overtime for each user, store in digest if value is in excess
		foreach ( $users as $curUser ) {
		
			// get total accrued worktime and plan time for current user
			$curUserTotalWorkTimeCreditInSec = 	  $computationService->computeTotalWorktimeCreditForUser($curUser, $curUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate())
												+ $computationService->computeWorktimeAdjustmentsForUser($curUser, $curUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
												
			$curUserTotalPlanTimeInSec = $computationService->computePlanTimeForUser($curUser, $curUser->getEntryDate(), DateTimeHelper::getDateTimeForCurrentDate());
			
			// store in digest if it exceeds threshold
			$curUserOvertimeInSec = $curUserTotalWorkTimeCreditInSec - $curUserTotalPlanTimeInSec;
			if ( $curUserOvertimeInSec > $overtimeExcessiveThresholdInSec ) {
				$excessiveOvertimeDigest[$curUser->getId()] = $curUserOvertimeInSec;
			}

		}
		
		return $excessiveOvertimeDigest;	
	}
	
	
	/**
	 * Determines whether a given week is fully contained in the indicated user's employment period
	 * 
	 * @return boolean					
	 */
	private function isWeekFullyContainedInUserEmploymentPeriod($week, $user) {
		
		$result = true;
		
		// test fails, if week starts earlier than user's employment start date
		if ( $week->getDateOfDay() < $user->getEntryDate() ) {
			$result = false;
		}
		// test also fails, if last day of week lies beyond employment end date
		else if ( DateTimeHelper::addDaysToDateTime($week->getDateOfDay(), 6) > $user->getExitDate() ) {
			$result = false;
		}
		
		return $result;
		
	}	

}