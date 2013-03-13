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

/**
 * FormHelper
 * 
 * Various form related support functions/constants
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers
 */
class FormHelper {
	
	//
	// a map of binary options in yes/no format
	public static $binaryOptionsYesNo = array(
		"1" => "yes",
		"0" => "no"
	);
	
	//
	// a map of workday values/options
	public static $workDayOptions = array(
		"1" => "Mon",
		"2" => "Tue",
		"3" => "Wed",
		"4" => "Thu",
		"5" => "Fri"
	);
	
	//
	// a map of report grouping values/options
	public static $reportGroupingOptions = array(
		"day" => "by day",
		"item" => "by item"
	);
	
	
	/**
	 * Encodes an uri segment to make it compatible with CI URI/URL segmentation
	 * 
	 * @param 	string	$uriSegment
	 * 
	 * @return	string	- the encoded segment
	 */
	public static function encodeUriSegment($uriSegment) {
		return urlencode(str_replace("/", "|", $uriSegment));
	}
	
	
	/**
	 * Decodes an uri segment previously encoded with "encodeUriSegment()"
	 * 
	 * @param 	string	$uriSegment
	 * 
	 * @return	string	- the decoded segment
	 */
	public static function decodeUriSegment($uriSegment) {
		return str_replace("|", "/", urldecode($uriSegment));
	}
	
	
}
