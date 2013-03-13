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

?>

<script type="text/javascript">

	//
	// submits the indicated form
	//
	function submitForm() {
		if ( isValidForm() ) {
			setAction();
			$("#form").submit();
		}
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/manageteams/displayteamsummary";
	}


	//
	// sets the action url
	//
	function setAction() {
		
		// get entered date value
		var unlockWeekValue = $(form).find("input[name=unlockWeekDate]").val();

		// construct action url
		var actionUrl = "/enforcement/unlockweekforuser/" + unlockWeekValue + "/<?php echo $component_target_user->getId() ?>/<?php echo FormHelper::encodeUriSegment("/manageteams/displayteamsummary");?>";

		// set form action
		$("#form").attr("action", actionUrl);
	}
	

	//
	// validates the form
	//
	//
	function isValidForm() {

		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";

		var unlockWeekValue = $(form).find("input[name=unlockWeekDate]").val();
	
		if ( unlockWeekValue == "" ) {
			validated = false;
			validationMsg += "Please indicate the week to unlock.";
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
		// set up datepicker
		
		$("#unlockWeekDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(	<?php echo $component_date_elems_earliest_unlock["year"]; ?>,
								<?php echo ($component_date_elems_earliest_unlock["month"] - 1); ?>,
								<?php echo $component_date_elems_earliest_unlock["day"]; ?>),
					
			maxDate:  new Date(	<?php echo $component_date_elems_latest_unlock["year"]; ?>,
								<?php echo ($component_date_elems_latest_unlock["month"] - 1); ?>,
								<?php echo $component_date_elems_latest_unlock["day"]; ?>)
		});


		// normalizes the selected date 
		// -- i.e., selects the monday of the week containing the selected date
		$("#unlockWeekDate input").change(function() {

			//
			// figure out monday of week containing selected date
			
			// get js Date representation of selected date
			var unlockWeekValue = $(form).find("input[name=unlockWeekDate]").val();
			var unlockWeekDate = Date.parseExact(unlockWeekValue, "d-M-yyyy");

			// compute weekday, as JS starts weeks in sunday (=0), we remap sunday to 7
			var unlockWeekDayNumber = (unlockWeekDate.getDay() != 0) ? unlockWeekDate.getDay() : 7;

			// compute difference to monday and get normalized date from this
			var diffDaysToMonday = -1 * (unlockWeekDayNumber - 1);
			var normalizedDate = unlockWeekDate.addDays(diffDaysToMonday);

			// set date field to normalized value
			$(form).find("input[name=unlockWeekDate]").val(normalizedDate.toString("d-M-yyyy"));
			
		});
		
	});

</script>

<!-- begin: component_form_message -->
<div class="row">
	<div class="offset3 span6">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header momo_top_spacer100">
				<h4><?php echo $component_title ?></h4>
			</div>
			
			<div class="well momo_panel_body"> 
				<?php 		
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>	    
		    	<form id="form" class="form-horizontal" name="form" action="" method="post">
		    	
					<div class="control-group">
						<label for="unlockWeekDate" class="control-label">Unlock week of:</label>
						<div class="controls">
							<div id="unlockWeekDate">
								<input type="text" name="unlockWeekDate" class="datePicker input-xlarge" value="" style="width: 80px;" readonly="readonly">
							</div>
						</div>	
					</div>
					
					<div class="form-actions">
						<button class="btn btn-mini btn-primary" type="button" onclick="submitForm();">unlock</button>
						<button class="btn btn-mini" onclick="cancelForm(); return false;">cancel</button>
					</div>
					
					<input name="userId" type="hidden" value="<?php echo $component_target_user->getId(); ?>">
					
				</form>
			 </div>		
			 
		</div>	
		  
    </div>
</div>
<!-- end: component_form_message -->


