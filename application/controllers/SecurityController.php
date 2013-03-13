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
 * ReportsController
 * 
 * Exposes security related client actions.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class SecurityController extends Momo_Controller {
	
	/**
	 * uthenticates the current session
	 *
	 * 	- if successful, forward to default controller (see config/route.php) 
	 * 	- if not successful, display login
	 */
	public function authenticate() {
		
		if ( ! $this->session->userdata('authenticated') ) {
			
			// get ref to security manager
			$securityService = $this->getCtx()->getSecurityService();
			
			// we're not authenticated, give it a try...
			$authenticated = false;
			
			// get credentials passed
			$login = $this->input->post('login');
			$password = $this->input->post('password');
					
			// ignore, if either post value is not present (i.e. "false")
			if ( $login && $password ) {
				// authenticate		
				$securityService->authenticate($login, $password);
							
				// throw do default route
				// -- this causes us to pass through Authenticator hook again
				redirect("/");
			}
			
			if ( ! $authenticated ) {
				// couldn't authenticate, display login form
				$this->displayLoginForm();
			}
			
		}
		else {
			// we're authenticated, pass on to default route
			redirect("/");
		}
	}
	
	
	/**
	 * displays the login form
	 */
	public function displayLoginForm() {	
		
		$data["component"] = "static/static_form_login.php";
		$data["component_title"] = "Welcome to " . $this->config->item("site_name", "momo");
		// render view
		$this->renderView($data);
	}
	
	/**
	 *  
	 * logs out the current session
	 */
	public function logout() {	
		
		// logout
		$this->getCtx()->getSecurityService()->logout();
				
		// back to default route
		redirect("/");	
	}
	
}