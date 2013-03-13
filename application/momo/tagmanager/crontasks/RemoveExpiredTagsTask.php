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

namespace momo\tagmanager\crontasks;

use momo\cronservice\CronTask;

/**
 * 
 * RemoveExpiredTagsTask
 * 
 * Removes expired tags.
 * 
 * Tags are considered "expired" if their expiration date has lapsed.
 * 
 * @author  Francesco Krattiger
 */
class RemoveExpiredTagsTask extends CronTask {

	public function run($cronService) {
			
		// get tag manager
		$tagManager = $this->getCtx()->getTagManager();
	
		// get expired tags
		$expiredTags = $tagManager->getExpiredTags();
		
		// delete the expired tags
		$expiredTags->delete();
		
		// log number of expired tags	
		$cronService->log("info", "\t\t" . $expiredTags->count() . " expired tags were deleted from system.");
			
	}

}