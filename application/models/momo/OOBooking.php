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

/**
 * The Momo extension to the Propel generated OOBooking class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class OOBooking extends BaseOOBooking {
	
	//
	// class constants 
	const ORIGIN_USER_PRETTY 		= "user request";
	const ORIGIN_MANAGEMENT_PRETTY 	= "management";
	
	
	/**
	 * Retrieves the booking's start date
	 * 
	 * Note: A booking's start date is defined by its earliest marker oo entry
	 * 
	 * @return DateTime
	 */
	public function getStartDate() {
		//
		// query for booking's ooentries, in ascending date order
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
								->_or()
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
								->_or()
								->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
								->useDayQuery()
									->orderByDateOfday(\DayQuery::ASC)
								->endUse()
								->find();
								
		// return date of first entry in collection, it indicates the start of the booking
		return $entries->getFirst()->getDay()->getDateOfDay();
						
	}
	
	
	/**
	 * Retrieves the booking's end date
	 * 
	 * Note: A booking's end date is defined by its latest marker oo entry
	 * 
	 * @return DateTime
	 */
	public function getEndDate() {
		//
		// query for booking's ooentries, in descending date order
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
								->_or()
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
								->_or()
								->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
								->useDayQuery()
									->orderByDateOfday(\DayQuery::DESC)
								->endUse()
								->find();
								
		// return date of first entry in collection (desc ordered), it indicates the end of the booking
		return $entries->getFirst()->getDay()->getDateOfDay();
	}
	
	
	/**
	 * Retrieves the booking's AM half-day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getHalfDayMarkerEntriesAM() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
								->find();
								
		return $entries;
	}
	
	
	/**
	 * Retrieves the booking's PM half-day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getHalfDayMarkerEntriesPM() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
								->find();
								
		return $entries;
	}
	
	
	/**
	 * Retrieves the booking's full day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getFullDayMarkerEntries() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
								->find();
								
		return $entries;
	}
	
	
/**
	 * Retrieves the booking's AM half-day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getHalfDayAllotmentEntriesAM() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
								->find();
								
		return $entries;
	}
	
	
	/**
	 * Retrieves the booking's PM half-day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getHalfDayAllotmentEntriesPM() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
								->find();
								
		return $entries;
	}
	
	
	/**
	 * Retrieves the booking's full day marker entries
	 * 
	 * @return PropelCollection
	 */
	public function getFullDayAllotmentEntries() {
		$entries = \OOEntryQuery::create()
								->filterByOOBooking($this)
								->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
								->find();
								
		return $entries;
	}
	
	

	/**
	 * Computes the sum of the allotments that make up the booking.
	 *  
	 * Bookings are comprised of "markers" and "allotments". While the former delineates
	 * the extent of the scheduled booking, the latter indicates where in the booking's range
	 * of dates the absence formally applies.
	 * 
	 * As an example: If one books vacation from monday to sunday, the bookings "marker"
	 * entries will extend across the entire week, whereas the allotment entries will only
	 * extend from monday to friday.
	 * 
	 * Hence, the sum of the allotments for a booking indicates the number of days that
	 * the booking formally applies to. In the case of above example, the allotment sum
	 * would be "5 days", whereas the booking's range is "7 days".
	 *  
	 *  @return	float
	 */
	public function getAllotmentSum() {
		
		$result = 0;
		
		$allotmentFullDayQuery = \OOEntryQuery::create()
									->useOOBookingQuery()
										->filterById($this->getId())
									->endUse()
									->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
									->withColumn('COUNT(*)', 'totalFullDayAllotments')
									->groupBy('OOBooking.id')
									->findOne();
									
		$allotmentHalfDayQuery = \OOEntryQuery::create()
									->useOOBookingQuery()
										->filterById($this->getId())
									->endUse()
									->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
									->_or()
									->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
									->withColumn('COUNT(*)', 'totalHalfDayAllotments')
									->groupBy('OOBooking.id')
									->findOne();

									
		if ( $allotmentFullDayQuery !== null ) {
			$result += $allotmentFullDayQuery->getTotalFullDayAllotments(); 
		}		

		if ( $allotmentHalfDayQuery !== null ) {
			$result += 0.5 * $allotmentHalfDayQuery->getTotalHalfDayAllotments();				 
		}		
								
		return $result;			
									
	}
	
	
	/**
	 * Compiles a digest of key/value pairs representing the object's state
	 * 
	 * @return array (
	 * 					{STATE_ITEM_KEY_1} => array(
	 * 												"item_pretty_name"	=> {string},
	 * 												"item_value"		=> {string}
	 * 											)
	 * 
	 * 					...
	 * 
	 * 					{STATE_ITEM_KEY_N} => array(
	 * 												"item_pretty_name"	=> {string},
	 * 												"item_value"		=> {string}
	 * 											)
	 * 				 ) 
	 */
	public function compileStateDigest() {
		
		$digest = array();
		
		//
		// the half day data require some massaging for inclusion in the digest
		$amHalfDayOOEntries = $this->getHalfDayMarkerEntriesAM();
		$pmHalfDayOOEntries = $this->getHalfDayMarkerEntriesPM();
		
		// generate an array of pretty date strings for each of the half-day collections
		$amHalfDayPrettyDateArray = array();
		foreach ( $amHalfDayOOEntries as $curOOEntry ) {
			$amHalfDayPrettyDateArray[] = DateTimeHelper::formatDateTimeToPrettyDateFormat($curOOEntry->getDay()->getDateOfDay()); 
		}
		
		$pmHalfDayPrettyDateArray = array();
		foreach ( $pmHalfDayOOEntries as $curOOEntry ) {
			$pmHalfDayPrettyDateArray[] = DateTimeHelper::formatDateTimeToPrettyDateFormat($curOOEntry->getDay()->getDateOfDay()); 
		}
		
		// the booking type needs to be massaged as well
		$prettyOOBookingTypeName = $this->getOOBookingType();
		if ( $this->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
			$prettyOOBookingTypeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$this->getOOBookingType()->getType()];
		}
		
		//
		// populate the digest
		$digest["user"] = array();
		$digest["user"]["item_name"] = "User";
		$digest["user"]["item_value"] = $this->getUser()->getFullName(); 
		
		$digest["booking_type"] = array();
		$digest["booking_type"]["item_name"] = "Booking Type";
		$digest["booking_type"]["item_value"] = $prettyOOBookingTypeName; 
		
		$digest["from_date"] = array();
		$digest["from_date"]["item_name"] = "From";
		$digest["from_date"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getStartDate()); 
			
		$digest["until_date"] = array();
		$digest["until_date"]["item_name"] = "Until";
		$digest["until_date"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getEndDate()); 
		
		$digest["half_days_am"] = array();
		$digest["half_days_am"]["item_name"] = "AM Half-Days";
		$digest["half_days_am"]["item_value"] = ( count($amHalfDayPrettyDateArray) > 0 ) ? $amHalfDayPrettyDateArray : "(none)"; 
		
		$digest["half_days_pm"] = array();
		$digest["half_days_pm"]["item_name"] = "PM Half-Days";
		$digest["half_days_pm"]["item_value"] = ( count($pmHalfDayPrettyDateArray) > 0 ) ? $pmHalfDayPrettyDateArray : "(none)";  
		
		$digest["auto_assign_worktime_credit"] = array();
		$digest["auto_assign_worktime_credit"]["item_name"] = "Auto Worktime Credit";
		$digest["auto_assign_worktime_credit"]["item_value"] = ( $this->getAutoAssignWorktimeCredit() ? "yes" : "no" ); 
		
		$digest["request_status"] = array();
		$digest["request_status"]["item_name"] = "Request Status";
		$digest["request_status"]["item_value"] = ( $this->getOORequest() !== null ? \OORequest::$STATUS_MAP[$this->getOORequest()->getStatus()] : "(not applicable)" ); 
		
		return $digest;		
	}
	
} // OOBooking
