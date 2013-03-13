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

namespace momo\core\application;

/**
 * ApplicationContext
 * 
 * Serves as a single access point to managers, services, the active user object and the application scope
 *
 * Singleton, obtain instances via a call to "ApplicationContext::getInstance()"
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.application
 */
class ApplicationContext
{
	// singleton instance
  	private static $instance = null;
 	
  	// application context objects
  	private $userObj = null;
  	private $applicationScope = null;
  	
  	private $userManager = null;
  	private $securityService = null;
  	private $entryManager = null;
  	private $teamManager = null;
  	private $projectManager = null;
  	private $entryTypeManager = null;
  	private $bookingTypeManager = null;
  	private $workPlanManager = null;
    private $settingsManager = null;
  	private $auditTrailManager = null;
  	private $ooManager = null;
  	private $tagManager = null;
  	private $emailService = null;
  	private $computationService = null;
  	private $enforcementService = null;
  	private $cronService = null;
  	private $reportingService = null;
  	
   
  	
  	/**
   	 * for singletons constructor is private, i.e. inaccessible
   	 */
   	private function __construct() { }
 
   	
   	/**
   	 * provides access to singleton class instance
   	 */
   	public static function getInstance() {
 
		if ( self::$instance === null ) {
        	self::$instance = new self();
       	}
       	
       	return self::$instance;
   	}

   	
   	/**
   	 * returns a reference to the active user's "User" object
   	 */
	public function getUser() {
		
		if ( $this->userObj === null ) {
			$ci = & get_instance();
			$usermanager = $this->getUserManager();
			$this->userObj = $usermanager->getUserByLogin($ci->session->userdata('securityservice.user_login'));
		}
		
 		return $this->userObj;
   	}
   	
   	
   	/**
   	 * returns a reference to the user manager
   	 */
	public function getUserManager() {
		
		if ( $this->userManager === null ) {
			$this->userManager = new \momo\usermanager\UserManager();
		}
		
 		return $this->userManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the security service
   	 */
	public function getSecurityService() {
		
 		if ( $this->securityService === null ) {
			$this->securityService = new \momo\securityservice\SecurityService();
		}
		
 		return $this->securityService;
   	}
   	
   	
   	/**
   	 * returns a reference to the entry manager
   	 */
	public function getEntryManager() {
		
 		if ( $this->entryManager === null ) {
			$this->entryManager = new \momo\entrymanager\EntryManager();
		}		
		
 		return $this->entryManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the team manager
   	 */
	public function getTeamManager() {
		
 		if ( $this->teamManager === null ) {
			$this->teamManager = new \momo\teammanager\TeamManager();
		}	
			
 		return $this->teamManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the project manager
   	 */
	public function getProjectManager() {
		
 		if ( $this->projectManager === null ) {
			$this->projectManager = new \momo\projectmanager\ProjectManager();
		}		
		
 		return $this->projectManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the type manager
   	 */
	public function getEntryTypeManager() {
		
 		if ( $this->entryTypeManager === null ) {
			$this->entryTypeManager = new \momo\entrytypemanager\EntryTypeManager();
		}		
		
 		return $this->entryTypeManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the booking type manager
   	 */
	public function getBookingTypeManager() {
		
 		if ( $this->bookingTypeManager === null ) {
			$this->bookingTypeManager = new \momo\bookingtypemanager\BookingTypeManager();
		}		
		
 		return $this->bookingTypeManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the workplan manager
   	 */
	public function getWorkplanManager() {
		
 		if ( $this->workPlanManager === null ) {
			$this->workPlanManager = new \momo\workplanmanager\WorkplanManager();
		}		
		
 		return $this->workPlanManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the audit trail manager
   	 */
	public function getAuditTrailManager() {
		
 		if ( $this->auditTrailManager === null ) {
			$this->auditTrailManager = new \momo\audittrailmanager\AuditTrailManager();
		}		
		
 		return $this->auditTrailManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the out-of-office manager
   	 */
	public function getOOManager() {
		
 		if ( $this->ooManager === null ) {
			$this->ooManager = new \momo\oomanager\OOManager();
		}		
		
 		return $this->ooManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the tag manager
   	 */
	public function getTagManager() {
		
 		if ( $this->tagManager === null ) {
			$this->tagManager = new \momo\tagmanager\TagManager();
		}	
			
 		return $this->tagManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the settings manager
   	 */
	public function getSettingsManager() {
		
 		if ( $this->settingsManager === null ) {
			$this->settingsManager = new \momo\settingsmanager\SettingsManager();
		}	
			
 		return $this->settingsManager;
   	}
   	
   	
   	/**
   	 * returns a reference to the computation service
   	 */
	public function getComputationService() {
		
 		if ( $this->computationService === null ) {
			$this->computationService = new \momo\computationservice\ComputationService();
		}	
			
 		return $this->computationService;
   	}
   	
   	
	/**
   	 * returns a reference to the enforcement service
   	 */
	public function getEnforcementService() {
		
 		if ( $this->enforcementService === null ) {
			$this->enforcementService = new \momo\enforcementservice\EnforcementService();
		}	
			
 		return $this->enforcementService;
   	}
   	
   	
	/**
   	 * returns a reference to the cron service
   	 */
	public function getCronService() {
		
 		if ( $this->cronService === null ) {
			$this->cronService = new \momo\cronservice\CronService();
		}	
			
 		return $this->cronService;
   	}
   	
   	
	/**
   	 * returns a reference to the reporting service
   	 */
	public function getReportingService() {
		
 		if ( $this->reportingService === null ) {
			$this->reportingService = new \momo\reportingservice\ReportingService();
		}	
			
 		return $this->reportingService;
   	}
   	
   	
   	/**
   	 * returns a reference to the email service
   	 */
	public function getEmailService() {
		
 		if ( $this->emailService === null ) {
			$this->emailService = new \momo\emailservice\EmailService();
		}		
		
 		return $this->emailService;
   	}
   	
   	
	/**
   	 * returns a reference to the application scope
   	 */
	private function getApplicationScope() {
		
 		if ( $this->applicationScope === null ) {
			$this->applicationScope = new \momo\core\application\ApplicationScope();
		}		
		
 		return $this->applicationScope;
   	}
   	
   	
	/**
   	 * sets a value in the application scope
   	 * 
   	 * @param $key		
   	 * @param $value
   	 */
	public function setApplicationScopeValue($key, $value) {
		$this->getApplicationScope()->setValue($key, $value);
   	}
   	
   	
	/**
   	 * gets a value from the application scope
   	 * 
   	 * @param $key		
   	 * 
   	 * @return string
   	 */
	public function getApplicationScopeValue($key) {
		return $this->getApplicationScope()->getValue($key);
   	}
   	
   	
   	/**
   	 * for singletons cloning is disabled
   	 */
   	private function __clone() { }
   	
}