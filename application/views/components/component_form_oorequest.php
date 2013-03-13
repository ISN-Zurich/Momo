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

// determine what action to call
$component_mode == "new" ? $action = "/managerequests/createrequest" : $action = "/managerequests/updaterequest";
	
?>

<script type="text/javascript">

	//
	// submits the indicated form
	//
	function submitForm() {

		var result = false;

		// check if valid form
		if ( result = isValidForm() ) {

			// mark all dates in datepicker multi select boxes as "selected"
			// otherwise they don't show up in post
			$("#halfDaysAM select").find("option").attr("selected", "selected");
			$("#halfDaysPM select").find("option").attr("selected", "selected");
			
		}

		return result;
	}

	
	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/managerequests";
	}


	//
	// validates the workplan form
	//
	// for "book by" = "day" and "day+half-day" the form is valid, if:
	//
	// - "fromDate" and "untilDate" are completed
	// - "fromDate" <= "untilDate"
	//
	function isValidForm() {
		
		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";
		var intRegex = /^\d+$/;

		var typeIdValue		= $(form).find("select[name=typeId]").val();
		var fromDateValue	= $(form).find("input[name=fromDate]").val();
		var untilDateValue	= $(form).find("input[name=untilDate]").val();

		var fromDate = Date.parseExact(fromDateValue, "d-M-yyyy");
		var untilDate = Date.parseExact(untilDateValue, "d-M-yyyy");
		
		// validate that an oo booking type has been chosen (Reason)
		if ( typeIdValue == -1 ) {
			validated = false;
			validationMsg += "<strong>Reason</strong> must be completed.";
		}		
		// validate that "fromDate" is completed
		else if ( fromDateValue == "") {
			validated = false;
			validationMsg += "<strong>From</strong> must be completed.";
		}
		// validate that "utilDate" is completed
		else if ( untilDateValue == "") {
			validated = false;
			validationMsg += "<strong>Until</strong> must be completed.";
		}
		// validate that "fromDate" lies before "untilDate"
		else if ( fromDate.getTime() > untilDate.getTime() ) {
			validated = false;
			validationMsg += "<strong>From</strong> may not lie after <strong>Until</strong>.";
		}
		// validate that the indicated date range does not conflict with an existing request (oo booking) or regularentry (ajax call)
		else if ( 	  (ajaxMessage = hasBookingConflict(<?php echo $component_user_target->getId() ?>, fromDateValue, untilDateValue))
					&& ajaxMessage.hasConflict ) {
			
			validated = false;

			// issue an alert indicating the type of conflict encountered
			if ( ajaxMessage.conflictSource == "oobooking" ) {
				validationMsg += "<p>The request is in conflict with an existing out-of-office entry.</p>";
				validationMsg += "<p>Please check existing out-of-office entries and make appropriate adjustments.</p>";
			}
			else {
				validationMsg += "<p>The request is in conflict with an existing timetracker entry.</p>";
				validationMsg += "<p>Please delete the conflicting timetracker entries and try again.</p>";
			}
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
	// constrains the half-day holidays according to the date range indicated by the "fromDate" and "untilDate" fields
	//
	function constrainHalfDayHolidays(changedPickerName, dateText, inst) {

		//
		// get "from" and "until" date strings
		var fromDate 	= getDatefromDateString($('input[name=fromDate]').val());
		var untilDate 	= getDatefromDateString($('input[name=untilDate]').val());

		//
		// update mindate and maxdate of the half-day holiday datepickers
		$("#halfDaysAM .datePicker").datepicker("option", "minDate", fromDate);
		$("#halfDaysAM .datePicker").datepicker("option", "maxDate", untilDate);

		$("#halfDaysPM .datePicker").datepicker("option", "minDate", fromDate);
		$("#halfDaysPM .datePicker").datepicker("option", "maxDate", untilDate);

		//
		// drop half days that are no longer in indicated "from" - "until" range

		// compute array of AM and PM half-days (as date strings)
		var amHalfDays = $.map( $('#halfDaysAM option'),
                						function(e) { return $(e).val(); } );

		var pmHalfDays = $.map( $('#halfDaysPM option'),
                						function(e) { return $(e).val(); } );

		// process lists of am halfdays, dropping all that are no longer in bounds	
		$.each( amHalfDays,

			 	function(elemIndex, elemValue) {

					var curHalfDayDate = Date.parseExact(elemValue, "d-M-yyyy");

					//
					// test current half day date against "from" - "until" range
					
					// both dates defined, test against full range		
					if ( (fromDate !== null) && (untilDate !== null) ) {

						if ( 		(curHalfDayDate.getTime() < fromDate)
								|| 	(curHalfDayDate.getTime() > untilDate) ) {

							$("#halfDaysAM select").find("option[value=" + elemValue + "]").remove();
							
						}
					}
					// only upper bound defined 
					else if ( (fromDate === null) && (untilDate !== null) ) {

						if ( curHalfDayDate.getTime() > untilDate ) {

							$("#halfDaysAM select").find("option[value=" + elemValue + "]").remove();
							
						}
						
					}
					// only lower bound defined
					else if ( (fromDate !== null) && (untilDate === null) ) {

						if ( curHalfDayDate.getTime() < fromDate ) {

							$("#halfDaysAM select").find("option[value=" + elemValue + "]").remove();
							
						}
						
					}
		 		}
			);


		// process lists of pm halfdays, dropping all that are no longer in bounds	
		$.each( pmHalfDays,

			 	function(elemIndex, elemValue) {

					var curHalfDayDate = Date.parseExact(elemValue, "d-M-yyyy");

					//
					// test current half day date against "from" - "until" range
					
					// both dates defined, test against full range		
					if ( (fromDate !== null) && (untilDate !== null) ) {

						if ( 		(curHalfDayDate.getTime() < fromDate)
								|| 	(curHalfDayDate.getTime() > untilDate) ) {

							$("#halfDaysPM select").find("option[value=" + elemValue + "]").remove();
							
						}
					}
					// only upper bound defined 
					else if ( (fromDate === null) && (untilDate !== null) ) {

						if ( curHalfDayDate.getTime() > untilDate ) {

							$("#halfDaysPM select").find("option[value=" + elemValue + "]").remove();
							
						}
						
					}
					// only lower bound defined
					else if ( (fromDate !== null) && (untilDate === null) ) {

						if ( curHalfDayDate.getTime() < fromDate ) {

							$("#halfDaysPM select").find("option[value=" + elemValue + "]").remove();
							
						}
						
					}
		 		}
			);

	}

	//
	// 	checks whether the current form parametrization leads to a booking conflict
	//
	// 	userId		- the user id for which the check is performed, pass "-1" to indicate no valid user
	//
	function hasBookingConflict(userId, fromDate, untilDate) {

		var ajaxMessage = null;

		var ajaxUrl = "/managebookings/checkbookingconflictjson/" + userId + "/" + encodeURIComponent(fromDate) + "/" + encodeURIComponent(untilDate);
		
		<?php
			// the ajax call also needs to pass the edit target's id so as to
			// exclude the new request's booking dates from the conflict check
			if ( $component_mode == "edit" ) :
		?>

			ajaxUrl += "/" + <?php echo $component_edit_target->getOOBooking()->getId(); ?>
			
		<?php endif; ?>
		
		$.ajax({
			  type: "GET",
			  url: ajaxUrl,
			  async: false,
			  dataType: "json",
			  success: function(data, textStatus, jqXHR) {
				  ajaxMessage = data;
			  }
		});

		return ajaxMessage;
	}

	
	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {

		//
		// set up datepicker instances
		
		// datepicker for "from" date
		$("#fromDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			onSelect: function(dateText, inst) {
				// constrain the half-day datepickers according to the active start/end dates
				constrainHalfDayHolidays("fromDate", dateText, inst);

				// purge possible "untilDate" value if it falls outside new range
				var fromDate 	= getDatefromDateString($('input[name=fromDate]').val());
				var untilDate 	= getDatefromDateString($('input[name=untilDate]').val());

				if ( (fromDate !== null) && (untilDate !== null) ) {
					
					if ( fromDate.getTime() > untilDate.getTime() ) {
						$('input[name=untilDate]').val("");
					}
					
				}

				// constrain "untilDate" according to the selection
				$("#untilDate .datePicker").datepicker("option", "minDate", getDatefromDateString(dateText));
				
			},
			minDate: new Date(	<?php echo $component_date_elems_earliest_request["year"]; ?>,
								<?php echo ($component_date_elems_earliest_request["month"] - 1); ?>,
								<?php echo $component_date_elems_earliest_request["day"]; ?>),
								
			maxDate:  new Date(	<?php echo $component_date_elems_latest_request["year"]; ?>,
								<?php echo ($component_date_elems_latest_request["month"] - 1); ?>,
								<?php echo $component_date_elems_latest_request["day"]; ?>)
		});

		// datepicker for "until" date
		$("#untilDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			onSelect: function(dateText, inst) {
				constrainHalfDayHolidays("untilDate", dateText, inst);
			},

			<?php if ( $component_mode == "new" ) : ?>
			
				minDate: new Date(	<?php echo $component_date_elems_earliest_request["year"]; ?>,
									<?php echo ($component_date_elems_earliest_request["month"] - 1); ?>,
									<?php echo $component_date_elems_earliest_request["day"]; ?>),
						
			<?php else : ?>

				minDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>,
									<?php echo (DateTimeHelper::getMonthFromDateTime($component_edit_target->getOOBooking()->getStartDate()) - 1); ?>,
									<?php echo DateTimeHelper::getDayFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>),
			
			<?php endif; ?>

			maxDate: new Date(	<?php echo $component_date_elems_latest_request["year"]; ?>,
								<?php echo ($component_date_elems_latest_request["month"] - 1); ?>,
								<?php echo $component_date_elems_latest_request["day"]; ?>)
			
		});

		// datepicker for AM half day holidays
		//
		$("#halfDaysAM .datePicker").datepicker({
			dateFormat: "dd-mm-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			onClose: function(dateText, inst) {
				// remove inline property when closing, see "multiSelectDPSelectionHandler()"
			    inst.inline = false;
			},
			onSelect: function(dateText, inst) {
				inst.inline = true;
				// handle the selection
				opmode = multiSelectDPSelectionHandler("halfDaysAM", dateText, inst);

				// if the selection resulted in an add, we need to make sure it is not contained among the "pm half days"
				if (opmode == "add") {
					$("#halfDaysPM select").find("option[value=" + formatDateStringAsValueString(dateText) + "]").remove();
				}
			}
			
			<?php if ( $component_mode == "edit" ) : ?>
	
				,minDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>,
									<?php echo (DateTimeHelper::getMonthFromDateTime($component_edit_target->getOOBooking()->getStartDate()) - 1); ?>,
									<?php echo DateTimeHelper::getDayFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>),
									
				 maxDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($component_edit_target->getOOBooking()->getEndDate()); ?>,
									<?php echo (DateTimeHelper::getMonthFromDateTime($component_edit_target->getOOBooking()->getEndDate()) - 1); ?>,
									<?php echo DateTimeHelper::getDayFromDateTime($component_edit_target->getOOBooking()->getEndDate()); ?>)
			
			<?php endif; ?>
			
		});

		// datepicker PM for half day holidays
		//
		$("#halfDaysPM .datePicker").datepicker({
			dateFormat: "dd-mm-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,

			onClose: function(dateText, inst) {
				// remove inline property when closing, see "multiSelectDPSelectionHandler()"
			    inst.inline = false;
			},
			onSelect: function(dateText, inst) {
				inst.inline = true;
				// handle the selection
				opmode = multiSelectDPSelectionHandler("halfDaysPM", dateText, inst);

				// if the selection resulted in an add, we need to make sure it is not contained among the "am half days"
				if (opmode == "add") {
					$("#halfDaysAM select").find("option[value=" + formatDateStringAsValueString(dateText) + "]").remove();
				}
				
			}
			
			<?php if ( $component_mode == "edit" ) : ?>
			
				,minDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>,
									<?php echo (DateTimeHelper::getMonthFromDateTime($component_edit_target->getOOBooking()->getStartDate()) - 1); ?>,
									<?php echo DateTimeHelper::getDayFromDateTime($component_edit_target->getOOBooking()->getStartDate()); ?>),
									
				 maxDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($component_edit_target->getOOBooking()->getEndDate()); ?>,
									<?php echo (DateTimeHelper::getMonthFromDateTime($component_edit_target->getOOBooking()->getEndDate()) - 1); ?>,
									<?php echo DateTimeHelper::getDayFromDateTime($component_edit_target->getOOBooking()->getEndDate()); ?>)
		
			<?php endif; ?>
		});

		
		//
		// install event handler for changes to oo request type (field labelled "Reason")
		$("#typeId").change(function() {

			// if a type is selected, populate "book by" according to type
			if ( $("#typeId").val() != -1 ) {
			
				// obtain information pertaining to the mode in which entry may be booked
				var typeIsBookableInDays 		= $("#typeId option:selected").data("bookableindays");
				var typeIsBookableInHalfDays 	= $("#typeId option:selected").data("bookableinhalfdays");
				
				if ( typeIsBookableInDays && ( ! typeIsBookableInHalfDays) ) {

					// make "bookable by day" controls visible
					$('#book_by_day_controls').css("display", "block");
					$('#book_by_halfday_controls').css("display", "none");

					// clear contents of half-day controls
					$("#halfDaysAM select option").remove();
					$("#halfDaysPM select option").remove();
					
				}
				else {
					$('#book_by_day_controls').css("display", "block");
					$('#book_by_halfday_controls').css("display", "block");	
				}
				
				// make container for entry type controls visible
				$('#secondary_controls').css("display", "block");

			}
			else {
				$('#secondary_controls').css("display", "none");
			}
			
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

<!-- begin: component_oorequest_form -->
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
							<label for="typeId" class="control-label">Reason</label>
							<div class="controls">
							
								<?php $component_mode == "edit" ? $disabled = "disabled='disabled'" : $disabled = "" ?>
							
								<select id="typeId" name="typeId" class="span3" <?php echo $disabled; ?>>
									<option value="-1">please select...</option>
									
									<?php foreach ( $component_bookingtypes as $curType ) : ?>
									
										<?php 
											// in case of system type, get pretty name
											$typeName = $curType->getType();
											if ( $curType->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
												$typeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$curType->getType()];
											}
										?>							
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
																							
											<?php if ( $component_edit_target->getOOBooking()->getOOBookingType() == $curType ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option data-bookableindays="<?php print( $curType->getBookableInDays() ? "true" : "false"); ?>"
												data-bookableinhalfdays="<?php print( $curType->getBookableInHalfDays() ? "true" : "false"); ?>"
												value="<?php echo $curType->getId(); ?>" <?php echo $selected; ?>>
												
											<?php echo $typeName; ?>
										
										</option>
										
									<?php endforeach; ?>	
									
								</select>
							</div>
						</div>
						
						<?php
						
							// 
							// if mode is "edit", secondary controls and subcontrols are displayed as indicated by type
							$secondaryControlsStyle = "display: none";
							$bookByDayControlsStyle = "display: none";
							$bookByHalfDayControlsStyle  = "display: none";
							
							if ( $component_mode == "edit" ) {
								
								$secondaryControlsStyle = "display: block";
	
								if ( $component_edit_target->getOOBooking()->getOOBookingType()->getBookableInDays() ) {
									$bookByDayControlsStyle = "display: block";
								}
								
								if ( $component_edit_target->getOOBooking()->getOOBookingType()->getBookableInHalfDays() ) {
									$bookByHalfDayControlsStyle = "display: block";
								}
								
							}
						?>
						
						<div id="secondary_controls" style="<?php echo $secondaryControlsStyle; ?>">
						
							<div id="book_by_day_controls" style="<?php echo $bookByDayControlsStyle; ?>">
							
								<div class="control-group">
									<label for="fromDate" class="control-label">From</label>
									<div class="controls">
										<div id="fromDate">
											<?php $component_mode == "edit" ? $fromDate = DateTimeHelper::formatDateTimeToStandardDateFormat($component_edit_target->getOOBooking()->getStartDate()) : $fromDate = "" ?>
											<input type="text" name="fromDate" class="datePicker input-xlarge" value="<?php echo $fromDate; ?>" style="width: 80px;" readonly="readonly">
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<label for="untilDate" class="control-label">Until</label>
									<div class="controls">
										<div id="untilDate">
											<?php $component_mode == "edit" ? $untilDate = DateTimeHelper::formatDateTimeToStandardDateFormat($component_edit_target->getOOBooking()->getEndDate()) : $untilDate = "" ?>
											<input type="text" name="untilDate" class="datePicker input-xlarge uneditable-input" value="<?php echo $untilDate; ?>" style="width: 80px;" readonly="readonly">
										</div>
									</div>
								</div>
								
								<div id="book_by_halfday_controls" style="<?php echo $bookByHalfDayControlsStyle; ?>">
								
									<div class="control-group">
										<div id="halfDaysAM">
											<label for="halfDaysAM[]" class="control-label">Half-Days (AM)</label>
											<div class="controls">
												<select multiple="multiple" name="halfDaysAM[]" class="span2 uneditable-input">
													
													<?php if ( $component_mode == "edit" ) : ?>
														
															<?php foreach ( $component_edit_target->getOOBooking()->getHalfDayAllotmentEntriesAM() as $curEntry ) : ?>
		
																<option value="<?php echo DateTimeHelper::formatDateTimeToStandardDateFormat($curEntry->getDay()->getDateOfDay()); ?>"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat(($curEntry->getDay()->getDateOfDay())); ?></option>
	
															<?php endforeach; ?>
															
													<?php endif; ?>
					
												</select>
												<input class="datePicker" type="hidden">
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<div id="halfDaysPM">
											<label for="halfDaysPM[]" class="control-label">Half-Days (PM)</label>
											<div class="controls">
												<select multiple="multiple" name="halfDaysPM[]" class="span2 uneditable-input">
													
													<?php if ( $component_mode == "edit" ) : ?>
														
														<?php foreach ( $component_edit_target->getOOBooking()->getHalfDayAllotmentEntriesPM() as $curEntry ) : ?>
	
															<option value="<?php echo DateTimeHelper::formatDateTimeToStandardDateFormat($curEntry->getDay()->getDateOfDay()); ?>"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat(($curEntry->getDay()->getDateOfDay())); ?></option>
	
														<?php endforeach; ?>
															
													<?php endif; ?>
					
												</select>
												<input class="datePicker" type="hidden">
											</div>
										</div>
									</div>
									
								</div>
								
							</div>
							
						</div>
	
						<div class="control-group">
							<label for="originatorComment" class="control-label">Comment</label>
							<div class="controls">
								<div id="originatorComment">
								
									<?php
										$originatorComment = "";
										if ( $component_mode == "edit" ) {
											$originatorComment =  $component_edit_target->getOriginatorComment();
										}
									?>
									
									<textarea name="originatorComment" class="input-xlarge" rows="3"><?php echo $originatorComment; ?></textarea>
								</div>
							</div>
						</div>
						
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;">cancel</button>
						</div>
						
						<?php if ( $component_mode == "edit" ) : ?>
							<!-- the id of the edit target -->
							<input type="hidden" name="editTargetId" value="<?php echo $component_edit_target->getId(); ?>">	
						<?php endif; ?>
											
					</fieldset>
				</form>
				
			</div>
		</div>	 	 
    </div>
</div>

<!-- end: component_oorequest_form -->
