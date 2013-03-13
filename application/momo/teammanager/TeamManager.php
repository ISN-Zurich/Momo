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

namespace momo\teammanager;

use momo\core\managers\BaseManager;
use momo\core\exceptions\MomoException;
use momo\audittrailmanager\EventDescription;

/**
 * TeamManager
 * 
 * Single access point for all team related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.teammanager
 */
class TeamManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_TEAM";
	
	/**
	 * Retrieves the active teams
	 * 
	 * Note: A team is active, if "archived=0"
	 * 
	 * @return PropelCollection
	 */
	public function getActiveTeams() {
		return \TeamQuery::create()
					->filterByArchived(false)
					->orderByName()
					->find();
	}
	
	/**
	 * Retrieves a team by its id
	 * 
	 * @return Team (or null)
	 */
	public function getTeamById($teamId) {
		return \TeamQuery::create()
					->filterById($teamId)
					->findOne();
	}
	
	/**
	 * Retrieves teams by a list of ids
	 *	 
	 * @param 	array	$idList		the list of team ids
	 *
	 * @return	PropelCollection	a collection of Team objects
	 */
	public function getTeamsByIdList($idList) {
		return \TeamQuery::create()
					->filterById($idList)
					->orderByName()
					->find();
	}
	
	
	/**
	 * Retrieves all teams for which the indicated user is team leader
	 * 
	 * @param	$user
	 * 
	 * @return PropelCollection
	 */
	public function getAllTeamsLedByUser($user) {
		return \TeamQuery::create()
					->filterByUser($user)
					->useTeamUserQuery()
						->filterByLeader(true)
					->endUse()
					->orderByName()
					->find();
	}
	
	
	/**
	 * Given an initial team and user the method returns the user's highest order team membership
	 * found in the initial team's hierarchy chain. Team membership means that the user is either
	 * "primary" or "secondary" member.
	 *   
	 * @param	User	$user   
	 * @param	Team	$team  
	 *   
	 * @return 	Team (or null, in case the user is not member of a team in the indicated hierarchy chain)
	 */
	public function getHighestOrderTeamMembershipForUser($user, $team) {
		
		$highestOrderTeamMembership = null;
		
		//
		// build an array that represents the team hierarchy
		$teamHierarchy = array($team);
		
		while (true) {	
			// retrieve current end of hierarchy chain
			$curTeam = end($teamHierarchy);
		
			// query for parent team of current end of hierarchy chain
			$curParentTeam = \TeamQuery::create()
									->useTeamUserQuery()
										->filterByUser($user)
										->filterByTeamId($curTeam->getParentId())
									->endUse()
									->findOne();							
										
			// add team to hierarchy chain, or break loop if we've reached end of rope
			if ( $curParentTeam !== null ) {
				$teamHierarchy[] = $curParentTeam;
			}
			else {
				break;
			}			
		}
		
		
		// with hierarchy in hand, we search for highest order record that the user is a member of
		// search proceeds from low to high order
		foreach ( $teamHierarchy as $curTeam ) {
				// if the user is primary or secondary member of the current
				// team in hierarchy chain, we update the return value accordingly
				if ( $user->isUserPrimaryMemberOfTeam($curTeam) || $user->isUserSecondaryMemberOfTeam($curTeam) ) {
					$highestOrderTeamMembership = $curTeam;
				}
		}
		
		return $highestOrderTeamMembership;		
	}
	
	
	/**
	 *  Creates a new team 
	 *  
	 *  @param integer				$parentTeamId		the parent team id (pass null, if there is no parent team)
	 *  @param string				$teamName			the name of the team
	 *  @param PropelCollection		$primaryMembers		the team's primary members
	 *  @param PropelCollection		$secondaryMembers	the team's secondary members
	 *  @param PropelCollection		$teamLeaders		the team's team leaders 
	 *  
	 *  @return Team
	 */
	public function createTeam($parentTeamId, $teamName, $primaryMembers, $secondaryMembers, $teamLeaders) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
	
			$newTeam = new \Team();
			
			// set parent team, and retrieve a reference to it
			$newTeam->setParentId($parentTeamId);
			$parentTeam = $this->getTeamById($parentTeamId);
			
			// set name
			$newTeam->setName($teamName);
			
			// add all the primary members to the team
			foreach ( $primaryMembers as $user ) {
				$newTeam->addUser($user);
			}
			
			// add all the secondary members to the team
			foreach ( $secondaryMembers as $user ) {
				$newTeam->addUser($user);
			}
			
			// add all the secondary members to the team
			foreach ( $teamLeaders as $user ) {
				$newTeam->addUser($user);
			}
			
			// persist
			$newTeam->save();
			
			//
			// at this point, all users are related to the team
			// now, set the junction records according to type of relation
			
			//
			// first, team leaders
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($teamLeaders->getPrimaryKeys())
									->filterByTeamId($newTeam->getId())
									->find();		
											
			// set all retrieved junction records to "leader"
			foreach ( $junctionRecords as $junctionRecord ) {
				$junctionRecord->setLeader(true);
				$junctionRecord->save();
			}	
			
			
			//
			// then, primary members
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($primaryMembers->getPrimaryKeys())
									->filterByTeamId($newTeam->getId())
									->find();		
											
			// set all retrieved junction records to "leader"
			foreach ( $junctionRecords as $junctionRecord ) {
				$junctionRecord->setPrimary(true);
				$junctionRecord->save();
			}	
			
			
			//
			// finally, secondary members
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($secondaryMembers->getPrimaryKeys())
									->filterByTeamId($newTeam->getId())
									->find();
																		
											
			// set all retrieved junction records to "primary"
			foreach ( $junctionRecords as $junctionRecord ) {
				$junctionRecord->setSecondary(true);
				$junctionRecord->save();
			}
		
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "TeamManager:createTeam() - created new team: " . $teamName);
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("TeamManager:createTeam() - a database error has occurred while while attempting to create the team: " . $teamName, 0, $ex);
		}
		
		return $newTeam;
	}
	
	/**
	 *  Updates a team 
	 *  
	 *  @param Team					$team				the team to be updated
	 *  @param integer				$parentTeamId		the parent team id (pass null, if there is no parent team)
	 *  @param string				$teamName			the name of the team
	 *  @param PropelCollection		$primaryMembers		the team's primary members
	 *  @param PropelCollection		$secondaryMembers	the team's secondary members
	 *  @param PropelCollection		$teamLeaders		the team's team leaders 
	 *  
	 *  @return Team
	 *  
	 */
	public function updateTeam($team, $parentTeamId, $teamName, $primaryMembers, $secondaryMembers, $teamLeaders) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// query for an empty user collection
			$emptyUsers = \TeamQuery::create()->filterById(-1)->find();
			
			// assign emtpy user collection to team (thereby resetting all team-user associations)
			$team->setUsers($emptyUsers);
						
			//
			// update team object with edit information
	
			// set parent team and obtain a reference to it
			$team->setParentId($parentTeamId);
			$parentTeam = $this->getTeamById($parentTeamId);
			
			// set name
			$team->setName($teamName);
			
			// add all the indicated primary members to the team
			foreach ($primaryMembers as $user) {
				$team->addUser($user);
			}
			
			// add all the indicated secondary members to the team
			foreach ($secondaryMembers as $user) {
				$team->addUser($user);
			}
			
			// add all the indicated secondary members to the team
			foreach ($teamLeaders as $user) {
				$team->addUser($user);
			}
			
			// persist new state
			$team->save();
		
			//
			// at this point, all users are related to the team
			// now, set the junction records according to type of relation
			
			//
			// first, team leaders
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($teamLeaders->getPrimaryKeys())
									->filterByTeamId($team->getId())
									->find();		
									
			// set all retrieved junction records to "leader"
			foreach ($junctionRecords as $junctionRecord) {
				$junctionRecord->setLeader(true);
				$junctionRecord->save();
			}	
					
			//
			// then, primary members
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($primaryMembers->getPrimaryKeys())
									->filterByTeamId($team->getId())
									->find();		
											
			// set all retrieved junction records to "primary"
			foreach ($junctionRecords as $junctionRecord) {
				$junctionRecord->setPrimary(true);
				$junctionRecord->save();
			}	
			
			
			//
			// finally, secondary members
			$junctionRecords = \TeamUserQuery::create()
									->filterByUserId($secondaryMembers->getPrimaryKeys())
									->filterByTeamId($team->getId())
									->find();
																		
											
			// set all retrieved junction records to "secondary"
			foreach ($junctionRecords as $junctionRecord) {
				$junctionRecord->setSecondary(true);
				$junctionRecord->save();
			}
			
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "TeamManager:updateTeam() - updated team with id: " . $team->getId());
		}
		catch (\PropelException $ex) {
		    //
			// oops, rollback TX
			$con->rollback();
			// rethrow	
			throw new MomoException("TeamManager:updateTeam() - a database error has occurred while attempting to update team with ID: " . $team->getId(), 0, $ex);
		}	
		
		return $team;
	}
	
	
	/**
	 *  Archives the indicated team
	 *  
	 *  A team is archived by:
	 *  	- removing all user references (primary and secondary members, team leaders)
	 *  	- setting the team's archive bit to "true"
	 *  
	 *  @param Team	$team	the team to archive 
	 *  
	 *  @return Team	- the archived team
	 */
	public function archiveTeam($team) {
	
		try {
			// query for an empty user collection
			$emptyUsers = \TeamQuery::create()->filterById(-1)->find();
			
			// assign emtpy user collection to team (thereby removing all team-user associations)
			$team->setUsers($emptyUsers);
			
			// remove possible parent team
			$team->setParentId(null);
			
			// set archive bit	
			$team->setArchived(true);
			
			// persist
			$team->save();
			
			// log at level "info"
			log_message('info', "TeamManager:archiveTeam() - archived team with ID: " . $team->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("TeamManager:archiveTeam() - a database error has occurred while attempting to archive team with ID: " . $team->getId(), 0, $ex);
		}

		return $team;
	}
	
	
	/**
	 *  Given a team, the method retrieves a list of all active teams minus
	 *  the indicated team and its (possible) children.
	 *  
	 *  @param	Team $team
	 *  
	 *  @return PropelCollection
	 */
	public function getAllActiveTeamsWithoutIndicatedTeamAndItsChildren($team) {
		//
		// obtain the ids of the indicated team's children
		$childIds = $this->getTeamChildrenIds($team->getId());
				
		// query for all teams, excluding the children as well as the indicated team
		$teams = \TeamQuery::create()
					->filterById($childIds, \Criteria::NOT_IN)
					->filterById($team->getId(), \Criteria::NOT_EQUAL)
					->filterByArchived(false)
					->find();
					
		return $teams;			
	}
	
	/**
	 *  Recursively retrieves a team's children's ids
	 *  
	 *  Note: the search recursively extends to arbitrary depth in the team hierarchy
	 *  
	 *  @param 	integer	$teamId		the id of the team in question
	 *  
	 *  @return array	an array of team ids that are children to the indicated team
	 */
	private function getTeamChildrenIds($teamId) {
		$childIdArray = array();
		
		// query for children of indicated team
		$children = \TeamQuery::create()->filterByParentId($teamId)->find();
		$childIdArray = $children->toKeyValue("id", "id");
		
		// recursively query for deeper children
		foreach ($children as $child) {
			$childIdArray = array_merge($childIdArray, $this->getTeamChildrenIds($child->getId()));
		}
		
		return $childIdArray;
	}
	
}