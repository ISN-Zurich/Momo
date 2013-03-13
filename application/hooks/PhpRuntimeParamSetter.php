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
 * PhpRuntimeParamSetter
 * 
 * Sets PHP runtime parameters.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.hooks
 */
class PhpRuntimeParamSetter
{
	public function set() {	
		
		// instantiate config class
		$config = new CI_Config();
		$config->load("momo-application-conf");
		date_default_timezone_set($config->item("php_server_timezone", "momo"));
		
		// add pear libs to include path	
		set_include_path(get_include_path() . PATH_SEPARATOR . FCPATH . APPPATH . "third_party/pear");
		
	}
}