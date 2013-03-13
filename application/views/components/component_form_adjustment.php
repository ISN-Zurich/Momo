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
use momo\core\helpers\FormHelper;
	
//
// some preliminary logic with regard to call mode

// determine what action to call
$component_mode == "new" ? $action = "/manageadjustments/createadjustment" : $action = "/manageadjustments/updateadjustment";

?>

<script type="text/javascript">

	//
	// set up map of adjustment type meta data
	var component_form_adjustments_typeMetaMap = {};
	
	<?php foreach ( \AdjustmentEntry::$TYPE_MAP as $curTypeKey => $curTypeVal ) : ?>
	
		component_form_adjustments_typeMetaMap["<?php echo $curTypeKey; ?>"] = {};
		component_form_adjustments_typeMetaMap["<?php echo $curTypeKey; ?>"]["unitPrettyName"] = "<?php echo $curTypeVal["prettyDisplayUnitNameSingular"]; ?>" + "s";
		component_form_adjustments_typeMetaMap["<?php echo $curTypeKey; ?>"]["units"] = "<?php echo $curTypeVal["units"]; ?>";
	
	<?php endforeach; ?>

	//
	// submits the indicated form
	//
	function submitForm() {

		var result = false;
		
		if ( result = isValidForm() ) {

			var form = $("#form");

			// if the form is valid, normalize time adjustments
			var normalizedValue = $(form).find("input[name=adjustmentValue]").val();
			
			if ( component_form_adjustments_typeMetaMap[$("#adjustmentType").val()]["units"] == "<?php echo \AdjustmentEntry::UNIT_SECONDS ?>" ) {
				//
				// convert hours to native seconds
				normalizedValue *= 3600;
			}

			// stuff normalized value into hidden form field
			$("#normalizedAdjustmentValue").val(normalizedValue);

		}
		
		return result;
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/manageadjustments";
	}


	//
	// validates the entry type form
	//
	// the form is valid, if:
	//
	//		- an adjustment type is selected
	//		- a non-zero numeric value has been entered
	//		- a date has been selected
	//		- a reason has been specified
	//
	function isValidForm() {
		
		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";
		var rationalRegex = /^[+,-]{0,1}\d*\.?\d+$/;

		var adjustmentTypeValue = $(form).find("select[name=adjustmentType]").val();
		var adjustmentValueValue = $(form).find("input[name=adjustmentValue]").val();
		var adjustmentDateValue = $(form).find("input[name=adjustmentDate]").val();
		var adjustmentReasonValue = $.trim($(form).find("input[name=adjustmentReason]").val());

		// validate that name field has a value
		if ( adjustmentTypeValue == -1 ) {
			validated = false;
			validationMsg += "The field <strong>Type</strong> must be completed.";
		}
		// validate that the value field is a rational number
		else if ( ! rationalRegex.test(adjustmentValueValue) ) {
			validated = false;
			validationMsg += "The field <strong>Value</strong> needs to contain a valid number.";
		}
		else if ( adjustmentDateValue == "" ) {
			validated = false;
			validationMsg += "Please indicate on which date the the adjustment takes effect.";
		}
		else if ( adjustmentReasonValue == "" ) {
			validated = false;
			validationMsg += "Please specify a reason for the adjustment.";
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
		// event handlers for "adjustmentType" selection
		$("#adjustmentType").change(function() {

			// if a type is selected, set the unit information
			if ( $("#adjustmentType").val() != -1 ) {
				$("#valueUnits").html(component_form_adjustments_typeMetaMap[$("#adjustmentType").val()]["unitPrettyName"]);
			}
			// otherwise, reset the unit information
			else {
				$("#valueUnits").html("--");
			}
			
		});

		//
		// set up datepicker
		
		$("#adjustmentDate .datePicker").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(	<?php echo $component_date_elems_earliest_adjustment["year"]; ?>,
								<?php echo ($component_date_elems_earliest_adjustment["month"] - 1); ?>,
								<?php echo $component_date_elems_earliest_adjustment["day"]; ?>),
					
			maxDate:  new Date(	<?php echo $component_date_elems_latest_adjustment["year"]; ?>,
								<?php echo ($component_date_elems_latest_adjustment["month"] - 1); ?>,
								<?php echo $component_date_elems_latest_adjustment["day"]; ?>)
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
							<label for="enabled" class="control-label">Type</label>
							<div class="controls">
							
								<select id="adjustmentType" name="adjustmentType" class="span2">
						
									<option value="-1" selected="selected">please select...</option>
									
									<?php foreach ( \AdjustmentEntry::$TYPE_MAP as $curTypeKey => $curTypeVal ) : ?>
									
										<?php if ( in_array(\AdjustmentEntry::CREATOR_USER, $curTypeVal["allowedCreators"]) ) : ?>
										
											<?php $selected = "" ?>
											<?php if ( $component_mode == "edit" ) : ?>
											
												<?php if ( $component_edit_target->getType() == $curTypeKey ) : ?>
													
													<?php $selected = "selected='selected'" ?>
											
												<?php endif; ?>
					
											<?php endif; ?>		
											
											<option value="<?php echo $curTypeKey; ?>" <?php echo $selected; ?>><?php echo $curTypeVal["prettyTypeName"]; ?></option>
												
										<?php endif; ?>	
											
									<?php endforeach; ?>
									
								</select>
								
							</div>
						</div>
							
						<div class="control-group">
							<label for="adjustmentValue" class="control-label">Value</label>
							<div class="controls">
								<div class="input-append">
								
									<?php 
										// time adjustments need to be converted from native seconds to hours for display
										$value = "";
										if ( $component_mode == "edit" ) {
											
											if ( \AdjustmentEntry::$TYPE_MAP[ $component_edit_target->getType()]["units"] == \AdjustmentEntry::UNIT_SECONDS ) {
												$value = sprintf("%.2f", ($component_edit_target->getValue() / 3600));
											}
											else {
												$value = sprintf("%.2f", $component_edit_target->getValue());
											}
										}
									?>
									<?php $component_mode == "edit" ? $valueUnits = \AdjustmentEntry::$TYPE_MAP[$component_edit_target->getType()]["prettyDisplayUnitNameSingular"] . "s" : $valueUnits = "--" ?>
									<input type="text" name="adjustmentValue" value="<?php echo $value; ?>" class="span1 input-xlarge" maxlength="7"><span id="valueUnits" class="add-on" ><?php echo $valueUnits; ?></span>
								
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="adjustmentDate" class="control-label">Effective per</label>
							<div class="controls">
								<div id="adjustmentDate">
									<?php $component_mode == "edit" ? $value = DateTimeHelper::formatDateTimeToStandardDateFormat($component_edit_target->getDay()->getDateOfDay()) : $value = "" ?>
									<input type="text" name="adjustmentDate" class="datePicker input-xlarge" value="<?php echo $value; ?>" style="width: 80px;" readonly="readonly">
								</div>
							</div>	
						</div>
						
						<div class="control-group">
							<label for="adjustmentReason" class="control-label">Reason</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $value = $component_edit_target->getReason() : $value = "" ?>
								<input type="text" name="adjustmentReason" value="<?php echo $value; ?>" class="span4 input-xlarge" maxlength="255">
							</div>
						</div>
					
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;">cancel</button>
						</div>
						
						<input type="hidden" name="adjustedUserId" value="<?php echo $component_target_user->getId(); ?>">
						<input id="normalizedAdjustmentValue" type="hidden" name="normalizedAdjustmentValue" value="">
						
						<?php if ( $component_mode == "edit" ) : ?>
								
							<input type="hidden" name="entryId" value="<?php echo $component_edit_target->getId(); ?>">
										
						<?php endif; ?>	
						
					</fieldset>
				
				</form>
			</div>
			
		</div>	
    </div>
</div>
