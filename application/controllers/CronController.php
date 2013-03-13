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
 * CronController
 * 
 * Exposes cron related client actions - CLI use only.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class CronController extends Momo_Controller {
	
	function __construct() {
		
        parent::__construct();
 
        // controller is for CLI use only.
      	if ( ! $this->input->is_cli_request() ) {
     		die;   	
        }
    }
    
    
	/**
	 * Runs the "hourly" cron tasks
	 */
	public function runHourlyTasks() {
		$cronService = $this->getCtx()->getCronService();	
		$cronService->runTasks("hourly");
	}
	
	
	/**
	 * Runs the "daily" cron tasks
	 */
	public function runDailyTasks() {
		$cronService = $this->getCtx()->getCronService();	
		$cronService->runTasks("daily");
	}
	
	
	/**
	 * Runs the "weekly" cron tasks
	 */
	public function runWeeklyTasks() {
		$cronService = $this->getCtx()->getCronService();	
		$cronService->runTasks("weekly");
	}
	
	
	/**
	 * Runs the "monthly" cron tasks
	 */
	public function runMonthlyTasks() {
		$cronService = $this->getCtx()->getCronService();	
		$cronService->runTasks("monthly");
	}
	
}
