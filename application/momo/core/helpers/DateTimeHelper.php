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

namespace momo\core\helpers;

use momo\core\managers\BaseManager;
use momo\core\exceptions\MomoException;

/**
 * DateTimeHelper
 *
 * Various date and time related helper functions
 *
 * @author  Francesco Krattiger
 * @package momo.application.core.helpers
 */
class DateTimeHelper {

	const MOMO_STANDARD_DATETIMEFORMAT = "j-n-Y Hi";
	const MOMO_STANDARD_DATEFORMAT = "j-n-Y";
	const MOMO_PRETTY_DATETIMEFORMAT = "j M Y - Hi";
	const MOMO_PRETTY_DATEFORMAT = "j M Y";
	const MOMO_PRETTY_LONGFORM_DATEFORMAT = "j. F Y - l";
	const MYSQL_DATETIMEFORMAT = "Y-m-d Hi";


	/**
	 * Tests a given date for validity
	 *
	 * @param 	string		$day
	 * @param 	string		$month
	 * @param 	string		$year
	 *
	 * @return	boolean		true, if the date validates, false otherwise.
	 */
	public static function isValidDate($day = null, $month = null, $year = null) {
		return checkdate($month, $day, $year);
	}


	/**
	 * Returns the first day of the week (Monday) for any given date.
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	DateTime	adjusted to the start of the week for the indicated date
	 */
	public static function getStartOfWeek($date) {
		//
		// get numeric representation of weekday (1=Mon, 7=Sun)
		$dayNum = date("N", $date->getTimestamp());

		// figure out offset from start of week
		$startOfWeekDayDiff = $dayNum - 1;

		return DateTimeHelper::addDaysToDateTime(clone $date, (-1 * $startOfWeekDayDiff));
	}


	/**
	 * Returns the last day of the week (Sunday) for any given date.
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	DateTime	adjusted to the end of the week for the indicated date
	 */
	public static function getEndOfWeek($date) {
		//
		// get numeric representation of weekday (1=Mon, 7=Sun)
		$dayNum = date("N", $date->getTimestamp());

		// figure out offset from end of week
		$endOfWeekDayDiff = 7 - $dayNum;

		return DateTimeHelper::addDaysToDateTime(clone $date, $endOfWeekDayDiff);
	}


	/**
	 * Returns a textual representation for a given month's ordinal number
	 *
	 * @return	string
	 */
	public static function getMonthStringFromNumber($monthNumber) {
		$date = \DateTime::createFromFormat(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT, "1-" . $monthNumber . "-1980"  . " 0000");
		return $date->format("F");
	}


	/**
	 * Returns Momo standard formatted "date-time string" from a DateTime object
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	string
	 */
	public static function formatDateTimeToStandardDateTimeFormat($date) {
		return $date->format(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT);
	}


	/**
	 * Returns Momo standard formatted "date string" from a DateTime object
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	string
	 */
	public static function formatDateTimeToStandardDateFormat($date) {
		return $date->format(DateTimeHelper::MOMO_STANDARD_DATEFORMAT);
	}


	/**
	 * Returns Momo pretty formatted "date-time string" from a DateTime object
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	string
	 */
	public static function formatDateTimeToPrettyDateTimeFormat($date) {
		return $date->format(DateTimeHelper::MOMO_PRETTY_DATETIMEFORMAT);
	}


	/**
	 * Returns Momo pretty formatted "date string" from a DateTime object
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	string
	 */
	public static function formatDateTimeToPrettyDateFormat($date) {
		return $date->format(DateTimeHelper::MOMO_PRETTY_DATEFORMAT);
	}


	/**
	 * Returns Momo pretty formatted "date string" (longform) from a DateTime object
	 *
	 * @param 	DateTime	$date
	 *
	 * @return	string
	 */
	public static function formatDateTimeToLongformPrettyDateFormat($date) {
		return $date->format(DateTimeHelper::MOMO_PRETTY_LONGFORM_DATEFORMAT);
	}



