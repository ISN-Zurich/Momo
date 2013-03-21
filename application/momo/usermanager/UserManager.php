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

namespace momo\usermanager;

use momo\core\helpers\FormHelper;
use momo\audittrailmanager\EventDescription;
use momo\core\helpers\DebugHelper;
use momo\core\helpers\DateTimeHelper;
use momo\core\exceptions\MomoException;
use momo\core\security\Roles;
use momo\core\managers\BaseManager;
use momo\usermanager\exceptions\UserNotFoundException;


/**
 * UserManager
 * 
 * Single access point for all user related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.usermanager
 */
class UserManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_USER";
		
	/**
	 * Retrieves a user by id
	 *	 
	 * @param 	string	$id			
	 * @return	User						
	 * @throws	UserNotFoundException		if the indicated user does not exist
	 * 
	 */
	public function getUserById($id) {
		
		$user = \UserQuery::create()
					->filterById($id)
					->filterByArchived(false)
					->findOne();
		
		if ( $user === null ) {
			throw new UserNotFoundException("UserManager:getUserById($id) - either the indicated user does not exist, or the id refers to an archived user record.");
		}
		
		return $user;
	}
		
	/**
	 * Retrieves a user by login
	 * Note: the schema defines "login" as unique
	 *	 
	 * @param 	string	$login				
	 * @return	User						
	 * @throws	UserNotFoundException		if the indicated user does not exist
	 * 
	 */
	public function getUserByLogin($login) {
		
		$user = \UserQuery::create()
					->filterByLogin($login)
					->filterByArchived(false)
					->findOne();
		
		if ( $user === null ) {	
			throw new UserNotFoundException("UserManager:getUserByLogin($login) - the indicated user does not exist.");
		}
		
		return $user;
		
	}
	
	/**
	 * Retrieves a user by email
	 * 
	 * Note: the schema defines "email" as unique
	 *	 
	 * @param 	string	$email				
	 * @return	User							
	 
	 * @throws	UserNotFoundException		if the indicated user does not exist
	 */
	public function getUserByEmail($email) {
		
		$user = \UserQuery::create()
					->filterByEmail($email)
					->filterByArchived(false)
					->findOne();
		
		if ( $user === null ) {	
			throw new UserNotFoundException("UserManager:getUserByEmail($email) - the indicated user does not exist.");
		}
		
		return $user;
	}
	
	
	/**
	 * Retrieves a user by reset token
	 *	 
	 * @param 	string	$token
	 * 				
	 * @return	User							
	 *
	 * @throws	UserNotFoundException		if the indicated user does not exist
	 */
	public function getUserByPasswordResetToken($token) {
		
		$user = \UserQuery::create()
					->filterByPasswordResetToken($token)
					->filterByArchived(false)
					->findOne();
		
		if ( $user === null ) {
			throw new UserNotFoundException("UserManager:getUserByPasswordResetToken($token) - the indicated user does not exist.");
		}
		
		return $user;
	}
	
	
	/**
	 * Retrieves all users
	 * 
	 * Note:
	 * 		- The set of "all users" consists all users that are not archived
	 * 		- Result is ordered by user last name, ascending order
	 *	 
	 * @return	PropelCollection	a collection of user objects
	 */
	public function getAllUsers() {
		return \UserQuery::create()
					->filterByArchived(false)
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Retrieves all enabled users
	 * 
	 * Note: 
	 * 		- Users are active, if "enabled=1" (and "archive=0")
	 *  	- The result is ordered by user last name, ascending order
	 *	 
	 * @return	PropelCollection	a collection of user objects
	 */
	public function getAllEnabledUsers() {
		return \UserQuery::create()
					->filterByEnabled(true)
					->filterByArchived(false)
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Retrieves users by a list of ids
	 *	 
	 * @param 	array	$idList		the list of user ids
	 *
	 * @return	PropelCollection	a collection of user objects
	 */
	public function getUsersByIdList($idList) {
		return \UserQuery::create()
					->filterById($idList)
					->orderByLastname()
					->find();
	
	}

	
	/**
	 * Retrieves users by role
	 *	 
	 * @param 	string	$role			the role to retrieve the users for
	 * 
	 * @return	PropelCollection		a collection of user objects
	 */
	public function getUsersByRole($role) {
		return \UserQuery::create()
					->filterByRole($role)
					->filterByArchived(false)
					->orderByLastname()
					->find();
	}
	
	
	/**
	 * Retrieves all users that, at a minium, are of the indicated role
	 *	 
	 * @param 	string	$role			the minimum role that the retrieved users must belong to
	 * 
	 * @return	PropelCollection		a collection of user objects
	 */
	public function getUsersOfRoleAndAbove($role) {
		
		$userQuery = \UserQuery::create()
								->filterByArchived(false)
								->orderByLastname();
		//
		// augment criteria based on inferior roles
		switch ( $role ) {
			
			case Roles::ROLE_USER:
				// add indicated role as first query criterium
				$userQuery->filterByRole($role)
						  	->_or()
						  	->filterByRole(Roles::ROLE_TEAMLEADER)
						  	->_or()
						  	->filterByRole(Roles::ROLE_MANAGER)
						  	->_or()
						  	->filterByRole(Roles::ROLE_ADMINISTRATOR);
				break;
			
			case Roles::ROLE_TEAMLEADER:
				// add indicated role as first query criterium
				$userQuery->filterByRole($role)
						  	->_or()
						  	->filterByRole(Roles::ROLE_MANAGER)
						  	->_or()
						  	->filterByRole(Roles::ROLE_ADMINISTRATOR);
				break;
			
			case Roles::ROLE_MANAGER:
				// add indicated role as first query criterium
				$userQuery->filterByRole($role)
						  	->_or()
						  	->filterByRole(Roles::ROLE_ADMINISTRATOR);
				break;
				
			case Roles::ROLE_ADMINISTRATOR:
				$userQuery->filterByRole($role);
				break;
		}
		
		// return result ordered result
		return $userQuery->find();
	}
	
		
	/**
	 * Retrieves all users that are primary or secondary members of the indicated list of teams
	 * 
	 * @param	PropelCollection 	$teams		- the teams for which we want to retrieve the users
	 * 
	 * @return 	PropelCollection
	 */
	public function getAllUsersMembersOfTeams($teams) {
		
		return \UserQuery::create()
					->useTeamUserQuery()
						->filterByTeamId($teams->toKeyValue("id", "id"))
						->filterByPrimary(true)
						->_or()
						->filterBySecondary(true)
					->endUse()
					->filterByArchived(false)
					->orderByLastname()
				->find();					
	}
	
	
	/**
	 * Given a collection of user objects and role, the method will return
	 * a new collection containing all members that are of, or below, the indicated role.
	 * 
	 * @param	PropelCollection 	$users
	 * @param	string 				$maxRole		- the maximum role to reduce collection to
	 * 
	 * @return 	PropelCollection
	 */
	public function reduceUserCollectionToMaximumRole($users, $maxRole) {
				
		$securityService = $this->getCtx()->getSecurityService();
		
		// get a map of all lower roles
		$lowerRolesMap = $securityService->getLowerRolesMap($maxRole);
		
		// compile an array of internal role keys that are to be returned
		// --> consists of all lower roles, plus the indicated role
		$rolesToReturn = array($maxRole);
		foreach ($lowerRolesMap as $curRoleKey => $curRoleValue) {
			array_push($rolesToReturn, $curRoleKey);
		}
		
		return \UserQuery::create()
				->filterById($users->toKeyValue("id", "id"))		
				->filterByRole($rolesToReturn)
				->orderByLastname()
				->find();					
	}
	
	
	/**
	 *  Creates a new user
	 *  
	 *  @param 	string		$firstName
	 *  @param 	string		$lastName
	 *  @param	string		$email
	 *  @param 	DateTime	$birthdate
	 *  @param 	string		$type
	 *  @param 	string		$login
	 *  @param 	float		$workload
	 *  @param 	array		$offDays
	 *  @param 	DateTime	$entryDate
	 *  @param 	DateTime	$exitDate
	 *  @param 	Team		$primaryTeam			- the user's primary team
	 *  @param 	string		$role
	 *  @param 	integer		$enabled
	 *  
	 *  
	 *  @return User		the newly created user object
	 *  
	 */
	public function createUser(	$firstName, $lastName, $email, $birthdate, $type, $login,
								$workload, $offDays, $entryDate, $exitDate, $primaryTeam, $role, $enabled ) {
		
		// get managers
		$workplanManager = $this->getCtx()->getWorkplanManager();
							
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {

			$user = new \User();
			
			$user->setFirstName($firstName);
			$user->setLastName($lastName);
			$user->setEmail($email);
			$user->setBirthdate($birthdate);			
			$user->setType($type);
			$user->setLogin($login);
			$user->setWorkload($workload);
			$user->setOffdays(serialize($offDays));
			$user->setEntrydate($entryDate);
			$user->setExitdate($exitDate);
			$user->setRole($role);
			$user->setEnabled($enabled);
		
			// set the password reset token - new users will need to set a password via this token before they can log in
			$user->setPasswordResetToken(substr(sha1(rand()), 0, 36));
			
			// assign team, provided one was passed
			if ( $primaryTeam !== null) {
				$user->addTeam($primaryTeam);
			}
			
			// persist user
			$user->save();
			
			if ( $primaryTeam !== null) {
				// if a team was assigned, query for user-team junction record and indicate primary membership
				$junctionRecord = \TeamUserQuery::create()
										->filterByUser($user)
										->filterByTeam($primaryTeam)
										->findOne();		
												
				$junctionRecord->setPrimary(true);
				$junctionRecord->setSecondary(false);
				$junctionRecord->setLeader(false);
				$junctionRecord->save();
			}
			
			// reinitialize all workplans for the user
			$workplanManager->reInitAllWorkplansForUser($user);
			
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "UserManager:createUser() - created user: " . $login);
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("UserManager:createUser() - a database error has occurred while attempting to create the user with login: " . $login, 0, $ex);
		}		
		
		return $user;
		
	}
	
	
	/**
	 *  Updates a user
	 *  
	 *  Note: to exclude a field from the update, set it to null.		
	 *
	 *  @param 	User		$updateTarget
	 *  @param 	string		$firstName
	 *  @param 	string		$lastName
	 *  @param	string		$email
	 *  @param 	DateTime	$birthdate
	 *  @param 	string		$type
	 *  @param 	string		$login
	 *  @param 	string		$password			- the password
	 *  @param 	float		$workload
	 *  @param 	array		$offDays
	 *  @param 	DateTime	$entryDate
	 *  @param 	DateTime	$exitDate
	 *  @param 	Team		$primaryTeam		- the user's primary team
	 *  @param 	string		$role
	 *  @param 	integer		$enabled
	 *  
	 */
	public function updateUser(	$updateTarget, $firstName, $lastName, $email, $birthdate, $type, $login, $password,
								$workload, $offDays, $entryDate, $exitDate, $primaryTeam, $role, $enabled ) {
		
									
		// get managers needed
		$teamManager = $this->getCtx()->getTeamManager();
		$tagManager = $this->getCtx()->getTagManager();
		$ooManager = $this->getCtx()->getOOManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();		
									
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			//
			// deal with all simple properties first
			$updateTarget->setFirstName($firstName);
			$updateTarget->setLastName($lastName);
			$updateTarget->setEmail($email);
			$updateTarget->setType($type);
			$updateTarget->setLogin($login);
			$updateTarget->setPassword($password);
			$updateTarget->setWorkload($workload);
			$updateTarget->setOffdays(serialize($offDays));
			$updateTarget->setEnabled($enabled);
			$updateTarget->setBirthdate($birthdate);
			$updateTarget->setEntrydate($entryDate);
			$updateTarget->setExitdate($exitDate);
			
			//
			// update the primary team membership as indicated
			if ( $primaryTeam === null ) {
				//
				// the user is not to have a primary team
				
				// see if there is already a junction record that indicates primary team membership
				// if so, clear its primary membership flag
				$junctionRecord = \TeamUserQuery::create()
									->filterByPrimary(true)
									->filterByUser($updateTarget)
									->findOne();
					
				if ( $junctionRecord !== null ) {
					$junctionRecord->setPrimary(false);
					$junctionRecord->save();	
				}					
			}			
			else {
				//
				// the indicated team is to be set as primary
				
				// see if there is already a junction record that defines the user as a primary member of another team
				// and if so, clear primary team membership
				$junctionRecord = \TeamUserQuery::create()
										->filterByUser($updateTarget)
										->filterByPrimary(true)
										->findOne();						
					
				if ( $junctionRecord !== null ) {
					$junctionRecord->setPrimary(false);
					$junctionRecord->save();	
				}
					
				// if there is no relation to the indicated team, we set one
				if ( ! $updateTarget->getTeams()->contains($primaryTeam) ) {
					$updateTarget->addTeam($primaryTeam);
					$updateTarget->save();		
				}
				
				// query for the junction record to the indicated primary team
				// and set membership to primary
				$junctionRecord = \TeamUserQuery::create()
										->filterByUser($updateTarget)
										->filterByTeam($primaryTeam)
										->findOne();		

				$junctionRecord->setPrimary(true);
				$junctionRecord->save();	
			}
			
			// if there is a role switch and the user's new role is USER, we need to ensure that
			// there is no team for which the user is configured as team leader
			if ( 	($updateTarget->getRole() !== $role)
				 && ($role === Roles::ROLE_USER) ) {
				
				// get all junction records for which user is configured as team leader
				$junctionRecords = \TeamUserQuery::create()
										->filterByLeader(true)
										->filterByUser($updateTarget)
										->find();

				// remove team leader attribute on all records found
				foreach ( $junctionRecords as $curJunctionRecord ) {
					$curJunctionRecord->setLeader(false);
					$curJunctionRecord->save();
				}						
	
			}
			
			// set user's new role 
			$updateTarget->setRole($role);
			
			
			// delete stale user-team relations, i.e., those with all membership flags set to 'false'
			// (above operations on team-user junction records might lead to these)
			\TeamUserQuery::create()
								->filterByUser($updateTarget)
								->filterByPrimary(false)
								->filterBySecondary(false)
								->filterByLeader(false)
								->find()
								->delete();
			
			// persist updates
			$updateTarget->save();
				
			//
			// TODO move all updates of related state to ManageUsersController
			//
			
			//
			// updating a user entity calls for management of related application state
			//
			
			// first, reinitialize all workplans for the user - this takes care of possible changes in employment period
			// and hence vacation credit allotted
			$workplanManager->reInitAllWorkplansForUser($updateTarget);
			
			//
			// next, clear all workweek initializations and off-day tags that lie in future (i.e. in the following week and beyond)
			
			// figure out date of the following week's monday
			$followingWeekMondayDate = DateTimeHelper::addDaysToDateTime(DateTimeHelper::getDateTimeForCurrentDate(), 7);
			$followingWeekMondayDate = DateTimeHelper::getStartOfWeek($followingWeekMondayDate);
			
			// clear all off-day related state tags
			$tagManager->deleteDayTagByTypeAndDateRange(\Tag::TYPE_WEEK_INITIALIZED, $updateTarget, $followingWeekMondayDate);
			$tagManager->deleteDayTagByTypeAndDateRange(\Tag::TYPE_DAY_DAY_OFF_FULL, $updateTarget, $followingWeekMondayDate);
			$tagManager->deleteDayTagByTypeAndDateRange(\Tag::TYPE_DAY_HALF_DAY_OFF_AM, $updateTarget, $followingWeekMondayDate);
			$tagManager->deleteDayTagByTypeAndDateRange(\Tag::TYPE_DAY_HALF_DAY_OFF_PM, $updateTarget, $followingWeekMondayDate);
			
			// recalculate possible oo bookings that lie in the future
			// reason: as the off-day configured might have changed, the oo bookings need to be update so as to take this into account
			$ooManager->recalculateOOBookingsForDateRange($updateTarget, $followingWeekMondayDate);
			
			// recompute the overtime and vacation lapses as employment period might have changed
			$enforcementService->recomputeAllOvertimeLapses($updateTarget);
			$enforcementService->recomputeAllVacationLapses($updateTarget);	
			
			// commit TX
			$con->commit();
			
			// log at level "info"
			log_message('info', "UserManager:updateUser() - updated user with id: " . $updateTarget->getId());	
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("UserManager:updateUser() - a database error has occurred while attempting to update the user with login: " . $updateTarget->getLogin(), 0, $ex);
		}		
	}
	
	
	/**
	 *  Archives the indicated user
	 *  
	 *  A user is archived by:
	 *  	- removing all team assignments
	 *  	- removing all project assignments
	 *  	- setting the user's archive bit to "true"
	 *  
	 *  @param	User	$user	the user to archive 
	 */
	public function archiveUser($user) {
 
		try {
			// query for an empty collection
			$emptyColl = \UserQuery::create()->filterById(-1)->find();
			
			// assign emtpy collection to teams and projects collections
			$user->setTeams($emptyColl);
			$user->setProjects($emptyColl);

			// set to disabled (not strictly necessary, but somehow makes more sense)
			$user->setEnabled(false);
			
			// set archive bit
			$user->setArchived(true);
			
			// prefix user's email and login with record id
			// this is done so as to allow the creation of a new user using the same email address
			// (schema has UNIQUE constraint on field)
			$user->setLogin($user->getId() . "_" . $user->getLogin());
			$user->setEmail($user->getId() . "_" . $user->getEmail());
			
			// persist
			$user->save();
			
			// log at level "info"
			log_message('info', "UserManager:archiveUser() - archived user with id: " . $user->getId());	
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("UserManager:archiveUser() - a database error has occurred while attempting to archive the user with login: " . $user->getLogin(), 0, $ex);
		}		
	}
	
	
	/**
	 * Sets a user password by means of a password reset token
	 *  
	 * If the operation succeeds, the token is invalidated (i.e., set to NULL) 
	 *  
	 * @param	string	$resetToken
	 * @param	string	$passwordPlain
	 *  
	 */
	public function setUserPasswordByToken($resetToken, $passwordPlain) {
		
		try {
			// retrieve reset target
			$resetTarget = $this->getUserByPasswordResetToken($resetToken);
			
			// set password and reset token
			$resetTarget->setPassword(md5($passwordPlain));
			$resetTarget->setPasswordResetToken(null);
			
			// persist
			$resetTarget->save();
		}
		catch (\Exception $ex) {
			// rethrow
			throw new MomoException("UserManager:resetPasswordByToken() - a database error has occurred while attempting to set the password for token: " . $resetToken, 0, $ex);
		}		
	}
	
	/**
	 * Sets the password for a given user
	 *  
	 * @param 	User	$user 
	 * @param	string	$passwordPlain
	 *  
	 */
	public function setUserPassword($user, $passwordPlain) {
		
		try {
			// set the password
			$user->setPassword(md5($passwordPlain));
			
			// persist
			$user->save();
		}
		catch (\Exception $ex) {
			// rethrow
			throw new MomoException("UserManager:setPassword() - a database error has occurred while attempting to set the password for user: " . $user->getLogin(), 0, $ex);
		}		
	}
	
	/**
	 * Resets the password for a given user.
	 * 
	 * Resetting the password means:
	 * 		- invalidating the current password (i.e., setting it to NULL)
	 * 		- generating a "reset token"
	 *  
	 * @param 	User	$user
	 *  
	 */
	public function resetUserPassword($user) {
		
		try {
			// invalidate current password
			$user->setPassword(null);
			
			// generate a password reset token
			$user->setPasswordResetToken(substr(sha1(rand()), 0, 36));
			
			// persist
			$user->save();	
		}
		catch (\Exception $ex) {
			// rethrow
			throw new MomoException("UserManager:resetUserPassword() - a database error has occurred while attempting to reset the password for user: " . $user->getLogin(), 0, $ex);
		}		
	}
	
	
}