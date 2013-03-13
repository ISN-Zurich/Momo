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

namespace momo\core\helpers;

use momo\core\security\Permissions;

/**
 * MenuHelper
 * 
 * Maps menus to permissions and provides functionality
 * to retrive this information for the active user.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers 
 */
class MenuHelper {
	
	// momo's top-level menu keys
	const KEY_TOPLEVEL_MENU_TIMETRACKER	 		= "KEY_TOPLEVEL_MENU_TIMETRACKER";
	const KEY_TOPLEVEL_MENU_REQUESTS 			= "KEY_TOPLEVEL_MENU_REQUESTS";
	const KEY_TOPLEVEL_MENU_REPORTS 			= "KEY_TOPLEVEL_MENU_REPORTS";
	const KEY_TOPLEVEL_MENU_TEAMS 				= "KEY_TOPLEVEL_MENU_TEAMS";
	const KEY_TOPLEVEL_MENU_APPLICATION 		= "KEY_TOPLEVEL_MENU_APPLICATION";
	
	// momo's action keys
	const KEY_ACTION_MANAGE_ADJUSTMENTS 		= "KEY_ACTION_MANAGE_ADJUSTMENTS";
	const KEY_ACTION_MANAGE_BOOKINGTYPES 		= "KEY_ACTION_MANAGE_BOOKINGTYPES";
	const KEY_ACTION_MANAGE_ENTRYTYPES 			= "KEY_ACTION_MANAGE_ENTRYTYPES";
	const KEY_ACTION_MANAGE_PROJECTS 			= "KEY_ACTION_MANAGE_PROJECTS";
	const KEY_ACTION_MANAGE_TEAMS 				= "KEY_ACTION_MANAGE_TEAMS";
	const KEY_ACTION_MANAGE_USERS 				= "KEY_ACTION_MANAGE_USERS";
	const KEY_ACTION_MANAGE_WORKPLANS 			= "KEY_ACTION_MANAGE_WORKPLANS";
	const KEY_ACTION_MANAGE_AUDITTRAIL 			= "KEY_ACTION_MANAGE_AUDITTRAIL";
	const KEY_ACTION_CHANGE_SETTINGS 			= "KEY_ACTION_CHANGE_SETTINGS";
	
	const KEY_ACTION_MANAGE_BOOKINGS 			= "KEY_ACTION_MANAGE_BOOKINGS";
	const KEY_ACTION_DISPLAY_TEAM_SUMMARY 		= "KEY_ACTION_DISPLAY_TEAM_SUMMARY";
	
	const KEY_ACTION_MANAGE_REQUESTS 			= "KEY_ACTION_MANAGE_REQUESTS";
	
	const KEY_ACTION_DISPLAY_OOSUMMARY 			= "KEY_ACTION_DISPLAY_OOSUMMARY";
	const KEY_ACTION_DISPLAY_PROJECTTIMEREPORT 	= "KEY_ACTION_DISPLAY_PROJECTTIMEREPORT";
	const KEY_ACTION_DISPLAY_TIMETRACKERREPORT 	= "KEY_ACTION_DISPLAY_TIMETRACKERREPORT";
	
