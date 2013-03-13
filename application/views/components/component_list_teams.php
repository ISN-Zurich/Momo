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

<!-- begin: component_list_teams -->
<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/manageteams/deleteteam/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/manageteams/displayeditteamform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no requests available
			if ( $component_teams_all->count() == 0 ) :
		?>

			displayAlert("alertBox", "No teams on record.", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

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
	    	
	    		<?php if ( $component_teams_all->count() != 0 ) : ?> 
	    	
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>Team</th>
							    <th>Team Leaders</th>
							    <th>Primary Members</th>
							    <th>Secondary Members</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_teams_all as $team ) :
									//
									// build an overview of member categories for current team
									$primMemberList = "";
									$secMemberList = "";
									$leaderList = "";
									
									$primListInit = true;
									$secListInit = true;
									$leaderListInit = true;
									
									foreach ( $component_users_all as $curUser ) {
										
										if ( $curUser->isUserPrimaryMemberOfTeam($team) ) {
											
											if (! $primListInit) {
												$primMemberList .= ", ";
											}
											
											$primMemberList .= $curUser->getFirstname() . " " . $curUser->getLastname();
											
											$primListInit = false;
										}	
										
									}
									
									foreach ( $component_users_all as $curUser ) {
										
										if ( $curUser->isUserSecondaryMemberOfTeam($team) ) {
											
											if (! $secListInit) {
												$secMemberList .= ", ";
											}
											
											$secMemberList .= $curUser->getFirstname() . " " . $curUser->getLastname();
											
											$secListInit = false;
										}
											
									}
									
									foreach ( $component_users_all as $curUser ) {
										
										if ( $curUser->isUserTeamLeaderOfTeam($team) ) {
											
											if (! $leaderListInit) {
												$leaderList .= ", ";
											}
											
											$leaderList .= $curUser->getFirstname() . " " . $curUser->getLastname();
											
											$leaderListInit = false;
										}
											
									}
									
									//
									// truncate member lists, if too lengthy
									strlen($primMemberList) > 20 ? $primMemberList = substr($primMemberList, 0, 20) . "..." : "";
									strlen($secMemberList) > 25 ? $secMemberList = substr($secMemberList, 0, 25) . "..." : "";
									strlen($leaderList) > 25 ? $leaderList = substr($leaderList, 0, 25) . "..." : "";				
									
								?>		
							    <tr>
								    <td width="15%"><?php echo $team->getName(); ?></td>
								    <td width="20%"><?php print($leaderList != "" ? $leaderList : "(none)"); ?></td>
								    <td width="25%"><?php print($primMemberList != "" ? $primMemberList : "(none)");  ?></td>
								    <td width="25%"><?php print($secMemberList != "" ? $secMemberList : "(none)");  ?></td>
								    <td width="15%">
								    	<a class="btn btn-mini" href="javascript:editEntry(<?php echo $team->getId(); ?>);">edit</a>
								    	<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the team <strong><?php echo $team->getName(); ?></strong> ?', 'javascript:deleteEntry(<?php echo $team->getId(); ?>);', 'delete')">delete</a>
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				    
				<?php endif; ?>     
				    
			    <a class="btn btn-mini" href="/manageteams/displaynewteamform">new</a>
			</div>
		</div>
    </div>
</div>

<!-- end: component_list_teams -->
