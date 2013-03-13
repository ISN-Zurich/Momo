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

use momo\core\helpers\DateTimeHelper;

use momo\cronservice\CronTask;
use momo\emailservice\EmailTemplates;
use momo\emailservice\EmailService;


/**
 * SendVacationBalanceLapseNotificationsTask
 * 
 * Notifies users of impending vacation balance lapses
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.enforcementservice
 */
class SendVacationBalanceLapseNotificationsTask extends CronTask {
	
	// application scope data key
	const LAST_VACATION_LAPSE_NOTIFICATION_DATE = "LAST_VACATION_LAPSE_NOTIFICATION_DATE";
	
	// notification time horizons in months
	// note that these are summed with the current date, so be sure to set proper sign here
	
	const FIRST_NOTIFICATION_TIME_HORIZON_IN_MONTHS 	= -3;
	const SECOND_NOTIFICATION_TIME_HORIZON_IN_MONTHS 	= -2;
	const THIRD_NOTIFICATION_TIME_HORIZON_IN_MONTHS 	= -1;
	
	
	public function run($cronService) {
			
		// get managers/services
		$emailService = $this->getCtx()->getEmailService();
		$compService = $this->getCtx()->getComputationService();
		$userManager = $this->getCtx()->getUserManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$enforcementService = $this->getCtx()->getEnforcementService();
		
		
		$cronService->log("info", "\t\tbegin pondering lapse date...");
		
		//
		// determine next applicable lapse date
		// -- if we've passed current year's vacation lapse date, the next year's dates apply
		
		// the current date
		
		// today's date
		$todayDate = DateTimeHelper::getDateTimeForCurrentDate();
		
		// the current year's vacation lapse date		
		$vacationLapseDate = $enforcementService->getVacationLapseDateForWorkplanYear(DateTimeHelper::getCurrentYear());												

		if ( $todayDate > $vacationLapseDate ) {
			$vacationLapseDate = $enforcementService->getVacationLapseDateForWorkplanYear(DateTimeHelper::getCurrentYear() + 1);
		}
			
		$cronService->log("info", "\t\t\tnext vacation lapse date is: " . DateTimeHelper::formatDateTimeToPrettyDateFormat($vacationLapseDate));
		
		//
		// having determined the lapse date, see if the requisite workplans exist.
		//
		// we always need a workplan corresponding to the lapse date (where the lapse is applied to by means on an AdjustmentEntry).
		//
		// for the first year of application use, there will be no workplan prior to the one corresponding to the lapse date.
		// in this case, no notifications will be sent, as there is nothing to roll over in the first place
		
		// look for workplans corresponding to lapse dates
		$lapseOnWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($vacationLapseDate));
		$lapseFromWorkplan = $workplanManager->getPlanByYear(DateTimeHelper::getYearFromDateTime($vacationLapseDate) - 1);

		// log and advise if there is no workplan for current lapse date
		if ( $lapseOnWorkplan === null ) {
			
			$warningMsg = "WARNING: There exists no workplan for the next vacation lapse date: " . DateTimeHelper::formatDateTimeToPrettyDateFormat($vacationLapseDate);
			
			$cronService->log("info", "\t\t\t" . $warningMsg);
			
			//
			// notify site administrator
			
			// get site administrator email address
			$siteAdminEmailAddress = $settingsManager->getSettingValue(\Setting::KEY_SITE_ADMIN_EMAIL_ADDRESS);
			
			// compile message map
			$messageMap = array();
			
			// drop list of affected weeks into message
			$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_PAYLOAD] = $warningMsg;
			
			// send notification email to site admin
			$emailService->sendEmailFromTemplate(
									$this->getCI()->config->item("email_from_address", "momo"),
									$this->getCI()->config->item("email_from_name", "momo"),
									$siteAdminEmailAddress,
									EmailTemplates::$siteAdminNotificationSubject,
									null,
									EmailTemplates::$siteAdminNotificationMessage,
									$messageMap
								);
									
