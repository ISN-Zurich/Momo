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
 * Momo_PropelLoader
 * 
 * Does just what you would expect.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.libraries
 */
class Momo_PropelLoader
{
	public function __construct() {
		
		require APPPATH . "third_party/propel/Propel.php";			
		
		Propel::init(APPPATH . "config/momo-propel-conf.php");
	}	
}