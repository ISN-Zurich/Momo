
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

?>

<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/manageprojects/deleteproject/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/manageprojects/displayeditprojectform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no requests available
			if ( $component_projects_active->count() == 0 ) :
		?>

			displayAlert("alertBox", "No projects on record.", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

<!-- begin: component_projectlist -->
<div class="row">
	<div class="offset1 span10">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php if ( $component_projects_active->count() != 0 ) : ?>  
	    		
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>Project</th>
							    <th>Assigned Teams</th>
							    <th>Assigned Users</th>
							    <th>Enabled</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_projects_active as $project ) :	
								
									//
									// build an overview of assigned teams and users for current project
									$assignedTeamList = "";
									$assignedUserList = "";
									
									$teamListInit = true;
									$userListInit = true;
									
									foreach ( $component_teams_active as $curTeam ) {
										
										if ( $project->isAssignedTeam($curTeam) ) {
											
											if (! $teamListInit) {
												$assignedTeamList .= ", ";
											}
											
											$assignedTeamList .= $curTeam->getName();
											
											$teamListInit = false;
										}	
									}
									
									foreach ( $component_users_active as $curUser ) {
										
										if ( $project->isAssignedUser($curUser) ) {
											
											if (! $userListInit) {
												$assignedUserList .= ", ";
											}
											
											$assignedUserList .= $curUser->getFirstname() . " " . $curUser->getLastname();
											
											$userListInit = false;
										}	
									}
									
									
									//
									// truncate member lists, if too lengthy
									strlen($assignedTeamList) > 25 ? $assignedTeamList = substr($assignedTeamList, 0, 25) . "..." : "";
									strlen($assignedUserList) > 25 ? $assignedUserList = substr($assignedUserList, 0, 25) . "..." : "";
									
								?>		
							    <tr>
								    <td width="25%"><?php echo $project->getName(); ?></td>
								    <td width="25%"><?php print($assignedTeamList != "" ? $assignedTeamList : "(none)"); ?></td>
								    <td width="25%"><?php print($assignedUserList != "" ? $assignedUserList : "(none)");  ?></td>
								    <td width="10%"><?php print($project->getEnabled() ? "yes" : "no");  ?></td>
								    <td width="15%">
								    	<a class="btn btn-mini" href="javascript:editEntry(<?php echo $project->getId(); ?>);">edit</a>
								    	<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the project <strong><?php echo $project->getName(); ?></strong> ?', 'javascript:deleteEntry(<?php echo $project->getId(); ?>);', 'delete')">delete</a>
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				    
				<?php endif; ?>     
				    
			    <a class="btn btn-mini" href="/manageprojects/displaynewprojectform">new</a>
			</div>
			
		</div>
			 	 
    </div>
</div>

<!-- end: component_projectlist -->
