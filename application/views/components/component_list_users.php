<!-- begin: component_list_users -->

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

use momo\core\security\Roles;
use momo\core\helpers\FormHelper;

?>

<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/manageusers/deleteuser/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/manageusers/displayedituserform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no user records available
			if ( $component_userlist->count() == 0 ) :
		?>

			displayAlert("alertBox", "There are no user records accessible to you.", "alert-info");
					
		<?php endif; ?>

		// initialize tooltips
		$('.off_day_entry').tooltip();
		$('.team_membership').tooltip();
		
	});
	
</script>


<div class="row">
	<div class="span12">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">
	    	
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php if ( $component_userlist->count() != 0 ) : ?>
	    		    	
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>User</th>
							    <th>Role</th>
							    <th>Teams</th>
							    <th>Off Days</th>
							    <th>Enabled</th>
							    <th style="text-align: right;">Last Login</th>
							    <th></th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
			  
					    	<?php
					    		//
					    		// build list of users
					    	
					    		foreach ( $component_userlist as $curUser ) : 
					    	
						    		//
									// build list of teams for which the user is member
									
									$teamList = null;
					    			
									// primary team
									if ( $curUser->getPrimaryTeam() !== null ) {
										$toolTipText = "primary";
										$teamList = "<u>";
										$teamList .= "<span class='team_membership' data-original-title='" . $toolTipText . "'>";
										$teamList .= $curUser->getPrimaryTeam()->getName();
										$teamList .= "</span></u>";
									}
									
									// secondary teams
									foreach ( $curUser->getSecondaryTeams() as $curTeam ) {
										
										$toolTipText = "secondary";
										
										if ( $teamList !== null ) {
											$teamList .= "<br>";
										}
										
										$teamList .= "<span class='team_membership' data-original-title='" . $toolTipText . "'>";
										$teamList .= $curTeam->getName();
										$teamList .= "</span>";
										
									}
									
									// finishing touches...
									if ( $teamList === null ) {
										// no memberships, set list value accordingly
										$teamList = "(none)";
									}
					    	?>		
					    
							    <tr>
								    <td width="15%"><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></td>
								    <td width="15%"><?php echo Roles::$ROLE_MAP[$curUser->getRole()] ?></td>
								    <td width="20%"><?php echo $teamList; ?></td>
								    <td width="15%">
								    
								    	<?php if ( $curUser->hasOffDays() ): ?> 
								    
									    	<?php foreach ( FormHelper::$workDayOptions as $curWorkDayValue => $curWorkDayText ) : ?>
									    		
									    		<?php if ( $curUser->getDayOffValueForWeekDayNumber($curWorkDayValue) !== null ) : ?>
				
									    			<?php
									    					// prepare class information and tooltip text for off day indicators
									    					if ( $curUser->getDayOffValueForWeekDayNumber($curWorkDayValue) == "full" ) {
									    						$classes = "momo_underline off_day_entry";
									    						$toolTipText = "full day";
									    					}
									    					else if ( $curUser->getDayOffValueForWeekDayNumber($curWorkDayValue) == "half-am" ) {
									    						$classes = "off_day_entry";
									    						$toolTipText = "half day (am)";
									    					}
									    					else if ( $curUser->getDayOffValueForWeekDayNumber($curWorkDayValue) == "half-pm" ) {
									    						$classes = "off_day_entry";
									    						$toolTipText = "half day (pm)";
									    					}
										    		?>
					
										    		<span data-original-title="<?php echo $toolTipText; ?>" class="<?php echo $classes; ?>"><?php echo FormHelper::$workDayOptions[$curWorkDayValue] ?></span>
									    			
									    		<?php endif; ?>
									    
									   		<?php endforeach; ?>
									   		
									   	<?php else : ?>	
									   		
									   		(none)
									   		
									   	<?php endif; ?>	
								    
								    </td>
								    <td width="10%"><?php echo FormHelper::$binaryOptionsYesNo[$curUser->getEnabled()]; ?></td>
								    <td width="13%" style="text-align: right;"><?php print($curUser->getLastLogin() != null ? $curUser->getLastLogin()->format("j M Y - Hi") : "(never)"); ?></td>
								    <td width="2%"></td>
								    <td width="*">
								    	<a class="btn btn-mini" href="javascript:editEntry(<?php echo $curUser->getId(); ?>);">edit</a>
								    	
								    	<?php
								    		// the delete operation is not allowed for active account 
								    		if ( $curUser->getId() != $component_activeuser->getId() ) :
								    	?>
								    		<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the user <strong><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></strong> ?', 'javascript:deleteEntry(<?php echo $curUser->getId(); ?>);', 'delete')">delete</a>
								    	<?php endif; ?>
						
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
			    	</table>
			    	
			    <?php endif; ?>
			     
			    <a class="btn btn-mini" href="/manageusers/displaynewuserform">new</a>
			</div>
			
		</div> 	 
    </div>
</div>

<!-- end: component_list_users -->
