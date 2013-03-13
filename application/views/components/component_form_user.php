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

use momo\core\helpers\FormHelper;
use momo\core\helpers\DateTimeHelper;

// set up some state variables related to call mode
if ( $component_mode == "new" ) {
	$action = "/manageusers/createuser";
	$editUserId = -1;
}
else {
	$action = "/manageusers/updateuser";	
	$editUserId = $component_edit_target->getId();
}
	
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
		document.location = "/manageusers";
	}


	//
	// validates the user form
	//
	function isValidForm() {

		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";
		var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

		var firstNameValue 		= $.trim($(form).find("input[name=firstName]").val());
		var lastNameValue		= $.trim($(form).find("input[name=lastName]").val());
		var emailValue 			= $.trim($(form).find("input[name=email]").val());
		var birthDateDayValue 	= $.trim($(form).find("select[name=birthDateDay]").val());
		var birthDateMonthValue = $.trim($(form).find("select[name=birthDateMonth]").val());
		var birthDateYearValue 	= $.trim($(form).find("select[name=birthDateYear]").val());
		var typeValue 			= $.trim($(form).find("select[name=type]").val());
		var loginValue 			= $.trim($(form).find("input[name=login]").val());
		var workloadValue 		= $.trim($(form).find("select[name=workload]").val());
		var entryDateValue 		= $.trim($(form).find("input[name=entryDate]").val());
		var exitDateValue 		= $.trim($(form).find("input[name=exitDate]").val());
		var teamIdValue 		= $.trim($(form).find("select[name=teamId]").val());
		var roleValue 			= $.trim($(form).find("select[name=role]").val());

		var entryDate = Date.parseExact(entryDateValue, "d-M-yyyy");
		var exitDate = Date.parseExact(exitDateValue, "d-M-yyyy");
		var birthDate = Date.parseExact(birthDateDayValue + "-" + birthDateMonthValue + "-" + birthDateYearValue, "d-M-yyyy");

		// validate that first name field has a value
		if ( firstNameValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>First Name</strong> must be completed.";
		}
		else if ( lastNameValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Last Name</strong> must be completed.";
		}
		else if ( emailValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Email</strong> must be completed.";
		}
		else if ( ! emailRegex.test(emailValue) ) {
			validated = false;
			validationMsg += "The field <strong>Email</strong> needs to contain a valid email address.";
		}
		else if ( ! isEmailOk(emailValue, <?php echo $editUserId ?> ) ) {
			validated = false;
			validationMsg += "The entered <strong>Email</strong> address is already in use.";
		}
		else if ( 		(birthDateDayValue == -1)
					||	(birthDateMonthValue == -1)
					||	(birthDateYearValue == -1)
				 ) {
			 
			validated = false;
			validationMsg += "The field <strong>Birthdate</strong> must be completed.";
		}
		else if ( birthDate == null ) {
		 
			validated = false;
			validationMsg += "The field <strong>Birthdate</strong> is not set to a valid date.";
		}
		else if ( typeValue == -1 ) {
			validated = false;
			validationMsg += "The field <strong>Type</strong> must be completed.";
		}
		else if ( loginValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Login</strong> must be completed.";
		}
		else if (loginValue.length < 5) {
			validated = false;
			validationMsg += "The field <strong>Login</strong> must be at least 5 characters long.";
		}
		else if ( ! isLoginOk(loginValue, <?php echo $editUserId ?> ) ) {
			validated = false;
			validationMsg += "The entered <strong>Login</strong> is already in use.";
		}
		else if ( workloadValue == -1 ) {
			validated = false;
			validationMsg += "The field <strong>Workload</strong> must be completed.";
		}
		else if ( (typeValue == "<?php echo \User::TYPE_STUDENT; ?>") && (workloadValue != 0.3659) ) {
			validated = false;
			validationMsg += "For users of type <strong>" + "<?php echo \User::$TYPE_MAP[\User::TYPE_STUDENT]; ?>" + "</strong> the workload must be set to 36.59%.";
		}
		else if ( (typeValue == "<?php echo \User::TYPE_STAFF; ?>") && (workloadValue == 0.3659) ) {
			validated = false;
			validationMsg += "For users of type <strong>" + "<?php echo \User::$TYPE_MAP[\User::TYPE_STAFF]; ?>" + "</strong> the workload may not be set to 36.59%.";
		}
		else if ( ! isValidOffDaySelection() ) {
			validated = false;
			validationMsg += "The <strong>Days off</strong> selection must match up with the user's workload.";
		}
		else if ( entryDateValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Entry Date</strong> must be completed.";
		}
		else if ( exitDateValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Exit Date</strong> must be completed.";
		}
		else if ( ! isEmploymentRangeOk(loginValue, entryDateValue, exitDateValue) ) {
			validated = false;
			validationMsg += "The employment period indicated conflicts with existing timetracker and/or out-of-office entries.";
		}
		else if ( exitDate.getTime() <= entryDate.getTime() ) {
			validated = false;
			validationMsg += "The <strong>Entry Date</strong> must lie before the <strong>Exit Date</strong>.";
		}
		<?php 	
				// enforce team assignment if appropriate flag is set  
				if ( $component_flag_must_assign_team ) :
		?>	
					else if ( teamIdValue == -1 ) {
						validated = false;
						validationMsg += "The field <strong>Team</strong> must be completed.";
					}
		
		<?php endif;?>
		
		else if ( roleValue == -1 ) {
			validated = false;
			validationMsg += "The field <strong>Role</strong> must be completed.";
		}
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;


		//
		// determines whether the off days marked match up with the workload chosen
		// i.e. the sum of the off days expressed as a workload percentage, plus the
		// 		workload chosen must equal 100%.
		//
		// this information is used to autmatically mark the user's off-days as "complete"
		// in any given work week.
		//
		// note: at present this is not enforced for type="student"
		//
		function isValidOffDaySelection() {

			var isValid = false;

			if ( typeValue == "<?php echo \User::TYPE_STAFF; ?>" ) {
				// figure out fractional workweek value of all days marked as off days
				var offDaySum = 0;
				$("input[name^=dayoff_]").each( function() {
													
													if ( $(this).attr("checked") ) {
														
														if ( ($(this).val() == "half-am") || ($(this).val() == "half-pm") ) {
															offDaySum += .1;
														}
														else if ( $(this).val() == "full" ) {
															offDaySum += .2;
														}
														
													}
				
												});
	
				// for the selection to be valid, we need the workweek value of
				// the off days and the workload to sum to unity.
				if ( parseFloat(workloadValue) + offDaySum == 1 ) {
					isValid = true;
				}
				
			}
			// the user type does employ automatic off-day marking
			// hence we do not have any restrictions on the off day selection
			else {
				isValid = true;
			}
				
			return isValid;
			
		}

		
	}


	//
	// resets the user's password via ajax call
	//
	function resetPassword(userId) {

		var ajaxMessage = null;
		var result = false;

		displayAjaxSpinner("alertBox");
		
		$.ajax({
			  type: "GET",
			  url: "/manageusers/resetuserpasswordbyidjson/" + encodeURIComponent(userId),
			  async: false,
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  ajaxMessage = data;
			  }
		});

		if ( ajaxMessage.opsuccess ) {
			var msg = "The password has been reset.";
			displayAlert("alertBox", msg, "info");
		}
		else {
			var msg = "The password could not be reset.";
			displayAlert("alertBox", msg, "error");
		}
		
	}
	

	//
	// 	checks whether the login entered is ok.
	//
	//	the login is ok if:
	//		- it is not assigned to another user
	//		- or, it is assigned to the user being edited (applicable to call mode "edit")
	//
	// 	login		- the login to check
	// 	userId		- the user id for which the check is performed, pass "-1" to indicate no valid user
	//
	function isLoginOk(login, userId) {

		var ajaxMessage = null;
		var result = false;
		
		$.ajax({
			  type: "GET",
			  url: "/manageusers/checkloginavailablejson/" + encodeURIComponent(login),
			  async: false,
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  ajaxMessage = data;
			  }
		});

		if ( ajaxMessage.available || ( (userId != -1) && (userId == ajaxMessage.userIdAssignedTo) ) ) {
			result = true;
		}
		
		return result;
	}


	//
	// 	checks whether the email adress entered is ok.
	//
	//	the email address is ok if:
	//		- it is not assigned to another user
	//		- or, it is assigned to the user being edited (applicable to call mode "edit")
	//
	// 	email		- the email address to check
	// 	userId		- the user id for which the check is performed, pass "-1" to indicate no valid user
	//
	function isEmailOk(email, userId) {

		var ajaxMessage = null;
		var result = false;
		
		$.ajax({
			  type: "GET",
			  url: "/manageusers/checkemailavailablejson/" + encodeURIComponent(email),
			  async: false,
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  ajaxMessage = data;
			  }
		});

		if ( ajaxMessage.available || ( (userId != -1) && (userId == ajaxMessage.userIdAssignedTo) ) ) {
			result = true;
		}
		
		return result;
	}

	
	//
	// 	checks whether the indicated employment range is ok
	//
	//	the employment range is ok if it does not cause existing timetracker or out-of-office entries
	//  to fall outside the employment range (can conceivably happen with update operations)
	//
	// 	login			- the login to check
	// 	entryDate		- the proposed start date of the employment period
	//	exitDate		- the proposed end date of the employment period
	//
	function isEmploymentRangeOk(login, entryDate, exitDate) {

		var ajaxMessage = null;
		var result = false;
		
		$.ajax({
			  type: "GET",
			  url: "/manageusers/checkemploymentrangetimetrackerconflictjson/" + encodeURIComponent(login) + "/" + encodeURIComponent(entryDate) + "/" + encodeURIComponent(exitDate),
			  async: false,
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  ajaxMessage = data;
			  }
		});

		if ( ! ajaxMessage.conflict ) {
			result = true;
		}
		
		return result;
	}



	

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {

		//
		// set up datepicker instances
		
		// datepicker for entry date
		$("#entryDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(<?php echo $component_entrydate_min; ?>, 0, 1)
		});

		// datepicker for exit date
		$("#exitDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(<?php echo $component_entrydate_min; ?>, 0, 1)
		});


		//
		// make radios uncheckable
		$("input[type=radio]").uncheckableRadio();

		//
		// register a change event handler for the user "type" control
		$("select[name=type]").change(function() {

			// show the "off day" controls if type is set to "staff"
			if ( $("select[name=type]").val() == "<?php echo \User::TYPE_STAFF?>" ) {
				$("#daysOffControlGroup").css("display", "block");
			}
			// for type student (only other case) hide it
			else {
				$("#daysOffControlGroup").css("display", "none");
			}

		});

	});

