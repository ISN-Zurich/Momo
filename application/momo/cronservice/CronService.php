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

namespace momo\cronservice;

use momo\core\exceptions\MomoException;
use momo\core\services\BaseService;

/**
 * CronService
 * 
 * Handles time scheduled Momo tasks.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.cronservice
 */
class CronService extends BaseService {
	
	const SERVICE_KEY = "SERVICE_CRON";
	const LOG_FILENAME = "cronservice";
	
	private $curTaskClassName = null;		// holds the name of the currently executed task, used to log non-recoverable errors
	
	
	/**
	 * Constructor
	 * 
	 * As fatal errors are non-catchable, we register a shutdown function
	 * that logs any shutdown events that might arise while executing crontasks.
	 */
	function __construct() {
        parent::__construct();
        
        // register shutdown function in case we go down in flames...
        register_shutdown_function(array($this, "onShutDown"));
    }
	
    
	/**
	 * Runs the indiacted group of cron tasks
	 * 
	 * @param	string	$taskGroupName		- E {hourly, daily, weekly, monthly}, indicates what task group to run 
	 */
	public function runTasks($taskGroupName) {
		
		// obtain cronservice config data
		$cronConfig = $this->getCI()->config->item("cronservice", "momo");
		
		// get indicated task list from indicated task group
		$taskGroupArray = null;
		
		switch ( $taskGroupName ) {
			
			case "hourly":
				$taskGroupArray = $cronConfig["hourly_tasks"] !== null ? $cronConfig["hourly_tasks"] : array();	
				break;
				
			case "daily":
				$taskGroupArray = $cronConfig["daily_tasks"] !== null ? $cronConfig["daily_tasks"] : array();				
				break;

			case "weekly":
				$taskGroupArray = $cronConfig["weekly_tasks"] !== null ? $cronConfig["weekly_tasks"] : array();	
				break;
				
			case "monthly":
				$taskGroupArray = $cronConfig["monthly_tasks"] !== null ? $cronConfig["monthly_tasks"] : array();	
				break;		
		
		}
		
		// log start of task group run
		$this->log("info", "starting execution of cronservice task group: " . $taskGroupName);
		
		//
		// sequentially execute all registered hourly tasks
		foreach ( $taskGroupArray as $curTaskClassName ) {
			
			// execution is wrapped in try-catch so that the (non-fatal) failure of any
			// one task does not adversely affect the execution of the others
			try {
				// store current task name in instance variable
				$this->curTaskClassName = $curTaskClassName;
				
				// instantiate current task class
				$taskObj = new $curTaskClassName();
			
				if ( $taskObj instanceof CronTask ) {
					// log start of task execution
					$this->log("info", "");
					$this->log("info", "\tstarting execution of task: " . $curTaskClassName);
					
					// run task
					$taskObj->run($this);
					
					// log end of task execution
					$this->log("info", "\tended execution of task: " . $curTaskClassName);
					$this->log("info", "");
					
				}
				else {
					// throw exception
					$errorMsg = "The indicated task: " . $curTaskClassName . " cannot be executed as it is not an instance of CronTask.";
					throw new MomoException($errorMsg);
				}
			}
			catch (\Exception $ex) {
				// log execution error
				$errorMsg = "CronService:runTasks() - an error occurred while attempting to run task: " . $curTaskClassName;
				$errorMsg .= "\nMessage: ". $ex->getMessage();
				
				$this->log("error", $errorMsg);
			}
		
		}
		
		// log end of execution	
		$this->log("info", "ended execution of cronservice task group: " . $taskGroupName);
		$this->log("info", "******************************************************************");
		
	}
	
	
	/**
	 * Executed in case runTasks() encounters fatal error
	 */
	public function onShutDown() { 
		$error = error_get_last();
	    if ( $error['type'] == E_ERROR ) {
	    	$errorMsg = "CronService - a fatal error occurred while attempting to run task: " . $this->curTaskClassName;
	    	$this->log("error", $errorMsg);
	    } 
	}
	
	/**
	 * logs a message to the cronservice log
	 * 
	 * @param $level 	- the log level E {"error", "debug", "info"}
	 */
	public function log($level, $msg) { 
		// write log message via CI logger
		$this->getCI()->log->write_log($level, $msg, false, CronService::LOG_FILENAME);
	}
	
}								  
									  