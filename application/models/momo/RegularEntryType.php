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
 * The Momo extension to the Propel generated RegularEntryType class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class RegularEntryType extends BaseRegularEntryType {

	//
	// define the internal keys for the entry types
	const CREATOR_SYSTEM 	= "CREATOR_SYSTEM";
	const CREATOR_USER	 	= "CREATOR_USER";
		
	/**
	 * Indicates whether the type is in use somewhere
	 * 
	 * @return boolean
	 */
	public function isInUse() {
		$usingEntries = \RegularEntryQuery::create()
							->filterByRegularEntryType($this)
							->find();
	
		return $usingEntries->count() != 0 ? true : false;
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
		
		$digest["entry_type"] = array();
		$digest["entry_type"]["item_name"] = "Entry Type";
		$digest["entry_type"]["item_value"] = $this->getType(); 
		
		$digest["worktime_credit"] = array();
		$digest["worktime_credit"]["item_name"] = "Worktime Credit";
		$digest["worktime_credit"]["item_value"] = ( $this->getWorkTimeCreditAwarded() ? "true" : "false");
		
		$digest["enabled"] = array();
		$digest["enabled"]["item_name"] = "Enabled";
		$digest["enabled"]["item_value"] = ( $this->getEnabled() ? "true" : "false" );
		
		return $digest;		
	}
	
} // RegularEntryType
