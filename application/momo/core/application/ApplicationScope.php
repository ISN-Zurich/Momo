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

namespace momo\core\application;

use momo\core\exceptions\MomoException;

/**
 * ApplicationScope
 * 
 * Provides a key/value store for state variables with application scope.
 * The data points are persisted to database.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.core.application
 */
class ApplicationScope {
	
	/**
	 * Returns the value for the indicated key
	 * 
	 * @param	string	$key
	 * 
	 * @return	mixed	the value corresponding to the key, or null if the key does not exist
	 * 
	 */
	public function getValue($key) {
		
		$value = null;
		
		// query for indicated key
		$scopeValue = \ApplicationScopeValueQuery::create()
												->filterByKey($key)
												->findOne();
		if ( $scopeValue !== null ) {
			$value = unserialize($scopeValue->getValue());
		}			
										
		return $value;					
	}
	
	
	/**
	 * Sets the value for the indicated key
	 * 
	 * @param	string	$key
	 * @param	mixed	$value
	 * 
	 * @throws MomoException
	 */
	public function setValue($key, $value) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\ApplicationScopeValuePeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// query for the indicated key
			$scopeValue = \ApplicationScopeValueQuery::create()
										->filterByKey($key)
										->findOne();
						
			if ( $scopeValue === null ) {
				// key does not exist, so we need to create new instance
				$scopeValue = new \ApplicationScopeValue();
			}
			
			// set the value and key
			$scopeValue->setValue(serialize($value));
			$scopeValue->setKey($key);
			
			// persist
			$scopeValue->save();
			
			// commit TX
			$con->commit();
			
		}
		catch (\PropelException $ex) {
		    //
			// oops, rollback TX
			$con->rollback();
			
			// write error to log and rethrow
			$errorMsg = "ApplicationScope:setValue() - a database error has occurred while while attempting to set the key/value pair: " . $key . "/" . $value;
			$errorMsg .= " - (" . $ex->getMessage() . ")";
			
			log_message('error', $errorMsg);
			
			throw new MomoException($errorMsg);
		}
							
	}
	
}