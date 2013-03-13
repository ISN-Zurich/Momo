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

// some call mode related prelims
$component_mode == "authenticated" ? $actionBtnText = "change password" : $actionBtnText = "set password";

?>

<script type="text/javascript">

	//
	// submits the indicated form
	//
	function submitForm() {
		if (isValidForm()) {
			$("#form").submit();
		}
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "/";
	}

	//
	// validates the form
	//
	//
	function isValidForm() {

		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";

		var passwordValue = $.trim($(form).find("input[name=password]").val());
		var passwordRepeatValue = $.trim($(form).find("input[name=passwordRepeat]").val());

		// validate that the password field has a value
		if ( passwordValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Password</strong> must be completed.";
		}
		// validate that name field is at least 5 chars long
		else if ( passwordValue.length < 5 ) {
			validated = false;
			validationMsg += "The field <strong>Password</strong> must be at least 5 characters long.";
		}
		// validate that the two password fields match
		else if ( passwordValue != passwordRepeatValue ) {
			validated = false;
			validationMsg += "The values in the two password fields do not match.";
		}
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
	}

</script>

<!-- begin: component_password_form -->
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
			
		    	<form id="form" class="form-horizontal" name="form" action="<?php echo $component_action; ?>" method="post">
		    	
		    		<div class="control-group">
						<label for="teamName" class="control-label">Password</label>
						<div class="controls">
							<input name="password" type="password" class="span2" tabindex="1" />
						</div>
					</div>
					<div class="control-group">
						<label for="teamName" class="control-label">Password (repeat)</label>
						<div class="controls">
							<input name="passwordRepeat" type="password" class="span2" tabindex="2" />
						</div>
					</div>
					<div class="form-actions">
						<button class="btn btn-mini btn-primary" type="button" onclick="submitForm();" tabindex="3"><?php echo $actionBtnText; ?></button>
						<?php if ($component_mode == "authenticated") : ?>
							<button class="btn btn-mini" type="button" onclick="cancelForm();" tabindex="4">cancel</button>
						<?php endif; ?>
					</div>
					
					<?php if ($component_mode == "token") : ?>
						<input name="resetToken" type="hidden" value="<?php echo $component_reset_target->getPasswordResetToken(); ?>" />
					<?php endif; ?>
				</form>
			 </div>	
		</div>
			 
    </div>
</div>
<!-- end: component_password_form -->


