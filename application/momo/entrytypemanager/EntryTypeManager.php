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

namespace momo\entrytypemanager;

use momo\core\helpers\DebugHelper;

use momo\core\helpers\ManagerHelper;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;
use momo\audittrailmanager\EventDescription;

/**
 * EntryTypeManager
 * 
 * Single access point for all entry type related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.entrytypemanager
 */
class EntryTypeManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_ENTRY_TYPE";
	
	/**
	 * Returns all types
	 * 
	 * @return	PropelCollection
	 */
	public function getAllTypes() {
		return \RegularEntryTypeQuery::create()
					->orderByType()
					->find();
	}
	
	/**
	 * Retrieves a type by its id
	 * 
	 * @return Type (or null)
	 * 
	 * TODO throw exception, if type not found
	 */
	public function getTypeById($typeId) {
		return \RegularEntryTypeQuery::create()
					->filterById($typeId)
					->findOne();
	}
	
	
	/**
	 * Returns all active Timetracker entry types
	 * 
	 * @return PropelCollection		- the timetracker entry types
	 */
	public function getAllActiveTypes() {
		$entryTypes = \RegularEntryTypeQuery::create()
						->filterByEnabled(true)
						->orderByDefaultType("desc")
						->orderByType("asc")
						->find();
						
		return $entryTypes;				
	}
	
	/**
	 * Returns all user types
	 * 
	 * @return	PropelCollection
	 */
	public function getAllUserTypes() {
		return \RegularEntryTypeQuery::create()
					->filterByCreator(\RegularEntryType::CREATOR_USER)
					->orderByType()
					->find();
	}
	
	
	/**
	 *  Creates a new entry type 
	 * 
	 *  @param string			$type						the type's designation
	 *  @param string			$creator					whether the type is user or system generated (see applicable EntryType class constants)
	 *  @param boolean			$workTimeCreditAwarded		whether the type awards work-time credit
	 *  @param boolean			$enabled
	 *  @param boolean			$defaultType				set to "true" to make entry type the default type (system setup use only, defaults to "false")
	 *  
	 *  @return	RegularEntryType
	 */
	public function createType($type, $creator, $workTimeCreditAwarded, $enabled, $defaultType=false) {

		try {
			// instantiate new type
			$newType = new \RegularEntryType();
			
			// populate with passed arguments 
			$newType->setType($type);
			$newType->setCreator($creator);
			$newType->setWorktimeCreditAwarded($workTimeCreditAwarded);
			$newType->setEnabled($enabled);
			$newType->setDefaulttype($defaultType);
			
			// persist
			$newType->save();
			
			// log at level "info"
			log_message('info', "EntryTypeManager:createType() - created new entry type: " . $type);
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryTypeManager:createType() - a database error has occurred while while attempting to create the regular entry type: " . $type, 0, $ex);
		}
		
		return $newType;
	}
	
	
	/**
	 *  Updates an entry type 
	 * 
	 *  @param Type				$updateTarget				the Type instance to update
	 *  @param string			$type						the type's designation
	 *  @param string			$creator					whether the type is user or system generated (see applicable EntryType class constants)
	 *  @param boolean			$workTimeCreditAwarded		whether the type awards work-time credit
	 *  @param boolean			$enabled
	 *  
	 *  @return RegularEntryType
	 */
	public function updateType($updateTarget, $type, $creator, $workTimeCreditAwarded, $enabled) {

		try {
			// populate the type instance with passed arguments 
			$updateTarget->setType($type);
			$updateTarget->setCreator($creator);
			$updateTarget->setWorktimeCreditAwarded($workTimeCreditAwarded);
			$updateTarget->setEnabled($enabled);
			
			// persist
			$updateTarget->save();
			
			// log at level "info"
			log_message('info', "EntryTypeManager:createType() - update the entry type with id: " . $updateTarget->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryTypeManager:updateType() - a database error has occurred while while attempting to update the entry type with ID: " . $updateTarget->getId(), 0, $ex);
		}
		
		return $updateTarget;
	}
	
	
	/**
	 * Deletes the indicated type
	 * 
	 * @param 	EntryType		$type	- the entry type to delete
	 */
	public function deleteType($type) {
 
		try {	
			// delete the type
			$type->delete();
			
			// log at level "info"
			log_message('info', "EntryTypeManager:deleteType() - deleted the entry type with id: " . $type->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryTypeManager:deleteType() - a database error has occurred while while attempting to delete the entry type with ID: " . $type->getId(), 0, $ex);
		}		
	}	
	
}