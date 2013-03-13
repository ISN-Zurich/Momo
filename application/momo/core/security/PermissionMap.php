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

namespace momo\core\security;

/**
 * PermissionMap
 * 
 * Maps permissions to roles and allows for retrieval of
 * the permissions applicable to a given role
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.security
 */
class PermissionMap {
	
	/**
	 * map the roles to permissions.
	 * 
	 * --> to grant a permission to a role, list it in the applicable role's permissionmap array.
	 */
	private static $permissionMap = array(
	
		Roles::ROLE_USER => array(
		
			//
			// TIMETRACKER RELATED PERMISSIONS
			Permissions::TIMETRACKER_ACCESS_OWN_TIMETRACKER 	=> Permissions::TIMETRACKER_ACCESS_OWN_TIMETRACKER,
			Permissions::TIMETRACKER_CREATE_ENTRY 				=> Permissions::TIMETRACKER_CREATE_ENTRY,
			Permissions::TIMETRACKER_UPDATE_ENTRY 				=> Permissions::TIMETRACKER_UPDATE_ENTRY,
			Permissions::TIMETRACKER_DELETE_ENTRY 				=> Permissions::TIMETRACKER_DELETE_ENTRY,
			
			//
			// USER RELATED PERMISSIONS
			Permissions::USERS_SET_OWN_PASSWORD					=> Permissions::USERS_SET_OWN_PASSWORD,
			
			//
			// REQUESTS RELATED PERMISSIONS
			Permissions::OO_REQUESTS_LIST_REQUESTS				=> Permissions::OO_REQUESTS_LIST_REQUESTS,
			Permissions::OO_REQUESTS_CREATE_REQUEST				=> Permissions::OO_REQUESTS_CREATE_REQUEST,
			Permissions::OO_REQUESTS_EDIT_REQUEST				=> Permissions::OO_REQUESTS_EDIT_REQUEST,
			Permissions::OO_REQUESTS_DELETE_REQUEST				=> Permissions::OO_REQUESTS_DELETE_REQUEST,
			
			//
			// REPORTS RELATED PERMISSIONS
			Permissions::REPORTS_VIEW_ALL_OO_SUMMARIES								=> Permissions::REPORTS_VIEW_ALL_OO_SUMMARIES,
			Permissions::REPORTS_VIEW_OWN_TIMETRACKER_REPORT						=> Permissions::REPORTS_VIEW_OWN_TIMETRACKER_REPORT,
			Permissions::REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT				=> Permissions::REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT,
			Permissions::REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT			=> Permissions::REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
			Permissions::REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT	=> Permissions::REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT,
			
		),
		
		Roles::ROLE_TEAMLEADER 	=> array(
		
			//
			// TIMETRACKER RELATED PERMISSIONS
			Permissions::TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER 	=> Permissions::TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER,
		
			//
			// USER RELATED PERMISSIONS
			Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE						=> Permissions::USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE,
			//Permissions::USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE						=> Permissions::USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE,
			//Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE						=> Permissions::USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE,
			//Permissions::USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE						=> Permissions::USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE,
			Permissions::USERS_SEND_EMAIL_TO_USER										=> Permissions::USERS_SEND_EMAIL_TO_USER,
			Permissions::USERS_UNLOCK_ASSIGNED_USERS_WEEKS								=> Permissions::USERS_UNLOCK_ASSIGNED_USERS_WEEKS,
			
			//
			// TEAM RELATED PERMISSIONS
			Permissions::TEAMS_LIST_ASSIGNED_TEAMS										=> Permissions::TEAMS_LIST_ASSIGNED_TEAMS,
			
			//
			// ROLE RELATED PERMISSIONS
			Permissions::ROLES_LIST_LOWER_ROLES											=> Permissions::ROLES_LIST_LOWER_ROLES,
			
			//
			// BOOKING RELATED PERMISSIONS
			Permissions::OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING						=> Permissions::OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING,
			Permissions::OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING							=> Permissions::OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING,
			Permissions::OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS						=> Permissions::OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS,
			Permissions::OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS						=> Permissions::OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS,

			//
			// REPORTS RELATED PERMISSIONS
			Permissions::REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT						=> Permissions::REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT,
			Permissions::REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT				=> Permissions::REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT,
			Permissions::REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT				=> Permissions::REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT,
			Permissions::REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT		=> Permissions::REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT
			
		),
				
		Roles::ROLE_MANAGER => array(
		
			//
			// TIMETRACKER RELATED PERMISSIONS
			Permissions::TIMETRACKER_ACCESS_ALL_TIMETRACKERS 		=> Permissions::TIMETRACKER_ACCESS_ALL_TIMETRACKERS,
		
			//
			// TEAM RELATED PERMISSIONS
			Permissions::TEAMS_LIST_ALL_TEAMS 						=> Permissions::TEAMS_LIST_ALL_TEAMS,
			Permissions::TEAMS_ARCHIVE_TEAM 						=> Permissions::TEAMS_ARCHIVE_TEAM,
			Permissions::TEAMS_CREATE_TEAM 							=> Permissions::TEAMS_CREATE_TEAM,
			Permissions::TEAMS_EDIT_TEAM 							=> Permissions::TEAMS_EDIT_TEAM,
			
			//
			// PROJECT RELATED PERMISSIONS
			Permissions::PROJECTS_LIST_ALL_PROJECTS 				=> Permissions::PROJECTS_LIST_ALL_PROJECTS,
			Permissions::PROJECTS_CREATE_PROJECT 					=> Permissions::PROJECTS_CREATE_PROJECT,
			Permissions::PROJECTS_EDIT_PROJECT 						=> Permissions::PROJECTS_EDIT_PROJECT,
			Permissions::PROJECTS_DELETE_PROJECT 					=> Permissions::PROJECTS_DELETE_PROJECT,
			
			//
			// ENTRY TYPE RELATED PERMISSIONS
			Permissions::ENTRYTYPES_LIST_ALL_TYPES 					=> Permissions::ENTRYTYPES_LIST_ALL_TYPES,
			Permissions::ENTRYTYPES_CREATE_TYPE 					=> Permissions::ENTRYTYPES_CREATE_TYPE,
			Permissions::ENTRYTYPES_EDIT_TYPE 						=> Permissions::ENTRYTYPES_EDIT_TYPE,
			Permissions::ENTRYTYPES_DELETE_TYPE 					=> Permissions::ENTRYTYPES_DELETE_TYPE,
			
			//
			// BOOKING TYPE RELATED PERMISSIONS
			Permissions::BOOKINGTYPES_LIST_ALL_TYPES 				=> Permissions::BOOKINGTYPES_LIST_ALL_TYPES,
			Permissions::BOOKINGTYPES_CREATE_TYPE 					=> Permissions::BOOKINGTYPES_CREATE_TYPE,
			Permissions::BOOKINGTYPES_EDIT_TYPE 					=> Permissions::BOOKINGTYPES_EDIT_TYPE,
			Permissions::BOOKINGTYPES_DELETE_TYPE 					=> Permissions::BOOKINGTYPES_DELETE_TYPE,
			
			//
			// ADJUSTMENTS RELATED PERMISSIONS
			Permissions::ADJUSTMENTS_LIST_ALL_ADJUSTMENTS 			=> Permissions::ADJUSTMENTS_LIST_ALL_ADJUSTMENTS,
			Permissions::ADJUSTMENTS_CREATE_ADJUSTMENT 				=> Permissions::ADJUSTMENTS_CREATE_ADJUSTMENT,
			Permissions::ADJUSTMENTS_EDIT_ADJUSTMENT 				=> Permissions::ADJUSTMENTS_EDIT_ADJUSTMENT,
			Permissions::ADJUSTMENTS_DELETE_ADJUSTMENT 				=> Permissions::ADJUSTMENTS_DELETE_ADJUSTMENT,
			
			//
			// WORKPLAN RELATED PERMISSIONS
			Permissions::WORKPLANS_LIST_ALL_PLANS 					=> Permissions::WORKPLANS_LIST_ALL_PLANS,
			Permissions::WORKPLANS_CREATE_PLAN 						=> Permissions::WORKPLANS_CREATE_PLAN,
			Permissions::WORKPLANS_EDIT_PLAN 						=> Permissions::WORKPLANS_EDIT_PLAN,
			Permissions::WORKPLANS_DELETE_PLAN 						=> Permissions::WORKPLANS_DELETE_PLAN,
			
			//
			// SETTINGS RELATED PERMISSIONS
			Permissions::SETTINGS_UPDATE_SETTINGS 					=> Permissions::SETTINGS_UPDATE_SETTINGS,
			
			//
			// USER RELATED PERMISSIONS
			Permissions::USERS_LIST_ALL_USERS						=> Permissions::USERS_LIST_ALL_USERS,
			Permissions::USERS_CREATE_GENERAL_USER					=> Permissions::USERS_CREATE_GENERAL_USER,
			Permissions::USERS_EDIT_ALL_USERS						=> Permissions::USERS_EDIT_ALL_USERS,
			Permissions::USERS_DELETE_ALL_USERS						=> Permissions::USERS_DELETE_ALL_USERS,
			Permissions::USERS_UNLOCK_ALL_USERS_WEEKS				=> Permissions::USERS_UNLOCK_ALL_USERS_WEEKS,
			
			//
			// ROLE RELATED PERMISSIONS
			Permissions::ROLES_LIST_ALL_ROLES						=> Permissions::ROLES_LIST_ALL_ROLES,
			
			//
			// AUDIT TRAIL RELATED PERMISSIONS
			Permissions::AUDIT_TRAIL_LIST_EVENTS					=> Permissions::AUDIT_TRAIL_LIST_EVENTS,
			
			//
			// BOOKING RELATED PERMISSIONS
			Permissions::OO_BOOKINGS_CREATE_BOOKING					=> Permissions::OO_BOOKINGS_CREATE_BOOKING,
			Permissions::OO_BOOKINGS_LIST_ALL_BOOKINGS				=> Permissions::OO_BOOKINGS_LIST_ALL_BOOKINGS,
			Permissions::OO_BOOKINGS_DELETE_ALL_BOOKINGS			=> Permissions::OO_BOOKINGS_DELETE_ALL_BOOKINGS,
			Permissions::OO_BOOKINGS_EDIT_ALL_BOOKINGS				=> Permissions::OO_BOOKINGS_EDIT_ALL_BOOKINGS,

			//
			// REPORTS RELATED PERMISSIONS
			Permissions::REPORTS_VIEW_ALL_TIMETRACKER_REPORTS		=> Permissions::REPORTS_VIEW_ALL_TIMETRACKER_REPORTS,
			Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS		=> Permissions::REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS
			
		),
		
		Roles::ROLE_ADMINISTRATOR => array(
			//
			// at present the role has no specifically assigned permissions
		)	
	);
	
	
	
	
	
