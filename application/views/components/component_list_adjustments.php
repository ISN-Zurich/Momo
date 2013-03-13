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

?>

<script>


	//
	// displays the new entry form
	//
	function displayNewEntryForm() {
	
		//
		// calling the new entry form requires a user to be selected
		if ( $("#targetUserId").val() != -1 ) {
			document.location.href='/manageadjustments/displaynewadjustmentform/' + $("#targetUserId").val();
		}
		else {
			displayAlert("alertBox", "Select a user in order to create an adjustment.", "alert-info");
		}
	
	}

	//
	// deletes the indicated adjustment
	//
	function deleteEntry(entryId) {
		document.location.href='/manageadjustments/deleteadjustment/' + entryId;
	}

	//
	// edits the indicated adjustment
	//
	function editEntry(entryId) {
		document.location.href='/manageadjustments/displayeditadjustmentform/' + entryId;
	}

	
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
		$("#targetUserId, #targetType").change(function() {

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
			buttonText: "display adjustments from this date onwards",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});

		// datepicker for until date
		$("#untilDate").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			buttonText: "display adjustments up to this date",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});
		
		<?php
			// issue appropriate JS notification if there are no requests available
			if ( 	($component_targetuser !== null)
				 && ($component_adjustment_entries->count() == 0) ) :
				 
				 
				 // fine tune alert message if a target type is active
				 $alertMsg = "No adjustment entries found.";
				 if ( $component_targettype !== null ) {
				 	
				 	$alertMsg = "No entries of type '";
				 	$alertMsg .= \AdjustmentEntry::$TYPE_MAP[$component_targettype]["prettyTypeName"];
				 	$alertMsg .= "' found.";
				 }
		?>
			displayAlert("alertBox", "<?php echo $alertMsg; ?>", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

<!-- begin: component_list_adjustments -->
<div class="row">
	<div class="span12">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<form id="form" action="/manageadjustments/listadjustments" method="post" class="form-inline well">
	    			
					<select id="targetUserId" name="targetUserId" class="span2">
					
						<option value="-1" selected="selected">please select...</option>
							
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
					
					<select id="targetType" name="targetType" class="span2">
					
						<option value="-1" selected="selected">(all types)</option>
						
						<?php foreach ( \AdjustmentEntry::$TYPE_MAP as $curTypeKey => $curTypeVal ) : ?>
							
							<?php $selected = "" ?>
						
							<?php if ( $component_targettype == $curTypeKey ) : ?>
																					
								<?php $selected = "selected='selected'" ?>
		
							<?php endif; ?>
									
							<option value="<?php echo $curTypeKey; ?>" <?php echo $selected; ?>><?php echo $curTypeVal["prettyTypeName"]; ?></option>
								
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
	    		
	    		<?php if ( $component_adjustment_entries->count() != 0 ) : ?>  
	    		
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
						    	<th>Effective per</th>
							    <th>Type</th>
							    <th>Creator</th>
							    <th>Value</th>
							    <th>Reason</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
				   		
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_adjustment_entries as $curEntry ) :	
							?>		
							    <tr>
							     	<td width="12%"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curEntry->getDay()->getDateOfDay()); ?></td>
							     	<td width="20%"><?php echo \AdjustmentEntry::$TYPE_MAP[$curEntry->getType()]["prettyTypeName"]; ?></td>
							     	<td width="10%"><?php echo \AdjustmentEntry::$CREATOR_MAP[$curEntry->getCreator()]; ?></td>
								    <td width="15%">
								    
								    	<?php 
								    	
								    		// time values need to be converted to hours from native seconds
								    		$entryValue = $curEntry->getValue();
								    		
								    		if ( \AdjustmentEntry::$TYPE_MAP[$curEntry->getType()]["units"] == \AdjustmentEntry::UNIT_SECONDS ) {
								    			// convert seconds to hours
								    			$entryValue /= 3600;
								    		}
								    		
								    		// get unit label for type and adjust for plurality
								    		$prettyUnitLabel = \AdjustmentEntry::$TYPE_MAP[$curEntry->getType()]["prettyDisplayUnitNameSingular"];
								    		$prettyUnitLabel .= ($entryValue != 1) ? "s" : "";
								    	
								    		echo sprintf("%+.2f", $entryValue) . " " . $prettyUnitLabel;
								    	?>
								    	
								    </td>
								    <td width="35%"><?php echo $curEntry->getReason(); ?></td>
								    <td width="*">
								    
								    	<?php 
								    		// edit and delete controls are not available for system entries
								    		if ( $curEntry->getCreator() != \AdjustmentEntry::CREATOR_SYSTEM ) : 
								    	?>
								    
									   		<a class="btn btn-mini" href="javascript:editEntry(<?php echo $curEntry->getId(); ?>);">edit</a>
									    	<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the entry ?', 'javascript:deleteEntry(<?php echo $curEntry->getId(); ?>);', 'delete')">delete</a>
								    
								    	<?php endif; ?>
								    	
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				    
				<?php endif; ?>
				
			   <button class="btn btn-mini" onclick="displayNewEntryForm();">new</button>
			   
			</div> 	 
		</div>	
			
    </div>
 
</div>

<!-- end:  begin: component_list_adjustments  -->
