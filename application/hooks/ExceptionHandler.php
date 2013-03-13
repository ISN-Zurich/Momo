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
 * The Momo exception handler
 * 
 * @author  Francesco Krattiger
 * @package momo.ci.hooks
 */
class ExceptionHandler
{
	public function setExceptionHandler() {
    	set_exception_handler(array($this, 'handleException'));
  	}
   
  	/**
  	 * Logs exception at "error" level and displays summary on frontend
  	 */
	public function handleException($exception) {
		
   		$templateMsg = array();
   		$logMsg = array();
 		$exceptionChain = array();
 		
 		//
 		// build up exception chain
 		$exceptionChain[] = $exception;
 		
		$curException = $exception;
   		while ( ($curException = $curException->getPrevious()) !== null ) {
   			$exceptionChain[] = $curException;
   		}
   		
   		//
   		// build error message arrays (template and log)
   		
   		// file name and line number
   		$templateMsg[] = "<strong>File:</strong><br>" . $exception->getFile();
   		$templateMsg[] = "<strong>Line:</strong><br>" . $exception->getLine();
   		$logMsg[] = "File:\n" . $exception->getFile();
   		$logMsg[] = "Line:\n" . $exception->getLine();
   		
   		// add exception chain to error message
   		$exceptionIndex = sizeof($exceptionChain);
   		$templateErrorMsg = "<strong>Exception Chain:</strong>";
   		$logErrorMsg = "Exception Chain:";
   		
   		foreach ( $exceptionChain as $curException ) {
   			$templateErrorMsg .= "<br>" . $exceptionIndex . ". " . get_class($curException);
   			$logErrorMsg .= "\n" . $exceptionIndex . ". " . get_class($curException);
   			$exceptionIndex--;
   		}
   	   		
   		$templateMsg[] = $templateErrorMsg;
   		$logMsg[] = $logErrorMsg;
   		
   		// add message chain to error message
   		$exceptionIndex = sizeof($exceptionChain);
   		$templateErrorMsg = "<strong>Message Chain:</strong>";
   		$logErrorMsg = "Message Chain:";

   		foreach ( $exceptionChain as $curException ) {
   			
   			$curMessage = $curException->getMessage();
   			
   			if ( trim($curMessage) == "" ) {
   				$curMessage = "(no message)";
   			}
   			
   			$templateErrorMsg .= "<br>" . $exceptionIndex . ". " . $curMessage;
   			$logErrorMsg .= "\n" . $exceptionIndex . ". " . $curMessage;
   			$exceptionIndex--;
   		}
   	   		
   		$templateMsg[] = $templateErrorMsg;
   		$logMsg[] = $logErrorMsg;
   		
   		// add backtrace to error message
   		$templateMsg[] = "<strong>Backtrace:</strong><br>" . str_replace("\n", "<br>", $exception->getTraceAsString());
   		$logMsg[] = "Backtrace:\n" . $exception->getTraceAsString();
   		
   		// log the error
   		log_message('error', "Exception Thrown: " . get_class($exception) . "\n" . implode("\n", $logMsg));
   		
   		// display the error
        show_error($templateMsg, 500, "Exception Thrown: " . get_class($exception));     
   
	}

}