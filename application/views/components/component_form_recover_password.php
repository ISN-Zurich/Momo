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
		var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

		var emailValue 			= $.trim($(form).find("input[name=email]").val());
		var loginValue 			= $.trim($(form).find("input[name=login]").val());

		if ( loginValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Login</strong> must be completed.";
		}
		else if ( emailValue == "" ) {
			validated = false;
			validationMsg += "The field <strong>Email</strong> must be completed.";
		}
		else if ( ! emailRegex.test(emailValue) ) {
			validated = false;
			validationMsg += "The field <strong>Email</strong> needs to contain a valid email address.";
		}
		
		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}

		return validated;
	}


	$(function() {

		var msg = "Please complete the form to start the password recovery process.";
		displayAlert("alertBox", msg, "info");

	});

</script>

<!-- begin: component_email_form -->
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
						<label for="login" class="control-label">Login</label>
						<div class="controls">
							<input name="login" type="text" class="span2" maxlength="50" tabindex="1" />
						</div>
					</div>
		    	
		    	
		    		<div class="control-group">
						<label for="email" class="control-label">Email</label>
						<div class="controls">
							<input name="email" type="text" class="span3" maxlength="100" tabindex="2" />
						</div>
					</div>
	
					<div class="form-actions">
						<button class="btn btn-mini btn-primary" type="button" onclick="submitForm();" tabindex="3">recover password</button>
						<button class="btn btn-mini" type="button" onclick="cancelForm();" tabindex="4">cancel</button>
					</div>
					
				</form>
			 </div>
		</div>
		 
    </div>
</div>
<!-- end: component_email_form -->