			$cronService->log("info", "\t\t\tsent advisory notice to site administrator: " . $siteAdminEmailAddress);
			$cronService->log("info", "\t\tend pondering lapse date.");
			$cronService->log("info", "");
			
		}
		else {
			
			$cronService->log("info", "\t\tend pondering lapse date.");
			$cronService->log("info", "");
		
			// log and advise if there is no workplan to lapse from
			if ( $lapseFromWorkplan === null ) {
				$cronService->log("info", "\t\t\tthere is no workplan to lapse from... no need to send notifications.");
				$cronService->log("info", "");
				
			}
			else {
				
				//	
				// get the task's application scope data
				$appScopeData = $this->getCtx()->getApplicationScopeValue(get_class($this));
				
				// if there is no app scope data, initialize it
				if ( $appScopeData == null ) {
					$appScopeData = array();
					$appScopeData[SendVacationBalanceLapseNotificationsTask::LAST_VACATION_LAPSE_NOTIFICATION_DATE] = null;
				}
				
				// retrieve last notification date from app scope data
				// note that the keys might resolve to null, in case no notifications of the given type have ever been sent
				$lastVacationLapseNotificationDate = $appScopeData[SendVacationBalanceLapseNotificationsTask::LAST_VACATION_LAPSE_NOTIFICATION_DATE];
				
				//
				// calculate the start of the various notification time windows
				
				$firstVacationNotificationTimeWindowStartDate = DateTimeHelper::addMonthsToDateNormalSemantics(
																			$vacationLapseDate,
																			SendVacationBalanceLapseNotificationsTask::FIRST_NOTIFICATION_TIME_HORIZON_IN_MONTHS
																		);
																		
				$secondVacationNotificationTimeWindowStartDate = DateTimeHelper::addMonthsToDateNormalSemantics(
																			$vacationLapseDate,
																			SendVacationBalanceLapseNotificationsTask::SECOND_NOTIFICATION_TIME_HORIZON_IN_MONTHS
																		);
	
				$thirdVacationNotificationTimeWindowStartDate = DateTimeHelper::addMonthsToDateNormalSemantics(
																			$vacationLapseDate,
																			SendVacationBalanceLapseNotificationsTask::THIRD_NOTIFICATION_TIME_HORIZON_IN_MONTHS
																		);	
			
																		
				//////////////////////////////////////////////////////////////////////////////////////////////////
				//
				// process lapse notifications
				//
				
				
				//
				// figure if one of the notifications needs to be sent
				// set flag, if the notification for the applicable time window was already sent
				$sendNotifications = false;
				$notificationsAlreadySent = false;
				$currentVacationNotificationTimeWindowStartDate = null;
				

				if ( $todayDate >= $thirdVacationNotificationTimeWindowStartDate ) {
					
					$currentVacationNotificationTimeWindowStartDate = $thirdVacationNotificationTimeWindowStartDate;
					
					if ( 	($lastVacationLapseNotificationDate < $thirdVacationNotificationTimeWindowStartDate)
						 || ($lastVacationLapseNotificationDate == null) ) {
						$sendNotifications = true;	
					}
					else {
						$notificationsAlreadySent = true;
					}
				}
				else if ( $todayDate >= $secondVacationNotificationTimeWindowStartDate ) {
					 	 	
					$currentVacationNotificationTimeWindowStartDate = $secondVacationNotificationTimeWindowStartDate;
					
					if ( 	($lastVacationLapseNotificationDate < $secondVacationNotificationTimeWindowStartDate)
						 || ($lastVacationLapseNotificationDate == null) ) {
						$sendNotifications = true;	
					}
					else {
						$notificationsAlreadySent = true;
					}
				}
				else if ( $todayDate >= $firstVacationNotificationTimeWindowStartDate ) {
					
					$currentVacationNotificationTimeWindowStartDate = $firstVacationNotificationTimeWindowStartDate;
					
					if ( 	($lastVacationLapseNotificationDate < $firstVacationNotificationTimeWindowStartDate)
						 || ($lastVacationLapseNotificationDate == null) ) {
						$sendNotifications = true;	
					}
					else {
						$notificationsAlreadySent = true;
					}
				}
							
				
				//
				// send notifications if indicated
				if ( $sendNotifications ) {
			
					$cronService->log("info", "\t\tstarting vacation lapse user notification");
					
					// store the current date as the date of the last vacation lapse notification
					$appScopeData[SendVacationBalanceLapseNotificationsTask::LAST_VACATION_LAPSE_NOTIFICATION_DATE] = $todayDate;
					$this->getCtx()->setApplicationScopeValue(get_class($this), $appScopeData);
					
					// get all users
					$allUsers = $userManager->getAllUsers();
					
					//
					// look at each enabled user and send notification where applicable	
					foreach ( $allUsers as $curUser ) {
						
						// the user is notified only if the present time point lies within their employment period
						if ( 	($curUser->getEntryDate() <= $todayDate)
							 && ($curUser->getExitDate() >= $todayDate) ) {
							
							$cronService->log("info", "\t\t\tprocessing user: " . $curUser->getFullName());
							 	
							// obtain number of vacation days in danger of expiring
							$vacationDaysToLapse = $enforcementService->computeVacationDaysSetToLapseForForUserAndDate($curUser, $todayDate);

							$cronService->log("info", "\t\t\t\tnumber of vacation days in danger of lapsing: " . $vacationDaysToLapse);
													  
							//
							// if there is something to roll over, we a send notification
							if ( $vacationDaysToLapse > 0 ) {
								
								// compile message map  
								$messageMap = array();
								$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_FIRST_NAME] 	= $curUser->getFirstName();
								$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_NUMBER] 		= round($vacationDaysToLapse, 1);
								$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_YEAR] 		= $lapseFromWorkplan->getYear();
								$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_DATE_1] 		= DateTimeHelper::formatDateTimeToPrettyDateFormat($vacationLapseDate);
								$messageMap[EmailTemplates::$REPLACEMENT_TOKEN_DATE_2] 		= DateTimeHelper::formatDateTimeToPrettyDateFormat(DateTimeHelper::addDaysToDateTime($vacationLapseDate, -1));
								
								// send notification email
								$emailService->sendEmailFromTemplate(
														$this->getCI()->config->item("email_from_address", "momo"),
														$this->getCI()->config->item("email_from_name", "momo"),
														$curUser->getEmail(),
														EmailTemplates::$notifyUserOfImpendingVacationBalanceLapseSubject,
														null,
														EmailTemplates::$notifyUserOfImpendingVacationBalanceLapseMessage,
														$messageMap
													);
													
								$cronService->log("info", "\t\t\t\tnotification email sent to: " . $curUser->getEmail());
													
							}
						 	else {
								$cronService->log("info", "\t\t\t\tuser has no vacation days in danger of expiry.");
							}
										
						}
							
					}
					
					$cronService->log("info", "\t\tcompleted vacation lapse user notification");
						
				}
				else if ( $notificationsAlreadySent ) {
					
					// figure out notification time window end date (1 day less then the start date plus one month)
					$currentVacationNotificationTimeWindowEndDate = DateTimeHelper::addMonthsToDateNormalSemantics($currentVacationNotificationTimeWindowStartDate, 1);
					$currentVacationNotificationTimeWindowEndDate = DateTimeHelper::addDaysToDateTime($currentVacationNotificationTimeWindowEndDate, -1);
					
					$logMessage = "\t\tvacation notifications already sent for the notification time window extending from ";
					$logMessage .= "'" . DateTimeHelper::formatDateTimeToPrettyDateFormat($currentVacationNotificationTimeWindowStartDate) . "'";
					$logMessage .= " to ";
					$logMessage .= "'" . DateTimeHelper::formatDateTimeToPrettyDateFormat($currentVacationNotificationTimeWindowEndDate) . "'";
					
					$cronService->log("info", $logMessage);
					
				}
				else {
					$logMessage = "\t\tpresently outside of applicable notification time windows.";
					$cronService->log("info", $logMessage);
				}
									
			}		
		}
		
	}

}