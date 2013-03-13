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
 * Momo Logger
 * 
 * An extension of the standard CI_Log class.
 * 
 * Unlike CI_Log, this class allows for different log files to be specified.
 * The class reverts to default behavior if no log file is specified.
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.libraries
 */
class Momo_Log extends CI_Log {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();	
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string		- the error level
	 * @param	string		- the error message
	 * @param	bool		- whether the error is a native PHP error
	 * @param	string		- the file to log to, omit parameter to log to default CI file
	 * 
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE, $logFileName = null)
	{
		
		if ( $this->_enabled === FALSE ) {
			return FALSE;
		}

		$level = strtoupper($level);

		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold)) {
			
			if ( empty($this->_threshold_array) OR ! isset($this->_threshold_array[$this->_levels[$level]]) ) {
				return FALSE;
			}
		}

		if ( $logFileName !== null ) {
			$filepath = $this->_log_path . $logFileName . "-" . date('Y-m-d') . '.php';
		}
		else {
			$filepath = $this->_log_path . 'log-' . date('Y-m-d') . '.php';
		}

		$message  = '';

		if ( ! file_exists($filepath)) {
			$newfile = TRUE;
			$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
			return FALSE;
		}

		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt). ' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) AND $newfile === TRUE) {
			@chmod($filepath, FILE_WRITE_MODE);
		}
		
		return TRUE;
	}

}