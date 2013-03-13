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

use momo\core\helpers\DateTimeHelper;

/**
 * The Momo extension to the Propel generated RegularEntry class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class ProjectEntry extends BaseProjectEntry {

	/**
	 * Compiles a digest of key/value pairs representing the object's state
	 * 
	 * @return array (
	 * 					{STATE_ITEM_KEY_1} => array(
	 * 												"item_pretty_name"	=> {string},
	 * 												"item_value"		=> {string}
	 * 											)
	 * 
	 * 					...
	 * 
	 * 					{STATE_ITEM_KEY_N} => array(
	 * 												"item_pretty_name"	=> {string},
	 * 												"item_value"		=> {string}
	 * 											)
	 * 				 ) 
	 */
	public function compileStateDigest() {
		
		$digest = array();
		
		$digest["project"] = array();
		$digest["project"]["item_name"] = "Project";
		$digest["project"]["item_value"] = $this->getProject()->getName(); 
		
		$digest["date"] = array();
		$digest["date"]["item_name"] = "Date";
		$digest["date"]["item_value"] = DateTimeHelper::formatDateTimeToPrettyDateFormat($this->getDay()->getDateOfDay()); 
		
		$digest["duration"] = array();
		$digest["duration"]["item_name"] = "Duration";
		$digest["duration"]["item_value"] = sprintf("%.2f h", ( $this->getTimeInterval() / 3600) ); 
		
		return $digest;		
	}
	
} // ProjectEntry
