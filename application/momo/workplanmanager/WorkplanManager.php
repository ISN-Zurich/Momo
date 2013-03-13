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

namespace momo\workplanmanager;

use momo\audittrailmanager\EventDescription;
use momo\core\helpers\DebugHelper;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;
use momo\core\helpers\DateTimeHelper;
use momo\workplanmanager\exceptions\PlanInUseException;
use momo\workplanmanager\exceptions\PlanActiveException;
use momo\workplanmanager\exceptions\NoWorkplanFoundException;
use momo\workplanmanager\exceptions\DayDoesNotExistException;

/**
 * WorkplanManager
 * 
 * Single access point for all workplan related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.workplanmanager
 */
class WorkplanManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_WORKPLAN";
	
	/**
	 * Returns all workplans, ordered by year
	 * 
	 * @return	PropelCollection
	 */
	public function getAllPlans() {
		return \WorkplanQuery::create()
					->orderByYear()
					->find();
	}
	
	
	/**
	 * Retrieves a workplan by its id
	 * 
	 * @return Workplan (or null)
	 * 
	 * TODO throw exception, if workplan not found
	 */
	public function getPlanById($planId) {
		return \WorkplanQuery::create()
					->filterById($planId)
					->findOne();
	}
	
	
	/**
	 * Retrieves a workplan by its year
	 * 
	 * @return Workplan (or null)
	 * 
	 * TODO throw exception, if workplan not found
	 */
	public function getPlanByYear($year) {
		return \WorkplanQuery::create()
					->filterByYear($year)
					->findOne();
	}
	
	
	/**
	 * Returns the first workplan in existence
	 * 
	 * @return	Workplan
	 * 
	 * @throws NoWorkplanFoundException
	 */
	public function getFirstPlan() {
		$wp =  \WorkplanQuery::create()
						->orderByYear("asc")
						->findOne();
						
		if ( $wp === null ) {
			throw new NoWorkplanFoundException("WorkplanManager:getFirstPlan() - could not retrieve first workplan as there are no workplans defined");
		}
		
		return $wp;
					
	}
	
	
	/**
	 * Returns the last workplan in existence
	 * 
	 * @return	Workplan
	 * 
	 * @throws NoWorkplanFoundException
	 */
	public function getLastPlan() {
		$wp =  \WorkplanQuery::create()
						->orderByYear("desc")
						->findOne();
						
		if ( $wp === null ) {	
			throw new NoWorkplanFoundException("WorkplanManager:getLastPlan() - could not retrieve last workplan as there are no workplans defined");
		}
		
		return $wp;
	}
	
	
	/**
	 * Returns the Day object for a given date
	 * 
	 * @param	DateTime	$dayDate
	 * 
	 * @return	Day
	 * 
	 * @throws DayDoesNotExistException
	 */
	public function getDayForDate($dayDate) {
		$entryDay = \DayQuery::create()
						->filterByDateOfDay($dayDate)
						->findOne();
						
		if ( $entryDay === null ) {
			throw new DayDoesNotExistException("WorkplanManager:getDayForDate() - could not retrieve a Day instance for the indicated date: " . DateTimeHelper::formatDateTimeToStandardDateFormat($dayDate));
		}
		
		return $entryDay;									
	}
	
	
	/**
	 * Returns the first Day of the indicated workplan
	 * 
	 * @param	Workplan	$workplan
	 * 
	 * @return	Day
	 */
	public function getFirstDayInPlan($workplan) {
		
		// query for the first Day date in the workplan
		$firstDayDate = \DayQuery::create()
							->filterByWorkplan($workplan)
							->withColumn('MIN(Day.dateOfDay)', 'firstDayDate')
							->groupBy("Day.workplanId")
							->findOne();

		$firstDay = $this->getDayForDate(DateTimeHelper::getDateTimeFromMySQLDateFormat($firstDayDate->getFirstDayDate()));		
									
		return $firstDay;										
	}
	
	
	/**
	 * Returns the last Day of the indicated workplan
	 * 
	 * @param	Workplan	$workplan
	 * 
	 * @return	Day
	 */
	public function getLastDayInPlan($workplan) {
		
		// query for the last Day date in the workplan
		$lastDayDate = \DayQuery::create()
							->filterByWorkplan($workplan)
							->withColumn('MAX(Day.dateOfDay)', 'lastDayDate')
							->groupBy("Day.workplanId")
							->findOne();
	
		$lastDay = $this->getDayForDate(DateTimeHelper::getDateTimeFromMySQLDateFormat($lastDayDate->getLastDayDate()));		
							
							
		return $lastDay;								
	}
	
	
	/**
	 * Returns a Day object by id
	 * 
	 * @param	integer		$dayId
	 * 
	 * @return	Day
	 * 
	 * @throws DayDoesNotExistException
	 */
	public function getDayById($dayId) {
		$entryDay = \DayQuery::create()
							->filterById($dayId)
							->findOne();

		if ( $entryDay === null ) {
			throw new DayDoesNotExistException("WorkplanManager:getDayById() - could not retrieve a Day instance with id: " . $dayId);
		}
		
		return $entryDay;						
							
	}
	
	
	/**
	 * Retrieves days by a list of ids
	 *	 
	 * @param 	array	$idList		the list of day ids
	 *
	 * @return	PropelCollection	a collection of Day objects
	 */
	public function getDaysByIdList($idList) {
		return \DayQuery::create()
					->filterById($idList)
					->find();
	}
	
	
	/**
	 * Returns the Day objects for a given date range
	 * 
	 * Note: the returned days are *inclusive* the boundary dates. 
	 * 
	 * @param	DateTime	$startDate
	 * @param	DateTime	$endDate
	 * 
	 * @return	PropelCollection
	 * 
	 * @throws DayDoesNotExistException		- if the start and end dates of the returned collection do not correspond to the range indicated
	 * 
	 */
	public function getDaysInDateRange($startDate, $endDate) {
		//
		// query for the indicated range
		$days = \DayQuery::create()
						->filterByDateOfDay($startDate, \DayQuery::GREATER_EQUAL)
						->filterByDateOfDay($endDate, \DayQuery::LESS_EQUAL)
						->find();
						
		// throw exception, if the indicated range was not (or not fully) retrieved
		if ( 		($days->getFirst()->getDateOfDay() != $startDate)
				||  ($days->getLast()->getDateOfDay() != $endDate) ) {
					
			// write error to log and throw exception
			$errorMsg = "WorkplanManager:getDaysInDateRange() - could not retrieve the indiacated range of Day instances (start date: " . DateTimeHelper::formatDateTimeToStandardDateFormat($startDate);
			$errorMsg .= ", end date: " . DateTimeHelper::formatDateTimeToStandardDateFormat($endDate) . ")";
		
			throw new DayDoesNotExistException($errorMsg);
		}

		return $days;
		
	}
	
	/**
	 * Retrieves the holiday information for a given day
	 * 
	 * @param Day $day
	 * 
	 * @return Holiday (or null, if the Day is no holiday)
	 */
	public function getHolidayForDay($day) {
		return \HolidayQuery::create()
					->useWorkplanQuery()
						->useDayQuery()
							->filterById($day->getId())
						->endUse()
					->endUse()
					->filterByDateOfHoliday($day->getDateOfDay())
					->findOne();								
	}
	
	
	
	/**
	 * Retrieves the full day holidays in the indicated date range
	 * 
	 * @param DateTime $startDate
	 * @param DateTime $endDate
	 * 
	 * @return PropelCollection
	 */
	public function getFullDayHolidaysInDateRange($startDate, $endDate) {
		return \HolidayQuery::create()
					->filterByDateOfHoliday($startDate, \HolidayQuery::GREATER_EQUAL)
					->filterByDateOfHoliday($endDate, \HolidayQuery::LESS_EQUAL)
					->filterByFullDay(true)
					->filterByHalfDay(false)
					->filterByOneHour(false)
					->find();								
	}
	
	
	/**
	 * Retrieves the half day holidays in the indicated date range
	 * 
	 * @param DateTime $startDate
	 * @param DateTime $endDate
	 * 
	 * @return PropelCollection
	 */
	public function getHalfDayHolidaysInDateRange($startDate, $endDate) {
		return \HolidayQuery::create()
					->filterByDateOfHoliday($startDate, \HolidayQuery::GREATER_EQUAL)
					->filterByDateOfHoliday($endDate, \HolidayQuery::LESS_EQUAL)
					->filterByFullDay(false)
					->filterByHalfDay(true)
					->filterByOneHour(false)
					->find();								
	}
	
	
	/**
	 * Retrieves the one hour holidays in the indicated date range
	 * 
	 * @param DateTime $startDate
	 * @param DateTime $endDate
	 * 
	 * @return PropelCollection
	 */
	public function getOneHourHolidaysInDateRange($startDate, $endDate) {
		return \HolidayQuery::create()
					->filterByDateOfHoliday($startDate, \HolidayQuery::GREATER_EQUAL)
					->filterByDateOfHoliday($endDate, \HolidayQuery::LESS_EQUAL)
					->filterByFullDay(false)
					->filterByHalfDay(false)
					->filterByOneHour(true)
					->find();								
	}
	
	
	/**
	 *  Indicates whether a workplan exists for the indicated year
	 *  
	 *  @param 	integer	$year
	 *  
	 *  @return boolean
	 */
	public function planExists($year) {
		
		$workplan =  \WorkplanQuery::create()
						->filterByYear($year)
						->findOne();
						
		return $workplan != null ? true : false;				
	}
	
	
	/**
	 *  Creates a new workplan 
	 * 
	 *  @param integer				$workPlanYear					the year that the workplan applies to
	 *  @param integer				$weeklyWorkHours				the number of weekly work hours for the workplan
	 *  @param integer				$annualVacationDaysUpTo19		the number of annual vacation days for <= 19 years of age
	 *  @param integer				$annualVacationDays20to49		the number of annual vacation days for employees 20 to 49 years of age
	 *  @param integer				$annualVacationDaysFrom50		the number of annual vacation days for employees >= 50 years of age
	 *  @param array				$fullDayHolidays				the date strings of the full day holidays in the workplan
	 *  @param array				$halfDayHolidays				the date strings of the half day holidays in the workplan
	 *  @param array				$oneHourHolidays				the date strings of the one hour holidays in the workplan
	 *  
	 *  @return Workplan	the created workplan
	 *  
	 */
	public function createPlan(	$workPlanYear, $weeklyWorkHours, $annualVacationDaysUpTo19, $annualVacationDays20to49, $annualVacationDaysFrom50,
								$fullDayHolidays, $halfDayHolidays, $oneHourHolidays) {
								   	
		try {
			
			//
			// creating a workplan is a three step process:
			//		1. create Workplan instance
			//		2. associate Day instances for each calendar day in workplan
			//		3. associate Holiday instances for each holiday in workplan
			
			
			// instantiate new Workplan
			$newWorkplan = new \Workplan();
			
			// populate with passed arguments 
			$newWorkplan->setYear($workPlanYear);
			$newWorkplan->setWeeklyworkhours($weeklyWorkHours);
			$newWorkplan->setAnnualvacationdaysupto19($annualVacationDaysUpTo19);
			$newWorkplan->setAnnualvacationdays20to49($annualVacationDays20to49);
			$newWorkplan->setAnnualvacationdaysfrom50($annualVacationDaysFrom50);
			
			// associate applicable Day instances
			for ( $monthIndex = 1; $monthIndex <= 12; $monthIndex++ ) {
				
				$firstDayOfMonth = \DateTime::createFromFormat('j n Y', '1 ' . $monthIndex . ' ' . $workPlanYear);
				$numDaysInMonth = date("t", $firstDayOfMonth->getTimestamp());
				
				for ( $dayInMonthIndex = 1; $dayInMonthIndex <= $numDaysInMonth; $dayInMonthIndex++ ) {
					
					$curDayDate = \DateTime::createFromFormat('j n Y', $dayInMonthIndex . ' ' . $monthIndex . ' ' . $workPlanYear);
					
					$curDay = new \Day();
					$curDay->setDateofday($curDayDate);
					$curDay->setWeekDayName($curDayDate->format("D"));
					$curDay->setIso8601Week($curDayDate->format("W"));
					
					$newWorkplan->addDay($curDay);
				}
			}
		
			// assign Holiday instances to update target
			$this->generateHolidays($newWorkplan, $fullDayHolidays, $halfDayHolidays, $oneHourHolidays);
			
			// persist the graph
			$newWorkplan->save();	
				
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("WorkplanManager:createWorkplan() - a database error has occurred while while attempting to create the workplan for year: " . $workPlanYear, 0, $ex);
		}
		
		return $newWorkplan;
	}
	
	
	/**
	 *  Updates a workplan 
	 * 
	 *  @param Workplan				$updateTarget					the workplan to update
	 *  @param integer				$workPlanYear					the year that the workplan applies to
	 *  @param integer				$weeklyWorkHours				the number of weekly work hours for the workplan
	 *  @param integer				$annualVacationDaysUpTo19		the number of annual vacation days for employees <= 19 years of age
	 *  @param integer				$annualVacationDays20to49		the number of annual vacation days for employees 20 to 49 years of age
	 *  @param integer				$annualVacationDaysFrom50		the number of annual vacation days for employees >= 50 years of age
	 *  @param array				$fullDayHolidays				the date strings indicating the full day holidays in the workplan
	 *  @param array				$halfDayHolidays				the date strings indicating the half day holidays in the workplan
	 *  @param array				$oneHourHolidays				the date strings indicating the one hour holidays in the workplan
	 */
	public function updatePlan(	$updateTarget, $workPlanYear, $weeklyWorkHours, $annualVacationDaysUpTo19, $annualVacationDays20to49, $annualVacationDaysFrom50,
								$fullDayHolidays, $halfDayHolidays, $oneHourHolidays) {
		
		// proceed only if plan is not "active" and hence locked against edits
		if ( ! $updateTarget->isActive() ) {
			
			try {
					
				//
				// updating a workplan means to update its parametrization
				// the Day instances that refer to the workplan remain unaffected as a workplan's year cannot be edited
				
				//
				// populate update target with passed arguments 
				
				$updateTarget->setWeeklyworkhours($weeklyWorkHours);
				$updateTarget->setAnnualvacationdaysUpTo19($annualVacationDaysUpTo19);
				$updateTarget->setAnnualvacationdays20to49($annualVacationDays20to49);
				$updateTarget->setAnnualvacationdaysfrom50($annualVacationDaysFrom50);
				
				//
				// update Holiday instances
				//
				// 	- first clear all existing Holiday references
				//	- then create new Holiday instances
				
				// clear existing holiday refs
				$emptyHolidays = \HolidayQuery::create()->filterById(-1)->find();
				$updateTarget->setHolidays($emptyHolidays);
				
				// assign Holiday instances to update target
				$this->generateHolidays($updateTarget, $fullDayHolidays, $halfDayHolidays, $oneHourHolidays);
			
				// persist the graph
				$updateTarget->save();	
			
			}
			catch (\PropelException $ex) {
				// rethrow
				throw new MomoException("WorkplanManager:createWorkplan() - a database error has occurred while while attempting to update the workplan for year: " . $updateTarget->getYear(), 0, $ex);
			}
					
		}
		else {
			throw new PlanActiveException("WorkplanManager:updatePlan() - unable to update the workplan (year: " . $updateTarget->getYear()  . ") as it is in active state.");
		}	
		
		return $updateTarget;
	}
	
	
	/**
	 * Instantiates applicable Holiday instances and assigns it to the indicated Workplan
	 * 
	 * @param 	Workplan	$plan
	 * @param	array		$fullDayHolidays		
	 * @param 	array		$halfDayHolidays		
	 * @param 	array		$oneHourHolidays		
	 *   
	 * @throws PlanInUseException
	 */
	private function generateHolidays($workPlan, $fullDayHolidays, $halfDayHolidays, $oneHourHolidays) {
		
		// full day holidays
		foreach ($fullDayHolidays as $curFullDayHoliday) {
			
			$newHoliday = new \Holiday();
			$newHoliday->setDateOfHoliday(DateTimeHelper::getDateTimeFromStandardDateFormat($curFullDayHoliday));
			$newHoliday->setFullDay(true);
			$newHoliday->setHalfday(false);
			$newHoliday->setOneHour(false);
			
			$workPlan->addHoliday($newHoliday);
		}
		
		
		// half day holidays
		foreach ($halfDayHolidays as $curHalfDayHoliday) {
			
			$newHoliday = new \Holiday();
			$newHoliday->setDateOfHoliday(DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayHoliday));
			$newHoliday->setFullDay(false);
			$newHoliday->setHalfday(true);
			$newHoliday->setOneHour(false);
			
			$workPlan->addHoliday($newHoliday);
		}
		
		// one hour holidays
		foreach ($oneHourHolidays as $curOneHourHoliday) {
			
			$newHoliday = new \Holiday();
			$newHoliday->setDateOfHoliday(DateTimeHelper::getDateTimeFromStandardDateFormat($curOneHourHoliday));
			$newHoliday->setFullDay(false);
			$newHoliday->setHalfday(false);
			$newHoliday->setOneHour(true);
			
			$workPlan->addHoliday($newHoliday);
		}
		
	}
	
	
	/**
	 * Deletes the indicated plan
	 * 
	 * Note: The delete operation is permitted, only if the delete target is not in use.
	 *  	 The method throws an exception if that should not be the case.
	 * 
	 * @param 	Workplan	$plan
	 *   
	 * @throws PlanInUseException
	 */
	public function deletePlan($plan) {
		//
		// proceed only, if plan not in use
		if ( ! $plan->isInUse() ) {
			
			try {
				// delete the plan
				$plan->delete();	
			}
			catch (\PropelException $ex) {
				// rethrow
				throw new MomoException("WorkplanManager:deletePlan() - a database error has occurred while while attempting to delete the workplan for year: " . $plan->getYear(), 0, $ex);
			}	
				
		}
		else {
			throw new PlanInUseException("WorkplanManager:deletePlan() - unable to delete workplan (year: " . $plan->getYear()  . ") as it is in use.");
		}
	}
	
	
	/**
	 * Computes the worktime that result from a given workplan parametrization - broken down by month and KW.
	 * 
	 *  @param 		integer		$workPlanYear				the year that the workplan applies to
	 *  @param 		integer		$weeklyWorkHours			the number of weekly work hours for the workplan
	 *  @param 		array		$fullDayHolidays			the dates (unix epochs) of the full day holidays in the workplan
	 *  @param 		array		$halfDayHolidays			the dates (unix epochs) of the half day holidays in the workplan
	 *  @param 		array		$oneHourHolidays			the dates (unix epochs) of the one hour holidays in the workplan
	 * 
	 *	@returns 	array		the computation result, structured as follows:
	 *  
	 *  result = array (	
	 *
	 *		"totalHoursYear"	=> (int)
	 *		"month_1"			=> array (
	 *									kw_1	=> (int),
	 *									...
	 *									kw_n	=> (int)
	 *								), 
	 *		...
	 *
	 *		"month_n"			=> array (
	 *									kw_m	=> (int),
	 *									...
	 *									kw_z	=> (int)
	 *								) 	
	 *  	)
	 *
	 */
	public function computeAnnualWorktimeBreakdown($workPlanYear, $weeklyWorkHours, $fullDayHolidays, $halfDayHolidays, $oneHourHolidays) { 
	
		$result = array();
		$totalHours = 0;
		$dailyWorkHours = $weeklyWorkHours / 5;
		
		
		// loop over calendar months
		for ( $curMonth = 1; $curMonth <= 12; $curMonth++ ) {
	
			//
			// obtain DateTime objects for month's boundary dates
			$dateFirstOfMonthString = '1-' . $curMonth . '-' . $workPlanYear . " 0000";
			$dateFirstOfMonth = \DateTime::createFromFormat('j-n-Y Hi', $dateFirstOfMonthString);
						
			$dateLastOfMonthString = date("t", $dateFirstOfMonth->getTimestamp()) . '-' . $curMonth . '-' . $workPlanYear  . " 0000";
			$dateLastOfMonth = \DateTime::createFromFormat('j-n-Y Hi', $dateLastOfMonthString);
					
			//
			// figure out first and last KW in month
			$firstKwInMonth = (integer) date("W", $dateFirstOfMonth->getTimestamp());
			$lastKwInMonth = (integer) date("W", $dateLastOfMonth->getTimestamp());
			
			// numerical represensation for weekday of first and last days of month (Monday=1, Sunday=7)
			$firstOfMonthWeekdayAsNumber = date("N", $dateFirstOfMonth->getTimestamp());
			$lastOfMonthWeekdayAsNumber = date("N", $dateLastOfMonth->getTimestamp());
			
			// numerical represensation of first and last day of month
			$firstOfMonthDayAsNumber = date("d", $dateFirstOfMonth->getTimestamp());
			$lastOfMonthDayNumber = date("d", $dateLastOfMonth->getTimestamp());
				
			//
			// build array of kws in current month
			$kwsInCurMonth = array();
			
			for ( $curDay = $firstOfMonthDayAsNumber; $curDay <= $lastOfMonthDayNumber; $curDay++ ) {
			
				$curDayDateString = (integer) $curDay . '-' . $curMonth . '-' . $workPlanYear . " 0000";
				$curDayKw = \DateTime::createFromFormat('j-n-Y Hi', $curDayDateString)->format("W");

				array_push($kwsInCurMonth, $curDayKw);
				
			}
			
			// remove dupes
			$kwsInCurMonth = array_unique($kwsInCurMonth);

			//////////
			// - figure out the KW the work month starts and ends with
			// - calculate the current month's work hours that fall on the starting KW and ending KW
			/////////
			
			// month starts with next KW if first day of month falls on weekend
			if ( $firstOfMonthWeekdayAsNumber > 5 ) {
				
				// obtain date for the monday of the following week
				$dayFollowingMonday = 1 + (8 - $firstOfMonthWeekdayAsNumber);
				$dateFollowingMonday = \DateTime::createFromFormat('j-n-Y Hi', $dayFollowingMonday . "-" . $curMonth . '-' . $workPlanYear  . " 0000");
				$firstKwInMonth = (integer) date("W", $dateFollowingMonday->getTimestamp());
				
				// in that case, the entire starting KW falls into the current month
				$startingKwHours = $weeklyWorkHours;
				
				// accordingly remove the first kw in array of kws built earlier
				array_shift($kwsInCurMonth);
				
			}
			else {
				///////
				// first day of month is in the range MO-FR of the current KW
				
				// figure out work hours that fall on the starting KW
				$startingKwHours = $weeklyWorkHours * ((5 - $firstOfMonthWeekdayAsNumber + 1) / 5);
			}
			
			// figure out current month's work hours that fall on ending KW
			$endingKwHours = $weeklyWorkHours * (MIN($lastOfMonthWeekdayAsNumber, 5) / 5);
			
			//
			// map resulting hours to month and kw
			$result["month_" . $curMonth] = array();
			
			foreach ( $kwsInCurMonth as $curKwInMonth ) {
				
				// set kw work hours, according to  boundary cases computed above
				if ( $curKwInMonth == $firstKwInMonth ) {
					$curKwHours = $startingKwHours;
				}
				else if ( $curKwInMonth == $lastKwInMonth ) {
					$curKwHours = $endingKwHours;
				}
				else {
					$curKwHours = $weeklyWorkHours;
				}
				
				
				//
				// now, compensate weekly work hours based on indicated holidays
				
				// full day holidays
				foreach ( $fullDayHolidays as $curFullDayHoliday ) {
					
					// figure out month of current holiday
					$curHolidayMonth = DateTimeHelper::getDateTimeFromStandardDateFormat($curFullDayHoliday)->format("n");
					
					// see if holiday falls in current month
					if ( $curHolidayMonth == $curMonth ) {
						// figure out KW of current holiday
						$curHolidayKW = DateTimeHelper::getDateTimeFromStandardDateFormat($curFullDayHoliday)->format("W");
						
						// adjust kw hours, if we're looking at holiday's kw
						if ( $curKwInMonth == $curHolidayKW ) {
							$curKwHours -= $dailyWorkHours;
						}

					}
				}
				
				// half day holidays
				foreach ( $halfDayHolidays as $curHalfDayHoliday ) {
					
					// figure out month of current holiday
					$curHolidayMonth = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayHoliday)->format("n");
					
					// see if holiday falls in current month
					if ( $curHolidayMonth == $curMonth ) {
						// figure out KW of current holiday
						$curHolidayKW = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayHoliday)->format("W");
						
						// adjust kw hours, if we're looking at holiday's kw
						if ( $curKwInMonth ==  $curHolidayKW ) {
							$curKwHours -= $dailyWorkHours / 2;
						}	
					}
				}
				
				// one hour holidays
				foreach ( $oneHourHolidays as $curOneHourHoliday ) {
					
					// figure out month of current holiday
					$curHolidayMonth = DateTimeHelper::getDateTimeFromStandardDateFormat($curOneHourHoliday)->format("n");
					
					// see if holiday falls in current month
					if ( $curHolidayMonth == $curMonth ) {
						// figure out KW of current holiday
						$curHolidayKW = DateTimeHelper::getDateTimeFromStandardDateFormat($curOneHourHoliday)->format("W");
						
						// adjust kw hours, if we're looking at holiday's kw
						if ( $curKwInMonth ==  $curHolidayKW ) {
							$curKwHours -= 1;
						}	
					}
				}
	
				$result["month_" . $curMonth]["kw_" . $curKwInMonth] = (float) $curKwHours;
				$totalHours += (float) $curKwHours;	
				
			}

		}
		
		$result["totalHoursYear"] = $totalHours;
	
		return $result;
		
	}
	
	
	/**
	 * Initializes a workplan specific parameters for the indicated user.
	 * 
	 * At present, vacation time is the only such parameter that needs to be handled.
	 * 
	 * The method applies the user's vacation credit by means of an AdjustmentEntry of the appropriate
	 * type placed on the workplan's first Day (i.e., Jan 1st). If such an entry already exists,
	 * the workplan is considered initialized for the user and no action is taken.
	 * 
	 * @param	User 		$user
	 * @param	Workplan 	$workplan
	 */
	public function initWorkplanForUser($user, $workplan) {
		
		// get managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$compService = $this->getCtx()->getComputationService();
		
		// retrieve a reference to first and last days in workplan
		$firstDayInPlan = $this->getFirstDayInPlan($workplan);
		$lastDayInPlan = $this->getLastDayInPlan($workplan);
		
		// retrieve user entry and exit dates
		$userEntryDate = $user->getEntryDate();
		$userExitDate = $user->getExitDate();
		
		//
		// we proceed only, if workplan is applicable to user's employment period
		if ( 	($userExitDate >= $firstDayInPlan->getDateOfDay())
			&&	($userEntryDate <= $lastDayInPlan->getDateOfDay()) ) {
			
			// query for the user's annual vacation credit adjustment for the concerned workplan
			$vacationCreditAdjustmentEntry = $entryManager->getAdjustmentEntries(	
																			$user,
																			\AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS,
																			$firstDayInPlan->getDateOfDay(),
																			$firstDayInPlan->getDateOfDay()
																		);	
																				
			//	
			// proceed only, if the current workplan's vacation credit has not yet been awarded	
			if ( $vacationCreditAdjustmentEntry->count() == 0 ) {
				
				// only users of type "staff" are awarded vacation credit, we branch accordingly
				if ( $user->getType() == \User::TYPE_STAFF ) {
				
					// obtain digesst of vacation days due to the user
					$vacationDaysDigest = $compService->computeVactionCreditForUser($user, $workplan);
				
					// initialize workplan by means of an appropriately typed adjustment entry
					$entryManager->createAdjustmentEntry(	$firstDayInPlan->getDateOfDay(),
															$user,
															\AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS,
															\AdjustmentEntry::CREATOR_SYSTEM,
															$vacationDaysDigest["vacation_credit_in_days_per_exit_date"],
															"Vacation credit for " . $workplan->getYear() . " workplan"
														);
				
				}
				// user is of type "student", set vacation credit to zero
				else {
					// initialize workplan by means of an appropriately typed adjustment entry
					$entryManager->createAdjustmentEntry(	$firstDayInPlan->getDateOfDay(),
															$user,
															\AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS,
															\AdjustmentEntry::CREATOR_SYSTEM,
															0,
															"Vacation credit for " . $workplan->getYear() . " workplan"
														);
				}										
			}
			
		}
				
	}
	
	
	/**
	 * Reinitializes all workplan's applicable to the employment period of the indicated user.
	 * 
	 * At present, vacation time is the only parameter that is handled as part of workplan initialization
	 * 
	 * @param	User 		$user
	 */
	public function reInitAllWorkplansForUser($user) {
		
		// get managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		
		// retrieve all workplans
		$workplans = $this->getAllPlans();
		
		// get db connection
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// reinitialize each workplan
			foreach ( $workplans as $curWorkplan ) {
				$this->reInitWorkplanForUser($user, $curWorkplan);
			}
			
			// commit TX
			$con->commit();
			
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("UserManager:reInitAllWorkplansForUser() - a database error has occurred while attempting to reinitialize workplans for the user with login: " . $login);
		}		
		
	}
	
	
	/**
	 * Reinitializes the indicated workplan's applicable to the employment period of the indicated user.
	 * 
	 * At present, vacation time is the only parameter that is handled as part of workplan initialization
	 * 
	 * @param	User 		$user
	 * @param	Workplan 	$workplan
	 */
	public function reInitWorkplanForUser($user, $workplan) {
		
		// get managers needed
		$entryManager = $this->getCtx()->getEntryManager();
				
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			//
			// reinitialize the indicated workplan
			
			// get first day in workplan
			$firstDayInPlan = $this->getFirstDayInPlan($workplan);
			
			// retrieve vaction credit adjustment entry from first day in workplan
			$vacationCreditAdjustmentEntry = $entryManager->getAdjustmentEntries(	
																				$user,
																				\AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS,
																				$firstDayInPlan->getDateOfDay(),
																				$firstDayInPlan->getDateOfDay()
																			);																								
																									
			// if applicable, delete vacation credit adjustment entry
			if ( $vacationCreditAdjustmentEntry->count() > 0 ) {
				$entryManager->deleteEntryById($vacationCreditAdjustmentEntry->getFirst()->getId());
			}
			
			// initialize the workplan
			$this->initWorkplanForUser($user, $workplan);
			
			// commit TX
			$con->commit();
			
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			
			// write error to log and rethrow
			$errorMsg = "UserManager:reInitWorkplanForUser() - a database error has occurred while attempting to reinitialize the workplan for year: " . $workplan->getYear();
			$errorMsg .= " for the user with login: " . $login;
					
			throw new MomoException($errorMsg);
		}		
		
	}
	
}