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

namespace momo\computationservice;

use momo\core\helpers\DateTimeHelper;
use momo\core\exceptions\MomoException;
use momo\core\services\BaseService;

/**
 * ComputationService
 * 
 * Computes time and vacation related quantities.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.computationservice
 */
class ComputationService extends BaseService {
	
	const SERVICE_KEY = "SERVICE_COMPUTATION";
	
	/**
	 * Computes the vacation credit due to a user relative to a given workplan.
	 * 
	 * The vacation credit due to the user is computed once relative to the user's
	 * "exit date" and once relative to the end of the indicated workplan (i.e., December 31st).
	 * 
	 * Hence, if a user's exit date lies before December 31st of the indicated workplan, the figure
	 * returned relative to the "exit date" will be less than that relative to the end of the workplan.
	 * Conversely, if the "exit date" lies beyond the end of the workplan, the two figures will be
	 * identical.
	 * 
	 * @param 	User 		$user
	 * @param 	Workplan 	$workplan		
	 * 
	 * @return	array(
	 * 				"vacation_credit_in_days_per_exit_date"			=> {VALUE},
	 * 				"vacation_credit_in_days_per_end_of_workplan"	=> {VALUE}
	 * 			)
	 */
	public function computeVactionCreditForUser($user, $workplan) {
		
		$result = array();
		
		// get managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// retrieve a reference to first and last days in workplan
		$firstDayInPlan = $workplanManager->getFirstDayInPlan($workplan);
		$lastDayInPlan = $workplanManager->getLastDayInPlan($workplan);
		
		// retrieve user entry and exit dates
		$userEntryDate = $user->getEntryDate();
		$userExitDate = $user->getExitDate();
		
		
		// figure out what age the user will be during the indicated workplan
		$userBirthYear = DateTimeHelper::getYearFromDateTime($user->getBirthDate());
		$maxAgeDuringWorkplan = $workplan->getYear() - $userBirthYear;
		
		//
		// retrieve applicable vacation tier
		
		// if a user turns 20 in the current year, they are still considered 19 for the
		// purpose of vacation allocation
		if ( $maxAgeDuringWorkplan <= 20 ) {
			$annualVacationDays = $workplan->getAnnualVacationDaysUpTo19();
		}
		// if a user turns 50 in the current year, they are considered 50
		// for the purpose of vacation allocation
		else if ( $maxAgeDuringWorkplan >= 50 ) {
			$annualVacationDays = $workplan->getAnnualVacationDaysFrom50();
		}
		// everybode else is out of luck...
		else {
			$annualVacationDays = $workplan->getAnnualVacationDays20To49();
		}
		
			
		// determine where to begin and end workday computation relative to the workplan
		// -- the workplan might be fully contained in the employment period, or it might only be partially contained
		$vacationComputationStartDate = $firstDayInPlan->getDateOfDay();
		if ( $userEntryDate > $firstDayInPlan->getDateOfDay() ) {
			$vacationComputationStartDate = $userEntryDate;
		}
		
		//
		// calculate the vacation credit relative to exit date
		//
		
		// set computation end date as indicated by the user's exit date
		$vacationComputationEndDate = $lastDayInPlan->getDateOfDay();
		if ( $userExitDate < $lastDayInPlan->getDateOfDay() ) {
			$vacationComputationEndDate = $userExitDate;
		}
		
		$result["vacation_credit_in_days_per_exit_date"] =    $annualVacationDays
															* $user->getWorkload()
															* $this->computeCreditFraction($vacationComputationStartDate, $vacationComputationEndDate);	
		
		//
		// calculate the vacation credit relative to last day in workplan
		//
		
		// set computation end date to last day in workplan
		$vacationComputationEndDate = $lastDayInPlan->getDateOfDay();
		
		$result["vacation_credit_in_days_per_end_of_workplan"] =   $annualVacationDays
																 * $user->getWorkload()
																 * $this->computeCreditFraction($vacationComputationStartDate, $vacationComputationEndDate);	
																				
		return $result;
		
	}
	
