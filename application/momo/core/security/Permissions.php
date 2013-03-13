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
 * Permissions
 * 
 * Enumerates the various application permissions
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.security
 */
class Permissions {
	
	//
	// TIMETRACKER RELATED PERMISSIONS
	const TIMETRACKER_ACCESS_OWN_TIMETRACKER 							= "TIMETRACKER_ACCESS_OWN_TIMETRACKER";
	const TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER 	= "TIMETRACKER_ACCESS_ASSIGNED_USER_OF_LOWER_ROLE_TIMETRACKER";
	const TIMETRACKER_ACCESS_ALL_TIMETRACKERS 							= "TIMETRACKER_ACCESS_ALL_TIMETRACKERS";
	
	const TIMETRACKER_CREATE_ENTRY 				= "TIMETRACKER_CREATE_ENTRY";
	const TIMETRACKER_UPDATE_ENTRY 				= "TIMETRACKER_UPDATE_ENTRY";
	const TIMETRACKER_DELETE_ENTRY 				= "TIMETRACKER_DELETE_ENTRY";
	
	//
	// TEAM RELATED PERMISSIONS
	const TEAMS_LIST_ALL_TEAMS 					= "TEAMS_LIST_ALL_TEAMS";
	const TEAMS_LIST_ASSIGNED_TEAMS 			= "TEAMS_LIST_ASSIGNED_TEAMS";				// allows the listing of teams for which the active user is designated as team leader
	const TEAMS_ARCHIVE_TEAM 					= "TEAMS_ARCHIVE_TEAM";
	const TEAMS_CREATE_TEAM 					= "TEAMS_CREATE_TEAM";
	const TEAMS_EDIT_TEAM 						= "TEAMS_EDIT_TEAM";
	
	//
	// PROJECT RELATED PERMISSIONS
	const PROJECTS_LIST_ALL_PROJECTS 			= "PROJECTS_LIST_ALL_PROJECTS";
	const PROJECTS_CREATE_PROJECT 				= "PROJECTS_CREATE_PROJECT";
	const PROJECTS_EDIT_PROJECT 				= "PROJECTS_EDIT_PROJECT";
	const PROJECTS_DELETE_PROJECT 				= "PROJECTS_DELETE_PROJECT";
	
	//
	// ADJUSTMENTS RELATED PERMISSIONS
	const ADJUSTMENTS_LIST_ALL_ADJUSTMENTS		= "ADJUSTMENTS_LIST_ALL_ADJUSTMENTS";
	const ADJUSTMENTS_CREATE_ADJUSTMENT			= "ADJUSTMENTS_CREATE_ADJUSTMENT";
	const ADJUSTMENTS_EDIT_ADJUSTMENT			= "ADJUSTMENTS_EDIT_ADJUSTMENT";
	const ADJUSTMENTS_DELETE_ADJUSTMENT			= "ADJUSTMENTS_DELETE_ADJUSTMENT";			
	
	//
	// ENTRY TYPE RELATED PERMISSIONS
	const ENTRYTYPES_LIST_ALL_TYPES 			= "ENTRYTYPES_LIST_ALL_TYPES";
	const ENTRYTYPES_CREATE_TYPE 				= "ENTRYTYPES_CREATE_TYPE";
	const ENTRYTYPES_EDIT_TYPE					= "ENTRYTYPES_EDIT_TYPE";
	const ENTRYTYPES_DELETE_TYPE 				= "ENTRYTYPES_DELETE_TYPE";
	
	//
	// BOOKING TYPE RELATED PERMISSIONS
	const BOOKINGTYPES_LIST_ALL_TYPES 			= "BOOKINGTYPES_LIST_ALL_TYPES";
	const BOOKINGTYPES_CREATE_TYPE 				= "BOOKINGTYPES_CREATE_TYPE";
	const BOOKINGTYPES_EDIT_TYPE				= "BOOKINGTYPES_EDIT_TYPE";
	const BOOKINGTYPES_DELETE_TYPE 				= "BOOKINGTYPES_DELETE_TYPE";
	
	//
	// WORKPLAN RELATED PERMISSIONS
	const WORKPLANS_LIST_ALL_PLANS 				= "WORKPLANS_LIST_ALL_PLANS";
	const WORKPLANS_CREATE_PLAN 				= "WORKPLANS_CREATE_PLAN";
	const WORKPLANS_EDIT_PLAN					= "WORKPLANS_EDIT_PLAN";
	const WORKPLANS_DELETE_PLAN 				= "WORKPLANS_DELETE_PLAN";
	
	//
	// SETTINGS RELATED PERMISSIONS
	const SETTINGS_UPDATE_SETTINGS 				= "SETTINGS_UPDATE_SETTINGS";
	
	//
	// ROLE RELATED PERMISSIONS
	const ROLES_LIST_ALL_ROLES 					= "ROLES_LIST_ALL_ROLES";
	const ROLES_LIST_LOWER_ROLES 				= "ROLES_LIST_LOWER_ROLES";					// allows the listing of roles lower than that held by the active user
	
	//
	// USER RELATED PERMISSIONS
	const USERS_LIST_ALL_USERS 								= "USERS_LIST_ALL_USERS";
	const USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE 			= "USERS_LIST_ASSIGNED_USERS_OF_LOWER_ROLE";	// allows the listing of users assigned to a team for which the active user is team leader, user must be of lower role
	
