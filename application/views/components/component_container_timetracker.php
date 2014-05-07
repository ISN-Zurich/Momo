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

<!-- begin: component_container_timetracker -->
<script>
	//
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		// initialize tooltips
		$('#popover_sumweekhours').popover();
		$('#popover_deltaweekhours').popover();
		$('#popover_deltatotalhours').popover();
		$('#popover_totalweekhours').popover();
		$('#popover_vacationtaken').popover();
		$('#popover_vacationbooked').popover();
		$('#popover_vacationbalance').popover();
		$('#popover_incompleteweeks').popover();
		$('#popover_unlockedweeks').popover();
	});
</script>

<div class="row">
	<div class="span9">
		<?php $this->load->view("components/component_other_timetracker.php"); ?>
    </div>
    <div class="span3">
		<div class="well" >
			 <div id="datepicker"></div>
			 <div style="margin-top: 10px;" align="center"><a class="btn btn-mini" href="javascript:jumpToToday();">today</a></div>
		</div>
		<div class="well">
			<table class="table table-striped table-condensed" style="margin-bottom: 0;">
				<tr>
		   			<td width="65%" style="padding-left: 1px; border: none;" >
		   				<strong>&#931; Hours (week)</strong>
		   			</td>
		   			<td width="35%" style="border: none; text-align: right;">
		   				<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($timetracker_worktime_credit_week_in_sec) . " h" ; ?>
		   			</td>
		   			<td width="*" style="border: none;">
		   				<span 	id="popover_sumweekhours"
		   						data-content="Reflects the current week's recorded work-time."
		   						data-original-title="&#931; Hours (week)"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
				<tr>
		   			<td style="padding-left: 1px; border: none;" >
		   				<strong>&#916; Hours (week)</strong>
		   			</td>
		   			<td style="border: none; text-align: right;">
		   				<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($timetracker_timedelta_week_insec, true) . " h" ; ?>
		   			</td>
		   			<td style="border: none;">
		   				<span 	id="popover_deltaweekhours"
		   						data-content="Reflects the hours short of (or in excess of) the current week's plan time."
		   						data-original-title="&#916; Hours (week)"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
				<tr>
		   			<td style="padding-left: 1px; border: none;" >
		   				<strong>&#916; Hours (total)</strong>
		   			</td>
		   			<td style="border: none; text-align: right;">
		   				<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($timetracker_timedelta_total_insec, true) . " h" ; ?>
		   			</td>
		   			<td style="border: none;">
		   				<span 	id="popover_deltatotalhours"
		   						data-content="Reflects your total over-/undertime up to and including the present day."
		   						data-original-title="&#916; Hours (total)"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
				<tr>
		   			<td style="padding-left: 1px; border: none;" >
		   				<strong>Total Hours (e.o.w.)</strong>
		   			</td>
		   			<td style="border: none; text-align: right;">
		   				<?php echo DateTimeHelper::formatTimeValueInSecondsToHHMM($timetracker_totalweekhours_insec, true) . " h" ; ?>
		   			</td>
		   			<td style="border: none;">
		   				<span 	id="popover_totalweekhours"
		   						data-content="Reflects your total over-/undertime up to and including the last day of the week."
		   						data-original-title="Total Hours (e.o.w.)"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
			</table>

			<table class="table table-striped table-condensed" style="margin: 15px 0 0 0;">
				<tr>
		   			<td width="60%" style="padding-left: 1px; border: none;" >
		   				<strong>Vacation taken</strong>
		   			</td>
		   			<td width="35%" style="border: none; text-align: right;">
		   				<?php printf("%.2f days", round($timetracker_vacation_taken, 2)); ?>
		   			</td>
		   			<td width="*" style="border: none;">
		   				<span 	id="popover_vacationtaken"
		   						data-content="Reflects the number of vacation days already taken in the current year."
		   						data-original-title="Vacation taken"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
				<tr>
		   			<td style="padding-left: 1px; border: none;" >
		   				<strong>Vacation booked</strong>
		   			</td>
		   			<td style="border: none; text-align: right;">
		   				<?php printf("%.2f days", round($timetracker_vacation_booked, 2)); ?>
		   			</td>
		   			<td style="border: none;">
		   				<span 	id="popover_vacationbooked"
		   						data-content="Reflects the number of vacation days booked.<br><br>'Booked days' are those entered into Momo which have not yet been consumed."
		   						data-original-title="Vacation booked"
		   						class="icon-question-sign"></span>
		   			</td>
				</tr>
				<tr>
		   			<td style="padding-left: 1px; border: none;" >
		   				<strong>Vacation balance</strong>
		   			</td>
		   			<td style="border: none; text-align: right;">
		   				<?php printf("%.2f days", round($timetracker_vacation_balance_effective, 2)); ?>
		   				<em><?php printf("%.2f days", round($timetracker_vacation_balance_projected, 2)); ?></em>
		   			</td>
		   			<td style="border: none;">
		   				<span id="popover_vacationbalance"
		   					  data-content="Your overall vacation balance.<br><br>The top figure takes into account the end of your contract.<br><br>The bottom figure reflects the balance up to the end of the calendar year, irrespective of contract."
		   					  data-original-title="Vacation balance"
		   					  class="icon-question-sign"></span>
		   			</td>
				</tr>
			</table>
		</div>

		<div class="well">
			<table class="table table-striped table-condensed" style="margin-bottom: 0;">

				<tr>
		   			<td width="20%" style="padding-left: 1px; border: none;">
		   				<strong>Team</strong>
		   			</td>
		   			<td width="*" style="border: none;">
		   				<?php print($timetracker_displayuser->getPrimaryTeam() !== null ? $timetracker_displayuser->getPrimaryTeam()->getName() : "(none)"); ?>
		   			</td>
				</tr>

				<tr>
		   			<td style="padding-left: 1px; border: none;"><strong>Leaders</strong></td>
		   			<td style="border: none;">
		   				<?php
		   					//
		   					// if the user is assigned to a team, list the team leaders
		   					if ( $timetracker_displayuser->getPrimaryTeam() !== null ) {
		   						// list the leaders
		   						foreach ( $timetracker_displayuser->getPrimaryTeam()->getTeamLeaderFullNames() as $curLeaderName ) {
		   							echo $curLeaderName . "<br>";
		   						}
		   					}
		   					else {
		   						echo "(none)";
		   					}

		   				?>
		   			</td>
				</tr>

				<tr>
		   			<td style="padding-left: 1px; border: none;"><strong>Projects</strong></td>
		   			<td style="border: none;">
						<?php if ( $timetracker_projects->count() !== 0 ) : ?>

							<?php foreach( $timetracker_projects as $curProject) : ?>

								<?php echo $curProject->getName(); ?><br>

							<?php endforeach; ?>

						<?php else : ?>

							(none)

						<?php endif; ?>
					</td>
				</tr>

			</table>

		</div>

		<div class="well">

			<table class="table table-striped table-condensed" style="margin-bottom: 0;">

				<tr>
		   			<td width="60%" style="padding-left: 1px; border: none;">
		   				<strong>Incomplete Weeks</strong>
		   			</td>
		   			<td width="35%" style="border: none;">
		   				<?php echo $timetracker_incomplete_weeks->count(); ?>
		   			</td>
		   			<td width="*" style="border: none;">

		   				<?php
		   					// generate a list of the weeks deemed incomplete
		   					$incompleteWeekList = "";
		   					foreach ( $timetracker_incomplete_weeks as $curWeek ) {
		   						$incompleteWeekList .= DateTimeHelper::formatDateTimeToPrettyDateFormat($curWeek->getDateOfDay()) . "<br>";
		   					}

		   					// set tooltip text according to whether we have incomplete weeks or not
		   					if ( $timetracker_incomplete_weeks->count() != 0 ) {
		   						$incompleteWeekTooltipText = "The weeks starting on the following dates are incomplete:<br><br>";
		   						$incompleteWeekTooltipText .= $incompleteWeekList;
		   					}
		   					else {
		   						$incompleteWeekTooltipText = "Presently, there are no incomplete weeks.";
		   					}
		   				?>

		   				<span id="popover_incompleteweeks" data-content="<?php echo $incompleteWeekTooltipText; ?>" data-original-title="Incomplete Weeks" class="icon-question-sign"></span>
		   			</td>
				</tr>

				<tr>
		   			<td style="padding-left: 1px; border: none;">
		   				<strong>Unlocked Weeks</strong>
		   			</td>
		   			<td style="border: none;">
		   				<?php echo $timetracker_unlocked_weeks->count(); ?>
		   			</td>
		   			<td align="right" style="border: none;">
		   				<?php
		   					// generate a list of the unlocked weeks
		   					$unlockedWeekList = "";
		   					foreach ( $timetracker_unlocked_weeks as $curWeek ) {
		   						$unlockedWeekList .= DateTimeHelper::formatDateTimeToPrettyDateFormat($curWeek->getDateOfDay()) . "<br>";
		   					}

		   					// set tooltip text according to whether we have unlocked weeks or not
		   					if ( $timetracker_unlocked_weeks->count() != 0 ) {
		   						$unlockedWeekTooltipText = "The weeks starting on the following dates are unlocked:<br><br>";
		   						$unlockedWeekTooltipText .= $unlockedWeekList;
		   					}
		   					else {
		   						$unlockedWeekTooltipText = "Presently, there are no unlocked weeks.";
		   					}
		   				?>

		   				<span id="popover_unlockedweeks" data-content="<?php echo $unlockedWeekTooltipText; ?>" data-original-title="Unlocked Weeks" class="icon-question-sign"></span>
		   			</td>
				</tr>

			</table>

		</div>

    </div>
