<?php


/**
 * Base class that represents a query for the 'regularentries' table.
 *
 * 
 *
 * @method     RegularEntryQuery orderByRegularentrytypeId($order = Criteria::ASC) Order by the regularentrytype_id column
 * @method     RegularEntryQuery orderByFrom($order = Criteria::ASC) Order by the from column
 * @method     RegularEntryQuery orderByUntil($order = Criteria::ASC) Order by the until column
 * @method     RegularEntryQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method     RegularEntryQuery orderByTimeInterval($order = Criteria::ASC) Order by the time_interval column
 * @method     RegularEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     RegularEntryQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method     RegularEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     RegularEntryQuery groupByRegularentrytypeId() Group by the regularentrytype_id column
 * @method     RegularEntryQuery groupByFrom() Group by the from column
 * @method     RegularEntryQuery groupByUntil() Group by the until column
 * @method     RegularEntryQuery groupByComment() Group by the comment column
 * @method     RegularEntryQuery groupByTimeInterval() Group by the time_interval column
 * @method     RegularEntryQuery groupById() Group by the id column
 * @method     RegularEntryQuery groupByDayId() Group by the day_id column
 * @method     RegularEntryQuery groupByUserId() Group by the user_id column
 *
 * @method     RegularEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     RegularEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     RegularEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     RegularEntryQuery leftJoinRegularEntryType($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegularEntryType relation
 * @method     RegularEntryQuery rightJoinRegularEntryType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegularEntryType relation
 * @method     RegularEntryQuery innerJoinRegularEntryType($relationAlias = null) Adds a INNER JOIN clause to the query using the RegularEntryType relation
 *
 * @method     RegularEntryQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     RegularEntryQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     RegularEntryQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     RegularEntryQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     RegularEntryQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     RegularEntryQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     RegularEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     RegularEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     RegularEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     RegularEntry findOne(PropelPDO $con = null) Return the first RegularEntry matching the query
 * @method     RegularEntry findOneOrCreate(PropelPDO $con = null) Return the first RegularEntry matching the query, or a new RegularEntry object populated from the query conditions when no match is found
 *
 * @method     RegularEntry findOneByRegularentrytypeId(int $regularentrytype_id) Return the first RegularEntry filtered by the regularentrytype_id column
 * @method     RegularEntry findOneByFrom(string $from) Return the first RegularEntry filtered by the from column
 * @method     RegularEntry findOneByUntil(string $until) Return the first RegularEntry filtered by the until column
 * @method     RegularEntry findOneByComment(string $comment) Return the first RegularEntry filtered by the comment column
 * @method     RegularEntry findOneByTimeInterval(int $time_interval) Return the first RegularEntry filtered by the time_interval column
 * @method     RegularEntry findOneById(int $id) Return the first RegularEntry filtered by the id column
 * @method     RegularEntry findOneByDayId(int $day_id) Return the first RegularEntry filtered by the day_id column
 * @method     RegularEntry findOneByUserId(int $user_id) Return the first RegularEntry filtered by the user_id column
 *
 * @method     array findByRegularentrytypeId(int $regularentrytype_id) Return RegularEntry objects filtered by the regularentrytype_id column
 * @method     array findByFrom(string $from) Return RegularEntry objects filtered by the from column
 * @method     array findByUntil(string $until) Return RegularEntry objects filtered by the until column
 * @method     array findByComment(string $comment) Return RegularEntry objects filtered by the comment column
 * @method     array findByTimeInterval(int $time_interval) Return RegularEntry objects filtered by the time_interval column
 * @method     array findById(int $id) Return RegularEntry objects filtered by the id column
 * @method     array findByDayId(int $day_id) Return RegularEntry objects filtered by the day_id column
 * @method     array findByUserId(int $user_id) Return RegularEntry objects filtered by the user_id column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseRegularEntryQuery extends EntryQuery
{
	
	/**
	 * Initializes internal state of BaseRegularEntryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'RegularEntry', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new RegularEntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    RegularEntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof RegularEntryQuery) {
			return $criteria;
		}
		$query = new RegularEntryQuery();
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
	 * @return    RegularEntry|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = RegularEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(RegularEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    RegularEntry A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `REGULARENTRYTYPE_ID`, `FROM`, `UNTIL`, `COMMENT`, `TIME_INTERVAL`, `ID`, `DAY_ID`, `USER_ID` FROM `regularentries` WHERE `ID` = :p0';
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
			$obj = new RegularEntry();
			$obj->hydrate($row);
			RegularEntryPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    RegularEntry|array|mixed the result, formatted by the current formatter
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
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(RegularEntryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(RegularEntryPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the regularentrytype_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByRegularentrytypeId(1234); // WHERE regularentrytype_id = 1234
	 * $query->filterByRegularentrytypeId(array(12, 34)); // WHERE regularentrytype_id IN (12, 34)
	 * $query->filterByRegularentrytypeId(array('min' => 12)); // WHERE regularentrytype_id > 12
	 * </code>
	 *
	 * @see       filterByRegularEntryType()
	 *
	 * @param     mixed $regularentrytypeId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByRegularentrytypeId($regularentrytypeId = null, $comparison = null)
	{
		if (is_array($regularentrytypeId)) {
			$useMinMax = false;
			if (isset($regularentrytypeId['min'])) {
				$this->addUsingAlias(RegularEntryPeer::REGULARENTRYTYPE_ID, $regularentrytypeId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($regularentrytypeId['max'])) {
				$this->addUsingAlias(RegularEntryPeer::REGULARENTRYTYPE_ID, $regularentrytypeId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::REGULARENTRYTYPE_ID, $regularentrytypeId, $comparison);
	}

	/**
	 * Filter the query on the from column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByFrom('2011-03-14'); // WHERE from = '2011-03-14'
	 * $query->filterByFrom('now'); // WHERE from = '2011-03-14'
	 * $query->filterByFrom(array('max' => 'yesterday')); // WHERE from > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $from The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByFrom($from = null, $comparison = null)
	{
		if (is_array($from)) {
			$useMinMax = false;
			if (isset($from['min'])) {
				$this->addUsingAlias(RegularEntryPeer::FROM, $from['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($from['max'])) {
				$this->addUsingAlias(RegularEntryPeer::FROM, $from['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::FROM, $from, $comparison);
	}

	/**
	 * Filter the query on the until column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUntil('2011-03-14'); // WHERE until = '2011-03-14'
	 * $query->filterByUntil('now'); // WHERE until = '2011-03-14'
	 * $query->filterByUntil(array('max' => 'yesterday')); // WHERE until > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $until The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByUntil($until = null, $comparison = null)
	{
		if (is_array($until)) {
			$useMinMax = false;
			if (isset($until['min'])) {
				$this->addUsingAlias(RegularEntryPeer::UNTIL, $until['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($until['max'])) {
				$this->addUsingAlias(RegularEntryPeer::UNTIL, $until['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::UNTIL, $until, $comparison);
	}

	/**
	 * Filter the query on the comment column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
	 * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $comment The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByComment($comment = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($comment)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $comment)) {
				$comment = str_replace('*', '%', $comment);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::COMMENT, $comment, $comparison);
	}

	/**
	 * Filter the query on the time_interval column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByTimeInterval(1234); // WHERE time_interval = 1234
	 * $query->filterByTimeInterval(array(12, 34)); // WHERE time_interval IN (12, 34)
	 * $query->filterByTimeInterval(array('min' => 12)); // WHERE time_interval > 12
	 * </code>
	 *
	 * @param     mixed $timeInterval The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByTimeInterval($timeInterval = null, $comparison = null)
	{
		if (is_array($timeInterval)) {
			$useMinMax = false;
			if (isset($timeInterval['min'])) {
				$this->addUsingAlias(RegularEntryPeer::TIME_INTERVAL, $timeInterval['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($timeInterval['max'])) {
				$this->addUsingAlias(RegularEntryPeer::TIME_INTERVAL, $timeInterval['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::TIME_INTERVAL, $timeInterval, $comparison);
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
	 * @see       filterByEntry()
	 *
	 * @param     mixed $id The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(RegularEntryPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the day_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDayId(1234); // WHERE day_id = 1234
	 * $query->filterByDayId(array(12, 34)); // WHERE day_id IN (12, 34)
	 * $query->filterByDayId(array('min' => 12)); // WHERE day_id > 12
	 * </code>
	 *
	 * @see       filterByDay()
	 *
	 * @param     mixed $dayId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByDayId($dayId = null, $comparison = null)
	{
		if (is_array($dayId)) {
			$useMinMax = false;
			if (isset($dayId['min'])) {
				$this->addUsingAlias(RegularEntryPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dayId['max'])) {
				$this->addUsingAlias(RegularEntryPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::DAY_ID, $dayId, $comparison);
	}

	/**
	 * Filter the query on the user_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUserId(1234); // WHERE user_id = 1234
	 * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
	 * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
	 * </code>
	 *
	 * @see       filterByUser()
	 *
	 * @param     mixed $userId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(RegularEntryPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(RegularEntryPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(RegularEntryPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query by a related RegularEntryType object
	 *
	 * @param     RegularEntryType|PropelCollection $regularEntryType The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByRegularEntryType($regularEntryType, $comparison = null)
	{
		if ($regularEntryType instanceof RegularEntryType) {
			return $this
				->addUsingAlias(RegularEntryPeer::REGULARENTRYTYPE_ID, $regularEntryType->getId(), $comparison);
		} elseif ($regularEntryType instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(RegularEntryPeer::REGULARENTRYTYPE_ID, $regularEntryType->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByRegularEntryType() only accepts arguments of type RegularEntryType or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the RegularEntryType relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function joinRegularEntryType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('RegularEntryType');

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
			$this->addJoinObject($join, 'RegularEntryType');
		}

		return $this;
	}

	/**
	 * Use the RegularEntryType relation RegularEntryType object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    RegularEntryTypeQuery A secondary query class using the current class as primary query
	 */
	public function useRegularEntryTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinRegularEntryType($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'RegularEntryType', 'RegularEntryTypeQuery');
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry|PropelCollection $entry The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(RegularEntryPeer::ID, $entry->getId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(RegularEntryPeer::ID, $entry->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    RegularEntryQuery The current query, for fluid interface
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
	 * Filter the query by a related Day object
	 *
	 * @param     Day|PropelCollection $day The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(RegularEntryPeer::DAY_ID, $day->getId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(RegularEntryPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    RegularEntryQuery The current query, for fluid interface
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
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(RegularEntryPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(RegularEntryPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the User relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('User');

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
			$this->addJoinObject($join, 'User');
		}

		return $this;
	}

	/**
	 * Use the User relation User object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'User', 'UserQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     RegularEntry $regularEntry Object to remove from the list of results
	 *
	 * @return    RegularEntryQuery The current query, for fluid interface
	 */
	public function prune($regularEntry = null)
	{
		if ($regularEntry) {
			$this->addUsingAlias(RegularEntryPeer::ID, $regularEntry->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseRegularEntryQuery