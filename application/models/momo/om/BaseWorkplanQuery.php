<?php


/**
 * Base class that represents a query for the 'workplans' table.
 *
 * 
 *
 * @method     WorkplanQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     WorkplanQuery orderByYear($order = Criteria::ASC) Order by the year column
 * @method     WorkplanQuery orderByWeeklyworkhours($order = Criteria::ASC) Order by the weeklyWorkHours column
 * @method     WorkplanQuery orderByAnnualvacationdaysupto19($order = Criteria::ASC) Order by the annualVacationDaysUpTo19 column
 * @method     WorkplanQuery orderByAnnualvacationdays20to49($order = Criteria::ASC) Order by the annualVacationDays20to49 column
 * @method     WorkplanQuery orderByAnnualvacationdaysfrom50($order = Criteria::ASC) Order by the annualVacationDaysFrom50 column
 *
 * @method     WorkplanQuery groupById() Group by the id column
 * @method     WorkplanQuery groupByYear() Group by the year column
 * @method     WorkplanQuery groupByWeeklyworkhours() Group by the weeklyWorkHours column
 * @method     WorkplanQuery groupByAnnualvacationdaysupto19() Group by the annualVacationDaysUpTo19 column
 * @method     WorkplanQuery groupByAnnualvacationdays20to49() Group by the annualVacationDays20to49 column
 * @method     WorkplanQuery groupByAnnualvacationdaysfrom50() Group by the annualVacationDaysFrom50 column
 *
 * @method     WorkplanQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WorkplanQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WorkplanQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WorkplanQuery leftJoinHoliday($relationAlias = null) Adds a LEFT JOIN clause to the query using the Holiday relation
 * @method     WorkplanQuery rightJoinHoliday($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Holiday relation
 * @method     WorkplanQuery innerJoinHoliday($relationAlias = null) Adds a INNER JOIN clause to the query using the Holiday relation
 *
 * @method     WorkplanQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     WorkplanQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     WorkplanQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     Workplan findOne(PropelPDO $con = null) Return the first Workplan matching the query
 * @method     Workplan findOneOrCreate(PropelPDO $con = null) Return the first Workplan matching the query, or a new Workplan object populated from the query conditions when no match is found
 *
 * @method     Workplan findOneById(int $id) Return the first Workplan filtered by the id column
 * @method     Workplan findOneByYear(int $year) Return the first Workplan filtered by the year column
 * @method     Workplan findOneByWeeklyworkhours(int $weeklyWorkHours) Return the first Workplan filtered by the weeklyWorkHours column
 * @method     Workplan findOneByAnnualvacationdaysupto19(int $annualVacationDaysUpTo19) Return the first Workplan filtered by the annualVacationDaysUpTo19 column
 * @method     Workplan findOneByAnnualvacationdays20to49(int $annualVacationDays20to49) Return the first Workplan filtered by the annualVacationDays20to49 column
 * @method     Workplan findOneByAnnualvacationdaysfrom50(int $annualVacationDaysFrom50) Return the first Workplan filtered by the annualVacationDaysFrom50 column
 *
 * @method     array findById(int $id) Return Workplan objects filtered by the id column
 * @method     array findByYear(int $year) Return Workplan objects filtered by the year column
 * @method     array findByWeeklyworkhours(int $weeklyWorkHours) Return Workplan objects filtered by the weeklyWorkHours column
 * @method     array findByAnnualvacationdaysupto19(int $annualVacationDaysUpTo19) Return Workplan objects filtered by the annualVacationDaysUpTo19 column
 * @method     array findByAnnualvacationdays20to49(int $annualVacationDays20to49) Return Workplan objects filtered by the annualVacationDays20to49 column
 * @method     array findByAnnualvacationdaysfrom50(int $annualVacationDaysFrom50) Return Workplan objects filtered by the annualVacationDaysFrom50 column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseWorkplanQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseWorkplanQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Workplan', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WorkplanQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WorkplanQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WorkplanQuery) {
			return $criteria;
		}
		$query = new WorkplanQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key.
	 * Propel uses the instance pool to skip the database if the object exists.
	 * Go fast if the query is untouched.
	 *
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    Workplan|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = WorkplanPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(WorkplanPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		if ($this->formatter || $this->modelAlias || $this->with || $this->select
		 || $this->selectColumns || $this->asColumns || $this->selectModifiers
		 || $this->map || $this->having || $this->joins) {
			return $this->findPkComplex($key, $con);
		} else {
			return $this->findPkSimple($key, $con);
		}
	}

	/**
	 * Find object by primary key using raw SQL to go fast.
	 * Bypass doSelect() and the object formatter by using generated code.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    Workplan A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `YEAR`, `WEEKLYWORKHOURS`, `ANNUALVACATIONDAYSUPTO19`, `ANNUALVACATIONDAYS20TO49`, `ANNUALVACATIONDAYSFROM50` FROM `workplans` WHERE `ID` = :p0';
		try {
			$stmt = $con->prepare($sql);			
			$stmt->bindValue(':p0', $key, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new Workplan();
			$obj->hydrate($row);
			WorkplanPeer::addInstanceToPool($obj, (string) $key);
		}
		$stmt->closeCursor();

		return $obj;
	}

	/**
	 * Find object by primary key.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    Workplan|array|mixed the result, formatted by the current formatter
	 */
	protected function findPkComplex($key, $con)
	{
		// As the query uses a PK condition, no limit(1) is necessary.
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKey($key)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKeys($keys)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->format($stmt);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WorkplanPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WorkplanPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterById(1234); // WHERE id = 1234
	 * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
	 * $query->filterById(array('min' => 12)); // WHERE id > 12
	 * </code>
	 *
	 * @param     mixed $id The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WorkplanPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the year column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByYear(1234); // WHERE year = 1234
	 * $query->filterByYear(array(12, 34)); // WHERE year IN (12, 34)
	 * $query->filterByYear(array('min' => 12)); // WHERE year > 12
	 * </code>
	 *
	 * @param     mixed $year The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByYear($year = null, $comparison = null)
	{
		if (is_array($year)) {
			$useMinMax = false;
			if (isset($year['min'])) {
				$this->addUsingAlias(WorkplanPeer::YEAR, $year['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($year['max'])) {
				$this->addUsingAlias(WorkplanPeer::YEAR, $year['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WorkplanPeer::YEAR, $year, $comparison);
	}

	/**
	 * Filter the query on the weeklyWorkHours column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByWeeklyworkhours(1234); // WHERE weeklyWorkHours = 1234
	 * $query->filterByWeeklyworkhours(array(12, 34)); // WHERE weeklyWorkHours IN (12, 34)
	 * $query->filterByWeeklyworkhours(array('min' => 12)); // WHERE weeklyWorkHours > 12
	 * </code>
	 *
	 * @param     mixed $weeklyworkhours The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByWeeklyworkhours($weeklyworkhours = null, $comparison = null)
	{
		if (is_array($weeklyworkhours)) {
			$useMinMax = false;
			if (isset($weeklyworkhours['min'])) {
				$this->addUsingAlias(WorkplanPeer::WEEKLYWORKHOURS, $weeklyworkhours['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($weeklyworkhours['max'])) {
				$this->addUsingAlias(WorkplanPeer::WEEKLYWORKHOURS, $weeklyworkhours['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WorkplanPeer::WEEKLYWORKHOURS, $weeklyworkhours, $comparison);
	}

	/**
	 * Filter the query on the annualVacationDaysUpTo19 column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAnnualvacationdaysupto19(1234); // WHERE annualVacationDaysUpTo19 = 1234
	 * $query->filterByAnnualvacationdaysupto19(array(12, 34)); // WHERE annualVacationDaysUpTo19 IN (12, 34)
	 * $query->filterByAnnualvacationdaysupto19(array('min' => 12)); // WHERE annualVacationDaysUpTo19 > 12
	 * </code>
	 *
	 * @param     mixed $annualvacationdaysupto19 The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByAnnualvacationdaysupto19($annualvacationdaysupto19 = null, $comparison = null)
	{
		if (is_array($annualvacationdaysupto19)) {
			$useMinMax = false;
			if (isset($annualvacationdaysupto19['min'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSUPTO19, $annualvacationdaysupto19['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($annualvacationdaysupto19['max'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSUPTO19, $annualvacationdaysupto19['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSUPTO19, $annualvacationdaysupto19, $comparison);
	}

	/**
	 * Filter the query on the annualVacationDays20to49 column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAnnualvacationdays20to49(1234); // WHERE annualVacationDays20to49 = 1234
	 * $query->filterByAnnualvacationdays20to49(array(12, 34)); // WHERE annualVacationDays20to49 IN (12, 34)
	 * $query->filterByAnnualvacationdays20to49(array('min' => 12)); // WHERE annualVacationDays20to49 > 12
	 * </code>
	 *
	 * @param     mixed $annualvacationdays20to49 The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByAnnualvacationdays20to49($annualvacationdays20to49 = null, $comparison = null)
	{
		if (is_array($annualvacationdays20to49)) {
			$useMinMax = false;
			if (isset($annualvacationdays20to49['min'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYS20TO49, $annualvacationdays20to49['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($annualvacationdays20to49['max'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYS20TO49, $annualvacationdays20to49['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYS20TO49, $annualvacationdays20to49, $comparison);
	}

	/**
	 * Filter the query on the annualVacationDaysFrom50 column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAnnualvacationdaysfrom50(1234); // WHERE annualVacationDaysFrom50 = 1234
	 * $query->filterByAnnualvacationdaysfrom50(array(12, 34)); // WHERE annualVacationDaysFrom50 IN (12, 34)
	 * $query->filterByAnnualvacationdaysfrom50(array('min' => 12)); // WHERE annualVacationDaysFrom50 > 12
	 * </code>
	 *
	 * @param     mixed $annualvacationdaysfrom50 The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByAnnualvacationdaysfrom50($annualvacationdaysfrom50 = null, $comparison = null)
	{
		if (is_array($annualvacationdaysfrom50)) {
			$useMinMax = false;
			if (isset($annualvacationdaysfrom50['min'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSFROM50, $annualvacationdaysfrom50['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($annualvacationdaysfrom50['max'])) {
				$this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSFROM50, $annualvacationdaysfrom50['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WorkplanPeer::ANNUALVACATIONDAYSFROM50, $annualvacationdaysfrom50, $comparison);
	}

	/**
	 * Filter the query by a related Holiday object
	 *
	 * @param     Holiday $holiday  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByHoliday($holiday, $comparison = null)
	{
		if ($holiday instanceof Holiday) {
			return $this
				->addUsingAlias(WorkplanPeer::ID, $holiday->getWorkplanId(), $comparison);
		} elseif ($holiday instanceof PropelCollection) {
			return $this
				->useHolidayQuery()
				->filterByPrimaryKeys($holiday->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByHoliday() only accepts arguments of type Holiday or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Holiday relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function joinHoliday($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Holiday');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'Holiday');
		}

		return $this;
	}

	/**
	 * Use the Holiday relation Holiday object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    HolidayQuery A secondary query class using the current class as primary query
	 */
	public function useHolidayQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinHoliday($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Holiday', 'HolidayQuery');
	}

	/**
	 * Filter the query by a related Day object
	 *
	 * @param     Day $day  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(WorkplanPeer::ID, $day->getWorkplanId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			return $this
				->useDayQuery()
				->filterByPrimaryKeys($day->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByDay() only accepts arguments of type Day or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Day relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function joinDay($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Day');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'Day');
		}

		return $this;
	}

	/**
	 * Use the Day relation Day object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery A secondary query class using the current class as primary query
	 */
	public function useDayQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinDay($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Day', 'DayQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Workplan $workplan Object to remove from the list of results
	 *
	 * @return    WorkplanQuery The current query, for fluid interface
	 */
	public function prune($workplan = null)
	{
		if ($workplan) {
			$this->addUsingAlias(WorkplanPeer::ID, $workplan->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseWorkplanQuery