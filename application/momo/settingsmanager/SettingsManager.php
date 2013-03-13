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

namespace momo\settingsmanager;

use momo\core\helpers\DebugHelper;

use momo\core\helpers\DateTimeHelper;

use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;

/**
 * SettingsManager
 * 
 * Single access point for all tag related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.settingsmanager
 */
class SettingsManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_SETTINGS";
	
	
	/**
	 * Returns all settings
	 * 
	 * @return	PropelCollection
	 */
	public function getAllSettings() {
		return \SettingQuery::create()->find();				
	}
	
	
	/**
	 * Returns the value of indicated setting
	 * 
	 * @param	string	$settingKey
	 * 
	 * @return	string	the setting's value, or "null", if the key does not exist
	 * 
	 */
	public function getSettingValue($settingKey) {
		
		$result = null;
		
		// query for indicated setting
		$setting = \SettingQuery::create()
						->filterByKey($settingKey)
						->findOne();

		if ( $setting !== null ) {
			$result = $setting->getValue();
		}			
										
		return $result;		
					
	}
	
	
	/**
	 * Returns the indicated setting
	 * 
	 * @param	string	$settingKey
	 * 
	 * @return	Setting (or null, if there is no Setting for the indicated key)
	 * 
	 */
	public function getSetting($settingKey) {		
		// query for indicated setting
		return \SettingQuery::create()
						->filterByKey($settingKey)
						->findOne();
			
	}
	
	
	/**
	 * Updates a batch of settings within a single TX
	 * 
	 * @param	array	$keyValueArray	- an associative array mapping setting keys to values
	 * 
	 * @throws MomoException
	 */
	public function batchUpdateSettings($keyValueArray) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\SettingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			foreach ( $keyValueArray as $curKey => $curValue ) {
				$this->updateSetting($curKey, $curValue);
			}
			
			$con->commit();
			
		}
		catch (\PropelException $ex) {
		    // roll back
			$con->rollback();
			// rethrow
			throw new MomoException("SettingsManager:batchUpdateSettings() - a database error has occurred while while attempting to batch update the setting with key: " . $curKey, 0, $ex);
		}
		
	}
	
	
	/**
	 * Updates a setting for the indicated key/value pair
	 * 
	 * @return	PropelCollection
	 */
	public function updateSetting($settingKey, $settingValue) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\SettingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			// updates are implemented as deletes followed by creates
			$this->deleteSetting($settingKey);
			$this->createSetting($settingKey, $settingValue);
			
			$con->commit();
		}
		catch (\PropelException $ex) {
			// oops, rollback TX
			$con->rollback();
			// roll back	
			throw new MomoException("SettingsManager:updateSetting() - a database error has occurred while while attempting to update the setting with key: " . $settingKey, 0, $ex);
		}

	}
	
	
	/**
	 * Creates a setting for the indicated key/value pair
	 * 
	 * @param	string	$settingKey
	 * @param	string	$settingValue
	 * 
	 * @throws MomoException		- if the indicated key already exists
	 */
	public function createSetting($settingKey, $settingValue) {

		// throw exception if the key already exists
		if ( $this->getSetting($settingKey) !== null ) {
			throw new MomoException("Unable to create setting with key: " . $settingKey . " - the key already exists");
		}
		
		// key does not exist, create new setting
		$newSetting = new \Setting();
		$newSetting->setKey($settingKey);
		$newSetting->setValue($settingValue);
		
		// persist
		$newSetting->save();
	}
	
	
	/**
	 * Deletes the indicated setting
	 * 
	 * @param	string	$settingKey
	 * 
	 */
	public function deleteSetting($settingKey) {

		// try to get a ref to delete target, if the delete target does
		// not exist we simply ignore the operation
		if ( ($deleteTarget = $this->getSetting($settingKey)) !== null ) {
			// delete the instance
			$deleteTarget->delete();
		}
	}
		
}