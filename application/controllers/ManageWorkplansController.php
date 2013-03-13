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

use momo\core\security\Roles;
use momo\core\security\Permissions;

/**
 * ManageWorkplansController
 * 
 * Exposes the client actions needed to work with workplans.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageWorkplansController extends Momo_Controller {
	
	/**
	 *  Default action displays a list of all the available workplans
	 */
	public function index() {
		
		// authorize call
		$this->authorize(Permissions::WORKPLANS_LIST_ALL_PLANS);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();

		// get a list of all active workplans
		$allWorkplans = $workplanManager->getAllPlans();
	
		// prepare view data
		$data["component"] = "components/component_list_workplans.php";
		$data["component_title"] = "Manage Workplans";
		
		$data["component_workplans_all"] = $allWorkplans;
	
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Processes the result of the multi-step workplan form
	 *  and calls a "create" or "update" operation, as indicated
	 *  by active call mode
	 */
	public function processPlanForm() {
		
		// get post array from session
		$postArray = $this->session->userdata('manageworkplanscontroller.post_array');
		
		if ( $postArray["componentMode"] == "new" ) {
			$this->createPlan($postArray);
		}
		elseif ($postArray["componentMode"] == "edit") {
			$this->updatePlan($postArray);
		}
	}
	
	
	/**
	 *  Creates a new workplan
	 *  
	 *  @package array $postArray 	the array of post values
	 */
	private function createPlan($postArray) {
			
		// authorize call
		$this->authorize(Permissions::WORKPLANS_CREATE_PLAN);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// create the plan
		$workplanManager->createPlan(	
										$postArray['workPlanYear'],
										$postArray['weeklyWorkHours'],
										$postArray['annualVacationDaysUpTo19'],
										$postArray['annualVacationDays20to49'],
										$postArray['annualVacationDaysFrom50'],
										($postArray['fullDayHolidays'] != null ? $postArray['fullDayHolidays'] : array()),
										($postArray['halfDayHolidays'] != null ? $postArray['halfDayHolidays'] : array()),
										($postArray['oneHourHolidays'] != null ? $postArray['oneHourHolidays'] : array())
									);
								
		// redisplay default view
		redirect("/manageworkplans");
	}
	
	
	/**
	 *  Updates a workplan
	 *  
	 *  @package array $postArray 	the array of post values 
	 */
	private function updatePlan($postArray) {
		
		// authorize call
		$this->authorize(Permissions::WORKPLANS_EDIT_PLAN);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$userManager = $this->getCtx()->getUserManager();
		$ooManager = $this->getCtx()->getOOManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\WorkplanPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// update the plan
			$updatedPlan = $workplanManager->updatePlan(	
													$workplanManager->getPlanById($postArray['planId']),
													$postArray['workPlanYear'],
													$postArray['weeklyWorkHours'],
													$postArray['annualVacationDaysUpTo19'],
													$postArray['annualVacationDays20to49'],
													$postArray['annualVacationDaysFrom50'],
													($postArray['fullDayHolidays'] != null ? $postArray['fullDayHolidays'] : array()),
													($postArray['halfDayHolidays'] != null ? $postArray['halfDayHolidays'] : array()),
													($postArray['oneHourHolidays'] != null ? $postArray['oneHourHolidays'] : array())
												);
										
			//
			// reinitialize updated workplan for all users
			
			// get all enabled users
			$allEnabledUsers = $userManager->getAllEnabledUsers();
			
			// reinitialize workplans for each user
			foreach ( $allEnabledUsers as $curUser ) {
				$workplanManager->reinitWorkplanForUser($curUser, $updatedPlan);
			}
			
			//
			// recompute affected oo bookings all users
			
			// recompute oo bookings that fall fully (or partly) within the plan for each user
			foreach ( $allEnabledUsers as $curUser ) {
				$ooManager->recalculateOOBookingsForDateRange(
															$curUser,
															$workplanManager->getFirstDayInPlan($updatedPlan)->getDateOfDay()
														 );
			}
			
			//
			// recompute vacation and worktime lapses for all users
			foreach ( $allEnabledUsers as $curUser ) {
				// recompute the overtime lapses
				$enforcementService->recomputeAllOvertimeLapses($curUser);

				// recompute the vacation lapses
				$enforcementService->recomputeAllVacationLapses($curUser);	
			}
			
			// commit TX
			$con->commit();							
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("ManageWorplansController:updatePlan() - an error has occurred while while attempting to create a new workplan.", 0, $ex);
		}							
		
		// redisplay default view
		redirect("/manageworkplans");
	}
	
	
	/**
	 *  Deletes the indicated workplan
	 *  
	 *  @param 	integer	$planId
	 */
	public function deletePlan($planId) {
		
		// authorize call
		$this->authorize(Permissions::WORKPLANS_DELETE_PLAN);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// delete plan
		$workplanManager->deletePlan($workplanManager->getPlanById($planId));
		
		// redisplay default view
		redirect("/manageworkplans");
	}
	
	
	/**
	 *  Displays the workplan summary that follows workplan form completion (both "new" and "edit")
	 */
	public function displayPlanSummary() {
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// calculate resulting annual workhous
		$annualWorkTimeBreakdown = $workplanManager->computeAnnualWorktimeBreakdown(
		
														$this->input->post("workPlanYear"),
														$this->input->post("weeklyWorkHours"),
														($this->input->post('fullDayHolidays') != null ? $this->input->post('fullDayHolidays') : array()),
														($this->input->post('halfDayHolidays') != null ? $this->input->post('halfDayHolidays') : array()),
														($this->input->post('oneHourHolidays') != null ? $this->input->post('oneHourHolidays') : array())
														
													  );
																  
		// store post array in session
		$this->session->set_userdata('manageworkplanscontroller.post_array', $this->input->post());
		
		// prepare view data
		$data["component"] = "components/component_form_workplan_step_2.php";
		$data["component_title"] = "Review Workplan";
		$data["component_mode"] = $this->input->post("componentMode");
	
		$data["component_post"] = $this->input->post();
		$data["component_worktime_breakdown"] = $annualWorkTimeBreakdown;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "create new workplan" form
	 */
	public function displayNewPlanForm() {
		
		// authorize call
		$this->authorize(Permissions::WORKPLANS_CREATE_PLAN);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
			
		// prepare view data
		$data["component"] = "components/component_form_workplan_step_1.php";
		$data["component_title"] = "New Workplan";
		$data["component_mode"] = "new";
	
		$data["component_next_possible_workplanyear"] = $this->determineNextPossibleWorkPlanYear();
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit workplan" form
	 *  
	 *  @param integer	$planId		- the workplan for which to display the edit form  
	 */
	public function displayEditPlanForm($planId) {
		
		// authorize call
		$this->authorize(Permissions::WORKPLANS_EDIT_PLAN);
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// query for edit target
		$editTarget = $workplanManager->getPlanById($planId);
			
		// prepare view data
		$data["component"] = "components/component_form_workplan_step_1";
		$data["component_title"] = "Edit Workplan (" . $editTarget->getYear() . ")";
		$data["component_mode"] = "edit";
	
		$data["component_edit_target"] = $editTarget;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Redisplays the workplan from when stepping back from the summary
	 */
	public function stepBackToPlanForm() {
		
		// get ref to managers needed
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// get post array from session
		$postArray = $this->session->userdata('manageworkplanscontroller.post_array');
			
		// prepare view data
		$data["component"] = "components/component_form_workplan_step_1";
		$data["component_title"] = $postArray["componentTitle"];
		$data["component_mode"] = $postArray["componentMode"];
	
		$data["component_postarray"] = $postArray;
		$data["component_next_possible_workplanyear"] = $this->determineNextPossibleWorkPlanYear();
		
		// if we're in edit mode, query for edit target and place it in view data
		if ( $postArray["componentMode"] == "edit" ) {
			$data["component_edit_target"] = $workplanManager->getPlanById($postArray["planId"]);
		}
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 * Determines the year of the next possible workplan
	 *
	 * @return	integer
	 */
	private function determineNextPossibleWorkPlanYear() {
		
		$workplanManager = $this->getCtx()->getWorkplanManager();
			
		// get last workplan
		$lastPlan = $workplanManager->getLastPlan();
		
		return $lastPlan->getYear() + 1;
	}
	
	
	
}