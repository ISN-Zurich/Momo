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

use momo\usermanager\exceptions\UserNotFoundException;
use momo\core\helpers\DateTimeHelper;
use momo\core\security\Roles;
use momo\core\security\Permissions;
use momo\emailservice\EmailService;
use momo\emailservice\EmailTemplates;


/**
 * ManageRequestsController
 * 
 * Exposes the client actions needed to work with out-of-office requests.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageRequestsController extends Momo_Controller {
	
	public function __construct() {
		parent::__construct();
		
		//
		// throw back to root if user may not access requests
		
		// note: as this check applies to all actions callable on the controller we perform it during construction time
		//
		//		 as it were, if the session times out, this raises an exception -
		//		 a situation that does not normally arise as the "Authenticator" reroutes
		//		 to the login screen before any access to the user object is attempted.
		//
		//		 long story short: we catch and squelch the exception should it arise, the Authenticator
		//		 follows immediately after controller construction and will reroute the call accordingly		
		try {
			$ooManager = $this->getCtx()->getOOManager();
			if ( ! $ooManager->userMayAccessOORequests($this->getCtx()->getUser()) ) {
				redirect("/");
			}
		}
		catch (UserNotFoundException $ex) {
			// NOOP
		}
		
	}
	
	
	/**
	 *  Default action displays list of active user's requests
	 */
	public function index() {
		
		// authorize
		$this->authorize(Permissions::OO_REQUESTS_LIST_REQUESTS);
		
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$userManager = $this->getCtx()->getUserManager();
		$teamManager = $this->getCtx()->getTeamManager();						
												
		//
		// prepare view data			
		$data["component"] = "components/component_list_oorequests.php";;
		$data["component_title"] = "Requests";
		
		$data["component_requests"] = $ooManager->getAllOORequestsForUser($this->getCtx()->getUser());
		
		// render view
		$this->renderView($data);
	}
	

	/**
	 *  Creates a request
	 */
	public function createRequest() {
		
		// authorize call
		$this->authorize(Permissions::OO_REQUESTS_CREATE_REQUEST);
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$emailService = $this->getCtx()->getEmailService();
		$teamManager = $this->getCtx()->getTeamManager();
		
		$activeUser = $this->getCtx()->getUser();
		
		//
		// create request of indicated type
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
				
		// create the request
		$ooManager->createOORequest(
										$this->getCtx()->getUser(),
										$bookingTypeManager->getTypeById($this->input->post("typeId")),
										DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("fromDate")),
										DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("untilDate")),
										$halfDaysAM,
										$halfDaysPM,
										trim($this->input->post("originatorComment"))
									);
											
		//												
		// notify concerned team leaders											
											
		// compile message map
		$messageMap = array();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_USER_FULL_NAME]	= $activeUser->getFullName();
		$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_GENERAL_URL] 	= $this->config->item("base_url") . "managebookings";
		
		// the team leaders of the highest order team membership need to be notified
		$highestOrderTeam = $teamManager->getHighestOrderTeamMembershipForUser($activeUser, $activeUser->getPrimaryTeam());
		
		// obtain a list of team leader email addresses for highest order team
		$teamLeaderEmailAddresses = $highestOrderTeam->getTeamLeaderEmailAddresses();
												
		// send notification email
		$emailService->sendEmailFromTemplate(
								$this->config->item("email_from_address", "momo"),
								$this->config->item("email_from_name", "momo"),
								$teamLeaderEmailAddresses,
								EmailTemplates::$ooRequestCreatedNotificationSubject,
								null,
								EmailTemplates::$ooRequestCreatedNotificationMessage,
								$messageMap
							);									
		
										
		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Request Created";
		$data["component_message"] = "Thank you. Momo has forwarded your request to your team leaders.";
		$data["component_mode"] = "info";
		$data["component_target"] = "/managerequests";
		
		// render view
		$this->renderView($data);							
		
	}
	
	
	/**
	 *  Updates a request
	 */
	public function updateRequest() {
		
		// authorize call
		$this->authorize(Permissions::OO_REQUESTS_EDIT_REQUEST);
		
		// get ref to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		
		
		// get reference to edit target
		$editTarget = $ooManager->getOORequestById($this->input->post("editTargetId"));
		
		//
		// update request as indicated
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
				
		// update the request
		// note, updating a request always forces it back to status "open"
		$ooManager->updateOORequest(
										$editTarget,
										\OORequest::STATUS_OPEN,
										$editTarget->getOOBooking()->getOOBookingType(),
										DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("fromDate")),
										DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post("untilDate")),
										$halfDaysAM,
										$halfDaysPM,
										trim($this->input->post("originatorComment"))
									);
		
		
		// prepare view data
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Request Created";
		$data["component_message"] = "Thank you. Momo will forward your amended request to your team leader.";
		$data["component_mode"] = "info";
		$data["component_target"] = "/managerequests";
		
		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 *  "Deletes" the indicated request
	 *  
	 *  @param 	integer		$requestId		the id of the request to delete
	 */
	public function deleteRequest($requestId) {
		
		// authorize call
		$this->authorize(Permissions::OO_REQUESTS_DELETE_REQUEST);
		
		// get ref to bookings manager
		$ooManager = $this->getCtx()->getOOManager();
		
		// delete the request
		$ooManager->deleteOORequest($ooManager->getOORequestById($requestId));
		
		// redisplay default view
		redirect("/managerequests");
	}
	
	
	/**
	 *  Displays the "create new request" form
	 */
	public function displayNewRequestForm() {
		
		// authorize call
		$this->authorize(Permissions::OO_REQUESTS_CREATE_REQUEST);
		
		// get refs to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// active user
		$activeUser = $this->getCtx()->getUser();
		
		// last day of last workplan in existence
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		
		//
		// requests may be made from entry date onwards, provided that the date lies beyond the start date of application use
		
		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		// compute earliest request date
		$earliestRequestDate = ($activeUser->getEntryDate() > $appUseStartDate) ? $activeUser->getEntryDate() : $appUseStartDate;
		
		// request may be made up to latest day of last workplan, provided employment period does not end before that time
		$latestRequestDate = ($activeUser->getExitDate() < $lastWorkplanDate) ? $activeUser->getExitDate() : $lastWorkplanDate;
		
		$earliestRequestDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($earliestRequestDate),
											"month" => DateTimeHelper::getMonthFromDateTime($earliestRequestDate),
											"day" => DateTimeHelper::getDayFromDateTime($earliestRequestDate)
										 );			
							
		// requests may be made up to last day of latest workplan in existence
		// compile this into an element array as well
		$latestRequestDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestRequestDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestRequestDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestRequestDate)
										);		
					
		// prepare view data
		$data["component"] = "components/component_form_oorequest.php";
		$data["component_title"] = "New Request";
		$data["component_mode"] = "new";
	
		// set the date information indicating the time range for which booking may be made
		$data["component_date_elems_earliest_request"] = $earliestRequestDateElems;
		$data["component_date_elems_latest_request"] = $latestRequestDateElems;
		$data["component_user_target"] = $activeUser;
		
		// the OO entry types available
		$data["component_bookingtypes"] = $bookingTypeManager->getAllActiveTypes();
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit request" form
	 *  
	 *  @param integer	$requestId		- the request for which to display the edit form
	 */
	public function displayEditRequestForm($requestId) {
		
		// authorize call
		$this->authorize(Permissions::OO_REQUESTS_EDIT_REQUEST);
		
		// get refs to managers needed
		$ooManager = $this->getCtx()->getOOManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		
		// query for edit target
		$editTarget = $ooManager->getOORequestById($requestId);
		
		// get a reference to the active user
		$activeUser = $this->getCtx()->getUser();
		
		// today's date
		$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
		
		//
		// requests may be made from entry date onwards, provided that the date lies beyond the start date of application use
		
		// last day of last workplan in existence
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		
		// obtain date at which application use starts (from application settings)
		$appUseStartDate = DateTimeHelper::getDateTimeFromStandardDateFormat($settingsManager->getSettingValue(\Setting::KEY_APPLICATION_USE_START_DATE));
		
		// compute earliest request date
		$earliestRequestDate = ($activeUser->getEntryDate() > $appUseStartDate) ? $activeUser->getEntryDate() : $appUseStartDate;
		
		// request may be made up to latest day of last workplan, provided employment period does not end before that time
		$latestRequestDate = ($activeUser->getExitDate() < $lastWorkplanDate) ? $activeUser->getExitDate() : $lastWorkplanDate;
		
		$earliestRequestDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($earliestRequestDate),
											"month" => DateTimeHelper::getMonthFromDateTime($earliestRequestDate),
											"day" => DateTimeHelper::getDayFromDateTime($earliestRequestDate)
										 );			
							
		// requests may be made up to last day of latest workplan in existence
		// compile this into an element array as well
		$latestRequestDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestRequestDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestRequestDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestRequestDate)
										);		
		
					
		// prepare view data
		$data["component"] = "components/component_form_oorequest.php";
		$data["component_title"] = "Edit Request";
		$data["component_mode"] = "edit";
	
		$data["component_edit_target"] = $editTarget;
		
		// set the date information indicating the time range for which entries are allowed
		$data["component_date_elems_earliest_request"] = $earliestRequestDateElems;
		$data["component_date_elems_latest_request"] = $latestRequestDateElems;
		$data["component_user_target"] = $activeUser;
		
		// the OO entry types available
		$data["component_bookingtypes"] = $bookingTypeManager->getAllActiveTypes();
		
		// render view
		$this->renderView($data);
	}
	

}