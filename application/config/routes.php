<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "TimetrackerController";

$route['develop/(:any)'] = "Develop/$1";

$route['enforcement/(:any)'] = "EnforcementController/$1";
$route['enforcement'] = "EnforcementController";

$route['manageadjustments/(:any)'] = "ManageAdjustmentsController/$1";
$route['manageadjustments'] = "ManageAdjustmentsController";

$route['manageaudittrail/(:any)'] = "ManageAuditTrailController/$1";
$route['manageaudittrail'] = "ManageAuditTrailController";

$route['manageentrytypes/(:any)'] = "ManageEntryTypesController/$1";
$route['manageentrytypes'] = "ManageEntryTypesController";

$route['managebookingtypes/(:any)'] = "ManageBookingTypesController/$1";
$route['managebookingtypes'] = "ManageBookingTypesController";

$route['managebookings/(:any)'] = "ManageBookingsController/$1";
$route['managebookings'] = "ManageBookingsController";

$route['manageprojects/(:any)'] = "ManageProjectsController/$1";
$route['manageprojects'] = "ManageProjectsController";

$route['managerequests/(:any)'] = "ManageRequestsController/$1";
$route['managerequests'] = "ManageRequestsController";

$route['managesettings/(:any)'] = "ManageSettingsController/$1";
$route['managesettings'] = "ManageSettingsController";

$route['manageteams/(:any)'] = "ManageTeamsController/$1";
$route['manageteams'] = "ManageTeamsController";

$route['manageusers/(:any)'] = "ManageUsersController/$1";
$route['manageusers'] = "ManageUsersController";

$route['manageworkplans/(:any)'] = "ManageWorkplansController/$1";
$route['manageworkplans'] = "ManageWorkplansController";

$route['reports/(:any)'] = "ReportsController/$1";
$route['reports'] = "ReportsController";

$route['security/(:any)'] = "SecurityController/$1";
$route['security'] = "SecurityController";

$route['timetracker/(:any)'] = "TimetrackerController/$1";
$route['timetracker'] = "TimetrackerController";

$route['utility/(:any)'] = "UtilityController/$1";
$route['utility'] = "UtilityController";


/* End of file routes.php */
/* Location: ./application/config/routes.php */