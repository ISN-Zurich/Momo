<?php


/**
 * Base class that represents a query for the 'holidays' table.
 *
 * 
 *
 * @method     HolidayQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     HolidayQuery orderByWorkplanId($order = Criteria::ASC) Order by the workplan_id column
 * @method     HolidayQuery orderByDateofholiday($order = Criteria::ASC) Order by the dateOfHoliday column
 * @method     HolidayQuery orderByFullday($order = Criteria::ASC) Order by the fullDay column
 * @method     HolidayQuery orderByHalfday($order = Criteria::ASC) Order by the halfDay column
 * @method     HolidayQuery orderByOnehour($order = Criteria::ASC) Order by the oneHour column
 *
 * @method     HolidayQuery groupById() Group by the id column
 * @method     HolidayQuery groupByWorkplanId() Group by the workplan_id column
 * @method     HolidayQuery groupByDateofholiday() Group by the dateOfHoliday column
 * @method     HolidayQuery groupByFullday() Group by the fullDay column
 * @method     HolidayQuery groupByHalfday() Group by the halfDay column
 * @method     HolidayQuery groupByOnehour() Group by the oneHour column
 *
 * @method     HolidayQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     HolidayQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     HolidayQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     HolidayQuery leftJoinWorkplan($relationAlias = null) Adds a LEFT JOIN clause to the query using the Workplan relation
 * @method     HolidayQuery rightJoinWorkplan($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Workplan relation
 * @method     HolidayQuery innerJoinWorkplan($relationAlias = null) Adds a INNER JOIN clause to the query using the Workplan relation
 *
 * @method     Holiday findOne(PropelPDO $con = null) Return the first Holiday matching the query
 * @method     Holiday findOneOrCreate(PropelPDO $con = null) Return the first Holiday matching the query, or a new Holiday object populated from the query conditions when no match is found
 *
 * @method     Holiday findOneById(int $id) Return the first Holiday filtered by the id column
 * @method     Holiday findOneByWorkplanId(int $workplan_id) Return the first Holiday filtered by the workplan_id column
 * @method     Holiday findOneByDateofholiday(string $dateOfHoliday) Return the first Holiday filtered by the dateOfHoliday column
 * @method     Holiday findOneByFullday(boolean $fullDay) Return the first Holiday filtered by the fullDay column
 * @method     Holiday findOneByHalfday(boolean $halfDay) Return the first Holiday filtered by the halfDay column
 * @method     Holiday findOneByOnehour(boolean $oneHour) Return the first Holiday filtered by the oneHour column
 *
 * @method     array findById(int $id) Return Holiday objects filtered by the id column
 * @method     array findByWorkplanId(int $workplan_id) Return Holiday objects filtered by the workplan_id column
 * @method     array findByDateofholiday(string $dateOfHoliday) Return Holiday objects filtered by the dateOfHoliday column
 * @method     array findByFullday(boolean $fullDay) Return Holiday objects filtered by the fullDay column
 * @method     array findByHalfday(boolean $halfDay) Return Holiday objects filtered by the halfDay column
 * @method     array findByOnehour(boolean $oneHour) Return Holiday objects filtered by the oneHour column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseHolidayQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseHolidayQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Holiday', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new HolidayQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    HolidayQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof HolidayQuery) {
			return $criteria;
		}
		$query = new HolidayQuery();
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
	 * @return    Holiday|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = HolidayPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(HolidayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Holiday A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `WORKPLAN_ID`, `DATEOFHOLIDAY`, `FULLDAY`, `HALFDAY`, `ONEHOUR` FROM `holidays` WHERE `ID` = :p0';
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
			$obj = new Holiday();
			$obj->hydrate($row);
			HolidayPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Holiday|array|mixed the result, formatted by the current formatter
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
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(HolidayPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(HolidayPeer::ID, $keys, Criteria::IN);
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
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(HolidayPeer::ID, $id, $comparison);
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
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByWorkplanId($workplanId = null, $comparison = null)
	{
		if (is_array($workplanId)) {
			$useMinMax = false;
			if (isset($workplanId['min'])) {
				$this->addUsingAlias(HolidayPeer::WORKPLAN_ID, $workplanId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($workplanId['max'])) {
				$this->addUsingAlias(HolidayPeer::WORKPLAN_ID, $workplanId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(HolidayPeer::WORKPLAN_ID, $workplanId, $comparison);
	}

	/**
	 * Filter the query on the dateOfHoliday column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDateofholiday('2011-03-14'); // WHERE dateOfHoliday = '2011-03-14'
	 * $query->filterByDateofholiday('now'); // WHERE dateOfHoliday = '2011-03-14'
	 * $query->filterByDateofholiday(array('max' => 'yesterday')); // WHERE dateOfHoliday > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $dateofholiday The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByDateofholiday($dateofholiday = null, $comparison = null)
	{
		if (is_array($dateofholiday)) {
			$useMinMax = false;
			if (isset($dateofholiday['min'])) {
				$this->addUsingAlias(HolidayPeer::DATEOFHOLIDAY, $dateofholiday['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dateofholiday['max'])) {
				$this->addUsingAlias(HolidayPeer::DATEOFHOLIDAY, $dateofholiday['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(HolidayPeer::DATEOFHOLIDAY, $dateofholiday, $comparison);
	}

	/**
	 * Filter the query on the fullDay column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByFullday(true); // WHERE fullDay = true
	 * $query->filterByFullday('yes'); // WHERE fullDay = true
	 * </code>
	 *
	 * @param     boolean|string $fullday The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByFullday($fullday = null, $comparison = null)
	{
		if (is_string($fullday)) {
			$fullDay = in_array(strtolower($fullday), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(HolidayPeer::FULLDAY, $fullday, $comparison);
	}

	/**
	 * Filter the query on the halfDay column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByHalfday(true); // WHERE halfDay = true
	 * $query->filterByHalfday('yes'); // WHERE halfDay = true
	 * </code>
	 *
	 * @param     boolean|string $halfday The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByHalfday($halfday = null, $comparison = null)
	{
		if (is_string($halfday)) {
			$halfDay = in_array(strtolower($halfday), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(HolidayPeer::HALFDAY, $halfday, $comparison);
	}

	/**
	 * Filter the query on the oneHour column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOnehour(true); // WHERE oneHour = true
	 * $query->filterByOnehour('yes'); // WHERE oneHour = true
	 * </code>
	 *
	 * @param     boolean|string $onehour The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByOnehour($onehour = null, $comparison = null)
	{
		if (is_string($onehour)) {
			$oneHour = in_array(strtolower($onehour), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(HolidayPeer::ONEHOUR, $onehour, $comparison);
	}

	/**
	 * Filter the query by a related Workplan object
	 *
	 * @param     Workplan|PropelCollection $workplan The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function filterByWorkplan($workplan, $comparison = null)
	{
		if ($workplan instanceof Workplan) {
			return $this
				->addUsingAlias(HolidayPeer::WORKPLAN_ID, $workplan->getId(), $comparison);
		} elseif ($workplan instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(HolidayPeer::WORKPLAN_ID, $workplan->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    HolidayQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     Holiday $holiday Object to remove from the list of results
	 *
	 * @return    HolidayQuery The current query, for fluid interface
	 */
	public function prune($holiday = null)
	{
		if ($holiday) {
			$this->addUsingAlias(HolidayPeer::ID, $holiday->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseHolidayQuery