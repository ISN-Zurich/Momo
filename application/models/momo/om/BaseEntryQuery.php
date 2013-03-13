<?php


/**
 * Base class that represents a query for the 'entries' table.
 *
 * 
 *
 * @method     EntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     EntryQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method     EntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     EntryQuery orderByDescendantClass($order = Criteria::ASC) Order by the descendant_class column
 *
 * @method     EntryQuery groupById() Group by the id column
 * @method     EntryQuery groupByDayId() Group by the day_id column
 * @method     EntryQuery groupByUserId() Group by the user_id column
 * @method     EntryQuery groupByDescendantClass() Group by the descendant_class column
 *
 * @method     EntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     EntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     EntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     EntryQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     EntryQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     EntryQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     EntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     EntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     EntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     EntryQuery leftJoinRegularEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegularEntry relation
 * @method     EntryQuery rightJoinRegularEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegularEntry relation
 * @method     EntryQuery innerJoinRegularEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the RegularEntry relation
 *
 * @method     EntryQuery leftJoinProjectEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectEntry relation
 * @method     EntryQuery rightJoinProjectEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectEntry relation
 * @method     EntryQuery innerJoinProjectEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectEntry relation
 *
 * @method     EntryQuery leftJoinOOEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOEntry relation
 * @method     EntryQuery rightJoinOOEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOEntry relation
 * @method     EntryQuery innerJoinOOEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the OOEntry relation
 *
 * @method     EntryQuery leftJoinAdjustmentEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdjustmentEntry relation
 * @method     EntryQuery rightJoinAdjustmentEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdjustmentEntry relation
 * @method     EntryQuery innerJoinAdjustmentEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the AdjustmentEntry relation
 *
 * @method     Entry findOne(PropelPDO $con = null) Return the first Entry matching the query
 * @method     Entry findOneOrCreate(PropelPDO $con = null) Return the first Entry matching the query, or a new Entry object populated from the query conditions when no match is found
 *
 * @method     Entry findOneById(int $id) Return the first Entry filtered by the id column
 * @method     Entry findOneByDayId(int $day_id) Return the first Entry filtered by the day_id column
 * @method     Entry findOneByUserId(int $user_id) Return the first Entry filtered by the user_id column
 * @method     Entry findOneByDescendantClass(string $descendant_class) Return the first Entry filtered by the descendant_class column
 *
 * @method     array findById(int $id) Return Entry objects filtered by the id column
 * @method     array findByDayId(int $day_id) Return Entry objects filtered by the day_id column
 * @method     array findByUserId(int $user_id) Return Entry objects filtered by the user_id column
 * @method     array findByDescendantClass(string $descendant_class) Return Entry objects filtered by the descendant_class column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseEntryQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseEntryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Entry', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new EntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    EntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof EntryQuery) {
			return $criteria;
		}
		$query = new EntryQuery();
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
	 * @return    Entry|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = EntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(EntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Entry A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `DAY_ID`, `USER_ID`, `DESCENDANT_CLASS` FROM `entries` WHERE `ID` = :p0';
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
			$obj = new Entry();
			$obj->hydrate($row);
			EntryPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Entry|array|mixed the result, formatted by the current formatter
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(EntryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(EntryPeer::ID, $keys, Criteria::IN);
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(EntryPeer::ID, $id, $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByDayId($dayId = null, $comparison = null)
	{
		if (is_array($dayId)) {
			$useMinMax = false;
			if (isset($dayId['min'])) {
				$this->addUsingAlias(EntryPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dayId['max'])) {
				$this->addUsingAlias(EntryPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntryPeer::DAY_ID, $dayId, $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(EntryPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(EntryPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntryPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the descendant_class column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDescendantClass('fooValue');   // WHERE descendant_class = 'fooValue'
	 * $query->filterByDescendantClass('%fooValue%'); // WHERE descendant_class LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $descendantClass The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByDescendantClass($descendantClass = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($descendantClass)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $descendantClass)) {
				$descendantClass = str_replace('*', '%', $descendantClass);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(EntryPeer::DESCENDANT_CLASS, $descendantClass, $comparison);
	}

	/**
	 * Filter the query by a related Day object
	 *
	 * @param     Day|PropelCollection $day The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(EntryPeer::DAY_ID, $day->getId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(EntryPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(EntryPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(EntryPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * Filter the query by a related RegularEntry object
	 *
	 * @param     RegularEntry $regularEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByRegularEntry($regularEntry, $comparison = null)
	{
		if ($regularEntry instanceof RegularEntry) {
			return $this
				->addUsingAlias(EntryPeer::ID, $regularEntry->getId(), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByProjectEntry($projectEntry, $comparison = null)
	{
		if ($projectEntry instanceof ProjectEntry) {
			return $this
				->addUsingAlias(EntryPeer::ID, $projectEntry->getId(), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByOOEntry($oOEntry, $comparison = null)
	{
		if ($oOEntry instanceof OOEntry) {
			return $this
				->addUsingAlias(EntryPeer::ID, $oOEntry->getId(), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function filterByAdjustmentEntry($adjustmentEntry, $comparison = null)
	{
		if ($adjustmentEntry instanceof AdjustmentEntry) {
			return $this
				->addUsingAlias(EntryPeer::ID, $adjustmentEntry->getId(), $comparison);
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
	 * @return    EntryQuery The current query, for fluid interface
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
	 * @param     Entry $entry Object to remove from the list of results
	 *
	 * @return    EntryQuery The current query, for fluid interface
	 */
	public function prune($entry = null)
	{
		if ($entry) {
			$this->addUsingAlias(EntryPeer::ID, $entry->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseEntryQuery