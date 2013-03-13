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
 * The Momo extension to the Propel generated Project class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class Project extends BaseProject {

	
	/**
	 * States whether the indicated team is assigned to the project
	 * 
	 * @param	Team	$team	the team we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isAssignedTeam($team) {
		
		$projectTeamRecord = \TeamProjectQuery::create()
								->filterByProject($this)
								->filterByTeam($team)
							->findOne();
							
		return $projectTeamRecord !== null ? true : false;
	}
	
	
	/**
	 * States whether the indicated user is assigned to the project
	 * 
	 * @param	User	$user	the user we are interested in	
	 * 
	 * @return 	boolean
	 */
	public function isAssignedUser($user) {
		
		$projectUserRecord = \UserProjectQuery::create()
								->filterByProject($this)
								->filterByUser($user)
							->findOne();
							
		return $projectUserRecord !== null ? true : false;
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
		$digest["name"]["item_name"] = "Project Name";
		$digest["name"]["item_value"] = $this->getName(); 
		
		$digest["assigned_teams"] = array();
		$digest["assigned_teams"]["item_name"] = "Assigned Teams";
		$digest["assigned_teams"]["item_value"] = implode(", ", $this->getTeams()->toKeyValue("id", "name"));
		
		// build list of assigned users (full names)
		$assignedUserList = "";
		$assignedUsers = $this->getUsers();
		foreach ( $assignedUsers as $curUser ) {
			
			$assignedUserList .= $curUser->getFullName();
			
			// add separator, if we're not yet at end of collection
			if ( ! $assignedUsers->isLast() ) {
				$assignedUserList .= ", ";
			}
		}		
		
		$digest["assigned_users"] = array();
		$digest["assigned_users"]["item_name"] = "Assigned Users";
		$digest["assigned_users"]["item_value"] = $assignedUserList;
		
		$digest["enabled"] = array();
		$digest["enabled"]["item_name"] = "Enabled";
		$digest["enabled"]["item_value"] = ( $this->getEnabled() ? "true" : "false" );
		
		return $digest;		
	}
	
} // Project
