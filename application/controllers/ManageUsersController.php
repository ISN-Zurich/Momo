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

use momo\usermanager\UserManager;
use momo\audittrailmanager\EventDescription;
use momo\usermanager\exceptions\UserNotFoundException;
use momo\core\helpers\FormHelper;
use momo\core\exceptions\MomoException;
use momo\core\helpers\DateTimeHelper;
use momo\usermanager\UserHelper;
use momo\core\security\Roles;
use momo\core\security\Permissions;
use momo\emailservice\EmailService;
use momo\emailservice\EmailTemplates;

/**
 * ManageUsersController
 * 
 * Exposes the client actions needed to work with users.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageUsersController extends Momo_Controller {
	
	/**
	 *  Default action displays a list of all available users
	 *  
	 *  
	 */
	public function index() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_LIST_ALL_USERS,
										Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE
									)	
								);
	
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$securityService = $this->getCtx()->getSecurityService();						
							
		//
		// ready view data in accordance with permissions				
		if ( $this->authorize(Permissions::USERS_LIST_ALL_USERS, true) ) {
			//
			// the permission indicates that we can return a list of all users
			$allowedUsers = $userManager->getAllUsers();
		}
		else if ( $this->authorize(Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE, true) ) {
			//
			// in this case we may only return the users that are assigned to teams
			// for which the active session is designated team leader and that are of lower role than the active session
			
			// get active user
			$activeUser = $this->getCtx()->getUser();
			
			// obtain a list of users assigned to teams for which the active user is team leader
			$ledTeams = $teamManager->getAllTeamsLedByUser($activeUser);		
			$allowedUsers = $userManager->getAllUsersMembersOfTeams($ledTeams);
			
			//
			// with this permission level, the active user may not list their own record.
			// --> remove active user from allowed users
			if ( $key = $allowedUsers->search($activeUser) ) {	
				$allowedUsers->remove($key);
			}

			//
			// with this permission level, we may only list users that belong to roles lower than the role of the active session
			// --> reduce the user collection accordingly
			
			// obtain the next lower role to active user's role
			$nextLowerRoleMap = $securityService->getNextLowerRoleMap($activeUser->getRole());

			// if there is a lower role, reduce user collection accordingly
			if ( count($nextLowerRoleMap) != 0 ) {
				$allowedUsers = $userManager->reduceUserCollectionToMaximumRole($allowedUsers, key($nextLowerRoleMap));
			}
			// there is no lower role, hence there are no users at all that may be listed
			else {
				// there are no lower roles, we set the allowedusers collection to an empty collection
				$allowedUsers = $userManager->getUsersByIdList("-1");
			}
						
		}

		//
		// prepare view data
		$data["component"] = "components/component_list_users.php";
		$data["component_title"] = "Manage Users";
		
		$data["component_userlist"] = $allowedUsers;
		$data["component_activeuser"] = $this->getCtx()->getUser();
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new user
	 */
	public function createUser() {
			
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_CREATE_GENERAL_USER,
										Permissions::USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE
									)	
								);
												
		// get ref to managers needed
		$userManager 	= $this->getCtx()->getUserManager();
		$teamManager 	= $this->getCtx()->getTeamManager();
		$emailService 	= $this->getCtx()->getEmailService();
		$auditManager 	= $this->getCtx()->getAuditTrailManager();	
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// query for the assigned team
			$assignedPrimaryTeam = $teamManager->getTeamById($this->input->post('teamId'));
			
			// compile birthdate from components
			$birthDateString = $this->input->post('birthDateDay') . "-" . $this->input->post('birthDateMonth') . "-" . $this->input->post('birthDateYear');
			
			// if the indicated user is of "staff" type, gather off day information into an array
			$offDayArray = array();
			if ( $this->input->post('type') == \User::TYPE_STAFF ) {
				$offDayArray = $this->buildOffDayArray($this->input->post());
			}
				
			// create the user
			$newUser = $userManager->createUser(
			
								$this->input->post('firstName'),
								$this->input->post('lastName'),
								$this->input->post('email'),
								DateTimeHelper::getDateTimeFromStandardDateFormat($birthDateString),
								$this->input->post('type'),
								$this->input->post('login'),
								$this->input->post('workload'),
								$offDayArray,
								DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('entryDate')),
								DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('exitDate')),
								$assignedPrimaryTeam,
								$this->input->post('role'),
								$this->input->post('enabled')
														
							);
							
			
			// record to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newUser->compileStateDigest());
						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									UserManager::MANAGER_KEY,
									"Created User",
									$eventDescription
								);		

			// commit TX
			$con->commit();
				
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("UserManager:createUser() - a database error has occurred while attempting to create the user with login: " . $login, 0, $ex);
		}						
												
		// compile message map for user notification email
		$messageMap = array();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]			= $newUser->getFirstName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_LOGIN] 				= $newUser->getLogin();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_SET_PASSWORD_URL] 	= $this->config->item("base_url") . "manageusers/displaysetpasswordbytokenform/" . $newUser->getPasswordResetToken();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_MOMO_URL]			= $this->config->item("base_url");
												
		// send notification email
		$emailService->sendEmailFromTemplate(
								$this->config->item("email_from_address", "momo"),
								$this->config->item("email_from_name", "momo"),
								$newUser->getEmail(),
								EmailTemplates::$accountCreatedNotificationSubject,
								null,
								EmailTemplates::$accountCreatedNotificationMessage,
								$messageMap
							);
																			
		// redisplay default view
		redirect("/manageusers");	
	}
	
	
	/**
	 *  Updates a user
	 */
	public function updateUser() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_EDIT_ALL_USERS,
										Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE
									)	
								);
												
		// get ref to managers needed
		$userManager 	= $this->getCtx()->getUserManager();
		$teamManager 	= $this->getCtx()->getTeamManager();
		$auditManager 	= $this->getCtx()->getAuditTrailManager();	
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// query for the update target
			$updateTarget = $userManager->getUserById($this->input->post('userId'));
			
			// store pre-update state in eventdescription (for audit trail)
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// if a team id was passed, get a ref to the indicated team
			// otherwise, re-retrieve present primary team
			if ( $this->input->post('teamId') != null ) {
				$assignedPrimaryTeam = $teamManager->getTeamById($this->input->post('teamId'));
			}
			else {
				$assignedPrimaryTeam = $updateTarget->getPrimaryTeam();
			}
			
			// compile birthdate from components
			$birthDateString = $this->input->post('birthDateDay') . "-" . $this->input->post('birthDateMonth') . "-" . $this->input->post('birthDateYear');
				
			// if the indicated user is of "staff" type, gather off day information into an array
			$offDayArray = array();
			if ( $updateTarget->getType() == \User::TYPE_STAFF ) {
				$offDayArray = $this->buildOffDayArray($this->input->post());
			}
			
			// update the user
			$userManager->updateUser(
			
							$updateTarget,
							$this->input->post('firstName'),
							$this->input->post('lastName'),
							$this->input->post('email'),
							DateTimeHelper::getDateTimeFromStandardDateFormat($birthDateString),
							$updateTarget->getType(),
							$updateTarget->getLogin(),
							$updateTarget->getPassword(),
							$updateTarget->getWorkload(),
							$offDayArray,
							DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('entryDate')),
							DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('exitDate')),
							$assignedPrimaryTeam,
							$this->input->post('role'),
							$this->input->post('enabled')
													
						);
						
			// add post update state to event description
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// record to audit trail			
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									UserManager::MANAGER_KEY,
									"Updated User",
									$eventDescription
								);
								
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("UserManager:updateUser() - a database error has occurred while attempting to update the user with ID: " . $this->input->post('userId'), 0, $ex);
		}					
					
		// redisplay default view
		redirect("/manageusers");
	}
	
	
	
	
	/**
	 *  "Deletes" the indicated user
	 *  (see UserManager for further information)
	 *  
	 *  @param 	integer	$userId		the id of the user to delete
	 */
	public function deleteUser($userId) {
		
		// authorize call
		$this->authorizeOneInList(	array(
										Permissions::USERS_DELETE_ALL_USERS,
										Permissions::USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE
									)	
								);
								
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();	

		// get active session's user object
		$activeUser = $this->getCtx()->getUser();
		
		// permit operation only if it does not pertain to active user
		if ( $userId != $activeUser->getId() ) {
			
			// get db connection (for TX operations, it is best practice to specify connection explicitly)
			$con = \Propel::getConnection(\UserPeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {
				
				$deletePermitted = false;
			
				// query for the delete target
				$deleteTarget = $userManager->getUserById($userId);
				
				// test whether operation is permitted		
				if ( $this->authorize(Permissions::USERS_DELETE_ALL_USERS, true) ) {
					$deletePermitted = true;	
				}
				else if ( $this->authorize(Permissions::USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE, true) ) {
					if ( $activeUser->isUserTeamLeaderOfTeam($deleteTarget->getPrimaryTeam()) ) {
						$deletePermitted = true;
					}
				}		
				
				// proceed if delete is permitted
				if ( $deletePermitted ) {
					
					// archive the user
					$userManager->archiveUser($deleteTarget);
					
					// write event to audit trail
					$eventDescription = new EventDescription();
					$eventDescription->addDescriptionItem("User Name", $deleteTarget->getFullName());
																																		
					// add event to audit trail						
					$auditManager->addAuditEvent(	
											$this->getCtx()->getUser(),
											UserManager::MANAGER_KEY,
											"Deleted User",
											$eventDescription
										);	
				}
				
				// commit TX
				$con->commit();
			}
			catch (\PropelException $ex) {
				// roll back
				$con->rollback();
				// rethrow
				throw new MomoException("UserManager:archiveUser() - a database error has occurred while attempting to archive the user with login: " . $user->getLogin(), 0, $ex);
			}	
		}
		else {
			// illegal operation, throw exception
			throw new MomoException("ManageUserController:deleteUser() - The active user is not permitted to delete his/her own record.");
		}								
		
		// redisplay default view
		redirect("/manageusers");
	}
	
	
	/**
	 * Resets a user password by means of a "reset password" token.
	 * 
	 * Note: 	The significance of the "reset token" lies therein that the
	 * 			controller method is not subject to formal authentication.
	 * 
	 * 			The method may hence be called anonymously - provided the caller
	 * 			passes a valid reset token, of course.
	 */
	public function setUserPasswordByToken() {
								
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$userManager->setUserPasswordByToken($this->input->post('resetToken'), $this->input->post('password'));

		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Set New Password";
		$data["component_message"] = "Your new password has been set.";
		$data["component_mode"] = "info";
		$data["component_target"] = "/";
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 * Set the active user's password
	 * 
	 */
	public function setActiveUserPassword() {
		
		// authorize call
		$this->authorize(Permissions::USERS_SET_OWN_PASSWORD);
								
		// get reference to active user
		$activeUser = $this->getCtx()->getUser();
		
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$userManager->setUserPassword($activeUser, $this->input->post('password'));

		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Change Password";
		$data["component_message"] = "<p>Your password has been changed.</p><p>The change will take effect at the time of your next login.</p>";
		$data["component_mode"] = "info";
		$data["component_target"] = "/";
		
		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 * Resets the indicated user's password 
	 */
	public function resetUserPasswordByEmailAndLogin() {
		
		// var to track operation state
		$opSuccess = false;
								
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$emailService = $this->getCtx()->getEmailService();

		try {
		
			// query for the update target
			$updateTarget = $userManager->getUserByLogin($this->input->post('login'));
			
			// if login and email match, we proceed with the reset
			if ( $updateTarget->getEmail() == $this->input->post('email') ) {
				
				// reset the indicated user's password
				$userManager->resetUserPassword($updateTarget);
				
				//
				// send email with reset token to concerned user
				
				// compile message map for user notification email
				$messageMap = array();
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]			= $updateTarget->getFirstName();
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_LOGIN] 				= $updateTarget->getLogin();
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_SET_PASSWORD_URL] 	= $this->config->item("base_url") . "manageusers/displaysetpasswordbytokenform/" . $updateTarget->getPasswordResetToken();
				$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_MOMO_URL]			= $this->config->item("base_url");
														
				// send notification email
				$emailService->sendEmailFromTemplate(
										$this->config->item("email_from_address", "momo"),
										$this->config->item("email_from_name", "momo"),
										$updateTarget->getEmail(),
										EmailTemplates::$passwordResetNotificationSubject,
										null,
										EmailTemplates::$passwordResetNotificationMessage,
										$messageMap
									);
									
				
				$data["component_message"] = "<p>The password recovery process has been started.</p><p>Please check your email for further instructions.</p>";
				$data["component_mode"] = "info";
			
			}
			// if we do not match, advise accordingly
			else {
				$data["component_message"] = "<p>The operation failed.<br>The information provided does not match an account on record.</p>";
				$data["component_mode"] = "error";
			}
		}
		catch (UserNotFoundException $ex) {
			$data["component_message"] = "<p>The operation failed.<br>The information provided does not match an account on record.</p>";
			$data["component_mode"] = "error";
		}
		
		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Recover Password";
		$data["component_target"] = "/";
		
		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 * Resets the indicated user's password and returns a JSON encoded message
	 * concerning the operation's result.
	 * 
	 * @param	integer		$userId
	 * 
	 * @return string	- json encoded message
	 * 
	 */
	public function resetUserPasswordByIdJson($userId) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_EDIT_ALL_USERS,
										Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE
									)	
								);
		
		// vars to track operation state / return message
		$message = array();
		$opSuccess = false;
								
		// get ref to managers needed
		$userManager = $this->getCtx()->getUserManager();
		$emailService = $this->getCtx()->getEmailService();

		// query for the update target
		$updateTarget = $userManager->getUserById($userId);
		
		// get active session's user object
		$activeUser = $this->getCtx()->getUser();
		
		// process operation according to permissions				
		if ( $this->authorize(Permissions::USERS_EDIT_ALL_USERS, true) ) {
			
			// if this permission is granted, we may proceed without further ado
			$userManager->resetUserPassword($updateTarget);
			$opSuccess = true;
			
		}
		else if ( $this->authorize(Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE, true) ) {
				
			if ( $activeUser->isUserTeamLeaderOfTeam($updateTarget->getPrimaryTeam()) ) {
				$userManager->resetUserPassword($updateTarget);
				$opSuccess = true;
			}
			
		}
		
		// send email with reset token to concerned user
		if ( $opSuccess ) {
			
			// compile message map for user notification email
			$messageMap = array();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]			= $updateTarget->getFirstName();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_LOGIN] 				= $updateTarget->getLogin();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_SET_PASSWORD_URL] 	= $this->config->item("base_url") . "manageusers/displaysetpasswordbytokenform/" . $updateTarget->getPasswordResetToken();
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_MOMO_URL]			= $this->config->item("base_url");
													
			// send notification email
			$emailService->sendEmailFromTemplate(
									$this->config->item("email_from_address", "momo"),
									$this->config->item("email_from_name", "momo"),
									$updateTarget->getEmail(),
									EmailTemplates::$passwordResetNotificationSubject,
									null,
									EmailTemplates::$passwordResetNotificationMessage,
									$messageMap
								);
								
		}
		
		$message["opsuccess"] = $opSuccess;
		
		print(json_encode($message));
		
	}
	
	
	
	/**
	 *  Displays the "create new user" form
	 */
	public function displayNewUserForm() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::USERS_CREATE_GENERAL_USER,
										Permissions::USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE
									)	
								);
								

		// get ref to managers
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// determine range of allowable birth years (from 18 to 70)
		$maxBirthYear = DateTimeHelper::getCurrentYear() - 18;
		$minBirthYear = DateTimeHelper::getCurrentYear() - 70;
		
		$assignableTeams = $this->getAssignableTeams();
		$assignableRoles = $this->getAssignableRolesMap();
		
		// obtain earliest workplan - it determines the year of the earliest possible entry
		$firstWorkplan = $workplanManager->getFirstPlan();						
									
		// ready permission dependent view data				
		if ( $this->authorize(Permissions::USERS_CREATE_GENERAL_USER, true) ) {
			//
			// the permission indicates that we are not required to assign a team
			$mustAssignTeam = false;
		}
		else if ( $this->authorize(Permissions::USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE, true) ) {
			//
			$mustAssignTeam = true;
		}
		else {
			// should not ever reach this
			throw new MomoException("ManageUserController:displayNewUserForm() - The active user is not permitted to create users.");
		}						
										
			
		// prepare view data
		$data["component"] = "components/component_form_user.php";
		$data["component_title"] = "New User";
		$data["component_mode"] = "new";
	
		$data["component_entrydate_min"] = $firstWorkplan->getYear();
		$data["component_exitdate_min"] = $firstWorkplan->getYear();
		$data["component_birthyear_min"] = $minBirthYear;
		$data["component_birthyear_max"] = $maxBirthYear;
		$data["component_teams"] = $assignableTeams;
		$data["component_roles"] = $assignableRoles;
		$data["component_types"] = User::$TYPE_MAP;
		$data["component_workloads"] = UserHelper::$workloadValues;
		
		$data["component_flag_must_assign_team"] = $mustAssignTeam;
		$data["component_flag_may_edit_team"] = true;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit user" form
	 *  
	 *  @param integer	$userId		- the user for which to display the edit form
	 */
	public function displayEditUserForm($userId) {
		
		// authorize call
		$this->authorizeOneInList(	array(
										Permissions::USERS_EDIT_ALL_USERS,
										Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE
									)	
								 );
								 
		// get ref to managers
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();				 
		
		// determine range of allowable birth years (from 18 to 70)
		$maxBirthYear = DateTimeHelper::getCurrentYear() - 18;
		$minBirthYear = DateTimeHelper::getCurrentYear() - 70;
		
		$assignableTeams = $this->getAssignableTeams();
		$assignableRoles = $this->getAssignableRolesMap();
		
		// obtain earliest workplan - it determines the year of the earliest possible entry
		$firstWorkplan = $workplanManager->getFirstPlan();
		
		// get a reference to the edit target
		$editTarget = $userManager->getUserById($userId);
		
		// ready permission dependent view data				
		if ( $this->authorize(Permissions::USERS_EDIT_ALL_USERS, true) ) {
			//
			// the permission indicates that we are not required to assign a team
			$mustAssignTeam = false;
			
			// the permission indicates that team membership may be edited at will
			$mayEditTeam = true;
		}
		else if ( $this->authorize(Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE, true) ) {
			//
			// the permission requires that the user remain assigned to a team
			$mustAssignTeam = true;
			
			// the permission to edit assigned users also implies that primary team membership may
			// only be changed if the user is a primary member of a team led by the active user.
			// (the user could also be a "secondary" member of a team led by the active user)
			//
			// --> otherwise the active session could "steal" a primary team member away from a team for
			//     which they are not designated team leader
			
			//
			// check whether the active session is team leader for the edit target's primary team
			$activeUser = $this->getCtx()->getUser();
			$mayEditTeam = false;
			
			if ( $activeUser->isUserTeamLeaderOfTeam($editTarget->getPrimaryTeam()) ) {
				$mayEditTeam = true;
			}
		}
		else {
			//
			// don't expect this to occur
			$errorMsg = "ManageUserController:displayEditUserForm() - The active user is not permitted to edit users.";
			
			log_message('error', $errorMsg);
			
			throw new MomoException($errorMsg);
		}	
				
		// prepare view data
		$data["component"] = "components/component_form_user.php";
		$data["component_title"] = "Edit User (" . $editTarget->getFullName() . ")";
		$data["component_mode"] = "edit";
	
		$data["component_edit_target"] = $editTarget;
		
		$data["component_entrydate_min"] = $firstWorkplan->getYear();
		$data["component_entrydate_max"] = DateTimeHelper::getCurrentYear();
		$data["component_exitdate_min"] = $firstWorkplan->getYear();
		
		$data["component_birthyear_min"] = $minBirthYear;
		$data["component_birthyear_max"] = $maxBirthYear;
		$data["component_teams"] = $assignableTeams;
		$data["component_roles"] = $assignableRoles;
		
		$data["component_types"] = User::$TYPE_MAP;
		$data["component_workloads"] = UserHelper::$workloadValues;
		
		$data["component_flag_must_assign_team"] = $mustAssignTeam;
		$data["component_flag_may_edit_team"] = $mayEditTeam;
								
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the form for password reset via a "reset token".
	 *  
	 *  Note: 	Reset tokens allow unauthenticated sessions to set the passwords to their respective accounts.
	 *  		As such, the tokens are tied to the respective user records and are valid for one password reset operation.
	 *  
	 *  @param integer	$resetToken
	 */
	public function displaySetPasswordByTokenForm($resetToken) {
								 
		// get ref to managers
		$userManager = $this->getCtx()->getUserManager();

		try {
			// query for a record with the indicated reset token
			$resetTarget = $userManager->getUserByPasswordResetToken($resetToken);
			
			// prepare view data
			$data["component"] = "components/component_form_password.php";
			$data["component_title"] = "Set Password for &laquo;" . $resetTarget->getFullName() . "&raquo;";
			$data["component_action"] = "/manageusers/setuserpasswordbytoken";
			$data["component_mode"] = "token";
			
			$data["component_reset_target"] = $resetTarget;
										
			// render view
			$this->renderView($data);
			
		}
		catch (UserNotFoundException $ex) {
			// redirect to default view
			redirect("/");
		}
		
	}
	
	
	/**
	 *  Displays the password reset form
	 *  
	 */
	public function displaySetPasswordForm() {
								 
		// prepare view data
		$data["component"] = "components/component_form_password.php";
		$data["component_title"] = "Change Password";
		$data["component_action"] = "/manageusers/setactiveuserpassword";
		$data["component_mode"] = "authenticated";
										
		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 *  Displays the form for password recovery
	 *  
	 *  Note: 	"Password recovery" is implemented by means of a "password reset" operation
	 */
	public function displayRecoverPasswordForm() {
								 		
		// prepare view data
		$data["component"] = "components/component_form_recover_password.php";
		$data["component_title"] = "Recover Password";
		$data["component_action"] = "/manageusers/resetuserpasswordbyemailandlogin";
									
		// render view
		$this->renderView($data);
			
	}
	
	
	/**
	 *  Determines the assignable teams based on the active session's permissions
	 *  
	 *  @return PropelCollection	- a collection of teams assignable
	 */
	private function getAssignableTeams() {
		
		$teamManager = $this->getCtx()->getTeamManager();
		
		// retrieve assignable teams based on active sessions permissions
		if ( $this->authorize(Permissions::TEAMS_LIST_ALL_TEAMS, true) ) {
			$assignableTeams = $teamManager->getActiveTeams();
		}
		else if ( $this->authorize(Permissions::TEAMS_LIST_ASSIGNED_TEAMS, true) ) {
			$user = $this->getCtx()->getUser();
			$assignableTeams = $teamManager->getAllTeamsLedByUser($user);
		}
		else {
			// should not ever reach this
			throw new MomoException("ManageUserController:displayNewUserForm() - The active user is not permitted to list teams.");
		}
		
		return $assignableTeams;
	}
	
	
	/**
	 *  Determines the assignable roles based on the active session's permissions
	 *  
	 *  @return array	- a map of roles assignable
	 *  
	 */
	private function getAssignableRolesMap() {
		
		$securityService = $this->getCtx()->getSecurityService();
		
		// retrieve assignable roles based on active sessions permissions
		if ( $this->authorize(Permissions::ROLES_LIST_ALL_ROLES, true) ) {
			$assignableRoles = $securityService->getRolesMap();
		}
		else if ( $this->authorize(Permissions::ROLES_LIST_LOWER_ROLES, true) ) {
			$user = $this->getCtx()->getUser();
			$assignableRoles = $securityService->getLowerRolesMap($user->getRole());
		}
		else {
			// should not ever reach this
			throw new MomoException("ManageUserController:getAssignableRolesMap() - The active user is not permitted to list roles.");
		}
		
		return $assignableRoles;
	}
	
	
	/**
	 *  Returns a JSON encoded message indicating the availability of the indicated login
	 *  
	 *  The method is intended to be called from the browser, accordingly it returns
	 *  the operation result in a JSON encoded "message".
	 *  
	 *  @param string	$login
	 *  
	 *  @return string	- json encoded message
	 *  
	 */
	public function checkLoginAvailableJson($login) {
		
		$message = array();
		
		$available = true;
		$userIdAssignedTo = -1;
		
		// try to obtain user with indicated login
		$userManager = $this->getCtx()->getUserManager();
		
		try {
			
			$user = $userManager->getUserByLogin(urldecode($login));
			
			$available = false;
			$userIdAssignedTo = $user->getId();
		
		}
		catch (UserNotFoundException $ex) {
			// NOOP
		}

		$message["requestStatus"] = "ok";	
		$message["available"] = $available;
		$message["userIdAssignedTo"] = $userIdAssignedTo;
		
		print(json_encode($message));
	
	}
	
	
	/**
	 *  Returns a JSON encoded message indicating the availability of the indicated email
	 *  
	 *  The method is intended to be called from the browser, accordingly it returns
	 *  the operation result in a JSON encoded "message".
	 *  
	 *  @param string	$email
	 *  
	 *  @return string	- json encoded message
	 *  
	 */
	public function checkEmailAvailableJson($email) {
		
		$message = array();
		
		$available = true;
		$userIdAssignedTo = -1;
		
		// try to obtain user with indicated login
		$userManager = $this->getCtx()->getUserManager();
		
		try {
			
			$user = $userManager->getUserByEmail(urldecode($email));
			
			$available = false;
			$userIdAssignedTo = $user->getId();
		
		}
		catch (UserNotFoundException $ex) {
			// NOOP
		}

		$message["requestStatus"] = "ok";	
		$message["available"] = $available;
		$message["userIdAssignedTo"] = $userIdAssignedTo;
		
		print(json_encode($message));
	
	}
	
	
	/**
	 *  Returns a JSON encoded message indicating whether the employment range
	 *  indicated by the "entryDate" and "exitDate" results in a conflict with
	 *  existing timetracker entries.
	 *  
	 *  The method is called in the context of a user update operation, where the
	 *  new employment range is deemed valid if it does not conflict with existing
	 *  RegularEntry, or OOEntry instances.
	 *  
	 *  Note: - ProjectEntry instances need not be checked, as the application does
	 *  	    not allow them to be created in the absence of a RegularEntry.
	 *  
	 *  	  - if the indicated login cannot be matched to a user, the method will indicate "no conflict" 
	 *  
	 *  The method is intended to be called from the client, accordingly it returns
	 *  the operation result in a JSON encoded "message".
	 *  
	 *  @param string	$login					
	 *  @param string	$newEntryDate		- the entry date to be applied to the user's profile
	 *  @param string	$newExitDate		- the exit date to be applied to the user's profile
	 *  
	 *  @return string	- json encoded message
	 *  
	 */
	public function checkEmploymentRangeTimetrackerConflictJson($login, $newEntryDate, $newExitDate) {
		
		$message = array();
		$conflict = false;
		
		// get managers
		$userManager = $this->getCtx()->getUserManager();
		$entryManager = $this->getCtx()->getEntryManager();
		
		try {
			
			// get user record
			$user = $userManager->getUserByLogin(urldecode($login));
	
			// in the following, we detect a conflict between the proposed employment range
			// and the presently configured employment range by comparing the entry count for
			// each of the entry types that we track for conflicts.
			//
			// if the entry count of a given entity for the old and new employment ranges does
			// not match we need to flag a conflict
			
			// RegularEntry instances
			$regularEntriesPresentRange = $entryManager->getRegularEntriesForDateRangeAndUser($user, $user->getEntryDate(), $user->getExitDate());
			$regularEntriesNewRange = $entryManager->getRegularEntriesForDateRangeAndUser($user, $newEntryDate, $newExitDate);
			
			// RegularEntry instances
			$ooEntriesPresentRange = $entryManager->getOOEntriesForDateRangeAndUser($user, $user->getEntryDate(), $user->getExitDate());
			$ooEntriesNewRange = $entryManager->getOOEntriesForDateRangeAndUser($user, $newEntryDate, $newExitDate);
			
			
			// check for conflict
			if ( $regularEntriesPresentRange->count() != $regularEntriesNewRange->count() ) {
				$conflict = true;
			}
			else if ( $ooEntriesPresentRange->count() != $ooEntriesNewRange->count() ) {
				$conflict = true;
			}
			
		}
		catch (UserNotFoundException $ex) {
			// NOOP
		}

		$message["requestStatus"] = "ok";
		$message["conflict"] = $conflict;
		
		print(json_encode($message));
	
	}
	
	
	
	/**
	 *  Builds an array of off days from post values
	 *  
	 *  @param array $postArray
	 *  
	 *  @return array	- array containing the off day information
	 */
	private function buildOffDayArray($postArray) {
		
		$offDayArray = array();
		
		foreach ( FormHelper::$workDayOptions as $curOptVal => $curOptText ) {
		 
			$curDayRadioName = "dayoff_" . $curOptVal;
			
			if ( isset($postArray[$curDayRadioName]) ) {
				$offDayArray[$curOptVal] = $postArray[$curDayRadioName];
			}
			
		}
		
		return $offDayArray;
	}
	
}