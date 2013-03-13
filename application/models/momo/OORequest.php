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
 * The Momo extension to the Propel generated OORequest class
 * 
 * @author  Francesco Krattiger
 * @package momo.application.models
 */
class OORequest extends BaseOORequest {

	//
	// define the internal keys for the stati
	const STATUS_OPEN 		= "STATUS_OPEN";
	const STATUS_PENDING 	= "STATUS_PENDING";
	const STATUS_APPROVED 	= "STATUS_APPROVED";
	const STATUS_DENIED 	= "STATUS_DENIED";
	
	//
	// map internal status keys to user friendly descriptions
	public static $STATUS_MAP = array (
		OORequest::STATUS_OPEN 		=> "open",
		OORequest::STATUS_PENDING 	=> "pending",
		OORequest::STATUS_APPROVED 	=> "approved",
		OORequest::STATUS_DENIED 	=> "denied"
	);
	
	
} // OORequest
