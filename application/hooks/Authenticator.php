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
 * Authenticator
 * 
 * Monitors application access.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.hooks
 */
class Authenticator
{
	
	/**
	 * Makes sure sessions accessing the application are authenticated.
	 * 
	 * By default, the method redirects unauthenticated sessions to the login screen.
	 * 
	 * Furthermore, the method ensures that the active session has status "enabled" (User attribute)
	 * and no active password reset token (User attribute). If either of these requirements are not met,
	 * the session is logged out and redirected to the login screen.
	 * 
	 * Furthermore, the following routes are accessible irrespective of authentication state
	 * 
	 * 		- "/manageusers/displaysetpasswordbytokenform"
	 * 		- "/manageusers/setuserpasswordbytoken"
	 * 		- "/manageusers/displayrecoverpasswordform"
	 * 		- "/manageusers/resetuserpasswordbyemailandlogin"
	 * 
	 * Finally, CLI originated requests need not be authenticated
	 */
	public function checkAuthenticatedAndEnabled() {	
		
		// get ci instance
		$ci = & get_instance();
		
		// get managers and such
 		$ctx =  \momo\core\application\ApplicationContext::getInstance();
		$usermanager = $ctx->getUserManager();
		$securityService = $ctx->getSecurityService();
		
		//
		// make sure PATH_INFO exists
		isset($_SERVER['PATH_INFO']) ? null : $_SERVER['PATH_INFO'] = "";
		
		// first off, check if this is a call to "password reset by token" form
		// --> if so, log out if needed and pass the call 
		if ( (strpos($_SERVER['PATH_INFO'], "/manageusers/displaysetpasswordbytokenform") !== false ) ) {
			
			// if indicated, logout active session
			if ( $ci->session->userdata('securityservice.user_authenticated') ) {	
	 			$securityService->logout();
	 		}
 		}
		// CLI originated requests need not authenticate
		// -- at present this would be limited to CRON originated calls
		else if ( $ci->input->is_cli_request() ) {
			// NOOP
 		}
		// in case this is a call to "password reset by token" (the action now), we let it pass
		// --> the token is validated down the line
		else if ( (strpos($_SERVER['PATH_INFO'], "/manageusers/setuserpasswordbytoken") !== false ) ) {
			// NOOP
 		}
 		// in case, this is a call to the "recover password" form, we let it pass
		else if ( (strpos($_SERVER['PATH_INFO'], "/manageusers/displayrecoverpasswordform") !== false ) ) {
			// NOOP
 		}
		// in case, this is a call to the "reset password by email and login" operation, we let it pass
		else if ( (strpos($_SERVER['PATH_INFO'], "/manageusers/resetuserpasswordbyemailandlogin") !== false ) ) {
			// NOOP
 		}
 		// a standard call, so we apply standard procedure
 		// 1. check authentication --> force to login if not authenticated
 		// 2. if authenticated, make sure still enabled and not subject to password reset token
 		//		-> if either of these conditions not met, logout and throw back to login screen
 		else if ( ! $ci->session->userdata('securityservice.user_authenticated') ) {
 				
			// throw back to authentication, provided this call is not going there already
	 		if ( (strpos($_SERVER['PATH_INFO'], "/security/authenticate") === false ) ) {
	 			//
	 			// throw back to default view
	 			redirect("/security/authenticate");
	 		}
 		}
 		//
 		// user is authenticated, check if still enabled or subject to password reset token
 		// in both cases, we logout and throw back to login dialog
 		else {
 	
 			$user = $usermanager->getUserByLogin($ci->session->userdata('securityservice.user_login'));
 			
 			if ( ( ! $user->getEnabled()) || ($user->getPasswordResetToken() != null) ) {
 			
 				//
 				// user is not enabled, or has active password reset token
 	
		 		// logout and throw back to login dialog
		 		$securityService->logout();
		 		redirect("/security/authenticate");
 			}
 			else {
 				// user is authenticated, enabled and not subject to password reset token
 				
 				// access is allowed, the only thing that remains to be done, is to redirect
 				// potential calls to '/security/authenticate' to default route
	 			if ( (strpos($_SERVER['PATH_INFO'], "/security/authenticate") !== false ) ) {
		 			//
		 			// throw back to default view
		 			redirect("/");
		 		}
 			
 			}
 		} 		
	}

}