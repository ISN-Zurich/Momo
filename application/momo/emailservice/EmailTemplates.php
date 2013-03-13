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

namespace momo\emailservice;

/**
 * EmailTemplates
 * 
 * A collection of email templates
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.emailservice
 */
class EmailTemplates {
	//
	// email template tokens
	public static $REPLACEMENT_TOKEN_FIRST_NAME 				= "{FIRST_NAME}";
	public static $REPLACEMENT_TOKEN_LOGIN 						= "{LOGIN}";
	public static $REPLACEMENT_TOKEN_SET_PASSWORD_URL 			= "{SET_PASSWORD_URL}";
	public static $REPLACEMENT_TOKEN_MOMO_URL 					= "{MOMO_URL}";
	public static $REPLACEMENT_TOKEN_USER_FULL_NAME 			= "{USER_FULL_NAME}";
	public static $REPLACEMENT_TOKEN_ACTOR_FULL_NAME 			= "{ACTOR_FULL_NAME}";
	public static $REPLACEMENT_TOKEN_GENERAL_URL 				= "{GENERAL_URL}";
	public static $REPLACEMENT_TOKEN_OOREQUEST_TYPE 			= "{OOREQUEST_TYPE}";
	public static $REPLACEMENT_TOKEN_OOREQUEST_DATE_FROM 		= "{OOREQUEST_DATE_FROM}";
	public static $REPLACEMENT_TOKEN_OOREQUEST_DATE_UNTIL 		= "{OOREQUEST_DATE_UNTIL}";
	public static $REPLACEMENT_TOKEN_OOREQUEST_STATUS 			= "{OOREQUEST_STATUS}";
	public static $REPLACEMENT_TOKEN_PAYLOAD 					= "{PAYLOAD}";
	public static $REPLACEMENT_TOKEN_DATE 						= "{DATE}";
	public static $REPLACEMENT_TOKEN_DATE_1 					= "{DATE_1}";
	public static $REPLACEMENT_TOKEN_DATE_2 					= "{DATE_2}";
	public static $REPLACEMENT_TOKEN_YEAR 						= "{YEAR}";
	public static $REPLACEMENT_TOKEN_NUMBER 					= "{NUMBER}";
	public static $REPLACEMENT_TOKEN_TEAM_NAME 					= "{TEAM_NAME}";
	

	
/////////////////////////////////////////////////////////////////////////////////////////
// account creation email template
	
	public static $accountCreatedNotificationSubject = "Momo - Account Created";
	
	public static $accountCreatedNotificationMessage = <<<EOF
		
Hello {FIRST_NAME},
	
This is to notify you that a user account has been created for you on "Momo", the ISN's time tracking application.
	
The login assigned to you is: {LOGIN}
	

Before you access Momo, please follow the link below and set a password for your new account:

{SET_PASSWORD_URL}


Having done so, you may log on to Momo at:

{MOMO_URL}
	

-- Momo
EOF;



/////////////////////////////////////////////////////////////////////////////////////////
//  password reset template
	
	public static $passwordResetNotificationSubject = "Momo - Password Reset";
	
	public static $passwordResetNotificationMessage = <<<EOF
		
Hello {FIRST_NAME},
	
This is to notify you that the password of your Momo account ({LOGIN}) has been reset.
	

To regain access to Momo, please follow the link below and set a new password:

{SET_PASSWORD_URL}


Having done so, you may log on to Momo at:

{MOMO_URL}
	

-- Momo
EOF;



/////////////////////////////////////////////////////////////////////////////////////////
// new oo request template
	
	public static $ooRequestCreatedNotificationSubject = "Momo - New Out-of-Office Request";
	
	public static $ooRequestCreatedNotificationMessage = <<<EOF
		
Hello,

{USER_FULL_NAME} has posted a new out-of-office request.

Follow the link below to manage out-of-office bookings and requests:

{GENERAL_URL}

	
-- Momo
EOF;



/////////////////////////////////////////////////////////////////////////////////////////
// oo request status change template
	
	public static $ooRequestStatusChangeSubject = "Momo - New Out-of-Office Request";
	
	public static $ooRequestStatusChangeMessage = <<<EOF
		
Hello {FIRST_NAME},

Your request for "{OOREQUEST_TYPE}" from {OOREQUEST_DATE_FROM} to {OOREQUEST_DATE_UNTIL} has been {OOREQUEST_STATUS}.

Follow the link below for an overview of your requests.

{GENERAL_URL}


-- Momo
EOF;


	
/////////////////////////////////////////////////////////////////////////////////////////
// timetracker week unlocked template
	