	/** 
	 * Calculates vacation "credit fraction" arising from indicated start and end dates,
	 * the value determines the fraction of a workplan's annual vacation credit that the user
	 * is entitled to based on the indicated date range.
	 * 
	 * @param 	DateTime 	$vacationComputationStartDate
	 * @param 	DateTime 	$vacationComputationEndDate	
	 * 
	 * @return 	float
	 */
	private function computeCreditFraction($vacationComputationStartDate, $vacationComputationEndDate) {
		
		$creditFraction = 0;				  
		
		// compute months and days between the indicated dates
		$monthsAndDaysInRange = DateTimeHelper::computeMonthsAndDaysInDateRange($vacationComputationStartDate, $vacationComputationEndDate);
		
		// add credit fraction contribution due to full months contained in date range
		$creditFraction = $monthsAndDaysInRange["months"] * (1 / 12);
		
		// if there is a day remainder, we need to allocate the vacation credit for that remainder
		// on the basis of the number of work days covered by the remainder
		if ( $monthsAndDaysInRange["days"] != 0 ) {

			// first we determine the start date that gave rise to the day remainder
			// this corresponds to the vacation computation start date offset by the determined number of full months in the range
			// (we compensate the range by one day, as "monthsAndDaysInRange" was computed inclusive the start/end dates)
			//
			$dayRemainderStartDate = $vacationComputationStartDate;
			if ( $monthsAndDaysInRange["months"] != 0 ) {
				$dayRemainderStartDate = DateTimeHelper::addMonthsToDateTime($vacationComputationStartDate, $monthsAndDaysInRange["months"]);
				$dayRemainderStartDate = DateTimeHelper::addDaysToDateTime($dayRemainderStartDate, -1);	
			}
			
			// next we compute the number of workdays that fall into the date range that gave rise to the day remainder
			$dayRemainderWorkdayCount = DateTimeHelper::computeWorkdaysInDateRange($dayRemainderStartDate, $vacationComputationEndDate);
			
			// the day remainder's contribution to the vacation credit fraction is then given by the by the
			// fraction of the average number of monthly workdays (21.74) covered
			// to ensure against possible overruns of unity (if the day remainder yields more than 21.74 workdays, possible in certain months)
			// the computed fraction is minimized to unity.
			$creditFraction += min(1, ($dayRemainderWorkdayCount / 21.74)) * (1 / 12);
		}		
	
		return $creditFraction;
		
	}
	

