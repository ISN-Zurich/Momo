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
 * The Momo extension to the Propel generated AdjustmentEntry class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class AdjustmentEntry extends BaseAdjustmentEntry {

	//
	// adjustment entry type constants 
	const TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS  			= "TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS";			// signifies the annual vacation credit awarded to users
	const TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS  		= "TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS";			// signifies a vacation balance adjustment due to lapse policy
	const TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS  	= "TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS";		// signifies an overtime balance adjustment due to lapse policy
	
	const TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS  			= "TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS";				// signifies a general adjustment to a user's vacation balance
	const TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS 			= "TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS";			// signifies a general adjustment to a user's work time balance
	
	//
	// adjustment entry creator constants 
	const CREATOR_SYSTEM  	= "CREATOR_SYSTEM";					// used if the adjustment was made by the system
	const CREATOR_USER 		= "CREATOR_USER";					// used if the adjustment was made by a user
	
	//
	// adjustment entry unit constants 
	const UNIT_SECONDS  	= "UNIT_SECONDS";				
	const UNIT_DAYS 		= "UNIT_DAYS";	
	
	//
	// map internal type keys to various meta data values
	//
	public static $TYPE_MAP = array(
	
		AdjustmentEntry::TYPE_VACATION_BALANCE_ANNUAL_CREDIT_IN_DAYS 	=> array(
																				"prettyTypeName" 				=> "Annual Vacation Credit",
																				"prettyDisplayUnitNameSingular"	=> "day",
																				"units"							=> AdjustmentEntry::UNIT_DAYS,
																				"allowedCreators"				=> array( AdjustmentEntry::CREATOR_SYSTEM )
																			),
																				
		AdjustmentEntry::TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS 		=> array(
																				"prettyTypeName" 				=> "Vacation Adjustment",
																				"prettyDisplayUnitNameSingular"	=> "day",
																				"units"							=> AdjustmentEntry::UNIT_DAYS,
																				"allowedCreators"				=> array( AdjustmentEntry::CREATOR_SYSTEM, AdjustmentEntry::CREATOR_USER )
																			),
																				 
		AdjustmentEntry::TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS 	=> array(
																				"prettyTypeName" 				=> "Worktime Adjustment",
																				"prettyDisplayUnitNameSingular"	=> "hour",
																				"units"							=> AdjustmentEntry::UNIT_SECONDS,
																				"allowedCreators"				=> array( AdjustmentEntry::CREATOR_SYSTEM, AdjustmentEntry::CREATOR_USER )
																			),

		AdjustmentEntry::TYPE_WORKTIME_BALANCE_LAPSE_ADJUSTMENT_IN_SECONDS 	=> array(
																				"prettyTypeName" 				=> "Worktime Lapse Adjustment",
																				"prettyDisplayUnitNameSingular"	=> "hour",
																				"units"							=> AdjustmentEntry::UNIT_SECONDS,
																				"allowedCreators"				=> array( AdjustmentEntry::CREATOR_SYSTEM )
																			),
																			
		AdjustmentEntry::TYPE_VACATION_BALANCE_LAPSE_ADJUSTMENT_IN_DAYS 	=> array(
																				"prettyTypeName" 				=> "Vacation Lapse Adjustment",
																				"prettyDisplayUnitNameSingular"	=> "day",
																				"units"							=> AdjustmentEntry::UNIT_DAYS,
																				"allowedCreators"				=> array( AdjustmentEntry::CREATOR_SYSTEM )
																			)																			
	);
	
	//
	// map internal type keys to user friendly descriptions
	//
	public static $CREATOR_MAP = array(
		AdjustmentEntry::CREATOR_SYSTEM 	=> "system",
		AdjustmentEntry::CREATOR_USER 		=> "management",
	);
	
	
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
	
		$digest["user"] = array();
		$digest["user"]["item_name"] = "User";
		$digest["user"]["item_value"] = $this->getUser()->getFullName(); 
		
		$digest["type"] = array();
		$digest["type"]["item_name"] = "Type";
		$digest["type"]["item_value"] = \AdjustmentEntry::$TYPE_MAP[$this->getType()]["prettyTypeName"]; 
		
		
		// figure display string for adjustment value
		if ( \AdjustmentEntry::$TYPE_MAP[$this->getType()]["units"] == \AdjustmentEntry::UNIT_SECONDS ) {
			$displayValue = sprintf("%+.2f", ($this->getValue() / 3600)) . " " . \AdjustmentEntry::$TYPE_MAP[$this->getType()]["prettyDisplayUnitNameSingular"];
			$displayValue .= (($this->getValue() / 3600) != 1) ? "s" : "";
		}
		else if ( \AdjustmentEntry::$TYPE_MAP[$this->getType()]["units"] == \AdjustmentEntry::UNIT_DAYS ) {
			$displayValue = sprintf("%+.2f", $this->getValue()) . " " . \AdjustmentEntry::$TYPE_MAP[$this->getType()]["prettyDisplayUnitNameSingular"];
			$displayValue .= ($this->getValue() != 1) ? "s" : "";
		}
		
		$digest["value"] = array();
		$digest["value"]["item_name"] = "Value";
		$digest["value"]["item_value"] = $displayValue; 
		
		$digest["effective_per"] = array();
		$digest["effective_per"]["item_name"] = "Effective per";
		$digest["effective_per"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getDay()->getDateOfDay()); 
		
		$digest["reason"] = array();
		$digest["reason"]["item_name"] = "Reason";
		$digest["reason"]["item_value"] = $this->getReason(); 
		
		return $digest;		
	}
	
	
} // AdjustmentEntry
