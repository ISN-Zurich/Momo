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

//
// some preliminary logic with regard to call mode

// determine what action to call
$component_mode == "new" ? $action = "/managebookingtypes/createtype" : $action = "/managebookingtypes/updatetype";

?>

<!-- link bootstrap colorpicker JS -->
<script src="/assets/js/bootstrap-colorpicker.js"></script>

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
		document.location = "/managebookingtypes";
	}


	//
	// validates the booking type form
	//
	// the form is valid, if:
	//
	//		for all cases
	//			- it has a "type" designation that is at least 5 and less than 50 chars long	
	//			- at minimum, one of the checkboxes of "Bookable In" is selected
	//
	function isValidForm() {
		
		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";

		var typeValue = $.trim($(form).find("input[name=type]").val());

		// validate that name field has a value
		if ( typeValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Type</strong> must be completed.";
		}
		// validate that name field is at least 5 chars long
		else if ( typeValue.length < 5 ) {
			validated = false;
			validationMsg += "The field <strong>Type</strong> must be at least 5 characters long.";
		}
		// if applicable, validate "out of office" fields
		else {

			var bookableInElems = $(form).find("#bookableInGroup input");
			var hasCheckedElement = false;

			// check if there is a selected box
			$(bookableInElems).each(function() {

				if ( $(this).attr("checked") == "checked" ) {
					hasCheckedElement = true;
				}
		
			});

			if ( ! hasCheckedElement ) {
				validated = false;
				validationMsg += "The field <strong>Bookable In</strong> requires at least one selection.";
			}
						
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
		// install event handler to force a "bookable in" selection of "half-day"
		// to also select "day". likewise, deselecting "day" will also deselect "half-day"
		$("#bookableIn_" + "<?php echo \OOBookingType::BOOKABLE_IN_HALFDAYS; ?>").change(function() {
			if ( $(this).attr("checked") === "checked" ) {
				$("#bookableIn_" + "<?php echo \OOBookingType::BOOKABLE_IN_DAYS; ?>").attr("checked", "checked");
			}
		});

		$("#bookableIn_" + "<?php echo \OOBookingType::BOOKABLE_IN_DAYS; ?>").change(function() {
			if ( $(this).attr("checked") === undefined ) {
				$("#bookableIn_" + "<?php echo \OOBookingType::BOOKABLE_IN_HALFDAYS; ?>").removeAttr("checked");
			}
		});

		// initialize colorpicker
		$('#colorpicker').colorpicker();
		
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
	
				<form id="form" action="<?php echo $action; ?>" method="post" class="form-horizontal widelabel" style="margin-left: 0px;">
					<fieldset>
					
						<div class="control-group">
							<label for="type" class="control-label">Type</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $type = $component_edit_target->getType() : $type = "" ?>
								<input type="text" name="type" value="<?php echo $type; ?>" class="span3 input-xlarge" maxlength="50" tabindex="1">
							</div>
						</div>
						
						<div class="control-group">
							<label for="bookableIn[]" class="control-label">Bookable In</label>
							<div id="bookableInGroup" class="controls">
							
								<?php foreach (\OOBookingType::$BOOKABLE_IN_MAP_PLURAL as $curOptVal => $curOptText) : ?>
								
									<?php 
											//
											// for edit mode, figure out which bookable-in checkboxes to check
											$checked = '';
											if ( $component_mode == "edit" ) {
							
												switch ( $curOptVal ) {
						
													case \OOBookingType::BOOKABLE_IN_DAYS:
														
														if ( $component_edit_target->getBookableInDays() ) {
															$checked = 'checked="checked"';
														}
														
														break;
														
													case \OOBookingType::BOOKABLE_IN_HALFDAYS:
														
														if ( $component_edit_target->getBookableInHalfDays() ) {
															$checked = 'checked="checked"';
														}
														
														break;
												}
	
											}
										 
									?>
		
									<div class="checkbox">
										<input id="bookableIn_<?php echo $curOptVal; ?>" tabindex="2" type="checkbox" value="<?php echo $curOptVal; ?>" name="bookableIn[]" <?php echo $checked; ?> > <?php echo $curOptText; ?>
									</div>
									
								<?php endforeach; ?>	
							</div>
						</div>	
						
						<div class="control-group">
							<label for="paid" class="control-label">Paid</label>
							<div class="controls">
							
								<select id="paid" name="paid" class="span1" tabindex="3">
									
									<?php foreach (\momo\core\helpers\FormHelper::$binaryOptionsYesNo as $curOptVal => $curOptText) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
									
											<?php if ( $component_edit_target->getPaid() == $curOptVal ) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo $curOptText; ?></option>
										
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
						
						<?php 
							// figure out booking type's color value
							// for new types we set to default color, for existing types use stored color
							if ( $component_mode == "new" ) {
								$rgbColorValue = $component_default_color;
							}
							else {
								$rgbColorValue = $component_edit_target->getRgbColorValue();
							}
						?>
						
						<div class="control-group">
							<label for="rgbColorValue" class="control-label">Summary Color</label>
							<div class="controls">
								<div id="colorpicker" class="input-append color" data-color="#<?php echo $rgbColorValue; ?>" data-color-format="hex">
								    <input name="rgbColorValue" type="text" class="span1" value="#<?php echo $rgbColorValue; ?>" tabindex="4">
								    <span class="add-on" ><i style="background-color: #<?php echo $rgbColorValue; ?>"></i></span>
								</div>
							</div>
						</div>
		
						<div class="control-group">
							<label for="enabled" class="control-label">Enabled</label>
							<div class="controls">
							
								<select id="enabled" name="enabled" class="span1" tabindex="5">
									
									<?php foreach (\momo\core\helpers\FormHelper::$binaryOptionsYesNo as $curOptVal => $curOptText) : ?>
									
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
									
											<?php if ($component_edit_target->getEnabled() == $curOptVal) : ?>
												
												<?php $selected = "selected='selected'" ?>
										
											<?php endif; ?>
										
										<?php endif; ?>
									
										<option value="<?php echo $curOptVal; ?>" <?php echo $selected; ?>><?php echo $curOptText; ?></option>
										
									<?php endforeach; ?>
									
								</select>
							</div>
						</div>
						
						<div class="form-actions">
							<button class="btn btn-mini btn-primary" onclick="return submitForm();" tabindex="6">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;" tabindex="7">cancel</button>
						</div>
						
						<?php if ( $component_mode == "edit" ) : ?>
							<input type="hidden" name="typeId" value="<?php echo $component_edit_target->getId(); ?>">				
						<?php endif; ?>	
						
					</fieldset>
				
				</form>
			</div> 	 
		</div>
			
    </div>
    
</div>
