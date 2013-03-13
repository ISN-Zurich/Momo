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


use momo\core\helpers\MenuHelper;
use momo\core\security\Roles;

//
// figure menu to mark as active

$cssActiveTimetracker = "";
$cssActiveReports = "";
$cssActiveRequests = "";
$cssActiveTeams = "";
$cssActiveApplication = "";


if ( 	( ! $this->uri->segment(1)) 
	 || ( strpos($this->uri->segment(1), "timetracker") !== false ) ) {
		
	$cssActiveTimetracker = "active";
}
else if  ( strpos($this->uri->segment(1), "managerequests") !== false ) {
	
	$cssActiveRequests = "active";
}
else if  ( strpos($this->uri->segment(1), "reports") !== false ) {
	
	$cssActiveReports = "active";
}
else if  ( 		(strpos($this->uri->segment(2), "displayteamsummary") !== false)
			||	(strpos($this->uri->segment(1), "managebookings") !== false) ) {
	
	$cssActiveTeams = "active";
}
else if  ( 
				( strpos($this->uri->segment(1), "manageentrytypes") !== false ) 
			 || ( strpos($this->uri->segment(1), "managebookingtypes") !== false )
			 || ( strpos($this->uri->segment(1), "manageprojects") !== false )
			 || ( strpos($this->uri->segment(1), "manageteams") !== false )
			 || ( strpos($this->uri->segment(1), "manageusers") !== false ) 
			 || ( strpos($this->uri->segment(1), "manageworkplans") !== false )
			 || ( strpos($this->uri->segment(1), "manageaudittrail") !== false ) ) {
	
	$cssActiveApplication = "active";
}
	
?>

