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

use momo\core\helpers\FormHelper;
use momo\core\security\Permissions;

/**
 * ManageSettingsController
 * 
 * Exposes the client actions needed to work with the application settings.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageSettingsController extends Momo_Controller {
	
	/**
	 *  Default action displays the settings form
	 */
	public function index() {
		$this->displaySettings();
	}
	
	
	/**
	 *  Updates the settings
	 */
	public function updateSettings() {
		
		// authorize call
		$this->authorize(Permissions::SETTINGS_UPDATE_SETTINGS);
		
		// get ref to managers
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// compile passed post fields to suitable array for batch update
		$keyValuePairs = array();
		foreach ( $this->input->post() as $curFieldName => $curFieldValue ) {
		
			// if the field name terminates in "_Normalized" we need to truncate that extension
			// before adding the pair to the array
			$curKey = $curFieldName;
			if ( substr($curFieldName, -strlen("_Normalized")) == "_Normalized" ) {
				$curKey = substr($curFieldName, 0, -strlen("_Normalized"));
			}
			
			$keyValuePairs[$curKey] = $curFieldValue;
		} 
		
		// batch update the passed settings
		$settingsManager->batchUpdateSettings($keyValuePairs);
		
		$data["component"] = "components/component_other_notification.php";
		$data["component_title"] = "Change Settings";
		$data["component_message"] = "The settings have been successfully updated.</p>";
		$data["component_mode"] = "info";
		$data["component_target"] = FormHelper::decodeUriSegment("/");		
		
		// render view
		$this->renderView($data);
		
	}
	
	
	/**
	 *  Displays the "edit settings form" form
	 */
	public function displaySettings() {
		
		// authorize call
		$this->authorize(Permissions::SETTINGS_UPDATE_SETTINGS);
		
		// get ref to managers
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// query for the settingss
		$settings = $settingsManager->getAllSettings();
			
		// prepare view data
		$data["component"] = "components/component_form_settings.php";
		$data["component_title"] = "Change Settings";

		// the settings are passed as a key/value map as the view will look up values by key
		$data["component_settings_key_value_map"] = $settings->toKeyValue("key", "value");
		
		// render view
		$this->renderView($data);
	}
	
}