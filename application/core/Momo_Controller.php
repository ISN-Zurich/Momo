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

/**
 * Momo_Controller
 * 
 * Defines commonly needed controller functionality with regard to security, view rendering
 * and site maintenance.
 * 
 * @author  Francesco Krattiger
 * @package momo.controllers
 */
abstract class Momo_Controller extends CI_Controller {

	function __construct() {
		
		parent::__construct();
				
		// refuse IE access
		// this can most likely be relaxed for IE >= v10
		if ( $this->agent->browser() == 'Internet Explorer' ) {
			// abort execution
			$this->abortWithNotification($this->config->item("ie_access_refused_title", "momo"), $this->config->item("ie_access_refused_message", "momo"));		
		}	
		
		
		// check for site offline status
		if ( $this->config->item("site_offline_status", "momo") == true ) {
		
			// site offline status does not apply to CLI originated requests
			if ( ! $this->input->is_cli_request() ) {
					
				// allow privileged IP to pass in offline mode
				if ( $_SERVER['REMOTE_ADDR'] != $this->config->item("site_offline_privileged_ip", "momo") ) {
					// abort execution
					$this->abortWithNotification($this->config->item("site_offline_title", "momo"), $this->config->item("site_offline_message", "momo"));
				}
			}
		}
	}
	
	/**
	 * Returns the ApplicationContext
	 */
	function getCtx() {
		return \momo\core\application\ApplicationContext::getInstance();
	}
	
	/**
	 * Tries to authorizes the current session against the indicated permission.
	 * 
	 * The parameter "$returnOnFailure" determines the behavior of the method as follows:
	 * 
	 * $returnOnFailure = false (default):
	 * the method does not return a result if the authorization fails, rather it displays an
	 * appropriate error page and ceases processing.
	 * 
	 * $returnOnFailure = true:
	 * the method always returns "true" or "false", according to the result of the authorization test.
	 * 
	 * @param string $permission
	 * @param string $returnValue
	 * 
	 * @return	boolean		(see above)
	 * 
	 */
	public function authorize($permission, $returnOnFailure = false) {
		
		$securityService = $this->getCtx()->getSecurityService();
		
		$authorized = $securityService->authorize($permission);
		
		if ( (! $returnOnFailure) &&  (! $authorized) ) {
			// abort execution
			$this->abortWithNotification("Insufficient Permissions", "Sadly, you lack the requisite access rights for this function. ");
		}
		
		return $authorized;
	}

	
	/**
	 * Tries to authorizes the current session against at least one of the indicated permissions
	 * 
	 * The parameter "$returnOnFailure" determines the behavior of the method as follows:
	 * 
	 * $returnOnFailure = false (default):
	 * the method does not return a result if the authorization fails, rather it displays an
	 * appropriate error page and ceases processing.
	 * 
	 * $returnOnFailure = true:
	 * the method always returns "true" or "false", according to the result of the authorization test.
	 * 
	 * @param array 	$permissions
	 * @param string 	$returnValue
	 * 
	 * @return	boolean		(see above)
	 * 
	 */
	public function authorizeOneInList($permissions, $returnOnFailure = false) {
		
		// get managers needed
		$securityService = $this->getCtx()->getSecurityService();
		
		
		
		// process list as in terms of logical OR
		$autorized = false;
		foreach ( $permissions as $curPerm ) {
			if ( $authorized = $securityService->authorize($curPerm) ) {
				break;
			}
		}
		
		if ( (! $returnOnFailure) &&  (! $authorized) ) {
			// abort execution
			$this->abortWithNotification("Insufficient Permissions", "Sadly, you lack the requisite access rights for this function. ");
		}
		
		return $authorized;
	}
	
	
	/**
	 * Renders the master view
	 * 
	 * @param 	array 	$viewData	- the data array to pass to the master view, its contents determine all further rendering steps
	 */
	public function renderView($viewData) {
				
		// get managers needed
		$ooManager = $this->getCtx()->getOOManager();
		
		//
		// if the user is logged in, determine whether the "Requests" function may be accessed
		//
		// access is granted, if:
		//
		// the user *is not* of role "manager" or "administrator"
		// *and* the user is a primary member of some team
		// *and* the user is not team leader of that primary team
		// *otherwise* if the user is team leader of the team he is a primary member of, then "requests" may be accessed,
		// 		if the user is member of a higher order team
		//		*and* the user is not team leader of that highest order team
		//
		$activeUser = null;
		$accessRequests = false;
			
		if ( $this->session->userdata('securityservice.user_authenticated') ) {
			$activeUser = $this->getCtx()->getUser();
			$accessRequests = $ooManager->userMayAccessOORequests($activeUser);
		}
		
		//
		// set view data bound to master view and miscellaneous widgets
		
		// the site name for use by the master view component
		$viewData["master_site_name"] = $this->config->item("site_name", "momo");
		// the access requests flag for use by the navbar widget
		$viewData["widget_navbar_access_requests"] = $accessRequests;
		// the the active user for use by the navbar widget
		$viewData["widget_navbar_active_user"] = $activeUser;
		
		
		$this->load->view('master.php', $viewData);
	}	
	
	
	
	/**
	 * Issues the indicated notification and aborts execution
	 * 
	 * @param	$title	the notification title
	 * @param	$msg	the message to issue
	 */
	private function abortWithNotification($title, $msg) {
		
		// populate view params
		$data["component"] = "components/component_other_notification.php";
		
		$data["component_title"] = $title;
		$data["component_message"] = $msg;
		$data["component_mode"] = "info";
		$data["component_target"] = null;
		
		// render master view
		$this->renderView($data);
		
		// force CI to output the view and stop processing
		$this->output->_display();
		exit();
	}
	
}