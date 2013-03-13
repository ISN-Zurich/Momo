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
 * The Momo extension to the Propel generated Team class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
use momo\core\helpers\StringHelper;

class Team extends BaseTeam {

	/**
	 * Returns the users that are designated team leaders for the team
	 * 
	 * @return 	PropelCollection
	 */
	public function getTeamLeaders() {
		return \UserQuery::create()
					->useTeamUserQuery()
						->filterByTeam($this)
						->filterByLeader(true)
					->endUse()
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Returns the team's members
	 * 
	 * Note: Members are users with either primary or secondary team membership
	 * 
	 * @return 	PropelCollection
	 */
	public function getTeamMembers() {
		return \UserQuery::create()
					->useTeamUserQuery()
						->filterByTeam($this)
						->filterByPrimary(true)
						->_or()
						->filterBySecondary(true)
					->endUse()
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Returns the team's primary members
	 *
	 * @return 	PropelCollection
	 */
	public function getPrimaryTeamMembers() {
		return \UserQuery::create()
					->useTeamUserQuery()
						->filterByTeam($this)
						->filterByPrimary(true)
					->endUse()
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Returns the team's secondary members
	 *
	 * @return 	PropelCollection
	 */
	public function getSecondaryTeamMembers() {
		return \UserQuery::create()
					->useTeamUserQuery()
						->filterByTeam($this)
						->filterBySecondary(true)
					->endUse()
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Returns the email addresses of the designated team leaders
	 * 
	 * @return 	array	- an array of team leader email addresses
	 */
	public function getTeamLeaderEmailAddresses() {
		
		$result = array();
		
		$teamLeaders = $this->getTeamLeaders();
		
		foreach ( $teamLeaders as $curLeader ) {
			$result[] = $curLeader->getEmail();
		}
		
		return $result;
	}
	
	
	/**
	 * Returns the full names of the designated team leaders
	 * 
	 * @return 	array	- an array of team leader full names
	 */
	public function getTeamLeaderFullNames() {
		
		$result = array();
		
		$teamLeaders = $this->getTeamLeaders();
		
		foreach ( $teamLeaders as $curLeader ) {
			$result[] = $curLeader->getFullName();
		}
		
		return $result;
	}
	
	
	/**
	 * Returns the full names of the primary members
	 * 
	 * @return 	array	- an array of  primary member full names
	 */
	public function getPrimaryMemberFullNames() {
		
		$result = array();
		
		$primaryMembers = $this->getPrimaryTeamMembers();
		
		foreach ( $primaryMembers as $curUser ) {
			$result[] = $curUser->getFullName();
		}
		
		return $result;
	}
	
	
	/**
	 * Returns the full names of the secondary members
	 * 
	 * @return 	array	- an array of secondary member full names
	 */
	public function getSecondaryMemberFullNames() {
		
		$result = array();
		
		$secondaryMembers = $this->getSecondaryTeamMembers();
		
		foreach ( $secondaryMembers as $curUser ) {
			$result[] = $curUser->getFullName();
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
		
		$digest["name"] = array();
		$digest["name"]["item_name"] = "Team Name";
		$digest["name"]["item_value"] = $this->getName(); 
		
		$digest["parent_team"] = array();
		$digest["parent_team"]["item_name"] = "Parent Team ID";
		$digest["parent_team"]["item_value"] = ($this->getParentId() !== null ? $this->getParentId() : "(none)"); 
		
		$digest["team_leaders"] = array();
		$digest["team_leaders"]["item_name"] = "Team Leaders";
		$digest["team_leaders"]["item_value"] = ($this->getTeamLeaders()->count() > 0 ? implode(", ", $this->getTeamLeaderFullNames()) : "(none)"); 
		
		$digest["primary_members"] = array();
		$digest["primary_members"]["item_name"] = "Primary Members";
		$digest["primary_members"]["item_value"] = ($this->getPrimaryTeamMembers()->count() > 0 ? implode(", ", $this->getPrimaryMemberFullNames()) : "(none)"); 
		
		$digest["secondary_members"] = array();
		$digest["secondary_members"]["item_name"] = "Secondary Members";
		$digest["secondary_members"]["item_value"] = ($this->getSecondaryTeamMembers()->count() > 0 ? implode(", ", $this->getSecondaryMemberFullNames()) : "(none)"); 
		
		return $digest;		
	}
	
} // Team
