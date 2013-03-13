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

// define template uri
$templateUri = "/manageteams/displayteamsummary"; 

?>

<script>

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		//
		// event handlers for "team" selection narrowing controls
		$("#targetTeamId").change(function() {

			// for these, simply call the controller action
			$("#form").submit();
		});


		//
		// issue appropriate JS notification if there is a team selected that does not have any members
		<?php if ( ($component_targetteam !== null) && (count($component_summary_digest) == 0) ) : ?>

			var alertMsg = "<?php echo $component_targetteam->getName() ?> has no members.";
		
			displayAlert("alertBox", alertMsg, "alert-info");
			
		<?php endif; ?>

		// initialize tooltips
		$('.off_day_entry').tooltip();
			
	});
	
</script>

<!-- begin: component_list_team_summary -->
<div class="row">

	<div class="span12">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<form id="form" action="/manageteams/displayteamsummary" method="post" class="form-inline well">
	    			
					<select id="targetTeamId" name="targetTeamId" class="span2">
					
						<option value="-1" selected="selected">please select...</option>
							
						<?php foreach ( $component_teams as $curTeam ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_targetteam !== null ) : ?>
							
								<?php if ( $component_targetteam->getId() == $curTeam->getId() ) : ?>
																						
									<?php $selected = "selected='selected'" ?>
			
								<?php endif; ?>
								
							<?php endif; ?>	
							
							<option value="<?php echo $curTeam->getId(); ?>" <?php echo $selected; ?>><?php echo $curTeam->getName(); ?></option>
								
						<?php endforeach; ?>
						
					</select>
					
	    		</form>
	    	
	    	
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
				<?php foreach ( $component_summary_digest as $curDigestEntry ) : ?>
				
		    		<div class="row">
			    		<div class="span9 momo_bottom_spacer20" style="width: 1130px;">
				    		<div class="momo_section_header momo_bg_lightgray">
				    		
					    		<h4 style="float: left;">
									<?php echo $curDigestEntry["user"]->getFullName(); ?>
								</h4>
								
								<div style="float: right; margin-top: -2px;">
									<div class="btn-group">
										<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
											actions
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" style="width: 190px;">
											<?php if ( array_key_exists("incomplete_weeks", $curDigestEntry["issues"]) ) : ?>
												<li>
													<a href="javascript:void(0);"><i class="icon-chevron-right" style="float: right;"></i>unlock incomplete week</a>
											        <ul class="dropdown-menu sub-menu">
											        	<?php foreach ( $curDigestEntry["issues"]["incomplete_weeks"] as $curWeek ) : ?>
												        	<li>
																<a href="/enforcement/unlockweekforuser/<?php echo DateTimeHelper::formatDateTimeToStandardDateFormat($curWeek->getDateOfDay()); ?>/<?php echo $curDigestEntry["user"]->getId(); ?>/<?php echo FormHelper::encodeUriSegment($templateUri); ?>">
																	week of: <?php echo DateTimeHelper::formatDateTimeToPrettyDateFormat($curWeek->getDateOfDay()); ?>
																</a>
															</li>
											    		<?php endforeach; ?>	
											        </ul>
											    </li>
											<?php endif; ?>	
											<li>
												<a href="/manageteams/displayunlockspecificweekform/<?php echo $curDigestEntry["user"]->getId();?>/<?php echo FormHelper::encodeUriSegment($templateUri); ?>">unlock specific week</a>
											</li>
											<li>
												<a href="/utility/displaymessageform/<?php echo $curDigestEntry["user"]->getId(); ?>/<?php echo FormHelper::encodeUriSegment($templateUri); ?>">send message</a>
											</li>
											<li>
												<a href="/timetracker/displayforuser/<?php echo DateTimeHelper::getCurrentDay(); ?>/<?php echo DateTimeHelper::getCurrentMonth(); ?>/<?php echo DateTimeHelper::getCurrentYear(); ?>/<?php echo $curDigestEntry["user"]->getId(); ?>/">show timetracker</a>
											</li>
										</ul>
									</div>
								</div>
			    			</div>
			    			
			    			<div class="momo_section_content well" style="margin-bottom: 0px;">
				    			<table class="table table-condensed">
					    
							      	<thead>
									    <tr>
									    	<th>Time</th>
									     	<th>Vacation</th>
									     	<th>Issues</th>
										    <th>Off Days</th>
									    </tr>
							   		</thead>
									
							   		<tbody>
							    		
									    <tr>
									      	<td width="35%">
									      		<table width="100%">
										   			<tbody>
										   				<tr>
										   					<td width="40%" style="border: none; padding: 0;">&#931; Hours (current year)</td>
										   					<td width="25%" style="border: none; padding: 0; text-align: right;">
										   						<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDigestEntry["worktime_credit_current_workplan_in_sec"]) . " h" ; ?>
										   					</td>
										   					<td width="*" style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">&#931; Hours (current week)</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDigestEntry["worktime_credit_week_in_sec"]) . " h" ; ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">&#916; Hours (current week)</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDigestEntry["timedelta_week_insec"], true) . " h" ; ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">&#916; Hours (up to today)</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($curDigestEntry["timedelta_total_insec"], true) . " h" ; ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   			</tbody>
							   					</table>
									      	</td>
										   	<td width="35%">
										   		<table width="100%">
										   			<tbody>
										   				<tr>
										   					<td width="40%" style="border: none; padding: 0;">taken</td>
										   					<td width="25%" style="border: none; padding: 0; text-align: right;">
										   						<?php printf("%.1f days", round($curDigestEntry["vacation_digest"]["vacation_days_consumed_by_workplan"][DateTimeHelper::getCurrentYear()], 1)); ?>
										   					</td>
										   					<td width="*" style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">booked</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php printf("%.1f days", round($curDigestEntry["vacation_digest"]["aggregate_results"]["global_vacation_days_booked"], 1)); ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">balance (contract)</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php printf("%.1f days", round($curDigestEntry["vacation_digest"]["aggregate_results"]["global_vacation_days_balance_effective"], 1)); ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   				<tr>
										   					<td style="border: none; padding: 0;">balance (end of year)</td>
										   					<td style="border: none; padding: 0; text-align: right;">
										   						<?php printf("%.1f days", round($curDigestEntry["vacation_digest"]["aggregate_results"]["global_vacation_days_balance_projected"], 1)); ?>
										   					</td>
										   					<td style="border: none; padding: 0;"></td>
										   				</tr>
										   			</tbody>
							   					</table>
										   	</td>
										   	<td width="20%">
										   	
										   		<table width="100%">
										   			<tbody>
										   			
										   				<?php if ( count($curDigestEntry["issues"]) > 0 ) : ?>
										   			
											   				<?php if ( array_key_exists("open_requests_count", $curDigestEntry["issues"]) ) : ?>
												   				<tr>
												   					<td style="border: none; padding: 0;">
												   						<?php echo $curDigestEntry["issues"]["open_requests_count"]; ?>
												   						open <?php echo $curDigestEntry["issues"]["open_requests_count"] == 1 ? "request" : "requests"; ?>
												   					</td>
												   				</tr>
												   			<?php endif; ?>		
											   			
											   				<?php if ( array_key_exists("pending_requests_count", $curDigestEntry["issues"]) ) : ?>
												   				<tr>
												   					<td style="border: none; padding: 0;">
												   						<?php echo $curDigestEntry["issues"]["pending_requests_count"]; ?>
												   						pending <?php echo $curDigestEntry["issues"]["pending_requests_count"] == 1 ? "request" : "requests"; ?>
												   					</td>
												   				</tr>
												   			<?php endif; ?>			
											   				
															<?php if ( array_key_exists("incomplete_weeks_count", $curDigestEntry["issues"]) ) : ?>
												   				<tr>
												   					<td style="border: none; padding: 0;">
												   						<?php echo $curDigestEntry["issues"]["incomplete_weeks_count"]; ?>
												   						incomplete <?php echo $curDigestEntry["issues"]["incomplete_weeks_count"] == 1 ? "week" : "weeks"; ?>
												   					</td>
												   				</tr>
											   				<?php endif; ?>	
											   				
											   				<?php if ( array_key_exists("has_excessive_overtime", $curDigestEntry["issues"]) ) : ?>
												   				<tr>
												   					<td style="border: none; padding: 0;">excessive overtime</td>
												   				</tr>
											   				<?php endif; ?>	
											   				
											   			<?php else : ?>	
											   				
											   					<tr>
												   					<td style="border: none; padding: 0;">(none)</td>
												   				</tr>
											   				
											   			<?php endif; ?>		
										   				
										   			</tbody>
							   					</table>
										  
											</td>
										   	<td width="15%">
										   	
										   		<?php if ( $curDigestEntry["user"]->hasOffDays() ): ?> 
								    
											    	<?php foreach ( FormHelper::$workDayOptions as $curWorkDayValue => $curWorkDayText ) : ?>
											    		
											    		<?php if ( $curDigestEntry["user"]->getDayOffValueForWeekDayNumber($curWorkDayValue) !== null ) : ?>
											    			
										    				<?php
										    					// prepare class information and tooltip text for off day indicators
										    					if ( $curDigestEntry["user"]->getDayOffValueForWeekDayNumber($curWorkDayValue) == "full" ) {
										    						$classes = "momo_underline off_day_entry";
										    						$toolTipText = "full day";
										    					}
										    					else if ( $curDigestEntry["user"]->getDayOffValueForWeekDayNumber($curWorkDayValue) == "half-am" ) {
										    						$classes = "off_day_entry";
										    						$toolTipText = "half day (am)";
										    					}
										    					else if ( $curDigestEntry["user"]->getDayOffValueForWeekDayNumber($curWorkDayValue) == "half-pm" ) {
										    						$classes = "off_day_entry";
										    						$toolTipText = "half day (pm)";
										    					}
										    				?>
					
										    				<span data-original-title="<?php echo $toolTipText; ?>" class="<?php echo $classes; ?>"><?php echo FormHelper::$workDayOptions[$curWorkDayValue] ?></span>
											    				
											    		<?php endif; ?>
											    
											   		<?php endforeach; ?>
											   		
											   	<?php else : ?>	
											   		
											   		(none)
											   		
											   	<?php endif; ?>	
										   	</td>
									    </tr>
		
									</tbody>
							    </table>
							
							</div>
							
						</div>
					</div>
	    		
	    		<?php endforeach; ?>
			   
			</div>
			
		</div>
			 	 
    </div>
 
</div>

<!-- end: component_list_team_summary -->
