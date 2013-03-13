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
 * CIRuntimeParamSetter
 * 
 * Sets CI runtime parameters.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.hooks 
 */
class CIRuntimeParamSetter
{
	
	/**
	 * Sets CI runtime params (needs to run on "pre_system")
	 */
	public function set() {	
		
		// instantiate config class and load Momo application config
		$config = new CI_Config();
		$config->load("momo-application-conf");
		
		//
		// the code section below is lifted from CI index.php
		// we redefine the CI environment settings in accordance to the retrieved value from Momo application config
		define('ENVIRONMENT', $config->item("environment", "momo"));

		switch (ENVIRONMENT)
		{
			case 'development':
				error_reporting(E_ALL);
			break;
		
			case 'testing':
			case 'production':
				error_reporting(0);
			break;
	
			default:
				exit('The application environment is not set correctly.');
		}
				
	}
}