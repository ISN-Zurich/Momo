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

use momo\entrytypemanager\EntryTypeManager;
use momo\audittrailmanager\EventDescription;
use momo\core\helpers\FormHelper;
use momo\core\security\Permissions;
use momo\entrytypemanager\exceptions\EntryTypeInUseException;
use momo\core\exceptions\MomoException;

/**
 * ManageEntryTypesController
 * 
 * Exposes the client actions needed to work with entry types.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageEntryTypesController extends Momo_Controller {
	
	/**
	 *  Default action displays a list of all user entry types
	 */
	public function index() {
		
		// authorize call
		$this->authorize(Permissions::ENTRYTYPES_LIST_ALL_TYPES);
		
		// get ref to managers needed
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		
		// retrieve all active entry types
		$activeTypes = $entryTypeManager->getAllUserTypes();
		
		// prepare view data
		$data["component"] = "components/component_list_entrytypes.php";
		$data["component_title"] = "Manage Entry Types";
		
		$data["component_types_active"] = $activeTypes;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new entry type
	 */
	public function createType() {
		
		// authorize call
		$this->authorize(Permissions::ENTRYTYPES_CREATE_TYPE);
		
		// get ref to managers needed
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection
		$con = \Propel::getConnection(\RegularEntryTypePeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			//
			// create the type
			$newType = $entryTypeManager->createType(	$this->input->post('type'),
														\RegularEntryType::CREATOR_USER,
													 	$this->input->post('worktimeCredit'),
													 	$this->input->post('enabled')
													  );
										  
			//
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newType->compileStateDigest());	
							
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryTypeManager::MANAGER_KEY,
									"Created Entry Type",
									$eventDescription
								);		

			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageEntryTypesController:createType() - an error has occurred while while attempting to create a new entry type", 0, $ex);
		}										
									  
		// redisplay default view
		redirect("/manageentrytypes");
	}
	
	
	/**
	 *  Updates an entry type
	 *  
	 *  Note: 	if the edit target is in use, the update operation is permitted only for the following fields:
	 *  
	 *  			- type
	 *  			- enabled
	 *  
	 *  		in this case, all other fields posted are ignored
	 *  
	 *  		however, if the edit target is not is use, all fields may be updated
	 *  
	 */
	public function updateType() {
		
		// authorize call
		$this->authorize(Permissions::ENTRYTYPES_EDIT_TYPE);
		
		// get ref to managers needed
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();

		// get db connection
		$con = \Propel::getConnection(\RegularEntryTypePeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// query for update target
			$updateTarget = $entryTypeManager->getTypeById($this->input->post('typeId'));
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// set values of "non-editable" fields depending on whether type is in use, or not
			if ( $updateTarget->isInUse() ) {
				$workTimeCreditAwarded = $updateTarget->getWorkTimeCreditAwarded();
			}
			else {
				$workTimeCreditAwarded = $this->input->post('worktimeCredit');
			}
			
			// update the type
			$updateTarget = $entryTypeManager->updateType(	
															$updateTarget,
															$this->input->post('type'),
															\RegularEntryType::CREATOR_USER,
														 	$workTimeCreditAwarded,
														 	$this->input->post('enabled')
														  );

														  
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
																			  
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									EntryTypeManager::MANAGER_KEY,
									"Updated Entry Type",
									$eventDescription
								);	

			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow			
			throw new MomoException("ManageEntryTypesController:updateType() - an error has occurred while while attempting to update the entry type with id: " . $updateTarget->getId() , 0, $ex);
		}				
		
		// redisplay default view
		redirect("/manageentrytypes");
	}
	
	
	/**
	 *  Deletes the indicated entry type.
	 *  
	 *  Note: 	The delete operation is permitted, only if the delete target is not in use.
	 *  		The method throws an exception if that should not be the case.
	 *  
	 *  @param 	integer	$typeId
	 *  
	 *  @throws EntryTypeInUseException
	 *  
	 */
	public function deleteType($typeId) {
		
		// authorize call
		$this->authorize(Permissions::ENTRYTYPES_DELETE_TYPE);
		
		// get ref to entry type manager 
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		
		// get type instance
		$deleteTarget = $entryTypeManager->getTypeById($typeId);
		
		// delete proceeds only if type not in use
		if ( ! $deleteTarget->isInUse() ) {
			
			// get db connection
			$con = \Propel::getConnection(\RegularEntryTypePeer::DATABASE_NAME);
			
			// start TX
			$con->beginTransaction();
	 
			try {
				// delete the type
				$entryTypeManager->deleteType($deleteTarget);
				
				//
				// write event to audit trail
				$auditManager = $this->getCtx()->getAuditTrailManager();
				
				$eventDescription = new EventDescription();
				$eventDescription->addDescriptionItem("Entry Type", $deleteTarget->getType());
																										
				// add event to audit trail						
				$auditManager->addAuditEvent(	
										$this->getCtx()->getUser(),
										EntryTypeManager::MANAGER_KEY,
										"Deleted Entry Type",
										$eventDescription
									);	

				// commit TX
				$con->commit();
			}
			catch (\Exception $ex) {
				// rollback
				$con->rollback();
				// rethrow			
				throw new MomoException("ManageEntryTypesController:updateType() - an error has occurred while while attempting to delete the entry type with id: " . $typeId, 0, $ex);
			}							
			
		}
		else {
			// entry type in use, throw exception
			throw new EntryTypeInUseException("ManageEntryTypesController:deleteType() - unable to delete entry type (ID: " . $typeId  . ") as it is in use.");
		}
		
		// redisplay default view
		redirect("/manageentrytypes");
	}
	
	
	/**
	 *  Displays the "create new entry type" form
	 */
	public function displayNewTypeForm() {
		
		// authorize call
		$this->authorize(Permissions::ENTRYTYPES_CREATE_TYPE);

		// prepare view data
		$data["component"] = "components/component_form_entrytype.php";
		$data["component_title"] = "New Entry Type";
		$data["component_mode"] = "new";
		
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
		$this->authorize(Permissions::ENTRYTYPES_EDIT_TYPE);
		
		// get ref to managers
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		
		// query for edit target
		$editTarget = $entryTypeManager->getTypeById($typeId);
		
		// prepare view data
		$data["component"] = "components/component_form_entrytype.php";
		$data["component_title"] = "Edit Entry Type (" . $editTarget->getType() . ")";
		$data["component_mode"] = "edit";

		$data["component_edit_target"] = $editTarget;
		
		// render view
		$this->renderView($data);
	}
	
}