	public static $timetrackerWeekUnlockedSubject = "Momo - Timetracker Week Unlocked";
	
	public static $timetrackerWeekUnlockedMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that {ACTOR_FULL_NAME} has unlocked the Timetracker week beginning on {DATE}.


-- Momo
EOF;

	
	
/////////////////////////////////////////////////////////////////////////////////////////
// incomplete weeks detected template (user notification)
	
	public static $notifyUserOfIncompleteWeeksSubject = "Momo - Incomplete Weeks Detected";
	
	public static $notifyUserOfIncompleteWeeksMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that the following weeks in your timetracker are incomplete:

{PAYLOAD}

-- Momo
EOF;


	
/////////////////////////////////////////////////////////////////////////////////////////
// incomplete weeks detected template (team leader notification)
	
	public static $notifyTeamLeadersOfIncompleteWeeksSubject = "Momo - Incomplete Weeks Detected";
	
	public static $notifyTeamLeadersOfIncompleteWeeksMessage = <<<EOF
		
Hello Team Leader,

This is to inform you that the following users of team '{TEAM_NAME}' have incomplete timetracker weeks:

{PAYLOAD}


-- Momo
EOF;
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////
// excessive overtime detected template (user notification)
	
	public static $notifyUserOfExcessiveOvertimeSubject = "Momo - Excessive Overtime Detected";
	
	public static $notifyUserOfExcessiveOvertimeMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that you presently carry {NUMBER} hours of overtime - this is considered excessive.


-- Momo
EOF;

	
	
/////////////////////////////////////////////////////////////////////////////////////////
// impending vacation lapse template (user notification)
	
	public static $notifyUserOfImpendingVacationBalanceLapseSubject = "Momo - Impending Vacation Balance Lapse";
	
	public static $notifyUserOfImpendingVacationBalanceLapseMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that your remaining vacation credit from {YEAR} (presently {NUMBER} days), will lapse on {DATE_1}.

Please note that the figure mentioned takes into account vacation booked up to and including {DATE_2}.


-- Momo
EOF;


	
/////////////////////////////////////////////////////////////////////////////////////////
// impending overtime lapse template (user notification)
	
	public static $notifyUserOfImpendingOvertimeBalanceLapseSubject = "Momo - Impending Overtime Balance Lapse";
	
	public static $notifyUserOfImpendingOvertimeBalanceLapseMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that any overtime balance in excess of 8 hours accrued in {YEAR} will lapse on {DATE_1}.

As per {DATE_2} you have {NUMBER} hours of overtime in danger of lapsing.


-- Momo
EOF;

	
/////////////////////////////////////////////////////////////////////////////////////////
// overtime lapsed notification template (user notification)
	
	public static $notifyUserOfOvertimeBalanceLapseSubject = "Momo - Overtime Balance Lapsed";
	
	public static $notifyUserOfOvertimeBalanceLapseMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that overtime for your account has lapsed as follows:

{PAYLOAD}

-- Momo
EOF;

	
/////////////////////////////////////////////////////////////////////////////////////////
// overtime lapsed notification template (user notification)
	
	public static $notifyUserOfVacationBalanceLapseSubject = "Momo - Vacation Balance Lapsed";
	
	public static $notifyUserOfVacationBalanceLapseMessage = <<<EOF
		
Hello {FIRST_NAME},

This is to inform you that vacation credit for your account has lapsed as follows:

{PAYLOAD}

-- Momo
EOF;
	

/////////////////////////////////////////////////////////////////////////////////////////
// incomplete weeks detected template (team leader notification)
	
	public static $notifyTeamLeadersOfExcessiveOvertimeSubject = "Momo - Excessive Overtime Detected";
	
	public static $notifyTeamLeadersOfExcessiveOvertimeMessage = <<<EOF
		
Hello Team Leader,

This is to inform you that the following users of team '{TEAM_NAME}' carry excessive overtime:

{PAYLOAD}


-- Momo
EOF;
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////
// management originated message template
	
	public static $siteAdminNotificationSubject = "Momo - Site Administrator Notification";
	
	public static $siteAdminNotificationMessage = <<<EOF
		
Dear Site Administrator,

Please take note of the message below.


-- Momo

-----------------------------------------------------------------------------------------

{PAYLOAD}
EOF;
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////
// management originated message template
	
	public static $managementOriginatedMessageSubject = "Momo - Management Originated Message";
	
	public static $managementOriginatedMessage = <<<EOF
		
Hello {FIRST_NAME},

{ACTOR_FULL_NAME} sends you the message below.


-- Momo

-----------------------------------------------------------------------------------------

{PAYLOAD}
EOF;

	
}