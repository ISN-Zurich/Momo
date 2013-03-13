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

use momo\core\helpers\DateTimeHelper;
	
?>


<!-- begin: component_other_timetracker -->

<script type="text/javascript">

	// global object used to store transient values pertinent to edit mode
	var editModeTransientValues = { };

	// global flag for active edit
	var activeEdit = false;

	// global flag for active dialog
	var activeDialog = false;

	// the nominal daily worktime in effect
	var nominalDailyWorktimeInSec = <?php echo $timetracker_nominal_daily_worktime_in_sec; ?>;
	
	//
	// set the day's "off day" tag
	//
	function setOffDayTagForDay(weekDayIndex, offDayType) {

		if ( isAllowedSetOffDayTag(weekDayIndex, offDayType) ) {		
			var dayId = $("#dayContainer_" + weekDayIndex).data("dayid");
			document.location = "/timetracker/setoffdaytag/" + dayId + "/" + offDayType;
		}

	}

	//
	// submits the indicated regular entry form
	//
	function saveRegularEntry(formId) {
		return isValidRegularEntry(formId);
	}

	//
	// submits the indicated project entry form
	//
	function saveProjectEntry(formId) {

		var result = false;
		
		if ( result = isValidProjectEntry(formId) ) {

			// copy the normalized form of the entered time value to the
			// appropriate hidden field and submit
			var timeString = $("#" + formId).find("input[name=hours]").val();
			var normalizedTime = normalizeProjectEntryTimeString(timeString);
			
			$("#" + formId).find("input[name=hoursNormalized]").val(normalizedTime);
		}

		return result;
		
	}
	 
	//
	// deletes the indicated regular entry
	//
	function deleteRegularEntry(formId) {

		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];
		
		if ( isAllowedRegularEntryDelete(formId) ) {
			// set action to delete operation
			$("#" + formId).attr("action", "/timetracker/deleteentry");
			$("#" + formId).submit();
		}
		else {
			
			msg = "<p>The entry cannot be deleted due to a conflict with the recorded project time.</p>";
			msg += "<p>Please reduce the recorded project time and try again.</p>";
			
			displayDialog("alertBox_" + weekDay, msg, "javascript:enableAllControls('" + weekDay + "'); purgeAlerts();", "ok", null, null);
		}
	}

	//
	// deletes the indicated project entry
	//
	function deleteProjectEntry(formId) {
		// set action to delete operation
		$("#" + formId).attr("action", "/timetracker/deleteentry");
		$("#" + formId).submit();
	}

	//
	// confirms the regular entry delete operation
	//	
	function confirmRegularEntryDelete(alertBoxId, formId) {

		displayConfirmDialog(
								alertBoxId,
								"Are you sure you want to delete the entry ?",
								"javascript:deleteRegularEntry('" + formId + "');",
								"delete",
								_deleteConfirmDialogCallBack
		);
		
	}

	//
	// confirms the regular entry delete operation
	//	
	function confirmProjectEntryDelete(alertBoxId, formId) {

		displayConfirmDialog(
								alertBoxId,
								"Are you sure you want to delete the entry ?",
								"javascript:deleteProjectEntry('" + formId + "');",
								"delete",
								_deleteConfirmDialogCallBack
		);
		
	}

	//
	// hides and redisplay the "new" controls in tune with the dialog state
	//
	function _deleteConfirmDialogCallBack(alertBoxId, state) {
	
		// figure out weekday of concerned form
		var weekDay = alertBoxId.split('_')[1];

		if ( state == "init" ) {
			disableAllControls(weekDay);
		}
		else {
			enableAllControls(weekDay);
		}
		
	}
	
	//
	// displays the new regular entry form for a given day
	//
	function displayNewRegularEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];

		// make new entry form visible
		$("#" + formId).toggle();

		disableAllControls(weekDay);
		
		// set active edit flag
		activeEdit = true;

		// set focus to first form element
		$("#" + formId).children(":first").focus();
	}

	//
	// displays the new project entry form for a given day
	//
	function displayNewProjectEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];
		
		// display new entry form
		$("#" + formId).toggle();

		// make section divider visible if not in state "fix"
		if ( ! $("#sectionSubdivider_" + weekDay).data("isfix") ) {
			$("#sectionSubdivider_" + weekDay).toggle();
		}
		
		disableAllControls(weekDay);

		// set active edit flag
		activeEdit = true;

		// set focus to first form element
		$("#" + formId).children(":first").focus();
	}

	//
	// cancels a new entry form
	//
	function cancelNewRegularEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];

		enableAllControls(weekDay);
		
		// hide and clear any displayed alerts
		purgeAlerts();

		// reset new entry form
		$("#" + formId).get(0).reset();
		
		// dismiss new regular entry form, redisplay "new" controls
		$("#" + formId).toggle();
		
		// reset active edit flag
		activeEdit = false;
	}

	//
	// cancels a new entry form
	//
	function cancelNewProjectEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];

		enableAllControls(weekDay);

		// hide and clear any displayed alerts
		purgeAlerts();

		// reset new entry form
		$("#" + formId).get(0).reset();
		
		// dismiss new project entry form, redisplay "new" link
		$("#" + formId).toggle();

		// hide section divider visible if not in state "fix"
		if ( ! $("#sectionSubdivider_" + weekDay).data("isfix") ) {
			$("#sectionSubdivider_" + weekDay).toggle();
		}

		// reset active edit flag
		activeEdit = false;
	}

	//
	// enables an existing entry form for edit
	//
	function enableEditExistingEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];

		// clear any displayed alerts
		purgeAlerts();

		// get a reference to the form
		var form = $('#' + formId);

		// enable all contained form elements
		$(form).find("input,select").each(function() {
			$(this).removeAttr("disabled");
		});

		disableAllControls(weekDay);

		//
		// switch controls in edit target to reflect edit mode
		
		// hide standard controls, display edit mode controls
		$(form).find('button[name=control_save]').css("display", "inline-block");
		$(form).find('button[name=control_cancel]').css("display", "inline-block");
		$(form).find('button[name=control_edit]').css("display", "none");
		$(form).find('button[name=control_delete]').css("display", "none");

		// enable edit mode controls
		enableControl($(form).find('button[name=control_save]'));
		enableControl($(form).find('button[name=control_cancel]'));
		
		// squirrel away present form values in case we need to restore later
		editModeTransientValues = {};
		if ( formId.indexOf("existingRegularEntryForm_") != -1) {
			editModeTransientValues.typeId 	= $(form).find('select[name=typeId]').val();
			editModeTransientValues.from 	= $(form).find('input[name=from]').val();
			editModeTransientValues.until 	= $(form).find('input[name=until]').val();
			editModeTransientValues.comment = $(form).find('input[name=comment]').val();
		}
		else {
			editModeTransientValues.projectId 	= $(form).find('select[name=projectId]').val();
			editModeTransientValues.hours 		= $(form).find('input[name=hours]').val();
		}

		// set active edit flag
		activeEdit = true;

		// set focus to first form element
		$("#" + formId).children(":first").focus();

	}

	//
	// cancels the edit of an existing entry form
	//
	function cancelEditExistingEntryForm(formId) {
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];

		// hide and clear displayed alerts
		purgeAlerts();
		
		// get a reference to the form
		var form = $('#' + formId);

		// disable all visible form elements
		$(form).find("input,select").not("input[type=hidden]").each(function() {
			$(this).attr("disabled", "disabled");
		});

		// restore old field values
		if ( formId.indexOf("existingRegularEntryForm_") != -1) {
			$(form).find('select[name=typeId]').val(editModeTransientValues.typeId);
			$(form).find('input[name=from]').val(editModeTransientValues.from);
			$(form).find('input[name=until]').val(editModeTransientValues.until);
			$(form).find('input[name=comment]').val(editModeTransientValues.comment);
		}
		else {
			$(form).find('select[name=projectId]').val(editModeTransientValues.projectId);
			$(form).find('input[name=hours]').val(editModeTransientValues.hours);
		}

		enableAllControls(weekDay);
		
		//
		// switch controls in edit target to reflect standard mode
		
		// hide edit controls, display standard controls
		$(form).find('button[name=control_save]').css("display", "none");
		$(form).find('button[name=control_cancel]').css("display", "none");
		$(form).find('button[name=control_edit]').css("display", "inline-block");
		$(form).find('button[name=control_delete]').css("display", "inline-block");

		// reset active edit flag
		activeEdit = false;
	}

	//
	// disables a given control
	//
	// note all form controls are implemented as <button> instances
	//		
	function disableControl(control) {
		$(control).attr("disabled", "disabled");
	}

	//
	// enables a given control
	//
	// note all form controls are implemented as <button> instances
	//		
	function enableControl(control) {
		$(control).removeAttr("disabled");
	}


	//
	// enables all controls in a given day
	//		
	function enableAllControls(weekDay) {

		// reenable "new entry" buttons
		enableControl($("#newRegularEntryFormControl_" + weekDay + " button"));
		enableControl($("#newProjectEntryFormControl_" + weekDay + " button"));

		// enable "off day" button
		enableControl($("#offDayControl_" + weekDay + " button"));

		// reenable all standard controls
		$("form[id^=existingRegularEntryForm_" + weekDay + "], form[id^=existingProjectEntryForm_" + weekDay + "]").each(function() {
			$(this).find("button[name^=control_edit], button[name^=control_delete]").each(function() {
				enableControl(this);
			});
		});
	}


	//
	// disables all controls in a given day
	//		
	function disableAllControls(weekDay) {

		// disable "new entry" buttons
		disableControl($("#newRegularEntryFormControl_" + weekDay + " button"));
		disableControl($("#newProjectEntryFormControl_" + weekDay + " button"));

		// disable "off day" button
		disableControl($("#offDayControl_" + weekDay + " button"));

		// disable all controls
		$("form[id^=existingRegularEntryForm_" + weekDay + "], form[id^=existingProjectEntryForm_" + weekDay + "]").each(function() {
			
			$(this).find("button[name^=control]").each(function() {
				disableControl(this);
			});
			
		});
	
	}

	//
	// determines whether it's okay to delete a given regular entry
	//
	// a delete operation may proceed, if it does not cause the worktime-credit to
	// underrun the recorded project time
	//		
	function isAllowedRegularEntryDelete(formId) {

		var isAllowed = true;
		
		// figure out weekday of concerned form
		var weekDay = formId.split('_')[1];
		
		// if the entry has worktime credit, we need to check the indicated constraints
		if ( $("#" + formId).data("entryhasworktimecredit") ) {

			// get the pertinent data from the form data attributes
			var daysTotalWorktime = $("#" + formId).data("daystotalworktime");
			var daysTotalProjectTime = $("#" + formId).data("daystotalprojecttime");
			var entryTimeInterval = $("#" + formId).data("entrytimeinterval");

			// check, if removing the entry will underrun logged project time
			if ( (daysTotalWorktime - entryTimeInterval) < daysTotalProjectTime ) {
				isAllowed = false;
			}

		}
		
		return isAllowed;
		
	}


	//
	// determines whether it's okay to set the "off-day" tag on the indicated day
	//
	// a day may always be set to "half off-day", whereas a day may be toggled to "off day"
	// only if it contains no entries
	//
	function isAllowedSetOffDayTag(weekDayIndex) {

		var isAllowed = true;

		// obtain day's current "complete" state
		var curDayFullOff = $("#dayContainer_" + weekDayIndex).data("isfulloff");
		
		// obtain existing "regular entry" forms for the indicated weekday
		var existingRegularEntryForms = $("form[id^=existingRegularEntryForm_" + weekDayIndex + "]");

		// we only need to check constraints if current state is not "full off"
		if ( ! curDayFullOff ) {

			// if there are existing regular entries, we may not proceed with state change
			if ( $(existingRegularEntryForms).size() > 0 ) {
				
				isAllowed = false;					
				msg = "<p>Please delete the existing entries prior to marking the day as an off day.</p>";

				displayDialog("alertBox_" + weekDayIndex, msg, "javascript:purgeAlerts();", "ok", null, null);
			}
		}

		return isAllowed;
		
	}
	

	//
	// validates a project entry
	//
	// a project entry is valid, if:
	//
	//		- "hours" is completed
	//		- "hours" matches one of the regular expressions: /^\d{1,2}[.:,]\d{1,2}$/ or /^\d{1,2}$/ or /^\d{1,2}:\d{2}$/
	//		- "hours" does not evaluate to zero
	//		- the sum of the new project entry's time and the existing project entries'
	//		  times does not exceed the day's logged worktime credit
	//		
	function isValidProjectEntry(formId) {

		// get a reference to new project entry form (the validation target)  and regular entry forms
		
		var newProjectEntryForm = $("#" + formId);
		
		var validated = true;
		var validationMsg = "";
		var hoursRegexFormat_1 = /^\d{1,2}[.,]\d{1,2}$/;
		var hoursRegexFormat_2 = /^\d{1,2}$/;
		var hoursRegexFormat_3 = /^\d{1,2}:\d{2}$/;

		var newProjectTimeInHoursValString = $.trim($(newProjectEntryForm).find("input[name=hours]").val());
	
		if ( newProjectTimeInHoursValString == "" ) {
			validated = false;
			validationMsg += "Please enter a time value.";
		}
		else if ( ! ( 		
							hoursRegexFormat_1.test(newProjectTimeInHoursValString)
						|| 	hoursRegexFormat_2.test(newProjectTimeInHoursValString) 
						|| 	hoursRegexFormat_3.test(newProjectTimeInHoursValString) 
					) 
				) {
			
			validated = false;
			validationMsg += "The time value must be specified as a decimal fraction (with leading zero) or in <strong>hh:mm</strong> format.";
		}
		else if ( 		
						 isNaN(normalizeProjectEntryTimeString(newProjectTimeInHoursValString)) 
					||	(normalizeProjectEntryTimeString(newProjectTimeInHoursValString) == 0)
				) {

			validated = false;
			validationMsg += "The entered time value is invalid.";
			
		}
		else {
			//
			// the time value is valid, now see wheter we have enough worktime credit
			// to log it
			
			// get the pertinent data from the form data attributes
			var daysTotalWorktimeInSec = $(newProjectEntryForm).data("daystotalworktime");
			var daysTotalProjectTimeInSec = $(newProjectEntryForm).data("daystotalprojecttime");

			// "entryTimeIntervalInSec" reflects the form's time interval at load time
			// this is only available if we're validating in the context of an edit
			// test for and set the variable accordingly
			var entryTimeIntervalInSec = 0;
			if ( $(newProjectEntryForm).data("entrytimeinterval") != undefined ) {
				entryTimeIntervalInSec = $(newProjectEntryForm).data("entrytimeinterval");
			}
			
			var newEntryTimeIntervalInSec = normalizeProjectEntryTimeString(newProjectTimeInHoursValString) * 3600;
			
			if ( (daysTotalProjectTimeInSec - entryTimeIntervalInSec + newEntryTimeIntervalInSec) > daysTotalWorktimeInSec ) {
				validated = false;
				validationMsg = "<p>The entry cannot be processed as there is not enough recorded work-time.</p>";
				validationMsg += "<p>Please reduce the project time, or increase the recorded work-time, and try again.</p>";
			}

		}
		
		if ( ! validated ) {
			// figure out weekday we are to display the alert for
			var weekDay = formId.split('_')[1];

			displayAlert("alertBox_" + weekDay, validationMsg, "error");
		}
		
		return validated;

	}

	// 
	// returns the float equivalent of the time value entered into a project entry form
	//
	// if the entered time value is deemed invalid, the function returns "NaN"
	//
	function normalizeProjectEntryTimeString(timeString) {
		
		var result = Number.NaN;

		// process according to format of the time string
		if ( timeString.indexOf(",") != -1 ) {
			result = parseFloat(timeString.replace(",", "."));
		}
		else if ( timeString.indexOf(":") != -1 ) {
			
			var timeComponents = timeString.split(":");
	
			if ( parseFloat(timeComponents[1]) <= 59 ) {
				result = parseFloat(timeComponents[0]) + parseFloat((timeComponents[1] / 60));
			}
		}
		else {
			result = parseFloat(timeString);
		}

		return result;
	}


	//
	// validates a regular entry
	//
	// a regular entry is valid, if:
	//
	//		- the entered values match regex /^\d\d[0-5]\d$/
	//		- from < until
	//		- the timerange defined by from and until does not overlap that of an existing entry
	//		- the resulting worktime is not less than the recorded project time (possible when editing a regular entry)
	//
	function isValidRegularEntry(formId) {

		var entryForm = $("#" + formId);
		
		var validated = true;
		var validationMsg = "";
		var validTimeStringRegex = /^\d\d[0-5]\d$/;
		
		var newFromValString 		= $.trim($(entryForm).find("input[name=from]").val());
		var newUntilValString 		= $.trim($(entryForm).find("input[name=until]").val());

		// explictly cast to numeric value
		newFromVal 	= parseInt(newFromValString, 10);
		newUntilVal = parseInt(newUntilValString, 10);
		
		// validate that time string is of valid format
		if ( ( ! validTimeStringRegex.test(newFromValString)) ||  ( ! validTimeStringRegex.test(newUntilValString)) ) {
			validated = false;
			validationMsg += "Time values need to be specified in <strong>hhmm</strong> format";
		}		
		// validate that from < until, but only if both carry values
		else if ( parseInt(newFromValString, 10) >= parseInt(newUntilValString, 10) ) {
			validated = false;
			validationMsg += "<strong>From</strong> needs to lie before <strong>Until</strong>";
		}
		// make sure resulting worktime does not conflict with recorded project time
		else if ( hasProjectTimeConflict() ) {
			validated = false;
			validationMsg = "<p>The entered time range causes a conflict with the recorded project time.</p>";
			validationMsg += "<p>Please increase the entered time range, or decrease the recorded project-time, and try again.</p>";
		}
		// finally, ensure that time interval defined by from/until does not overlap an already existing one
		else {
			// figure out weekday of form we're validating
			var formIdSegments 	= formId.split("_");
			var weekDay 		= formIdSegments[1];

			// get a reference to the existing entry forms of the weekDay that the new entry belongs to
			var existingEntryForms = $("form[id^=existingRegularEntryForm_" + weekDay + "]");

			// loop over each existing entry and ensure that new entry does not conflict
			// -- to do this, we check whether the new entry overlaps or intersects any of the existing entries
			//
			$.each(existingEntryForms, function() {

				// when processing existing entry forms, the edit target itself will be contained in the jquery collection
				// -- we skip it, if it pops up
				
				if ( entryForm.attr("id") != this.id ) {

					// get from/until for current entry
					var existingFromVal = $(this).find("input[name=from]").val();
					var existingUntilVal = $(this).find("input[name=until]").val();
	
					// explictly cast to numeric value
					existingFromVal 	= parseInt(existingFromVal, 10);
					existingUntilVal 	= parseInt(existingUntilVal, 10);
	
					// check for overlap and intersect
					if ( 		( (newFromVal <= existingFromVal) && (newUntilVal >= existingUntilVal) )
							||	( (newFromVal > existingFromVal) && (newFromVal < existingUntilVal) )
							||	( (newUntilVal > existingFromVal) && (newUntilVal < existingUntilVal) ) ) {

						validated = false;
						validationMsg += "The entered time range conflicts with an existing one";
	
						// break out of jquery loop
						return false;					
					}
				}
			});
		}
		
		if ( ! validated ) {
			// figure out weekday we are to display the alert for
			var weekDay = formId.split('_')[1];

			displayAlert("alertBox_" + weekDay, validationMsg, "error");
		}

		return validated;


		// 
		// checks whether the indicated entry's time values sum to a value larger
		// or equal to the recorded project time
		function hasProjectTimeConflict() {

			var result = false;
			
			// get the pertinent data from the form data attributes
			
			var daysTotalWorktimeInSec = $(entryForm).data("daystotalworktime");
			var daysTotalProjectTimeInSec = $(entryForm).data("daystotalprojecttime");
			var presentlyEntryTypeAwardsWorktimeCredit = $(entryForm).find("select[name=typeId]").find(":selected").data("worktimecreditawarded");
			var entryTypeWasLoadedAwardingWorktimeCredit = $(entryForm).data("entryhasworktimecredit");
			
			// if the entry type was loaded as awarding worktime credit, we need to use the old time value in the calculation
			// of the entry's new total worktime
			var oldEntryTimeIntervalInSec = 0;
			if ( entryTypeWasLoadedAwardingWorktimeCredit ) {
				var oldEntryTimeIntervalInSec = $(entryForm).data("entrytimeinterval");
			}

			// if the entry type presently awards worktime credit, we need to use the entered time value in the calculation
			// of the entry's new total worktime
			var newEntryTimeIntervalInSec = 0;
			if ( presentlyEntryTypeAwardsWorktimeCredit ) {
				newEntryTimeIntervalInSec = ($("#" + formId).find("input[name=until]").val() -  $("#" + formId).find("input[name=from]").val()) * (3600 / 100);
			}

			// we have a conflict, if the newly resulting worktime for the day underruns the total project time
			if ( (daysTotalWorktimeInSec - oldEntryTimeIntervalInSec + newEntryTimeIntervalInSec) < daysTotalProjectTimeInSec ) {
				result = true;
			}

			return result;

		}

	}


	//
	// opens an accordion section
	//
	function openAccordionSection(selectedSection) {

		// open section only, if we don't have an active edit or dialog
		if ( ! hasActiveAlert() ) {

			// no action if the selected section is already open
			if ( ! $(selectedSection).parent().parent().next("div.momo_section_content.accordion").hasClass("current")) {
				//
				// find currently open day section and close it
				$('div.momo_section_content.accordion.current').slideUp('fast').toggleClass('current');
				
				// slide down the day section that was clicked and mark it as 'current'
				$(selectedSection).parent().parent().next("div.momo_section_content.accordion").slideDown('fast').toggleClass('current');
			}
		}
		
	}


	//
	// checks if there is an active alert pending
	//
	// if so, the user is instructed, accordingly and the function returns "true"
	//
	function hasActiveAlert() {
		
		var activeAlert = false;

		if ( (activeEdit) || (activeDialog) ) {
			
			 activeAlert = true;

			 if (activeEdit) {
				// if it's an active edit, we issue an appropriate alert message
				var msg = "You need to complete (or cancel) the active operation prior to switching days.";
				displayAlert($('div.momo_section_content.accordion.current').find('.alert').attr("id"), msg, "notice");
			}
			else {
				pulsateVisibleAlerts();
			}

		}

		return activeAlert;
	}	

	//
	// various setup tasks that need the DOM fully loaded
	//
	$(function() {

		// render tooltips
		$('.tooltip_totaldaytime').tooltip();
		$('.tooltip_totaldayworktime').tooltip();
		
		//
		// install accordion type slider for the day sections
		//
		$('div.momo_section_header a').click(function() {
			
			// open the section
            openAccordionSection(this);

			// return false so as not to follow the link (just in case)
            return false;  
        });    
		
		//
		// event handler for "keypress" event for input "from" and "until" elements
		// -- the handler prevents non-numeric entries to those fields
		//
		$("form").find("input[name=from],input[name=until]").keypress(function(e) {
		    var a = [];				// array of allowable keycodes
		    var k = e.which;		// the pressed key

		    // add keycodes for numeric keys
		    for ( i = 48; i < 58; i++ ) {
		    	a.push(i);
		    }

		    // add keycodes for backspace and tab
		    a.push(8);
		    a.push(0);

		    // prevent anything that isn't contained in keycode array
		    if ( !(a.indexOf(k) >= 0) ) {
		       e.preventDefault();
		    }
		});

		
		//
		// event handler for "onblur" event for input "from" and "until" elements
		//
		// -- the handler rounds rejects invalid element time values and ensures that
		//	  valid ones are rounded to the nearest 5 minute interval, *unless* the entered
		//	  time happens to result in a total time that corresponds to the nominal daily
		//	  worktime
		//
		//    note: - valid entries are in the format HHMM
		//			- makes use of "datejs", an extension library to the JS Date object
		//
		$("form").find("input[name=from],input[name=until]").blur(function(e) {

			// obtain field value
			var enteredVal = e.target.value; 

			// skip, if not a valid entry (i.e., 4 digits long)
			if ( enteredVal.length == 4 ) {

				// ensure that the entered value is a valid time
				if ( (enteredVal >= 0) && (enteredVal <= 2400) ) {

					var enforceFiveMinuteIncrements = true;
					
					// if we're dealing with an "until" value, we test the result for compliance with the
					// nominal daily time
					if ( $(this).attr("name") == "until" ) {

						// obtain the total time currently recorded to day
						var dayTotalTimeInSec = $(this).closest("form").data("daystotaltime");

						// obtain entry's old time value
						var entrysOldTimeValueInSec = $(this).closest("form").data("entrytimeinterval");
						
						// id of containing form
						var containingFormId = $(this).closest("form").attr("id");

						// get "from" and "until" values
						var fromValue = $(this).prev().val();
						var untilValue = $(this).val();
						
						// parse these to normalized JS date objects
						var fromDateRep = Date.parseExact("1-1-2000 " + fromValue, "d-M-yyyy HHmm");
						var untilDateRep = Date.parseExact("1-1-2000 " + untilValue, "d-M-yyyy HHmm");

						// compute time value in seconds this represents
						var newEntryTimeIntervalInSec = (untilDateRep.getTime() - fromDateRep.getTime()) / 1000;

						// if the already recorded time plus the new entry's time equal
						// the nominal daily time, we accept the value without rounding
						
						// computation needs to proceed sligthly differently if we're editing an
						// existing value as opposed to adding a new value
						if ( containingFormId.indexOf("newRegularEntryForm") != -1 ) {
							// this is a new entry, so we test with the new entry time value added to the day's presentls recorded time
							if ( (dayTotalTimeInSec + newEntryTimeIntervalInSec) == nominalDailyWorktimeInSec ) {
								enforceFiveMinuteIncrements = false;
							}
						}
						else {
							// this is an existing entry, so we test with the new entry time value added to the day's presently recorded time minus
							// the entry's old time value
							if ( (dayTotalTimeInSec + newEntryTimeIntervalInSec - entrysOldTimeValueInSec) == nominalDailyWorktimeInSec ) {
								enforceFiveMinuteIncrements = false;
							}
						}
						
					}
					
					//
					// if indicated, enforce 5-min increments
					if ( enforceFiveMinuteIncrements && ( (enteredVal % 5) != 0 ) ) {
						// figure out distance from nearest 5 min interval
						var nearestIntervalDistance = (enteredVal % 5) >= 3 ? 5 - (enteredVal % 5) : -(enteredVal % 5);

						// adjust (round) the entered value accordingnly
						var enteredTime = new Date(2000, 1, 1, enteredVal.substring(0, 2), enteredVal.substring(2), 0);
						var roundedTime = enteredTime.addMinutes(nearestIntervalDistance);

						// extract hours and minutes from the Date object and write value back to input field
						var newHours 	= (new String(roundedTime.getHours()).length == 1) ? "0" + new String(roundedTime.getHours()) :  new String(roundedTime.getHours());
						var newMinutes 	= (new String(roundedTime.getMinutes()).length == 1) ? "0" + new String(roundedTime.getMinutes()) : new String(roundedTime.getMinutes());
						
						e.target.value = newHours + newMinutes;
					}	
				}
				else {
					// invalid time value, reset field
					e.target.value = "";
				}
			}
		});
	});

