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

namespace momo\securitymanager;

use momo\core\managers\BaseManager;
use momo\core\security\Roles;
use momo\core\security\PermissionMap;
use momo\core\exceptions\MomoException;
use momo\usermanager\exceptions\UserNotFoundException;

/**
 * SecurityManager
 * 
 * Single access point for all security related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.securitymanager
 */
class SecurityManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_SECURITY";
	
	/**
	 * Returns a map of all roles
	 * 
	 * @return	array
	 */
	public function getRolesMap() {
		return Roles::$ROLE_MAP;
	}
	
	/**
	 * Returns a map of roles lower than the indicated role
	 * 
	 * @package $role
	 * 
	 * @return	array	- a map of the lower roles, the map is empty if there are no lower roles
	 */
	public function getLowerRolesMap($role) {
		
		$result = Roles::$ROLE_MAP;
		
		// reduce role map as indicated
		switch ($role) {
		
			case Roles::ROLE_ADMINISTRATOR:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
			break;
			
			case Roles::ROLE_MANAGER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
			break;
			
			case Roles::ROLE_TEAMLEADER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
				unset($result[Roles::ROLE_TEAMLEADER]);
			break;
			
			case Roles::ROLE_USER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
				unset($result[Roles::ROLE_TEAMLEADER]);
				unset($result[Roles::ROLE_USER]);
			break;

		}
		
		return $result;
	}
	
	/**
	 * Returns a map of the next lower role
	 * 
	 * @package $role
	 * 
	 * @return	array	- a map of the next lower role, the map is empty if there is no lower role
	 */
	public function getNextLowerRoleMap($role) {
		
		$result = Roles::$ROLE_MAP;
		
		// reduce role map as indicated
		switch ($role) {
		
			case Roles::ROLE_ADMINISTRATOR:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_TEAMLEADER]);
				unset($result[Roles::ROLE_USER]);
			break;
			
			case Roles::ROLE_MANAGER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
				unset($result[Roles::ROLE_USER]);
			break;
			
			case Roles::ROLE_TEAMLEADER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
				unset($result[Roles::ROLE_TEAMLEADER]);
			break;
			
			case Roles::ROLE_USER:
				unset($result[Roles::ROLE_ADMINISTRATOR]);
				unset($result[Roles::ROLE_MANAGER]);
				unset($result[Roles::ROLE_TEAMLEADER]);
				unset($result[Roles::ROLE_USER]);
			break;

		}
		
		return $result;
	}
	
	/**
	 * authenticates the passed credentials.
	 * 
	 * @access 	public
	 * @param 	string	$login		the login
	 * @param 	string	$password	the password (MD5 hash)
	 * @return	void
	 */
	public function authenticate($login, $password) {
		
		$authenticated = false;	
		
		//
		// attempt to authenticate
		try {
			$usermanager = $this->getCtx()->getUserManager();
		
			$user = $usermanager->getUserByLogin($login);

			if ( 	   ( $user !== null )
					&& ( $user->getPassword() !== null )
					&& ( $user->getPassword() == md5($password) )
					&&   $user->getEnabled()
				) {

				$authenticated = true;
				
				// associate this authenticated session with the concerned user
				$this->getCI()->session->set_userdata('securitymanager.user_login', $user->getLogin());	
				
				// update last login timestamp
				$user->setLastLogin(new \DateTime());	
				$user->save();
			}		
		}
		catch (UserNotFoundException $ex) {
			// NOOP - is to be expected if users enter non-existent logins
		}
		catch (\PropelException $ex) {
			throw new MomoException("SecurityManager:authenticate() - a database error occurred", 0 , $ex);
		}
		
		// authenticate and store user object in session
		$this->getCI()->session->set_userdata('securitymanager.user_authenticated', $authenticated);
		
	}
	
	/**
	 * tests whether the active credentials are enabled for the indicated permission
	 * 
	 * @access 	public
	 * @param 	string	$permission	the permission to test for
	 * @param 	string	$password	the password (MD5 hash)
	 * @return	boolean	true, if authentication is successful. otherwise false.
	 */
	public function authorize($permission) {
		
		$authorized = false;
		//
		// attempt to authorize, proceed only if there is an authenticated session in place
		if ($this->getCI()->session->userdata('securitymanager.user_authenticated') !== null) {
			
			$user = $this->getCtx()->getUser();
			$perms = PermissionMap::getPermissionsForRole($user->getRole());
			
			if ( array_key_exists($permission, $perms) ) {
				$authorized = true;
			}			
			
		}
		
		return $authorized;
	}
	
	/**
	 * log out the current session
	 * 
	 * @return	void
	 */
	public function logout() {
		$this->getCI()->session->sess_destroy();
	}
	
}