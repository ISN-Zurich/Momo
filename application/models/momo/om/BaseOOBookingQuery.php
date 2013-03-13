<?php


/**
 * Base class that represents a query for the 'oobookings' table.
 *
 * 
 *
 * @method     OOBookingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     OOBookingQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     OOBookingQuery orderByOobookingtypeId($order = Criteria::ASC) Order by the oobookingtype_id column
 * @method     OOBookingQuery orderByAutoassignworktimecredit($order = Criteria::ASC) Order by the autoAssignWorktimeCredit column
 *
 * @method     OOBookingQuery groupById() Group by the id column
 * @method     OOBookingQuery groupByUserId() Group by the user_id column
 * @method     OOBookingQuery groupByOobookingtypeId() Group by the oobookingtype_id column
 * @method     OOBookingQuery groupByAutoassignworktimecredit() Group by the autoAssignWorktimeCredit column
 *
 * @method     OOBookingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     OOBookingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     OOBookingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     OOBookingQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     OOBookingQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     OOBookingQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     OOBookingQuery leftJoinOOBookingType($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOBookingType relation
 * @method     OOBookingQuery rightJoinOOBookingType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOBookingType relation
 * @method     OOBookingQuery innerJoinOOBookingType($relationAlias = null) Adds a INNER JOIN clause to the query using the OOBookingType relation
 *
 * @method     OOBookingQuery leftJoinOOEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOEntry relation
 * @method     OOBookingQuery rightJoinOOEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOEntry relation
 * @method     OOBookingQuery innerJoinOOEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the OOEntry relation
 *
 * @method     OOBookingQuery leftJoinOORequest($relationAlias = null) Adds a LEFT JOIN clause to the query using the OORequest relation
 * @method     OOBookingQuery rightJoinOORequest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OORequest relation
 * @method     OOBookingQuery innerJoinOORequest($relationAlias = null) Adds a INNER JOIN clause to the query using the OORequest relation
 *
 * @method     OOBooking findOne(PropelPDO $con = null) Return the first OOBooking matching the query
 * @method     OOBooking findOneOrCreate(PropelPDO $con = null) Return the first OOBooking matching the query, or a new OOBooking object populated from the query conditions when no match is found
 *
 * @method     OOBooking findOneById(int $id) Return the first OOBooking filtered by the id column
 * @method     OOBooking findOneByUserId(int $user_id) Return the first OOBooking filtered by the user_id column
 * @method     OOBooking findOneByOobookingtypeId(int $oobookingtype_id) Return the first OOBooking filtered by the oobookingtype_id column
 * @method     OOBooking findOneByAutoassignworktimecredit(boolean $autoAssignWorktimeCredit) Return the first OOBooking filtered by the autoAssignWorktimeCredit column
 *
 * @method     array findById(int $id) Return OOBooking objects filtered by the id column
 * @method     array findByUserId(int $user_id) Return OOBooking objects filtered by the user_id column
 * @method     array findByOobookingtypeId(int $oobookingtype_id) Return OOBooking objects filtered by the oobookingtype_id column
 * @method     array findByAutoassignworktimecredit(boolean $autoAssignWorktimeCredit) Return OOBooking objects filtered by the autoAssignWorktimeCredit column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseOOBookingQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseOOBookingQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'OOBooking', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new OOBookingQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    OOBookingQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof OOBookingQuery) {
			return $criteria;
		}
		$query = new OOBookingQuery();
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
	 * @return    OOBooking|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = OOBookingPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(OOBookingPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    OOBooking A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `USER_ID`, `OOBOOKINGTYPE_ID`, `AUTOASSIGNWORKTIMECREDIT` FROM `oobookings` WHERE `ID` = :p0';
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
			$obj = new OOBooking();
			$obj->hydrate($row);
			OOBookingPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    OOBooking|array|mixed the result, formatted by the current formatter
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
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(OOBookingPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(OOBookingPeer::ID, $keys, Criteria::IN);
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
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(OOBookingPeer::ID, $id, $comparison);
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
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(OOBookingPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(OOBookingPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(OOBookingPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the oobookingtype_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOobookingtypeId(1234); // WHERE oobookingtype_id = 1234
	 * $query->filterByOobookingtypeId(array(12, 34)); // WHERE oobookingtype_id IN (12, 34)
	 * $query->filterByOobookingtypeId(array('min' => 12)); // WHERE oobookingtype_id > 12
	 * </code>
	 *
	 * @see       filterByOOBookingType()
	 *
	 * @param     mixed $oobookingtypeId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByOobookingtypeId($oobookingtypeId = null, $comparison = null)
	{
		if (is_array($oobookingtypeId)) {
			$useMinMax = false;
			if (isset($oobookingtypeId['min'])) {
				$this->addUsingAlias(OOBookingPeer::OOBOOKINGTYPE_ID, $oobookingtypeId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($oobookingtypeId['max'])) {
				$this->addUsingAlias(OOBookingPeer::OOBOOKINGTYPE_ID, $oobookingtypeId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(OOBookingPeer::OOBOOKINGTYPE_ID, $oobookingtypeId, $comparison);
	}

	/**
	 * Filter the query on the autoAssignWorktimeCredit column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAutoassignworktimecredit(true); // WHERE autoAssignWorktimeCredit = true
	 * $query->filterByAutoassignworktimecredit('yes'); // WHERE autoAssignWorktimeCredit = true
	 * </code>
	 *
	 * @param     boolean|string $autoassignworktimecredit The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByAutoassignworktimecredit($autoassignworktimecredit = null, $comparison = null)
	{
		if (is_string($autoassignworktimecredit)) {
			$autoAssignWorktimeCredit = in_array(strtolower($autoassignworktimecredit), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(OOBookingPeer::AUTOASSIGNWORKTIMECREDIT, $autoassignworktimecredit, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(OOBookingPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOBookingPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    OOBookingQuery The current query, for fluid interface
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
	 * Filter the query by a related OOBookingType object
	 *
	 * @param     OOBookingType|PropelCollection $oOBookingType The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByOOBookingType($oOBookingType, $comparison = null)
	{
		if ($oOBookingType instanceof OOBookingType) {
			return $this
				->addUsingAlias(OOBookingPeer::OOBOOKINGTYPE_ID, $oOBookingType->getId(), $comparison);
		} elseif ($oOBookingType instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OOBookingPeer::OOBOOKINGTYPE_ID, $oOBookingType->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByOOBookingType() only accepts arguments of type OOBookingType or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the OOBookingType relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function joinOOBookingType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('OOBookingType');

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
			$this->addJoinObject($join, 'OOBookingType');
		}

		return $this;
	}

	/**
	 * Use the OOBookingType relation OOBookingType object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOBookingTypeQuery A secondary query class using the current class as primary query
	 */
	public function useOOBookingTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinOOBookingType($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'OOBookingType', 'OOBookingTypeQuery');
	}

	/**
	 * Filter the query by a related OOEntry object
	 *
	 * @param     OOEntry $oOEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByOOEntry($oOEntry, $comparison = null)
	{
		if ($oOEntry instanceof OOEntry) {
			return $this
				->addUsingAlias(OOBookingPeer::ID, $oOEntry->getOobookingId(), $comparison);
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
	 * @return    OOBookingQuery The current query, for fluid interface
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
	 * Filter the query by a related OORequest object
	 *
	 * @param     OORequest $oORequest  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function filterByOORequest($oORequest, $comparison = null)
	{
		if ($oORequest instanceof OORequest) {
			return $this
				->addUsingAlias(OOBookingPeer::ID, $oORequest->getId(), $comparison);
		} elseif ($oORequest instanceof PropelCollection) {
			return $this
				->useOORequestQuery()
				->filterByPrimaryKeys($oORequest->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByOORequest() only accepts arguments of type OORequest or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the OORequest relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function joinOORequest($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('OORequest');

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
			$this->addJoinObject($join, 'OORequest');
		}

		return $this;
	}

	/**
	 * Use the OORequest relation OORequest object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OORequestQuery A secondary query class using the current class as primary query
	 */
	public function useOORequestQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinOORequest($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'OORequest', 'OORequestQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     OOBooking $oOBooking Object to remove from the list of results
	 *
	 * @return    OOBookingQuery The current query, for fluid interface
	 */
	public function prune($oOBooking = null)
	{
		if ($oOBooking) {
			$this->addUsingAlias(OOBookingPeer::ID, $oOBooking->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseOOBookingQuery