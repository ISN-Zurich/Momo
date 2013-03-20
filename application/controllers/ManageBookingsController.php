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
use momo\core\security\Roles;
use momo\core\security\Permissions;
use momo\emailservice\EmailService;
use momo\emailservice\EmailTemplates;
use momo\audittrailmanager\EventDescription;
use momo\oomanager\OOManager;
use momo\core\exceptions\MomoException;

/**
 * ManageBookingsController
 * 
 * Exposes the client actions needed to work with out-of-office bookings.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageBookingsController extends Momo_Controller {
	
	/**
	 *  Default action
	 * 
	 */
	public function index() {
				
		$session = $this->session;
		
		// if possible, restore filter form values from session otherwise, set default values
		$_POST["targetUserId"] = ( $session->userdata('managebookingscontroller.target_user.id') != null ? $session->userdata('managebookingscontroller.target_user.id') : -1 );
		$_POST["fromDate"] = ( $session->userdata('managebookingscontroller.from_date') != null ? $session->userdata('managebookingscontroller.from_date') : "" );
		$_POST["untilDate"] = ( $session->userdata('managebookingscontroller.until_date') != null ? $session->userdata('managebookingscontroller.until_date') : "" );
		
		$this->listBookings();
	}
	
	
	/**
	 * Lists the oo bookings as indicated by the form parameters
	 * 
	 * Note: the method pulls the following filtering parameters from $_POST:
	 * 
	 * targetUserId		- the user to list the bookings for
	 * fromDate			- the earliest date from which to display the bookings for
	 * untilDate		- the latest date from which to display the bookings for
	 * 
	 * if the method is to be called outside a post context, set these manually in $_POST.
	 *
	 */
	public function listBookings() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_LIST_ALL_BOOKINGS,
										Permissions::OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS
									)	
								);
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$securityService = $this->getCtx()->getSecurityService();	
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();

		$targetUser = null;
		$fromDate = null;
		$untilDate = null;
		$untilDateForQuery = null;
		$ooBookings = new \PropelCollection(array());
		
		// process params that govern display of list
		
		// target user
		if ( $this->input->post('targetUserId') != -1 ) {
			$targetUser = $userManager->getUserById($this->input->post('targetUserId'));
		}
		
		// "from" date
		if ( $this->input->post('fromDate') != "" ) {
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('fromDate'));
		}
		
		// "until" date
		if ( $this->input->post('untilDate') != "" ) {
			//
			// as the "until" date is to be inclusive of all bookings extending to that date, we need to increment the passed date value by one day
			// i.e., "until 20-1-2012 00:00h" is taken to mean "until 21-1-2012 00:00h" - that way all bookings that extend to the 20th will be
			// returned in the result
			$untilDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			$untilDateForQuery = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			$untilDateForQuery->modify("+1 day");
		}
		
		//
		// store filter form values in session
		$this->session->set_userdata('managebookingscontroller.target_user.id', $this->input->post('targetUserId'));
		$this->session->set_userdata('managebookingscontroller.from_date', $this->input->post('fromDate'));
		$this->session->set_userdata('managebookingscontroller.until_date', $this->input->post('untilDate'));
		
		
		//
		// ready view data in accordance with permissions

		if (		$this->authorize(Permissions::OO_BOOKINGS_LIST_ALL_BOOKINGS, true)
				&&  $this->authorize(Permissions::USERS_LIST_ALL_USERS, true)
			) {
				
			// get collection of users that may be accessed
			$allowedUsers = $userManager->getAllUsers();
			
			// retrieve bookings if there is a target user specified
			if ( $targetUser !== null ) {
				// get collection of bookings that may be accessed
				$ooBookings = $ooManager->getOOBookingsInDateRange(	
																	$targetUser,
																	$fromDate,
																	$untilDate,
																	"asc"
															 	);
			}
			
		}
		else if ( 		$this->authorize(Permissions::OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS, true)
					&&  $this->authorize(Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE, true)
				) {

					
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
				//$allowedUsers = $userManager->getUsersByIdList("-1");
				$allowedUsers = new \PropelCollection(array());
			}
			
			// retrieve bookings if there is a target user specified and user access is permitted
			if ( $allowedUsers->search($targetUser) !== false ) {
				// get collection of bookings that may be accessed
				$ooBookings = $ooManager->getOOBookingsInDateRange(	
																$targetUser,
																$fromDate,
																$untilDate,
																"asc"
															   );	
			}
	
		}
		else {
			throw new MomoException("ManageBookingsController:listBookings() - Unable to list bookings as the active user does not have the requisite permissions.");
		}
		
		//
		// prepare view data			
		$data["component"] = "components/component_list_oobookings.php";
		$data["component_title"] = "Manage Out-of-Office";
		
		$data["component_users"] = $allowedUsers;
		$data["component_bookings"] = $ooBookings;
		$data["component_targetuser"] = $targetUser;
		$data["component_fromdate"] = $fromDate;
		$data["component_untildate"] = $untilDate;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a booking
	 */
	public function createBooking() {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_CREATE_BOOKING,
										Permissions::OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING
									)	
								);
									
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$userManager = $this->getCtx()->getUserManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get a reference to the target user
			$targetUser = $userManager->getUserById($this->input->post("targetUserId"));
			
			//
			// create booking of indicated type
			//
			
			//
			// compile half day date strings into arrays of DateTime objects
			
			// AM half-days		
			$halfDaysAM = array();
			if ( array_key_exists("halfDaysAM", $this->input->post()) ) {
				
				foreach ( $this->input->post("halfDaysAM") as $curHalfDayString ) {
					$halfDaysAM[] = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayString);
				}
			
			}
			
			// PM half-days	
			$halfDaysPM = array();
			if ( array_key_exists("halfDaysPM", $this->input->post()) ) {
	
				foreach ( $this->input->post("halfDaysPM") as $curHalfDayString ) {
					$halfDaysPM[] = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayString);
				}
				
			}
			
			//
			// if this is a paid booking, set autoassign worktime credit flag according to form value else set to null
			$bookingType = $bookingTypeManager->getTypeById($this->input->post("typeId"));
			$autoAssignWorktimeCredit = null;
			if ( $bookingType->getPaid() ) {
				$autoAssignWorktimeCredit = $this->input->post("autoAssignWorktimeCredit");
			}
			
			//
			// create the booking
			$newBooking = $ooManager->createOOBooking(
														$targetUser,
														$bookingType,
														DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("fromDate")),
														DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("untilDate")),
														$halfDaysAM,
														$halfDaysPM,
														$autoAssignWorktimeCredit
													);
										
			// recompute the target user's overtime lapses
			$enforcementService->recomputeAllOvertimeLapses($targetUser);
	
			// recompute the target user's vacation lapses
			$enforcementService->recomputeAllVacationLapses($targetUser);	
			
			
			//
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newBooking->compileStateDigest());	
														
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									OOManager::MANAGER_KEY,
									"Created OO Booking",
									$eventDescription
								);	
			
			// commit TX
			$con->commit();
			
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("WorkplanBookingsController:createBooking() - an error has occurred while while attempting to create an oobooking", 0, $ex);
		}					
								
		// throw back to overview
		redirect("/managebookings");						
	}
	
	
	/**
	 *  Updates a booking
	 */
	public function updateBooking() {
	
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_EDIT_ALL_BOOKINGS,
										Permissions::OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING
									)	
								);
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$emailService = $this->getCtx()->getEmailService();
		$teamManager = $this->getCtx()->getTeamManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		$tagManager = $this->getCtx()->getTagManager();
		
		// get db connection
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get reference to edit target
			$editTarget = $ooManager->getOOBookingById($this->input->post("editTargetId"));
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($editTarget->compileStateDigest());

			//
			// update the booking as indicated
			// 
			
			// before proceeding with the actual update, we need to clear possible "week complete"
			// flags on the weeks that the booking presently covers
			$tagManager->deleteWeekTagByTypeAndDateRange(
															\Tag::TYPE_WEEK_COMPLETE,
															$editTarget->getUser(),
															$editTarget->getStartDate(),
															$editTarget->getEndDate()
														);
			
			//
			// compile half day date strings into arrays of DateTime objects
			
			// AM half-days		
			$halfDaysAM = array();
			if ( array_key_exists("halfDaysAM", $this->input->post()) ) {
				
				foreach ( $this->input->post("halfDaysAM") as $curHalfDayString ) {
					$halfDaysAM[] = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayString);
				}
			
			}
			
			// PM half-days	
			$halfDaysPM = array();
			if ( array_key_exists("halfDaysPM", $this->input->post()) ) {
	
				foreach ( $this->input->post("halfDaysPM") as $curHalfDayString ) {
					$halfDaysPM[] = DateTimeHelper::getDateTimeFromStandardDateFormat($curHalfDayString);
				}
				
			}
			
			//
			// if this is a paid booking, set autoassign worktime credit flag according to form value else set to null
			$autoAssignWorktimeCredit = null;
			if ( $editTarget->getOOBookingType()->getPaid() ) {
				$autoAssignWorktimeCredit = $this->input->post("autoAssignWorktimeCredit");
			}
			
			//
			// when updating a booking in this context, the booking may have an associated request.
			// if so, the POST carries state information for that request. accordingly, we call the booking manager's
			// appropriate update method (i.e., either updateOOBooking() or updateOORequest())
			//
			if ( $editTarget->getOORequest() !== null ) {
				
				// if we're updating a request user's will need to be notified of status changes
				// -- set aside present status
				$originalRequestStatus = $editTarget->getOORequest()->getStatus();
				
				// update the request/booking as indicated
				$editTarget = $ooManager->updateOORequest(
												$editTarget->getOORequest(),
												$this->input->post("requestStatus"),
												$editTarget->getOOBookingType(),
												DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("fromDate")),
												DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("untilDate")),
												$halfDaysAM,
												$halfDaysPM,
												trim($this->input->post("originatorComment")),
												$autoAssignWorktimeCredit
											);

				// above call returns an OORequest, we set the edit target back to the associated OOBooking							
				$editTarget = $editTarget->getOOBooking();							
										
				// if status has changed, issue email notification
				if ( $originalRequestStatus !== $this->input->post("requestStatus") ) {
					
					// figure out pretty name of oo booking type
					$prettyOOBookingTypeName = $editTarget->getOOBookingType();
					if ( $editTarget->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
						$prettyOOBookingTypeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$editTarget->getOOBookingType()->getType()];
					}
						
					// compile message map
					$messageMap = array();
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME]				= $editTarget->getUser()->getFirstName();
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_OOREQUEST_TYPE]			= $prettyOOBookingTypeName;
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_OOREQUEST_DATE_FROM]		= DateTimeHelper::formatDateTimeToPrettyDateFormat($editTarget->getStartDate());
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_OOREQUEST_DATE_UNTIL]	= DateTimeHelper::formatDateTimeToPrettyDateFormat($editTarget->getEndDate());
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_OOREQUEST_STATUS]		= \OORequest::$STATUS_MAP[$editTarget->getOORequest()->getStatus()];
					$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_GENERAL_URL] 			= $this->config->item("base_url") . "managerequests";
															
					// send notification email
					$emailService->sendEmailFromTemplate(
											$this->config->item("email_from_address", "momo"),
											$this->config->item("email_from_name", "momo"),
											$editTarget->getUser()->getEmail(),
											EmailTemplates::$ooRequestStatusChangeSubject,
											null,
											EmailTemplates::$ooRequestStatusChangeMessage,
											$messageMap
										);	
					
				}									
													
			}
			else {
				// update the booking as indicated
				$editTarget = $ooManager->updateOOBooking(
												$editTarget,
												$editTarget->getOOBookingType(),
												DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("fromDate")),
												DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("untilDate")),
												$halfDaysAM,
												$halfDaysPM,
												$autoAssignWorktimeCredit
											);
			}
			
			// recompute the overtime lapses
			$enforcementService->recomputeAllOvertimeLapses($editTarget->getUser());
			
			// recompute the vacation lapses
			$enforcementService->recomputeAllVacationLapses($editTarget->getUser());	

			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($editTarget->compileStateDigest());		
		
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									OOManager::MANAGER_KEY,
									"Updated Out-of-Office Booking",
									$eventDescription
								);
			
			// commit TX
			$con->commit();
			
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("ManageBookingsController:updateBooking() - an error has occurred while while attempting to update the oo booking with id: " . $editTarget->getId(), 0, $ex);
		}					
			
		// throw back to overview
		redirect("/managebookings");						
	}
	
	
	/**
	 *  "Deletes" the indicated booking
	 *  
	 *  @param	integer		$bookingId		the id of the booking to delete
	 */
	public function deleteBooking($bookingId) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_DELETE_ALL_BOOKINGS,
										Permissions::OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS
									)	
								);
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$securityService = $this->getCtx()->getSecurityService();	
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		$tagManager = $this->getCtx()->getTagManager();
		
		// get db connection
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get ref to delete target and the associated user
			$deleteTarget = $ooManager->getOOBookingById($bookingId);
			$deleteTargetUser = $deleteTarget->getUser();
			
			// get active user
			$activeUser = $this->getCtx()->getUser();
			
			// populate audit event description pre delete
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($deleteTarget->compileStateDigest());
			
			
			// before proceeding with delete, we clear possible "week complete" flags on the weeks that the booking presently covered
			$tagManager->deleteWeekTagByTypeAndDateRange(
															\Tag::TYPE_WEEK_COMPLETE,
															$deleteTargetUser,
															$deleteTarget->getStartDate(),
															$deleteTarget->getEndDate()
														);
			
			//
			// process delete according to permissions				
			if ( $this->authorize(Permissions::OO_BOOKINGS_DELETE_ALL_BOOKINGS, true) ) {
				
				// if this permission is granted, we may proceed without further ado
				$ooManager->deleteOOBooking($deleteTarget);
				
			}
			else if ( $this->authorize(Permissions::OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS, true) ) {
	
				// we may delete only if the active user is assigned to a team for which
				// the active user is designated team leader
				
				if ( $activeUser->isUserTeamLeaderOfTeam($deleteTarget->getUser()->getPrimaryTeam() ) ) {
					$ooManager->deleteOOBooking($deleteTarget);
				}
				else {
					throw new MomoException("ManageUserController:deleteBooking() - The active user is not permitted to delete the booking with id: " . $bookingId);
				}
				
			}
			
			// recompute the overtime lapses
			$enforcementService->recomputeAllOvertimeLapses($deleteTargetUser);
			
			// recompute the vacation lapses
			$enforcementService->recomputeAllVacationLapses($deleteTargetUser);	
			
			// write operation to audit trail											
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									OOManager::MANAGER_KEY,
									"Deleted OO Booking",
									$eventDescription
								);	
			
			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("ManageBookingsController:deleteBooking() - an error has occurred while while attempting to delete the oo booking with id: " . $bookingId, 0, $ex);
		}			
		
		// redisplay default view
		redirect("/managebookings");
	}
	
	
	/**
	 *  Displays the "create new booking" form for the indicated user
	 *  
	 *  @param	integer		$userId
	 *  
	 */
	public function displayNewBookingForm($userId) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_CREATE_BOOKING,
										Permissions::OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING
									)	
								);
		
		// get refs to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// get a reference to the user that the booking is for
		$targetUser = $userManager->getUserById($userId);		

		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		//
		// bookings may be made from entry date onwards, provided that the date lies beyond the start date of application use
		
		// compute earliest request date
		$earliestBookingDate = ($targetUser->getEntryDate() > $appUseStartDate) ? $targetUser->getEntryDate() : $appUseStartDate;
								 
		$earliestBookingDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($earliestBookingDate),
											"month" => DateTimeHelper::getMonthFromDateTime($earliestBookingDate),
											"day" => DateTimeHelper::getDayFromDateTime($earliestBookingDate)
										 );										 

		// bookings may be made up to latest day of last workplan, provided employment period does not end before that time
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		$latestBookingDate = ($targetUser->getExitDate() < $lastWorkplanDate) ? $targetUser->getExitDate() : $lastWorkplanDate;
		
		$latestBookingDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestBookingDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestBookingDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestBookingDate)
										);	
						
		// prepare view data
		$data["component"] = "components/component_form_oobooking.php";
		$data["component_title"] = "New Booking for " . $targetUser->getFullName();
		$data["component_mode"] = "new";
	
		// set the date information indicating the time range for which booking may be made
		$data["component_date_elems_earliest_booking"] = $earliestBookingDateElems;
		$data["component_date_elems_latest_booking"] = $latestBookingDateElems;
		$data["component_user_target"] = $targetUser;
		
		// the OO entry types available
		$data["component_bookingtypes"] = $bookingTypeManager->getAllActiveTypes();
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit booking" form
	 *  
	 *  @param integer	$bookingId		- the booking for which to display the edit form
	 */
	public function displayEditBookingForm($bookingId) {
		
		// authorize
		$this->authorizeOneInList(	array(
										Permissions::OO_BOOKINGS_EDIT_ALL_BOOKINGS,
										Permissions::OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING
									)	
								 );
		
		// get refs to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$userManager = $this->getCtx()->getUserManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// query for edit target
		$editTarget = $ooManager->getOOBookingById($bookingId);
			
		//
		// prepare booking origin dependent information
		
		// if of request origin, set prepare form title and status info accordingly
		if ( $editTarget->getOORequest() !== null ) {
			
			$request = $editTarget->getOORequest();
			
			// viewing a request based booking forces status from "pending" to "open"
			if ( $request->getStatus() == \OORequest::STATUS_OPEN ) {
				$ooManager->updateOORequestStatus($request, \OORequest::STATUS_PENDING);
			}
			
			$formTitle = "Edit Booking for " . $editTarget->getUser()->getFullName() . " (request origin)";

			// the accessible statuses depend on current status as follows
			// "pending" -> "denied", "approved"
			// "approved" -> "denied"
			// "denied" -> "approved"
			
			if ( $request->getStatus() == \OORequest::STATUS_PENDING ) {
				// if the current status is pending, it may be approved, denied, or left unchanged
				$allowedRequestStatusMap = array (
					OORequest::STATUS_PENDING 	=> OORequest::$STATUS_MAP[OORequest::STATUS_PENDING],
					OORequest::STATUS_APPROVED 	=> OORequest::$STATUS_MAP[OORequest::STATUS_APPROVED],
					OORequest::STATUS_DENIED 	=> OORequest::$STATUS_MAP[OORequest::STATUS_DENIED],
				);
			}
			else {
				// the status is either "approved" or "denied", we may leave it unchanged or switch to the opposite state
				$allowedRequestStatusMap = array (
					OORequest::STATUS_APPROVED 	=> OORequest::$STATUS_MAP[OORequest::STATUS_APPROVED],
					OORequest::STATUS_DENIED 	=> OORequest::$STATUS_MAP[OORequest::STATUS_DENIED]
				);
			}
			
		}
		// if of management origin, just prepare form title accordingly
		else {
			$allowedRequestStatusMap = null;
			$formTitle = "Edit Booking for " . $editTarget->getUser()->getFullName() . " (management origin)";
		}
		
		
		// get a reference to the user that the booking is for
		$targetUser = $editTarget->getUser();

		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		
		//
		// bookings may be made from entry date onwards, provided that the date lies beyond the start date of application use
		
		// compute earliest booking date
		$earliestBookingDate = ($targetUser->getEntryDate() > $appUseStartDate) ? $targetUser->getEntryDate() : $appUseStartDate;
								 
		$earliestBookingDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($earliestBookingDate),
											"month" => DateTimeHelper::getMonthFromDateTime($earliestBookingDate),
											"day" => DateTimeHelper::getDayFromDateTime($earliestBookingDate)
										 );										 

		//
		// bookings may be made up to latest day of last workplan, provided employment period does not end before that time
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		$latestBookingDate = ($targetUser->getExitDate() < $lastWorkplanDate) ? $targetUser->getExitDate() : $lastWorkplanDate;
		
		$latestBookingDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestBookingDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestBookingDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestBookingDate)
										);	
										
		// prepare view data
		$data["component"] = "components/component_form_oobooking.php";
		$data["component_title"] = $formTitle;
		$data["component_mode"] = "edit";
	
		$data["component_edit_target"] = $editTarget;
		
		// set the date information indicating the time range for which entries are allowed
		$data["component_date_elems_earliest_booking"] = $earliestBookingDateElems;
		$data["component_date_elems_latest_booking"] = $latestBookingDateElems;
		
		// the user to which this booking applies to
		$data["component_user_target"] = $targetUser;

		// the OO entry types available
		$data["component_bookingtypes"] = $bookingTypeManager->getAllActiveTypes();
		
		// the allowed request statuses (only applicable to request originated bookings)
		$data["component_map_allowed_request_status"] = $allowedRequestStatusMap;
			
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Returns a JSON encoded message indicating whether the indicated start and end dates conflict with an existing booking.
	 *  Optionally, an existing booking may be excluded from the check by passing the corresponding booking id.
	 *  
	 *  A conflict exists, if:
	 *  	
	 *  	- there already exists an OO Booking that intersects with the indicated date range
	 *  	- there exist RegularEntry (or, implicitly, ProjectEntry) instances on Day instances that lie within the indicated date range
	 *  
	 *  
	 *  @param	integer		$userId				- the user for which to check for booking conflicts
	 *  @param	string		$startDate			- the start date of the range to check
	 *  @param	string		$endDate			- the end date of the range to check
	 *  @param	integer		$excludeBookingId	- (optional) exclude the booking from the check (used when editing an existing booking)
	 *  
	 *  @return string	- json encoded message
	 */
	public function checkBookingConflictJson($userId, $startDate, $endDate, $excludeBookingId=null) {
		
		$hasConflict = false;		// indicates whether a bookign conflict was detected
		$conflictSource = null;		// E [null, 'oobooking', 'regularentry'] indicates the source of the conflict
		
		$message = array();
		
		$ooManager = $this->getCtx()->getOOManager();
		$entryManager = $this->getCtx()->getEntryManager();
		$userManager = $this->getCtx()->getUserManager();
		
		// get a reference to target user
		$targetUser = $userManager->getUserById($userId);
														 
		// get oo bookings that intersect the indicated date range														 
		$ooBookingsInRange = $ooManager->getOOBookingsInDateRange(
													$targetUser,
													DateTimeHelper::getDateTimeFromStandardDateFormat($startDate),
													DateTimeHelper::getDateTimeFromStandardDateFormat($endDate),
													false
												);
												
		// look at all bookings found and flag a conflict as soon as we find a booking
		// other than the excluded one (provided one is set)
		foreach ( $ooBookingsInRange as $curBooking ) {
		
			if ( $curBooking->getId() == $excludeBookingId ) {
				 continue;
			}
			
			$hasConflict = true;
			$conflictSource = "oobooking";
		}
									

		// if there is no booking conflict, we need to check for conflict with regular entries
		// that lie on Day instances not contained in the excluded booking (provided one is set)
		if ( ! $hasConflict ) {
			
			$regularEntriesInRange = $entryManager->getRegularEntriesForDateRangeAndUser(
																					$targetUser,
																					$startDate,
																					$endDate
																				);
																											
			// process according to whether there is a booking to exclude or not
			if ( $excludeBookingId === null ) {
				
				// there is no booking to exclude, hence if regular entries occur in the date range of a proposed oo booking,
				// this always indicates a conflict
				if ( $regularEntriesInRange->count() > 0 ) {
					$hasConflict = true;
					$conflictSource = "regularentry";
				}	
				
			}
			else {
				// there is a booking to exclude, only those regular entries that fall outside the excluded booking
				// indicate a conflict
				
				// determine start/end dates of the excluded booking
				$excludedBooking = $ooManager->getOOBookingById($excludeBookingId);
				
				$excludedBookingStartDate = $excludedBooking->getStartDate();
				$excludedBookingEndDate = $excludedBooking->getEndDate();
				
				foreach ( $regularEntriesInRange as $curEntry ) {
					
					if ( 	($curEntry->getDay()->getDateOfDay() < $excludedBookingStartDate)
						||  ($curEntry->getDay()->getDateOfDay() > $excludedBookingEndDate)	) {
						
						// there is a regular entry in conflict with the proposed start/end dates
						// -- flag conflict and break floop
						
						$hasConflict = true;
						$conflictSource = "regularentry";
						
						break;
					}
				}
			}																																
		}
		
		// set and render result in json format
		$message["hasConflict"] = $hasConflict;
		$message["conflictSource"] = $conflictSource;														 
		
		print(json_encode($message));
	
	}
	
}