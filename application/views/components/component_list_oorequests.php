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
use momo\core\helpers\StringHelper;
use momo\core\helpers\DateTimeHelper;

?>


<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/managerequests/deleterequest/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/managerequests/displayeditrequestform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no requests available
			if ( $component_requests->count() == 0 ) :
		?>

			displayAlert("alertBox", "You have no requests on record.", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

<!-- begin: component_requests_list -->
<div class="row">

	<div class="offset1 span10">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php if ( $component_requests->count() != 0 ) : ?>  
	    		  		
				    <table class="table table-striped table-condensed">
				    
				      	<thead>
						    <tr>
							    <th>Type</th>
							    <th>From</th>
							    <th>Until</th>
							    <th>Comment</th>
							    <th>Status</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
				   		
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_requests as $curRequest ) :
	
									// in case of system type, get pretty name
									$curTypeName = $curRequest->getOOBooking()->getOOBookingType()->getType();
									if ( $curRequest->getOOBooking()->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
										$curTypeName = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$curRequest->getOOBooking()->getOOBookingType()->getType()];
									}
							?>		
							    <tr>
								    <td width="15%"><?php echo $curTypeName; ?></td>
								    <td width="15%"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curRequest->getOOBooking()->getStartDate()); ?></td>
								    <td width="15%"><?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curRequest->getOOBooking()->getEndDate());  ?></td>
								    <td width="35%"><?php echo trim(StringHelper::limitString($curRequest->getOriginatorComment(), 45)) != "" ? StringHelper::limitString($curRequest->getOriginatorComment(), 45) : "(none)"; ?></td>
								    <td width="10%"><?php echo \OORequest::$STATUS_MAP[$curRequest->getStatus()]; ?></td>
								    <td width="10%">
								    
								    	<?php 
								    		// edit and delete ops are only available for statused "DENIED"
								    		if ( $curRequest->getStatus() == \OORequest::STATUS_DENIED ) :
								    	?>
								    		<a class="btn btn-mini" href="javascript:editEntry(<?php echo $curRequest->getId(); ?>);">edit</a>
		
								    	<?php endif; ?>
								    	
								    	<?php 
								    		// edit and delete ops are only available for statused "DENIED"
								    		if ( ($curRequest->getStatus() == \OORequest::STATUS_DENIED) ) :
								    	?>
									    	<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the request?', 'javascript:deleteEntry(<?php echo $curRequest->getId(); ?>);', 'delete')">delete</a>
	  	
								    	<?php endif; ?>
								    	
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				 
				<?php endif; ?>   
				
				<button class="btn btn-mini" onclick="document.location='/managerequests/displaynewrequestform'">new</button>    
			   
			</div>
			
		</div>
	
    </div>
 
</div>

<!-- end: component_requests_list -->
