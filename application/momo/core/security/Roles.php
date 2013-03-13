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

namespace momo\core\security;

/**
 * Roles
 * 
 * Enumerates the application roles
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.security
 */
class Roles {

	const ROLE_USER 			= 'ROLE_USER';
	const ROLE_TEAMLEADER 		= 'ROLE_TEAMLEADER';
	const ROLE_MANAGER 			= 'ROLE_MANAGER';
	const ROLE_ADMINISTRATOR 	= 'ROLE_ADMINISTRATOR';
	
	//
	// map internal role keys to user friendly descriptions
	public static $ROLE_MAP = array(
		Roles::ROLE_USER			=>	"User",	
		Roles::ROLE_TEAMLEADER		=>	"Teamleader",
		Roles::ROLE_MANAGER			=>	"Manager",
		Roles::ROLE_ADMINISTRATOR	=>	"Administrator"	
	);
	
}