	/**
	 * Computes a digest of vacation data points for the indicated user.
	 * 
	 * When called without specifying an (optional) target date, the computation is performed up to the present date.
	 * 
	 * The digest returnes:
	 * 
	 *  - the vacation days credited, broken down by workplan
	 *  - the vacation days consumed, broken down by workplan
	 *  - the vacation days booked, broken down by workplan
	 *  - the vacation days adjustments, broken down by workplan
	 *    
	 * Next to the above, the digest contains the following aggregata data:
	 * 
	 *  - the currently booked vacation days					.. i.e., the vacation days presently booked, but not yet taken
	 *  - the current workplan's consumed vacation days 		.. i.e., the vacation days consumed within the present workplan
	 *  - the current vacation balance 							.. i.e., the vacation days still available to the user
	 *  
	 *  Note: "current" in the above is understood to be relative to the target date in operation.
	 *  
	 * 
	 * @param 	User 		$user
	 * @param 	DateTime 	$targetDate		- (optional) the date for which the digest is to be rendered
	 * 
	 * 
	 * @return	array(
	 * 						"vacation_days_credited_by_workplan"		=> array(
	 * 																			{WORKPLAN_1_YEAR} => {VALUE},
	 * 																			...,
	 * 																			{WORKPLAN_N_YEAR} => {VALUE}
	 * 																		),
	 * 
	 * 	 					"vacation_days_consumed_by_workplan"		=> array(
	 * 																			{WORKPLAN_1_YEAR} => {VALUE},
	 * 																			...,
	 * 																			{WORKPLAN_N_YEAR} => {VALUE}
	 * 																		),
	 * 
	 * 	 	  	 			"vacation_days_adjusted_by_workplan"		=> array(
	 * 																			{WORKPLAN_1_YEAR} => {VALUE},
	 * 																			...,
	 * 																			{WORKPLAN_N_YEAR} => {VALUE}
	 * 																		),
	 * 
	 * 						"vacation_days_booked_by_workplan"			=> array(
	 * 																			{WORKPLAN_1_YEAR} => {VALUE},
	 * 																			...,
	 * 																			{WORKPLAN_N_YEAR} => {VALUE}
	 * 																		),
	 * 
	 * 						"aggregate_results"							=> array(
	 * 																			"global_vacation_days_credit" 				=> {VALUE},
	 * 																			"global_vacation_days_consumed" 			=> {VALUE},
	 * 																			"global_vacation_days_booked" 				=> {VALUE},
	 * 																			"global_vacation_days_adjustment" 			=> {VALUE},
	 * 																			"global_vacation_days_balance_effective" 	=> {VALUE},
	 *  																		"global_vacation_days_balance_projected" 	=> {VALUE}
	 * 																		)
	 * 			)
	 * 
	 */	
	public function computeVacationStatisticsDigestForUser($user, $targetDate=null) {
		
		$vacationDigest = array();
		
		// get managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$entryManager = $this->getCtx()->getEntryManager();
		
		
		// if not specified, set target date to current date
		if ( $targetDate == null ) {
			$targetDate = DateTimeHelper::getDateTimeForCurrentDate();;
		}
		

		// initialize digest entries that carry "per workplan" information for all
		// existing workplans
		
		$workplans = $workplanManager->getAllPlans();
		
		$vacationDigest["vacation_days_credited_by_workplan"] 	= array();
		$vacationDigest["vacation_days_consumed_by_workplan"] 	= array();	
		$vacationDigest["vacation_days_booked_by_workplan"] 	= array();	
		$vacationDigest["vacation_days_adjusted_by_workplan"] 	= array();
		
		foreach ( $workplans as $curWorkplan ) {
			// initialize workplan's digest entries to zero
			$vacationDigest["vacation_days_credited_by_workplan"][$curWorkplan->getYear()] 	= 0;
			$vacationDigest["vacation_days_consumed_by_workplan"][$curWorkplan->getYear()] 	= 0;
			$vacationDigest["vacation_days_booked_by_workplan"][$curWorkplan->getYear()]	= 0;
			$vacationDigest["vacation_days_adjusted_by_workplan"][$curWorkplan->getYear()] 	= 0;	
		}
		
			
		//
		// compile each workplan's vacation contribution into digest
		
		$workplans = $workplanManager->getAllPlans();
		foreach ( $workplans as $curWorkplan ) {

			//if ( $curWorkplan->getYear() <= DateTimeHelper::getCurrentYear() ) {
			if ( $curWorkplan->getYear() <= DateTimeHelper::getYearFromDateTime($targetDate) ) {
				
				// get first day in workplan
				$firstDayInPlan = $workplanManager->getFirstDayInPlan($curWorkplan);
				
				// retrieve vaction credit adjustment entry from first day in workplan																						
				$vacationCreditAdjustmentEntry = $entryManager->getAdjustmentEntries(	
																						$user,
																						\AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS,
																						$firstDayInPlan->getDateOfDay(),
																						$firstDayInPlan->getDateOfDay()
																					);		
				// compile data into digest
				if ( $vacationCreditAdjustmentEntry->count() > 0 ) {
					$vacationDigest["vacation_days_credited_by_workplan"][$curWorkplan->getYear()] = (float) $vacationCreditAdjustmentEntry->getFirst()->getValue();
				}
			}
			
		}
		
		
		//
		// compute the number of vacation days consumed per workplan and compute the global
		// vacation days consumed from that
		
		// query for consumption of full vacation days
		$fullDayVacationConsumedQuery = \OOEntryQuery::create()
											->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
											->useOOBookingQuery()
												->useOOBookingTypeQuery()
													->filterByType(\OOBookingType::SYSTEM_TYPE_VACATION)
												->endUse()
												->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
													->filterByStatus(\OORequest::STATUS_APPROVED)
													->_or()
													->filterByStatus(null)
												->endUse()
											->endUse()
											->useUserQuery()
												->filterById($user->getId())
											->endUse()
											->useDayQuery()
												->filterByDateofDay($targetDate, \DayQuery::LESS_EQUAL)
												->useWorkplanQuery()
												->endUse()
											->endUse()
											->withColumn('COUNT(*)', 'totalWorkplanFullVacationDays')
											->groupBy('Workplan.id')
											->find();
		
		// query for consumption of hald vacation days
		$halfDayVacationConsumedQuery = \OOEntryQuery::create()
											->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
											->_or()
											->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
											->useOOBookingQuery()
												->useOOBookingTypeQuery()
													->filterByType(\OOBookingType::SYSTEM_TYPE_VACATION)
												->endUse()
												->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
													->filterByStatus(\OORequest::STATUS_APPROVED)
													->_or()
													->filterByStatus(null)
												->endUse()
											->endUse()
											->useUserQuery()
												->filterById($user->getId())
											->endUse()
											->useDayQuery()
												->filterByDateofDay($targetDate, \DayQuery::LESS_EQUAL)
												->useWorkplanQuery()
												->endUse()
											->endUse()
											->withColumn('COUNT(*)', 'totalWorkplanHalfVacationDays')
											->groupBy('Workplan.id')
											->find();
		

		//
		// compile data into digest and compute global days consumed
									
		
		foreach ( $fullDayVacationConsumedQuery as $curResult ) {
			
			// add full day vacation days consumed to appropriate digest entry
			$vacationDigest["vacation_days_consumed_by_workplan"][$curResult->getDay()->getWorkplan()->getYear()] = (float) $curResult->getTotalWorkplanFullVacationDays();
		}

		foreach ( $halfDayVacationConsumedQuery as $curResult ) {
			
			// convert current result's contribution to full days
			$curResultFullVacationDays = 0.5 * $curResult->getTotalWorkplanHalfVacationDays();
			
			$vacationDigest["vacation_days_consumed_by_workplan"][$curResult->getDay()->getWorkplan()->getYear()] += $curResultFullVacationDays;
			
		}
		
		
		//
		// compute vacation days booked per workplan, where the workplans considered are those from the present date forward
		// -- booked vacation days are those that lie ahead of the present date
			
		// full vacation days booked
		$fullDayVacationBookedQuery = \OOEntryQuery::create()
												->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
												->useOOBookingQuery()
													->useOOBookingTypeQuery()
														->filterByType(\OOBookingType::SYSTEM_TYPE_VACATION)
													->endUse()
													->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
														->filterByStatus(\OORequest::STATUS_APPROVED)
														->_or()
														->filterByStatus(null)
													->endUse()
												->endUse()
												->useUserQuery()
													->filterById($user->getId())
												->endUse()
												->useDayQuery()
													->filterByDateofDay($targetDate, \DayQuery::GREATER_THAN)
													->useWorkplanQuery()
													->endUse()
												->endUse()
												->withColumn('COUNT(*)', 'totalWorkplanFullBookedVacationDays')
												->groupBy('Workplan.id')
												->find();
								
								
		// half vacation days booked
		$halfDayVacationBookedQuery = \OOEntryQuery::create()
												->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
												->_or()
												->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
												->useOOBookingQuery()
													->useOOBookingTypeQuery()
														->filterByType(\OOBookingType::SYSTEM_TYPE_VACATION)
													->endUse()
													->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
														->filterByStatus(\OORequest::STATUS_APPROVED)
														->_or()
														->filterByStatus(null)
													->endUse()
												->endUse()
												->useUserQuery()
													->filterById($user->getId())
												->endUse()
												->useDayQuery()
													->filterByDateofDay($targetDate, \DayQuery::GREATER_THAN)
													->useWorkplanQuery()
													->endUse()
												->endUse()
												->withColumn('COUNT(*)', 'totalWorkplanHalfBookedVacationDays')
												->groupBy('Workplan.id')
												->find();
		

		//
		// compile data into digest and compute global days consumed
								
		
		foreach ( $fullDayVacationBookedQuery as $curResult ) {
			
			// add full day vacation days consumed to appropriate digest entry
			$vacationDigest["vacation_days_booked_by_workplan"][$curResult->getDay()->getWorkplan()->getYear()] = (float) $curResult->getTotalWorkplanFullBookedVacationDays();
		}

		foreach ( $halfDayVacationBookedQuery as $curResult ) {
			
			// convert current result's contribution to full days
			$curResultFullVacationDays = 0.5 * $curResult->getTotalWorkplanHalfBookedVacationDays();
			
			// add this to the appropriate digest entry
			$vacationDigest["vacation_days_booked_by_workplan"][$curResult->getDay()->getWorkplan()->getYear()] += $curResultFullVacationDays;
			
		}												
												
		
		//
		// sum adjustments to vacation balance
		// -- adjustment entries need to me made within employees employment range
		$vacationAdjustmentsQuery = \AdjustmentEntryQuery::create()
											->filterByType(\AdjustmentEntry::TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS)
											->_or()
											->filterByType(\AdjustmentEntry::TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS)
											->useUserQuery()
												->filterById($user->getId())
											->endUse()
											->useDayQuery()
												->filterByDateofDay($user->getEntryDate(), \DayQuery::GREATER_EQUAL)
												->filterByDateofDay($targetDate, \DayQuery::LESS_EQUAL)
												->useWorkplanQuery()
												->endUse()
											->endUse()
											->withColumn('SUM(value)', 'totalVacationAdjustmentInDays')
											->groupBy('Workplan.id')
											->find();

											
		//
		// compile data into digest and compute global days consumed
									
		
		foreach ( $vacationAdjustmentsQuery as $curResult ) {
			// add full day vacation days consumed to appropriate digest entry
			$vacationDigest["vacation_days_adjusted_by_workplan"][$curResult->getDay()->getWorkplan()->getYear()] = (float) $curResult->gettotalVacationAdjustmentInDays();
		}											
											
		
		//
		// with per-workplan information in hand, compute global figures
		
		// vacation booked
		$globalVacationBooked = 0;
		foreach ( $vacationDigest["vacation_days_booked_by_workplan"] as $curWorkPlanYear => $curWorkPlanValue ) {
			$globalVacationBooked += $curWorkPlanValue;
		}
		
		// global vacation credit
		$globalVacationCredit = 0;
		foreach ( $vacationDigest["vacation_days_credited_by_workplan"] as $curWorkPlanYear => $curWorkPlanValue ) {
			$globalVacationCredit += $curWorkPlanValue;
		}
		
		// global vacation consumed
		$globalVacationConsumed = 0;
		foreach ( $vacationDigest["vacation_days_consumed_by_workplan"] as $curWorkPlanYear => $curWorkPlanValue ) {
			$globalVacationConsumed += $curWorkPlanValue;
		}
		
		// global vacation adjustment
		$globalVacationAdjustment = 0;
		foreach ( $vacationDigest["vacation_days_adjusted_by_workplan"] as $curWorkPlanYear => $curWorkPlanValue ) {
			$globalVacationAdjustment += $curWorkPlanValue;
		}
		
		
		//												
		// from this compute the effective global vacation balance
		// -- the effective balance is the balance that results relative to the user's entry and exit date
		$globalVacationBalanceEffective = $globalVacationCredit - ($globalVacationConsumed + $globalVacationBooked) + $globalVacationAdjustment;
		
		//
		// we also need to compute the global projected vacation balance
		// -- the projected balance is the balance up to the end of the end of the workplan containing the target date
		
		// get the workplan for the target date
		$targetDateWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($targetDate));
		// compute the vacation credit for the target date workplan
		$targetDateWorkplanVacationCreditDigest = $this->computeVactionCreditForUser($user, $targetDateWorkplan);
		
