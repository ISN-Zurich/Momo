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

namespace momo\_meta;

use momo\core\helpers\DateTimeHelper;
use momo\workplanmanager\WorkplanManager;
use momo\core\security\Roles;

/**
 * DatabaseInitializer
 * 
 * @author  Francesco Krattiger
 * @package momo.application.meta
 */
class DatabaseInitializer {
	
	// holds red to the application context
	private $ctx = null;
	
	// these hold the data parsed from the xml init data
	private $settings = array();
	private $holidays = array();
	private $workplanProps = array();
	private $userProps = array();
	
	// keeps track of the tags we process
	private $tagStack = array();
		
	
	/**
	 * constructor
	 */
	public function __construct() {
		$this->ctx = \momo\core\application\ApplicationContext::getInstance();
	}
	
	
	/**
	 * Initializes the database based on the information configured in "_shellscripts/setup/initdata.xml"
	 * 
	 * Additionally, the method will create needed system data points.
	 */
	public function initDatabase() {
		
	 	$userManager = $this->ctx->getUserManager();
        $workplanManager = $this->ctx->getWorkplanManager();
        $settingsManager = $this->ctx->getSettingsManager();
        $entryTypeManager = $this->ctx->getEntryTypeManager();
        $bookingTypeManager = $this->ctx->getBookingTypeManager();
        
        // before proceeding, make sure the database is in pristine state.
        // we determine this, by querying for workplans and users - both operations need to yield zero records
        $allUsers = $userManager->getAllUsers();
        $allWorkplans = $workplanManager->getAllPlans();
		if ( ($allUsers->count() != 0) || ($allWorkplans->count() != 0) ) {
			echo "\n\nUtilityController:initDatabase() - method may not be called on an active system.\n\n";
			die;
        }
		
		// get db connection
		$con = \Propel::getConnection(\SettingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// parse xml init data
			$this->parseInitData();		
			
			// generate settings based on init data
			foreach ( $this->settings as $curSetting ) {
				$settingsManager->createSetting($curSetting["key"], $curSetting["value"]);
			}
			
			// separate holiday date strings into three arrays, one per each type
			$oneHourHolidays = array();
			$halfDayHourHolidays = array();
			$fullDayHolidays = array();
			
			foreach ( $this->holidays as $curHoliday ) {
					
				$holidayDateString = $curHoliday["day"] . "-" . $curHoliday["month"] . "-" . DateTimeHelper::getCurrentYear();
				
				switch ( $curHoliday["type"] ) {
				
					case "full_day":
						$fullDayHolidays[] = $holidayDateString;
						
					case "half_day":
						$halfDayHolidays[] = $holidayDateString;

					case "one_hour":
						$oneHourHolidays[] = $holidayDateString;	
				
				}
		
			}
			
			//
			// create workplan based on init data
			$workplanManager->createPlan(
									DateTimeHelper::getCurrentYear(),
									$this->workplanProps["weeklyworkhours"],
									$this->workplanProps["annualvacationdaysupto19"],
									$this->workplanProps["annualvacationdays20to49"],
									$this->workplanProps["annualvacationdaysfrom50"],
									$fullDayHolidays,
									$halfDayHolidays,
									$oneHourHolidays
								);
								
								
			// create system entry type
			$entryTypeManager->createType("Presence", \RegularEntryType::CREATOR_SYSTEM, true, true, true);
								
			// create system booking types
			$bookingTypeManager->createType(\OOBookingType::SYSTEM_TYPE_VACATION, \OOBookingType::CREATOR_SYSTEM, true, true, true, "3d2eb4", true);
			$bookingTypeManager->createType(\OOBookingType::SYSTEM_TYPE_UNPAID_LEAVE, \OOBookingType::CREATOR_SYSTEM, true, true, false, "b42e62", true);
			
			// create admin user
			$adminUser = $userManager->createUser(
													$this->userProps["firstname"],
													$this->userProps["lastname"],
													$this->userProps["email"],
													DateTimeHelper::getDateTimeFromStandardDateFormat("1-1-1970"),
													\User::TYPE_STAFF,
													$this->userProps["login"],
													1.0,
													array(),
													DateTimeHelper::getDateTimeFromStandardDateFormat("1-1-" . DateTimeHelper::getCurrentYear()),
													DateTimeHelper::getDateTimeFromStandardDateFormat("31-12-" . (DateTimeHelper::getCurrentYear() + 100)),
													null,
													Roles::ROLE_ADMINISTRATOR,
													true
												);
			// set password									
			$userManager->setUserPasswordByToken($adminUser->getPasswordResetToken(), $this->userProps["password"]);									
															
			// commit TX
			$con->commit();
			
			echo "\n\nUtilityController:initDatabase()\n\n";
			echo "Database successfully initialized! You may log onto the system with login '" . $this->userProps["login"] . "' and password '" . $this->userProps["password"] . "'";
			echo "\n\n";
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("DatabaseInitializer:initDatabase() - an error has occurred while while attempting to initialize the database", 0, $ex);
		}	

	}
	
	
	/**
	 * Parses the database initialization data
	 */
	public function parseInitData() {
		
        // set up the xml parser
        $xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1); 
		xml_set_element_handler($xml_parser, array($this, "__parseInitData_startTag"), array($this, "__parseInitData_endTag"));
		xml_set_character_data_handler($xml_parser, array($this, "__parseInitData_processCharData")); 
		
