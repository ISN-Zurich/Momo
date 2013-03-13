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

namespace momo\audittrailmanager;

/**
 * EventDescription
 * 
 * A digest of items describing an audit event
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.audittrailmanager
 */
class EventDescription {
	
	// digest of event description items
	private $eventDescriptionItems = array();

	/**
	 * Adds a description item to the event items digest
	 * 
	 * @param string $itemName		- the name of the description item
	 * @param string $itemValue		- the value of the description item
	 * 
	 */
	public function addDescriptionItem($itemName, $itemValue) {
		
		// add the item information to the description digest
		$this->eventDescriptionItems[] = array(
													"item_name" => $itemName,
													"item_value" => $itemValue
												);
	}
	
	/**
	 * Adds a digest of description items to the event items digest
	 * 
	 * @param Array $itemDigest		- the digest of description items to add
	 */
	public function addDescriptionItemDigest($itemDigest) {
		
		// add the passed description items to the event items
		foreach ($itemDigest as $curItem) {
			$this->eventDescriptionItems[] = array(
													"item_name" => $curItem["item_name"],
													"item_value" => $curItem["item_value"]
												);
		}
	}
	
	/**
	 * Renders the digest as a serialized string
	 */
	public function __toString() {
         return serialize($this->eventDescriptionItems);
    }
	
}