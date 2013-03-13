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
 * The Momo extension to the Propel generated OOEntry class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class OOEntry extends BaseOOEntry {

	//
	// entry type constants 
	const TYPE_ALLOTMENT_FULL_DAY 			= "TYPE_ALLOTMENT_FULL_DAY";			// used to identify a full day allotment resulting from an oo booking 
	const TYPE_ALLOTMENT_HALF_DAY_AM 		= "TYPE_ALLOTMENT_HALF_DAY_AM";			// used to identify an am half-day allotment resulting from an oo booking 
	const TYPE_ALLOTMENT_HALF_DAY_PM 		= "TYPE_ALLOTMENT_HALF_DAY_PM";			// used to identify a pm half-day allotment resulting from an oo booking
	
	const TYPE_MARKER_FULL_DAY		 	= "TYPE_MARKER_FULL_DAY";			// marks a full day entry applicable to an oo booking
	const TYPE_MARKER_HALF_DAY_AM	 	= "TYPE_MARKER_HALF_DAY_AM";		// marks an am half-day applicable to an oo booking (i.e. an am half-day configured in the booking/request form)
	const TYPE_MARKER_HALF_DAY_PM	 	= "TYPE_MARKER_HALF_DAY_PM";		// marks a pm half-day applicable to an oo booking (i.e. a pm half-day configured in the booking/request form)

	
	//
	// map internal "types" keys to user friendly descriptions of the period they concern
	public static $PERIOD_MAP = array(
		OOEntry::TYPE_ALLOTMENT_FULL_DAY 		=> "full day",
		OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM 	=> "morning",
		OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM 	=> "afternoon",
		OOEntry::TYPE_MARKER_FULL_DAY 			=> "full day",
		OOEntry::TYPE_MARKER_HALF_DAY_AM 		=> "morning",
		OOEntry::TYPE_MARKER_HALF_DAY_PM 		=> "afternoon"
	);
	
	
	/**
	 * Returns a pretty name for the period that the entry applies to
	 * 
	 * @return string
	 */
	public function getPrettyPeriodIndicator() {
		return OOEntry::$PERIOD_MAP[$this->getType()];
	}
	
	
} // OOEntry
