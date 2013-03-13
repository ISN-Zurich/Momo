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
				if ( $(this).attr("id") == "untilDate" ) {
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
			buttonText: "report from this date onwards",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});
	
		// datepicker for until date
		$("#untilDate").datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			buttonText: "report up to this date",
			showOn: "button",
			buttonImage: "/assets/img/datepicker_cal.png",
			buttonImageOnly: true,
			showOtherMonths: true,
			selectOtherMonths: true
		});


		//
		// event handlers for changes to "reportItemKey" control
		$("#reportItemKey").change(function() {
			$("#form").submit();
		});

		//
		// event handlers for changes to "reportItemKey" control
		$("#groupBy").change(function() {
			$("#form").submit();
		});

		//
		// issue appropriate JS notification if the report comes up empty
		<?php if (		($component_targetuser !== null)
					&&  (count($component_report_data) == 0) ) : ?>
		
			displayAlert("alertBox", "There are no items to report.", "alert-info");
		
		<?php endif; ?>

	});
	
</script>

<!-- begin: component_other_timetracker_report -->
<div class="row">

	<div class="span10 offset1">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header">
				<h4><?php echo $component_title; ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    	
	    		<form id="form" action="/reports/displaytimetrackerreport" method="post" class="form-inline well">
	    			
					<select id="targetUserId" name="targetUserId" class="span2">
					
						<option value="-1" selected="selected">please select...</option>
					
						<?php foreach ( $component_allowed_users as $curUser ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_targetuser !== null ) : ?>
							
								<?php if ( $component_targetuser->getId() == $curUser->getId() ) : ?>
																						
									<?php $selected = "selected='selected'" ?>
			
								<?php endif; ?>
								
							<?php endif; ?>	
							
							<option value="<?php echo $curUser->getId(); ?>" <?php echo $selected; ?>><?php echo $curUser->getFullName(); ?></option>
								
						<?php endforeach; ?>
					
					</select>
					
					<select id="reportItemKey" name="reportItemKey" class="span2">
					
						<option value="-1" selected="selected">(all items)</option>
						
						<?php foreach ( $component_reportable_items as $curItemGroup ) : ?>
						
							<optgroup label="<?php echo $curItemGroup["group_name"];?>">
						
							<?php foreach ( $curItemGroup["group_items"] as $curItemKey => $curItemValue ) : ?>
								
								<?php $selected = "" ?>
								<?php if ( $component_target_report_item_key !== "-1" ) : ?>
								
									<?php if ( $component_target_report_item_key == $curItemKey ) : ?>
																				
										<?php $selected = "selected='selected'" ?>
				
									<?php endif; ?>
									
								<?php endif; ?>	
								
								<option value="<?php echo $curItemKey; ?>" <?php echo $selected; ?>><?php echo $curItemValue; ?></option>
									
							<?php endforeach; ?>
							
							</optgroup>
							
						<?php endforeach; ?>
							
					</select>
					
					<select id="groupBy" name="groupBy" class="span2">
					
						<?php foreach ( FormHelper::$reportGroupingOptions as $curOptKey => $curOptVal ) : ?>
							
							<?php $selected = "" ?>
							<?php if ( $component_groupby == $curOptKey ) : ?>
																		
								<?php $selected = "selected='selected'" ?>
		
							<?php endif; ?>
	
							<option value="<?php echo $curOptKey; ?>" <?php echo $selected; ?>><?php echo $curOptVal; ?></option>
								
						<?php endforeach; ?>
					
					</select>
					
					<?php $component_fromdate != null ? $fromDateString = DateTimeHelper::formatDateTimeToStandardDateFormat($component_fromdate) :  $fromDateString = "" ?>
							
					<input type="text" id="fromDate" name="fromDate" class="datePicker input-xlarge" placeholder="from" value="<?php echo $fromDateString; ?>" style="width: 80px; margin-left: 30px;" readonly="readonly">
					<a href="javascript:resetDate('fromDate');" title="reset to start of current week"><i class="icon-remove" style="vertical-align: middle; margin-top: 1px;"></i></a>
					
					<?php $component_untildate != null ? $untilDateString = DateTimeHelper::formatDateTimeToStandardDateFormat($component_untildate) :  $untilDateString = "" ?>
						
					<input type="text" id="untilDate" name="untilDate" class="datePicker input-xlarge" placeholder="until" value="<?php echo $untilDateString; ?>" style="width: 80px; margin-left: 10px;" readonly="readonly">
					<a href="javascript:resetDate('untilDate');" title="reset to one week range"><i class="icon-remove" style="vertical-align: middle; margin-top: 1px;"></i></a>	
					
	    		</form>
	    	
	    		<?php 
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php
	    			// issue reported time and project time in interval
	    			if ( count($component_report_data) != 0 ) :
	    		?>
		    		<div class="row">
		    			<div class="span9" style="width: 930px;">
			    			<div class="momo_section_content well">
				    			<table class="table table-condensed" style="margin-bottom: 0px;">
									<tbody>
										
										<tr>
											<td style="border: none;">&#931; Hours (oo work-time credit):</td>
											<td style="border: none;"><?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($component_report_oobooking_work_time) . " h" ; ?></td>
											<td style="border: none;"></td>
											
											
										</tr>
										<tr>
											<td style="border: none;">&#931; Hours (timetracker work-time credit):</td>
											<td style="border: none;">
												<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($component_report_timetracker_work_time) . " h" ; ?>
											</td>
											<td width="*" style="border: none;"></td>
										</tr>
										<tr>
											<td style="border: none;">&#931; Hours (total work-time credit):</td>
											<td style="border: none;">
												<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($component_report_total_work_time) . " h" ; ?>
											</td>
											<td width="*" style="border: none;"></td>
										</tr>
										<tr>
											<td width="27%" style="border: none;">&#931; Hours (total time recorded):</td>
											<td width="50%" style="border: none;">
												<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($component_report_total_time) . " h" ; ?>
											</td>
											<td width="*" style="border: none;"></td>
										</tr>
									</tbody>									
								</table>
							</div>
						</div>
					</div>
	
	    		<?php endif; ?>
	    		
				<?php foreach( $component_report_data as $curReportGroup ) : ?>
	
					<div class="row">
			    		<div class="span9 momo_bottom_spacer20" style="width: 930px;">
			    		
				    		<div class="momo_section_header momo_bg_lightgray">
					    		<h4 style="float: left;">
									<?php echo $curReportGroup["group_title"]; ?>
								</h4>
								
								<?php
									// actions are only shown for 
									if ( $component_groupby == "day" ) :
								?>
								
									<div style="float: right; margin-top: -2px;">
										<div class="btn-group">
											<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
												actions
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="width: 100px;">
												<li>
												
													<?php 
														// determine timetracker url for day from group meta data
														$groupDateTime = $curReportGroup["group_meta"]["group_datetime"];
														$timetrackerDayUrl = "/timetracker/displayforuser/" . DateTimeHelper::getDayFromDateTime($groupDateTime);
														$timetrackerDayUrl .= "/" . DateTimeHelper::getMonthFromDateTime($groupDateTime);
														$timetrackerDayUrl .= "/" . DateTimeHelper::getYearFromDateTime($groupDateTime);
														$timetrackerDayUrl .= "/" . $component_targetuser->getId();
													?>
												
													<a href="<?php echo $timetrackerDayUrl; ?>">show in timetracker</a>
												</li>
											</ul>
										</div>
									</div>
									
								<?php endif; ?>
			    			</div>
			    			
			    			<div class="momo_section_content well" style="margin-bottom: 0px;">
			    			
			    				<?php 
			    					//
			    					// report groups are subdivided into subgroups,
			    					//
			    					// each subgroup is rendered in a separate table with the table structured
			    					// according to meta information carried within the report data structure
			    					foreach ( $curReportGroup["subgroups"] as $curSubGroup ) :
			    				?>
						    			<table class="table table-condensed">
						    	
									      	<thead>
											    <tr style="border-bottom: 1px solid #dddddd">
											    
											    	<?php 
											    		//
											    		// render table head for current subgroup
											    		$headerColumnIndex = 0;
											    		foreach ( $curSubGroup["meta"]["subgroup_columns"] as $curColum ) :
											    	?>
											   			<th style="<?php echo $curSubGroup["meta"]["subgroup_column_css_styles"][$headerColumnIndex]; ?>"><?php echo $curColum; ?></th>
											   			
											   			<?php $headerColumnIndex++; ?>
											    
											    	<?php endforeach; ?>
											    	
											    </tr>
									   		</thead>
											
									   		<tbody>
									   		
									   			<?php
									   				// render group data
									   				// -- one row per report item
									   				foreach ( $curSubGroup["items"] as $curItem ) :
									   			?>
												    <tr>
												    
												    	<?php
											   				// render item data
											   				// -- one column per data item
											   				$itemColumnIndex = 0;
											   				foreach ( $curItem as $curItemColumn ) :
											   			?>
														      	<td width="<?php echo $curSubGroup["meta"]["subgroup_column_weights"][$itemColumnIndex]; ?>%"
														      		style="border: none; <?php echo $curSubGroup["meta"]["subgroup_column_css_styles"][$itemColumnIndex]; ?>">
																	<?php echo $curItemColumn; ?>
														      	</td>
														      	
														      	<?php $itemColumnIndex++; ?>
												      	
												      	<?php endforeach; ?>
												      	
												    </tr>
												    
												<?php endforeach; ?>
				
											</tbody>
														
									    </table>
									    
								<?php endforeach; ?>
						
							</div>
							
						</div>
						
					</div>
	
				<?php endforeach; ?>
					
			</div>
		
		</div>	
			
    </div>
 
</div>

<!-- end: component_other_timetracker_report -->
