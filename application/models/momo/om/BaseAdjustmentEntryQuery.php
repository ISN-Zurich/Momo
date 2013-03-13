<?php


/**
 * Base class that represents a query for the 'adjustmententries' table.
 *
 * 
 *
 * @method     AdjustmentEntryQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     AdjustmentEntryQuery orderByCreator($order = Criteria::ASC) Order by the creator column
 * @method     AdjustmentEntryQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method     AdjustmentEntryQuery orderByReason($order = Criteria::ASC) Order by the reason column
 * @method     AdjustmentEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     AdjustmentEntryQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method     AdjustmentEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     AdjustmentEntryQuery groupByType() Group by the type column
 * @method     AdjustmentEntryQuery groupByCreator() Group by the creator column
 * @method     AdjustmentEntryQuery groupByValue() Group by the value column
 * @method     AdjustmentEntryQuery groupByReason() Group by the reason column
 * @method     AdjustmentEntryQuery groupById() Group by the id column
 * @method     AdjustmentEntryQuery groupByDayId() Group by the day_id column
 * @method     AdjustmentEntryQuery groupByUserId() Group by the user_id column
 *
 * @method     AdjustmentEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     AdjustmentEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     AdjustmentEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     AdjustmentEntryQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     AdjustmentEntryQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     AdjustmentEntryQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     AdjustmentEntryQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     AdjustmentEntryQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     AdjustmentEntryQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     AdjustmentEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     AdjustmentEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     AdjustmentEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     AdjustmentEntry findOne(PropelPDO $con = null) Return the first AdjustmentEntry matching the query
 * @method     AdjustmentEntry findOneOrCreate(PropelPDO $con = null) Return the first AdjustmentEntry matching the query, or a new AdjustmentEntry object populated from the query conditions when no match is found
 *
 * @method     AdjustmentEntry findOneByType(string $type) Return the first AdjustmentEntry filtered by the type column
 * @method     AdjustmentEntry findOneByCreator(string $creator) Return the first AdjustmentEntry filtered by the creator column
 * @method     AdjustmentEntry findOneByValue(double $value) Return the first AdjustmentEntry filtered by the value column
 * @method     AdjustmentEntry findOneByReason(string $reason) Return the first AdjustmentEntry filtered by the reason column
 * @method     AdjustmentEntry findOneById(int $id) Return the first AdjustmentEntry filtered by the id column
 * @method     AdjustmentEntry findOneByDayId(int $day_id) Return the first AdjustmentEntry filtered by the day_id column
 * @method     AdjustmentEntry findOneByUserId(int $user_id) Return the first AdjustmentEntry filtered by the user_id column
 *
 * @method     array findByType(string $type) Return AdjustmentEntry objects filtered by the type column
 * @method     array findByCreator(string $creator) Return AdjustmentEntry objects filtered by the creator column
 * @method     array findByValue(double $value) Return AdjustmentEntry objects filtered by the value column
 * @method     array findByReason(string $reason) Return AdjustmentEntry objects filtered by the reason column
 * @method     array findById(int $id) Return AdjustmentEntry objects filtered by the id column
 * @method     array findByDayId(int $day_id) Return AdjustmentEntry objects filtered by the day_id column
 * @method     array findByUserId(int $user_id) Return AdjustmentEntry objects filtered by the user_id column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseAdjustmentEntryQuery extends EntryQuery
{
	
	/**
	 * Initializes internal state of BaseAdjustmentEntryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'AdjustmentEntry', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new AdjustmentEntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    AdjustmentEntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof AdjustmentEntryQuery) {
			return $criteria;
		}
		$query = new AdjustmentEntryQuery();
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
	 * @return    AdjustmentEntry|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = AdjustmentEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(AdjustmentEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    AdjustmentEntry A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `TYPE`, `CREATOR`, `VALUE`, `REASON`, `ID`, `DAY_ID`, `USER_ID` FROM `adjustmententries` WHERE `ID` = :p0';
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
			$obj = new AdjustmentEntry();
			$obj->hydrate($row);
			AdjustmentEntryPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    AdjustmentEntry|array|mixed the result, formatted by the current formatter
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(AdjustmentEntryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(AdjustmentEntryPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the type column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
	 * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $type The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByType($type = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($type)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $type)) {
				$type = str_replace('*', '%', $type);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::TYPE, $type, $comparison);
	}

	/**
	 * Filter the query on the creator column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByCreator('fooValue');   // WHERE creator = 'fooValue'
	 * $query->filterByCreator('%fooValue%'); // WHERE creator LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $creator The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByCreator($creator = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($creator)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $creator)) {
				$creator = str_replace('*', '%', $creator);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::CREATOR, $creator, $comparison);
	}

	/**
	 * Filter the query on the value column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByValue(1234); // WHERE value = 1234
	 * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
	 * $query->filterByValue(array('min' => 12)); // WHERE value > 12
	 * </code>
	 *
	 * @param     mixed $value The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByValue($value = null, $comparison = null)
	{
		if (is_array($value)) {
			$useMinMax = false;
			if (isset($value['min'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($value['max'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::VALUE, $value, $comparison);
	}

	/**
	 * Filter the query on the reason column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByReason('fooValue');   // WHERE reason = 'fooValue'
	 * $query->filterByReason('%fooValue%'); // WHERE reason LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $reason The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByReason($reason = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($reason)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $reason)) {
				$reason = str_replace('*', '%', $reason);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::REASON, $reason, $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::ID, $id, $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByDayId($dayId = null, $comparison = null)
	{
		if (is_array($dayId)) {
			$useMinMax = false;
			if (isset($dayId['min'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dayId['max'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::DAY_ID, $dayId, $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(AdjustmentEntryPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(AdjustmentEntryPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry|PropelCollection $entry The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(AdjustmentEntryPeer::ID, $entry->getId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(AdjustmentEntryPeer::ID, $entry->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(AdjustmentEntryPeer::DAY_ID, $day->getId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(AdjustmentEntryPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(AdjustmentEntryPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(AdjustmentEntryPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
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
	 * @param     AdjustmentEntry $adjustmentEntry Object to remove from the list of results
	 *
	 * @return    AdjustmentEntryQuery The current query, for fluid interface
	 */
	public function prune($adjustmentEntry = null)
	{
		if ($adjustmentEntry) {
			$this->addUsingAlias(AdjustmentEntryPeer::ID, $adjustmentEntry->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseAdjustmentEntryQuery