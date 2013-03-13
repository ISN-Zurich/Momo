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

namespace momo\bookingtypemanager;

use momo\core\helpers\ManagerHelper;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;
use momo\audittrailmanager\EventDescription;


/**
 * BookingTypeManager
 * 
 * Single access point for all booking type related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.bookingtypemanager
 */
class BookingTypeManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_BOOKING_TYPE";
	
	/**
	 * Returns all booking types
	 * 
	 * @return	PropelCollection
	 */
	public function getAllTypes() {
		return \OOBookingTypeQuery::create()
					->orderByType()
					->find();
	}
	
	/**
	 * Retrieves a booking type by its id
	 * 
	 * @return BookingType (or null)
	 */
	public function getTypeById($typeId) {
		return \OOBookingTypeQuery::create()
					->filterById($typeId)
					->findOne();
	}
	
	
	/**
	 * Returns all active booking types
	 * 
	 * @return PropelCollection
	 */
	public function getAllActiveTypes() {
		$entryTypes = \OOBookingTypeQuery::create()
						->filterByEnabled(true)
						->orderByType()
						->find();
		
		return $entryTypes;				
	}
	
	/**
	 * Returns all user types
	 * 
	 * @return	PropelCollection
	 */
	public function getAllUserTypes() {
		return \OOBookingTypeQuery::create()
					->filterByCreator(\OOBookingType::CREATOR_USER)
					->orderByType()
					->find();
	}

	
	/**
	 *  Creates a new booking type 
	 * 
	 *  @param string			$type							the type's designation
	 *  @param string			$creator						whether the type is user or system generated (see applicable BookingType class constants)
	 *  @param boolean			$bookableInDays					whether the type is bookable in full days (applicable to types with context "out of office")
	 *  @param boolean			$bookableInHalfDays				whether the type is bookable in half days (applicable to types with context "out of office")
	 *  @param boolean			$paid							whether the type is paid
	 *  @param string			$rgbColorValue					the booking's color value (used for oo summary display)
	 *  @param boolean			$enabled
	 *  
	 *  @return OOBookingType	the newly created instance
	 */
	public function createType($type, $creator, $bookableInDays, $bookableInHalfDays, $paid, $rgbColorValue, $enabled) {
 
		try {
			
			// instantiate new type
			$newType = new \OOBookingType();
			
			// populate with passed arguments 
			$newType->setType($type);
			$newType->setCreator($creator);
			$newType->setBookableInDays($bookableInDays);
			$newType->setBookableInHalfDays($bookableInHalfDays);
			$newType->setPaid($paid);
			$newType->setRgbcolorValue($rgbColorValue);
			$newType->setEnabled($enabled);
			
			// persist
			$newType->save();
							
			// log at level "info"
			log_message('info', "BookingTypeManager:createType() - created new booking type: " . $type);
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("BookingTypeManager:createType() - a database error has occurred while while attempting to create the booking type: " . $type, 0, $ex);
		}
		
		return $newType;
	}
	
	
	/**
	 *  Updates a booking type 
	 * 
	 *	@param Type				$updateTarget					the BookingType instance to update
	 *  @param string			$type							the type's designation
	 *  @param string			$creator						whether the type is user or system generated (see applicable BookingType class constants)
	 *  @param boolean			$bookableInDays					whether the type is bookable in full days (applicable to types with context "out of office")
	 *  @param boolean			$bookableInHalfDays				whether the type is bookable in half days (applicable to types with context "out of office")
	 *  @param boolean			$paid							whether the type is paid
	 *  @param string			$rgbColorValue					the booking's color value (used for oo summary display)
	 *  @param boolean			$enabled
	 *  
	 *  @return OOBookingType	the updated instance
	 */
	public function updateType(	$updateTarget, $type, $creator, $bookableInDays, $bookableInHalfDays, $paid, $rgbColorValue, $enabled) {
		
		try {
			// populate the type instance with passed arguments 
			$updateTarget->setType($type);
			$updateTarget->setCreator($creator);
			$updateTarget->setBookableInDays($bookableInDays);
			$updateTarget->setBookableInHalfDays($bookableInHalfDays);
			$updateTarget->setPaid($paid);
			$updateTarget->setRgbcolorValue($rgbColorValue);
			$updateTarget->setEnabled($enabled);
			
			// persist
			$updateTarget->save();
			
			// log at level "info"
			log_message('info', "BookingTypeManager:updateType() - updated booking type with ID: " . $updateTarget->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("BookingTypeManager:updateType() - a database error has occurred while while attempting to update the booking type with ID: " . $updateTarget->getId(), 0, $ex);
		}
		
		return $updateTarget;
	}
	
	
	/**
	 * Deletes the indicated type
	 * 
	 * @param 	Booking		$type	- the booking type to delete
	 */
	public function deleteType($type) {
		 
		try {
			// delete the type
			$type->delete();
			
			// log at level "info"
			log_message('info', "BookingTypeManager:deleteType() - deleted booking type with ID: " . $type->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("BookingTypeManager:deleteType() - a database error has occurred while while attempting to delete the booking type with ID: " . $type->getId(), 0, $ex);
		}	
			
	}	
	
}