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
 * The Momo extension to the Propel generated Tag class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class Tag extends BaseTag {

	//
	// types
	const TYPE_WEEK_INITIALIZED 		= "TYPE_WEEK_INITIALIZED";			// used to mark a week as initialized
	const TYPE_WEEK_COMPLETE 			= "TYPE_WEEK_COMPLETE";				// used to mark a week as complete

	const TYPE_DAY_UNLOCKED 			= "TYPE_DAY_UNLOCKED";				// used to temporarily unlock a day
	
	const TYPE_DAY_DAY_OFF_FULL 		= "TYPE_DAY_DAY_OFF_FULL";			// used to mark a day as a full off day
	const TYPE_DAY_HALF_DAY_OFF_AM		= "TYPE_DAY_HALF_DAY_OFF_AM";		// used to mark a day as a half-day (am) off day
	const TYPE_DAY_HALF_DAY_OFF_PM 		= "TYPE_DAY_HALF_DAY_OFF_PM";		// used to mark a day as a half-day (pm) off day
	
} // Tag
