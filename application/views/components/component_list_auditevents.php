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
use momo\core\helpers\ManagerHelper;
use momo\core\helpers\FormHelper;

?>

<script>

	//
	// resets the indicated date control
	//
	function resetDate(dateBoxId) {
		$("#" + dateBoxId).val("");
		$("#form").submit();
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		//
		// event handlers for "user" and "module" selection narrowing controls
		$("#targetUserId, #targetModule").change(function() {

			// for these, simply call the controller action
			$("#form").submit();

		});

		//
		// event handlers for "from" and "until" selection narrowing controls
		$("#fromDate, #untilDate").change(function() {

			// for the date forms, ensure that dates are consistent
			// mark error if there's a conflict, otherwise call controller action
			var fromDateValue 		= $("#fromDate").val();
			var untilDateValue 		= $("#untilDate").val();

			// conversions below yield null if the date field was empty
			// hence we can apply them directly and test for empty field (i.e. null) later
			var fromDate = Date.parseExact(fromDateValue, "d-M-yyyy");
			var untilDate = Date.parseExact(untilDateValue, "d-M-yyyy");

			if (		(fromDate != null)
					&& 	(untilDate != null)
					&&	(fromDate.getTime() > untilDate.getTime()) ) {

				// mark opposing field with error
				if ($(this).attr("id") == "untilDate") {
					$("#fromDate").addClass("momo_error");
					$("#untilDate").removeClass("momo_error");
				}
				else {
					$("#untilDate").addClass("momo_error");
					$("#fromDate").removeClass("momo_error");
				}
				
			}
			// all good, call controller action
			else {
				$("#form").submit();
			}
			
		});
		

		//
		// set up datepicker instances
		
		// set up datepicker for from date
		$("#fromDate").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOn: "button",
			buttonText: "display events from this date onwards",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});

		// datepicker for until date
		$("#untilDate").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			buttonText: "display events up to this date",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});


		//
		// issue appropriate JS notification if there view is called with empty audit event collection
		<?php if ( 		($component_auditevents !== null)
					&&  ($component_auditevents->count() == 0) ) :
		?>
		
			displayAlert("alertBox", "No events recorded to the audit trail.", "alert-info");
		
		<?php endif; ?>
		
	});
	
</script>

<!-- begin: component_auditevents_list -->
<div class="row">
	<div class="span12">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">
	    	
	    		<form id="form" action="/manageaudittrail/viewaudittrail" method="post" class="form-inline well">
	    			
					<select id="targetUserId" name="targetUserId" class="span2">
					
						<option value="-1" selected="selected">(all users)</option>
							
						<?php foreach ( $component_users as $curUser ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_targetuser !== null ) : ?>
							
								<?php if ( $component_targetuser->getId() == $curUser->getId() ) : ?>
																						
									<?php $selected = "selected='selected'" ?>
			
								<?php endif; ?>
								
							<?php endif; ?>	
							
							<option value="<?php echo $curUser->getId(); ?>" <?php echo $selected; ?>><?php echo $curUser->getFullName(); ?></option>
								
						<?php endforeach; ?>
												
					</select>
					
					<select id="targetModule" name="targetModule" class="span2">
					
						<option value="-1" selected="selected">(all modules)</option>
							
						<?php foreach ( $component_audited_managers_map as $curManagerVal => $curManagerOpt ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_targetmodule !== null ) : ?>
							
								<?php if ( $component_targetmodule == $curManagerVal ) : ?>
																						
									<?php $selected = "selected='selected'" ?>
			
								<?php endif; ?>
								
							<?php endif; ?>	
							
							<option value="<?php echo $curManagerVal; ?>" <?php echo $selected; ?>><?php echo $curManagerOpt; ?></option>
								
						<?php endforeach; ?>
						
					</select>
					
					<?php $component_fromdate != null ? $fromDateString = DateTimeHelper::formatDateTimeToStandardDateFormat($component_fromdate) :  $fromDateString = "" ?>		
					
					<input type="text" id="fromDate" name="fromDate" class="datePicker input-xlarge" placeholder="from" value="<?php echo $fromDateString; ?>" style="width: 80px; margin-left: 30px;" readonly="readonly">
					<a href="javascript:resetDate('fromDate');" title="reset"><i class="icon-remove" style="vertical-align: middle; margin-top: 1px;"></i></a>
					
					<?php $component_untildate != null ? $untilDateString = DateTimeHelper::formatDateTimeToStandardDateFormat($component_untildate) :  $untilDateString = "" ?>	
					
					<input type="text" id="untilDate" name="untilDate" class="datePicker input-xlarge" placeholder="until" value="<?php echo $untilDateString; ?>" style="width: 80px; margin-left: 10px;" readonly="readonly">
					<a href="javascript:resetDate('untilDate');" title="reset"><i class="icon-remove" style="vertical-align: middle; margin-top: 1px;"></i></a>	
					
	    		</form>
	    	
	    		<?php 
	    														
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	  			<?php 
	
	  				if ( 		($component_auditevents !== null)
							&&  ($component_auditevents->count() > 0) ) :
				?>
	    		    	
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>Timestamp</th>
							    <th>User</th>
							    <th>Module</th>
							    <th>Action</th>
							    <th>Details</th>
						    </tr>
				   		</thead>
		
				   		<tbody>
			  
					    	<?php foreach ( $component_auditevents as $curEvent ) : ?>		
					    
							    <tr>
								    <td width="12%"><?php echo $curEvent->getTimeStamp()->format("j M Y - Hi"); ?></td>
								    <td width="12%"><?php echo $curEvent->getUser()->getFullName(); ?></td>
								    <td width="15%"><?php echo ManagerHelper::$MANAGERS_AUDITED_MAP[$curEvent->getSourceKey()]; ?></td>
								    <td width="15%"><?php echo $curEvent->getAction(); ?></td>
								    <td width="46%">
								   		<?php 
								   				// unserialize event message
								   				$eventMessage = unserialize($curEvent->getDetails());
								   			
								   				// render event message items
								   				foreach ( $eventMessage as $curMessageItem ) :
								   		
								   					if ( is_array($curMessageItem["item_value"]) ) : 
								   		?>
									   					<table width="100%">
									   					
									   						<tr>
										   						<td style="border: none; padding: 0;" width="20%"><?php echo $curMessageItem["item_name"]; ?></td>
										   						
										   						<td style="border: none; padding: 0 0 0 5px;" width="*">
											   						<?php foreach ( $curMessageItem["item_value"] as $curItemValue ) : ?>
											   							
											   								<?php echo $curItemValue . "<br>"; ?>
											   							
											   						<?php endforeach; ?>
									   							</td>
									   							
									   						</tr>
									   					
									   					</table>
	
								   				<?php else : ?>
								   				
								   					<?php echo $curMessageItem["item_name"] . ": " . $curMessageItem["item_value"] . "<br>"; ?>
								   				
								   				<?php endif;?>	
								   				
								   		<?php endforeach; ?>
				
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
			    	</table>
			    	
			     <?php endif; ?>
			     	
			</div> 	
		</div>
		 
    </div>
</div>

<!-- end: component_auditevents_list -->
