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
			document.location.href='/managebookings/displaynewbookingform/' + $("#targetUserId").val();
		}
		else {
			displayAlert("alertBox", "To create a booking, you must first select a user.", "alert-info");
		}

	}

	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/managebookings/deletebooking/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/managebookings/displayeditbookingform/' + entryId;
	}

	function resetDate(dateBoxId) {
		$("#" + dateBoxId).val("");
		$("#form").submit();
	}


	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		//
		// event handlers for "user" selection narrowing controls
		$("#targetUserId").change(function() {

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
		<?php if ( $component_bookings->count() == 0 ) : ?>

			<?php if ( $component_targetuser !== null ) : ?>

				<?php if ( ($component_fromdate !== null) ||  ($component_untildate !== null) ) : ?>

					displayAlert("alertBox", "<?php echo $component_targetuser->getFullname(); ?> has no bookings in the indicated date range.", "alert-info");

				<?php else : ?>

					displayAlert("alertBox", "<?php echo $component_targetuser->getFullname(); ?> has no bookings on record.", "alert-info");
			
				<?php endif; ?>
		
			<?php else : ?>

				<?php if ( ($component_fromdate !== null) ||  ($component_untildate !== null) ) : ?>
	
					displayAlert("alertBox", "There are no bookings in the indicated date range.", "alert-info");
		
				<?php else : ?>
		
					displayAlert("alertBox", "There are no bookings on record.", "alert-info");
			
				<?php endif; ?>
			
			<?php endif; ?>
		
		<?php endif; ?>
		
	});
	
	
</script>

<!-- begin: component_requests_list -->
<div class="row">
	<div class="offset span12">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<form id="form" action="/managebookings/listbookings" method="post" class="form-inline well">
	    			
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
	    		
	    		<?php if ( $component_bookings->count() != 0 ) : ?>  
	    		  		
				    <table class="table table-striped table-condensed">
				    
				      	<thead>
						    <tr>
						    	<th>User</th>
						     	<th>Type</th>
						     	<th style="text-align: right;">From</th>
							    <th style="text-align: right;">Until</th>
							    <th style="text-align: right;">Days Booked</th>
							    <th></th>
							    <th>Origin</th>
							    <th>Request Status</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
				   		
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_bookings as $curBooking ) :
								
									// in case of system type, get pretty name
									$curTypeName = $curBooking->getOOBookingType()->getType();
									if ( $curBooking->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
										$curTypeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$curBooking->getOOBookingType()->getType()];
									}
										
							?>		
							    <tr>
							      	<td width="14%"><?php  echo $curBooking->getUser()->getFullName(); ?></td>
								    <td width="13%"><?php  echo $curTypeName; ?></td>
								    <td width="10%" style="text-align: right;"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curBooking->getStartDate()); ?></td>
								    <td width="12%" style="text-align: right;"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curBooking->getEndDate());  ?></td>
								    <td width="12%" style="text-align: right;"><?php printf("%.1f", $curBooking->getAllotmentSum()); ?></td>
								    <td width="3%"></td>
								    <td width="12%"><?php  echo $curBooking->getOORequest() !== null ? OOBooking::ORIGIN_USER_PRETTY : OOBooking::ORIGIN_MANAGEMENT_PRETTY; ?></td>
								    <td width="15%"><?php  echo $curBooking->getOORequest() !== null ? \OORequest::$STATUS_MAP[$curBooking->getOORequest()->getStatus()] : "(not applicable)"; ?></td>
								    <td width="*">
									    <a class="btn btn-mini" href="javascript:editEntry(<?php echo $curBooking->getId(); ?>);">edit</a>
									    <a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the booking?', 'javascript:deleteEntry(<?php echo $curBooking->getId(); ?>);', 'delete')">delete</a>
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

<!-- end: component_requests_list -->