		// compute the projected balance from the vacation credit digest
		// note: only applues if the user is of type "STAFF", users of type "STUDENT" get zero projected credit
		//
		// TODO: it's unfortunate that this method needs to be aware of employee type to do its thing.
		//		 perhaps restructure the projected vacation functionality at some time
		//
		$globalVacationBalanceProjected = 0;
		if ( $user->getType() == \User::TYPE_STAFF ) {
			$globalVacationBalanceProjected = 	  $globalVacationBalanceEffective
											+ $targetDateWorkplanVacationCreditDigest["vacation_credit_in_days_per_end_of_workplan"]
											- $targetDateWorkplanVacationCreditDigest["vacation_credit_in_days_per_exit_date"];
		}
		
		//
		// compile global values into result digest
		$vacationDigest["aggregate_results"] = array();	
		$vacationDigest["aggregate_results"]["global_vacation_days_credit"] 			= $globalVacationCredit;
		$vacationDigest["aggregate_results"]["global_vacation_days_consumed"] 			= $globalVacationConsumed;
		$vacationDigest["aggregate_results"]["global_vacation_days_booked"] 			= $globalVacationBooked;
		
		$vacationDigest["aggregate_results"]["global_vacation_days_adjustment"] 		= $globalVacationAdjustment;
		$vacationDigest["aggregate_results"]["global_vacation_days_balance_effective"] 	= $globalVacationBalanceEffective;
		$vacationDigest["aggregate_results"]["global_vacation_days_balance_projected"] 	= $globalVacationBalanceProjected;
		
