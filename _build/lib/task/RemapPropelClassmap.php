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

require_once 'phing/Task.php';

class RemapPropelClassMap extends Task
{

	protected $sourceFilePath = null;
	protected $destFilePath = null;
	
	public function init() { }
 
	public function main() {
		file_put_contents( 	
							$this->destFilePath,
							str_replace("' => '", "' => APPPATH . DIRECTORY_SEPARATOR . 'models/", file_get_contents($this->sourceFilePath))
						  );
	}
	 
	public function setSourceFilePath($sourceFilePath) {
    	$this->sourceFilePath = $sourceFilePath;
  	}
 
  	public function setDestFilePath($destFilePath) {
    	$this->destFilePath = $destFilePath;
  	}

}