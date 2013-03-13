<!-- begin: component_form_team -->
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

//
// some preliminary logic with regard to call mode

// determine what action to call
$component_mode == "new" ? $action = "/manageteams/createteam" : $action = "/manageteams/updateteam";

?>


<script type="text/javascript">

	//
	// submits the indicated form
	//
	function submitForm() {
		return isValidForm();
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/manageteams";
	}


	//
	// validates the team form
	//
	// the team form is valid, if
	// 	- it has a name that is at least 5 and less than 50 chars long
	//  - it has at least one designated team leader 	
	//
	function isValidForm() {

		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";

		var nameValue = $.trim($(form).find("input[name=teamName]").val());
		var teamLeaderValues = $("#teamLeaders").val();

		// validate that name field has a value
		if ( nameValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Name</strong> must be completed.";
		}
		// validate that name field is at least 5 chars long
		else if ( nameValue.length < 3 ) {
			validated = false;
			validationMsg += "The field <strong>Name</strong> must be at least 3 characters long.";
		}
		// validate that there is at least one team leader selected
		else if ( teamLeaderValues == null ) {
			validated = false;
			validationMsg += "The field <strong>Team Leaders</strong> must show at least one selection.";
		}
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
	}

	

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		//
		// "primary" and "secondary" memberships are mutually exclusive
		// -- install these event handlers to handle this aspect
		$("#primaryMembers").change(function() {
			//
			// deselect all selected options in "secondaryMembers"
			var valueArray = $(this).val();

			if (valueArray != null) {
				$.each(valueArray, function(index, value) {
					$("#secondaryMembers").find("option[value=" + valueArray[index] + "]").removeAttr("selected");
				});
			}	
		});

		$("#secondaryMembers").change(function() {
			//
			// deselect all selected options in "primaryMembers"
			var valueArray = $(this).val();

			if (valueArray != null) {
				$.each(valueArray, function(index, value) {
					$("#primaryMembers").find("option[value=" + valueArray[index] + "]").removeAttr("selected");
				});
			}	
		});	
	});
	
	

</script>

<div class="row">
	<div class="offset2 span8">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body"> 
	    		
	    		<?php 		
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	
				<form id="form" action="<?php echo $action; ?>" method="post" class="form-horizontal" style="margin-left: 0px;">
					<fieldset>
					
						<div class="control-group">
							<label for="teamName" class="control-label">Name</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $name = $component_edit_target->getName() : $name = "" ?>
								<input type="text" name="teamName" value="<?php echo $name; ?>" class="span3 input-xlarge" maxlength="50" tabindex="1">
							</div>
						</div>
						
						<div class="control-group">
							<label for="parentTeam" class="control-label">Parent Team</label>
							<div class="controls">
								<select name="parentTeam" class="span2" tabindex="2">
									<option value="-1">(none)</option>
									<?php foreach ($component_teams_possible_parents as $curTeam) : ?>
										<?php
											//
											// if we're in edit mode, mark possible parent team as selected
											$selected = "";
											if ($component_mode == "edit") {
												if ( $curTeam->getId() == $component_edit_target->getParentId() ) {
													$selected = 'selected="selected"';
												}	
											}
										?>
										<option value="<?php echo $curTeam->getId(); ?>" <?php echo $selected; ?>> <?php echo $curTeam->getName(); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
	
						<div class="control-group">
							<label for="teamLeaders" class="control-label">Team Leaders</label>
							<div class="controls">
								<select id="teamLeaders" multiple="multiple" name="teamLeaders[]" class="span2" tabindex="3">
								
									<?php foreach ($component_users_teamleaders as $curUser) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
									
											<?php if ( $curUser->isUserTeamLeaderOfTeam($component_edit_target) ) : ?>
												<?php $selected = 'selected="selected"' ?>
											<?php endif; ?>
											 
										<?php endif; ?>
									
										<option value="<?php echo $curUser->getId(); ?>" <?php echo $selected; ?>><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></option>
										
									<?php endforeach; ?>
								
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label for="primaryMembers" class="control-label">Primary Members</label>
							<div class="controls">
								<select id="primaryMembers" multiple="multiple" name="primaryMembers[]" class="span2" tabindex="4">
								
									<?php foreach ($component_users_all as $curUser) : ?>
									
										<?php if ( $component_mode == "edit" ) : ?>
											
											<?php if ( ! $curUser->isUserPrimaryMemberSomeOtherTeam($component_edit_target) ) : ?>	
												
												<?php $selected = "" ?>
												<?php if ( ! $curUser->isUserSecondaryMemberOfTeam($component_edit_target) ) : ?>
												
													<?php if ( $curUser->isUserPrimaryMemberOfTeam($component_edit_target) ) : ?>
													
														<?php $selected = 'selected="selected"'; ?>
													
													<?php endif; ?>
			
												<?php endif; ?>
												
												<option value="<?php echo $curUser->getId(); ?>" <?php echo $selected; ?>><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></option>
													
											<?php endif; ?>
				
										<?php else : ?>
										
											<?php if ( ! $curUser->isUserPrimaryMemberOfSomeTeam() ) : ?>
													
												<option value="<?php echo $curUser->getId(); ?>"><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></option>	
												
											<?php endif; ?>	
												
										<?php endif; ?>
										
									<?php endforeach; ?>
								
								</select>
							</div>
						</div>
						
						
						<div class="control-group">
							<label for="secondaryMembers" class="control-label">Secondary Members</label>
							<div class="controls">
								<select id="secondaryMembers" multiple="multiple" name="secondaryMembers[]" class="span2" tabindex="5">
								
									<?php foreach ($component_users_all as $curUser) : ?>
									
										<?php if ( $component_mode == "edit" ) : ?>
				
											<?php if ( $curUser->isUserSecondaryMemberOfTeam($component_edit_target) ) : ?>
											
												<?php $selected = 'selected="selected"' ?>
											
											<?php else : ?>
											
												<?php $selected = "" ?>
											
											<?php endif; ?>
										
											<option value="<?php echo $curUser->getId(); ?>" <?php echo $selected; ?>><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></option>
																		
										<?php else : ?>	
										
											<option value="<?php echo $curUser->getId(); ?>"><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></option>	
											
										<?php endif; ?>	
									
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
	
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();" tabindex="6">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;" tabindex="7">cancel</button>
						</div>
						
						
						<?php if ( $component_mode == "edit" ) : ?>
							<!-- the id of the edit target -->
							<input type="hidden" name="teamId" value="<?php echo $component_edit_target->getId(); ?>">	
						<?php endif; ?>	
						
					</fieldset>
	
				</form>
			</div>
		</div>
		
    </div>
</div>

<!-- end: component_team_form -->
