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

use momo\core\security\Roles;
use momo\core\helpers\FormHelper;
use momo\core\helpers\DateTimeHelper;

/**
 * The Momo extension to the Propel generated User class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class User extends BaseUser {
	
	//
	// user type constants 
	const TYPE_STUDENT 		= "TYPE_STUDENT";
	const TYPE_STAFF 		= "TYPE_STAFF";
	
	//
	// map internal type keys to user friendly descriptions
	public static $TYPE_MAP = array(
		User::TYPE_STAFF 		=> "Staff",
		User::TYPE_STUDENT 		=> "Student"
	);
	
	
	/**
	 * Returns the user's full name
	 */
	public function getFullName() {
		return $this->getFirstName() . " " . $this->getLastName();
	}
	
	
	/**
	 * Returns the "day off" value for the indicated weekday number.
	 * 
	 * @param	integer		$weekDayNumber (mon=1, ..., sun=7)
	 * 
	 * @return 	string		(or null) if the indicated day has no value
	 */
	public function getDayOffValueForWeekDayNumber($weekDayNumber) {
		
		$result = null;
		
		// unserialize the off day value
		$dayOffValueArray = unserialize($this->getOffDays());

		// if the weekday has a value, extract it
		if ( ($dayOffValueArray != false)  && isset($dayOffValueArray[$weekDayNumber]) ) {
			$result = $dayOffValueArray[$weekDayNumber];
		}
					
		return $result;
		
	}

	
	/**
	 * Returns a human-readable compilation of the user's off-days
	 * 
	 * @return 	string	- the off days in human readable form, or null if there are no off-days
	 */
	public function getDayOffDaysInPrettyStringFormat() {
		//
		// prepare a string describing the off days
		$offDaysAsString = null;
		
		// compile the string
		foreach ( FormHelper::$workDayOptions as $curWorkDayValue => $curWorkDayText ) {
			
			if ( $this->getDayOffValueForWeekDayNumber($curWorkDayValue) !== null ) {
				
				if ( $offDaysAsString == null ) {
					$offDaysAsString = "";
				}
				
				$offDaysAsString .= FormHelper::$workDayOptions[$curWorkDayValue] . " ";
			}
		}
		
		return $offDaysAsString;
	}
	
	

	
	/**
	 * Indicates whether the user has any off days defined
	 * 
	 * @return 	boolean
	 */
	public function hasOffDays() {
		
		$result = false;
		
		// unserialize the off day value
		$dayOffValueArray = unserialize($this->getOffDays());

		// if the weekday has a value, extract it
		if ( ($dayOffValueArray != false) && (count($dayOffValueArray) != 0) ) {
			
			$result = true;
		
		}
					
		return $result;
		
	}
	
	
	/**
	 * Retrieves the user's primary team
	 * 
	 * @return Team (or null)
	 */
	public function getPrimaryTeam() {
		return \TeamQuery::create()
					->filterByUser($this)
					->useTeamUserQuery()
						->filterByPrimary(true)
					->endUse()
					->findOne();
	}
	
	
	/**
	 * Retrieves the user's secondary teams
	 * 
	 * @param	$user
	 * 
	 * @return PropelCollection
	 */
	public function getSecondaryTeams() {
		return \TeamQuery::create()
					->filterByUser($this)
					->useTeamUserQuery()
						->filterBySecondary(true)
					->endUse()
					->find();
	}
	
	
	/**
	 * States whether the user is a primary member of some team other than the indicated one
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isUserPrimaryMemberSomeOtherTeam($team) {
		
		$userTeamRecords = \TeamUserQuery::create()
								->filterByUser($this)
								->filterByTeam($team, \Criteria::NOT_EQUAL)
								->filterByPrimary(true)
							->find();
												
		return $userTeamRecords->count() != 0 ? true : false;	
					
	}
	
	
	/**
	 * States whether the user is a primary member of the indicated team
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isUserPrimaryMemberOfTeam($team) {
		$userTeamRecord = \TeamUserQuery::create()
								->filterByUser($this)
								->filterByTeam($team)
								->filterByPrimary(true)
							->findOne();
							
		return $userTeamRecord !== null ? true : false;
	}
	
	
	/**
	 * States whether the user is a primary member of any team
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isUserPrimaryMemberOfSomeTeam() {
		$userTeamRecords = \TeamUserQuery::create()
								->filterByUser($this)
								->filterByPrimary(true)
							->find();
							
		return $userTeamRecords->count() != 0 ? true : false;
	}
	
	
	
	/**
	 * States whether the user is a primary member of the indicated team
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isUserSecondaryMemberOfTeam($team) {
		$userTeamRecord = \TeamUserQuery::create()
								->filterByUser($this)
								->filterByTeam($team)
								->filterBySecondary(true)
							->findOne();
		
		return $userTeamRecord !== null ? true : false;
	}
	
	
	/**
	 * States whether the user is a team leader of the indicated team
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isUserTeamLeaderOfTeam($team) {
		
		$userTeamRecord = \TeamUserQuery::create()
								->filterByUser($this)
								->filterByTeam($team)
								->filterByLeader(true)
							->findOne();
							
		return $userTeamRecord !== null ? true : false;						
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
		
		$digest["first_name"] = array();
		$digest["first_name"]["item_name"] = "First Name";
		$digest["first_name"]["item_value"] = $this->getFirstname(); 
		
		$digest["last_name"] = array();
		$digest["last_name"]["item_name"] = "Last Name";
		$digest["last_name"]["item_value"] = $this->getLastname(); 
		
		$digest["email"] = array();
		$digest["email"]["item_name"] = "Email";
		$digest["email"]["item_value"] = $this->getEmail(); 
		
		$digest["birthdate"] = array();
		$digest["birthdate"]["item_name"] = "Birthdate";
		$digest["birthdate"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getBirthdate()); 
		
		$digest["type"] = array();
		$digest["type"]["item_name"] = "Type";
		$digest["type"]["item_value"] = \User::$TYPE_MAP[$this->getType()]; 
		
		$digest["login"] = array();
		$digest["login"]["item_name"] = "Login";
		$digest["login"]["item_value"] = $this->getLogin(); 
		
		$digest["workload"] = array();
		$digest["workload"]["item_name"] = "Workload";
		$digest["workload"]["item_value"] = sprintf("%d%%", 100 * $this->getWorkload()); 
		
		$digest["off_days"] = array();
		$digest["off_days"]["item_name"] = "Off Days";
		$digest["off_days"]["item_value"] = ($this->getDayOffDaysInPrettyStringFormat() !== null ? $this->getDayOffDaysInPrettyStringFormat() : "(none)"); 
		
		$digest["entry_date"] = array();
		$digest["entry_date"]["item_name"] = "Entry Date";
		$digest["entry_date"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getEntryDate()); 
		
		$digest["exit_date"] = array();
		$digest["exit_date"]["item_name"] = "Exit Date";
		$digest["exit_date"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getExitDate()); 
		
		$digest["primary_team"] = array();
		$digest["primary_team"]["item_name"] = "Primary Team";
		$digest["primary_team"]["item_value"] = ($this->getPrimaryTeam() !== null ? $this->getPrimaryTeam()->getName() : "(none)"); 
		
		$digest["role"] = array();
		$digest["role"]["item_name"] = "Role";
		$digest["role"]["item_value"] = Roles::$ROLE_MAP[$this->getRole()]; 
		
		$digest["enabled"] = array();
		$digest["enabled"]["item_name"] = "Enabled";
		$digest["enabled"]["item_value"] = ($this->getEnabled() ? "true" : "false"); 
		
		return $digest;		
	}

} // User