	const KEY_ACTION_TIMETRACKER 				= "KEY_ACTION_TIMETRACKER";
	
	
	// map top-level menu keys to contained actions / required permissions
	public static $TOPLEVEL_MENU_TO_ACTION_MAP = array(
	
					MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION	=> array(
					
														"actions"	=> array (
	
																						MenuHelper::KEY_ACTION_MANAGE_ADJUSTMENTS => array( 
																														Permissions::ADJUSTMENTS_LIST_ALL_ADJUSTMENTS
																													),
																						MenuHelper::KEY_ACTION_MANAGE_BOOKINGTYPES => array( 
																														Permissions::BOOKINGTYPES_LIST_ALL_TYPES
																													),
																						MenuHelper::KEY_ACTION_MANAGE_ENTRYTYPES => array( 
																														Permissions::ENTRYTYPES_LIST_ALL_TYPES
																													),
																						MenuHelper::KEY_ACTION_MANAGE_PROJECTS => array( 
																														Permissions::PROJECTS_LIST_ALL_PROJECTS
																													),
																						MenuHelper::KEY_ACTION_MANAGE_TEAMS => array( 
																														Permissions::TEAMS_LIST_ALL_TEAMS
																													),
																						MenuHelper::KEY_ACTION_MANAGE_USERS => array( 
																														Permissions::USERS_LIST_ALL_USERS
																													),
																						MenuHelper::KEY_ACTION_MANAGE_WORKPLANS => array( 
																														Permissions::WORKPLANS_LIST_ALL_PLANS
																													),
																						MenuHelper::KEY_ACTION_MANAGE_AUDITTRAIL => array( 
																														Permissions::AUDIT_TRAIL_LIST_EVENTS
																													),
																						MenuHelper::KEY_ACTION_CHANGE_SETTINGS => array( 
																														Permissions::SETTINGS_UPDATE_SETTINGS
																													)				
																				)							
																	),
																	
					MenuHelper::KEY_TOPLEVEL_MENU_TEAMS	=> array(
					
														"actions"	=> array (
	
																						MenuHelper::KEY_ACTION_MANAGE_BOOKINGS => array( 
																														Permissions::OO_BOOKINGS_LIST_ALL_BOOKINGS,
																														Permissions::OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS
																													),
																						MenuHelper::KEY_ACTION_DISPLAY_TEAM_SUMMARY => array( 
																														Permissions::TEAMS_LIST_ALL_TEAMS,
																														Permissions::TEAMS_LIST_ASSIGNED_TEAMS
																													)
																					)							
																	),
																	
																	
					MenuHelper::KEY_TOPLEVEL_MENU_REQUESTS	=> array(
					
														"actions"	=> array (
																						MenuHelper::KEY_ACTION_MANAGE_REQUESTS => array( 
																														Permissions::OO_REQUESTS_LIST_REQUESTS
																													)
																					)							
																	),
																																	
					MenuHelper::KEY_TOPLEVEL_MENU_REPORTS	=> array(
					
														"actions"	=> array (
	
																						MenuHelper::KEY_ACTION_DISPLAY_OOSUMMARY => array( 
																														Permissions::REPORTS_VIEW_ALL_OO_SUMMARIES
																													),
																						MenuHelper::KEY_ACTION_DISPLAY_PROJECTTIMEREPORT => array( 
																														Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS,
																														Permissions::REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT,
																														Permissions::REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT,
																														Permissions::REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
																														Permissions::REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
																														Permissions::REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT,
																														Permissions::REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT
																													),
																						MenuHelper::KEY_ACTION_DISPLAY_TIMETRACKERREPORT => array( 
																														Permissions::REPORTS_VIEW_ALL_TIMETRACKER_REPORTS,
																														Permissions::REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT,
																														Permissions::REPORTS_VIEW_OWN_TIMETRACKER_REPORT
																													)
																					)							
																	),
																	
					MenuHelper::KEY_TOPLEVEL_MENU_TIMETRACKER	=> array(
					
														"actions"	=> array (
	
																						MenuHelper::KEY_ACTION_TIMETRACKER => array( 
																														Permissions::TIMETRACKER_ACCESS_ALL_TIMETRACKERS,
																														Permissions::TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER,
																														Permissions::TIMETRACKER_ACCESS_OWN_TIMETRACKER
																													)
																					)							
																	)

		);
		
		
	/**
	 * Indicates, whether the active user has access to a given menu group
	 * 
	 * Note: for performance reasons, the method will cache the result to session
	 *   
	 * @param string $topLevelMenuKey		- the menu key of the menu group to test access for
	 * 
	 * @return boolean
	 */											
	public static function activeUserHasAccessToMenuGroup($topLevelMenuKey) {
		
		$authorized = false;
		
		// get a reference to ci
		$ci = & get_instance();
		
		if ( $ci->session->userdata('menuhelper.accesstomenugroup.' . $topLevelMenuKey) !== false ) {
			$authorized = $ci->session->userdata('menuhelper.accesstomenugroup.' . $topLevelMenuKey);
		}
		else {
			
			// get ref to application context
			$ctx = \momo\core\application\ApplicationContext::getInstance();
			
			// get managers needed
			$securityService = $ctx->getSecurityService();
			
			// process list in terms of logical OR
			foreach ( MenuHelper::$TOPLEVEL_MENU_TO_ACTION_MAP[$topLevelMenuKey]["actions"] as $curAction ) {
				
				foreach ( $curAction as $curPermission ) {
			
					if ( $authorized = $securityService->authorize($curPermission) ) {
						// break out of both foreachs
						break 2;
					}
				}
				
			}
			
			// persist result to session
			$ci->session->set_userdata('menuhelper.accesstomenugroup.' . $topLevelMenuKey, $authorized);			
		
		}
		
		return $authorized;	
	}
	
	
	/**
	 * Indicates whether the active user has access to the indicated action contained in a given top level menu
	 * 
	 * Note: for performance reasons, the method will cache the result of this operation to session
	 *
	 * @param string $topLevelMenuKey		- the key of the top level menu that contains the action
	 * @param string $actionKey				- the key of the action to test access for
	 * 
	 * @return boolean
	 */											
	public static function activeUserHasAccessToAction($topLevelMenuKey, $actionKey) {
		
		$authorized = false;
		
		// get a reference to ci
		$ci = & get_instance();
		
		if ( $ci->session->userdata('menuhelper.accesstoaction.' . $actionKey) !== false ) {
			$authorized = $ci->session->userdata('menuhelper.accesstoaction.' . $actionKey);
		}
		else {
			
			// get ref to application context
			$ctx = \momo\core\application\ApplicationContext::getInstance();
			
			// get managers needed
			$securityService = $ctx->getSecurityService();
		
			// test whether user matches one of the listed permission
			foreach ( MenuHelper::$TOPLEVEL_MENU_TO_ACTION_MAP[$topLevelMenuKey]["actions"][$actionKey] as $curPermission ) {
				if ( $authorized = $securityService->authorize($curPermission) ) {
					break;
				}
			}
			
			// persist result to session
			$ci->session->set_userdata('menuhelper.accesstoaction.' . $actionKey, $authorized);			
		
		}
		
		return $authorized;
		
	}
	
}