</script>


<?php 
	//
	// figure out header elements according to whether we're
	// displaying active user's timetracker or not
	
	// change header color, and in case this is not the active user's timetracker
	$timeTrackerHeaderClass = "";
	if ( ! $timetracker_viewing_own_timetracker ) {
		$timeTrackerHeaderClass = "momo_bg_red";
	}
	
?>

<div class="momo_panel">

	<div class="momo_panel_header <?php echo $timeTrackerHeaderClass; ?>">
		
		<h4 style="float: left;">
			<?php echo $timetracker_title; ?> 
		</h4>
		
		<?php 
			// render "dismiss" button in case this is not the active user's timetracker
			if ( ! $timetracker_viewing_own_timetracker ) :
			
				// figure out revert url
				$timetrackerRevertUrl = "/timetracker/displayforuser/" . DateTimeHelper::getDayFromDateTime($timetracker_today_date);
				$timetrackerRevertUrl .= "/" . DateTimeHelper::getMonthFromDateTime($timetracker_today_date);
				$timetrackerRevertUrl .= "/" . DateTimeHelper::getYearFromDateTime($timetracker_today_date);
				$timetrackerRevertUrl .= "/" . $timetracker_activeuser->getId();
		?>
		
			<div style="float: right; margin-right: 15px;">
				<a class="btn btn-mini" href="<?php echo $timetrackerRevertUrl; ?>">dismiss</a>
			</div>
			
		<?php endif; ?>
	</div>
	
	<div class="well momo_panel_body" style="margin-bottom: 0px;">  
	
	<?php
		//
		// render the timetracker
		//
	
		$firstEntryFlag = true;
		
		// process each day aggregrate contained in week data
		foreach ( $timetracker_week_data as $curDayAggregate ) :
		
			$curDayDate = $curDayAggregate["day"]->getDateOfDay();	
			$weekDayIndex = $curDayAggregate["weekday_index"];		
	?>
		
		<div class="row">
		
			<div class="span8" style="width: 830px;">
			
				
				<!-- 
					the day container wraps all the elements making up the day
					additionally, it is used to store state data pertaining to the entire day
				 -->
				<div 	id="dayContainer_<?php echo $weekDayIndex; ?>"
						data-isfulloff="<?php echo $curDayAggregate['day_is_full_off']; ?>"
						data-dayid="<?php echo $curDayAggregate['day']->getId(); ?>" >
			
					<?php 
						//
						// figure out css classes that apply to day's header and body sections
						$sectionHeaderClasses = "momo_section_header";	
					
						// add a spacer to the header div if we're not rendering the first entry 	
						if ( $firstEntryFlag ) {
							$firstEntryFlag = false;
						}
						else {
							$sectionHeaderClasses .= " momo_top_spacer20";
						}
						
						// determine the day's header class color
						if ( 	$curDayAggregate["day"]->getWeekDayName() == "Sat"
							 || $curDayAggregate["day"]->getWeekDayName() == "Sun" ) {
							
							 //
							 // we're rendering a weekend day, the header is red in color	
							 $sectionHeaderClasses .= " momo_bg_lightred_crosshatched";		 	
						}
						else if ( $curDayDate == $timetracker_today_date ) {
							//
							// we're rendering the current day, the header is light blue
							$sectionHeaderClasses .= " momo_bg_lightblue today";
						}
						else {
							// in all other cases, the header is light gray
							$sectionHeaderClasses .= " momo_bg_lightgray";
						}
					
						// if we're rendering the selected date ($selectedDate), the body is expanded, i.e. of class "current"
						if ( $curDayDate == $timetracker_selected_date ) {
							$sectionBodyClasses = "momo_section_content accordion current";
						}
						else {
							$sectionBodyClasses = "momo_section_content accordion";	
						}
					?>
					
					<!-- 
						the day's section header
							- we display the date as well as various status icons here
							- the header block (.momo_section_header) is matched with a content block (.momo_section_content) with the content block being
						 	  collapsible, accordion style
					-->
					<div class="<?php echo $sectionHeaderClasses; ?>">
					
						<h4 style="float: left;">
							<a href="#"><?php echo DateTimeHelper::formatDateTimeToLongformPrettyDateFormat($curDayDate);?></a>
						</h4>
						
						<h5 style="float: right;">
							<span data-original-title="total time reported" class="tooltip_totaldaytime">
								<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDayAggregate["total_time_credit"]); ?> h
							</span>
							<span data-original-title="total work-time reported" class="tooltip_totaldayworktime">
								(<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDayAggregate["regularentry_worktime_credit"]); ?> h)
							</span>
						</h5>
						
						<?php if ($curDayAggregate["day_islocked"]) :?>
							<div style="float: right; margin-right: 10px; margin-top: 1px;" class="icon-lock icon-light-gray"></div>
						<?php endif; ?>
						
					</div>
					
					<!-- 
						the day's content block
					-->	
					<div class="<?php echo $sectionBodyClasses; ?>">
					
						<?php 
							//
							// compile subheader content for current day
	
							//
							// holiday
							$holidayNotificationToRender = false;
	
							if ( $curDayAggregate["day_holiday"] !== null ) {
								
								$holidayNotificationToRender = true;
								
								// build notification string according to type
								if ( $curDayAggregate["day_holiday"]->getFullDay() ) {
									$holidayNotification = "Today is a <strong>full-day</strong> holiday.";
								}
								else if ( $curDayAggregate["day_holiday"]->getHalfDay() ) {
									$holidayNotification = "This afternoon is a <strong>half-day</strong> holiday.";
								}
								else if ( $curDayAggregate["day_holiday"]->getOneHour() ) {
									$holidayNotification = "Work time is reduced by <strong>one hour</strong> today.";
								}
							}
							
							//
							// "off" day
							$offDayNotificationToRender = false;
							
							if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_DAY_OFF_FULL ) {
								$offDayNotificationToRender = true;
								$offDayNotification = "This is marked as an <strong>off-day</strong>";
							}
							else if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_HALF_DAY_OFF_AM ) {
								$offDayNotificationToRender = true;
								$offDayNotification = "This morning is marked as an <strong>off half-day</strong>";
							}
							else if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_HALF_DAY_OFF_PM ) {
								$offDayNotificationToRender = true;
								$offDayNotification = "This afternoon is marked as an <strong>off half-day</strong>";
							}
							
							//
							// out-of-office entry
							$ooNotificationToRender = false;
	
							if ( $curDayAggregate["oo_entry"] !== null ) {
	
								$ooNotificationToRender = true;
								
							    // build notification string according to type
								if ( $curDayAggregate["oo_entry"]->getType() == \OOEntry::TYPE_ALLOTMENT_FULL_DAY ) {
									$ooNotification = "This is a <strong>" . $curDayAggregate["oo_entry"]->getOOBooking()->getOOBookingType()->getPrettyTypeName() . "</strong> day.";
								}
								else if ( $curDayAggregate["oo_entry"]->getType() == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM ) {
									$ooNotification = "This morning is a <strong>" . $curDayAggregate["oo_entry"]->getOOBooking()->getOOBookingType()->getPrettyTypeName() . "</strong> half-day.";
								}
								else if ( $curDayAggregate["oo_entry"]->getType() == \OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM ) {
									$ooNotification = "This afternoon is a <strong>" . $curDayAggregate["oo_entry"]->getOOBooking()->getOOBookingType()->getPrettyTypeName() . "</strong> half-day.";
								}
								
							}
							
						?>
					
						<?php if ( $holidayNotificationToRender ) : ?>
							<div class="momo_section_subheader">
								<?php echo $holidayNotification; ?>
							</div>
						<?php endif; ?>
						
						<?php if ( $offDayNotificationToRender ) : ?>
							<div class="momo_section_subheader">
								<?php echo $offDayNotification; ?>
							</div>
						<?php endif; ?>
						
						<?php if ( $ooNotificationToRender ) : ?>
							<div class="momo_section_subheader">
								<?php echo $ooNotification; ?>
							</div>
						<?php endif; ?>
					
						<div class="well" style="margin-bottom: 0px;">
	
							<?php 
							
								//	
			    				// load alert box widget
			    				$alertBoxParams = array();
			    				$alertBoxParams["id"] = "alertBox_" . $weekDayIndex;
			    				$alertBoxParams["width"] = "585";
							
			    				$this->load->view("widgets/widget_alert_box.php", $alertBoxParams);
			    			
								//
								// issue the day's existing regular entries
								
								$entryIndex = 0;
								foreach ( $curDayAggregate["regular_entries"] as $curRegularEntry ) :
								
									$entryIndex++;
									
									// set flag to reflect worktime credit state of current entry (for storage as form data attribute)
									$curRegularEntry->getRegularEntryType()->getWorkTimeCreditAwarded() ? $workTimeCreditFlag = "true" : $workTimeCreditFlag = "false";
							?>		
							
								<form 	id="existingRegularEntryForm_<?php echo $weekDayIndex . "_" . $entryIndex ?>"
										data-entryhasworktimecredit="<?php echo $workTimeCreditFlag; ?>"
										data-entrytimeinterval="<?php echo $curRegularEntry->getTimeInterval(); ?>"
										data-daystotalworktime="<?php echo $curDayAggregate["regularentry_worktime_credit"]; ?>"
										data-daystotalprojecttime="<?php echo $curDayAggregate["total_project_time_credit"]; ?>"
										data-daystotaltime="<?php echo $curDayAggregate["total_time_credit"]; ?>"
										action="/timetracker/updateregularentry"
										method="post"
										class="form-inline"
										style="margin-bottom: 5px;" >
								
									<select name="typeId" disabled="disabled" style="width: 200px;" tabindex="1">
									
										<?php foreach( $timetracker_entrytypes as $curType ) : ?>
											<?php $worktimeCreditFlag = ($curType->getWorktimeCreditAwarded() ? "true" : "false"); ?>
											<option data-worktimecreditawarded="<?php echo $worktimeCreditFlag ?>" value="<?php echo $curType->getId()?>" <?php $curType->getId() == $curRegularEntry->getRegularEntryTypeId() ? print('selected="selected"') :  print(''); ?> >
												<?php echo $curType->getType(); ?>
											</option>	
										<?php  endforeach; ?>
									
									</select>
									
									<?php
										// 
										// format from/until for display
										$from = $curRegularEntry->getFrom()->format("Hi");
										$until = $curRegularEntry->getUntil()->format("Hi");
									?>
									
									<input name="from" type="text" maxlength="4" value="<?php echo $from; ?>" disabled="disabled" style="width: 60px;" tabindex="2" />
									<input name="until" type="text" maxlength="4" value="<?php echo ($until == "0000") ? "2400" : $until; ?>" disabled="disabled" style="width: 60px;" tabindex="3" />  
								 	<input name="comment" type="text" maxlength="255" value="<?php echo htmlspecialchars($curRegularEntry->getComment()); ?>" disabled="disabled" style="width: 330px;" tabindex="4" />
								
									<?php if ( ! $curDayAggregate["day_islocked"] ) :  ?>
									
									  	<button name="control_save" class="btn btn-mini" onclick="return saveRegularEntry('existingRegularEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>');" style="display: none;" tabindex="4">save</button>
									  	<button name="control_cancel" class="btn btn-mini" onclick="cancelEditExistingEntryForm('existingRegularEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;" style="display: none;" tabindex="5" onblur="$(this).parent().children(':first'	).focus();">cancel</button>
									  	<button name="control_edit" class="btn btn-mini" onclick="enableEditExistingEntryForm('existingRegularEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;">edit</button>	
									  	<button name="control_delete" class="btn btn-mini" onclick="confirmRegularEntryDelete('alertBox_<?php echo $weekDayIndex ?>', 'existingRegularEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;">delete</button>
			
									<?php endif; ?>
		
									<input name="entryId" type="hidden" value="<?php echo $curRegularEntry->getId(); ?>" />		
								
								</form>
								
							<?php endforeach; ?>
							
							<!-- 
								a blank form for creating new regular entries
							-->	
							<form
								id="newRegularEntryForm_<?php echo $weekDayIndex; ?>"
								data-entrytimeinterval="0"
								data-daystotaltime="<?php echo $curDayAggregate["total_time_credit"]; ?>"
								action="/timetracker/createregularentry"
								class="form-inline" 
								method="post"
								style="display: none;">
										
								<select name="typeId" style="width: 200px;" tabindex="1">
									<?php foreach( $timetracker_entrytypes as $curType ) :  ?>
										<option value="<?php echo $curType->getId()?>"><?php echo $curType->getType(); ?>
									<?php  endforeach; ?>
								</select>
						
								<input name="from" type="text" maxlength="4" placeholder="from" style="width: 60px;" tabindex="2" />
								<input name="until" type="text" maxlength="4" placeholder="until" style="width: 60px;" tabindex="3" />  
							 	<input name="comment" type="text" maxlength="255" placeholder="comment..." style="width: 330px;" tabindex="4" />
							 		 
							 	<button name="control_save" class="btn btn-mini" onclick="return saveRegularEntry('newRegularEntryForm_<?php echo $weekDayIndex?>');" tabindex="5">save</button>
								<button name="control_cancel" class="btn btn-mini" onclick="cancelNewRegularEntryForm('newRegularEntryForm_<?php echo $weekDayIndex?>'); return false;" tabindex="6" onblur="$(this).parent().children(':first').focus();">cancel</button>
							
								<input name="entryId" type="hidden" value="-1" />
								<input name="entryDate" type="hidden" value="<?php echo DateTimeHelper::formatDateTimeToStandardDateFormat($curDayDate); ?>" />
								
							</form>
							
							<?php if ( $curDayAggregate["regular_entries"]->count() != 0 ) : ?>
								<div style="margin-left: 8px; font-size: smaller;">
									total: <?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDayAggregate["total_time_credit"]) . " h"; ?>
									( work-time: <?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDayAggregate["total_worktime_credit"]) . " h"; ?> )
								</div>
							<?php endif; ?>
							
							<?php	
									// prepare data elements that govern the display of the section subdivider
									// --> 	if there are project entries, the section divider is always displayed
									//		in case there are none, the section divider display is governed by the display of the "new project entry" form
									$sectionSubDividerDisplay = "display: none;";
									$isFixState = "false";
							
									if ( $curDayAggregate["project_entries"]->count() != 0 ) {
										$sectionSubDividerDisplay = "display: block;";
										$isFixState = "true";
									}	
							?>
										
							<div id="sectionSubdivider_<?php echo $weekDayIndex; ?>" data-isfix="<?php echo $isFixState; ?>" style="margin: 10px 0 10px 0; border-top: thin solid rgba(0, 0, 0, 0.15); <?php echo $sectionSubDividerDisplay; ?>"></div>
							
							<?php
								//
								// issue the day's existing project entries
								
								$entryIndex = 0;
								foreach ( $curDayAggregate["project_entries"] as $curProjectEntry ) :
								
									$entryIndex++;
							?>		
							
							
								<form 	id="existingProjectEntryForm_<?php echo $weekDayIndex . "_" . $entryIndex ?>"
										data-entrytimeinterval="<?php echo $curProjectEntry->getTimeInterval(); ?>"
										data-daystotalworktime="<?php echo $curDayAggregate["regularentry_worktime_credit"]; ?>"
										data-daystotalprojecttime="<?php echo $curDayAggregate["total_project_time_credit"]; ?>"
										action="/timetracker/updateprojectentry"
										method="post"
										class="form-inline"
										style="margin-bottom: 5px;" >
								
									<select name="projectId" disabled="disabled" style="width: 200px;" tabindex="1">
										<?php foreach( $timetracker_projects as $curProject ) :  ?>
											<option value="<?php echo $curProject->getId()?>" <?php $curProject->getId() == $curProjectEntry->getProjectId() ? print('selected="selected"') :  print(''); ?>" > <?php echo $curProject->getName(); ?> </option>
										<?php  endforeach; ?>
									</select>
							
									<div class="input-append">
										<input name="hours" type="text" value="<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectEntry->getTimeInterval()); ?>" disabled="disabled" maxlength="4" style="width: 33px;" tabindex="2" /><span class="add-on">h</span>
								 	</div> 
								 	
								 	
								 	<?php if ( ! $curDayAggregate["day_islocked"] ) :  ?>
								 	  	
									  	<button name="control_save" class="btn btn-mini" onclick="return saveProjectEntry('existingProjectEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>');" style="display: none;" tabindex="3">save</button>
									  	<button name="control_cancel" class="btn btn-mini" onclick="cancelEditExistingEntryForm('existingProjectEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;" tabindex="4" style="display: none;" onblur="$(this).parent().children(':first').focus();">cancel</button>
									  	<button name="control_edit" class="btn btn-mini" onclick="enableEditExistingEntryForm('existingProjectEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;">edit</button>
									  	<button name="control_delete" class="btn btn-mini" onclick="confirmProjectEntryDelete('alertBox_<?php echo $weekDayIndex ?>', 'existingProjectEntryForm_<?php echo $weekDayIndex  . "_" . $entryIndex ?>'); return false;">delete</button>
									
									<?php endif; ?>
									
									<input name="hoursNormalized" type="hidden" />
									<input name="entryId" type="hidden" value="<?php echo $curProjectEntry->getId(); ?>" />		
								
								</form>
								
							<?php endforeach; ?>
							
							<?php if ($curDayAggregate["project_entries"]->count() != 0) : ?>
								<div style="margin-left: 8px; font-size: smaller;">
									total: <?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDayAggregate["total_project_time_credit"]) . " h"; ?>
								</div>
							<?php endif; ?>
							
							<!-- 
								a blank form for creating new project entries
							-->	
							<form	id="newProjectEntryForm_<?php echo $weekDayIndex?>"
									data-daystotalworktime="<?php echo $curDayAggregate["regularentry_worktime_credit"]; ?>"
									data-daystotalprojecttime="<?php echo $curDayAggregate["total_project_time_credit"]; ?>"
									action="/timetracker/createprojectentry"
									class="form-inline" 
									method="post"
									style="display: none;">
										
								<select name="projectId" style="width: 200px;" tabindex="1">
									<?php foreach( $timetracker_projects as $curProject ) :  ?>
										<option value="<?php echo $curProject->getId()?>"><?php echo $curProject->getName(); ?>
									<?php  endforeach; ?>
								</select>
						
								<div class="input-append">
									<input name="hours" type="text" maxlength="4" placeholder="hours" style="width: 33px;" tabindex="2" /><span class="add-on">h</span>
								</div> 
	
								<button name="control_save" class="btn btn-mini" onclick="return saveProjectEntry('newProjectEntryForm_<?php echo $weekDayIndex?>');" tabindex="3">save</button>
								<button name="control_cancel" class="btn btn-mini" onclick="cancelNewProjectEntryForm('newProjectEntryForm_<?php echo $weekDayIndex?>'); return false;" onblur="$(this).parent().children(':first').focus();" tabindex="4">cancel</button>
							 		 		 		 	
							 	<input name="hoursNormalized" type="hidden" />
								<input name="entryId" type="hidden" value="-1" />
								<input name="entryDate" type="hidden" value="<?php echo DateTimeHelper::formatDateTimeToStandardDateFormat($curDayDate); ?>" />
								
							</form>
						
						</div>
						
						<div class="momo_section_footer momo_bg_lightgray">
	
							<?php
								// display controls for "new regular entry" if day accepts new entries
								if ( $curDayAggregate["day_allows_new_regular_entries"] ) :
							?>
						
								<span id="newRegularEntryFormControl_<?php echo $weekDayIndex?>">
									<button class="btn btn-mini" onclick="displayNewRegularEntryForm('newRegularEntryForm_<?php echo $weekDayIndex?>');">time entry</button>
								</span>
								
							<?php endif; ?>
			
							<?php
							 
								// control for new project entry is displayed if
								//
								//		- the current day has accrued work-time credit
								// and
								//		- there are projects assigned to the user
								// and
								//		- the day is not locked
	
								if ( 	($curDayAggregate["regularentry_worktime_credit"] > 0)
									 && ($timetracker_projects->count() > 0)
									 &&	( ! $curDayAggregate["day_islocked"])
									) :
							?>
							
								<span id="newProjectEntryFormControl_<?php echo $weekDayIndex?>">
									<button class="btn btn-mini" onclick="displayNewProjectEntryForm('newProjectEntryForm_<?php echo $weekDayIndex?>');">project entry</button>
								</span>
								
							<?php endif; ?>
	
							<?php 
								// if allowed, display "off day" control
								if ( $curDayAggregate["day_allows_off_day_control"] ) :
							?>
							
								<?php 
									// figure out the button label (current state) and dropdown action (toggle state)
									if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_DAY_OFF_FULL ) {
										$markDayControlButtonLabel = "off entire day";
									}
									else if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_HALF_DAY_OFF_AM ) {
										$markDayControlButtonLabel = "morning off";
									}
									else if ( $curDayAggregate["day_off_day_tag"] == \Tag::TYPE_DAY_HALF_DAY_OFF_PM ) {
										$markDayControlButtonLabel = "afternoon off";
									}
									else {
										$markDayControlButtonLabel = "work day";
									}
								?>
								
								<div style="float: right; margin-top: 0px;">
									<div id="offDayControl_<?php echo $weekDayIndex?>" class="btn-group">
										<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
											<?php echo $markDayControlButtonLabel; ?>
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a href="javascript:setOffDayTagForDay(<?php echo $weekDayIndex; ?>, 'work-day');">work day</a>
											</li>
											<li>
												<a href="javascript:setOffDayTagForDay(<?php echo $weekDayIndex; ?>, 'full');">entire day off</a>
											</li>
											<li>
												<a href="javascript:setOffDayTagForDay(<?php echo $weekDayIndex; ?>, 'half-am');">morning off</a>
											</li>
											<li>
												<a href="javascript:setOffDayTagForDay(<?php echo $weekDayIndex; ?>, 'half-pm');">afternoon off</a>
											</li>
										</ul>
									</div>
								</div>
								
							<?php endif; ?>	
	
			    		</div>
							
					</div>
				
				</div>
					
			</div>
				
		</div>
		    	
	<?php endforeach; ?>
	
	</div>

</div>

<!-- begin: component_other_timetracker -->