	const USERS_CREATE_GENERAL_USER	 						= "USERS_CREATE_GENERAL_USER";
	const USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE	 		= "USERS_CREATE_ASSIGNED_USER_OF_LOWER_ROLE";	// allows the creation of users assigned to a team for which the active user is team leader, user must be of lower role
	
	const USERS_EDIT_ALL_USERS	 							= "USERS_EDIT_ALL_USERS";
	const USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE	 		= "USERS_EDIT_ASSIGNED_USERS_OF_LOWER_ROLE";	// allows the editing of users assigned to a team for which the active user is team leader, user must be of lower role
	
	const USERS_DELETE_ALL_USERS	 						= "USERS_DELETE_ALL_USERS";
	const USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE			= "USERS_DELETE_ASSIGNED_USERS_OF_LOWER_ROLE";	// allows the deleting of users assigned to a team for which the active user is team leader,, user must be of lower role
	
	const USERS_SET_OWN_PASSWORD							= "USERS_SET_OWN_PASSWORD";
	const USERS_SEND_EMAIL_TO_USER							= "USERS_SEND_EMAIL_TO_USER";
	const USERS_UNLOCK_ALL_USERS_WEEKS						= "USERS_UNLOCK_ALL_USERS_WEEKS";
	const USERS_UNLOCK_ASSIGNED_USERS_WEEKS					= "USERS_UNLOCK_ASSIGNED_USERS_WEEKS";
	
	//
	// AUDIT TRAIL RELATED PERMISSIONS
	const AUDIT_TRAIL_LIST_EVENTS							= "AUDIT_TRAIL_LIST_EVENTS";
	
	//
	// REQUEST RELATED PERMISSIONS
	const OO_REQUESTS_LIST_REQUESTS							= "OO_REQUESTS_LIST_REQUESTS";
	const OO_REQUESTS_CREATE_REQUEST						= "OO_REQUESTS_CREATE_REQUEST";
	const OO_REQUESTS_EDIT_REQUEST							= "OO_REQUESTS_EDIT_REQUEST";
	const OO_REQUESTS_DELETE_REQUEST						= "OO_REQUESTS_DELETE_REQUEST";

	//
	// BOOKING RELATED PERMISSIONS
	const OO_BOOKINGS_LIST_ALL_BOOKINGS						= "OO_BOOKINGS_LIST_ALL_BOOKINGS";
	const OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS			= "OO_BOOKINGS_LIST_ASSIGNED_USER_BOOKINGS";
	
	const OO_BOOKINGS_DELETE_ALL_BOOKINGS					= "OO_BOOKINGS_DELETE_ALL_BOOKINGS";
	const OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS			= "OO_BOOKINGS_DELETE_ASSIGNED_USER_BOOKINGS";
	
	const OO_BOOKINGS_CREATE_BOOKING						= "OO_BOOKINGS_CREATE_BOOKING";
	const OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING			= "OO_BOOKINGS_CREATE_ASSIGNED_USER_BOOKING";
	const OO_BOOKINGS_EDIT_ALL_BOOKINGS						= "OO_BOOKINGS_EDIT_ALL_BOOKINGS";
	const OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING			= "OO_BOOKINGS_EDIT_ASSIGNED_USER_BOOKING";

	//
	// REPORTS RELATED PERMISSIONS
	const REPORTS_VIEW_ALL_OO_SUMMARIES						= "REPORTS_VIEW_ALL_OO_SUMMARIES";								// allows all oo summaries to be viewed
	
	const REPORTS_VIEW_ALL_TIMETRACKER_REPORTS				= "REPORTS_VIEW_ALL_TIMETRACKER_REPORTS";						// allows all timetracker reports to be viewed
	const REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT		= "REPORTS_VIEW_ASSIGNED_USER_TIMETRACKER_REPORT";				// allows viewing of timetracker reports for users assigned to teams for which active user is team leader
	const REPORTS_VIEW_OWN_TIMETRACKER_REPORT				= "REPORTS_VIEW_OWN_TIMETRACKER_REPORT";						// allows the active user to view their own timetracker report
	
	
	const REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS							= "REPORTS_VIEW_ALL_PROJECT_TIME_REPORTS";							// allows all project time reports to be viewed
	
	const REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT		= "REPORTS_VIEW_ASSIGNED_USER_PROJECT_TIME_BY_USER_REPORT";			// allows viewing of "project time, by user" reports for users assigned to teams for which active user is team leader
	const REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT					= "REPORTS_VIEW_OWN_PROJECT_TIME_BY_USER_REPORT";					// allows the active user to view their own "project time, by user" report
	
	const REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT		= "REPORTS_VIEW_ASSIGNED_TEAM_PROJECT_TIME_BY_TEAM_REPORT";			// allows viewing of "project time, by team" reports for teams for which active user is team leader
	const REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT				= "REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT";				// allows the active user to view the "project time, by team" report for their own primary team
	
	const REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT	= "REPORTS_VIEW_ASSIGNED_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT";	// allows viewing of "project time, by project" reports for projects assigned to teams for which active user is team leader
	const REPORTS_VIEW_OWN_PROJECT_PROJECT_TIME_BY_PROJECT_REPORT		= "REPORTS_VIEW_OWN_TEAM_PROJECT_TIME_BY_TEAM_REPORT";				// allows the active user to view the the "project time, by project" report for projects assigned to them (via primary team membership, or directly)
	
	
	
	
	
}