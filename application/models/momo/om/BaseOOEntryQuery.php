<?php


/**
 * Base class that represents a query for the 'ooentries' table.
 *
 * 
 *
 * @method     OOEntryQuery orderByOobookingId($order = Criteria::ASC) Order by the oobooking_id column
 * @method     OOEntryQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     OOEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     OOEntryQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method     OOEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     OOEntryQuery groupByOobookingId() Group by the oobooking_id column
 * @method     OOEntryQuery groupByType() Group by the type column
 * @method     OOEntryQuery groupById() Group by the id column
 * @method     OOEntryQuery groupByDayId() Group by the day_id column
 * @method     OOEntryQuery groupByUserId() Group by the user_id column
 *
 * @method     OOEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     OOEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     OOEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     OOEntryQuery leftJoinOOBooking($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOBooking relation
 * @method     OOEntryQuery rightJoinOOBooking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOBooking relation
 * @method     OOEntryQuery innerJoinOOBooking($relationAlias = null) Adds a INNER JOIN clause to the query using the OOBooking relation
 *
 * @method     OOEntryQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     OOEntryQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     OOEntryQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     OOEntryQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     OOEntryQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     OOEntryQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     OOEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     OOEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     OOEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     OOEntry findOne(PropelPDO $con = null) Return the first OOEntry matching the query
 * @method     OOEntry findOneOrCreate(PropelPDO $con = null) Return the first OOEntry matching the query, or a new OOEntry object populated from the query conditions when no match is found
 *
 * @method     OOEntry findOneByOobookingId(int $oobooking_id) Return the first OOEntry filtered by the oobooking_id column
 * @method     OOEntry findOneByType(string $type) Return the first OOEntry filtered by the type column
 * @method     OOEntry findOneById(int $id) Return the first OOEntry filtered by the id column
 * @method     OOEntry findOneByDayId(int $day_id) Return the first OOEntry filtered by the day_id column
 * @method     OOEntry findOneByUserId(int $user_id) Return the first OOEntry filtered by the user_id column
 *
 * @method     array findByOobookingId(int $oobooking_id) Return OOEntry objects filtered by the oobooking_id column
 * @method     array findByType(string $type) Return OOEntry objects filtered by the type column
 * @method     array findById(int $id) Return OOEntry objects filtered by the id column
 * @method     array findByDayId(int $day_id) Return OOEntry objects filtered by the day_id column
 * @method     array findByUserId(int $user_id) Return OOEntry objects filtered by the user_id column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseOOEntryQuery extends EntryQuery
{
	
	/**
	 * Initializes internal state of BaseOOEntryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'OOEntry', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new OOEntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    OOEntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof OOEntryQuery) {
			return $criteria;
		}
		$query = new OOEntryQuery();
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
	 * @return    OOEntry|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = OOEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(OOEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    OOEntry A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `OOBOOKING_ID`, `TYPE`, `ID`, `DAY_ID`, `USER_ID` FROM `ooentries` WHERE `ID` = :p0';
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
			$obj = new OOEntry();
			$obj->hydrate($row);
			OOEntryPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    OOEntry|array|mixed the result, formatted by the current formatter
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(OOEntryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(OOEntryPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the oobooking_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOobookingId(1234); // WHERE oobooking_id = 1234
	 * $query->filterByOobookingId(array(12, 34)); // WHERE oobooking_id IN (12, 34)
	 * $query->filterByOobookingId(array('min' => 12)); // WHERE oobooking_id > 12
	 * </code>
	 *
	 * @see       filterByOOBooking()
	 *
	 * @param     mixed $oobookingId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByOobookingId($oobookingId = null, $comparison = null)
	{
		if (is_array($oobookingId)) {
			$useMinMax = false;
			if (isset($oobookingId['min'])) {
				$this->addUsingAlias(OOEntryPeer::OOBOOKING_ID, $oobookingId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($oobookingId['max'])) {
				$this->addUsingAlias(OOEntryPeer::OOBOOKING_ID, $oobookingId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(OOEntryPeer::OOBOOKING_ID, $oobookingId, $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
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
		return $this->addUsingAlias(OOEntryPeer::TYPE, $type, $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(OOEntryPeer::ID, $id, $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByDayId($dayId = null, $comparison = null)
	{
		if (is_array($dayId)) {
			$useMinMax = false;
			if (isset($dayId['min'])) {
				$this->addUsingAlias(OOEntryPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dayId['max'])) {
				$this->addUsingAlias(OOEntryPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(OOEntryPeer::DAY_ID, $dayId, $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(OOEntryPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(OOEntryPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(OOEntryPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query by a related OOBooking object
	 *
	 * @param     OOBooking|PropelCollection $oOBooking The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByOOBooking($oOBooking, $comparison = null)
	{
		if ($oOBooking instanceof OOBooking) {
			return $this
				->addUsingAlias(OOEntryPeer::OOBOOKING_ID, $oOBooking->getId(), $comparison);
		} elseif ($oOBooking instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOEntryPeer::OOBOOKING_ID, $oOBooking->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByOOBooking() only accepts arguments of type OOBooking or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the OOBooking relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function joinOOBooking($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('OOBooking');

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
			$this->addJoinObject($join, 'OOBooking');
		}

		return $this;
	}

	/**
	 * Use the OOBooking relation OOBooking object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOBookingQuery A secondary query class using the current class as primary query
	 */
	public function useOOBookingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinOOBooking($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'OOBooking', 'OOBookingQuery');
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry|PropelCollection $entry The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(OOEntryPeer::ID, $entry->getId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOEntryPeer::ID, $entry->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(OOEntryPeer::DAY_ID, $day->getId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOEntryPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
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
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(OOEntryPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOEntryPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    OOEntryQuery The current query, for fluid interface
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
	 * @param     OOEntry $oOEntry Object to remove from the list of results
	 *
	 * @return    OOEntryQuery The current query, for fluid interface
	 */
	public function prune($oOEntry = null)
	{
		if ($oOEntry) {
			$this->addUsingAlias(OOEntryPeer::ID, $oOEntry->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseOOEntryQuery