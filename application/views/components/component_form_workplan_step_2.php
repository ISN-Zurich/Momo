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

<script type="text/javascript">

	//
	// creates the plan
	//
	function processPlanForm() {
		document.location = "/manageworkplans/processplanform";		
	}

	//
	// cancels the plan creation
	//
	function cancelPlan() {
		document.location = "/manageworkplans";
	}

	//
	// returns to prior step
	//
	function stepBack() {
		document.location = "/manageworkplans/stepbacktoplanform";
	}

</script>



<!-- begin: component_entrytype_form -->
<div class="row">
	<div class="offset1 span10">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body"> 
	    	
	    		<div class="well momo_bg_lightgray">
	    			<h4>Step 2 of 2 - Review Parametrization</h4>
	    		</div>
	    	
	    		<div class="momo_section_header momo_bg_lightgray">
					<h4>Parametrization Summary</h4>
				</div>
	    	
	    		<div class="well">
	    			
	    			<table class="table table-striped table-condensed">
	    			    				
	    				<tr>
				   			<td width="20%" style="padding-left: 1px; border: none;"><strong>Year</strong></td>
				   			<td width="*" style="border: none;"><?php echo $component_post["workPlanYear"]?></td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Work Hours</strong></td>
				   			<td><?php echo $component_post["weeklyWorkHours"] ?> h/week</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Vacation Tier 1</strong></td>
				   			<td><?php echo $component_post["annualVacationDaysUpTo19"] ?> days/year</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Vacation Tier 2</strong></td>
				   			<td><?php echo $component_post["annualVacationDays20to49"] ?> days/year</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Vacation Tier 3</strong></td>
				   			<td><?php echo $component_post["annualVacationDaysFrom50"] ?> days/year</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Full Day Holidays</strong></td>
				   			<td>
				   				<?php if ( isset($component_post["fullDayHolidays"]) ) : ?>
				   					
					   				<?php foreach ( $component_post["fullDayHolidays"] as $curEntry ) : ?>
					   				
					   					 <?php echo DateTimeHelper::getDateTimeFromStandardDateFormat($curEntry)->format("d M Y") . "<br />"; ?>
					   				
					   				<?php endforeach; ?>
					   				
					   			<?php else: ?>
					   					
					   				(none)
					   				
					   			<?php endif; ?>	
					   			
				   			</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>Half Day Holidays</strong></td>
				   			<td>
				   				<?php if ( isset($component_post["halfDayHolidays"]) ) : ?>
				   			
					   				<?php foreach ( $component_post["halfDayHolidays"] as $curEntry ) : ?>
					   				
					   					 <?php echo DateTimeHelper::getDateTimeFromStandardDateFormat($curEntry)->format("d M Y") . "<br />"; ?>
					   				
					   				<?php endforeach; ?>
					   				
					   			<?php else: ?>
					   					
					   				(none)
					   				
					   			<?php endif; ?>	
				   			</td>
						</tr>
						<tr>
				   			<td style="padding-left: 1px;"><strong>One Hour Holidays</strong></td>
				   			<td>
				   				<?php if ( isset($component_post["oneHourHolidays"]) ) : ?>
				   			
					   				<?php foreach ( $component_post["oneHourHolidays"] as $curEntry ) : ?>
					   				
					   					 <?php echo DateTimeHelper::getDateTimeFromStandardDateFormat($curEntry)->format("d M Y") . "<br />"; ?>
					   				
					   				<?php endforeach; ?>
					   				
					   			<?php else: ?>
					   					
					   				(none)
					   				
					   			<?php endif; ?>	
				   			</td>
						</tr>
					
	    			</table>    		
	
	    		</div>
	    	
	    		<div class="momo_section_header momo_bg_lightgray">
					<h4>Resultant Work Hours - <?php echo $component_worktime_breakdown["totalHoursYear"]; ?> h total</h4>
				</div>
				
				<div class="well" style="min-height: 750px;">
				
					<div class="momo_horizcenter">
					
						<?php foreach ( $component_worktime_breakdown as $month => $monthBreakdown ) : ?>
						
							<?php 
								if ( strstr($month, "month_") ) :
								
									$monthComps = explode("_", $month);
							?>
									<div class="momo_box">
									
										<div class="momo_section_header momo_bg_lightgray">
											<h4><?php echo DateTimeHelper::getMonthStringFromNumber($monthComps[1]); ?></h4>
										</div>
										
										<div class="momo_section_content momo_left_spacer15">
										
											<table class="table table-striped table-condensed">
						      	
												<?php
													$rowIndex = 1;
													foreach ( $monthBreakdown as $kw => $kwHours) :
				
														$kwComps = explode("_", $kw);
														
														$tdBorderStyle = "";
														if ( $rowIndex++ == 1 ) {
															$tdBorderStyle = "border: none;";
														}
												?>
												
													<tr>
											   			<td width="30%" style="padding-left: 1px; <?php echo $tdBorderStyle; ?>"><strong><?php echo "KW " . $kwComps[1]; ?></strong></td>
											   			<td width="*" align="left" style="<?php echo $tdBorderStyle; ?>"><?php printf("%.1f h", round($kwHours, 1)); ?></td>
													</tr>
											
												<?php endforeach; ?>
												
											</table>
											
										</div>
										
									</div>
									
							<?php endif; ?>	
		
						<?php endforeach; ?>
	
					</div>
	
				</div>
				
				<div class="form-actions">
				
					<button class="btn btn-mini" onclick="stepBack();" tabindex="1">&lt;&lt; back</button>&nbsp;&nbsp;&nbsp;&nbsp;
					
					<?php if ( $component_post["componentMode"] == "new" ) : ?>
					
						<button class="btn btn-mini btn-primary" onclick="processPlanForm();" tabindex="2">create workplan</button>
					
					<?php else: ?>
					   					
		   				<button class="btn btn-mini btn-primary" onclick="processPlanForm();" tabindex="3">save changes</button>
		   				
		   			<?php endif; ?>	
		
					<button class="btn btn-mini" onclick="cancelPlan();">cancel</button>
				</div>
				
			</div>
			
		</div>
		 	 
    </div>
</div>

<!-- end: component_entrytype_form -->
