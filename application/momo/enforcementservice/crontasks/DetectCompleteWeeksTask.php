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

namespace momo\enforcementservice\crontasks;

use momo\cronservice\CronTask;

/**
 * DetectCompleteWeeksTask
 * 
 * This task detects and tags weeks that are complete from a timetracking perspective.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class DetectCompleteWeeksTask extends CronTask {

	public function run($cronService) {
			
		// get managers
		$tagManager = $this->getCtx()->getTagManager();
		$userManager = $this->getCtx()->getUserManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
	
		// detect which weeks presently still have status "incomplete"
		$incompleteWeeksDigest = $enforcementService->detectIncompleteWeeksForAllActiveUsers();
		
		//
		// process each users' results
		//
		// - tag weeks now deemed "complete" accordingly
		// - notify team leader and user of incomplete weeks detected
		//
		foreach ( $incompleteWeeksDigest as $curUserId => $newlyIncompleteWeekMondays ) {
			
			$curUser = $userManager->getUserById($curUserId);
			
			// obtain a list of weeks presently in state "incomplete"
			$presentlyIncompleteWeekMondays = $enforcementService->getIncompleteWeeksForUser($curUser);
			
			// query for the difference between the sets of the presently incomplete weeks and those newly detected to be incomplete
			// the result represents the weeks presently marked incomplete that are now deemed complete
			// -- the set of newly incomplete weeks is guaranteed to be a subset of the set of presently incomplete weeks
			$newlyCompleteWeekMondays = \DayQuery::create()
												->filterById($presentlyIncompleteWeekMondays->toKeyValue("id", "id"))
												->filterById(array_keys($newlyIncompleteWeekMondays), \DayQuery::NOT_IN)
												->find();
		
			// mark newly complete weeks accordingly
			foreach ( $newlyCompleteWeekMondays as $curCompleteWeekMonday ) {
				$tagManager->createDayTag( $curCompleteWeekMonday, \Tag::TYPE_WEEK_COMPLETE, $curUser);
			}
			
			// log stats for current user
			$cronService->log("info", "\t\tprocessing user: " . $curUser->getFullName());
			$cronService->log("info", "\t\t\t" . count($newlyIncompleteWeekMondays) . " incomplete week(s) detected.");
			
			if ( $newlyCompleteWeekMondays->count() > 0 ) {
				$cronService->log("info", "\t\t\t" . $newlyCompleteWeekMondays->count() . " new week(s) promoted to complete.");
			}
										
		}
				
	}

}