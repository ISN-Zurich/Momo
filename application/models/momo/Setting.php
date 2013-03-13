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
 * The Momo extension to the Propel generated Setting class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */

class Setting extends BaseSetting {

	//
	// setting keys
	const KEY_TIMETRACKER_LOCKOUT_DAYS 			= "KEY_TIMETRACKER_LOCKOUT_DAYS";			// the number of days that elapse before days are locked against user edits
	const KEY_TIMETRACKER_RELOCK_DAYS 			= "KEY_TIMETRACKER_RELOCK_DAYS";			// the number of days that elapse before explcitly unlocked days are relocked
	
	const KEY_VACATION_LAPSE_DAY 				= "KEY_VACATION_LAPSE_DAY";					// the day and month at which carried over vacation credit lapses	
	const KEY_VACATION_LAPSE_MONTH 				= "KEY_VACATION_LAPSE_MONTH";
	
	const KEY_OVERTIME_LAPSE_DAY	 			= "KEY_OVERTIME_LAPSE_DAY";					// the day and month at which carried over overtime credit lapses	
	const KEY_OVERTIME_LAPSE_MONTH 				= "KEY_OVERTIME_LAPSE_MONTH";
	
	const KEY_OVERTIME_EXCESSIVE_IN_SEC 		= "KEY_OVERTIME_EXCESSIVE_IN_SEC";			// overtime beyond this value is considered excessive
	
	const KEY_APPLICATION_USE_START_DATE 		= "KEY_APPLICATION_USE_START_DATE";			// the date at which application use starts

	const KEY_OO_BOOKING_DEFAULT_COLOR 			= "KEY_OO_BOOKING_DEFAULT_COLOR";			// the default color used to display oo bookings in the oo summary
	
	const KEY_SITE_ADMIN_EMAIL_ADDRESS 			= "KEY_SITE_ADMIN_EMAIL_ADDRESS";			// the site administrators email address

	
	//
	// settings unit constants 
	const UNIT_SECONDS  	= "UNIT_SECONDS";				
	const UNIT_DAYS 		= "UNIT_DAYS";
	
	// data type constants
	const DATATYPE_DATE  			= "DATATYPE_DATE";
	const DATATYPE_INTEGER  		= "DATATYPE_INTEGER";
	const DATATYPE_FLOAT  			= "DATATYPE_FLOAT";
	const DATATYPE_STRING 	 		= "DATATYPE_STRING";
	const DATATYPE_EMAIL_ADDRESS 	= "DATATYPE_EMAIL_ADDRESS";
	
	//
	// map internal keys to various meta data values
	//
	public static $KEY_MAP = array(
	
		Setting::KEY_OVERTIME_EXCESSIVE_IN_SEC 	=> array(
																"prettyKeyName" 				=> "Maximum Allowed Overtime",
																"prettyDisplayUnitNameSingular"	=> "hour",
																"units"							=> Setting::UNIT_SECONDS,
																"dataType"						=> Setting::DATATYPE_FLOAT
															),
	
		Setting::KEY_TIMETRACKER_LOCKOUT_DAYS 	=> array(
																"prettyKeyName" 				=> "Timetracker Lockout after",
																"prettyDisplayUnitNameSingular"	=> "day",
																"units"							=> Setting::UNIT_DAYS,
																"dataType"						=> Setting::DATATYPE_INTEGER
															),
															
		Setting::KEY_TIMETRACKER_RELOCK_DAYS 	=> array(
																"prettyKeyName" 				=> "Timetracker Relock after",
																"prettyDisplayUnitNameSingular"	=> "day",
																"units"							=> Setting::UNIT_DAYS,
																"dataType"						=> Setting::DATATYPE_INTEGER
															),

		//Setting::KEY_VACATION_LAPSE_DAY 		=> array(
		//														"prettyKeyName" 				=> "Vacation Lapses On Day",
		//														"prettyDisplayUnitNameSingular"	=> null,
		//														"units"							=> null,
		//														"dataType"						=> Setting::DATATYPE_INTEGER
		//													),

		//Setting::KEY_VACATION_LAPSE_MONTH 		=> array(
		//														"prettyKeyName" 				=> "Vacation Lapses On Month",
		//														"prettyDisplayUnitNameSingular"	=> null,
		//														"units"							=> null,
		//														"dataType"						=> Setting::DATATYPE_INTEGER
		//													),
																											
		//Setting::KEY_OVERTIME_LAPSE_DAY 		=> array(
		//														"prettyKeyName" 				=> "Overtime Lapses On Day",
		//														"prettyDisplayUnitNameSingular"	=> null,
		//														"units"							=> null,
		//														"dataType"						=> Setting::DATATYPE_INTEGER
		//													),

		//Setting::KEY_OVERTIME_LAPSE_MONTH 		=> array(
		//														"prettyKeyName" 				=> "Overtime Lapses On Month",
		//														"prettyDisplayUnitNameSingular"	=> null,
		//														"units"							=> null,
		//														"dataType"						=> Setting::DATATYPE_INTEGER
		//													),


		// "application use start date" does not really need to be exposed on frontend
		// -- it's a one-off setting that does not ever need to be changed
		//													
		//Setting::KEY_APPLICATION_USE_START_DATE => array(
		//														"prettyKeyName" 				=> "Application Use Starts On",
		//														"prettyDisplayUnitNameSingular"	=> null,
		//														"units"							=> null,
		//														"dataType"						=> Setting::DATATYPE_DATE
		//													),

		Setting::KEY_OO_BOOKING_DEFAULT_COLOR => array(
																"prettyKeyName" 				=> "OO Booking Default Color",
																"prettyDisplayUnitNameSingular"	=> null,
																"units"							=> null,
																"dataType"						=> Setting::DATATYPE_STRING
															),

		Setting::KEY_SITE_ADMIN_EMAIL_ADDRESS => array(
																"prettyKeyName" 				=> "Site Admin Email",
																"prettyDisplayUnitNameSingular"	=> null,
																"units"							=> null,
																"dataType"						=> Setting::DATATYPE_EMAIL_ADDRESS
															)											

	);
	
	
	
	
} // Setting