</div>

<script>

	<?php

		$todayDay = DateTimeHelper::getDayFromDateTime($timetracker_today_date);
		$todayMonth = DateTimeHelper::getMonthFromDateTime($timetracker_today_date);
		$todayYear = DateTimeHelper::getYearFromDateTime($timetracker_today_date);

		$selectedDay = DateTimeHelper::getDayFromDateTime($timetracker_selected_date);
		$selectedMonth = DateTimeHelper::getMonthFromDateTime($timetracker_selected_date);
		$selectedYear = DateTimeHelper::getYearFromDateTime($timetracker_selected_date);

		// generate JS Date and String represenentations for "display", "selected" and "today" dates
		echo "var selectedDate = new Date($selectedYear, $selectedMonth - 1, $selectedDay);";
		echo "var todayDate = new Date($todayYear, $todayMonth - 1, $todayDay);";
		echo "var todayDateString = '" . $todayDay . "-" . $todayMonth . "-" . $todayYear . "';";
	?>

	// initialize datepicker on document ready
	$(function() {
		$( "#datepicker" ).datepicker({
			dateFormat: "d-m-yy",
			firstDay: "1",
			showOtherMonths: true,
			selectOtherMonths: true,
			onSelect: function(dateString, inst) { callTimetracker(dateString); },
			minDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($timetracker_date_min); ?>,
								<?php echo (DateTimeHelper::getMonthFromDateTime($timetracker_date_min) - 1); ?>,
								<?php echo DateTimeHelper::getDayFromDateTime($timetracker_date_min); ?>),

			maxDate: new Date(	<?php echo DateTimeHelper::getYearFromDateTime($timetracker_date_max); ?>,
								<?php echo (DateTimeHelper::getMonthFromDateTime($timetracker_date_max) - 1); ?>,
								<?php echo DateTimeHelper::getDayFromDateTime($timetracker_date_max); ?>)
		});

		//
		// set datepicker date to "selected" date
		$( "#datepicker" ).datepicker("setDate", selectedDate);

	});

	//
	// bind week highlighter to "mouseenter" event
	$('#datepicker .ui-datepicker-calendar tr').live('mouseenter', function() { setTimeout("applyWeeklyHighlight()", 100); });

	//
	// calls the timetracker with the selected date
	function callTimetracker(dateString) {

		// we only switch, if there is no active alert
		if ( ! hasActiveAlert() ) {
			var dateComps = dateString.split("-");
			document.location = "/timetracker/display/" + dateComps[0] + "/" + dateComps[1] + "/" + dateComps[2];
		}

	}

	//
	// calls the timetracker for "today"
	function jumpToToday() {
		//
		// we jump only, if today is not already selected
		if ( todayDate.compareTo(selectedDate) != 0 ) {
			callTimetracker(todayDateString);
		}
		else {
			// if today's date is in fact the selected date, we still need to make sure that
			// the today form is expanded
			var a = $("div.today a");
			openAccordionSection($("div.today a"));
		}
	}

	function applyWeeklyHighlight() {

	    $('.ui-datepicker-calendar tr').each(function() {

	        if ( $(this).parent().get(0).tagName == 'TBODY' ) {

	            $(this).mouseover(function() {
		        	$(this).find('a').css({'background':'#c7bea3'});
	            });

	            $(this).mouseout(function() {
	            	$(this).find('a').css('background','');
	            });

	        }

	    });
	}

</script>

<!-- begin: component_container_timetracker -->
