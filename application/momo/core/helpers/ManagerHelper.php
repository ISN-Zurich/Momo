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

namespace momo\core\helpers;

use momo\teammanager\TeamManager;
use momo\entrymanager\EntryManager;
use momo\usermanager\UserManager;
use momo\entrytypemanager\EntryTypeManager;
use momo\bookingtypemanager\BookingTypeManager;
use momo\projectmanager\ProjectManager;
use momo\workplanmanager\WorkplanManager;
use momo\oomanager\OOManager;

/**
 * ManagerHelper
 * 
 * Not really a helper per-se, rather a digest of available managers plus
 * a digest of audited managers.
 * 
 * TODO: this information needs to move to application config.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers
 */
class ManagerHelper {
	
	//
	// a map of internal manager values/options for managers subject to the audit trail
	public static $MANAGERS_AUDITED_MAP = array(
		EntryTypeManager::MANAGER_KEY	=> "Entry Type Manager",
		BookingTypeManager::MANAGER_KEY	=> "Booking Type Manager",
		UserManager::MANAGER_KEY		=> "User Manager",
		EntryManager::MANAGER_KEY		=> "Entry Manager",
		TeamManager::MANAGER_KEY		=> "Team Manager",
		ProjectManager::MANAGER_KEY		=> "Project Manager",
		OOManager::MANAGER_KEY			=> "Out-of-Office Manager"
	);
	
}