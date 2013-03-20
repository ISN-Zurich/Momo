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

namespace momo\tagmanager;

use momo\core\helpers\DateTimeHelper;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;

/**
 * TagManager
 * 
 * Single access point for all tag related business logic
 * 
 * @author  Francesco Krattiger
 * @package momo.application.managers.tagmanager
 */
class TagManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_TAG";
	
	/**
	 * Determines whether a given day carries a certain tag type for a user
	 * 
	 * @param	Day			$day
	 * @param	string		$tagType
	 * @param	User		$user
	 * 
	 * @return	boolean
	 */
	public function dayHasTag($day, $tagType, $user) {
		// query for the tag on the indicated day 
		$tag = \TagQuery::create()
					->filterByDay($day)
					->filterByType($tagType)
					->filterByUser($user)
					->findOne();
					
		return ($tag !== null) ? true : false;						
	}
	
	
	/**
	 * Passed a date, the method determines whether a given week carries a certain tag type for a user.
	 * 
	 * Note: This method, together with createWeekTag() provide week-based semantics for tag operations.
	 * 
	 * @param	DateTime	$date
	 * @param	string		$tagType
	 * @param	User		$user
	 * 
	 * @return	boolean
	 */
	public function weekHasTag($date, $tagType, $user) {
	
		// query for the tag on the week that the indicated day falls on
		$tags = \TagQuery::create()
					->useDayQuery()
						->filterByDateOfDay(DateTimeHelper::getStartOfWeek($date), \TagQuery::GREATER_EQUAL)
						->filterByDateOfDay(DateTimeHelper::getEndOfWeek($date), \TagQuery::LESS_EQUAL)
					->endUse()
					->filterByType($tagType)
					->filterByUser($user)
					->find();
					
		return ($tags->count() != 0) ? true : false;						
	}
	
	
	/**
	 * Retrieves all days that carry a certain tag for a given user
	 * 
	 * @param	string	$tagType
	 * @param	User	$user
	 * 
	 * @return	PropelCollection		- a collection of days that carry the indicated tag
	 */
	public function getAllDaysWithTag($tagType, $user) {
		return \DayQuery::create()
					->useTagQuery()
						->filterByType($tagType)
						->filterByUser($user)
					->endUse()		
					->find();					
	}
	
	
	/**
	 * Retrieves all expired tags
	 */
	public function getExpiredTags() {	
		return \TagQuery::create()
				->filterByExpirationDate(new \DateTime(), \TagQuery::LESS_THAN)
				->find();
	}
	
	
	/**
	 * Creates a user tag of the indicated type on the indicated day
	 * 
	 * Note: a day may only carry one tag of any given type, if the tag already
	 * 		 exists, the method does nothing.
	 * 
	 * @param	Day			$day
	 * @param	string		$tagType
	 * @param	User		$user
	 * @param	DateTime	$tagExpirationDate (optional)
	 */
	public function createDayTag($day, $tagType, $user, $tagExpirationDate=null) {
		
		// we tag only, if the tag does not yet exist
		if ( ! $this->dayHasTag($day, $tagType, $user) ) {

			$newTag = new \Tag();
			
			$newTag->setDay($day);
			$newTag->setUser($user);
			$newTag->setType($tagType);
			$newTag->setExpirationDate($tagExpirationDate);
			
			$newTag->save();
						
		}
	}
	
	
	/**
	 * Creates a user tag of the indicated type for the week on which the indicated date falls
	 * 
	 * Note: a week may only carry one tag of any given type, if the tag already exists, the method does nothing.
	 * 
	 * @param	DateTime	$date
	 * @param	string		$tagType
	 * @param	User		$user
	 * @param	DateTime	$tagExpirationDate (optional)
	 */
	public function createWeekTag($date, $tagType, $user, $tagExpirationDate=null) {
		
		// we tag only, if the tag does not yet exist
		if ( ! $this->weekHasTag($date, $tagType, $user) ) {
			
			// find the first day of the week indicated by the day for which
			// there exists a workplan (Day) instance.
			$weekDays = \DayQuery::create()
									->filterByDateOfDay(DateTimeHelper::getStartOfWeek($date), \TagQuery::GREATER_EQUAL)
									->filterByDateOfDay(DateTimeHelper::getEndOfWeek($date), \TagQuery::LESS_EQUAL)
									->orderByDateOfDay(\TagQuery::ASC)
									->find();
		
			//						
			// place the tag on the first day found							
									
			$newTag = new \Tag();
			
			$newTag->setDay($weekDays->getFirst());
			$newTag->setUser($user);
			$newTag->setType($tagType);
			$newTag->setExpirationDate($tagExpirationDate);
			
			$newTag->save();
						
		}
	}
	
	/**
	 * Deletes a user tag of the indicated type for the week on which the indicated date falls
	 * 
	 * @param	DateTime	$date
	 * @param	string		$tagType
	 * @param	User		$user
	 */
	public function deleteWeekTag($date, $tagType, $user) {						
		// query for the tags of the indicated type that fall on the week containing the indicated day						
		$deleteTags = \TagQuery::create()
							->useDayQuery()
								->filterByDateOfDay(DateTimeHelper::getStartOfWeek($date), \TagQuery::GREATER_EQUAL)
								->filterByDateOfDay(DateTimeHelper::getEndOfWeek($date), \TagQuery::LESS_EQUAL)
							->endUse()
							->filterByUser($user)
							->filterByType($tagType)
							->find();
							
		// delete the retrieved tags
		\TagQuery::create()
					->filterById($deleteTags->toKeyValue("id", "id"))
					->delete();										
	}
	
	
	/**
	 * Deletes a user tag of the indicated type from a given day
	 * 
	 * @param	Day			$day
	 * @param	string		$tagType
	 * @param	User		$user
	 * @param	DateTime	$tagExpirationDate (optional)
	 */
	public function deleteDayTagByType($day, $tagType, $user) {
		
		// delete the indicated tag
		\TagQuery::create()
					->filterByDay($day)
					->filterByUser($user)
					->filterByType($tagType)
					->delete();
			
	}
	
	
	/**
	 * Deletes all day tags that carry a certain tag for a given user and date range
	 * 
	 * @param	string		$tagType
	 * @param	User		$user
	 * @param	DateTime	$fromDate		- the start date of the range to consider
	 * @param	DateTime	$untilDate		- the end date of the range to consider (optional)
	 * 
	 */
	public function deleteDayTagByTypeAndDateRange($tagType, $user, $fromDate, $untilDate=null) {
		
		// construct the delete query
		$deleteTagQuery = \TagQuery::create()
									->useDayQuery()
										->filterByDateOfDay($fromDate, \TagQuery::GREATER_EQUAL)
									->endUse()
									->filterByUser($user)
									->filterByType($tagType);

		if ( $untilDate !== null ) {
				$deleteTagQuery->useDayQuery()
									->filterByDateOfDay($untilDate, \TagQuery::LESS_EQUAL)
								->endUse();
		}							

		// find and delete the tags
		$deleteTags = $deleteTagQuery->find()->delete();
										
	}
	
	
	/**
	 * Deletes all tags of the indicated type for a given user and date range employing week semantics.
	 * 
	 * The method guarantees that the week containing the range end points and all the week
	 * in between have the indicated tag cleared.
	 * 
	 * @param	string		$tagType
	 * @param	User		$user
	 * @param	DateTime	$fromDate		- the start date of the range to consider
	 * @param	DateTime	$untilDate		- the end date of the range to consider (set to null, if there is to be no upper date limit)
	 */
	public function deleteWeekTagByTypeAndDateRange($tagType, $user, $fromDate, $untilDate) {
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\TagPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// the currently processed date
			$curDate = $fromDate;
			
			// we step forward until we exceed the end date
			while ( $curDate <= $untilDate ) {
				// delete the week tag for the current date
				$this->deleteWeekTag($curDate, $tagType, $user);
				
				// step the current date forward one week
				$curDate = DateTimeHelper::addDaysToDateTime($curDate, 7);
			}
			
			// if the until date's week day lies before that of the from date, we need to
			// delete the tag of the until date explicitly
			if ( DateTimeHelper::getWeekDayNumberFromDateTime($untilDate) > DateTimeHelper::getWeekDayNumberFromDateTime($fromDate) ) {
				$this->deleteWeekTag($untilDate, $tagType, $user);
			}

			$con->commit();
		}
		catch (\PropelException $ex) {
			// roll back
			$con->rollback();
			// rethrow
			throw new MomoException("TagManager:deleteWeekTagByTypeAndDateRange() - a database error has occurred while attempting to delete the week tags for user with id: " . $user->getId(), 0, $ex);
		}		
										
	}
	
}