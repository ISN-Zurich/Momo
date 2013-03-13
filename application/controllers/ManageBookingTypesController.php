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

use momo\bookingtypemanager\BookingTypeManager;
use momo\bookingtypemanager\exceptions\BookingTypeInUseException;
use momo\core\helpers\FormHelper;
use momo\core\security\Permissions;
use momo\audittrailmanager\EventDescription;

/**
 * ManageBookingTypesController
 * 
 * Exposes the client actions needed to work with booking types.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageBookingTypesController extends Momo_Controller {
	
	/**
	 *  Default action displays a list of all user entry types
	 */
	public function index() {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_LIST_ALL_TYPES);
		
		// get ref to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		
		// retrieve all active booking types
		$activeTypes = $bookingTypeManager->getAllUserTypes();
		
		// prepare view data
		$data["component"] = "components/component_list_bookingtypes.php";
		$data["component_title"] = "Manage Booking Types";
		
		$data["component_types_active"] = $activeTypes;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new entry type
	 */
	public function createType() {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_CREATE_TYPE);
		
		// get ref to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
				
			// get map of OO form field values
			$ooFieldValuesMap = $this->processOOFormValues($this->input->post());	
			
			// strip color value of hash character
			$rgbColorValue = str_replace("#", "", $this->input->post("rgbColorValue"));
			
			//
			// create the type
			$newType = $bookingTypeManager->createType(	
												$this->input->post('type'),
												\OOBookingType::CREATOR_USER,
											 	$ooFieldValuesMap['bookableInDays'],
											 	$ooFieldValuesMap['bookableInHalfDays'],
											 	$this->input->post('paid'),
											 	$rgbColorValue,
											 	$this->input->post('enabled')
										  	);
										  
										  
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newType->compileStateDigest());
						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									BookingTypeManager::MANAGER_KEY,
									"Created Booking Type",
									$eventDescription
								);		
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageBookingTypesController:createType() - a database error has occurred while attempting to create a booking type", 0, $ex);
		}						  
									  
		// redisplay default view
		redirect("/managebookingtypes");
	}
	
	
	/**
	 *  Updates an entry type
	 *  
	 */
	public function updateType() {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_EDIT_TYPE);
		
		// get ref to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
				
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// get map of OO form field values
			$ooFieldValuesMap = $this->processOOFormValues($this->input->post());
			
			// strip color value of hash character
			$rgbColorValue = str_replace("#", "", $this->input->post("rgbColorValue"));
			
			// query for update target
			$updateTarget = $bookingTypeManager->getTypeById($this->input->post('typeId'));
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			//
			// update the type
			$updateTarget = $bookingTypeManager->updateType(	
													$updateTarget,
													$this->input->post('type'),
													\OOBookingType::CREATOR_USER,
												 	$ooFieldValuesMap['bookableInDays'],
												 	$ooFieldValuesMap['bookableInHalfDays'],
												 	$this->input->post('paid'),
												 	$rgbColorValue,
												 	$this->input->post('enabled')
											  );
										  
			
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
											  
			// write to audit trail
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									BookingTypeManager::MANAGER_KEY,
									"Updated Booking Type",
									$eventDescription
								);		

			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageBookingTypesController:updateType() - a database error has occurred while attempting to update the booking type with ID: " . $this->input->post('typeId') , 0, $ex);
		}									
									  
		// redisplay default view
		redirect("/managebookingtypes");
	}
	
	/**
	 *  Processes the OO relevant form elements and returns a map
	 *  with the key values set accordingly. In the absence of applicable form values,
	 *  the returned map will have return all keys set to "false"
	 *
	 *  the map keys are:
	 *
	 *		bookableInDays
	 *		bookableInHalfDays
	 *		bookableInHours
	 *  
	 *  @param	array	$postArray	the array of post values
	 *  
	 */
	private function processOOFormValues($postArray) {
		
		$resultArray = array(
								"bookableInDays" 		=> false,
								"bookableInHalfDays" 	=> false
							);
	
		foreach ( \OOBookingType::$BOOKABLE_IN_MAP_PLURAL as $curOptionKey => $curOptionValue ) {
				
			switch ( $curOptionKey ) {
				
				case \OOBookingType::BOOKABLE_IN_DAYS:
					
					if ( in_array(\OOBookingType::BOOKABLE_IN_DAYS, $postArray['bookableIn']) ) {
						$resultArray["bookableInDays"] = true;
					}
					
					break;
					
				case \OOBookingType::BOOKABLE_IN_HALFDAYS:
					
					if ( in_array(\OOBookingType::BOOKABLE_IN_HALFDAYS, $postArray['bookableIn']) ) {
						$resultArray["bookableInHalfDays"] = true;
					}
					
					break;
					
			}
			
		}
	
		return $resultArray;
		
	}
	
	
	/**
	 *  Deletes the indicated booking type.
	 *  
	 *  Note: 	The delete operation is permitted, only if the delete target is not in use.
	 *  		The method throws an exception if that should not be the case.
	 *  
	 *  @param 	integer	$typeId
	 *  
	 *  @throws TypeInUseException
	 *  
	 */
	public function deleteType($typeId) {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_DELETE_TYPE);
		
		// get ref to managers needed
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get type instance
		$deleteTarget = $bookingTypeManager->getTypeById($typeId);
		
		if ( ! $deleteTarget->isInUse() ) {
			
			// get db connection (for TX operations, it is best practice to specify connection explicitly)
			$con = \Propel::getConnection(\TeamPeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {
			
				// all ok, delete
				$bookingTypeManager->deleteType($deleteTarget);
				
				// write event to audit trail
				$eventDescription = new EventDescription();
				$eventDescription->addDescriptionItem("Booking Type", $deleteTarget->getType());
																																					
				$auditManager->addAuditEvent(	
										$this->getCtx()->getUser(),
										BookingTypeManager::MANAGER_KEY,
										"Deleted Booking Type",
										$eventDescription
									);	
									
				// commit TX
				$con->commit();
			}
			catch (\PropelException $ex) {
				// roll back
				$con->rollback();
				// rethrow			
				throw new MomoException("ManageBookingTypesController:deleteType() - a database error has occurred while attempting to delete the booking type with ID: " . $typeId , 0, $ex);
			}								
		}
		else {
			// entry type in use, throw exception
			throw new BookingTypeInUseException("ManageBookingTypesController:deleteType() - unable to delete booking type (ID: " . $typeId  . ") as it is in use.");
		}
		
		// redisplay default view
		redirect("/managebookingtypes");
	}
	
	
	/**
	 *  Displays the "create new entry type" form
	 */
	public function displayNewTypeForm() {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_CREATE_TYPE);
		
		// get ref to managers
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// get default color for oo bookings
		$defaultOOBookingsColor = $settingsManager->getSettingValue(\Setting::KEY_OO_BOOKING_DEFAULT_COLOR);
		
		// prepare view data
		$data["component"] = "components/component_form_booking_type.php";
		$data["component_title"] = "New Booking Type";
		$data["component_mode"] = "new";
		$data["component_default_color"] = $defaultOOBookingsColor;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit entry type" form
	 *  
	 *  @param integer	$typeId
	 */
	public function displayEditTypeForm($typeId) {
		
		// authorize call
		$this->authorize(Permissions::BOOKINGTYPES_EDIT_TYPE);
		
		// get ref to managers
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		
		// query for edit target
		$editTarget = $bookingTypeManager->getTypeById($typeId);
		
		// prepare view data
		$data["component"] = "components/component_form_booking_type.php";
		$data["component_title"] = "Edit Booking Type (" . $editTarget->getType() . ")";
		$data["component_mode"] = "edit";

		$data["component_edit_target"] = $editTarget;
		
		// render view
		$this->renderView($data);
	}
	
}