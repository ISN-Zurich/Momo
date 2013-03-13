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

use momo\core\exceptions\MomoException;
use momo\audittrailmanager\EventDescription;
use momo\entrymanager\EntryManager;
use momo\core\helpers\DateTimeHelper;
use momo\core\security\Roles;
use momo\core\security\Permissions;

/**
 * ManageAdjustmentsController
 * 
 * Exposes the client actions needed to work with adjustments.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageAdjustmentsController extends Momo_Controller {
	
	/**
	 *  Default action
	 */
	public function index() {
		$this->listAdjustments();
	}
	
	
	/**
	 *  Lists the adjustments as indicated by the POST params.
	 */
	public function listAdjustments() {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_LIST_ALL_ADJUSTMENTS);
		
		// get ref to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$userManager = $this->getCtx()->getUserManager();
		
		//
		// prepare post parameters for call to adjustment entry manager
		
		// target user
		$targetUser = null;
		if ( 	($this->input->post('targetUserId') !== false)
		 	 && ($this->input->post('targetUserId') != -1) ) {

			// if a user id is passed, we retrieve the indicated user and persist user id to session
			$targetUser = $userManager->getUserById($this->input->post('targetUserId'));
						
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.targetuserid', $this->input->post('targetUserId'));
		}
		else if ( $this->session->userdata('manageadjustmentscontroller.list_adjustments.targetuserid') !== false ) {
			// retrieve persisted user
			$targetUser = $userManager->getUserById($this->session->userdata('manageadjustmentscontroller.list_adjustments.targetuserid'));
		}
		
		
		//
		// "from" date
		$fromDate = null;
		if ( 	   ($this->input->post('fromDate') !== false)
				&& ($this->input->post('fromDate') != "") ) {
			//
			// if there is a valid date passed, we accept it and persist it to the session
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('fromDate'));
			
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.fromdate', $fromDate);
		}
		else if ( $this->input->post('fromDate') !== false ) {
			// if there is no from date passed, we set the query start date to the start of the current year
			$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat("1-1-" . DateTimeHelper::getCurrentYear());
			
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.fromdate', $fromDate);
		}
		else {
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('manageadjustmentscontroller.list_adjustments.fromdate') !== false ) {
				$fromDate = $this->session->userdata('manageadjustmentscontroller.list_adjustments.fromdate');
			}
			else {
				// if there is no session value to restore, we set the from date to the start of the current year
				$fromDate = DateTimeHelper::getDateTimeFromStandardDateFormat("1-1-" . DateTimeHelper::getCurrentYear());
			}			
		}
		
		
		//
		// "until" date
		$untilDate = null;
		if ( 	   ($this->input->post('untilDate') !== false)
				&& ($this->input->post('untilDate') != "") ) {
			//		
			// if there is a valid date passed, we accept it and persist it to the session
			$untilDate = DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('untilDate'));
			$untilDateForQuery = $untilDate;
			
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.untildate', $untilDate);
		}
		else if ( $this->input->post('untilDate') !== false ) {
				
			// if there is an empty date passed, we default to a one year range (based on current from date)	
			$untilDate = DateTimeHelper::addMonthsToDateNormalSemantics($fromDate, 12);
			$untilDate = DateTimeHelper::addDaysToDateTime($untilDate, -1);
			
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.untildate', $untilDate);
		}
		else {
			
			// if there is no date passed we attempt to restore from session
			if ( $this->session->userdata('manageadjustmentscontroller.list_adjustments.untildate') !== false ) {
				// restore from session
				$untilDate = $this->session->userdata('manageadjustmentscontroller.list_adjustments.untildate');
			}
			else {
				// if there is no session value to restore, we default to a one month range (based on current from date)	
				$untilDate = DateTimeHelper::addMonthsToDateNormalSemantics($fromDate, 12);
				$untilDate = DateTimeHelper::addDaysToDateTime($untilDate, -1);
			}	
		}
		
		//
		// "targetType"
		$targetType = null;
		if ( $this->input->post('targetType') !== false ) {

			// if a target type is passed, we persist it to session
			// "-1" (all types) is mapped to null, as this tells entrymanager not to restrict by type
			$targetType = ($this->input->post('targetType') != "-1") ? $this->input->post('targetType') : null;
						
			// persist to session
			$this->session->set_userdata('manageadjustmentscontroller.list_adjustments.targettype', $targetType);
		}
		else {
			// in case, no valid type is passed, we attempt to restore the value from session
			if ( $this->session->userdata('manageadjustmentscontroller.list_adjustments.targettype') !== false ) {
				
				// retrieve type from session
				$targetType = $this->session->userdata('manageadjustmentscontroller.list_adjustments.targettype');
			}
		}
		
		
		// if there is a target user defined, we query for the adjustments
		$adjustmentEntries = new \PropelCollection(array());
		if ( $targetUser !== null ) {
			//
			// retrieve adjustment entries that match constraints
			$adjustmentEntries = $entryManager->getAdjustmentEntries(
																		$targetUser,
																		$targetType,
																		$fromDate,
																		$untilDate
																	);
		}
		
		
		
		// prepare view data
		$data["component"] = "components/component_list_adjustments.php";
		$data["component_title"] = "Adjustments";
		
		$data["component_users"] = $userManager->getAllUsers();
		$data["component_adjustment_entries"] = $adjustmentEntries;
		
		$data["component_targettype"] = $targetType;
		$data["component_targetuser"] = $targetUser;
		$data["component_fromdate"] = $fromDate;
		$data["component_untildate"] = $untilDate;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new adjustment
	 */
	public function createAdjustment() {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_CREATE_ADJUSTMENT);
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		$userManager = $this->getCtx()->getUserManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// get target user
			$targetUser = $userManager->getUserById($this->input->post('adjustedUserId'));
			
			// create adjustment entry
			$newAdjustmentEntry = $entryManager->createAdjustmentEntry(
														DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('adjustmentDate')),
														$targetUser,
														$this->input->post('adjustmentType'),
														\AdjustmentEntry::CREATOR_USER,
														$this->input->post('normalizedAdjustmentValue'),
														$this->input->post('adjustmentReason')
													);
	
												
			// we need to recompute vacation/overtime lapses in accordance with adjustment type
			if ( $this->input->post('adjustmentType') == \AdjustmentEntry::TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS ) {
				// we're dealing with an vacation adjustment, recompute the vacation lapses
				$enforcementService->recomputeAllVacationLapses($targetUser);	
			}
			else if ( $this->input->post('adjustmentType') == \AdjustmentEntry::TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS ) {
				// we're dealing with an overtime adjustment, recompute the overtime lapses
				$enforcementService->recomputeAllOvertimeLapses($targetUser);			
			}
					
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newAdjustmentEntry->compileStateDigest());							
												
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									"Created Adjustment Entry",
									$eventDescription
								);
			
			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow			
			throw new MomoException("WorkplanAdjustmentsController:createAdjustment() - an error has occurred while while attempting to create an adjustment entry for user: " . $targetUser->getFullName(), 0, $ex);
		}			
			
		// redisplay default view
		redirect("/manageadjustments");
	}
	
	
	/**
	 *  Updates an adjustment
	 */
	public function updateAdjustment() {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_EDIT_ADJUSTMENT);
		
		// get managers
		$entryManager = $this->getCtx()->getEntryManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get a reference to the user that owns the update target
			$updateTarget = $entryManager->getEntryById($this->input->post('entryId'))->getChildObject();
	
			// write pre-update state to event description
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());							
													
			// update adjustment
			$updateTarget = $entryManager->updateAdjustmentEntry(
																	$this->input->post('entryId'),
																	DateTimeHelper::getDateTimeFromStandardDateFormat($this->input->post('adjustmentDate')),
																	$this->input->post('adjustmentType'),
																	$this->input->post('normalizedAdjustmentValue'),
																	$this->input->post('adjustmentReason')
																);
	
			// recompute vacation and overtime lapses
			$enforcementService->recomputeAllVacationLapses($updateTarget->getUser());	
			$enforcementService->recomputeAllOvertimeLapses($updateTarget->getUser());			
		
			// write post update state to event description
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());			
					
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									"Updated Adjustment Entry",
									$eventDescription
								);
			
			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("WorkplanAdjustmentsController:updateAdjustment() - an error has occurred while while attempting to update the adjustment entry with id: " . $this->input->post('entryId'), 0, $ex);
		}																					
		
		// redisplay default view
		redirect("/manageadjustments");
	}
	
	
	/**
	 *  "Deletes" the indicated adjustment
	 *  
	 *  @param 	integer	$adjustmentId		the id of the adjustment to delete
	 */
	public function deleteAdjustment($adjustmentId) {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_DELETE_ADJUSTMENT);
		
		// get ref to project manager
		$entryManager = $this->getCtx()->getEntryManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\AdjustmentEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get a reference to the delete target
			$deleteTarget = $entryManager->getEntryById($adjustmentId)->getChildObject();
			
			// delete the adjustment entry
			$entryManager->deleteEntryById($adjustmentId);
			
			// we need to recompute vacation/overtime lapses in accordance with adjustment type
			if ( $deleteTarget->getType() == \AdjustmentEntry::TYPE_VACATION_BALANCE_ADJUSTMENT_IN_DAYS ) {
				// we're dealing with a vacation adjustment, recompute the vacation lapses
				$enforcementService->recomputeAllVacationLapses($deleteTarget->getUser());	
			}
			else if ( $deleteTarget->getType() == \AdjustmentEntry::TYPE_WORKTIME_BALANCE_ADJUSTMENT_IN_SECONDS ) {
				// we're dealing with an overtime adjustment, recompute the overtime lapses
				$enforcementService->recomputeAllOvertimeLapses($deleteTarget->getUser());			
			}
			
			//
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($deleteTarget->compileStateDigest());							
												
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryManager::MANAGER_KEY,
									"Deleted Adjustment Entry",
									$eventDescription
								);
			
			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("WorkplanAdjustmentsController:deleteAdjustment() - an error has occurred while while attempting to delete the adjustment entry with id: " . $adjustmentId, 0, $ex);
		}						
		
		// redisplay default view
		redirect("/manageadjustments");
	}
	
	
	/**
	 *  Displays the "create new adjustment" form
	 *  
	 *  @param	$userId		- the user for which the adjustment is to be created
	 *  
	 */
	public function displayNewAdjustmentForm($userId) {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_CREATE_ADJUSTMENT);
		
		// get managers
		$userManager = $this->getCtx()->getUserManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// target user
		$targetUser = $userManager->getUserById($userId);
		
		// adjustments may be made from user entry date on forward
		$earliestAdjustmentDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($targetUser->getEntryDate()),
											"month" => DateTimeHelper::getMonthFromDateTime($targetUser->getEntryDate()),
											"day" => DateTimeHelper::getDayFromDateTime($targetUser->getEntryDate())
										 );			

		// adjustments may be made up to latest day of last workplan, provided employment period does not end before that time
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		$latestAdjustmentDate = ($targetUser->getExitDate() < $lastWorkplanDate) ? $targetUser->getExitDate() : $lastWorkplanDate;
		
		$latestAdjustmentDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestAdjustmentDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestAdjustmentDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestAdjustmentDate)
										);	
		
		
		// prepare view data
		$data["component"] = "components/component_form_adjustment.php";
		$data["component_title"] = "New Adjustment for " . $targetUser->getFullName();
		$data["component_mode"] = "new";
		
		// set the date information indicating the time range for which adjustments may be made
		$data["component_date_elems_earliest_adjustment"] = $earliestAdjustmentDateElems;
		$data["component_date_elems_latest_adjustment"] = $latestAdjustmentDateElems;
		
		$data["component_target_user"] = $targetUser;
		
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit adjustment" form
	 *  
	 *  @param integer	$adjustmentId		- the project for which to display the edit form
	 */
	public function displayEditAdjustmentForm($adjustmentId) {
		
		// authorize call
		$this->authorize(Permissions::ADJUSTMENTS_EDIT_ADJUSTMENT);
		
		// get managers
		$userManager = $this->getCtx()->getUserManager();
		$entryManager = $this->getCtx()->getEntryManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		
		// edit target
		$editTarget = $entryManager->getEntryById($adjustmentId)->getChildObject();

		// target user
		$targetUser = $editTarget->getUser();
		
		// adjustments may be made from user entry date on forward
		$earliestAdjustmentDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($targetUser->getEntryDate()),
											"month" => DateTimeHelper::getMonthFromDateTime($targetUser->getEntryDate()),
											"day" => DateTimeHelper::getDayFromDateTime($targetUser->getEntryDate())
										 );			

		// adjustments may be made up to latest day of last workplan, provided employment period does not end before that time
		$lastWorkplanDate = $workplanManager->getLastDayInPlan($workplanManager->getLastPlan())->getDateOfDay();
		$latestAdjustmentDate = ($targetUser->getExitDate() < $lastWorkplanDate) ? $targetUser->getExitDate() : $lastWorkplanDate;
		
		$latestAdjustmentDateElems = array(
											"year" => DateTimeHelper::getYearFromDateTime($latestAdjustmentDate),
											"month" => DateTimeHelper::getMonthFromDateTime($latestAdjustmentDate),
											"day" => DateTimeHelper::getDayFromDateTime($latestAdjustmentDate)
										);	
		
		
		// prepare view data
		$data["component"] = "components/component_form_adjustment.php";
		$data["component_title"] = "Edit Adjustment for " . $targetUser->getFullName();
		$data["component_mode"] = "edit";
		
		// set the date information indicating the time range for which adjustments may be made
		$data["component_date_elems_earliest_adjustment"] = $earliestAdjustmentDateElems;
		$data["component_date_elems_latest_adjustment"] = $latestAdjustmentDateElems;
		
		$data["component_target_user"] = $targetUser;
		$data["component_edit_target"] = $editTarget;
		
		// render view
		$this->renderView($data);
	}
	
}