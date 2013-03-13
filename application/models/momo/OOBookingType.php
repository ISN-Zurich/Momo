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

/**
 * The Momo extension to the Propel generated OOBookingType class
 *
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class OOBookingType extends BaseOOBookingType {

	//
	// define the internal keys for the entry type creators
	const CREATOR_SYSTEM 	= "CREATOR_SYSTEM";
	const CREATOR_USER	 	= "CREATOR_USER";
	
	//
	// define the internal keys for "bookable in" values
	// note: key values are as 2^n so as to facilitate bitwise logical ops
	const BOOKABLE_IN_DAYS 		= "1";
	const BOOKABLE_IN_HALFDAYS 	= "2";
	
	//
	// define the internal keys for the system oo types
	const SYSTEM_TYPE_VACATION = "SYSTEM_TYPE_VACATION";
	const SYSTEM_TYPE_UNPAID_LEAVE = "SYSTEM_TYPE_UNPAID_LEAVE";
	
	//
	// map internal "bookable in" keys to user friendly descriptions (in plural form)
	public static $BOOKABLE_IN_MAP_PLURAL = array(
		OOBookingType::BOOKABLE_IN_DAYS 		=> "days",
		OOBookingType::BOOKABLE_IN_HALFDAYS 	=> "half-days"
	);
	
	//
	// map internal "bookable in" keys to user friendly descriptions (in singular form)
	public static $BOOKABLE_IN_MAP_SINGULAR = array(
		OOBookingType::BOOKABLE_IN_DAYS 		=> "day",
		OOBookingType::BOOKABLE_IN_HALFDAYS 	=> "half-day"
	);
	
	//
	// map internal "system type" keys to user friendly descriptions
	public static $BOOKABLE_SYSTEM_TYPE_MAP = array(
		OOBookingType::SYSTEM_TYPE_VACATION => "Vacation",
		OOBookingType::SYSTEM_TYPE_UNPAID_LEAVE => "Unpaid Leave"
	);
	
	
	/**
	 * Indicates whether the type is in use somewhere
	 * 
	 * @return boolean
	 */
	public function isInUse() {
		$usingEntries = \OOBookingQuery::create()
							->filterByOOBookingType($this)
							->find();
	
		return $usingEntries->count() != 0 ? true : false;
	}
	
	
	/**
	 * Returns a pretty name for the type
	 * 
	 * For system types, this looks up an appropriate map value
	 * For user types this simply returns the value of the "type" field
	 * 
	 * @return string
	 */
	public function getPrettyTypeName() {
		
		$prettyName = null;
		
		if ( $this->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
			$prettyName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$this->getType()];
		}
		else {
			$prettyName = $this->getType();
		}
								
		return $prettyName;
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
		
		$digest["booking_type"] = array();
		$digest["booking_type"]["item_name"] = "Booking Type";
		$digest["booking_type"]["item_value"] = $this->getType(); 
		
		$digest["bookable_in_days"] = array();
		$digest["bookable_in_days"]["item_name"] = "Bookable In Days";
		$digest["bookable_in_days"]["item_value"] = ( $this->getBookableInDays() ? "true" : "false" ); 
		
		$digest["bookable_in_half_days"] = array();
		$digest["bookable_in_half_days"]["item_name"] = "Bookable In Half-Days";
		$digest["bookable_in_half_days"]["item_value"] = ( $this->getBookableInHalfDays() ? "true" : "false" ); 
		
		$digest["paid"] = array();
		$digest["paid"]["item_name"] = "Paid";
		$digest["paid"]["item_value"] = ( $this->getPaid() ? "true" : "false" ); 
		
		$digest["summary_color"] = array();
		$digest["summary_color"]["item_name"] = "Summary Color";
		$digest["summary_color"]["item_value"] = $this->getRgbColorValue(); 
		
		$digest["enabled"] = array();
		$digest["enabled"]["item_name"] = "Enabled";
		$digest["enabled"]["item_value"] = ( $this->getEnabled() ? "true" : "false" ); 
		
		return $digest;		
	}
	
} // OOBookingType