	/**
	 * Given a time value in seconds, the method returns the "hh:mm" equivalent.
	 *
	 * @param 	integer		$timeValueInSec		- the time value to convert
	 * @param	boolean		$prefixPositives	- true prefixes positive values with sign, false doesn't
	 * 											  (defaults to "false")
	 *
	 * @return	string
	 */
	public static function formatTimeValueInSecondsToHHMM($timeValueInSec, $prefixPositives=false) {

		$inputIsNegative = false;

		if ( $timeValueInSec < 0 ) {
			// we're dealing with a negative quantity, convert and flag
			$inputIsNegative = true;
			$timeValueInSec *= -1;
		}

		$hours = floor($timeValueInSec / 3600);
		$minutes = floor( ($timeValueInSec - (3600 * $hours)) / 60);
		$seconds = $timeValueInSec - (60 * $minutes + 3600 * $hours);

		// round time values, if seconds >= 30
		if ( $seconds >= 30 ) {
			$seconds = 0;
			$minutes++;
		}

		// roll over minutes if needed
		if ( $minutes == 60 ) {
			$minutes = 0;
			$hours++;
		}

    	$returnValue = $hours . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT);

    	//
    	// if indicated, attach sign to return value

		if ( $inputIsNegative ) {
			$returnValue = "-" . $returnValue;
		}
		elseif ( $prefixPositives ) {
			$returnValue = "+" . $returnValue;
		}

