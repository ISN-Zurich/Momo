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

?>


<script type="text/javascript">

	//
	// submits the indicated form
	//
	function submitForm() {

		var result = false;

		// check if valid form
		if ( result = isValidForm() ) {

			// mark all dates in datepicker select boxes as "selected"
			// otherwise they don't show up in post
			$("#fullDayHolidays select").find("option").attr("selected", "selected");
			$("#halfDayHolidays select").find("option").attr("selected", "selected");
			$("#oneHourHolidays select").find("option").attr("selected", "selected");
		}

		return result;
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/manageworkplans";
	}


	//
	// validates the workplan form
	//
	// the form is valid, if:
	//	- weekly work hours is completed and integer and non-zero
	//	- vacation tiers 1-3 are completed and integer and non-zero
	//
	function isValidForm() {
		
		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";
		var intRegex = /^\d+$/;

		var weeklyWorkHoursValue 			= $.trim($(form).find("input[name=weeklyWorkHours]").val());
		var annualVacationDaysUpTo19Value 	= $.trim($(form).find("input[name=annualVacationDaysUpTo19]").val());
		var annualVacationDays20to49Value 	= $.trim($(form).find("input[name=annualVacationDays20to49]").val());
		var annualVacationDaysFrom50Value 	= $.trim($(form).find("input[name=annualVacationDaysFrom50]").val());

		// validate that workhours field has a value
		if ( weeklyWorkHoursValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Work Hours</strong> must be completed.";
		}
		// validate that workhours is an integer
		else if ( ! intRegex.test(weeklyWorkHoursValue) || (weeklyWorkHoursValue == 0) ) {
			validated = false;
			validationMsg += "The field <strong>Work Hours</strong> must be a non-zero integer.";
		}
		// validate that vacation tier 1 field has a value
		else if ( annualVacationDaysUpTo19Value == "" ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 1</strong> must be completed.";
		}
		// validate that vacation tier 1 field is an integer
		else if ( ! intRegex.test(annualVacationDaysUpTo19Value) || (annualVacationDaysUpTo19Value == 0) ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 1</strong> must be a non-zero integer.";
		}
		// validate that vacation tier 2 field has a value
		else if ( annualVacationDays20to49Value == "" ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 2</strong> must be completed.";
		}
		// validate that vacation tier 2 field is an integer
		else if ( ! intRegex.test(annualVacationDays20to49Value) || (annualVacationDays20to49Value == 0) ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 2</strong> must be a non-zero integer.";
		}
		// validate that vacation tier 3 field has a value
		else if ( annualVacationDaysFrom50Value == "" ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 3</strong> must be completed.";
		}
		// validate that vacation tier 3 field is an integer
		else if ( ! intRegex.test(annualVacationDaysFrom50Value) || (annualVacationDaysFrom50Value == 0) ) {
			validated = false;
			validationMsg += "The field <strong>Vacation Tier 3</strong> must be a non-zero integer.";
		}
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
		
	}

	//
	// handles the selection events for multiselect datepickers
	//
	// returns the detected opmode
	//
	function multiSelectDPSelectionHandler(blockName, dateText, inst) {

		// get ref to select box associated with datepicker
		var selBox =  $("#" + blockName + " select");

		// track the implied operation mode
		var impliedOpMode = "add";

		//
		// select events need to be treated as follows:
		//	-- add the selected date, if not yet in associated select box
		//	-- remove the selected date, if already in associated select box
	
		// first, try to treat the call as a date removal
		if ( $(selBox).find("option[value=" + formatDateStringAsValueString(dateText) + "]").length != 0 ) {
			// found it, now remove it
			$(selBox).find("option[value=" + formatDateStringAsValueString(dateText) + "]").remove();
			// nope, no longer an add call...
			impliedOpMode = "remove";			
		}

		// if at this point opmode is still add, add the selected date
		if ( impliedOpMode == "add" ) {
			// prettify date string
			var prettyDateText = formatDateStringAsDisplayString(dateText);

			// generate an option element
			var opt = $('<option />').attr('value', formatDateStringAsValueString(dateText)).text(prettyDateText);
			
			// add option to select box
			$(selBox).append(opt);
		}

		return impliedOpMode;
	
	}

	
	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {

		// set up datepicker instances
		//
	
		// datepicker for full day holidays
		//
		$("#fullDayHolidays .datePicker").datepicker({
			dateFormat: "dd-mm-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,

			<?php 
				// for mode "new", set year range to first year in select box
				// for mode "edit" set it to whatever year applies
				
				if ( $component_mode == "new" ) :
			?>

				<?php if ( isset($component_postarray) ) : ?>
				
					minDate: new Date(<?php echo $component_postarray["workPlanYear"]; ?>, 0, 1),
					maxDate: new Date(<?php echo  $component_postarray["workPlanYear"]; ?>, 11, 31),

				<?php else : ?>

					minDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 0, 1),
					maxDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 11, 31),
				
				<?php endif; ?>
					
			<?php else : ?>

				minDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 0, 1),
				maxDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 11, 31),

			<?php endif; ?>
			
			onClose: function(dateText, inst) {
				// remove inline property when closing, see "multiSelectDPSelectionHandler()"
			    inst.inline = false;
			},
			onSelect: function(dateText, inst) {
				// set the datepicker to inline, so as to prevent it from
				// closing once a date is selected (this is datepicker voodoo)
				inst.inline = true;
				
				// handle the selection for this select box
				opmode = multiSelectDPSelectionHandler("fullDayHolidays", dateText, inst);

				// if the selection resulted in an add, we need to make sure it is not contained among the "half-holidays"
				if (opmode == "add") {
					$("#halfDayHolidays select").find("option[value=" + formatDateStringAsValueString(dateText) + "]").remove();
				}
			}
		});

		// datepicker for half day holidays
		//
		$("#halfDayHolidays .datePicker").datepicker({
			dateFormat: "dd-mm-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,

			<?php if ( $component_mode == "new" ) : ?>
			
				<?php if ( isset($component_postarray) ) : ?>
					
					minDate: new Date(<?php echo $component_postarray["workPlanYear"]; ?>, 0, 1),
					maxDate: new Date(<?php echo  $component_postarray["workPlanYear"]; ?>, 11, 31),
		
				<?php else : ?>
		
				minDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 0, 1),
				maxDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 11, 31),
				
				<?php endif; ?>
			
			<?php else : ?>
	
				minDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 0, 1),
				maxDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 11, 31),
	
			<?php endif; ?>

			onClose: function(dateText, inst) {
				// remove inline property when closing, see "multiSelectDPSelectionHandler()"
			    inst.inline = false;
			},
			onSelect: function(dateText, inst) {
				inst.inline = true;
				
				// handle the selection
				opmode = multiSelectDPSelectionHandler("halfDayHolidays", dateText, inst);

				// if the selection resulted in an add, we need to make sure it is not contained among the "half-holidays"
				if (opmode == "add") {
					$("#fullDayHolidays select").find("option[value=" + formatDateStringAsValueString(dateText) + "]").remove();
				}
			}
		});

		// datepicker for one hour worktime reductions
		//
		$("#oneHourHolidays .datePicker").datepicker({
			dateFormat: "dd-mm-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			
			<?php if ( $component_mode == "new" ) : ?>
			
				<?php if ( isset($component_postarray) ) : ?>
				
					minDate: new Date(<?php echo $component_postarray["workPlanYear"]; ?>, 0, 1),
					maxDate: new Date(<?php echo  $component_postarray["workPlanYear"]; ?>, 11, 31),
		
				<?php else : ?>
		
					minDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 0, 1),
					maxDate: new Date(<?php echo $component_next_possible_workplanyear; ?>, 11, 31),
				
				<?php endif; ?>
			
			<?php else : ?>
	
				minDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 0, 1),
				maxDate: new Date(<?php echo $component_edit_target->getYear(); ?>, 11, 31),
	
			<?php endif; ?>

			onClose: function(dateText, inst) {
				// remove inline property when closing, see "multiSelectDPSelectionHandler()"
			    inst.inline = false;
			},
			onSelect: function(dateText, inst) {
				inst.inline = true;
				
				multiSelectDPSelectionHandler("oneHourHolidays", formatDateStringAsValueString(dateText), inst);
			}
		});

		//
		// install event handler for year changes
		//
		// -- changing a year will
		// 		- constrain datepicker to selected year
		//		- clear all selected dates (at present, might want to improve on that)
		//
		$("#workPlanYear").change(function() {

			var selYear = $(this).val();

			// update max/min dates for all pickers
			$("#fullDayHolidays .datePicker").datepicker("option", "minDate", new Date(selYear, 0, 1));
			$("#fullDayHolidays .datePicker").datepicker("option", "maxDate", new Date(selYear, 11, 31));

			// update max/min dates for all pickers
			$("#halfDayHolidays .datePicker").datepicker("option", "minDate", new Date(selYear, 0, 1));
			$("#halfDayHolidays .datePicker").datepicker("option", "maxDate", new Date(selYear, 11, 31));

			// update max/min dates for all pickers
			$("#oneHourHolidays .datePicker").datepicker("option", "minDate", new Date(selYear, 0, 1));
			$("#oneHourHolidays .datePicker").datepicker("option", "maxDate", new Date(selYear, 11, 31));

			// remove all selected dates
			// @TODO would be nicer to update dates instead of removing them
			$("#fullDayHolidays select").find("option").remove();
			$("#halfDayHolidays select").find("option").remove();
			$("#oneHourHolidays select").find("option").remove();

		});

		//
		// install event handler datepicker select box selection changes
		// -- selections within select-box have no meaning, so if one occurs we simply undo it
		$("#fullDayHolidays select").change(function() {
			$("#fullDayHolidays select").find("option").removeAttr("selected");
		});

		$("#halfDayHolidays select").change(function() {
			$("#halfDayHolidays select").find("option").removeAttr("selected");
		});

		$("#oneHourHolidays select").change(function() {
			$("#oneHourHolidays select").find("option").removeAttr("selected");
		});

	});

	//
	// Formats a date string for use as an option value
	//
	function formatDateStringAsValueString(dateString) {
		return getDatefromDateString(dateString).toString("d-M-yyyy");
	}


	//
	// Formats a date string for use as a display value
	//
	function formatDateStringAsDisplayString(dateString) {
		return getDatefromDateString(dateString).toString("d MMM yyyy");
	}

	
	//
	// Returns a Date object for a datepicker date string
	//
	function getDatefromDateString(dateString) {
		// split date string into components (datepicker dates are in format "d-m-yy")
		var dateComps = dateString.split("-");
				
		// return a Date object this (where month needs to be compensated as zero based in Date)
		return new Date(dateComps[2], dateComps[1] - 1, dateComps[0], 0, 0, 0, 0);
	}

