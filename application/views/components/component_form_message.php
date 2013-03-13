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
		if ( isValidForm() ) {
			$("#form").submit();
		}
	}

	//
	// cancels the form
	//
	function cancelForm() {
		document.location = "<?php echo $component_return_url; ?>";
	}

	//
	// validates the form
	//
	//
	function isValidForm() {

		var form = $("#form");
		
		var validated = true;
		var validationMsg = "";

		var messageValue = $(form).find("textarea[name=message]").val();
	
		if ( $.trim(messageValue) == "" ) {
			validated = false;
			validationMsg += "Please enter a message.";
		}	

		if ( ! validated ) {
			displayAlert("alertBox", validationMsg, "error");
		}
		
		return validated;
	}

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
		    	<form id="form" class="form-horizontal" name="form" action="/utility/sendmessage" method="post">
		    	
					<div class="control-group">
						<label for="message" class="control-label">Message</label>
						<div class="controls">
							<div id="message">
								<textarea name="message" class="input-xlarge" rows="3"></textarea>
							</div>
						</div>
					</div>
					
					<div class="form-actions">
						<button class="btn btn-mini btn-primary" type="button" onclick="submitForm();">send</button>
						<button class="btn btn-mini" onclick="cancelForm(); return false;">cancel</button>
					</div>
					
					<input name="userId" type="hidden" value="<?php echo $component_target_user->getId(); ?>">
					<input name="returnUrl" type="hidden" value="<?php echo $component_return_url; ?>">
					
				</form>
			 </div>	
		 </div>		 
    </div>
</div>
<!-- end: component_form_message -->


