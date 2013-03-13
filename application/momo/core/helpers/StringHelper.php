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
 * StringHelper
 * 
 * Various string related support functions
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers
 */
class StringHelper {
	
	/**
	 * Ensures that the passed string is limited to the indicated length.
	 * 
	 * If the string exceeds the indicated length limit, the string is truncated on the
	 * nearest word boundary *before* the limit is reached and fitted with ellipsis.
	 * If the string is within the indicated length limit, it is returned unchanged.
	 * 
	 * @param 	string	$targetString
	 * @param 	integer	$lengthLimit
	 * 
	 * @return	string
	 */
	public static function limitString($targetString, $lengthLimit) {
	
		$result = $targetString;
	
		// truncate string if it exceeds limit
		if ( strlen($targetString) > $lengthLimit ) {
			
			// truncate the string
			$result = substr($targetString, 0, $lengthLimit);
			
			// try to improve result, by ending at nearest word boundary 
			$lastWordBoundaryPos = strrpos($result, " ");
			
			// if there is a word boundary truncate whatever runs past that boundary
			if ( $lastWordBoundaryPos !== false ) {
				// trim to word boundary
				$result = substr($targetString, 0, $lastWordBoundaryPos);
				
				// if trim ends with an undesired character, remove that as well
				$undesiredCharRegex = "/[,:-;.]$/";
				if ( preg_match($undesiredCharRegex, $result) ) {
					$result = substr($result, 0, strlen($result) - 1);
				}
				
			}
			
			// add ellipsis
			$result .= "...";
			
		}
		
		return $result;
	
	}
	
	
	/**
	 * Fits a given string with the possessive apostrophe
	 * 
	 * @param 	string	$targetString
	 * 
	 * @return	string
	 */
	public static function possessivizeString($targetString) {
		
		// fit possessive apostrophe in accordance with "s" rule
		if ( strtolower(substr($targetString, -1) != "s" ) ) {
			
			$result = $targetString . "'s";
		}
		else {
			$result = $targetString . "'";
		}
		
		return $result;
	
	}
	
}