    	return $returnValue;
	}


	/**
	 * Converts a DateTime object to the indicated timezone
	 *
	 * @param 	DateTime	$date					- the date to convert
	 * @param	string		$timeZoneIdentifier		- a php time zone identifier
	 *
	 * @return	integer
	 */
	public static function convertDateTimeToTimeZone($date, $timeZoneIdentifier) {
		return $date->setTimezone(new \DateTimeZone($timeZoneIdentifier));
	}


	/**
	 * Returns a DateTime object from a Momo standard formatted "date-time string".
	 *
	 * Optionally, a time zone identifier may be passed, in which case the
	 * returned DateTime will be relative to that zone. If no timezone is
	 * passed the DateTime will be relative to the server default time zone.
	 *
	 * @param 	string	$dateString
	 * @param 	string	$timeZoneIdentifier
	 *
	 * @return	DateTime
	 *
	 */
	public static function getDateTimeFromStandardDateTimeFormat($dateString, $timeZoneIdentifier = null) {

		if ( $timeZoneIdentifier != null ) {
			$date = \DateTime::createFromFormat(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT, $dateString, new \DateTimeZone($timeZoneIdentifier));
		}
		else {
			$date = \DateTime::createFromFormat(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT, $dateString);
		}

		return $date;
	}


	/**
	 * Returns a DateTime object from a Momo standard formatted "date string".
	 *
	 * Optionally, a time zone identifier may be passed, in which case the
	 * returned DateTime will be relative to that zone. If no timezone is
	 * passed the DateTime will be relative to the server default time zone.
	 *
	 * @param 	string	$dateString
	 * @param 	string	$timeZoneIdentifier
	 *
	 * @return	DateTime
	 *
	 */
	public static function getDateTimeFromStandardDateFormat($dateString, $timeZoneIdentifier = null) {

		if ( $timeZoneIdentifier != null ) {
			$date = \DateTime::createFromFormat(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT, $dateString . " 0000", new \DateTimeZone($timeZoneIdentifier));
		}
		else {
			$date = \DateTime::createFromFormat(DateTimeHelper::MOMO_STANDARD_DATETIMEFORMAT, $dateString . " 0000");
		}

		return $date;
	}


	/**
	 * Returns a DateTime object from a MYSQL formatted date string
	 *
	 * Optionally, a time zone identifier may be passed, in which case the
	 * returned DateTime will be relative to that zone. If no timezone is
	 * passed the DateTime will be relative to the server default time zone.
	 *
	 * @param 	string	$dateString
	 * @param 	string	$timeZoneIdentifier
	 *
	 * @return	DateTime
	 *
	 */
	public static function getDateTimeFromMySQLDateFormat($dateString, $timeZoneIdentifier = null) {

		if ( $timeZoneIdentifier != null ) {
			$date = \DateTime::createFromFormat(DateTimeHelper::MYSQL_DATETIMEFORMAT, $dateString . " 0000", new \DateTimeZone($timeZoneIdentifier));
		}
		else {
			$date = \DateTime::createFromFormat(DateTimeHelper::MYSQL_DATETIMEFORMAT, $dateString . " 0000");
		}

		return $date;
	}


	/**
	 * Returns the current (pure) date as a DateTime object
	 * Note: "pure" implies that the time value is normalized to "0000"
	 *
	 * @return	DateTime
	 */
	public static function getDateTimeForCurrentDate() {
		// compile (pure) date string in standard format
		$dateString = DateTimeHelper::getCurrentDay() . "-" . DateTimeHelper::getCurrentMonth() . "-" . DateTimeHelper::getCurrentYear() . " 0000";

		return DateTimeHelper::getDateTimeFromStandardDateTimeFormat($dateString);
	}


	/**
	 * Returns the current year (four digit)
	 *
	 * @return	integer
	 */
	public static function getCurrentYear() {
		return DateTimeHelper::getYearFromDateTime(new \DateTime());
	}


	/**
	 * Returns the current month (no leading zeros)
	 *
	 * @return	integer
	 */
	public static function getCurrentMonth() {
		return DateTimeHelper::getMonthFromDateTime(new \DateTime());
	}


	/**
	 * Returns the current day (no leading zeros)
	 *
	 * @return	integer
	 */
	public static function getCurrentDay() {
		return DateTimeHelper::getDayFromDateTime(new \DateTime());
	}


	/**
	 * Returns the current year (no leading zeros)
	 *
	 * @return	integer
	 */
	public static function getYearFromDateTime($dateTime) {
		return $dateTime->format("Y");
	}


	/**
	 * Returns the month (no leading zeros) for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getMonthFromDateTime($dateTime) {
		return $dateTime->format("n");
	}


	/**
	 * Returns the day (no leading zeros) for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getDayFromDateTime($dateTime) {
		return $dateTime->format("j");
	}


	/**
	 * Returns the weekday number (mon=1, ..., sun=7) for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getWeekDayNumberFromDateTime($dateTime) {
		return $dateTime->format("N");
	}


	/**
	 * Returns the day of the year (beginning at zero) for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getDayOfYearFromDateTime($dateTime) {
		return $dateTime->format("z");
	}


	/**
	 * Returns the ISO week number for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getISOWeekNumberFromDateTime($dateTime) {
		return $dateTime->format("W");
	}


	/**
	 * Returns the number of days in the month for a given DateTime
	 *
	 * @return	integer
	 */
	public static function getMonthDaysFromDateTime($dateTime) {
		return $dateTime->format("t");
	}


	/**
	 * Returns a date with the indicated number of days added to the passed DateTime value
	 *
	 * Note: $numDays is an integer value - i.e., it may be negative as well.
	 *
	 * @param 	DateTime 	$dateTime
	 * @param 	integer		$numDays			- the number of days to add
	 *
	 * @return	DateTime
	 */
	public static function addDaysToDateTime($dateTime, $numDays) {
		$returnDateTime = clone $dateTime;
		return $returnDateTime->modify("+" . $numDays . " day");
	}


	/**
	 * Returns a date with the indicated number of months added to the passed DateTime value
	 *
	 * TODO: deprecated, replace calls to this method with "addMonthsToDateSpecialSemantics"
	 *
	 * Note: $numMonths is an integer value - i.e., it may be negative as well.
	 *
	 * @param 	DateTime 	$dateTime
	 * @param 	integer		$numMonths			- the number of months to add
	 *
	 * @return	DateTime
	 */
	public static function addMonthsToDateTime($dateTime, $numMonths) {
		$returnDateTime = clone $dateTime;
		return $returnDateTime->modify("+" . $numMonths . " month");
	}


	/**
	 * Returns a date with the indicated number of months added to the passed DateTime value.
	 *
	 * Unlike the simplistic nature of addMonthsToDateTime(), this method will increment months
	 * in accordance to commonly employed semantics. More specifically, adding the indicated number
	 * of months to the passed date is guaranteed to yield a result whose distance corresponds to the
	 * indicated number of months.
	 *
	 * To illustrate: If we were to add 1 month to "31-Jan-2012" (leap year), the method would return
	 * "29-Feb-2012", in accordance to what is commonly meant when we speak of "adding a month" to a date.
	 * "addMonthsToDateTime()" would yield "2-Mar-2012" - a result perhaps useful in some alternate universe.
	 *
	 * @param 	DateTime 	$dateTime
	 * @param 	integer		$numMonths			- the number of months to add
	 *
	 * @return	DateTime
	 */
	function addMonthsToDateNormalSemantics($dateTime, $numMonths) {

		// get target date components
		$day = DateTimeHelper::getDayFromDateTime($dateTime);
		$month = DateTimeHelper::getMonthFromDateTime($dateTime);
		$year = DateTimeHelper::getYearFromDateTime($dateTime);

		//
		// figure out resulting month and year
		//
		// in order to perform both positive and negative month offsets
		// it is necessary to convert dates to months and back.
		//
		// in particular, given a date of the form month-year,
		// the month equivalent is given by:
		//
		//		12 * (year - 1) + month
		//
		// the conversion back to month-year form is somewhat more complicated
		// as boundary cases need to be checked for:
		//
		//

		// first off, we determine the raw resulting month that adding the
		// indicated number of months will yield.
		$rawResultMonth = ($month + $numMonths) % 12;

		//
		// this raw resulting month needs to be interpreted relative the context
		// of the operation for it to yield a valid month

		// if the raw result is negative, we need to offset it by 12 months
		// as we're stepping backwards into the resulting year
		if ( $rawResultMonth < 0 ) {
			$resultMonth = 12 + $rawResultMonth;
		}
		// if the raw resulting month results in zero that indicates a full cycle
		// of months was encountered (mod 12). in the context of date representation
		// a full cycle of months is simply given by "12"
		else if ( $rawResultMonth == 0 ) {
			$resultMonth = 12;
		}
		// if neither case applies, the resulting month is given by the raw value
		else {
			$resultMonth = $rawResultMonth;
		}

		// compute the raw resulting year value by converting to months and then
		// extracting the resulting number of full years that result from that (int cast)
		$resultYearRaw = (int) (( 12 * ($year - 1) + $month + $numMonths ) / 12);

		// to obtain the year that we return as a result from all this
		// we need to increment the raw year value by one, except in the
		// case where the resulting month indicates a full cycle, in which case the
		// raw year value already accounts for the result year
		if ( $resultMonth != 12 ) {
			$resultYear = $resultYearRaw + 1;
		}
		else {
			$resultYear = $resultYearRaw;
		}

		// we'll need to test the number of days in the result month
		// accordingly, we create a DateTime representation for the 1st day of the
		// result month
		$resultDateTime = DateTimeHelper::getDateTimeFromStandardDateFormat("1-" . $resultMonth . "-" . $resultYear);

		// if needed, we limit the resultDay to the number of days in the month of the result date
		$resultDay = $day;
		if ( $day > DateTimeHelper::getMonthDaysFromDateTime($resultDateTime) ) {
			$resultDay = DateTimeHelper::getMonthDaysFromDateTime($resultDateTime);
		}

		return DateTimeHelper::getDateTimeFromStandardDateFormat($resultDay . "-" . $resultMonth . "-" . $resultYear);
	}


	/**
	 * Computes the number of of integral months and days that lie between the indicated days.
	 *
	 * @param 	DateTime 	$fromDate		- the start date of the range to consider (inclusive)
	 * @param 	DateTime 	$untilDate		- the end date of the range to consider (inclusive)
	 *
	 * @return	array(
	 * 					"months" => [INTEGER]
	 * 					"days" 	 => [INTEGER]
	 * 				)
	 */
	public static function computeMonthsAndDaysInDateRange($fromDate, $untilDate) {

		$result = array();

		//SANITY CHECK
		//fromDate MUST BE BEFORE untilDate
		if ( $fromDate > $untilDate ) {

			return array( 'months' => 0 , 'days' => 0 ) ;
		}

		// as the computation is to be inclusive the indicated date range,
		// we need to add one day to the end date
		$adjUntilDate = DateTimeHelper::addDaysToDateTime($untilDate, 1);

		// increment months until we reach adjusted date month
		$monthCounter = 0;
		$curIterationDate = $fromDate;
		while ( true ) {
			// we step forward in one month increments until we reach the month/year of the adjusted until date
			if ( 	(DateTimeHelper::getMonthFromDateTime($curIterationDate) !== DateTimeHelper::getMonthFromDateTime($adjUntilDate))
				 ||	(DateTimeHelper::getYearFromDateTime($curIterationDate) !== DateTimeHelper::getYearFromDateTime($adjUntilDate))) {

				 // step the date one month forward
				 $curIterationDate = DateTimeHelper::addMonthsToDateNormalSemantics($fromDate, $monthCounter + 1);

				 // keep track of the total count of months we've stepped through
				 $monthCounter++;
			}
			else {
				$finalIterationDate = $curIterationDate;
				break;
			}
		}

		//
		// at this point we've reached the month/year of the adjusted until date
		// we now need to proceed according to one of these cases:
		//
		//	case 1: we've overshot the adjusted until date
		//			in this case, we step one month back from the final iteration date and
		//			compute the day remainder as the difference in days between between that time
		//			point and the adjusted until date. the month count is given by the current month count minus one.
		//
		//	case 2:	we've hit the adjusted until date exactly
		//			the result is equal to the current month count, with a day remainder of zero
		//
		//	case 3:	we've undershot the adjusted until date
		//			the result is equal to the current month count, with the day remainder given
		//			by the difference in days between the final iteration date and the adjusted until date

		if ( $finalIterationDate > $adjUntilDate ) {

			// case 1 (see above)

			// accordingly, the resulting number of full months is one less than the count obtained while
			// iterating across the date range
			$resultMonths = $monthCounter - 1;

			// the adjusted month range corresponds to this final iteration date
			$finalIterationDate = DateTimeHelper::addMonthsToDateNormalSemantics($fromDate, $monthCounter - 1);

			// the day remainder is given as the difference in days between the final iteration date and the adjusted until date
			$resultDays = $finalIterationDate->diff($adjUntilDate)->days;
		}
		else {

			// cases 2 and 3 (see above) can be handled in one go

			// the resulting number of full months is given by the count obtained while
			// iterating across the date range
			$resultMonths = $monthCounter;

			// the day remainder is given as the difference in days between the final iteration date and the adjusted until date
			$resultDays = $finalIterationDate->diff($adjUntilDate)->days;
		}

		// complete result data structure
		$result["months"] = $resultMonths;
		$result["days"] = $resultDays;

		return $result;
	}


	/**
	 * Computes the number of workdays (Mon, Tue, ..., Fri) in a given date range.
	 *
	 * @param 	DateTime 	$fromDate		- the start date of the range to consider (inclusive)
	 * @param 	DateTime 	$untilDate		- the end date of the range to consider (inclusive)
	 *
	 * @return	integer
	 */
	public static function computeWorkdaysInDateRange($fromDate, $untilDate) {

		$workDaysInDateRange = 0;

		// sanity test
		if ( $fromDate <= $untilDate ) {

			//
			// first off, test if the from and end dates lie within the same week
			if ( DateTimeHelper::getStartOfWeek($fromDate) == DateTimeHelper::getStartOfWeek($untilDate) ) {
				//
				// if so, we figure out the weekdays contained between the fromDate and untilDate
				if ( DateTimeHelper::getWeekDayNumberFromDateTime($fromDate) <= 5) {

					$workDaysInDateRange = 1 + (5 - DateTimeHelper::getWeekDayNumberFromDateTime($fromDate));

					// compensate workdays if until date falls before friday
					if ( DateTimeHelper::getWeekDayNumberFromDateTime($untilDate) <= 4 ) {
						$workDaysInDateRange -= (5 - DateTimeHelper::getWeekDayNumberFromDateTime($untilDate));
					}
				}
			}
			else {
				//
				// otherwise, we step through the range week by week until we wind up in week indicated by "untilDate"

				// set up a pointer to the currently processed week in date range
				$curWeekMondayDate = DateTimeHelper::getStartOfWeek($fromDate);

				// first off, add possible fractional contribution of fromDate workweek to result
				if ( 	(DateTimeHelper::getStartOfWeek($fromDate) != $fromDate)
					 && (DateTimeHelper::getWeekDayNumberFromDateTime($fromDate) <= 5) ) {
					//
					// add workday contribution due to fractional start week
					$workDaysInDateRange += 5 - (DateTimeHelper::getWeekDayNumberFromDateTime($fromDate) - 1);

					// and step current week pointer forward to next week
					$curWeekMondayDate = DateTimeHelper::addDaysToDateTime($curWeekMondayDate, 7);
				}
				else if ( DateTimeHelper::getWeekDayNumberFromDateTime($fromDate) > 5 ) {
					// if the fractional start week falls on a weekend, we step forward to the next week as well
					// but dont need to count a workday contribution
					$curWeekMondayDate = DateTimeHelper::addDaysToDateTime($curWeekMondayDate, 7);
				}

				//
				// step through date range and add weekday contributions
				while ( true ) {
					if ( DateTimeHelper::getStartOfWeek($untilDate) != $curWeekMondayDate ) {
						//
						// we haven't reached the until week yet, therefore we fully count the current week's workdays
						$workDaysInDateRange += 5;
						$curWeekMondayDate = DateTimeHelper::addDaysToDateTime($curWeekMondayDate, 7);
					}
					else {
						// we've reached the untilDate's week...
						// we add whatever part of the untilDate week applies up to a maximum of 5 days
						$workDaysInDateRange += min(DateTimeHelper::getWeekDayNumberFromDateTime($untilDate), 5);

						break;
					}
				}

			}
		}
		else {
			throw new MomoException("DateTimeHelper::computeWorkdaysInDateRange() - invalid date range specified.");
		}


		return $workDaysInDateRange;

	}

}