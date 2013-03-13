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

// ------------------------------------------------------------------------

namespace momo\reportingservice;

use momo\core\helpers\DebugHelper;
use momo\core\helpers\DateTimeHelper;
use momo\core\exceptions\MomoException;
use momo\core\services\BaseService;

/**
 * ReportingService
 * 
 * Generates the various Momo reports.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.services.reportingservice
 */
class ReportingService extends BaseService {
	
	const SERVICE_KEY = "SERVICE_REPORTING";
	
	/**
	 * Compiles a "project time" report for the indicated report target and time range
	 * 
	 * @param mixed 		$reportTarget
	 * @param DateTime 		$fromDate
	 * @param DateTime 		$untilDate
	 * 
	 * @return	array		the compiled report, for a description of the array structure, see "compileTimetrackerReport()"
	 * 
	 */
	public function compileProjectTimeReport($reportTarget, $fromDate, $untilDate) {
		
		$reportData = array();
	
		// process according to report target
		if ( $reportTarget instanceof \User ) {
			
			//
			// query for the project time grouped by project, year and month
			//
			$projectTimeByUserQuery = \ProjectEntryQuery::create()
														->filterByUser($reportTarget)
														->useDayQuery()
															->filterByDateofDay($fromDate, \DayQuery::GREATER_EQUAL)
															->filterByDateofDay($untilDate, \DayQuery::LESS_EQUAL)
															->useWorkplanQuery()
															->endUse()
														->endUse()
														->useProjectQuery()
														->endUse()
														->withColumn('SUM(ProjectEntry.timeinterval)', 'totalProjectTimeInMonthYearAndProject')
														->withColumn('MONTH(Day.dateofday)', 'month')
														->groupBy('Project.id')
														->groupBy('Workplan.id')
														->groupBy('month')
														->orderBy('Project.Name')
														->orderBy('Workplan.Year')
														->orderBy('month')
														->find();

			//
			// if there are results to report, we populate the report data structure
			if ( $projectTimeByUserQuery->count() != 0 ) {
				
				//
				// initialize report data structure
				// -- for this report there is only one group, containing one subgroup
				//						
				$reportData[0] = array();
				$reportData[0]["group_title"] = "'" . $reportTarget->getFullName() . "' - Activity by Project, Year and Month";
				$reportData[0]["group_meta"] = array(); 
										
				$reportData[0]["subgroups"] = array();
				$reportData[0]["subgroups"][0] = array();
				$reportData[0]["subgroups"][0]["items"] = array();	
				
				$reportData[0]["subgroups"][0]["meta"] = array();	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_columns"] = array("Project", "Year", "Month", "Hours", "", "");	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_weights"] = array("25", "15", "15", "10", "10", "25");
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_css_styles"] = array("", "", "", "text-align: right;", "", "text-align: left; font-weight: bold;");	
				
				//
				// compile query result into report data structure
				$reportItemIndex = 0;
				$curProcessedProjectName = "";
				$curProcessedYear = "";
				$curYearTimeSum = 0;
				$curProjectTimeSum = 0;
				foreach ( $projectTimeByUserQuery as $curProjectEntry ) {
					
					//
					// when rendering the data structure, we suppress project names and years if they
					// have been issued already --> redundant information that results in visual noise
					
					//
					// check whether we need to render the current project name
					if ( $curProcessedProjectName != $curProjectEntry->getProject()->getName() ) {
						
						// if we're not processing the first entry, we issue an empty row
						// when encountering an new project
						if ( $projectTimeByUserQuery->getPosition() !== 0 ) {
							
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "";	
							
							$reportItemIndex++;
								
						}
						
						//
						// the row pertains to a new project, accordingly, we output the project name 
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = $curProjectEntry->getProject()->getName();
						
						// update the currently processed project 
						$curProcessedProjectName = $curProjectEntry->getProject()->getName();
						
						// reset processed year, as we want it reissued when the project name changes
						$curProcessedYear = "";
						
						// reset the current project time sum
						$curProjectTimeSum = 0;
					}
					else {
						//
						// same project as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = "";
					}
					
					//
					// check whether we need to render the current year
					if ( $curProcessedYear != $curProjectEntry->getDay()->getWorkplan()->getYear() ) {
						
						//
						// the row pertains to a new year, accordingly, we output the year 
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// update the currently processed year 
						$curProcessedYear = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// reset the year time sum
						$curYearTimeSum = 0;
					}
					else {
						//
						// same year as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
					}
					
					// sum the current row's contribution into the current year's time sum 
					$curYearTimeSum += $curProjectEntry->getTotalProjectTimeInMonthYearAndProject();
					
					// sum the current row's contribution into the current project's time sum 
					$curProjectTimeSum += $curProjectEntry->getTotalProjectTimeInMonthYearAndProject();
								
					// write current row's month and hours elements to report data structure
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = DateTimeHelper::getMonthStringFromNumber($curProjectEntry->getMonth());
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectEntry->getTotalProjectTimeInMonthYearAndProject()) . " h";
					
					
					//
					// a given year's time sum needs to be output whenever the year's entries have been exhausted

					// get next element in iterator and immediately restore iterator position
					$nextProject = $projectTimeByUserQuery->getNext();
					$projectTimeByUserQuery->getPrevious();
					
					// we issue the current year's time sum if there is no further row to be processed, or if
					// the next row pertains to a different year or project
					if ( 	($nextProject === null) 
						 ||	($nextProject->getDay()->getWorkplan()->getYear() != $curProcessedYear)
						 ||	($nextProject->getProject()->getName() != $curProcessedProjectName) ) {

						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "&#931; hours (year) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curYearTimeSum) . " h";
					}
					else {
						// otherwise we set the cells to empty
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "";	
					}
					
					
					// we issue the current project's time sum if there is no further row to be processed, or if
					// the next row pertains to a different project
					// -- this occurs on a separate row
					if ( 	($nextProject === null) 
						 ||	($nextProject->getProject()->getName() != $curProcessedProjectName) ) {

						$reportItemIndex++; 	
						 	
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "&#931; hours (project) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectTimeSum) . " h";

					}
				
					$reportItemIndex++;
				}															
													
			}
		}
		else if ( $reportTarget instanceof \Team ) {
			
			//
			// query for the project time grouped by year, month and project
			//
			$projectTimeByTeamQuery = \ProjectEntryQuery::create()
															->filterByTeam($reportTarget)
															->useDayQuery()
																->filterByDateofDay($fromDate, \DayQuery::GREATER_EQUAL)
																->filterByDateofDay($untilDate, \DayQuery::LESS_EQUAL)
																->useWorkplanQuery()
																->endUse()
															->endUse()
															->useProjectQuery()
															->endUse()
															->withColumn('SUM(ProjectEntry.timeinterval)', 'totalProjectTimeInProjectMonthAndYear')
															->withColumn('MONTH(Day.dateofday)', 'month')
															->groupBy('Workplan.id')
															->groupBy('month')
															->groupBy('Project.id')
															->orderBy('Workplan.Year')
															->orderBy('month')
															->orderBy('Project.Name')
															->find();
			
			
			//
			// if there are results to report, we populate the report data structure
			if ( $projectTimeByTeamQuery->count() != 0 ) {
				
				//
				// initialize report data structure
				// -- for this report there is only one group, containing one subgroup
				//						
				$reportData[0] = array();
				$reportData[0]["group_title"] = "'" . $reportTarget->getName() . "' - Activity by Year, Month and Project";
				$reportData[0]["group_meta"] = array();
				
				$reportData[0]["subgroups"] = array();
				$reportData[0]["subgroups"][0] = array();
				$reportData[0]["subgroups"][0]["items"] = array();	
				
				$reportData[0]["subgroups"][0]["meta"] = array();	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_columns"] = array("Year", "Month", "Project", "Hours", "", "");	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_weights"] = array("10", "15", "20", "10", "10", "45");
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_css_styles"] = array("", "", "", "text-align: right;", "", "text-align: left; font-weight: bold;");	
				
				//
				// compile query result into report data structure
				$reportItemIndex = 0;
				$curProcessedMonth = "";
				$curProcessedYear = "";
				$curYearTimeSum = 0;
				$curMonthTimeSum = 0;
				foreach ( $projectTimeByTeamQuery as $curProjectEntry ) {
					
					//
					// when rendering the data structure, we suppress months and years if they
					// have been issued already
					
					//
					// check whether we need to render the current year
					if ( $curProcessedYear != $curProjectEntry->getDay()->getWorkplan()->getYear() ) {
						
						// if we're not processing the first entry, we issue an empty row
						// when encountering an new year
						if ( $projectTimeByTeamQuery->getPosition() !== 0 ) {
							
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "";	
							
							$reportItemIndex++;
								
						}
						
						//
						// as the row pertains to a new year, we write it to the current row
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// update the currently processed year 
						$curProcessedYear = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// reset processed month, as we want it reissued when the year changes
						$curProcessedMonth = "";
						
						// reset the current year time sum
						$curYearTimeSum = 0;
					}
					else {
						//
						// same year as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
					}
					
					//
					// check whether we need to render the current month
					if ( $curProcessedMonth != $curProjectEntry->getMonth() ) {
						
						//
						// output the month, as it does not match the currently processed one
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = DateTimeHelper::getMonthStringFromNumber($curProjectEntry->getMonth());
						
						// update the currently processed year 
						$curProcessedMonth = $curProjectEntry->getMonth();
						
						// reset the month's time sum
						$curMonthTimeSum = 0;
					}
					else {
						//
						// same year as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
					}
					
					// sum the current row's contribution into the current year's time sum 
					$curYearTimeSum += $curProjectEntry->getTotalProjectTimeInProjectMonthAndYear();
					
					// sum the current row's contribution into the current month's time sum 
					$curMonthTimeSum += $curProjectEntry->getTotalProjectTimeInProjectMonthAndYear();
								
					// write current row's project and hours elements to report data structure
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = $curProjectEntry->getProject()->getName();					
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"]	 = DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectEntry->getTotalProjectTimeInProjectMonthAndYear()) . " h";
					
					
					//
					// a given month's time sum needs to be output whenever the month's entries have been exhausted

					// get next element in iterator and immediately restore iterator position
					$nextProject = $projectTimeByTeamQuery->getNext();
					$projectTimeByTeamQuery->getPrevious();
					
					// we issue the current month's time sum if there is no further row to be processed, or if
					// the next row pertains to a different month or year
					if ( 	($nextProject === null) 
						 ||	($nextProject->getMonth() != $curProcessedMonth) 
						 ||	($nextProject->getDay()->getWorkplan()->getYear() != $curProcessedYear) ) {

						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "&#931; hours (month) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curMonthTimeSum) . " h";
						
					}
					else {
						// otherwise we set the cells to empty
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "";	
					}
					
					
					// we issue the current year's time sum if there is no further row to be processed, or if
					// the next row pertains to a different year
					// -- this occurs on a separate row
					if ( 	($nextProject === null) 
						 ||	($nextProject->getDay()->getWorkplan()->getYear() != $curProcessedYear) ) {

						$reportItemIndex++; 	
						
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["project"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "&#931; hours (year) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curYearTimeSum) . " h";	

					}
				
					$reportItemIndex++;
				}															
													
			}
															
		}
		else if ( $reportTarget instanceof \Project ) {
			
			//
			// query for the project time grouped by year, month and team
			//
			$projectTimeByProjectQuery = \ProjectEntryQuery::create()
														->filterByProject($reportTarget)
														->useDayQuery()
															->filterByDateofDay($fromDate, \DayQuery::GREATER_EQUAL)
															->filterByDateofDay($untilDate, \DayQuery::LESS_EQUAL)
															->useWorkplanQuery()
															->endUse()
														->endUse()
														->useTeamQuery()
														->endUse()
														->useUserQuery()
														->endUse()
														->withColumn('SUM(ProjectEntry.timeinterval)', 'totalProjectTimeInProjectMonthAndYear')
														->withColumn('MONTH(Day.dateofday)', 'month')
														->groupBy('Workplan.id')
														->groupBy('month')
														->groupBy('Team.id')
														->groupBy('User.id')
														->orderBy('Workplan.Year')
														->orderBy('month')
														->orderBy('Team.Name', "desc")
														->orderBy('User.LastName')
														->find();

															
			//
			// if there are results to report, we populate the report data structure
			if ( $projectTimeByProjectQuery->count() != 0 ) {
				
				//
				// initialize report data structure
				// -- for this report there is only one group, containing one subgroup
				//						
				$reportData[0] = array();
				$reportData[0]["group_title"] = "'" . $reportTarget->getName() . "' - Activity by Year, Month and Team / User";
				$reportData[0]["group_meta"] = array();
				
				$reportData[0]["subgroups"] = array();
				$reportData[0]["subgroups"][0] = array();
				$reportData[0]["subgroups"][0]["items"] = array();	
				
				$reportData[0]["subgroups"][0]["meta"] = array();	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_columns"] = array("Year", "Month", "Team / User", "Hours", "", "");	
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_weights"] = array("10", "15", "15", "10", "10", "40");
				$reportData[0]["subgroups"][0]["meta"]["subgroup_column_css_styles"] = array("", "", "", "text-align: right;", "", "text-align: left; font-weight: bold;");	
				
				//
				// compile query result into report data structure
				$reportItemIndex = 0;
				$curProcessedMonth = "";
				$curProcessedYear = "";
				$curYearTimeSum = 0;
				$curMonthTimeSum = 0;
				foreach ( $projectTimeByProjectQuery as $curProjectEntry ) {
					
					//
					// when rendering the data structure, we suppress months and years if they
					// have been issued already
					
					//
					// check whether we need to render the current year
					if ( $curProcessedYear != $curProjectEntry->getDay()->getWorkplan()->getYear() ) {
						
						// if we're not processing the first entry, we issue an empty row
						// when encountering an new year
						if ( $projectTimeByProjectQuery->getPosition() !== 0 ) {
							
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["team_user"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
							$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "";	
							
							$reportItemIndex++;
								
						}
						
						//
						// as the row pertains to a new year, we write it to the current row
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// update the currently processed year 
						$curProcessedYear = $curProjectEntry->getDay()->getWorkplan()->getYear();
						
						// reset processed month, as we want it reissued when the year changes
						$curProcessedMonth = "";
						
						// reset the current year time sum
						$curYearTimeSum = 0;
					}
					else {
						//
						// same year as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
					}
					
					//
					// check whether we need to render the current month
					if ( $curProcessedMonth != $curProjectEntry->getMonth() ) {
						
						//
						// output the month, as it does not match the currently processed one
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = DateTimeHelper::getMonthStringFromNumber($curProjectEntry->getMonth());
						
						// update the currently processed year 
						$curProcessedMonth = $curProjectEntry->getMonth();
						
						// reset the month's time sum
						$curMonthTimeSum = 0;
					}
					else {
						//
						// same year as prior row, supress output
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
					}
					
					// sum the current row's contribution into the current year's time sum 
					$curYearTimeSum += $curProjectEntry->gettotalProjectTimeInProjectMonthAndYear();
					
					// sum the current row's contribution into the current month's time sum 
					$curMonthTimeSum += $curProjectEntry->gettotalProjectTimeInProjectMonthAndYear();
								
					// write current row's team/user and hours elements to report data structure
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["team_user"] = ( $curProjectEntry->getTeam() !== null ? $curProjectEntry->getTeam()->getName() : "(" . $curProjectEntry->getUser()->getFullName() .")" );
					$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectEntry->gettotalProjectTimeInProjectMonthAndYear()) . " h";	
					
					
					
					//
					// a given month's time sum needs to be output whenever the month's entries have been exhausted

					// get next element in iterator and immediately restore iterator position
					$nextProject = $projectTimeByProjectQuery->getNext();
					$projectTimeByProjectQuery->getPrevious();
					
					// we issue the current month's time sum if there is no further row to be processed, or if
					// the next row pertains to a different month or year
					if ( 	($nextProject === null) 
						 ||	($nextProject->getMonth() != $curProcessedMonth) 
						 ||	($nextProject->getDay()->getWorkplan()->getYear() != $curProcessedYear) ) {

						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";		
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "&#931; hours (month) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curMonthTimeSum) . " h";
						
						
					}
					else {
						// otherwise we set the cells to empty
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["monthTimeSum"] = "";	
					}
					
					
					// we issue the current year's time sum if there is no further row to be processed, or if
					// the next row pertains to a different year
					// -- this occurs on a separate row
					if ( 	($nextProject === null) 
						 ||	($nextProject->getDay()->getWorkplan()->getYear() != $curProcessedYear) ) {

						$reportItemIndex++; 	
						
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["year"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["month"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["team_user"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["hours"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["SPACER"] = "";
						$reportData[0]["subgroups"][0]["items"][$reportItemIndex]["yearTimeSum"] = "&#931; hours (year) = " . DateTimeHelper::formatTimeValueInSecondsToHHMM($curYearTimeSum) . " h";

					}
				
					$reportItemIndex++;
				}															
												
			}
																		
		}
				
		return $reportData;
																								
	}
	
	
	
	
	
	/**
	 *  Compiles timetracker report for the indicated parametrization
	 * 
	 *  @param 	User		$user			- the user to generate the report for
	 *  @param	string		$reportItemKey	- the item key to report on, set to null for all items
	 *  @param	string		$groupBy		- E {day, item}, determines whether the report is grouped by day, or by item
	 *  @param 	DateTime	$fromDate		- report start date
	 *  @param 	DateTime	$untilDate		- report end date
	 *  
	 *  @return	array	the compiled report, structured as follows
	 *  
	 *  $reportData = array(
	 *  
	 *  					// the result array is structured into N "group" arrays, each of which represents
	 *  					// the top-level grouping specified via "$groupBy"
	 *   					array(
	 *   
	 *    						"group_title" 	=>  		{GROUP_TITLE},
	 *    
	 *    						// as groups vary in nature, the meta data fields are not predefined.
	 *    						"group_meta" 	=>  		array(
	 *    															{META_KEY_1}	=>	{META_VALUE_1}
	 *    															...
	 *    															{META_KEY_N}	=>	{META_VALUE_N}
	 *    														),
	 *   
	 *   						// each of the top-level groups is subdivided into subgroups, one for each of
	 *   						// to the data subtypes encountered in the result
	 *   						"subgroups"		=> array (
	 *   
	 *   												array (
	 *   
	 *   													// each subgroup contains metadata that allows the report data to be rendered
	 *   													// in a human-readable way
	 *   													"meta"	=>	array (
	 *   
	 *   																		"subgroup_columns"	=>			array (
	 *   																											{COL_1_NAME},
	 *   																											...
	 *   																											{COL_N_NAME}
	 *   																										)
	 *  	 																	"subgroup_column_weights" =>	array (
	 *   																											{COL_1_WEIGHT},
	 *   																											...
	 *   																											{COL_1_WEIGHT}
	 *   																										)
	 *   																		),
	 *   																		"subgroup_column_css_styles" =>	array (
	 *   																											{COL_1_STYLE},
	 *   																											...
	 *   																											{COL_N_STYLE}
	 *   																										)
	 *   																		),  
	 *   
	 *   													//
	 *   													// the metadata block is followed by the subgroup's data items
	 *    													"items"	=>	array (
	 *    
	 *    																	array (
	 *												 								{FIELD_NAME_1}	=> {FIELD_VALUE_1}
	 *												 									...
	 *												 								{FIELD_NAME_N}	=> {FIELD_VALUE_N}
	 *											   								  ),
	 *
	 *																			...
	 *
	 *																		array (
	 *																				...
	 *											   								  )
	 *
	 *    																)
	 *    												),
	 *    
	 *    												...
	 *    
	 *    												array (...)
	 
	 *   											)
	 *   
	 *   					)
	 *   			)
	 */
	public function compileTimetrackerReport($user, $reportItemKey, $groupBy, $fromDate, $untilDate) {
		
		$reportData = array();
		
		//
		// get ref to managers needed
		$entryTypeManager = $this->getCtx()->getEntryTypeManager();
		$bookingTypeManager = $this->getCtx()->getBookingTypeManager();
		$workplanManager = $this->getCtx()->getWorkplanManager();
		$compService = $this->getCtx()->getComputationService();
		
		//
		// process in accordance with grouping
		if ( $groupBy == "day" ) {
			
			//
			// query for the entries to report
			
			// set up base query
			//
			// -- we support all subtypes of "Entry", with the exception of "AdjustmentEntry"
			//    
			//    as a booking consists of OOEntry instances of various types, we restrict to the "MARKER" type, which delineates
			//    the extent of the booking
			//
			$entriesToReportQuery = \EntryQuery::create()
											->filterByUser($user)
											->useOOEntryQuery("ooentryjoin", \EntryQuery::LEFT_JOIN)
												->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
												->_or()
												->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
												->_or()
												->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
												->_or()
												->filterByType(null)
											->endUse()
											->filterByDescendantClass("AdjustmentEntry", \EntryQuery::NOT_EQUAL)
											->useDayQuery()
												->filterByDateOfDay($fromDate, \EntryQuery::GREATER_EQUAL)
												->filterByDateOfDay($untilDate, \EntryQuery::LESS_EQUAL)
											->endUse();
											
			//
			// augment base query if a "reportItemKey" (i.e., a specific subtype of "Entry") was passed
			
			// test for and process specific regular entry subtype							
			if ( 	   ($reportItemKey !== null)
					&& strstr($reportItemKey, \ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY) ) {

				// segment the report item key so as to be able to retrieve entry type id
				$reportItemKeyElems = explode("-", $reportItemKey);
				
				$entriesToReportQuery->useRegularEntryQuery()
										->filterByRegularEntryTypeId($reportItemKeyElems[1])
									  ->endUse();
			}
			// test for and process specific oo entry subtype		
			else if (  	   ($reportItemKey !== null)
						&& strstr($reportItemKey, \ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY) ) {

				// segment the report item key so as to be able to retrieve entry type id
				$reportItemKeyElems = explode("-", $reportItemKey);
						
				// for oo entries, we query for marker entries
				// -- marker entries delineate the full extent of the associated booking
				$entriesToReportQuery->useOOEntryQuery()
										->useOOBookingQuery()
											->filterByOOBookingTypeId($reportItemKeyElems[1])
										->endUse()
									  ->endUse();
			}
			
			//
			// execute query
			$entriesToReport = $entriesToReportQuery->find();		
			
			//
			// compile the report for the indicated date range
			$curReportDayDate = $fromDate;
			$reportGroupIndex = 0;
			
			while ( $curReportDayDate <= $untilDate ) {
	
				// get a reference to Day object for current day processed
				$curDay = $workplanManager->getDayForDate($curReportDayDate);
				
				// first off, we query for the subset of the entries to report that
				// belong to the current day
				$curDayEntries = \EntryQuery::create()
									->filterByDay($curDay)
									->filterById($entriesToReport->toKeyValue("id", "id"))
									->find();
									
		
				// if the current day has entries, we proceed to compile them
				// into the result data structure					
				if ( $curDayEntries->count() > 0 ) {
					
					// set the title for the report group (day)
					$reportData[$reportGroupIndex]["group_title"] = DateTimeHelper::formatDateTimeToLongformPrettyDateFormat($curDay->getDateOfDay());
					$reportData[$reportGroupIndex]["group_meta"]["group_datetime"] = $curDay->getDateOfDay(); 
					
					$processedTypes = array();
					$subReportGroupIndex = 0;
					
					foreach ( $curDayEntries as $curDayEntry ) {
						
						// set array of subgroup items to empty
						$subReportGroupItems = array();
						
						switch ( $curDayEntry->getDescendantClass() ) {
							
							case "RegularEntry":
									
								if ( ! array_key_exists("RegularEntry", $processedTypes) ) {
									
									// add current subgroup type to digest of processed types
									$processedTypes["RegularEntry"] = "RegularEntry";
									
									// initialize meta data for the current report subgroup
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																										"subgroup_columns" 		=> array("Time Entry", "From", "Until", "Hours", "", "Comment"),
																										"subgroup_column_weights" 	=> array("15", "10", "10", "10", "10", "35"),
																										"subgroup_column_css_styles" => array("", "", "", "text-align: right;", "", "")
																									);
							
									// query for the items belonging to the subreport group
									$subreportGroupItems = \EntryQuery::create()
																->filterByDescendantClass("RegularEntry")
																->filterById($curDayEntries->toKeyValue("id", "id"))
																->find();				
																
									//
									// compile the subgroup items into report data digest
									$reportItemIndex = 1;
									foreach ( $subreportGroupItems as $curSubGroupItem ) {
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["entry_type"] 		= $curSubGroupItem->getChildObject()->getRegularEntryType()->getType();
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["from"] 			= $curSubGroupItem->getChildObject()->getFrom()->format("Hi");
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["until"] 			= $curSubGroupItem->getChildObject()->getUntil()->format("Hi");
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["hours"] 			= DateTimeHelper::formatTimeValueInSecondsToHHMM($curSubGroupItem->getChildObject()->getTimeInterval()) . " h";
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["SPACER"] 			= "";
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["comment"] 		= $curSubGroupItem->getChildObject()->getComment();
										
										$reportItemIndex++;														
									}

									$subReportGroupIndex++;			

								}
							
								break;
								
								
							case "OOEntry":
										
								if ( ! array_key_exists("OOEntry", $processedTypes) ) {
									
									$processedTypes["OOEntry"] = "OOEntry";
									
									
									// initialize meta data for the current report subgroup
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																										"subgroup_columns" 		=> array("Absence Type", "Request Status", ""),
																										"subgroup_column_weights" 	=> array("15", "20", "65"),
																										"subgroup_column_css_styles" => array("", "", "")
																									);
									
									// query for the items belonging to the subreport group
									$subreportGroupItems = \EntryQuery::create()
																->filterByDescendantClass("OOEntry")
																->filterById($curDayEntries->toKeyValue("id", "id"))
																->find();
																
									//
									// compile the subgroup items into report data digest
									$reportItemIndex = 1;
									foreach ( $subreportGroupItems as $curSubGroupItem ) {
										
										// retrieve OOEntry that makes up this report item
										$reportedOOEntry = $curSubGroupItem->getChildObject();
										
										//
										// map system oo booking types to pretty name
										$absenceType = $reportedOOEntry->getOOBooking()->getOOBookingType()->getType();
										if ( $reportedOOEntry->getOOBooking()->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
											$absenceType = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$absenceType];
										}
										
										//
										// issue status information
										// -- default request status text to "(not applicable)" as not all bookings are request originated
										$statusInfo = "(not applicable)";
										if ( $reportedOOEntry->getOOBooking()->getOORequest() !== null ) {
											$statusInfo = \OORequest::$STATUS_MAP[$reportedOOEntry->getOOBooking()->getOORequest()->getStatus()];
										}
									
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["absence_type"] 	= $absenceType;
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["status_info"] 	= $statusInfo;
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["(empty)"] 		= "";
										
										$reportItemIndex++;														
									}

									$subReportGroupIndex++;		
								
								}
		
								break;
								
								
							case "ProjectEntry":
								
								if ( ! array_key_exists("ProjectEntry", $processedTypes) ) {
									
									$processedTypes["ProjectEntry"] = "ProjectEntry";
									
									// initialize meta data for the current report subgroup
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																										"subgroup_columns" 		=> array("Project Entry", "Hours", ""),
																										"subgroup_column_weights" 	=> array("25", "15", "60"),
																										"subgroup_column_css_styles" => array("", "", "")
																									);
									
									// query for the items belonging to the subreport group
									$subreportGroupItems = \EntryQuery::create()
																->filterByDescendantClass("ProjectEntry")
																->filterById($curDayEntries->toKeyValue("id", "id"))
																->find();
															
									//
									// compile the subgroup items into report data digest
									$reportItemIndex = 1;
									foreach ( $subreportGroupItems as $curSubGroupItem ) {
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["project_name"] 	= $curSubGroupItem->getChildObject()->getProject()->getName();
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["hours"] 			= DateTimeHelper::formatTimeValueInSecondsToHHMM($curSubGroupItem->getChildObject()->getTimeInterval()) . " h";
										$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["(empty)"] 		= "";
										
										$reportItemIndex++;														
									}

									$subReportGroupIndex++;		
																
								}
								
								break;
											
						}
						
					}
					
				}					
									
				// increment the currently processed date by one day
				$curReportDayDate = DateTimeHelper::addDaysToDateTime($curReportDayDate, 1);
				
				$reportGroupIndex++;		
			}
			
		}
		else if ( $groupBy == "item" ) {
			
			// prepare superstructure and metadata for day grouped report
			$reportData = array();
			
			// as all query items presently are instances of "Entry" we can
			// base the entire data retrieval off an EntryQuery

			// set up basic Entry query that determines what entry types are to be reported, excluding AdjustmentEntry instances
			// -- we'll receive the various types N-fold with this query, this will handle when we actually query for the items (i.e., days) related to the type
			$entryTypesToReportQuery = \EntryQuery::create()
											->filterByUser($user)
											->filterByDescendantClass("AdjustmentEntry", \EntryQuery::NOT_EQUAL)
											->useDayQuery()
												->filterByDateOfDay($fromDate, \EntryQuery::GREATER_EQUAL)
												->filterByDateOfDay($untilDate, \EntryQuery::LESS_EQUAL)
											->endUse();

			//
			// if a specific report item is indicated, we further restrict the query
			if ( $reportItemKey !== null ) {
				//
				// segment the report item key so as to be able to retrieve entry type id
				$reportItemKeyElems = explode("-", $reportItemKey);
				
				// augment the query as indicated
				switch ( $reportItemKeyElems[0] ) {
					
					case \ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_OO_ENTRY:
						
						$entryTypesToReportQuery->useOOEntryQuery()
													  ->useOOBookingQuery()
															->useOOBookingTypeQuery()
																->filterById($reportItemKeyElems[1])
															->endUse()
													  ->endUse()
												 ->endUse();
							  
						break;
						
					case \ReportsController::$TIMETRACKER_REPORT_ITEM_PREFIX_REG_ENTRY:
						
						$entryTypesToReportQuery->useRegularEntryQuery()
													->useRegularEntryTypeQuery()
														->filterById($reportItemKeyElems[1])
												  	->endUse()
												->endUse();
							  
						break;
									
				}
				
			}

			// retrieve all reportable entry type
			$entryTypesToReport = $entryTypesToReportQuery->find();
								
			//
			// compile the reportable entries into report
			//
			// to do this, we process the Entry subtypes determined and query for the Day instances
			// that carry them. Each Entry subtype defines a report group, the Day instances for the Entry subtype make up the
			// report items of that group
			//
			// as "entryTypesToReport" will carry multiples of the various types, a log of already processed types
			// needs to be kept
			//

			$processedTypes = array();
			$reportGroupIndex = 0;
			
			foreach ( $entryTypesToReport as $curEntryType ) {
				
				// set array of subgroup items to empty
				// -- note: as we're grouping by item, there will only be one subgroup in the result of the current group
				$subGroupItems = array();
				$subReportGroupIndex = 0;

				switch ( $curEntryType->getDescendantClass() ) {
					
					case "RegularEntry":
						
						// process type, if not already on list of processed types
						$curTypeIdentifier = "RegularEntryType-" . $curEntryType->getChildObject()->getRegularEntryType()->getId();
						
						if ( ! array_key_exists($curTypeIdentifier, $processedTypes) ) {
							
							$processedTypes[$curTypeIdentifier] = $curTypeIdentifier;
							
							// initialize group title
							$reportData[$reportGroupIndex]["group_title"] = "'" . $curEntryType->getChildObject()->getRegularEntryType()->getType() . "' - Time Entry";
							$reportData[$reportGroupIndex]["group_meta"] = array(); 
							
							// initialize meta data for the current report subgroup
							$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																									"subgroup_columns" 				=> array("Date", "Time Entry", "From", "Until", "Hours", "", "Comment"),
																									"subgroup_column_weights" 		=> array("10", "15", "10", "10", "5", "10", "30"),
																									"subgroup_column_css_styles" 	=> array("", "", "", "", "text-align: right;", "", "")
																								  );
							$subreportGroupItems = \DayQuery::create()
														->useEntryQuery()
															->filterByUser($user)
															->filterByDescendantClass($curEntryType->getDescendantClass())
														->endUse()	
														->filterByDateOfDay($fromDate, \EntryQuery::GREATER_EQUAL)
														->filterByDateOfDay($untilDate, \EntryQuery::LESS_EQUAL)
														->distinct()
														->find();
											
							//
							// compile the subgroup items into report data digest
							$reportItemIndex = 1;
							foreach ( $subreportGroupItems as $curSubGroupItem ) {
								
								// query for the subgroups regular entries
								$regEntriesToReportForSubGroup = \RegularEntryQuery::create()
																		->filterByRegularEntryType($curEntryType->getChildObject()->getRegularEntryType())
																		->filterByUser($user)
																		->useDayQuery()
																			->filterByDateOfDay($curSubGroupItem->getDateOfDay())
																		->endUse()
																		->find();
								

								// add regular entries in current group as report items
								foreach ( $regEntriesToReportForSubGroup as $curRegEntryToReport ) {
									
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["date"]			= DateTimeHelper::formatDateTimeToPrettyDateFormat($curSubGroupItem->getDateOfDay());
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["entry_type"] 		= $curRegEntryToReport->getRegularEntryType()->getType();
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["from"] 			= $curRegEntryToReport->getFrom()->format("Hi");
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["until"] 			= $curRegEntryToReport->getUntil()->format("Hi");
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["hours"] 			= DateTimeHelper::formatTimeValueInSecondsToHHMM($curRegEntryToReport->getTimeInterval()) . " h";
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["SPACER"] 			= "";
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["comment"] 		= $curRegEntryToReport->getComment();
								
									$reportItemIndex++;														
								
								}
							
							}								
														
						}
						
						break;
						
						
					case "OOEntry":
						
						// process type, if not already on list of processed types
						$curTypeIdentifier = "OOBookingType-" . $curEntryType->getChildObject()->getOOBooking()->getOOBookingType()->getId();
						
						if ( ! array_key_exists($curTypeIdentifier , $processedTypes) ) {
							
							$processedTypes[$curTypeIdentifier] = $curTypeIdentifier;
							
							//
							// map system oo booking types to pretty name
							$entryTitle = $curEntryType->getChildObject()->getOOBooking()->getOOBookingType()->getType();
							if ( $curEntryType->getChildObject()->getOOBooking()->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
								$entryTitle = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$entryTitle];
							}
							
							$reportData[$reportGroupIndex]["group_title"] = "'" . $entryTitle . "' - Out-of-Office Entry";
							$reportData[$reportGroupIndex]["group_meta"] = array();
							
							// initialize meta data for the current report subgroup
							// -- when grouping by item, 
							$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																									"subgroup_columns" 				=> array("Date", "Absence Type", "Request Status"),
																									"subgroup_column_weights" 		=> array("15", "20", "65"),
																									"subgroup_column_css_styles" 	=> array("", "", "")
																								  );
							
							$subreportGroupItems = \DayQuery::create()
														->useEntryQuery()
															->filterByUser($user)
															->filterByDescendantClass($curEntryType->getDescendantClass())
														->endUse()	
														->filterByDateOfDay($fromDate, \EntryQuery::GREATER_EQUAL)
														->filterByDateOfDay($untilDate, \EntryQuery::LESS_EQUAL)
														->distinct()
														->find();
														
							//
							// compile the subgroup items into report data digest
							$reportItemIndex = 1;
							foreach ( $subreportGroupItems as $curSubGroupItem ) {
								
								// query for the subgroups oo entries
								$ooEntriesToReportForSubGroup = \OOEntryQuery::create()
																		->filterByUser($user)
																		->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
																		->_or()
																		->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
																		->_or()
																		->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
																		->useOOBookingQuery()
																			->filterByOOBookingType($curEntryType->getChildObject()->getOOBooking()->getOOBookingType())
																		->endUse()
																		->useDayQuery()
																			->filterByDateOfDay($curSubGroupItem->getDateOfDay())
																		->endUse()
																		->find();
								

								// add regular entries in current group as report items
								foreach ( $ooEntriesToReportForSubGroup as $curOOEntryToReport ) {
									
									//
									// map system oo booking types to pretty name
									$absenceType = $curOOEntryToReport->getOOBooking()->getOOBookingType()->getType();
									if ( $curOOEntryToReport->getOOBooking()->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
										$absenceType = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$absenceType];
									}
									
									//
									// if the oo entry is request based, we issue state information
									$statusInfo = "(not applicable)";
									if ( $curOOEntryToReport->getOOBooking()->getOORequest() !== null ) {
										$statusInfo = \OORequest::$STATUS_MAP[$curOOEntryToReport->getOOBooking()->getOORequest()->getStatus()];
									}
								
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["date"]			= DateTimeHelper::formatDateTimeToPrettyDateFormat($curSubGroupItem->getDateOfDay());
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["absence_type"] 	= $absenceType;
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["status_info"] 	= $statusInfo;
								
									$reportItemIndex++;														
								
								}
											
							}									
			
						}

						break;
						
						
					case "ProjectEntry":
						
						// process type, if not already on list of processed types
						$curTypeIdentifier = "ProjectEntry-" . $curEntryType->getChildObject()->getProject()->getId();
						
						if ( ! array_key_exists($curTypeIdentifier , $processedTypes) ) {
							
							$processedTypes[$curTypeIdentifier] = $curTypeIdentifier;
							
							$reportData[$reportGroupIndex]["group_title"] = "'" . $curEntryType->getChildObject()->getProject()->getName()  . "' - Project Entry";
							$reportData[$reportGroupIndex]["group_meta"] = array();
							
							// initialize meta data for the current report subgroup
							// -- when grouping by item, 
							$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["meta"] = array (
																									"subgroup_columns" 		=> array("Date", "Project Entry", "Hours", ""),
																									"subgroup_column_weights" 	=> array("15", "30", "10", "45"),
																									"subgroup_column_css_styles" 	=> array("", "", "text-align: right;", "")
																								  );
							
							$subreportGroupItems = \DayQuery::create()
														->useEntryQuery()
															->filterByUser($user)
															->filterByDescendantClass($curEntryType->getDescendantClass())
														->endUse()	
														->filterByDateOfDay($fromDate, \EntryQuery::GREATER_EQUAL)
														->filterByDateOfDay($untilDate, \EntryQuery::LESS_EQUAL)
														->distinct()
														->find();

							//
							// compile the subgroup items into report data digest
							$reportItemIndex = 1;
							foreach ( $subreportGroupItems as $curSubGroupItem ) {
								
								// query for the subgroups project entries
								$projectEntriesToReportForSubGroup = \ProjectEntryQuery::create()
																			->filterByProject($curEntryType->getChildObject()->getProject())
																			->filterByUser($user)
																			->useDayQuery()
																				->filterByDateOfDay($curSubGroupItem->getDateOfDay())
																			->endUse()
																			->find();
								
								// add regular entries in current group as report items
								foreach ( $projectEntriesToReportForSubGroup as $curProjectEntryToReport ) {
									
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["date"]			= DateTimeHelper::formatDateTimeToPrettyDateFormat($curSubGroupItem->getDateOfDay());
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["project_name"] 	= $curProjectEntryToReport->getProject()->getName();
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["hours"]			= DateTimeHelper::formatTimeValueInSecondsToHHMM($curProjectEntryToReport->getTimeInterval()) . " h";
									$reportData[$reportGroupIndex]["subgroups"][$subReportGroupIndex]["items"][$reportItemIndex]["(empty)"] 		= "";
									
									$reportItemIndex++;														
								
								}
							
							}			
							
						}
						
						break;
									
				}

				$reportGroupIndex++;														
			}
			
		}
		
		return $reportData;
		
	}
	
	
	/**
	 *  Compiles the out-of-office summary for a given user and workplan
	 * 
	 *  @param 	User		$user		
	 *  @param 	Workplan	$workplan
	 *  
	 *  @return	array	the computation result, structured as follows
	 *  
	 *  result = array (	
	 *
	 *		"month_1"			=> array (
	 *									day_1	=> array(
	 *													"weekday_number" = {weekdaynumber},
	 *													"oobooking" = {OOBooking}
	 *													"oobooking_color" = {color associated with oo booking type}
	 *													"ooentry" = {OOEntry}
	 *											   ),
	 *									...
	 *									day_n	=> array (...)
	 *								), 
	 *		...
	 *
	 *		"month_12"			=> array (
	 *									day_1	=> array(
	 *													[booking type]
	 *											   ),
	 *									...
	 *									day_n	=> array (...)
	 *								), 
	 *  	)
	 *
	 */
	public function compileOOSummary($user, $workplan) {
		
		$result = array();
		
		// get ref to managers needed
		$entryManager = $this->getCtx()->getEntryManager();
		$settingsManager = $this->getCtx()->getSettingsManager();
		
		// get default color for oo bookings
		$defaultOOBookingsColor = $settingsManager->getSettingValue(\Setting::KEY_OO_BOOKING_DEFAULT_COLOR);
		
		// process each calendar month
		for ( $curMonth = 1; $curMonth <= 12; $curMonth++ ) {
			
			$result["month_" . $curMonth] = array();
			
			// construct a DateTime representation of first day in current month
			$firstDayOfMonthDate = DateTimeHelper::getDateTimeFromStandardDateFormat("1-" . $curMonth . "-" . $workplan->getYear());
			
			// process each day in current month
			for ( $curMonthDay = 1; $curMonthDay <= DateTimeHelper::getMonthDaysFromDateTime($firstDayOfMonthDate); $curMonthDay++ ) {
				
				// get DateTime representation for current day
				$curDayDate = DateTimeHelper::getDateTimeFromStandardDateFormat($curMonthDay . "-" . $curMonth . "-" . $workplan->getYear());

				//
				// query for ooboking that applies to current day
				$curDayOOBooking = null;
				$curDayAllotmentOOEntry = null;
				
				// first off, query for a possible marker oo entry on the current day
				// -- as each day in a oobooking carries one of these, if we find such an entry on the current day,
				//    it indicates that it is part of an oobooking
				$curDayMarkerOOEntry = \OOEntryQuery::create()
											->filterByUser($user)
											->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_AM)
											->_or()
											->filterByType(\OOEntry::TYPE_MARKER_HALF_DAY_PM)
											->_or()
											->filterByType(\OOEntry::TYPE_MARKER_FULL_DAY)
											->useDayQuery()
												->filterByDateofday($curDayDate)
											->endUse()
											->findOne();

				// if we have a marker ooentry, set the booking reference from it
				// and query for applicable allotment entry
				if ( $curDayMarkerOOEntry !== null ) {
					
					// set booking reference from marker
					$curDayOOBooking = $curDayMarkerOOEntry->getOOBooking();
					
					// query for a possible allotment oo entry
					$curDayAllotmentOOEntry = \OOEntryQuery::create()
													->filterByUser($user)
													->filterByType(\OOEntry::TYPE_ALLOTMENT_FULL_DAY)
													->_or()
													->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_AM)
													->_or()
													->filterByType(\OOEntry::TYPE_ALLOTMENT_HALF_DAY_PM)
													->useDayQuery()
														->filterByDateofday($curDayDate)
													->endUse()
													->findOne();
				}
				
				//
				// set the color value and base descriptive text for the booking
				//
				$ooBookingColor = null;
				$ooBookingDescriptiveText = null;
				
				if ( $curDayOOBooking !== null ) {
					
					// set color value for the booking to oo types color, 
					// if none is defined, use default color	
					$ooBookingColor = $defaultOOBookingsColor;	
					
					if ( $curDayOOBooking->getOOBookingType()->getRgbColorValue() !== null ) {
						$ooBookingColor = $curDayOOBooking->getOOBookingType()->getRgbColorValue();
					}
					
					// for system types set descriptive text to MAP value defined in OOBookingType
					// for user type set to value entered as "type"	
					if ( $curDayOOBooking->getOOBookingType()->getCreator() == \OOBookingType::CREATOR_SYSTEM ) {
						$ooBookingDescriptiveText = \OOBookingType::$BOOKABLE_SYSTEM_TYPE_MAP[$curDayOOBooking->getOOBookingType()->getType()];
					}
					else {
						$ooBookingDescriptiveText = $curDayOOBooking->getOOBookingType()->getType();
					}
				}	
				
				//
				// feed all data into summary strucuture
				$result["month_" . $curMonth]["day_" . $curMonthDay] = array();
				$result["month_" . $curMonth]["day_" . $curMonthDay]["weekday_number"] = DateTimeHelper::getWeekDayNumberFromDateTime($curDayDate);
				$result["month_" . $curMonth]["day_" . $curMonthDay]["oobooking"] = $curDayOOBooking;
				$result["month_" . $curMonth]["day_" . $curMonthDay]["oobooking_color"] = $ooBookingColor;
				$result["month_" . $curMonth]["day_" . $curMonthDay]["allotment_ooentry"] = $curDayAllotmentOOEntry;
			}
			
		}
		
		return $result;
		
	}
	
	
}								  
									  