</script>

<!-- begin: component_user_form -->
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
							<label for="firstName" class="control-label">First Name</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $firstName = $component_edit_target->getFirstName() : $firstName = "" ?>
								<input type="text" name="firstName" value="<?php echo $firstName; ?>" class="span3 input-xlarge" maxlength="100" tabindex="1">
							</div>
						</div>
						
						<div class="control-group">
							<label for="lastName" class="control-label">Last Name</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $lastName = $component_edit_target->getLastName() : $lastName = "" ?>
								<input type="text" name="lastName" value="<?php echo $lastName; ?>" class="span3 input-xlarge" maxlength="100" tabindex="2">
							</div>
						</div>
						
						<div class="control-group">
							<label for="email" class="control-label">Email</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $email = $component_edit_target->getEmail() : $email = "" ?>
								<input type="text" name="email" value="<?php echo $email; ?>" class="span3 input-xlarge" maxlength="100" tabindex="3">
							</div>
						</div>
						
						<div class="control-group">
							<label for="birthDate" class="control-label">Birthdate</label>
							<div class="controls">
							
								<?php $component_mode == "edit" ? $birthDate = $component_edit_target->getBirthdate() : $birthDate = "" ?>
								
								<select name="birthDateDay" style="width: 60px;" tabindex="4">
									<option value="-1">--</option>
									<?php for ( $dayIndex = 1; $dayIndex <= 31; $dayIndex++ ) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																									
											<?php if ( DateTimeHelper::getDayFromDateTime($birthDate) == $dayIndex ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $dayIndex; ?>" <?php echo $selected; ?>><?php echo $dayIndex; ?></option>
									<?php endfor; ?>
								</select>
								
								<select name="birthDateMonth" style="width: 100px;" tabindex="5">
									<option value="-1">--</option>
									<?php for ( $monthIndex = 1; $monthIndex <= 12; $monthIndex++ ) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																									
											<?php if ( DateTimeHelper::getMonthFromDateTime($birthDate) == $monthIndex ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $monthIndex; ?>" <?php echo $selected; ?>><?php echo DateTimeHelper::getMonthStringFromNumber($monthIndex); ?></option>
									<?php endfor; ?>
									
								</select>
								
								<select name="birthDateYear" class="span1" tabindex="6">
									<option value="-1">--</option>
									<?php for ( $yearIndex = $component_birthyear_max; $yearIndex >= $component_birthyear_min; $yearIndex-- ) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																									
											<?php if ( DateTimeHelper::getYearFromDateTime($birthDate) == $yearIndex ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $yearIndex; ?>" <?php echo $selected; ?>><?php echo $yearIndex; ?></option>
									<?php endfor; ?>
								</select>	
								
							</div>
						</div>
						
						<div class="control-group">
							<label for="type" class="control-label">Type</label>
							<div class="controls">
							
								<?php $component_mode == "edit" ? $disabled = "disabled='disabled'" : $disabled = "" ?>
							
								<select id="type" name="type" class="span2" <?php echo $disabled; ?>  tabindex="7">
									<option value="-1">please select...</option>
									<?php foreach ( $component_types as $curOptVal => $curOptText ) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																							
											<?php if ($component_edit_target->getType() == $curOptVal) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo $curOptText; ?></option>
										
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label for="login" class="control-label">Login</label>
							<div class="controls">
							
								<?php 
										$readonly = "";
										if ( $component_mode == "edit" ) {
											$login = $component_edit_target->getLogin();
											$readonly = "readonly='readonly'";
										} 
										else {
											$login = "";
										}
								?>
	
								<input type="text" name="login" class="span2 input-xlarge" value="<?php echo $login; ?>" maxlength="50" <?php echo $readonly; ?> tabindex="8">
								
							</div>
						</div>
						
						<?php if ( $component_mode == "edit" ) : ?>
						
							<div class="control-group">
								<label for="password" class="control-label">Password</label>
								<div class="controls">
									<button class="btn btn-mini"
											type="button"
											onclick="displayConfirmDialog('alertBox', 'Are you sure you want to reset the password ?', 'javascript:resetPassword(<?php echo $component_edit_target->getId(); ?>);', 'reset')"
											style="margin-top: 3px;"
											tabindex="9">
											
											Reset Password
									</button>
								</div>
							</div>
							
						<?php endif; ?>
						
						<div class="control-group">
							<label for="workload" class="control-label">Workload</label>
							<div class="controls">
							
								<?php $component_mode == "edit" ? $disabled = "disabled='disabled'" : $disabled = "" ?>
							
								<select name="workload" class="span2" <?php echo $disabled; ?> tabindex="10">
									<option value="-1">please select...</option>
									
									<?php foreach ( $component_workloads as $curOptVal) : ?>
										
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																							
											<?php if ( $component_edit_target->getWorkload() == $curOptVal ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo ($curOptVal * 100) . " %"; ?></option>
										
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
						
						<?php 
							// "days off" control group is made visible on load if we're editing a
							// user of type "staff"
							$daysOffControlGroupStyle = "display: none;";
							if ( 	($component_mode == "edit")
								 && ($component_edit_target->getType() == \User::TYPE_STAFF) ) {
								
								$daysOffControlGroupStyle = "display: block;";
							}
						?>
						<div id="daysOffControlGroup" class="control-group" style="<?php echo $daysOffControlGroupStyle; ?>">
							<label class="control-label">Days Off</label>
							
							<div id="daysOff" class="controls">
	
								<?php foreach ( FormHelper::$workDayOptions as $curOptVal => $curOptText ) : ?>
									
									<?php 
											//
											// for edit mode, figure out which offday checkboxes to check
											$dayOffFullChecked = "";
											$dayOffHalfAMChecked = "";
											$dayOffHalfPMChecked = "";
											
											if ( $component_mode == "edit" ) {
												if ( $component_edit_target->getDayOffValueForWeekDayNumber($curOptVal) == "full" ) {
													$dayOffFullChecked = "checked='checked'";
												}
												else if ( $component_edit_target->getDayOffValueForWeekDayNumber($curOptVal) == "half-am" ) {
													$dayOffHalfAMChecked = "checked='checked'";
												}
												else if ( $component_edit_target->getDayOffValueForWeekDayNumber($curOptVal) == "half-pm" ) {
													$dayOffHalfPMChecked = "checked='checked'";
												}
											}
									?>
							
									<div class="radio inline">
										<input tabindex="11" type="radio" value="full" name="dayoff_<?php echo $curOptVal; ?>" <?php echo $dayOffFullChecked; ?>><?php echo $curOptText; ?><br>
										<input tabindex="12" type="radio" value="half-am" name="dayoff_<?php echo $curOptVal; ?>" <?php echo $dayOffHalfAMChecked; ?>><?php echo $curOptText; ?> (am)<br>
										<input tabindex="13" type="radio" value="half-pm" name="dayoff_<?php echo $curOptVal; ?>" <?php echo $dayOffHalfPMChecked; ?>><?php echo $curOptText; ?> (pm)
									</div>
								
								<?php endforeach; ?>
										
							</div>
						</div>
						
						<div class="control-group">
							<label for="entryDate" class="control-label">Entry Date</label>
							<div class="controls">
								<div id="entryDate">
									<?php $component_mode == "edit" ? $entryDate = DateTimeHelper::formatDateTimeToStandardDateFormat($component_edit_target->getEntryDate()) : $entryDate = "" ?>
									<input tabindex="14" type="text" name="entryDate" class="datePicker input-xlarge" value="<?php echo $entryDate; ?>" style="width: 80px;" readonly="readonly">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="exitDate" class="control-label">Exit Date</label>
							<div class="controls">
								<div id="exitDate">
									<?php $component_mode == "edit" ? $exitDate = DateTimeHelper::formatDateTimeToStandardDateFormat($component_edit_target->getExitDate()) : $exitDate = "" ?>
									<input tabindex="15" type="text" name="exitDate" class="datePicker input-xlarge uneditable-input" value="<?php echo $exitDate; ?>" style="width: 80px;" readonly="readonly">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="teamId" class="control-label">Primary Team</label>
							<div class="controls">
							
								<?php if ( $component_flag_may_edit_team ) : ?>
								
									<select name="teamId" class="span2" tabindex="16">
										<option value="-1">(none)</option>
	
										<?php foreach ( $component_teams as $curTeam ) : ?>
										
											<?php $selected = "" ?>
											<?php if ( $component_mode == "edit" ) : ?>
																								
												<?php if ( $component_edit_target->isUserPrimaryMemberOfTeam($curTeam) ) : ?>
													
													<?php $selected = "selected='selected'" ?>
											
												<?php endif; ?>
											
											<?php endif; ?>
										
											<option value="<?php echo $curTeam->getId(); ?>" <?php echo $selected; ?>><?php echo $curTeam->getName(); ?></option>
											
										<?php endforeach; ?>
		
									</select>
								
								<?php else : ?>
								
									<select name="teamId" class="span2" disabled="disabled" tabindex="17">
										<option value="<?php echo $component_edit_target->getPrimaryTeam()->getId(); ?>"><?php echo $component_edit_target->getPrimaryTeam()->getName(); ?></option>
									</select>
							
								<?php endif; ?>
								
							</div>
						</div>
							
						<div class="control-group">
							<label for="role" class="control-label">Role</label>
							<div class="controls">
								<select name="role" class="span2" tabindex="18">
									<option value="-1">please select...</option>
									
									<?php foreach ($component_roles as $curOptVal => $curOptText) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																							
											<?php if ( $component_edit_target->getRole() == $curOptVal ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo $curOptText; ?></option>
										
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label for="enabled" class="control-label">Enabled</label>
							<div class="controls">
								<select id="enabled" name="enabled" class="span1" tabindex="19">
									
									<?php foreach ( FormHelper::$binaryOptionsYesNo as $curOptVal => $curOptText ) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																							
											<?php if ($component_edit_target->getEnabled() == (bool) $curOptVal) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo $curOptText; ?></option>
										
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
						
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();" tabindex="20">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;" tabindex="21">cancel</button>
						</div>
						
						<?php if ( $component_mode == "edit" ) : ?>
							<!-- the id of the edit target -->
							<input type="hidden" name="userId" value="<?php echo $component_edit_target->getId(); ?>">	
						<?php endif; ?>	
						
					</fieldset>
	
				</form>
			</div>
		</div>
		
    </div>
</div>

<!-- end: component_user_form -->
