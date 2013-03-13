/*
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


// ------------------------------------------------------------------------

/////////////////////////////////////////////////////////////
// momo-alerts.js
//
// functionality to display, hide and clear bootstrap alerts
//


// global callback registry
var momo_alerts_callbacks = $.Callbacks();

//
// displays the indicated alert
//
// alertBoxId	- the id of the alertbox to display
// msg			- the message to display
// type			- type of alertbox to display (error, notice)
//

function displayAlert(alertBoxId, msg, type) {

	var alertBox = $("#" + alertBoxId);

	// clear any old alert
	clearAlerts();

	// process according to type	
	switch (type) {
	
		case 'error':
			alertBox.addClass('alert-error');
			break;

		case 'success':
			alertBox.addClass('alert-success');
			break;
			
		case 'info':
			alertBox.addClass('alert-info');
			break;
			
		default:
			alertBox.addClass('alert-info');
			
	}
	
	// pack the alert into the alertbox
	alertBox.find("p.alertMsg").html(msg);

	// mark as active
	alertBox.addClass("active");
	
	//
	// display alert box
	// -- however, if it is visible already, we pulsate it instead (note: pulsate is part of "jquery ui")
	//
	
	if (alertBox.css("display") != "block") {
		// show the alert box
		alertBox.css("display", "block");
	}
	else {
		pulsateVisibleAlerts();
	}
	
}

//
// displays a dialog
//
// note: 	the dialog has two configurable controls, passing "null" for either "control1_href"
//			or "control2_href" suppresses the respective control
//
// alertBoxId			- the id of the alert box to display the dialog in
// msg					- the message to disply
// control1_href		- the href for control 1
// control1_name		- the name to display for control 1
// control2_href		- the href for control 2
// control2_name		- the name to display for control 2
//
function displayDialog(alertBoxId, msg, control1_href, control1_name, control2_href, control2_name) {
	
	var alertBox = $("#" + alertBoxId);
	
	// clear any old alert
	clearAlerts();
	
	// pack the alert message into the alertbox
	alertBox.find("p.alertMsg").html(msg);

	//
	// wire up control and display controls
	
	// control 1
	if ( control1_href !== null ) {
		alertBox.find("a.control_1").attr("href", control1_href);
		alertBox.find("a.control_1").html(control1_name);
		alertBox.find("a.control_1").css("display", "inline");
	}
	
	
	if ( control2_href !== null ) {
		alertBox.find("a.control_2").attr("href", control2_href);
		alertBox.find("a.control_2").html(control2_name);
		alertBox.find("a.control_2").css("display", "inline");
	}
	
	alertBox.find("div.controls").css("display", "block");

	// display as info message
	alertBox.addClass('alert-info');
	
	// mark as active
	alertBox.addClass("active");
	
	// flag dialog as active
	activeDialog = true;
			
	//
	// display dialog box
	// -- however, if it is visible already, we pulsate it instead (note: pulsate is part of "jquery ui")
	//
	if ( alertBox.css("display") != "block" ) {
		// show the alert box
		alertBox.css("display", "block");
	}
	else {
		pulsateVisibleAlerts();
	}
}


//
// displays operation confirmation dialog
//
// note: 	an operation confirmation dialog has alyways two controls, one configurable in terms of action and label,
//			whereas the second is labelled "cancel" an defaults to a preconfigured action to this effect
//
// alertBoxId			- the id of the alert box to display the dialog in
// msg					- the message to disply
// control1_href		- the href for control 1 (the action to take if dialog is confirmed)
// control1_name		- the name to display for control 1
// callback				- an optional callback function. if passed, the callback is called prior to the
//						  display of and prior to dismissal of the dialog with the signature "callback(alertBoxId, state)", where state E ("init", "end")
//
function displayConfirmDialog(alertBoxId, msg, control1_href, control1_name, callback) {
	
	var alertBox = $("#" + alertBoxId);
		
	// clear any old alert
	clearAlerts();
	
	// call callback with state "init" if defined
	if ( typeof callback === "function" ) {
		callback(alertBoxId, "init");
		momo_alerts_callbacks.add(callback);
	}
	
	// pack the alert message into the alertbox
	alertBox.find("p.alertMsg").html(msg);

	//
	// wire up control and display controls
	
	// control 1
	alertBox.find("a.control_1").attr("href", control1_href);
	alertBox.find("a.control_1").css("display", "inline");
	alertBox.find("a.control_1").html(control1_name);
	alertBox.find("a.control_1").addClass("btn-danger");
	
	// control 2 (cancel)
	alertBox.find("a.control_2").attr(	"href",
										"javascript:cancelConfirmDialog('" +  alertBoxId + "');"
									);
	
	alertBox.find("a.control_2").html("cancel");
	alertBox.find("a.control_2").css("display", "inline");
	
	
	alertBox.find("div.controls").css("display", "block");

	// display as info message
	alertBox.addClass('alert-info');
	
	// mark as active
	alertBox.addClass("active");
	
	// flag dialog as active
	activeDialog = true;
			
	//
	// display dialog box
	// -- however, if it is visible already, we pulsate it instead (note: pulsate is part of "jquery ui")
	//
	if ( alertBox.css("display") != "block" ) {
		// show the alert box
		alertBox.css("display", "block");
	}
	else {
		pulsateVisibleAlerts();
	}
}

//
// Shows the Ajax Spinner
//
// Note: the spinner will appear to the right of whatever controls are displayed
//
// alertBoxId			- the id of the alert box for which the spinner is to be shown
//
function displayAjaxSpinner(alertBoxId) {
	
	var alertBox = $("#" + alertBoxId);
	
	// make spinner visible
	alertBox.find("span.spinner").css("display", "inline");	
}

//
// Hides the Ajax Spinner
//
// Note: the spinner will appear to the right of whatever controls are displayed
//
// alertBoxId			- the id of the alert box for which the spinner is to be shown
//
function hideAjaxSpinner(alertBoxId) {
	
	var alertBox = $("#" + alertBoxId);
	
	// make spinner visible
	alertBox.find("span.spinner").css("display", "none");	
}

//
// cancels a confirm dialog
//
// note: the operation fires any pending callbacks on the jquery "once" callback list
//
function cancelConfirmDialog(alertBoxId) {
	
	// fire pending callbacks
	momo_alerts_callbacks.fire(alertBoxId, "end");
	
	// hide and reset alerts
	purgeAlerts();
	

	// flag dialog as inactive
	activeDialog = false;
}

//
// clears / resets all alert boxes
//
function clearAlerts() {

	$(".alert").each(function() {
		
		//
		// reset alert widget to virgin state
		
		// reset alert type
		$(this).attr("class", "alert");
		
		// clear alert message
		$(this).find(".alertbox-message").html("");

		// control container
		$(this).find("div.controls").css("display", "none");
		
		
		// control 1
		
		// message and action
		$(this).find("a.control_1").html("");
		$(this).find("a.control_1").attr("href", "");
		
		// classes
		$(this).find("a.control_1").attr("class", "control_1 btn btn-mini");
		
		// display
		$(this).find("a.control_1").css("display", "none");
		
		
		// control 2
		
		// message and action
		$(this).find("a.control_2").html("");
		$(this).find("a.control_2").attr("href", "");
		
		// classes
		$(this).find("a.control_2").attr("class", "control_2 btn btn-mini");
		
		// display
		$(this).find("a.control_2").css("display", "none");
		
		
		// ajax spinner
		$(this).find("span.spinner").css("display", "none");
		
		// clear callback registry
		momo_alerts_callbacks.empty();
		
	
	});
	
	activeDialog = false;
}

//
// hides all alert boxes
//
function hideAlerts() {

	$(".alert").each(function() { 
		$(this).css("display", "none");
	});		
}

//
// hides and clears all alert boxes
//
function purgeAlerts() {
	clearAlerts();
	hideAlerts();	
}

//
// pulsates visible alerts
//
function pulsateVisibleAlerts() {
	$(".alert.active").effect("pulsate", { times:2 }, 500);;
}