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

?>

<script type="text/javascript">

	//
	// set up map of setting keys vis-a-vis their meta data
	var component_form_settings_keyMetaMap = {};
	
	<?php foreach ( \Setting::$KEY_MAP as $curTypeKey => $curTypeVal ) : ?>
	
		component_form_settings_keyMetaMap["<?php echo $curTypeKey; ?>"] = {};
		component_form_settings_keyMetaMap["<?php echo $curTypeKey; ?>"]["unitPrettyName"] = "<?php echo $curTypeVal["prettyDisplayUnitNameSingular"]; ?>" + "s";
		component_form_settings_keyMetaMap["<?php echo $curTypeKey; ?>"]["units"] = "<?php echo $curTypeVal["units"]; ?>";
		component_form_settings_keyMetaMap["<?php echo $curTypeKey; ?>"]["datatype"] = "<?php echo $curTypeVal["dataType"]; ?>";
	
	<?php endforeach; ?>

	//
	// submits the indicated form
	//
	function submitForm() {

		var result = false;
		
		if ( result = isValidForm() ) {

			var form = $("#form");

			//
			// if the form is valid, normalize fields with units "seconds"
			// i.e., convert user entered hours to seconds and store those in the provided hidden form field
			var secondsFields = $(form).find('input[data-units=<?php echo Setting::UNIT_SECONDS; ?>]');
			
			$(secondsFields).each(function (index) {
				var hiddenInputFieldName = $(this).attr("id") + "_Normalized";			
				$("#" + hiddenInputFieldName).val(3600 * $(this).val());
			});	

		}

		return result;
	}

	//
	// cancels the form
	//
	function cancelForm() {
		// throw back to the default route as there is no list mode
		// for "settings"
		document.location = "/";
	}


	//
	// validates the settings form
	//
	// the form is valid, if:
	//
	function isValidForm() {
				
		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";
		var rationalRegex = /^[+,-]{0,1}\d*\.?\d+$/;
		var intRegex = /^\d+$/;
		var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

		var curFieldValue = null;
		var curFieldName = null;
		
		// validate fields according to data type
		$.each( component_form_settings_keyMetaMap, function(index, value) {

			// retrieve the value of the currently considered field
			curFieldValue = $.trim($("#" + index).val());
			curFieldName = $(form).find("label[for='" + index + "']").text();
			
			switch ( value["datatype"] ) {

				case "<?php echo \Setting::DATATYPE_EMAIL_ADDRESS; ?>" :

					if ( ! emailRegex.test(curFieldValue) ) {
						validated = false;
						validationMsg = "The field <strong> " + curFieldName + "</strong> needs to contain a valid email address.";
					}
					
					break;

				case "<?php echo \Setting::DATATYPE_DATE; ?>" :
					// NOOP - ok due to date types being readonly
					break;

				case "<?php echo \Setting::DATATYPE_FLOAT; ?>" :
					
					if ( ! rationalRegex.test(curFieldValue) ) {
						validated = false;
						validationMsg = "The field <strong> " + curFieldName + "</strong> needs to contain a numeric value.";
					}
					
					break;

				case "<?php echo \Setting::DATATYPE_INTEGER; ?>" :

					if ( ! intRegex.test(curFieldValue) ) {
						validated = false;
						validationMsg = "The field <strong> " + curFieldName + "</strong> needs to contain an integer value.";
					}
					
					break;	

				case "<?php echo \Setting::DATATYPE_STRING; ?>" :

					if ( curFieldValue == "" ) {
						validated = false;
						validationMsg = "The field <strong> " + curFieldName + "</strong> may not be left empty.";
					}
					
					break;		

			}

			if ( ! validated ) {
				return false;
			}

		});
		
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
		
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {

		// set up datepicker for all fields with datatype="date"	
		$(form).find('input[data-datatype=<?php echo Setting::DATATYPE_DATE; ?>]').datepicker({
							dateFormat: "d-m-yy",
							firstDay: "1",
							showOn: "button",
							buttonImage: "/assets/img/datepicker_cal.png",
							buttonImageOnly: true,
							showOtherMonths: true,
							selectOtherMonths: true
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
	
				<form id="form" action="/managesettings/updatesettings" method="post" class="form-horizontal widelabel" style="margin-left: 0px;">
				
					<fieldset>
						
						<?php foreach ( \Setting::$KEY_MAP as $curTypeKey => $curTypeVal ) : ?>
	
							<div class="control-group">
								<label for="<?php echo $curTypeKey; ?>" class="control-label"><?php echo $curTypeVal["prettyKeyName"]; ?></label>
								<div class="controls">
								
									<?php
										//
										// set field classes according to setting datatype
										switch( $curTypeVal["dataType"] ) {
											
											case Setting::DATATYPE_DATE :
												$fieldClass = "";
												$fieldStyle = "width: 80px;";
												$readOnly = "readonly='readonly'";
												break;
												
											case Setting::DATATYPE_FLOAT :	
											case Setting::DATATYPE_INTEGER :
												$fieldClass = "span1";
												$fieldStyle = "";
												$readOnly = "";
												break;	
												
											case Setting::DATATYPE_EMAIL_ADDRESS :	
											case Setting::DATATYPE_STRING :
												$fieldClass = "span3";
												$fieldStyle = "";
												$readOnly = "";
												break;
										}
										
										
										// set field value according to underlying unit
										switch( $curTypeVal["units"] ) {
											
											case Setting::UNIT_SECONDS :
												// seconds are displayed as hours
												$fieldValue = sprintf("%.2f", $component_settings_key_value_map[$curTypeKey] / 3600);
												break;
												
											default :
												$fieldValue = $component_settings_key_value_map[$curTypeKey];	
										}
										
									?>
									
										<div class="input-append">
											<input 	id="<?php echo $curTypeKey; ?>"
													type="text"
													name="<?php echo $curTypeKey; ?>"
													value="<?php echo $fieldValue; ?>"
													class="<?php echo $fieldClass; ?> input-xlarge"
													style="<?php echo $fieldStyle; ?>"
													<?php echo $readOnly; ?>
													data-units="<?php echo $curTypeVal["units"]; ?>"
													data-datatype="<?php echo $curTypeVal["dataType"]; ?>">
													
											<?php if ( $curTypeVal["units"] !== null ) : ?>		
												<span id="valueUnits" class="add-on" ><?php echo $curTypeVal["prettyDisplayUnitNameSingular"]; ?>s</span>
											<?php endif; ?>
											
											<?php
												// fields with underlying units of seconds render a hidden formfield which will hold normalized
												// value (i.e. value in seconds)
												if ( $curTypeVal["units"] == Setting::UNIT_SECONDS ) :
											?>		
												<input id="<?php echo $curTypeKey; ?>_Normalized" type="hidden" name="<?php echo $curTypeKey; ?>_Normalized" value="" >
											<?php endif; ?>
												
										</div>
					
								</div>
							</div>
							
						<?php endforeach; ?>	
						
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;">cancel</button>
						</div>
						
					</fieldset>
				
				</form>
			</div> 	 
		</div>
    </div>
</div>
