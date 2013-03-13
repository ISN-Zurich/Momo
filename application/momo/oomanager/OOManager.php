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

namespace momo\oomanager;

use momo\core\helpers\DateTimeHelper;

use momo\core\security\Roles;
use momo\oomanager\exceptions\InvalidStateException;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;

/**
 * OOManager
 * 
 * Single access point for all out-of-office related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.oomanager
 */
class OOManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_OOBOOKING";
	
	/**
	 * Retrieves a oo request by its id
	 * 
	 * @return OORequest (or null)
	 */
	public function getOORequestById($requestId) {
		return \OORequestQuery::create()
					->filterById($requestId)
					->findOne();
	}
	
	
	/**
	 * Returns all OO Requests for the indicated user
	 * 
	 * Requests are ordered chronologically in descending order
	 * 
	 * @param	User	$user
	 * 
	 * @return	PropelCollection
	 */
	public function getAllOORequestsForUser($user) {
		
		return \OORequestQuery::create()
					->useOOBookingQuery()
						->filterByUserId($user->getId())
						->useOOEntryQuery()
							->useDayQuery()
								->orderByDateOfDay(\OORequestQuery::DESC)
							->endUse()
						->endUse()
					->endUse()
					->distinct()
					->find();	
	}
	
	
	/**
	 * Returns all OO Requests of the indicated status for a given user
	 * 
	 * Requests are ordered chronologically in descending order
	 * 
	 * @param	User	$user
	 * @param	String	$requestStatus		- the status that is to be queried for
	 * 
	 * @return	PropelCollection
	 */
	public function getOORequestsByStatusForUser($user, $requestStatus) {
		
		return \OORequestQuery::create()
					->filterByStatus($requestStatus)
					->useOOBookingQuery()
						->filterByUserId($user->getId())
						->useOOEntryQuery()
							->useDayQuery()
								->orderByDateOfDay(\OORequestQuery::DESC)
							->endUse()
						->endUse()
					->endUse()
					->distinct()
					->find();	
	}
	
	
	/**
	 * Returns all OO Bookings
	 * 
	 * @return	PropelCollection
	 */
	public function getAllOOBookings() {
		return \OOBookingQuery::create()->find();		
	}
	
	
	/**
	 * Retrieves a oo booking by its id
	 * 
	 * @return OOBooking (or null)
	 */
	public function getOOBookingById($bookingId) {
		return \OOBookingQuery::create()
					->filterById($bookingId)
					->findOne();
	}
	
	
	/**
	 * Retrieves the oo bookings as indicated by the passed parameters.
	 * 
	 * @param 	PropelCollection		$users						- (pass null to ignore constraint) the users for which to retrieve the bookings 
	 * @param 	DateTime				$dateFrom					- (pass null to ignore constraint) the lower booking date range to consider
	 * @param 	DateTime				$dateUntil 					- (pass null to ignore constraint) the upper booking date range to consider
	 * @param	boolean					$limitToFullyContained		- (optional, defaults to true)
	 * 																   true: only bookings that are fully contained in the date range are to be returned
	 * 																   false : all bookings are returned, even if they extend across date range	 
	 * @param 	string					$sortOrder					- (optional, defaults to "asc") ["asc", "desc"] the sort order to apply to the primary sort field
	 * 																  the primary sort field is the user's last name, the secondary sort field is the start day of the booking
	 */
	public function getOOBookingsInDateRange($users, $dateFrom, $dateUntil, $limitToFullyContained=true, $sortOrder="asc") {
		
		// build query based on passed args
		$query = \OOBookingQuery::create();
		
		// filter by user
		if ( $users !== null ) {
			$query->filterByUser($users);
		}
		
		// filter by lower date
		// (note: we apply the date criteria to the oo booking range, not to the date that the booking was made)
		if ( $dateFrom !== null ) {
			
			$query->useOOEntryQuery()
						->useDayQuery()
							->filterByDateOfDay($dateFrom, \DayQuery::GREATER_EQUAL)
						->endUse()
					->endUse();
		}
		
		// filter by upper date
		if ( $dateUntil !== null ) {
			
			$query->useOOEntryQuery()
						->useDayQuery()
							->filterByDateOfDay($dateUntil, \DayQuery::LESS_EQUAL)
						->endUse()
					->endUse();
		
		}
		
		// execute the query
		$bookings = $query->distinct()->find();
		
		// if indicated, limit the result to bookings fully contained in date range
		if ( $limitToFullyContained ) {
			
			if ( ($dateFrom !== null) || ($dateUntil !== null) ) {
			
				// loop over all bookings, test accordingly and build list of booking ids to remove
				$removeBookingIds = array();
				foreach ( $bookings as $curBooking ) {
								
					if ( ($dateFrom !== null) ) {
						
						// prune the booking, if its start date lies before the beginning of the date range
						if ( $curBooking->getStartDate() < $dateFrom ) {
							$removeBookingIds[] = $curBooking->getId();
						}	
					
					}
					
					if ( ($dateUntil !== null) ) {
									
						// prune the booking, if its end date lies beyond the end of the date range
						if ( $curBooking->getEndDate() > $dateUntil ) {
							$removeBookingIds[] = $curBooking->getId();
						}	
					}
					
				}
				
				$bookings = \OOBookingQuery::create()
								->filterById($bookings->toKeyValue("id", "id"))
								->filterById($removeBookingIds, \OOBookingQuery::NOT_IN)
								->find();			
			}
			
		}
		
		
		// finally, sort as indicated
		$primarysFieldSortOrderCriterium = \DayQuery::ASC;
		if ( $sortOrder == "desc") {
			$primarysFieldSortOrderCriterium = \DayQuery::DESC;
		}
		
		$bookings = \OOBookingQuery::create()
								->filterById($bookings->toKeyValue("id", "id"))
								->useUserQuery()
								->endUse()
								->useOOEntryQuery()
									->useDayQuery()
									->endUse()
								->endUse()
								->distinct()
								->orderBy("User.lastName", $primarysFieldSortOrderCriterium)
								->orderBy("Day.dateOfDay", \DayQuery::DESC)
								->find();	
		
		return $bookings;					
	}
	
	
	/**
	 * Recalculates any oo booking that falls into the indicated date range
	 * 
	 * Recalculating is accomplished by updating the OOBooking with its present booking parametrization.
	 * 
	 * To keep things simple, the method recalculates all bookings, irrespective of type. 
	 * 
	 * @param	User		$user
	 * @param 	DateTime	$fromDate				- the start date of the range to consider
	 * @param 	DateTime	$untilDate				- the end date of the range to consider (null indicates no upper date limit)
	 */
	public function recalculateOOBookingsForDateRange($user, $fromDate, $untilDate=null) {
			
		// query for booking that fall within the indicated date range
		$affectedBookingsQuery =  \OOBookingQuery::create()
										->filterByUser($user)
										->useOOEntryQuery()
											->useDayQuery()
												->filterByDateOfDay($fromDate, \DayQuery::GREATER_EQUAL)
											->endUse()
										->endUse();
										
		if ( $untilDate !== null ) {
			//
			$affectedBookingsQuery->useOOEntryQuery()
										->useDayQuery()
											->filterByDateOfDay($untilDate, \DayQuery::LESS_EQUAL)
										->endUse()
									->endUse();
		}

		$affectedBookings = $affectedBookingsQuery->distinct()->find();	
								
		//
		// recalculate the bookings
		foreach ( $affectedBookings as $curBooking ) {
			//
			// if there is a booking affected, recalculate it via a call to updateBooking
		
			// compile am and pm half marker half days to DateTime arrays
			$halfDayMarkerEntriesAM = $curBooking->getHalfDayMarkerEntriesAM();
			$halfDayMarkerEntriesPM = $curBooking->getHalfDayMarkerEntriesPM();
			
			$amHalfDaysDateTimeArray = array();
			$pmHalfDaysDateTimeArray = array();
			
			foreach ( $halfDayMarkerEntriesAM as $curHalfDayMarkerEntry ) {
				$amHalfDaysDateTimeArray[] = $curHalfDayMarkerEntry->getDay()->getDateOfDay();
			}
			
			foreach ( $halfDayMarkerEntriesPM as $curHalfDayMarkerEntry ) {
				$pmHalfDaysDateTimeArray[] = $curHalfDayMarkerEntry->getDay()->getDateOfDay();
			}
			
			// recalculate booking
			$this->updateOOBooking(		
										$curBooking,
										$curBooking->getOOBookingType(),
										$curBooking->getStartDate(),
										$curBooking->getEndDate(),
										$amHalfDaysDateTimeArray,
										$pmHalfDaysDateTimeArray,
										$curBooking->getAutoAssignWorktimeCredit()
								   );
										
		}						
																						
						
	}
	
	

	/**
	 *  Creates an oo booking 
	 *
	 *  @param 	User				$user							- the user for which to create the booking
	 *  @param 	BookingType			$bookingType					- the booking type to use
	 *  @param 	DateTime			$fromDate						- the start date of the requested oo period 
	 *  @param 	DateTime			$untilDate						- the end date of the requested oo period 
	 *  @param  array				$halfDaysAM						- an array of DateTime objects, indicating AM half days in the indicated range 
	 *  @param  array				$halfDaysPM						- an array of DateTime objects, indicating PM half days in the indicated range
	 *  @param  boolean				$autoAssignWorktimeCredit		- whether the system is to automatically assign worktime credit (applies only to bookings of paid type)
	 *  															  (set to null for unpaid types)
	 */
	public function createOOBooking($user, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM, $autoAssignWorktimeCredit=null) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {	
			//
			// set up booking
			$newBooking = new \OOBooking();
			$newBooking->setOOBookingType($bookingType);
			$newBooking->setUser($user);
			
			// for system types, we set the auto assign worktime credit flag manually
			if ( $bookingType->getType() == \OOBookingType::SYSTEM_TYPE_VACATION ) {
				$newBooking->setAutoassignWorktimeCredit(true);
			}
			else if ( $bookingType->getType() == \OOBookingType::SYSTEM_TYPE_UNPAID_LEAVE ) {
				$newBooking->setAutoassignWorktimeCredit(false);
			}
			// user type, set to method param
			else {
				$newBooking->setAutoassignWorktimeCredit($autoAssignWorktimeCredit);
			}
				
			// create the oo entries for the booking
			$this->createOOEntriesForBooking($user, $newBooking, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM);
			
			// persist the booking
			$newBooking->save();
			
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "OOManager:createOOBooking() - created oo booking for user: " . $user->getLogin());	
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:createOOBooking() - a database error has occurred while while attempting to create a new oo booking." . $teamName, 0, $ex);
		}
		
		return $newBooking;
	}
	
	
	
	/**
	 *  Updates an oo booking 
	 *
	 *  @param 	OOBooking			$booking						- the update target
	 *  @param 	BookingType			$bookingType					- the oo booking type to use
	 *  @param 	DateTime			$fromDate						- the start date of the requested oo period 
	 *  @param 	DateTime			$untilDate						- the end date of the requested oo period 
	 *  @param  array				$halfDaysAM						- an array of DateTime objects, indicating AM half days in the indicated range 
	 *  @param  array				$halfDaysPM						- an array of DateTime objects, indicating PM half days in the indicated range
	 *  @param  boolean				$autoAssignWorktimeCredit		- whether the system is to automatically assign worktime credit.
	 *  															  (this applies only to bookings of paid type, set to null for unpaid types)
	 */
	public function updateOOBooking($booking, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM, $autoAssignWorktimeCredit=null) {

		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {

			//
			// set parameters pertaining to OOBooking object
			$booking->setAutoassignWorktimeCredit($autoAssignWorktimeCredit);
			$booking->setOOBookingType($bookingType);
			
			//
			// purge existing oo entries
			// -- when updating a booking, we recreate the oo entries from scratch
			$booking->getOOEntrys()->delete();
				
			// create the oo entries for the booking
			$this->createOOEntriesForBooking(
												$booking->getUser(),
												$booking,
												$bookingType,
												$fromDate,
												$untilDate,
												$halfDaysAM,
												$halfDaysPM
											);
						
			//
			// persist
			$booking->save();
			
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "OOManager:updateOOBooking() - updated oo booking with ID: " . $booking->getId());		
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:updateOOBooking() - a database error has occurred while while attempting to update the oo booking with id: " . $booking->getId(), 0, $ex);
		}
		
		return $booking;
	}
	
	
	/**
	 *  Creates the OOEntries that represent the booking.
	 *  
	 *  For explanation of paremeters, see "createOOBooking()" or "updateOOBooking()".
	 */
	private function createOOEntriesForBooking($user, $booking, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM) {
		
		// get refs to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$entryManager = $this->getCtx()->getEntryManager();
		$tagManager = $this->getCtx()->getTagManager();
		
		// get the collection of days indicated by the booking
		$bookingDays = $workplanManager->getDaysInDateRange($fromDate, $untilDate);
		
		//
		// create oo entries for the bookings
		// 
		// oo bookings fall into one of the following three modes:
		//		- "paid auto worktime", a mode that automatically assigns worktime credit according to certain rules (vacation would be an example)
		//		- "paid manual worktime", a mode that expects manual entry of worktime credit
		//		- "unpaid" bookings, a mode which does not involve any worktime credit at all
		//
		// an oo booking is represented by OOEntries that are of type "marker" or "allotment".
		//
		// a "marker" oo entry represents the booking's base parametrization. i.e. each day in the booking's indicated date range is represented with a "marker"
		// oo entry of which there are the three flavors "full day", "half day am" and "half day pm".
		//
		// depending on the conventions of the mode, a subset of the marker entries make up the actual "live" booking - this subset is identified by "allotment"
		// oo entries of which there are again three distinct flavors, namely: "full day", "half day am" and "half day pm".
		//
		// the algorithmic conventions governing the placement of "allotment" oo entries are as follows:
		//
		// 		- if the booking is "paid, auto worktime"
		//			- all weekends, full-day holidays and full off-days are *not* assigned an allotment oo entry
		//			- for the rest, we assign a full-day or half-day allotment oo entry as indicated in the booking form
		//				- full day markers result in half-day allotments for days that are half-day holidays or half-day off days
		//				- half day markers are applied provided they do not clash with possible half-day off days and half-day holidays
		//			
		//		- if the booking is "paid manual worktime"
		//			- full-day off days are *not* assigned an allotment entry
		//			- for the rest, we assign a full-day or half-day allotment oo entry as indicated in the booking form
		//				- full day markers result in half-day allotments for days that are half-day off days
		//				- half day markers are applied provided they do not clash with possible half-day off days
		//
		//		- if the booking is unpaid
		//			- all weekends, full off-days (as indicated in the user's profile) are *not* assigned an allotment oo entry
		//			- for the rest, we assign a full-day or half-day allotment oo entry as indicated in the booking form
		//				- full day markers result in half-day allotments for days that are half-day off days
		//				- half day markers are applied provided they do not clash with possible half-day off days
		//
		// before generating the oo entries, set globally applicable processing flags according to the rules just outlined
		//
		if (   ($bookingType->getPaid() == true)
			 && $booking->getAutoAssignWorktimeCredit() ) {

			$bookingMode = "paid_auto_worktime";
			$avoidWeekends = true;
			$avoidFullOffDays = true; 
			$avoidFullDayHolidays = true;
			
		}
		else if (    ($bookingType->getPaid() == true)
		 		  && (! $booking->getAutoAssignWorktimeCredit()) ) {

			$bookingMode = "paid_manual_worktime";
			$avoidWeekends = false;
			$avoidFullOffDays = true;
			$avoidFullDayHolidays = false;
		
		}
		else if ( $bookingType->getPaid() == false ) {
			
			$bookingMode = "unpaid";
			$avoidWeekends = true;
			$avoidFullOffDays = true;
			$avoidFullDayHolidays = false;
		}
		
		//
		// generate an ooentry of the proper types
		//
		//  - the bookings parametrization is represented with entries of the "MARKER" type.
		//	  these are always generated and reflect the booking's parametrization
		//
		//  - beyond the marker types, we create oo entries of type "ALLOTMENT", these indicate
		//	  the effective distribution of 
		
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			foreach ( $bookingDays as $curBookingDay ) {
				
				// make sure user's current work week is initialized prior to creating the oo entries
				// -- the call ensures that "off days" are marked as indicated in the user's profile
				$entryManager->initWorkWeek($user, $curBookingDay->getDateOfDay());
				
				//
				// determine the day's marker and allotment types
				if ( $this->isDateTimeInDateTimeArray($curBookingDay->getDateOfDay(), $halfDaysAM) ) {
					$markerType = \OOEntry::TYPE_MARKER_HALF_DAY_AM;
					$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;
				}
				else if ( $this->isDateTimeInDateTimeArray($curBookingDay->getDateOfDay(), $halfDaysPM) ) {
					$markerType = \OOEntry::TYPE_MARKER_HALF_DAY_PM;
					$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM;
				}
				else {
					$markerType = \OOEntry::TYPE_MARKER_FULL_DAY;
					$allotmentType = \OOEntry::TYPE_ALLOTMENT_FULL_DAY;
				}
				
				// create the indicated marker type
				$entryManager->createOOEntry($user, $curBookingDay->getDateOfDay(), $booking, $markerType, false);
				
				//
				// proceed and create allotment types as needed
				
				// skip current day, if it is a weekend and avoid weekend flag is set
				if ( 	(DateTimeHelper::getWeekDayNumberFromDateTime($curBookingDay->getDateOfDay()) > 5)
					 && $avoidWeekends ) {
					continue;
				}
				
				// skip current day, if it is a (full) off day and avoid off day flag is set
				if ( $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_DAY_OFF_FULL, $user)
					 && $avoidFullOffDays ) {
					continue;
				}
				
				// skip current day, if it is a full holiday day and avoid holiday flag is set
				$curBookingDayHoliday = $workplanManager->getHolidayForDay($curBookingDay);
				if ( 	( ($curBookingDayHoliday !== null) && ($curBookingDayHoliday->getFullDay()) )	
					 &&    $avoidFullDayHolidays ) {
					continue;
				}
				
					
				//
				// at this point, we've done all common processing
				// we proceed with processing specific to the booking mode
				
				// for mode "paid auto worktime", we need to kill indicated half-days that fall on a weekend,
				// a full-day holiday or a full off-day
				if ( $bookingMode == "paid_auto_worktime" ) {
					
					//
					// full-day and half-day allotments need to be processed slightly differently
					if ( $allotmentType == \OOEntry::TYPE_ALLOTMENT_FULL_DAY ) {
						
						// skip, if the current day is a (half-day) off day and a pm half-day holiday
						if (	$tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user)
							 &&	( ($curBookingDayHoliday !== null) && $curBookingDayHoliday->getHalfDay()) ) {
							continue;
						}
						// create a half-day allotment if the day is a half-day holiday
						// -- as half-day holidays always fall on afternoons, we create an am allotment
						else if ( ($curBookingDayHoliday !== null) && $curBookingDayHoliday->getHalfDay() ) {
							// set allotment to half-day am
							$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;
						}
						// create pm oo entry if the day is an am half-day off-day
						else if ( $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user) ) {
							// set allotment type to half-day pm
							$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM;
						}
						// create am oo entry if the day is a pm half-day off-day
						else if ( $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
							// set allotment type to half-day am
							$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;
						}
		
					}
					// process half-day allotment
					else {
		
						// skip, in case an PM allotment clashes with half-day holiday (the latter always fall on the PM side)
						if ( 	(($curBookingDayHoliday !== null) && $curBookingDayHoliday->getHalfDay()) 
							 && ($allotmentType == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)) {
							continue;
						}
						// skip, in case an AM allotment clashes with an AM half-day off day
						else if (	($allotmentType == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
								  && $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user) ) {
							continue;
						}
						// skip, in case an PM allotment clashes with an PM half-day off day
						else if (	($allotmentType == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
								  && $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
							continue;
						}
						
					}
					
				}
				// modes "paid manual worktime" and "unpaid", essentially do the same as above, except that they
				// don't book around holidays
				else if ( 	   ($bookingMode == "paid_manual_worktime")
							|| ($bookingMode == "unpaid") ) {
					
					//
					// full-day and half-day allotments need to be processed slightly differently
					if ( $allotmentType == \OOEntry::TYPE_ALLOTMENT_FULL_DAY ) {
							
						// create pm allotment oo entry if the day is an am half-day off-day
						if ( $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user) ) {
							// set allotment type to half-day pm
							$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM;
						}
						// create am allotment oo entry if the day is a pm half-day off-day
						else if ( $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
							// set allotment type to half-day am
							$allotmentType = \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM;
						}
		
					}
					// process half-day allotment
					else {
						// skip, in case an AM allotment clashes with an AM half-day off day
						if (	($allotmentType == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
							  && $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user) ) {
							continue;
						}
						// skip, in case an PM allotment clashes with an PM half-day off day
						else if (	($allotmentType == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
								  && $tagManager->dayHasTag($curBookingDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user) ) {
							continue;
						}
						
					}
					
				}
				
				// create the indicated allotment type
				$entryManager->createOOEntry($user, $curBookingDay->getDateOfDay(), $booking, $allotmentType, false);
							
			}
			
			// commit TX
			$con->commit();	
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:createOOEntriesForBooking() - a database error has occurred while while attempting to create the oo entried for the booking with id: " . $booking->getId(), 0, $ex);
		}
		
	}
	
		
	/**
	 *  Creates an oo request 
	 *
	 *  @param 	User				$user					- the user for which to create the request
	 *  @param 	BookingType			$bookingType			- the oo booking type to use
	 *  @param 	DateTime			$fromDate				- the start date of the requested oo period 
	 *  @param 	DateTime			$untilDate				- the end date of the requested oo period 
	 *  @param  array				$halfDaysAM				- an array of DateTime objects, indicating AM half days in the indicated range 
	 *  @param  array				$halfDaysPM				- an array of DateTime objects, indicating PM half days in the indicated range 
	 *  @param  string				$originatorComment		- the originator's comment regarding the request
	 */
	public function createOORequest($user, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM, $originatorComment) {
			
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// create the oo booking for the request
			$booking = $this->createOOBooking(	$user,
												$bookingType,
												$fromDate,
												$untilDate,
												$halfDaysAM,
												$halfDaysPM,
												null
											 );
			
			//
			// now set up request
			$newRequest = new \OORequest();
		
			// requests start out in "open" status
			$newRequest->setStatus(\OORequest::STATUS_OPEN);
			$newRequest->setOriginatorComment($originatorComment);
			
			$booking->setOORequest($newRequest);

			// persist booking
			$booking->save();

			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "OOManager:createOORequest() - created oo request for user: " . $user->getLogin());		
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:createOORequest() - a database error has occurred while while attempting to create a new oo request.", 0, $ex);
		}
		
	}
	
	
	/**
	 *  Updates an oo request 
	 *
	 *  @param 	OORequest			$request					- the update target
	 *  @param 	string				$status						- the status to set
	 *  @param 	BookingType			$bookingType				- the oo booking type to use
	 *  @param 	DateTime			$fromDate					- the start date of the requested oo period 
	 *  @param 	DateTime			$untilDate					- the end date of the requested oo period 
	 *  @param  array				$halfDaysAM					- an array of DateTime objects, indicating AM half days in the indicated range 
	 *  @param  array				$halfDaysPM					- an array of DateTime objects, indicating PM half days in the indicated range 
	 *  @param  string				$originatorComment			- the originator's comment regarding the request
	 *  @param  boolean				$autoAssignWorktimeCredit	- whether the system is to automatically assign worktime credit (applies only to bookings of paid type)
	 */
	public function updateOORequest($request, $status, $bookingType, $fromDate, $untilDate, $halfDaysAM, $halfDaysPM, $originatorComment, $autoAssignWorktimeCredit=null) {
			
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OORequestPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
		
		try {
			
			//
			// update the request
			
			$request->setStatus($status);
			$request->setOriginatorComment($originatorComment);	
			
			// update the associated oo booking
			$this->updateOOBooking(		
										$request->getOOBooking(),
										$bookingType,
										$fromDate,
										$untilDate,
										$halfDaysAM,
										$halfDaysPM,
										$autoAssignWorktimeCredit
									);
			
			//
			// persist
			$request->save();
		
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "OOManager:updateOORequest() - updated oo request with ID: " . $request->getId());	
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:updateOORequest() - a database error has occurred while while attempting to update the oo request with id: " . $request->getId(), 0, $ex);
		}
		
		return $request;
	}
	
	
	/**
	 *  Updates an oo request's status
	 *
	 *  @param 	OORequest		$request		- the update target
	 *  @param 	string			$status			- the status to set
	 */
	public function updateOORequestStatus($request, $status) {
			
		try {
			// set status
			$request->setStatus($status);
		
			// persist
			$request->save();
		
			log_message('info', "OOManager:updateOORequestStatus() - updated status for oo request with ID: " . $request->getId());	
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("OOManager:updateOORequestStatus() - a database error has occurred while while attempting to update the status of oo request with id: " . $request->getId(), 0, $ex);
		}
		
	}
	
	
	/**
	 * Deletes the indicated "OORequest"
	 * 
	 * @param 	OORequest	$request	
	 *
	 * @throws MomoException
	 */
	public function deleteOORequest($request) {
		
		//
		// delete is permissible only if request has status "denied"
		if ( ($request->getStatus() == \OORequest::STATUS_DENIED) ) {
			
			// all ok, delete the request / booking	
			$this->deleteOOBooking($request->getOOBooking());

			log_message('info', "OOManager:deleteOORequest() - deleted oo request with ID: " . $request->getId());	
		}
		else {
			// illegal delete, throw exception
			throw new InvalidStateException("OOManager:deleteOORequest() - unable to delete OORequest as it is not in state 'denied'. OORequest has id: " . $request->getId());
		}			
		
	}
	
	
	/**
	 * Deletes the indicated "OOBooking"
	 * 
	 * Deleting a booking entails:
	 * 
	 * 	- deletion of the OORequest
	 *  - deletion of the OOBooking
	 *  - deletion of the OOEntries
	 * 
	 * @param 	OOBooking	$booking	
	 *
	 * @throws MomoException
	 */
	public function deleteOOBooking($booking) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {

			// delete associated oo entries
			$booking->getOOEntrys()->delete();				
							
			// delete booking and request via cascade
			$booking->delete();
			
			// commit TX
			$con->commit();
			
			log_message('info', "OOManager:deleteOOBooking() - deleted oo booking with ID: " . $booking->getId());
		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("OOManager:deleteOOBooking() - a database error has occurred while while attempting to delete the booking with id: " . $booking->getId(), 0, $ex);
		}		
		
	}
	
	
	/**
	 *  Checks, whether the user may access "requests" functionality.
	 *  
	 *  This is a context specific check. Irrespective of the user's permissions, the sending of
	 *  requests makes sense only if certain requirements vis-a-vis the user's role and team membership
	 *  are met.
	 *  
	 *  More specifically, a user may may access "requests" functionality:
	 *  
	 *  if the user _is not_ of role "manager" or "administrator"
	 *  
	 *		_and_
	 *			the user is a primary member of some team
	 *
	 *		_and_ 
	 *			the user is not team leader of that primary team
	 *
	 *			_or_
	 *  			the user is member of a higher order team
	 *  
	 * 				_and_
	 * 					the user is not team leader of the highest order team found
	 * 
	 *  @param 	User		$user			- the DateTime to search for
	 *  
	 *  @return	boolean
	 */
	public function userMayAccessOORequests($user) {
		
		$result = false;
		
		$teamManager = $this->getCtx()->getTeamManager();
		$activeUser = $this->getCtx()->getUser();
		
		// check role constraint
		if ( ($user->getRole() !== Roles::ROLE_ADMINISTRATOR) && ($user->getRole() !== Roles::ROLE_MANAGER) ) {
			
			// check primary membership constraint
			if ( $user->isUserPrimaryMemberOfSomeTeam() ) {
				
				// get user's primary team
				$primaryTeam = $user->getPrimaryTeam();
				
				// check team leader constraint
				if ( ! $user->isUserTeamLeaderOfTeam($primaryTeam) ) {
					$result = true;
				}
				else {
					
					// retrieve highest order team membership
					$highestOrderTeam = $teamManager->getHighestOrderTeamMembershipForUser($user, $primaryTeam);
					
					// grant access, if highest order team is defined and not equal to the primary team
					// and the user is not team leader of that highest order team
					if ( 		($highestOrderTeam !== null)
							&& 	($highestOrderTeam->getId() !== $primaryTeam->getId())
							&&	( ! $user->isUserTeamLeaderOfTeam($highestOrderTeam) ) ) {
								
						$result = true;
					}
				
				}
		
			}
		}
		
		return $result;
		
	}
	

	/**
	 *  Indicates whether a given DateTime is contained in an array of DateTime objects
	 *  A DateTime value is contained in the array, if the DateTime's time value matches that of one of the array elements.
	 * 
	 *  @param 	DateTime		$searchDateTime			- the DateTime to search for
	 *  @param 	array			$dateTimeArray			- the array of DateTime objects to search
	 *  
	 *  @return	boolean
	 */
	private function isDateTimeInDateTimeArray($searchDateTime, $dateTimeArray) {
		
		$result = false;
		
		foreach ( $dateTimeArray as $curDateTime ) {
					
			if ( $curDateTime->getTimestamp() ==  $searchDateTime->getTimestamp() ) {
				$result = true;
				break;
			}
			
		}
		
		return $result;
		
	}

}