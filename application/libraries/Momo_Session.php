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
 * Momo_Session
 * 
 * An extension of CI_Session, with this class only instantiating if we're not
 * running in CLI mode.
 * 
 * This is necessary, as we want to autoload the class (via 'config/autoload.php').
 * In the presently used CI version (v2.1.3), autoloading the class leads to an exception
 * when running in CLI mode. Future versions might resolve this, in which case the extension
 * will become obsolete.
 * 
 * Note: In the case of a CLI call, the returned sessionm object is "broken" and may
 * 		 not be used in any capacity whatsoever.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.libraries
 */
class Momo_Session extends CI_Session {

	/**
	 * Constructor
	 * 
	 * Will fully instantiate only when not running in CLI context
	 */
	public function __construct()
	{
		// get ci instance
		$ci = get_instance();
		
		// only instantiate parent if this is not a CLI request
		if ( ! $ci->input->is_cli_request() ) {
			parent::__construct();
		}
	}
}