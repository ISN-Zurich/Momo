<!-- begin: component_form_entrytype -->
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

// determine what action to call
$component_mode == "new" ? $action = "/manageentrytypes/createtype" : $action = "/manageentrytypes/updatetype";

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
		document.location = "/manageentrytypes";
	}


	//
	// validates the entry type form
	//
	// the form is valid, if:
	//
	//			- it has a "type" designation that is at least 5 and less than 50 chars long
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
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
		
	}

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
							<label for="type" class="control-label">Type</label>
							<div class="controls">
								<?php $component_mode == "edit" ? $type = $component_edit_target->getType() : $type = "" ?>
								<input type="text" name="type" value="<?php echo $type; ?>" class="span3 input-xlarge" maxlength="50" tabindex="1">
							</div>
						</div>
	
						<div class="control-group">
							<label for="worktimeCredit" class="control-label">Worktime Credit</label>
							<div class="controls">
								
								<?php ($component_mode == "edit" && $component_edit_target->isInUse()) ? $disabled = "disabled='disabled'" : $disabled = "" ?>
							
								<select id="worktimeCredit" name="worktimeCredit" class="span1" <?php echo $disabled; ?> tabindex="2">
								
									<?php foreach (\momo\core\helpers\FormHelper::$binaryOptionsYesNo as $curOptVal => $curOptText) : ?>
																	
										<?php $selected = "" ?>
										<?php if ( $component_mode == "edit" ) : ?>
									
											<?php if ( $component_edit_target->getWorkTimeCreditAwarded() ==  $curOptVal ) : ?>
												<?php $selected = 'selected="selected"' ?>
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
							
								<select id="enabled" name="enabled" class="span1" tabindex="3">
									
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
							<button class="btn btn-mini btn-primary" onclick="return submitForm();" tabindex="4">save</button>
							<button class="btn btn-mini" onclick="cancelForm(); return false;" tabindex="5">cancel</button>
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
<!-- end: component_form_entrytype -->
