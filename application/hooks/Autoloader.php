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
 * Autoloader
 * 
 * Loads Momo application classes.
 * These are all classes residing in the directory 'application/momo'.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.hooks
 */
class Autoloader
{
	public function initautoloader() {
		spl_autoload_register(array($this, 'loader'));
	}

	private function loader($className) {
	
		// only load classes in the namespace "momo"
		if ( stripos($className, 'momo\\') !== FALSE ) {
			$class = APPPATH . str_replace('\\', '/', $className) . '.php'; 		
        	@include_once($class);
		}
	}
	
}