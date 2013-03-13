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

namespace momo\oomanager\exceptions;

use momo\core\exceptions\MomoException;

/**
 * InvalidStateException
 * 
 * Single access point for all out-of-office related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.oomanager
 */
class InvalidStateException extends MomoException
{  
   	public function __construct($message = null, $code = 0, Exception $previous = null) {
   		parent::__construct($message, $code, $previous);
   	}  	
}