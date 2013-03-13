<?php


/**
 * Base class that represents a query for the 'days' table.
 *
 * 
 *
 * @method     DayQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     DayQuery orderByWorkplanId($order = Criteria::ASC) Order by the workplan_id column
 * @method     DayQuery orderByDateofday($order = Criteria::ASC) Order by the dateOfDay column
 * @method     DayQuery orderByWeekdayname($order = Criteria::ASC) Order by the weekDayName column
 * @method     DayQuery orderByIso8601week($order = Criteria::ASC) Order by the iso8601Week column
 *
 * @method     DayQuery groupById() Group by the id column
 * @method     DayQuery groupByWorkplanId() Group by the workplan_id column
 * @method     DayQuery groupByDateofday() Group by the dateOfDay column
 * @method     DayQuery groupByWeekdayname() Group by the weekDayName column
 * @method     DayQuery groupByIso8601week() Group by the iso8601Week column
 *
 * @method     DayQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DayQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DayQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DayQuery leftJoinWorkplan($relationAlias = null) Adds a LEFT JOIN clause to the query using the Workplan relation
 * @method     DayQuery rightJoinWorkplan($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Workplan relation
 * @method     DayQuery innerJoinWorkplan($relationAlias = null) Adds a INNER JOIN clause to the query using the Workplan relation
 *
 * @method     DayQuery leftJoinTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tag relation
 * @method     DayQuery rightJoinTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tag relation
 * @method     DayQuery innerJoinTag($relationAlias = null) Adds a INNER JOIN clause to the query using the Tag relation
 *
 * @method     DayQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     DayQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     DayQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     DayQuery leftJoinRegularEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegularEntry relation
 * @method     DayQuery rightJoinRegularEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegularEntry relation
 * @method     DayQuery innerJoinRegularEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the RegularEntry relation
 *
 * @method     DayQuery leftJoinProjectEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectEntry relation
 * @method     DayQuery rightJoinProjectEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectEntry relation
 * @method     DayQuery innerJoinProjectEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectEntry relation
 *
 * @method     DayQuery leftJoinOOEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOEntry relation
 * @method     DayQuery rightJoinOOEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOEntry relation
 * @method     DayQuery innerJoinOOEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the OOEntry relation
 *
 * @method     DayQuery leftJoinAdjustmentEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdjustmentEntry relation
 * @method     DayQuery rightJoinAdjustmentEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdjustmentEntry relation
 * @method     DayQuery innerJoinAdjustmentEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the AdjustmentEntry relation
 *
 * @method     Day findOne(PropelPDO $con = null) Return the first Day matching the query
 * @method     Day findOneOrCreate(PropelPDO $con = null) Return the first Day matching the query, or a new Day object populated from the query conditions when no match is found
 *
 * @method     Day findOneById(int $id) Return the first Day filtered by the id column
 * @method     Day findOneByWorkplanId(int $workplan_id) Return the first Day filtered by the workplan_id column
 * @method     Day findOneByDateofday(string $dateOfDay) Return the first Day filtered by the dateOfDay column
 * @method     Day findOneByWeekdayname(string $weekDayName) Return the first Day filtered by the weekDayName column
 * @method     Day findOneByIso8601week(int $iso8601Week) Return the first Day filtered by the iso8601Week column
 *
 * @method     array findById(int $id) Return Day objects filtered by the id column
 * @method     array findByWorkplanId(int $workplan_id) Return Day objects filtered by the workplan_id column
 * @method     array findByDateofday(string $dateOfDay) Return Day objects filtered by the dateOfDay column
 * @method     array findByWeekdayname(string $weekDayName) Return Day objects filtered by the weekDayName column
 * @method     array findByIso8601week(int $iso8601Week) Return Day objects filtered by the iso8601Week column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseDayQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseDayQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Day', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DayQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DayQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DayQuery) {
			return $criteria;
		}
		$query = new DayQuery();
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
	 * @return    Day|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = DayPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(DayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Day A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `WORKPLAN_ID`, `DATEOFDAY`, `WEEKDAYNAME`, `ISO8601WEEK` FROM `days` WHERE `ID` = :p0';
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
			$obj = new Day();
			$obj->hydrate($row);
			DayPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Day|array|mixed the result, formatted by the current formatter
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
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DayPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DayPeer::ID, $keys, Criteria::IN);
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
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DayPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the workplan_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByWorkplanId(1234); // WHERE workplan_id = 1234
	 * $query->filterByWorkplanId(array(12, 34)); // WHERE workplan_id IN (12, 34)
	 * $query->filterByWorkplanId(array('min' => 12)); // WHERE workplan_id > 12
	 * </code>
	 *
	 * @see       filterByWorkplan()
	 *
	 * @param     mixed $workplanId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByWorkplanId($workplanId = null, $comparison = null)
	{
		if (is_array($workplanId)) {
			$useMinMax = false;
			if (isset($workplanId['min'])) {
				$this->addUsingAlias(DayPeer::WORKPLAN_ID, $workplanId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($workplanId['max'])) {
				$this->addUsingAlias(DayPeer::WORKPLAN_ID, $workplanId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DayPeer::WORKPLAN_ID, $workplanId, $comparison);
	}

	/**
	 * Filter the query on the dateOfDay column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDateofday('2011-03-14'); // WHERE dateOfDay = '2011-03-14'
	 * $query->filterByDateofday('now'); // WHERE dateOfDay = '2011-03-14'
	 * $query->filterByDateofday(array('max' => 'yesterday')); // WHERE dateOfDay > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $dateofday The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByDateofday($dateofday = null, $comparison = null)
	{
		if (is_array($dateofday)) {
			$useMinMax = false;
			if (isset($dateofday['min'])) {
				$this->addUsingAlias(DayPeer::DATEOFDAY, $dateofday['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dateofday['max'])) {
				$this->addUsingAlias(DayPeer::DATEOFDAY, $dateofday['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DayPeer::DATEOFDAY, $dateofday, $comparison);
	}

	/**
	 * Filter the query on the weekDayName column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByWeekdayname('fooValue');   // WHERE weekDayName = 'fooValue'
	 * $query->filterByWeekdayname('%fooValue%'); // WHERE weekDayName LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $weekdayname The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByWeekdayname($weekdayname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($weekdayname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $weekdayname)) {
				$weekdayname = str_replace('*', '%', $weekdayname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DayPeer::WEEKDAYNAME, $weekdayname, $comparison);
	}

	/**
	 * Filter the query on the iso8601Week column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByIso8601week(1234); // WHERE iso8601Week = 1234
	 * $query->filterByIso8601week(array(12, 34)); // WHERE iso8601Week IN (12, 34)
	 * $query->filterByIso8601week(array('min' => 12)); // WHERE iso8601Week > 12
	 * </code>
	 *
	 * @param     mixed $iso8601week The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByIso8601week($iso8601week = null, $comparison = null)
	{
		if (is_array($iso8601week)) {
			$useMinMax = false;
			if (isset($iso8601week['min'])) {
				$this->addUsingAlias(DayPeer::ISO8601WEEK, $iso8601week['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($iso8601week['max'])) {
				$this->addUsingAlias(DayPeer::ISO8601WEEK, $iso8601week['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DayPeer::ISO8601WEEK, $iso8601week, $comparison);
	}

	/**
	 * Filter the query by a related Workplan object
	 *
	 * @param     Workplan|PropelCollection $workplan The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByWorkplan($workplan, $comparison = null)
	{
		if ($workplan instanceof Workplan) {
			return $this
				->addUsingAlias(DayPeer::WORKPLAN_ID, $workplan->getId(), $comparison);
		} elseif ($workplan instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(DayPeer::WORKPLAN_ID, $workplan->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByWorkplan() only accepts arguments of type Workplan or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Workplan relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinWorkplan($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Workplan');

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
			$this->addJoinObject($join, 'Workplan');
		}

		return $this;
	}

	/**
	 * Use the Workplan relation Workplan object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WorkplanQuery A secondary query class using the current class as primary query
	 */
	public function useWorkplanQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWorkplan($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Workplan', 'WorkplanQuery');
	}

	/**
	 * Filter the query by a related Tag object
	 *
	 * @param     Tag $tag  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByTag($tag, $comparison = null)
	{
		if ($tag instanceof Tag) {
			return $this
				->addUsingAlias(DayPeer::ID, $tag->getDayId(), $comparison);
		} elseif ($tag instanceof PropelCollection) {
			return $this
				->useTagQuery()
				->filterByPrimaryKeys($tag->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByTag() only accepts arguments of type Tag or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Tag relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Tag');

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
			$this->addJoinObject($join, 'Tag');
		}

		return $this;
	}

	/**
	 * Use the Tag relation Tag object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TagQuery A secondary query class using the current class as primary query
	 */
	public function useTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinTag($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Tag', 'TagQuery');
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry $entry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(DayPeer::ID, $entry->getDayId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			return $this
				->useEntryQuery()
				->filterByPrimaryKeys($entry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByEntry() only accepts arguments of type Entry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Entry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Entry');

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
			$this->addJoinObject($join, 'Entry');
		}

		return $this;
	}

	/**
	 * Use the Entry relation Entry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EntryQuery A secondary query class using the current class as primary query
	 */
	public function useEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Entry', 'EntryQuery');
	}

	/**
	 * Filter the query by a related RegularEntry object
	 *
	 * @param     RegularEntry $regularEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByRegularEntry($regularEntry, $comparison = null)
	{
		if ($regularEntry instanceof RegularEntry) {
			return $this
				->addUsingAlias(DayPeer::ID, $regularEntry->getDayId(), $comparison);
		} elseif ($regularEntry instanceof PropelCollection) {
			return $this
				->useRegularEntryQuery()
				->filterByPrimaryKeys($regularEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByRegularEntry() only accepts arguments of type RegularEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the RegularEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinRegularEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('RegularEntry');

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
			$this->addJoinObject($join, 'RegularEntry');
		}

		return $this;
	}

	/**
	 * Use the RegularEntry relation RegularEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    RegularEntryQuery A secondary query class using the current class as primary query
	 */
	public function useRegularEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinRegularEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'RegularEntry', 'RegularEntryQuery');
	}

	/**
	 * Filter the query by a related ProjectEntry object
	 *
	 * @param     ProjectEntry $projectEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByProjectEntry($projectEntry, $comparison = null)
	{
		if ($projectEntry instanceof ProjectEntry) {
			return $this
				->addUsingAlias(DayPeer::ID, $projectEntry->getDayId(), $comparison);
		} elseif ($projectEntry instanceof PropelCollection) {
			return $this
				->useProjectEntryQuery()
				->filterByPrimaryKeys($projectEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByProjectEntry() only accepts arguments of type ProjectEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the ProjectEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinProjectEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('ProjectEntry');

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
			$this->addJoinObject($join, 'ProjectEntry');
		}

		return $this;
	}

	/**
	 * Use the ProjectEntry relation ProjectEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ProjectEntryQuery A secondary query class using the current class as primary query
	 */
	public function useProjectEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinProjectEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'ProjectEntry', 'ProjectEntryQuery');
	}

	/**
	 * Filter the query by a related OOEntry object
	 *
	 * @param     OOEntry $oOEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByOOEntry($oOEntry, $comparison = null)
	{
		if ($oOEntry instanceof OOEntry) {
			return $this
				->addUsingAlias(DayPeer::ID, $oOEntry->getDayId(), $comparison);
		} elseif ($oOEntry instanceof PropelCollection) {
			return $this
				->useOOEntryQuery()
				->filterByPrimaryKeys($oOEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByOOEntry() only accepts arguments of type OOEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the OOEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinOOEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('OOEntry');

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
			$this->addJoinObject($join, 'OOEntry');
		}

		return $this;
	}

	/**
	 * Use the OOEntry relation OOEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOEntryQuery A secondary query class using the current class as primary query
	 */
	public function useOOEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinOOEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'OOEntry', 'OOEntryQuery');
	}

	/**
	 * Filter the query by a related AdjustmentEntry object
	 *
	 * @param     AdjustmentEntry $adjustmentEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function filterByAdjustmentEntry($adjustmentEntry, $comparison = null)
	{
		if ($adjustmentEntry instanceof AdjustmentEntry) {
			return $this
				->addUsingAlias(DayPeer::ID, $adjustmentEntry->getDayId(), $comparison);
		} elseif ($adjustmentEntry instanceof PropelCollection) {
			return $this
				->useAdjustmentEntryQuery()
				->filterByPrimaryKeys($adjustmentEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByAdjustmentEntry() only accepts arguments of type AdjustmentEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the AdjustmentEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function joinAdjustmentEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AdjustmentEntry');

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
			$this->addJoinObject($join, 'AdjustmentEntry');
		}

		return $this;
	}

	/**
	 * Use the AdjustmentEntry relation AdjustmentEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AdjustmentEntryQuery A secondary query class using the current class as primary query
	 */
	public function useAdjustmentEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAdjustmentEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AdjustmentEntry', 'AdjustmentEntryQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Day $day Object to remove from the list of results
	 *
	 * @return    DayQuery The current query, for fluid interface
	 */
	public function prune($day = null)
	{
		if ($day) {
			$this->addUsingAlias(DayPeer::ID, $day->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseDayQuery