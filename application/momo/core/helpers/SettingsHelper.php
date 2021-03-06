<?php

/** 
 * Copyright 2013, ETH Z�rich
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

namespace momo\core\helpers;

/**
 * SettingsHelper
 * 
 * Settings related helper functions
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers
 */
class SettingsHelper {
	
	/**
	 * Retrieves the value for a given settings key
	 * 
	 * @param 	string	$key
	 * 
	 * @return	string
	 * 
	 * TODO throw exception if invalid key
	 */
	public static function getKeyValue($key) {
		
		$setting = \SettingQuery::create()
						->filterByKey($key)
						->findOne();
						
		return $setting->getValue();				
	}
	
}