		return $vacationDigest;
		
	}
	
	
	/**
	 * Computes the total worktime credit for a given user and date range
	 * 
	 * The worktime balance is computed across all days from the indicated start date up to the indicated end date.
	 * 
	 * To compute the total worktime balance, we need to:
	 * 
	 * 	- sum all worktime credit due to timetracker entries
	 *  - sum all worktime credit due to oo bookings
	 * 
	 * @param 	User 		$user
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @return	float		the total worktime credit, in seconds
	 */
	public function computeTotalWorktimeCreditForUser($user, $startDate, $endDate) {
		
		$result = "0";
		
		$computationStartDate = $startDate;
		$computationEndDate = $endDate;
		
		// constrain computation start and end dates to employment period
		if ( $startDate < $user->getEntryDate() ) {
			$computationStartDate = $user->getEntryDate();
		}
		
		if ( $endDate > $user->getExitDate() ) {
			$computationEndDate = $user->getExitDate();
		}
		
								
		// add the contribution due to regular entries entries
		$result += $this->computeRegularEntryWorktimeCreditForUser($user, $startDate, $endDate);
		
		// add the contribution due to oo bookings
		$result += $this->computeOOBookingWorktimeCreditForUser($user, $startDate, $endDate);
								
		return $result;
		
	}
	
	
	/**
	 * Computes the total worktime credit due to regular entries for a given user and date range
	 * 
	 * The credit is computed across all days from the indicated start date up to the indicated end date.
	 * 
	 * @param 	User 		$user
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @return	float		the total worktime credit, in seconds
	 */
	public function computeRegularEntryWorktimeCreditForUser($user, $startDate, $endDate) {
		
		$result = "0";
		
		// 
		// sum the worktime credit that arises from RegularEntry instances with worktime credit
		$worktimeQuery = \RegularEntryQuery::create()
								->useUserQuery()
									->filterById($user->getId())
								->endUse()
								->useDayQuery()
									->filterByDateofDay($startDate, \DayQuery::GREATER_EQUAL)
									->filterByDateofDay($endDate, \DayQuery::LESS_EQUAL)
								->endUse()
								->useRegularEntryTypeQuery()
									->filterByWorkTimeCreditAwarded(true)
								->endUse()
								->withColumn('SUM(time_interval)', 'totalWorkTime')
								->groupBy('User.id')
								->findOne();
	
										
		// set the result in accordance of query state	
		if ( $worktimeQuery !== null ) {
			$result = $worktimeQuery->getTotalWorkTime();
		}
								
		return $result;
		
	}
	
	
	
	/**
	 * Computes the worktime credit due to OO bookings (with automatic worktime credit) for a given user and date range
	 * 
	 * The worktime balance is computed across all days from the indicated start date up to the indicated end date.
	 * 
	 * @param 	User 		$user
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @return	float		the total worktime credit due to oo bookings, in seconds
	 */
	public function computeOOBookingWorktimeCreditForUser($user, $startDate, $endDate) {
		
		$result = "0";
				
		// query for the full day ooentry allotments due to oo bookings with automatic worktime credit
		// -- as the credited time is workplan dependent, the computation is grouped by workplan
		$ooEntryFullDayAllotmentSums = \OOEntryQuery::create()
											->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
											->filterByUser($user)
											->useDayQuery()
												->filterByDateofDay($startDate, \DayQuery::GREATER_EQUAL)
												->filterByDateofDay($endDate, \DayQuery::LESS_EQUAL)
												->useWorkplanQuery()
												->endUse()
											->endUse()
											->useOOBookingQuery()
												->filterByAutoAssignWorktimeCredit(true)
												->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
													->filterByStatus(\OORequest::STATUS_APPROVED)
													->_or()
													->filterByStatus(null)
												->endUse()
											->endUse()
											->withColumn('COUNT(*) * (Workplan.weeklyWorkHours / 5)', 'totalFullDayAllotmentTime')
											->groupBy('Workplan.id')					
											->find();
										

		// query for the half day ooentry allotments due to oo bookings with automatic worktime credit
		// -- as the credited time is workplan dependent, the computation is grouped by workplan
		$ooEntryHalfDayAllotmentSums = \OOEntryQuery::create()
										->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
										->_or()
										->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
										->filterByUser($user)
										->useDayQuery()
											->filterByDateofDay($startDate, \DayQuery::GREATER_EQUAL)
											->filterByDateofDay($endDate, \DayQuery::LESS_EQUAL)
											->useWorkplanQuery()
											->endUse()
										->endUse()
										->useOOBookingQuery()
											->filterByAutoAssignWorktimeCredit(true)
											->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
												->filterByStatus(\OORequest::STATUS_APPROVED)
												->_or()
												->filterByStatus(null)
											->endUse()
										->endUse()
										->withColumn('COUNT(*) * (0.5 * (Workplan.weeklyWorkHours / 5))', 'totalHalfDayAllotmentTime')
										->groupBy('Workplan.id')								
										->find();									


		//
		// combine all results to compute resulting worktime credit			
										
		// add the contribution due to full-day and half-day allotments
		foreach ( $ooEntryFullDayAllotmentSums as $curAllotmentSum ) {
			$result += 3600 * $curAllotmentSum->getTotalFullDayAllotmentTime();
		}
		
		foreach ( $ooEntryHalfDayAllotmentSums as $curAllotmentSum ) {
			$result += 3600 * $curAllotmentSum->getTotalHalfDayAllotmentTime();
		}
								
		return $result;
		
	}
	
	
	/**
	 * Computes a user's worktime adjustments.
	 * 
	 * The computation across all days from the indicated start date up to the indicated end date.
	 * At maximum, the computation ranges from the employment start date up to the employment end date.
	 * 
	 * @param 	User 		$user
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @param 	User 		$user		
	 * 
	 * @return	integer		the total worktime adjustment, in seconds
	 */
	public function computeWorktimeAdjustmentsForUser($user, $startDate, $endDate) {
		
		$computationStartDate = $startDate;
		$computationEndDate = $endDate;
		
		// constrain computation start and end dates to employment period
		if ( $startDate < $user->getEntryDate() ) {
			$computationStartDate = $user->getEntryDate();
		}
		
		if ( $endDate > $user->getExitDate() ) {
			$computationEndDate = $user->getExitDate();
		}
		
		// sum possible worktime balance adjustment entries
		// -- adjustment entries need to me made within employee's employment range
		$globalWorktimeAdjustmentsQuery = \AdjustmentEntryQuery::create()
												->filterByType(\AdjustmentEntry::TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS)
												->_or()
												->filterByType(\AdjustmentEntry::TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS)
												->useUserQuery()
													->filterById($user->getId())
												->endUse()
												->useDayQuery()
													->filterByDateofDay($computationStartDate, \DayQuery::GREATER_EQUAL)
													->filterByDateofDay($computationEndDate, \DayQuery::LESS_EQUAL)
												->endUse()
												->withColumn('SUM(value)', 'totalWorktimeAdjustmentInSeconds')
												->groupBy('User.id')
												->findOne();

		// determine value of global vacation adjustment												
		$globalWorktimeAdjustmentsInSeconds = 0;										
		if ( $globalWorktimeAdjustmentsQuery !== null ) {
			$globalWorktimeAdjustmentsInSeconds = $globalWorktimeAdjustmentsQuery->getTotalWorktimeAdjustmentInSeconds();
		}	
		
		return $globalWorktimeAdjustmentsInSeconds;
	}
	
	
	/**
	 * Computes the "plan time" in the indicated date range for a given user.
	 * 
	 * Where "plan time" is defined to be the work time credit that the user needs to accrue
	 * for a given date range. Accordingly, the method takes into account all aspects occurring
	 * in the date range that reduce plan time.
	 * 
	 * Outside of an "unpaid" oo booking, holidays are the only aspect that may reduce plan time.
	 * 
	 * Within an oo booking of type "unpaid", all allotment oo entries reduce plan time
	 * by the indicated amount. I.e., a "full day" allotment oo entry will reduce plan time
	 * by one full day, and a "half-day" allotment oo entry will reduce it by one half-day.
	 * Furthermore, within such a booking, holidays do not apply. I.e., there is no reduction of
	 * plan time due to holidays within an "unpaid" oo booking.
	 * 
	 * In case the date range exceeds the user's employment date range, the method automatically
	 * restricts the considered date range so that it falls within the employment date range.
	 * 
	 * To compute the total plan time outside of unpaid oo bookings, we need to:
	 * 
	 * 	- sum the users daily plan time across all days in the range
	 *  - adjust the sum for holidays that fall in the date range
	 *  - weight the sum according to the user's workload
	 *  
	 * To compute the total plan time within unpaid oo bookings, we need to:
	 * 
	 * 	- sum the users daily plan time across all days in the range
	 *  - adjust the sum for allotment oo entries that fall in the date range
	 *  - weight the sum according to the user's workload 
	 *  
	 *  
	 * @param 	User 		$user
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate	
	 * 
	 * @return	float		the total plan time, in seconds
	 * 
	 */
	public function computePlanTimeForUser($user, $startDate, $endDate) {
		
		$planTime = 0;
		
		// get managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$ooManager = $this->getCtx()->getOOManager();
		
		// get user workload
		$userWorkload = $user->getWorkload();
		
		//
		// figure out start and end dates
		// -- these may not extend beyond employment period
		$computationStartDate = $startDate;
		$computationEndDate = $endDate;
		
		// constrain computation start and end dates to employment period
		if ( $startDate < $user->getEntryDate() ) {
			$computationStartDate = $user->getEntryDate();
		}
		
		if ( $endDate > $user->getExitDate() ) {
			$computationEndDate = $user->getExitDate();
		}

		//
		// query for the nominal plantime, grouped by workplan
		// -- nominal plan time being the plan time that results from work days in the date range,
		//	  - unadjusted for any other factors							
		$nominalPlanTimeSums = \DayQuery::create()
									->filterByDateofDay($computationStartDate, \DayQuery::GREATER_EQUAL)
									->filterByDateofDay($computationEndDate, \DayQuery::LESS_EQUAL)
									->filterByWeekDayName(array("Sat", "Sun"), \DayQuery::NOT_IN)
									->useWorkplanQuery()
									->endUse()
									->withColumn('COUNT(*) * (Workplan.weeklyWorkHours / 5)', 'totalNominalPlanTimeByWorkplanSegment')
									->groupBy('Workplan.id')
									->find();							

											
		//
		// query for the unpaid full-day allotment oo entries and calculate the resulting compensation time, grouped by workplan
		$fullDayUnpaidOOTimeSums = \OOEntryQuery::create()
										->filterByUser($user)
										->useOOBookingQuery()
											->useOOBookingTypeQuery()
												->filterByPaid(false)
											->endUse()
										->endUse()
										->useDayQuery()
											->filterByDateofDay($computationStartDate, \DayQuery::GREATER_EQUAL)
											->filterByDateofDay($computationEndDate, \DayQuery::LESS_EQUAL)
											->useWorkplanQuery()
											->endUse()
										->endUse()
										->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
										->withColumn('COUNT(*) * (Workplan.weeklyWorkHours / 5)', 'totalUnpaidOOTimeByWorkplanSegment')
										->groupBy('Workplan.id')
										->find();							

										
		//
		// query for the unpaid half-day allotment oo entries and calculate the resulting compensation time, grouped by workplan
		$halfDayUnpaidOOTimeSums = \OOEntryQuery::create()
										->filterByUser($user)
										->useOOBookingQuery()
											->useOOBookingTypeQuery()
												->filterByPaid(false)
											->endUse()
										->endUse()
										->useDayQuery()
											->filterByDateofDay($computationStartDate, \DayQuery::GREATER_EQUAL)
											->filterByDateofDay($computationEndDate, \DayQuery::LESS_EQUAL)
											->useWorkplanQuery()
											->endUse()
										->endUse()
										->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
										->_or()
										->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
										->withColumn('COUNT(*) * (0.5 * (Workplan.weeklyWorkHours / 5))', 'totalUnpaidOOTimeByWorkplanSegment')
										->groupBy('Workplan.id')
										->find();	
									

		// query for the holidays in date range
		$fullDayHolidays = $workplanManager->getFullDayHolidaysInDateRange($computationStartDate, $computationEndDate);
		$halfDayHolidays = $workplanManager->getHalfDayHolidaysInDateRange($computationStartDate, $computationEndDate);
		$oneHourHolidays = $workplanManager->getOneHourHolidaysInDateRange($computationStartDate, $computationEndDate);
		
		// query for the oo bookings within, or cutting across the indicated date range
		$ooBookingsInDateRange = $ooManager->getOOBookingsInDateRange($user, $startDate, $endDate, false);
		
		
		//
		// compute resulting plan time
			
		// the base plan time is equal to the nominal plan time (i.e., the sum of the nominal plan times of the various workplan segements)
		// -- as the nominal plan time sum does not take into account the user's workload, we need to compensate accordingly here.
		foreach ( $nominalPlanTimeSums as $curSum ) {
			$planTime += $userWorkload * $curSum->getTotalNominalPlanTimeByWorkplanSegment();
		}
		
		// adjust the base plan time for full-day unpaid oo bookings in the indicated date range
		// -- the unpaid oo entry sums do take into account the user workload, hence they need not be compensated here
		//    note: the user workload is automatically taken into account when summing (see earlier) due to the fact that oo allotment entries
		//	         only appear for workdays, but not for "off days"
		foreach ( $fullDayUnpaidOOTimeSums as $curSum ) {
			$planTime -= $curSum->getTotalUnpaidOOTimeByWorkplanSegment();
		}
		
		
		// adjust the base plan time for half-day unpaid oo bookings in the indicated date range
		foreach ( $halfDayUnpaidOOTimeSums as $curSum ) {
			$planTime -= $curSum->getTotalUnpaidOOTimeByWorkplanSegment();
		}
		
		
		//
		// adjust the base value for holidays
		// 
		// if a holiday falls within the confines of an unpaid oo booking it is not compensated
		// -- reason: unpaid absences are never awarded holidays
		
		// full day holidays
		foreach ( $fullDayHolidays as $curFullDayHoliday ) {
			
			// figure out daily plan time for current holiday
			$curDailyPlantime = $curFullDayHoliday->getWorkplan()->getWeeklyWorkHours() / 5;
			
			// compensate the full-day holiday
			$planTime -= ($userWorkload * $curDailyPlantime);
			
			// negate compensation, in case holiday falls within unpaid oo booking
			foreach ( $ooBookingsInDateRange as $curOOBooking ) {
			
				// test if holiday falls within unpaid oo booking
				if ( 	($curOOBooking->getOOBookingType()->getPaid() === false)
					 && ( ($curOOBooking->getStartDate() <= $curFullDayHoliday->getDateOfHoliday()) && ($curOOBooking->getEndDate() >= $curFullDayHoliday->getDateOfHoliday()) )
				   ) {
				   	
					// negate the holiday compensation
					$planTime += ($userWorkload * $curDailyPlantime);
				}
			}
		}
		
		
		// half day holidays
		foreach ( $halfDayHolidays as $curHalfDayHoliday ) {
			
			// figure out daily plan time for current holiday
			$curDailyPlantime = $curHalfDayHoliday->getWorkplan()->getWeeklyWorkHours() / 5;
			
			// compensate the half-day holiday
			$planTime -= $userWorkload * (0.5 * $curDailyPlantime);
			
			// negate compensation, in case holiday falls within unpaid oo booking
			foreach ( $ooBookingsInDateRange as $curOOBooking ) {
			
				// test if holiday falls within unpaid oo booking
				if ( 	($curOOBooking->getOOBookingType()->getPaid() === false)
					 && ( ($curOOBooking->getStartDate() <= $curHalfDayHoliday->getDateOfHoliday()) && ($curOOBooking->getEndDate() >= $curHalfDayHoliday->getDateOfHoliday()) )
				   ) {
				   	
					// negate the holiday compensation
					$planTime += $userWorkload * (0.5 * $curDailyPlantime);
				}
			}
		}
		
		
		// one hour holidays
		foreach ( $oneHourHolidays as $curOneHourHoliday ) {
			
			// compensate the one hour holiday
			$planTime -= 1;
			
			// negate compensation, in case holiday falls within unpaid oo booking
			foreach ( $ooBookingsInDateRange as $curOOBooking ) {
			
				// test if holiday falls within unpaid oo booking
				if ( 	($curOOBooking->getOOBookingType()->getPaid() === false)
					 && ( ($curOOBooking->getStartDate() <= $curOneHourHoliday->getDateOfHoliday()) && ($curOOBooking->getEndDate() >= $curOneHourHoliday->getDateOfHoliday()) )
				   ) {
				   	
					// negate the holiday compensation
					$planTime += 1;
				}
			}
		}	

		// and convert to seconds
		$planTime = $planTime * 3600;
	
		return $planTime;
		
	}
	
	

	/**
	 * Computes the total time credit for a given user and date range
	 * 
	 * A user's total time credit is given by the worktime credit, plus
	 * any non-worktime credit related entries contained in the date range.
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @return	float		the total worktime credit, in seconds
	 */
	public function computeTotalTimeCreditForUser($user, $startDate, $endDate) {
		
		$result = 0;
		
		// query for the day's non-worktime credit entries
		$nonWorktimeQuery = \RegularEntryQuery::create()
								->filterByUser($user)
								->useRegularEntryTypeQuery()
									->filterByWorkTimeCreditAwarded(false)
								->endUse()
								->useDayQuery()
									->filterByDateofday($startDate, \RegularEntryQuery::GREATER_EQUAL)
									->filterByDateofday($endDate, \RegularEntryQuery::LESS_EQUAL)
								->endUse()
								->withColumn('SUM(time_interval)', 'totalNonWorkTime')
								->groupBy('Day.id')
								->find();


		//
		// compute total time credit for the day
								
		// sum the non worktime credit found
		foreach ( $nonWorktimeQuery as $curDaySum ) {
			$result += $curDaySum->getTotalNonWorkTime();
		}									
		
		// add in the worktime credit for the day					
		$result += $this->computeTotalWorktimeCreditForUser($user, $startDate, $endDate);
																				
		return $result;
		
	}
	
	
	/**
	 * Computes the project time credit for a given user and date range
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$startDate
	 * @param 	DateTime	$endDate
	 * 
	 * @return	float		the total project time, in seconds
	 */
	public function computeProjectTimeCreditForUser($user, $startDate, $endDate) {
		
		$result = 0;
		
		$projectTimeQuery = \ProjectEntryQuery::create()
								->filterByUser($user)
								->useDayQuery()
									->filterByDateofday($startDate, \RegularEntryQuery::GREATER_EQUAL)
									->filterByDateofday($endDate, \RegularEntryQuery::LESS_EQUAL)
								->endUse()
								->withColumn('SUM(time_interval)', 'totalProjectTime')
								->groupBy('Day.id')
								->find();

													
		// sum the project time found
		foreach ( $projectTimeQuery as $curDaySum ) {
			$result += $curDaySum->getTotalProjectTime();
		}							
																				
		return $result;
		
	}
	

}