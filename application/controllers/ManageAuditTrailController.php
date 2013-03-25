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
use momo\core\helpers\ManagerHelper;
use momo\core\security\Roles;
use momo\core\security\Permissions;

/**
 * ManageAuditTrailController
 * 
 * Exposes the client actions needed to work with the audit trail.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageAuditTrailController extends Momo_Controller {
	
	/**
	 *  Default action displays the entire audit trail
	 */
	public function index() {
		$this->viewAuditTrail();
	}
	
	
	/**
	 *  Displays the audit trail for the indicated parameters   
	 */
	public function viewAuditTrail() {
		
		// authorize call
		$this->authorize(Permissions::AUDIT_TRAIL_LIST_EVENTS);
		
		// get ref to managers needed
		$auditTrailManager = $this->getCtx()->getAuditTrailManager();
		$userManager = $this->getCtx()->getUserManager();
		
		//
		// prepare post parameters for call to audit trail manager
		
		// target user
		$targetUser = null;
		if ( $this->input->post('targetUserId') !== false ) {

			// if a user id is passed, we retrieve the indicated user and persist user id to session
			// "-1" (all users) is mapped to null, as this tells audittrailmananger not to restrict by user
			$targetUserId = $this->input->post('targetUserId');
			$targetUser = ($targetUserId != "-1") ? $userManager->getUserById($targetUserId) : null;
						
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.targetuserid', $targetUserId);
		}
		else {
			// in case, no valid user is passed, we attempt to restore the value from session
			if ( $this->session->userdata('manageaudittrailcontroller.view_audittrail.targetuserid') !== false ) {
				
				// retrieve id from session
				$targetUserId = $this->session->userdata('manageaudittrailcontroller.view_audittrail.targetuserid');
				
				// retrieve indicated user, map "-1" (all users) to null
				$targetUser = ($targetUserId != "-1") ? $userManager->getUserById($targetUserId) : null;
			}	
		}
		
		//
		// "from" date
		$fromDate = null;
		if ( 	   ($this->input->post('fromDate') !== false)
				&& ($this->input->post('fromDate') != "") ) {
			//
			// if there is a valid date passed, we accept it and persist it to the session
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('fromDate'));
			
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.fromdate', $fromDate);
		}
		else if ( $this->input->post('fromDate') !== false ) {
			// if there is no from date passed, we set the query start date to the start of the current week
			$fromDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
			
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.fromdate', $fromDate);
		}
		else {
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('manageaudittrailcontroller.view_audittrail.fromdate') !== false ) {
				$fromDate = $this->session->userdata('manageaudittrailcontroller.view_audittrail.fromdate');
			}
			else {
				// if there is no session value to restore, we set the from date to the start of the current week		
				$fromDate = DateTimeHelper::getStartOfWeek(DateTimeHelper::getDateTimeForCurrentDate());
			}			
		}
			
		//
		// "until" date
		$untilDate = null;
		if ( 	   ($this->input->post('untilDate') !== false)
				&& ($this->input->post('untilDate') != "") ) {
			//		
			// if there is a valid date passed, we accept it and persist it to the session
			$untilDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			$untilDateForQuery = DateTimeHelper::addDaysToDateTime($untilDate, 1);;
			
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.untildate', $untilDate);
		}
		else if ( $this->input->post('untilDate') !== false ) {
				
			// if there is an empty date passed, we default to a one week range (based on current from date)	
			$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, 6);
			
			// as we're comparing to a timestamp, the until date needs to be compensated by one day for it to
			// be inclusive of the intended date
			$untilDateForQuery = DateTimeHelper::addDaysToDateTime($fromDate, 1);
			
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.untildate', $untilDate);
		}
		else {
			
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('manageaudittrailcontroller.view_audittrail.untildate') !== false ) {
				// restore from session
				$untilDate = $this->session->userdata('manageaudittrailcontroller.view_audittrail.untildate');
				// as we're comparing to a timestamp, the until date needs to be compensated by one day for it to
				// be inclusive of the intended date
				$untilDateForQuery = DateTimeHelper::addDaysToDateTime($untilDate, 1);
			}
			else {
				// if there is no session value to restore, we default to a one week range (based on current from date)	
				$untilDate = DateTimeHelper::addDaysToDateTime($fromDate, 6);
				// again, we compensate for one day due to timestamp comparison
				$untilDateForQuery = DateTimeHelper::addDaysToDateTime($untilDate, 1);
			}	
		}
		
	
		
		//
		// target module
		$targetModule = null;
		if ( $this->input->post('targetModule') !== false ) {

			// if a valid target module is passed, we retrieve the passed value and persist it to session
			// -- "-1" is mapped to null for the purpose of communication with audittrailmanager (indicates no source restriction)
			$targetModule = ($this->input->post('targetModule') != "-1") ? $this->input->post('targetModule') : null;
			
			// persist to session
			$this->session->set_userdata('manageaudittrailcontroller.view_audittrail.targetmodule', $targetModule);
		}
		else {
			// in case, no valid module is passed, we attempt to restore the value from session
			if ( $this->session->userdata('manageaudittrailcontroller.view_audittrail.targetmodule') !== false ) {
				$targetModule = $this->session->userdata('manageaudittrailcontroller.view_audittrail.targetmodule');
			}
		}
		
		//
		// query for audit events
		$auditEvents = $auditTrailManager->getAuditEvents(
												$targetUser,
												$targetModule,
												$fromDate,
												$untilDateForQuery,
												"desc"
											);
																
		
		// prepare view data
		$data["component"] = "components/component_list_auditevents.php";
		$data["component_title"] = "Audit Trail";
		
		$data["component_auditevents"] = $auditEvents;
		$data["component_users"] = $userManager->getAllUsers();
		$data["component_audited_managers_map"] = ManagerHelper::$MANAGERS_AUDITED_MAP;
		$data["component_targetuser"] = $targetUser;
		$data["component_targetmodule"] = $targetModule;
		$data["component_fromdate"] = $fromDate;
		$data["component_untildate"] = $untilDate;
		
		// render view
		$this->renderView($data);
	}
	
	
}