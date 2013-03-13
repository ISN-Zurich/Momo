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

namespace momo\audittrailmanager;

use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;

/**
 * AuditManager
 * 
 * Single access point for all audit trail related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.audittrailmanager
 */
class AuditTrailManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_AUDITTRAIL";
	
	/**
	 * Audits an event for a given user
	 * 
	 * @param 	User				$user
	 * @param 	string				$sourceKey				- the internal key of the event source
	 * @param 	string				$action					- a description of the audited action, e.g. "delete entry"
	 * @param 	EventDescription	$eventDetails			- a digest of event description items detailing the event
	 */
	public function addAuditEvent($user, $sourceKey, $action, $eventDetails) {
		
		try {
			// instantiate new audit event
			$newEvent = new \AuditEvent();
			
			// populate with passed arguments 
			$newEvent->setUser($user);
			$newEvent->setSourceKey($sourceKey);
			$newEvent->setAction($action);
			$newEvent->setDetails((string) $eventDetails);
			$newEvent->setTimestamp(new \DateTime());
			
			// persist
			$newEvent->save();	
					
			// log at level "info"
			log_message('info', "AuditManager:addAuditEvent() - created new audit event for user: " . $user->getLogin());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("AuditManager:addAuditEvent() - a database error has occurred while while attempting to audit an event for user: " . $user->getLogin(), 0, $ex);
		}
		
	}
	
	
	/**
	 * Retrieves all audit events, default order is reverse chronological
	 * 
	 * @param string	$sortOrder		- ["asc", "desc"] the sort order to apply (optional) 
	 */
	public function getAllAuditEvents($sortOrder="desc") {
		return $this->getAuditEvents(null, null, null, null, $sortOrder);
	}
	
	/**
	 * Retrieves the events as indicated by passed parameters
	 * 
	 * @param 	User		$user			- the user for which to retrieve the events
	 * @param 	string		$sourceKey		- the source key for which to display the audit trail
	 * @param 	DateTime	$dateFrom		- the lower date range to consider
	 * @param 	DateTime	$dateUntil 		- the upper date range to consider
	 * @param 	string		$sortOrder		- ["asc", "desc"] the sort order to apply (optional)
	 * 
	 * @return PropelCollection
	 * 
	 * Note: passing "null" for an argument causes that constraint to be ignored.
	 *       this is not true for sortorder which always defaults to "desc"
	 */
	public function getAuditEvents($user, $sourceKey, $dateFrom, $dateUntil, $sortOrder="desc") {
		
		// build query based on passed args
		$query = \AuditEventQuery::create();
		
		if ( $user != null ) {
			$query->filterByUser($user);
		}
		
		if ( $sourceKey != null ) {
			$query->filterBySourceKey($sourceKey);
		}
		
		if ( $dateFrom != null ) {
			$query->filterByTimestamp($dateFrom, \AuditEventQuery::GREATER_EQUAL);
		}
		
		if ( $dateUntil != null ) {
			$query->filterByTimestamp($dateUntil, \AuditEventQuery::LESS_EQUAL);
		}
				
		if ( $sortOrder == "asc") {
			$query->orderByTimestamp(\AuditEventQuery::ASC);
		}
		else {
			$query->orderByTimestamp(\AuditEventQuery::DESC);
		}
		
		// return the audit events
		return $query->find();
	}

}