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
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License. 
 */

// ------------------------------------------------------------------------

/**
 * 
 * Momo Application Configuration
 * 
 */

//
// ------------------------------------------------------------------------
// basic params

//
// application version
$config['momo']['version'] = 'HEAD';

//
// site name
$config['momo']['site_name'] = "Momo";

//
// the application environment, set to one of {development, testing, production}
// the above value corresponding to the standard CI environment values
$config['momo']['environment'] = "development";


//
// ------------------------------------------------------------------------
// site online/offline

// offline flag: set to true to take site offline
$config['momo']['site_offline_status'] = false;

// privileged ip: enter ip that is allowed to access site despite offline=true
$config['momo']['site_offline_privileged_ip'] = "127.0.0.1";

// offline message: enter message to display while offline=true
$config['momo']['site_offline_title'] = "Site Offline";
$config['momo']['site_offline_message'] = "<p>Momo is getting an oil change - stay tuned.</p>";


//
// ------------------------------------------------------------------------
// refuse internet explorer access

// refusal message: enter message to display when site is accessed by IE
$config['momo']['ie_access_refused_title'] = "Unsupported Browser";
$config['momo']['ie_access_refused_message'] = "<p>Momo does not support Internet Explorer.</p><p>Please try again with a current version of Firefox, Chrome or Opera.</p>";


//
// ------------------------------------------------------------------------
// email related

// this key allows notification emails to be turned on or off, useful in test environment
$config['momo']['sendemails'] = false;

// these keys determine sender information applied to notification emails sent by the system
$config['momo']['email_from_address'] = 'noreply@momo.isn.ethz.ch';
$config['momo']['email_from_name'] = 'Momo';

// this key determines where notification emails are sent when key "environment" is other than 'production'
$config['momo']['email_to_address_non_production'] = 'krattiger@sipo.gess.ethz.ch';


//
// ------------------------------------------------------------------------
// cron service tasks
// -- place fully qualified path to CronTask instance in one of the provided scheduling slots

// hourly tasks
$config['momo']['cronservice']['hourly_tasks'][] 	= "momo\\enforcementservice\\crontasks\\DetectCompleteWeeksTask";
$config['momo']['cronservice']['hourly_tasks'][] 	= "momo\\tagmanager\\crontasks\\RemoveExpiredTagsTask";

// daily tasks
//$config['momo']['cronservice']['daily_tasks'][] 	= "momo\\enforcementservice\\crontasks\\SendOvertimeBalanceLapseNotificationsTask";
//$config['momo']['cronservice']['daily_tasks'][] 	= "momo\\enforcementservice\\crontasks\\SendVacationBalanceLapseNotificationsTask";
//$config['momo']['cronservice']['daily_tasks'][] 	= "momo\\enforcementservice\\crontasks\\LapseOvertimeBalancesTask";
//$config['momo']['cronservice']['daily_tasks'][] 	= "momo\\enforcementservice\\crontasks\\LapseVacationBalancesTask";

// weekly tasks
$config['momo']['cronservice']['weekly_tasks'][] 	= "momo\\enforcementservice\\crontasks\\SendIncompleteWeekNotificationsTask";

// monthly tasks
$config['momo']['cronservice']['monthly_tasks'][] 	= "momo\\enforcementservice\\crontasks\\SendExcessiveOvertimeNotificationsTask";


//
// ------------------------------------------------------------------------
// php specific

//
// server time zone		
$config['momo']['php_server_timezone'] = 'Europe/Zurich';


//
// ------------------------------------------------------------------------
// enumeration of third party components

/**

$config['momo']['third_party_components']['codeigniter']['version'] = '2.1.3';
$config['momo']['third_party_components']['codeigniter']['license'] = 'MIT';
$config['momo']['third_party_components']['codeigniter']['name'] = 'CodeIgniter';
$config['momo']['third_party_components']['codeigniter']['description'] = 'MVC Webapplication Framework';

$config['momo']['third_party_components']['propel']['version'] = '1.6.6-dev';
$config['momo']['third_party_components']['propel']['license'] = 'MIT';
$config['momo']['third_party_components']['propel']['name'] = 'Propel';
$config['momo']['third_party_components']['propel']['description'] = 'Object-Relational Mapper';

$config['momo']['third_party_components']['bootstrap']['version'] = '2.0.3';
$config['momo']['third_party_components']['bootstrap']['license'] = 'Apache v2.0';
$config['momo']['third_party_components']['bootstrap']['name'] = 'Twitter Bootstrap';
$config['momo']['third_party_components']['bootstrap']['description'] = 'HTML5 Framework';

$config['momo']['third_party_components']['jquery']['version'] = '1.7.2';
$config['momo']['third_party_components']['jquery']['license'] = 'MIT';
$config['momo']['third_party_components']['jquery']['name'] = 'jQuery';
$config['momo']['third_party_components']['jquery']['description'] = 'JS Library';

$config['momo']['third_party_components']['jquery_ui']['version'] = '1.8.21';
$config['momo']['third_party_components']['jquery_ui']['license'] = 'MIT';
$config['momo']['third_party_components']['jquery_ui']['name'] = 'jQuery UI';
$config['momo']['third_party_components']['jquery_ui']['description'] = 'UI library for use with jQuery';

$config['momo']['third_party_components']['datejs']['version'] = '1.0 Alpha-1';
$config['momo']['third_party_components']['datejs']['license'] = 'MIT';
$config['momo']['third_party_components']['datejs']['name'] = 'Date JS';
$config['momo']['third_party_components']['datejs']['description'] = 'JS date library';

$config['momo']['third_party_components']['bootstrap_colorpicker']['version'] = '[not versioned]';
$config['momo']['third_party_components']['bootstrap_colorpicker']['license'] = 'Apache v2.0';
$config['momo']['third_party_components']['bootstrap_colorpicker']['name'] = 'Bootstrap Colorpicker';
$config['momo']['third_party_components']['bootstrap_colorpicker']['description'] = 'Colorpicker for use with Twitter Bootstrap';

$config['momo']['third_party_components']['jquery_ui_bootstrap']['version'] = '[not versioned]';
$config['momo']['third_party_components']['jquery_ui_bootstrap']['license'] = 'MIT/GPL2 dual-license';
$config['momo']['third_party_components']['jquery_ui_bootstrap']['name'] = 'jQuery UI Bootstrap';
$config['momo']['third_party_components']['jquery_ui_bootstrap']['description'] = 'jQuery UI skin with \'look and feel\' of Twitter Bootstrap';

$config['momo']['third_party_components']['pear_log']['version'] = '1.1.27';
$config['momo']['third_party_components']['pear_log']['license'] = 'MIT';
$config['momo']['third_party_components']['pear_log']['name'] = 'PEAR Log';
$config['momo']['third_party_components']['pear_log']['description'] = 'PEAR logging package';

**/
 