	/**
	 * Returns an array containing the permissions for the indicated role.
	 * 
	 * Note: 	The various roles' permissions are hierarchized as follows:
	 * 
	 * 					ROLE_ADMINISTRATOR > ROLE_MANAGER > ROLE_TEAMLEADER > ROLE _USER
	 * 
	 * 					--> with higher order roles inheriting the permissions of lower order roles
	 * 
	 * @param 	string 	$role
	 * 
	 * @return  array	an array containing the permissions for the indicated role, empty if role not found
	 */
	public static function getPermissionsForRole($role) {
		
		$permissions = array();
		
		if ( array_key_exists($role, PermissionMap::$permissionMap) ) {
			//
			// retrieve permissions native to indicated role
			$permissions = PermissionMap::$permissionMap[$role];
			
			//
			// if applicable, add permissions from lower order roles
			switch ($role) {
				
				case Roles::ROLE_ADMINISTRATOR:
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_MANAGER]);
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_TEAMLEADER]);
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_USER]);
					break;
					
				case Roles::ROLE_MANAGER:
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_TEAMLEADER]);
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_USER]);
					break;
					
				case Roles::ROLE_TEAMLEADER:
					$permissions = array_merge($permissions, PermissionMap::$permissionMap[Roles::ROLE_USER]);
					break;	
			}			
		}
		
		return $permissions;
	}
	
}