<!-- begin: widget_navbar -->
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/"><?php echo $this->config->item("site_name", "momo"); ?></a>
			
			<?php if ( $this->session->userdata('securityservice.user_authenticated') ) : ?>
			
				<ul class="nav pull-right">  
			    	<li class="dropdown">  
			        	<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user icon-light-gray" style="margin-right: 5px;"></span><?php echo $widget_navbar_active_user->getFullName(); ?><b class="caret"></b></a>  
			        	 <ul class="dropdown-menu">
		            		<li><a href="/manageusers/displaysetpasswordform">Change Password</a></li>
		              		<li class="divider"></li>
		              		<li><a href="/security/logout">Logout</a></li>
		            	</ul>
		           </li>
		        </ul> 
	        
				<!-- begin: nav-collapse -->
				<div class="nav-collapse">
					<ul class="nav">
					
						<?php
							// display application menu group if authorized to one of the contained actions
							if ( MenuHelper::activeUserHasAccessToMenuGroup(MenuHelper::KEY_TOPLEVEL_MENU_TIMETRACKER) ) :
						?>
					
							<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_TIMETRACKER, MenuHelper::KEY_ACTION_TIMETRACKER) ) :?>
				       			<li><a href="/timetracker">Timetracker</a></li>
				       		<?php endif; ?>
						
						<?php endif; ?>	
						
						<?php
							// display application menu group if authorized to one of the contained actions
							if ( MenuHelper::activeUserHasAccessToMenuGroup(MenuHelper::KEY_TOPLEVEL_MENU_REPORTS) ) :
						?>
						
							<li class="dropdown <?php echo $cssActiveReports; ?>">
								<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Reports<b class="caret"></b></a>
								<ul class="dropdown-menu">
								
									<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_REPORTS, MenuHelper::KEY_ACTION_DISPLAY_OOSUMMARY) ) :?>
						       			<li><a href="/reports/displayoosummary">Out-of-Office Summary</a></li>
						       		<?php endif; ?>
								
									<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_REPORTS, MenuHelper::KEY_ACTION_DISPLAY_PROJECTTIMEREPORT) ) :?>
						       			<li><a href="/reports/displayprojecttimereport">Project Time</a></li>
						       		<?php endif; ?>
						       		
						       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_REPORTS, MenuHelper::KEY_ACTION_DISPLAY_TIMETRACKERREPORT) ) :?>
						       			<li><a href="/reports/displaytimetrackerreport">Timetracker</a></li>
						       		<?php endif; ?>
	
								</ul>
							</li>
							
						<?php endif; ?>	
						
						<?php
							// display application menu group if authorized to one of the contained actions
							if ( MenuHelper::activeUserHasAccessToMenuGroup(MenuHelper::KEY_TOPLEVEL_MENU_REQUESTS) ) :
						?>
						
							<?php if ( $widget_navbar_access_requests ) : ?>
							
								<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_REQUESTS, MenuHelper::KEY_ACTION_MANAGE_REQUESTS) ) :?>
					       			<li><a href="/managerequests">Requests</a></li>
					       		<?php endif; ?>
							
							<?php endif; ?>
							
						<?php endif; ?>	
						
						<?php
							// display application menu group if authorized to one of the contained actions
							if ( MenuHelper::activeUserHasAccessToMenuGroup(MenuHelper::KEY_TOPLEVEL_MENU_TEAMS) ) :
						?>
							
							<li class="dropdown <?php echo $cssActiveTeams; ?>">
								<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Teams<b class="caret"></b></a>
								<ul class="dropdown-menu">
								
									<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_TEAMS, MenuHelper::KEY_ACTION_MANAGE_BOOKINGS) ) :?>
						       			<li><a href="/managebookings">Manage Out-of-Office</a></li>
						       		<?php endif; ?>	
						       		
									<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_TEAMS, MenuHelper::KEY_ACTION_DISPLAY_TEAM_SUMMARY) ) :?>
						       			<li><a href="/manageteams/displayteamsummary">Summary</a></li>
						       		<?php endif; ?>	
						       		
								</ul>
							</li>
							
						<?php endif; ?>	
						
						<?php
							// display application menu group if authorized to one of the contained actions
							if ( MenuHelper::activeUserHasAccessToMenuGroup(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION) ) :
						?>
							
							<li class="dropdown <?php echo $cssActiveApplication; ?>">
								<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Application<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="javascript:void(0);"><i class="icon-chevron-right" style="float: right;"></i>Manage</a>
								        <ul class="dropdown-menu sub-menu">
								        	
								        	<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_ADJUSTMENTS) ) :?>
								       			<li><a href="/manageadjustments">Adjustments</a></li>
								       		<?php endif; ?>	
								       		
								       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_BOOKINGTYPES) ) :?>
								       			<li><a href="/managebookingtypes">Booking Types</a></li>
								       		<?php endif; ?>	
								       		
								       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_ENTRYTYPES) ) :?>
								       			<li><a href="/manageentrytypes">Entry Types</a></li>
								       		<?php endif; ?>	
								       		
								       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_PROJECTS) ) :?>
								       		<li><a href="/manageprojects">Projects</a></li>
								       		<?php endif; ?>	
								       		
								       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_TEAMS) ) :?>
								       				<li><a href="/manageteams">Teams</a></li>
								       		<?php endif; ?>	
								       		
								       		<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_USERS) ) :?>
								       			 <li><a href="/manageusers">Users</a></li>
								       		<?php endif; ?>		
								       			
								        	<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_WORKPLANS) ) :?>
								       			  <li><a href="/manageworkplans">Workplans</a></li>
								       		<?php endif; ?>	
			       
								        </ul>
								    </li>
								    <?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_MANAGE_AUDITTRAIL) ) :?>	
										<li>
											<a href="/manageaudittrail">Audit Trail</a>
										</li>
									<?php endif; ?>	
									<?php if ( MenuHelper::activeUserHasAccessToAction(MenuHelper::KEY_TOPLEVEL_MENU_APPLICATION, MenuHelper::KEY_ACTION_CHANGE_SETTINGS) ) :?>	
										<li>
											<a href="/managesettings">Change Settings</a>
										</li>
									<?php endif; ?>	
								</ul>
							</li>
							
						<?php endif; ?>	
							
					</ul>
		
				</div> 
				<!-- end: nav-collapse -->
				
			<?php endif; ?>
		</div>
	</div>
</div>
<!-- end: widget_navbar -->