</script>

<!-- begin: component_entrytype_form -->
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
	    		
	    		<div class="well momo_bg_lightgray">
	    			<h4>Step 1 of 2 - Parametrize</h4>
	    		</div>
	
				<form id="form" action="/manageworkplans/displayplansummary" method="post" class="form-horizontal" style="margin-left: 0px;">
					<fieldset>
					
						<div class="control-group">
							<label for="workPlanYear" class="control-label">Year</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $workPlanYear = $component_edit_target->getYear() : $workPlanYear = $component_next_possible_workplanyear; ?>
								<input type="text" name="workPlanYear" value="<?php echo $workPlanYear; ?>" class="span1 input-xlarge" readonly="readonly">
							</div>
						</div>
						
						<div class="control-group">
							<label for="weeklyWorkHours" class="control-label">Work Hours</label>
							<div class="controls">
								<div class="input-append">
								
									<?php if ( isset($component_postarray) ) : ?>
									
										<?php $weeklyWorkHours = $component_postarray["weeklyWorkHours"] ?>
									
									<?php else: ?>
									
										<?php $component_mode == "edit" ? $weeklyWorkHours = $component_edit_target->getWeeklyWorkHours() : $weeklyWorkHours = "41" ?>
									
									<?php endif; ?>
	
									<input type="text" name="weeklyWorkHours" value="<?php echo $weeklyWorkHours; ?>" class="span1 input-xlarge" maxlength="2" tabindex="1">
									<span class="add-on">h/week</span>
									<span class="add-on momo_plain_add_on">( full time )</span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="annualVacationDaysUpTo19" class="control-label">Vacation Tier 1</label>
							<div class="controls">
								<div class="input-append">
								
									<?php if ( isset($component_postarray) ) : ?>
									
										<?php $annualVacationDaysUpTo19 = $component_postarray["annualVacationDaysUpTo19"] ?>
									
									<?php else: ?>
									
										<?php $component_mode == "edit" ? $annualVacationDaysUpTo19 = $component_edit_target->getAnnualVacationDaysUpTo19() : $annualVacationDaysUpTo19 = "30" ?>
									
									<?php endif; ?>
		
									<input type="text" name="annualVacationDaysUpTo19" value="<?php echo $annualVacationDaysUpTo19; ?>" class="span1 input-xlarge" maxlength="2" tabindex="2">
									<span class="add-on">days/year</span>
									<span class="add-on momo_plain_add_on">( up to 19 years of age, full time )</span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="annualVacationDays20to49" class="control-label">Vacation Tier 2</label>
							<div class="controls">
								<div class="input-append">
								
									<?php if ( isset($component_postarray) ) : ?>
									
										<?php $annualVacationDays20to49 = $component_postarray["annualVacationDays20to49"] ?>
									
									<?php else: ?>
									
										<?php $component_mode == "edit" ? $annualVacationDays20to49 = $component_edit_target->getAnnualVacationDays20To49() : $annualVacationDays20to49 = "25" ?>
									
									<?php endif; ?>
								
									<input type="text" name="annualVacationDays20to49" value="<?php echo $annualVacationDays20to49; ?>" class="span1 input-xlarge" maxlength="2" tabindex="3">
									<span class="add-on">days/year</span>
									<span class="add-on momo_plain_add_on">( 20 to 49 years of age, full time )</span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="annualVacationDaysFrom50" class="control-label">Vacation Tier 3</label>
							<div class="controls">
								<div class="input-append">
								
									<?php if ( isset($component_postarray) ) : ?>
									
										<?php $annualVacationDaysFrom50 = $component_postarray["annualVacationDaysFrom50"] ?>
									
									<?php else: ?>
									
										<?php $component_mode == "edit" ? $annualVacationDaysFrom50 = $component_edit_target->getAnnualVacationDaysFrom50() : $annualVacationDaysFrom50 = "30" ?>
									
									<?php endif; ?>
	
									<input type="text" name="annualVacationDaysFrom50" value="<?php echo $annualVacationDaysFrom50; ?>" class="span1 input-xlarge" maxlength="2" tabindex="4">
									<span class="add-on">days/year</span>
									<span class="add-on momo_plain_add_on">( from 50 years of age onward, full time )</span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<div id="fullDayHolidays">
								<label for="fullDayHolidays[]" class="control-label">Full Day Holidays</label>
								<div class="controls">
									<select multiple="multiple" name="fullDayHolidays[]" class="span2 uneditable-input" tabindex="5">
									
										<?php if ( isset($component_postarray) ) : ?>
										
											<?php foreach ( $component_postarray["fullDayHolidays"] as $curHoliday ) : ?>
											
												<?php 
													// obtain DateTime representation of current holiday
													$curHolidayAsDateTime = DateTimeHelper::getDateTimeFromStandardDateFormat($curHoliday);
												?>
	
												<option value="<?php echo $curHoliday; ?>"><?php echo $curHolidayAsDateTime->format("j M Y"); ?></option>
			
											<?php endforeach; ?>
										
										<?php else: ?>
										
											<?php if ( $component_mode == "edit" ) : ?>
												
													<?php foreach ( $component_edit_target->getHolidays() as $curHoliday ) : ?>
													
														<?php if ( $curHoliday->getFullDay() ) : ?>
													
															<option value="<?php echo $curHoliday->getDateOfHoliday()->format("j-n-Y"); ?>"><?php echo $curHoliday->getDateOfHoliday()->format("j M Y"); ?></option>
														
														<?php endif; ?>
				
													<?php endforeach; ?>
													
												<?php endif; ?>
										
										<?php endif; ?>
										
									</select>
									<input class="datePicker" type="hidden">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<div id="halfDayHolidays">
								<label for="halfDayHolidays[]" class="control-label">Half Day Holidays</label>
								<div class="controls">
									<select multiple="multiple" name="halfDayHolidays[]" class="span2 uneditable-input" tabindex="6">
	
										<?php if ( isset($component_postarray) ) : ?>
											
											<?php foreach ( $component_postarray["halfDayHolidays"] as $curHoliday ) : ?>
											
												<?php 
													// obtain DateTime representation of current holiday
													$curHolidayAsDateTime = DateTimeHelper::getDateTimeFromStandardDateFormat($curHoliday);
												?>
											
												<option value="<?php echo $curHoliday; ?>"><?php echo $curHolidayAsDateTime->format("j M Y"); ?></option>
			
											<?php endforeach; ?>
											
										<?php else: ?>
											
											<?php if ( $component_mode == "edit" ) : ?>
									
												<?php foreach ( $component_edit_target->getHolidays() as $curHoliday ) : ?>
												
													<?php if ( $curHoliday->getHalfDay() ) : ?>
												
														<option value="<?php echo $curHoliday->getDateOfHoliday()->format("j-n-Y"); ?>"><?php echo $curHoliday->getDateOfHoliday()->format("j M Y"); ?></option>
													
													<?php endif; ?>
			
												<?php endforeach; ?>
												
											<?php endif; ?>
											
										<?php endif; ?>
	
									</select>
									<input class="datePicker" type="hidden">
								</div>
							</div>	
						</div>
						
						<div class="control-group">
							<div id="oneHourHolidays">
								<label for="oneHourHolidays[]" class="control-label">One-Hour Holidays</label>
								<div class="controls">
									<select multiple="multiple" name="oneHourHolidays[]" class="span2 uneditable-input" tabindex="7">
									
										<?php if ( isset($component_postarray) ) : ?>
											
											<?php foreach ( $component_postarray["oneHourHolidays"] as $curHoliday ) : ?>
											
												<?php 
													// obtain DateTime representation of current holiday
													$curHolidayAsDateTime = DateTimeHelper::getDateTimeFromStandardDateFormat($curHoliday);
												?>
											
												<option value="<?php echo $curHoliday; ?>"><?php echo $curHolidayAsDateTime->format("j M Y"); ?></option>
			
											<?php endforeach; ?>
											
										<?php else: ?>
											
											<?php if ( $component_mode == "edit" ) : ?>
										
												<?php foreach ( $component_edit_target->getHolidays() as $curHoliday ) : ?>
												
													<?php if ( $curHoliday->getOneHour() ) : ?>
									
														<option value="<?php echo $curHoliday->getDateOfHoliday()->format("j-n-Y"); ?>"><?php echo $curHoliday->getDateOfHoliday()->format("j M Y"); ?></option>
													
													<?php endif; ?>
			
												<?php endforeach; ?>
												
											<?php endif; ?>
											
										<?php endif; ?>
	
									</select>
									<input class="datePicker" type="hidden">
								</div>
							</div>	
						</div>
						
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();" tabindex="8">next &gt;&gt;</button>&nbsp;&nbsp;&nbsp;&nbsp;
							<button class="btn btn-mini" onclick="cancelForm(); return false;" tabindex="9">cancel</button>
						</div>
						
						<input type="hidden" name="componentMode" value="<?php echo $component_mode; ?>">
						<input type="hidden" name="componentTitle" value="<?php echo $component_title; ?>">	
						
						<!-- issue workplan id if in edit mode -->
						<?php if ( $component_mode == "edit" ) : ?>
							<input type="hidden" name="planId" value="<?php echo $component_edit_target->getId(); ?>">
							<input type="hidden" name="workPlanYear" value="<?php echo $component_edit_target->getYear(); ?>">
						<?php endif; ?>
					
					</fieldset>
				</form>
			</div>
		</div>
		
    </div>
</div>

<!-- end: component_entrytype_form -->
