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

namespace momo\entrymanager;


use momo\core\helpers\DebugHelper;
use momo\audittrailmanager\EventDescription;
use momo\core\helpers\DateTimeHelper;
use momo\core\managers\BaseManager;
use momo\core\exceptions\MomoException;
use momo\workplanmanager\exceptions\DayDoesNotExistException;

/**
 * EntryManager
 * 
 * Single access point for all entry related business logic
 *
 * @author  Francesco Krattiger
 * @package momo.application.managers.entrymanager
 */
class EntryManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_ENTRY";

	
	/**
	 * Retrieves a given Entry instance by id.
	 * 
	 * @param 	integer $entryId		
	 * 
	 * @return	Entry	(or null)
	 */
	public function getEntryById($entryId) {
		return	\EntryQuery::create()
						->filterById($entryId)
						->findOne();
	}
	
	
	/**
	 * Retrieves a user's RegularEntry instances for a given date.
	 * 
	 * Note: results are ordered ("asc") by the entries' start time (field "from")
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$date
	 * 
	 * @return	PropelCollection	
	 */
	public function getRegularEntriesForDateAndUser($user, $date) {
		return	\RegularEntryQuery::create()
						->useEntryQuery()
							->filterByUser($user)
						->endUse()
						->useDayQuery()
							->filterByDateOfDay($date)
						->endUse()
						->orderByFrom("asc")
						->find();
	}
	
	
	/**
	 * Retrieves a user's RegularEntry instances contained in a given date range.
	 * 
	 * Note: results are ordered ("asc") by the entries start time' (field "from")
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$startDate		- the start date of the range to consider (inclusive)
	 * @param 	DateTime	$endDate		- the end date of the range to consider (inclusive)
	 * 
	 * @return	PropelCollection	
	 */
	public function getRegularEntriesForDateRangeAndUser($user, $startDate, $endDate) {
		return	\RegularEntryQuery::create()
						->useEntryQuery()
							->filterByUser($user)
						->endUse()
						->useDayQuery()
							->filterByDateOfDay($startDate, \RegularEntryQuery::GREATER_EQUAL)
							->filterByDateOfDay($endDate, \RegularEntryQuery::LESS_EQUAL)
						->endUse()
						->orderByFrom("asc")
						->find();
	}
	
	
	/**
	 * Retrieves a user's the ProjectEntry instances for a given date.
	 * 
	 * Note: results are ordered ("asc") by the entry id (i.e., they reflect the order they were entered into the system)
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$date
	 * 
	 * @return	PropelCollection	
	 */
	public function getProjectEntriesForDateAndUser($user, $date) {
		return	\ProjectEntryQuery::create()
						->useEntryQuery()
							->filterByUser($user)
						->endUse()
						->useDayQuery()
							->filterByDateOfDay($date)
						->endUse()
						->orderById("asc")
						->find();
	}
	
	
	
	/**
	 * Retrieves a user's allotment OOEntry instance for a given date.
	 *
	 * Note: for request based bookings, we only return allotments for which the request has status "approved" 
	 *
	 * @param 	User 		$user		
	 * @param 	DateTime	$date
	 * 
	 * @return	OOEntry (or null, if there is no allotment OOEntry assigned to the indicated date)	
	 */
	public function getAllotmentOOEntryForDateAndUser($user, $date) {
		return \OOEntryQuery::create()
						->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
						->_or()
						->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
						->_or()
						->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
						->useEntryQuery()
							->filterByUser($user)
						->endUse()
						->useDayQuery()
							->filterByDateOfDay($date)
						->endUse()
						->useOOBookingQuery()
							->useOORequestQuery("request", \OOEntryQuery::LEFT_JOIN)
								->filterByStatus(\OORequest::STATUS_APPROVED)
								->_or()
								->filterByStatus(null)
							->endUse()
						->endUse()		
						->findOne();						
	}
	
	
	/**
	 * Retrieves a user's marker OOEntry instance for a given date.
	 *
	 * @param 	User 		$user		
	 * @param 	DateTime	$date
	 * 
	 * @return	OOEntry (or null, if there is no OOEntry assigned to the indicated date)	
	 */
	public function getMarkerOOEntryForDateAndUser($user, $date) {
		return \OOEntryQuery::create()
						->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
						->_or()
						->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
						->_or()
						->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
						->useEntryQuery()
							->filterByUser($user)
						->endUse()
						->useDayQuery()
							->filterByDateOfDay($date)
						->endUse()
						->findOne();					
	}
	
	
	/**
	 * Retrieves a user's OOEntry instances contained in a given date range.
	 * 
	 * Note: results are ordered ("asc") by the entries start time' (field "from")
	 * 
	 * @param 	User 		$user		
	 * @param 	DateTime	$startDate		- the start date of the range to consider (inclusive)
	 * @param 	DateTime	$endDate		- the end date of the range to consider (inclusive)
	 * 
	 * @return	PropelCollection	
	 */
	public function getOOEntriesForDateRangeAndUser($user, $startDate, $endDate) {
		return	\OOEntryQuery::create()
						->filterByUser($user)
						->useDayQuery()
							->filterByDateOfDay($startDate, \OOEntryQuery::GREATER_EQUAL)
							->filterByDateOfDay($endDate, \OOEntryQuery::LESS_EQUAL)
						->endUse()
						->orderById("asc")
						->find();
	}
	
	
	/**
	 * Retrieves the adjustment entries as indicated by the parametrization
	 * 
	 * @param 	User		$user						- the user for which to retrieve the events
	 * @param 	string		$adjustmentEntryType		- the type of adjustment entry to return
	 * @param 	DateTime	$dateFrom					- the lower date range to consider (inclusive)
	 * @param 	DateTime	$dateUntil 					- the upper date range to consider (inclusive)
	 * @param 	string		$sortOrder					- ["asc", "desc"] the sort order to apply (optional)
	 * 
	 * Note: passing "null" for an argument causes that constraint to be ignored.
	 */
	public function getAdjustmentEntries($user, $adjustmentEntryType, $dateFrom, $dateUntil, $sortOrder="desc") {
		
		// build query based on passed args
		$query = \AdjustmentEntryQuery::create();
		
		// restrict by user
		if ( $user != null ) {
			$query->filterByUser($user);
		}
		
		// restrict by type
		if ( $adjustmentEntryType != null ) {
			$query->filterByType($adjustmentEntryType);
		}
		
		// restrict by from date
		if ( $dateFrom != null ) {
			$query->useDayQuery()
				  	->filterByDateOfDay($dateFrom, \AdjustmentEntryQuery::GREATER_EQUAL)
				  ->endUse();
		}
		
		// restrict by until date
		if ( $dateUntil != null ) {
			$query->useDayQuery()
				  	->filterByDateOfDay($dateUntil, \AdjustmentEntryQuery::LESS_EQUAL)
				  ->endUse();
		}
				
		// sort by date of day that the entry applies to
		$query->useDayQuery()
				->orderByDateOfDay($sortOrder)
			  ->endUse();

			  
		return $query->find();
							
	}
	
	
	
	/**
	 * Creates a "RegularEntry"
	 * 
	 * @param 		User			$user					- the user that owns the entry
	 * @param 		int				$entryTypeId			- the id of the entry type that applies to this entry
	 * @param 		DateTime		$entryDate				- the date for which the entry is to be made
	 * @param 		DateTime		$fromTime				- the "from" time of the entry
	 * @param 		DateTime		$untilTime				- the "until" time of the entry
	 * @param 		string			$comment				- the entry's comment
	 * 
	 * @return	RegularEntry
	 * 
	 * @throws MomoException	
	 */
	public function createRegularEntry($user, $entryTypeId, $entryDate, $fromTime, $untilTime, $comment=null) {
		
		try {	
			// get ref to managers needed
			$workplanManager = $this->getCtx()->getWorkplanManager();
			
			// the current date
			$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
			
			// create entry object and persist
			$regularEntry = new \RegularEntry();
			
			$regularEntry->setUser($user);
			$regularEntry->setRegularEntryTypeId($entryTypeId);
			$regularEntry->setFrom($fromTime);
			$regularEntry->setUntil($untilTime);
			$regularEntry->setComment($comment);
			$regularEntry->setDay($workplanManager->getDayForDate($entryDate));
			$regularEntry->setTimeinterval($untilTime->getTimestamp() - $fromTime->getTimestamp());
			
			// persist
			$regularEntry->save();
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:createRegularEntry() - a database error has occurred while while attempting to create a regular entry for user with login: " . $user->getLogin(), 0, $ex);
		}

		return $regularEntry;
	}	
	
	
	/**
	 * Creates a "ProjectEntry"
	 * 
	 * @param 		User			$user					- the user that owns the entry$
	 * @param 		DateTime		$entryDate				- the date for which the entry is to be made
	 * @param 		Project			$project				- the project that this entry applies to
	 * @param 		float			$hours					- the number of hours to be recorded with this entry
	 * 
	 * @return	ProjectEntry	
	 * 
	 * @throws MomoException
	 * 
	 */
	public function createProjectEntry($user, $entryDate, $project, $hours) {
	
		try {
			
			// get ref to managers needed
			$workplanManager = $this->getCtx()->getWorkplanManager();
			//$projectManager = $this->getCtx()->getProjectManager();
			
			// get the projects accessible 
			
								
			// create entry object and persist
			$projectEntry = new \ProjectEntry();
			
			$projectEntry->setUser($user);
			$projectEntry->setDay($workplanManager->getDayForDate($entryDate));
			$projectEntry->setProject($project);
			$projectEntry->setTimeInterval(3600 * $hours);
			
			// the team reference is set only if the referenced project is assigned to the user's primary team
			if ( $user->getPrimaryTeam() !== null ) {
				
				if ( $user->getPrimaryTeam()->getProjects()->contains($project) ) {
					$projectEntry->setTeam($user->getPrimaryTeam());
				}
				
			}
			
			// persist
			$projectEntry->save();
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:createProjectEntry() - a database error has occurred while while attempting to create a project entry for user with login: " . $user->getLogin(), 0, $ex);
		}

		return $projectEntry;
			
	}	
	
	
	/**
	 * Creates an "OOEntry"
	 * 
	 * @param 		User			$user					- the user that owns the entry
	 * @param 		DateTime		$entryDate				- the date for which the entry is to be made
	 * @param 		OOBooking		$booking				- the booking that this entry applies to
	 * @param 		string			$type					- the entry's type (i.e, full-day, am half-day or pm half-day -- see class OOEntry)
	 * @param 		boolean			$timetrackingRelevant	- true, if the entry is relevant from a timetracking perspective. false otherwise
	 * @param 		boolean			$persist				- set to false, if the caller will persist the newly created entry
	 * 
	 * @return	OOEntry	
	 * 
	 * @throws MomoException
	 */
	public function createOOEntry($user, $entryDate, $booking, $type, $persist=true) {
	
		try {
			// get ref to managers needed
			$workplanManager = $this->getCtx()->getWorkplanManager();
								
			// create new oo entry instance
			$newOOEntry = new \OOEntry();
			$newOOEntry->setDay($workplanManager->getDayForDate($entryDate));
			$newOOEntry->setUser($user);
			$newOOEntry->setOOBooking($booking);
			$newOOEntry->setType($type);
		
			// persist according to call mode
			if ( $persist ) {
				// persist
				$newOOEntry->save();	
			}
				
			// log at level "info"
			log_message('info', "EntryManager:createOOEntry() - created oo entry for user: " . $user->getLogin());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:createOOEntry() - a database error has occurred while while attempting to create an oo entry for user with login: " . $user->getLogin(), 0, $ex);
		}

		return $newOOEntry;
			
	}	
	
	
	/**
	 * Creates an "AdjustmentEntry"
	 * 
	 * @param	DateTime	$date						- the date the adjustment applies to
	 * @param	User		$user						- the user that this adjustment entry pertains to
	 * @param	string		$creator					- an identifier that indicates where the entry originated (see class AdjustmentEntry)
	 * @param	string		$adjustmentEntryType		- the type of the entry (see class AdjustmentEntry)
	 * @param	float		$value						- the value of the adjustment entry
	 * @param	string		$reason						- a textual description of the reason for the adjustment
	 * 
	 * @return	AdjustmentEntry
	 * 
	 * @throws MomoException	
	 */
	public function createAdjustmentEntry($date, $user, $adjustmentEntryType, $creator, $value, $reason) {
		
		try {
			// get managers
			$workplanManager = $this->getCtx()->getWorkplanManager();
								
			// create entry object and persist
			$adjustmentEntry = new \AdjustmentEntry();
			
			$adjustmentEntry->setUser($user);
			$adjustmentEntry->setDay($workplanManager->getDayForDate($date));
			$adjustmentEntry->setType($adjustmentEntryType);
			$adjustmentEntry->setCreator($creator);
			$adjustmentEntry->setValue($value);
			$adjustmentEntry->setReason($reason);
			
			// persist
			$adjustmentEntry->save();
			
			// log at level "info"
			log_message('info', "EntryManager:createAdjustmentEntry() - created adjustment entry for user: " . $user->getLogin());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:createAdjustmentEntry() - a database error has occurred while while attempting to create an adjustment entry for user with login: " . $user->getLogin(), 0, $ex);
		}
			
		return $adjustmentEntry;
	}	
	
	
	/**
	 * Updates a "RegularEntry"
	 * 
	 * @param 	$entryId		int				- the id of the entry to update
	 * @param 	$entryTypeId	int				- the id of the entry type that applies to this entry
	 * @param 	$fromTime		DateTime		- the "from" time of the entry
	 * @param 	$untilTime		DateTime		- the "until" time of the entry
	 * @param 	$comment		string			- the entry's comment
	 * 
	 * @return					RegularEntry	- the updated regular entry	
	 * 
	 * @throws MomoException
	 */
	public function updateRegularEntry($entryId, $entryTypeId, $fromTime, $untilTime, $comment=null) {
		
		try {
			// get a reference to the entry that is to be updated
			$updateTarget = \RegularEntryQuery::create()
												->filterById($entryId)
												->findOne();
				
			$updateTarget->setRegularEntryTypeId($entryTypeId);
			$updateTarget->setFrom($fromTime);
			$updateTarget->setUntil($untilTime);
			$updateTarget->setComment($comment);
			$updateTarget->setTimeinterval($untilTime->getTimestamp() - $fromTime->getTimestamp());
			
			// persist
			$updateTarget->save();
			
			// log at level "info"
			log_message('info', "EntryManager:updateRegularEntry() - updated regular entry with ID: " . $entryId);
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:updateRegularEntry() - a database error has occurred while while attempting to update the regular entry with ID: " . $entryId, 0, $ex);
		}	
		
		return $updateTarget;
	}
	
	
	/**
	 * Updates a "ProjectEntry"
	 * 
	 * @param 		int				$entryId				- the id of the entry to update
	 * @param 		Project			$project				- the project that this entry applies to
	 * @param 		float			$hours					- the number of hours to be recorded with this entry
	 * 
	 * @return	ProjectEntry	- the updated project entry	
	 * 
	 * @throws MomoException
	 */
	public function updateProjectEntry($entryId, $project, $hours) {
	
		try {			
			// get a reference to the entry that is to be updated
			$updateTarget = \ProjectEntryQuery::create()
										->filterById($entryId)
										->findOne();
			
			// populate with new values 
			$updateTarget->setProject($project);
			$updateTarget->setTimeInterval(3600 * $hours);						
			
			// persist
			$updateTarget->save();
			
			// log at level "info"
			log_message('info', "EntryManager:updateProjectEntry() - updated project entry with ID: " . $entryId);	
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:updateProjectEntry() - a database error has occurred while while attempting to update the project entry with ID: " . $entryId, 0, $ex);
		}	
		
		return $updateTarget;
	}
	
	
	/**
	 * Updates an "AdjustmentEntry"
	 * 
	 * @param 	int			$entryId					- the id of the entry to update
	 * @param	DateTime	$date						- the date the adjustment applies to
	 * @param	User		$user						- the user that this adjustment entry pertains to
	 * @param	string		$creator					- an identifier that indicates where the entry originated (see class AdjustmentEntry)
	 * @param	string		$adjustmentEntryType		- the type of the entry (see class AdjustmentEntry)
	 * @param	float		$value						- the value of the adjustment entry
	 * @param	string		$reason						- a textual description of the reason for the adjustment
	 * 
	 * @return	AdjustmentEntry		- the updated adjustment entry	
	 * 
	 * @throws MomoException
	 */
	public function updateAdjustmentEntry($entryId, $date, $adjustmentEntryType, $value, $reason) {
	
		try {
			// get ref to managers needed
			$workplanManager = $this->getCtx()->getWorkplanManager();
			
			// get a reference to the entry that is to be updated
			$updateTarget = $this->getEntryById($entryId)->getChildObject();
			
			// set new values on update target
			$updateTarget->setType($adjustmentEntryType);
			$updateTarget->setValue($value);
			$updateTarget->setDay($workplanManager->getDayForDate($date));		
			$updateTarget->setReason($reason);						
			
			// persist
			$updateTarget->save();
			
			// log at level "info"
			log_message('info', "EntryManager:updateAdjustmentEntry() - updated adjustment entry with ID: " . $entryId);	
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:updateAdjustmentEntry() - a database error has occurred while while attempting to update the adjustment entry with ID: " . $entryId, 0, $ex);
		}	
		
		return $updateTarget;
	}
	
	
	/**
	 * Deletes the indicated "Entry"
	 * 
	 * @param 	int		entryId			- the id of the entry to delete
	 * 
	 * @return	Entry					- the deleted entry
	 * 
	 * @throws MomoException
	 */
	public function deleteEntryById($entryId) {
			
		try {
	
			// delete the indicated entry instance
			$entry = \EntryQuery::create()
						->filterById($entryId)
						->findOne();			
			
			$entry->delete();
			
			// log at level "info"
			log_message('info', "EntryManager:deleteEntryById() - deleted entry with ID: " . $entryId);
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("EntryManager:deleteEntry() - a database error has occurred while while attempting to delete the entry with ID: " . $entryId, 0, $ex);
		}		
		
		return $entry;
	}
	
	
	/**
	 * TODO
	 * method below needs a more suitable home
	 */
	
	
	/**
	 * Initializes the work week for a given date and user.
	 * 
	 * Initializing a work week entails:
	 * 
	 * 		- marking the user's default off days as indicated in the user profile
	 * 		- tagging the week as initialized.
	 * 
	 * If a work week is already initialized, the method will take no action.
	 * 
	 * If the date passed belongs to a work week that straddles a workplan boundary, we proceed as follows:
	 * 
	 *  	- if the beginning of the week lies in the prior workplan, we check for its existence.
	 *  	  if it exists, we proceed normally. if no, we initialize the work week for the days that we have
	 *  	  a workplan for.
	 *  
	 *  	- if the end of the week lies in the following workplan, we check for its existence.
	 *		  if it exists, we proceed normally. if no, we initialize the work week for the days that we have
	 *  	  a workplan for.
	 * 
	 * @param	User		$user	
	 * @param	DateTime	$date
	 */
	public function initWorkWeek($user, $date) {

		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\RegularEntryPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
		
		try {
			
			// get refs to managers needed
			$tagManager = $this->getCtx()->getTagManager();
			$workplanManager = $this->getCtx()->getWorkplanManager();
			
			// we proceed only if week is not yet initialized
			if ( ! $tagManager->weekHasTag($date, \Tag::TYPE_WEEK_INITIALIZED, $user) ) {
				
				//
				// loop over every weekday, and mark "off-days" as indicated by user profile
				// note: in the case of weeks straddling workplans, we're not guaranteed that all days in the week exist.
				//		 accordingly, we test for existence when retrieving the Day instances and skip ahead if we come up empty
				//			
				
				// determine start of week (monday) date for passed date
				$startOfWeekDate = DateTimeHelper::getStartofWeek($date);
				
				for ( $weekDayNumber = 0;  $weekDayNumber <= 6;  $weekDayNumber++ ) {
					
					try {
						// attempt to retrieve the current weekday
						$curDay = $workplanManager->getDayForDate(DateTimeHelper::addDaysToDateTime($startOfWeekDate, $weekDayNumber));
						
						if ( $user->getDayOffValueForWeekDayNumber($weekDayNumber + 1) == "full" ) {
							$tagManager->createDayTag($curDay, \Tag::TYPE_DAY_DAY_OFF_FULL, $user);
						}
						else if ( $user->getDayOffValueForWeekDayNumber($weekDayNumber + 1) == "half-am" ) {
							$tagManager->createDayTag($curDay, \Tag::TYPE_DAY_HALF_DAY_OFF_AM, $user);
						}
						else if ( $user->getDayOffValueForWeekDayNumber($weekDayNumber + 1) == "half-pm" ) {
							$tagManager->createDayTag($curDay, \Tag::TYPE_DAY_HALF_DAY_OFF_PM, $user);
						}
					}
					catch (DayDoesNotExistException $ex) {
						// NOOP
					}

				}
				
				// finally, mark week as initialized
				$tagManager->createWeekTag($date, \Tag::TYPE_WEEK_INITIALIZED, $user);
			}
			
			// commit tx
			$con->commit();
			
		}
		catch (\PropelException $ex) {
			// rollback TX
			$con->rollback();
			// rethrow
			throw new MomoException("EntryManager:initWorkWeek() - a database error has occurred while while attempting to initialize the work week for: " . DateTimeHelper::formatDateTimeToPrettyDateFormat($date), 0, $ex);
		}		
		
	}
	
}