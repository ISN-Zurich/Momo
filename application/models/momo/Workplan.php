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
use momo\core\helpers\DebugHelper;

/**
 * The Momo extension to the Propel generated Workplan class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class Workplan extends BaseWorkplan {
	
	/**
	 * Whether the workplan is "in use"
	 * 
	 * A workplan is defined to be "in use" if there exist associated Entry instances.
	 * 
	 * @return boolean
	 */
	public function isInUse() {
		
		$entryCount = \WorkplanQuery::create()
							->filterById($this->getId())
							->useDayQuery()
								->useEntryQuery()
								->endUse()
							->endUse()
							->count();		
							
		return $entryCount != 0 ? true : false;
	}
	

	/**
	 * Whether the workplan is "active"
	 * 
	 * If the current year lies before the workplan year, the workplan is considered inactive.
	 * Otherwise, the workplan is considered inactive as long as it does not carry regular entry instances.
	 * 
	 * Note: The Timetracker prevents creation of RegularEntry instances for future dates, so the
	 * 		 check for RegularEntry instances is in a way redundant but in the spirit of defensive
	 * 		 programming it is put in place nonetheless. 
	 * 
	 * @return boolean
	 */
	public function isActive() {
		
		$result = false;
					
		if ( DateTimeHelper::getCurrentYear() >= $this->getYear() ) {
			$result = true;
		}
		else {
			
			// if the current year lies still before the workplan's year
			// the active status is determined by possibly existing regular entries
			
			// query for regular entries associated with the workplan
			$regularEntryCount = \WorkplanQuery::create()
									->filterById($this->getId())
									->useDayQuery()
										->useRegularEntryQuery()
										->endUse()
									->endUse()
									->count();
															
			if ( $regularEntryCount != 0 ) {
				$result = true;
			}						
									
		}					
								
		return $result;
	}
	
	
	/**
	 * Informs, whether the workplan has associated RegularEntry
	 * (i.e., Timetracker) instances
	 */
	public function hasRegularEntries() {
		$entryCount = \WorkplanQuery::create()
								->filterById($this->getId())
								->useDayQuery()
									->useRegularEntryQuery()
									->endUse()
								->endUse()
								->count();						
									
		return $entryCount != 0 ? true : false;
	}
	
	
	/**
	 * Informs, whether the workplan has associated OOEntry
	 * (i.e., Out-of-Office) instances
	 */
	public function hasOOEntries() {
		$entryCount = \WorkplanQuery::create()
							->filterById($this->getId())
							->useDayQuery()
								->useOOEntryQuery()
								->endUse()
							->endUse()
							->count();							
									
		return $entryCount != 0 ? true : false;
	}
	
} // Workplan
