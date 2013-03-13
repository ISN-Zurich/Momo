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
		// event handlers for "year" selection narrowing controls
		$("#targetYear").change(function() {

			// for these, simply call the controller action
			$("#form").submit();
		});


		// initialize tooltips
		$('.oo_entry').tooltip();
			
	});
	
	
</script>

<!-- begin: component_oo_summary -->
<div class="row">

	<div class="span12">
	
		<div class="momo_panel">
		
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<form id="form" action="/reports/displayoosummary" method="post" class="form-inline well">
	    			
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
					
					<select id="targetYear" name="targetYear" class="span1">
			
						<?php foreach ( $component_workplans as $curWorkplan ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_targetworkplan !== null ) : ?>
							
								<?php if ( $component_targetworkplan->getYear() == $curWorkplan->getYear() ) : ?>
																						
									<?php $selected = "selected='selected'" ?>
			
								<?php endif; ?>
								
							<?php endif; ?>	
							
							<option value="<?php echo $curWorkplan->getYear(); ?>" <?php echo $selected; ?>><?php echo $curWorkplan->getYear(); ?></option>
								
						<?php endforeach; ?>
					
					</select>
					
	    		</form>
	    	
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php if ( $component_targetuser !== null ) : ?>
	    		
		    		<div class="well" style="min-height: 700px;">
					
						<div class="momo_horizcenter">
						
							<?php foreach ( $component_oosummary as $month => $monthSummary ) : ?>
							
								<?php 
									if ( strstr($month, "month_") ) :
									
										$monthComps = explode("_", $month);
								?>
										<div class="momo_box" style="margin-left: 0px; padding-left: 0px; height: 200px;">
										
											<div class="momo_section_header momo_bg_lightgray" style="padding-left: 10px;">
												<h4><?php echo DateTimeHelper::getMonthStringFromNumber($monthComps[1]); ?> <?php echo $component_targetworkplan->getYear(); ?></h4>
											</div>
											
											<div class="momo_section_content" style="margin-left: 5px;">
											
												<table class="table table-striped table-condensed">
												
													<thead>
														<tr>
															<th><span title="Monday">Mo</span></th>
															<th><span title="Tuesday">Tu</span></th>
															<th><span title="Wednesday">We</span></th>
															<th><span title="Thursday">Th</span></th>
															<th><span title="Friday">Fr</span></th>
															<th><span title="Saturday">Sa</span></th>
															<th><span title="Sunday">Su</span></th>
														</tr>
													</thead>
													
													<?php
														// render the days of the month with the first of the month
														// offset to the appropriate weekday
														
														// if indicated, offset first day of month to proper weekday
														$firstDayOfMonthWeekDayNum = $monthSummary["day_1"]["weekday_number"];
														$firstDayIsOffset = false;
														if ( $firstDayOfMonthWeekDayNum > 1 ) {
															// set a flag to indicate offset
															$firstDayIsOffset = true;
															
															echo "<tr>";
															for ( $offset = 1; $offset < $firstDayOfMonthWeekDayNum; $offset++ ) {
																echo "<td></td>";
															}
														}
													?>		
							      	
													<?php
														//
														// render the days of the month with the first of the month
														// offset to the appropriate weekday
														foreach ( $monthSummary as $day => $daySummary) :
					
															$dayComps = explode("_", $day);
															
															//
															// set style and tooltip info according to booking/entry
															$ooStyle = "";
															$ooTooltip = "";
			
															if ( $daySummary["oobooking"] !== null ) {
																//
																// set booking color as indicated in the summary
																$ooStyle = " background-color: #" . $daySummary["oobooking_color"];
																
																// if the booking is based on an unapproved request, we prepend the string to signify that
																if ( 	($daySummary["oobooking"]->getOORequest() !== null)
																	 && ($daySummary["oobooking"]->getOORequest()->getStatus() !== \OORequest::STATUS_APPROVED) ) {
																	 	
																	$ooTooltip = "Request for ";
																}
																
																// set tooltip text to type of booking
																$ooTooltip .= $daySummary["oobooking"]->getOOBookingType()->getPrettyTypeName();
					
																// days with allotment entries are marked with oo type and an indication of the period concerned (i.e., full day, half-day)
																if ( $daySummary["allotment_ooentry"] !== null ) {
																	$ooTooltip .= " (". $daySummary["allotment_ooentry"]->getPrettyPeriodIndicator() . ")";
																}
															}
													?>
													
														<?php if ( ( ! $firstDayIsOffset) && ($daySummary["weekday_number"] - 1) % 7 == 0 ) : ?>
															<tr>
														<?php endif; ?>	
															
													   		<td class="oo_entry" data-original-title="<?php echo $ooTooltip; ?>" style="text-align: center; <?php echo $ooStyle;?>">
													   			<?php echo $dayComps[1]; ?>
													   		</td>
													   		
														<?php if ( ($daySummary["weekday_number"]) % 7 == 0 ) : ?>
															</tr>
														<?php endif; ?>	
													
													<?php endforeach; ?>
													
													<?php
														//
														// if needed pad out the month to fill fractional end weeks
														$lastDayOfMonthWeekDayNum = $daySummary["weekday_number"];
														if ( $lastDayOfMonthWeekDayNum < 7 ) {
															for ( $offset = ($lastDayOfMonthWeekDayNum + 1); $offset <= 7; $offset++ ) {
																echo "<td></td>";
															}
															
															echo "</tr>";
														}
													?>		
													
												</table>
												
											</div>
											
										</div>
										
								<?php endif; ?>	
			
							<?php endforeach; ?>
		
						</div>
		
					</div>
			   
			   <?php endif; ?>	
			   
			</div>
			
		</div>
			
    </div>
 
</div>

<!-- end: component_oo_summary -->