		if ( ($fp = fopen(FCPATH . "_setup/setupdata.xml", "r") ) ) {
		    
			while ( $data = fread($fp, 4096) ) {
				
			    if ( ! xml_parse($xml_parser, $data, feof($fp)) ) {
			    	echo "DatabaseInitializer:initDatabase() - " . sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser));
			    }
			}
		
			xml_parser_free($xml_parser);
		}
		else {
			echo "DatabaseInitializer:initDatabase() - failed to open 'initdata.xml'";
		}
	}
	
	
	/**
	 * Support methods for parseInitData()
	 */
	function __parseInitData_startTag($parser, $name, $attrs) {
	 	
		// push current tag onto tag stack
		array_push($this->tagStack, strtolower($name));
		
		switch ( $this->__parseInitData_getCurTagPath() ) {
		
			case "/momosetup/settings/setting":		
				// push array to hold new setting
				array_push($this->settings, array());
				break;
				
			case "/momosetup/initialworkplan/holidays/holiday":		
				// push array to hold new holiday
				array_push($this->holidays, array("type" => $attrs["TYPE"]));
				break;	
		}
	   
	}
	
	function __parseInitData_endTag($parser, $name) {
		// pop current tag off of tag stack
	   	array_pop($this->tagStack);
	}
	
	function __parseInitData_processCharData($parser, $data) {
		
		// get current tag name
		$curTagName = $this->tagStack[count($this->tagStack) - 1];
				
		// process according to context
		switch ( $this->__parseInitData_getCurTagPath() ) {

			case "/momosetup/settings/setting/key":
			case "/momosetup/settings/setting/value":
				$curSetting = & $this->settings[count($this->settings) - 1];
				$curSetting[$curTagName] = $data;
				break;
				
			case "/momosetup/initialworkplan/holidays/holiday/day":
			case "/momosetup/initialworkplan/holidays/holiday/month":
				$curHoliday = & $this->holidays[count($this->holidays) - 1];
				$curHoliday[$curTagName] = $data;
				break;

			case "/momosetup/initialworkplan/year":
			case "/momosetup/initialworkplan/weeklyworkhours":
			case "/momosetup/initialworkplan/annualvacationdaysupto19":
			case "/momosetup/initialworkplan/annualvacationdays20to49":
			case "/momosetup/initialworkplan/annualvacationdaysfrom50":
				$this->workplanProps[$curTagName] = $data;
				break;		
				
			case "/momosetup/adminuser/firstname":
			case "/momosetup/adminuser/lastname":
			case "/momosetup/adminuser/login":
			case "/momosetup/adminuser/password":
			case "/momosetup/adminuser/email":
				$this->userProps[$curTagName] = $data;
				break;	
				
		}
	}
	
	function __parseInitData_getCurTagPath() {
		
		$curElemPath = "";
		
		foreach ( $this->tagStack as $curTagName ) {
			$curElemPath .= "/" . $curTagName;
		}
		
		return $curElemPath;
	}
	
}