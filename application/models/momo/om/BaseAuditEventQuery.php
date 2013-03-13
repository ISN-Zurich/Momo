<?php


/**
 * Base class that represents a query for the 'auditevents' table.
 *
 * 
 *
 * @method     AuditEventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     AuditEventQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     AuditEventQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method     AuditEventQuery orderBySourcekey($order = Criteria::ASC) Order by the sourcekey column
 * @method     AuditEventQuery orderByAction($order = Criteria::ASC) Order by the action column
 * @method     AuditEventQuery orderByDetails($order = Criteria::ASC) Order by the details column
 *
 * @method     AuditEventQuery groupById() Group by the id column
 * @method     AuditEventQuery groupByUserId() Group by the user_id column
 * @method     AuditEventQuery groupByTimestamp() Group by the timestamp column
 * @method     AuditEventQuery groupBySourcekey() Group by the sourcekey column
 * @method     AuditEventQuery groupByAction() Group by the action column
 * @method     AuditEventQuery groupByDetails() Group by the details column
 *
 * @method     AuditEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     AuditEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     AuditEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     AuditEventQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     AuditEventQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     AuditEventQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     AuditEvent findOne(PropelPDO $con = null) Return the first AuditEvent matching the query
 * @method     AuditEvent findOneOrCreate(PropelPDO $con = null) Return the first AuditEvent matching the query, or a new AuditEvent object populated from the query conditions when no match is found
 *
 * @method     AuditEvent findOneById(int $id) Return the first AuditEvent filtered by the id column
 * @method     AuditEvent findOneByUserId(int $user_id) Return the first AuditEvent filtered by the user_id column
 * @method     AuditEvent findOneByTimestamp(string $timestamp) Return the first AuditEvent filtered by the timestamp column
 * @method     AuditEvent findOneBySourcekey(string $sourcekey) Return the first AuditEvent filtered by the sourcekey column
 * @method     AuditEvent findOneByAction(string $action) Return the first AuditEvent filtered by the action column
 * @method     AuditEvent findOneByDetails(string $details) Return the first AuditEvent filtered by the details column
 *
 * @method     array findById(int $id) Return AuditEvent objects filtered by the id column
 * @method     array findByUserId(int $user_id) Return AuditEvent objects filtered by the user_id column
 * @method     array findByTimestamp(string $timestamp) Return AuditEvent objects filtered by the timestamp column
 * @method     array findBySourcekey(string $sourcekey) Return AuditEvent objects filtered by the sourcekey column
 * @method     array findByAction(string $action) Return AuditEvent objects filtered by the action column
 * @method     array findByDetails(string $details) Return AuditEvent objects filtered by the details column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseAuditEventQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseAuditEventQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'AuditEvent', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new AuditEventQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    AuditEventQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof AuditEventQuery) {
			return $criteria;
		}
		$query = new AuditEventQuery();
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
	 * @return    AuditEvent|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = AuditEventPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(AuditEventPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    AuditEvent A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `USER_ID`, `TIMESTAMP`, `SOURCEKEY`, `ACTION`, `DETAILS` FROM `auditevents` WHERE `ID` = :p0';
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
			$obj = new AuditEvent();
			$obj->hydrate($row);
			AuditEventPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    AuditEvent|array|mixed the result, formatted by the current formatter
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
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(AuditEventPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(AuditEventPeer::ID, $keys, Criteria::IN);
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
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(AuditEventPeer::ID, $id, $comparison);
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
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(AuditEventPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(AuditEventPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(AuditEventPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the timestamp column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByTimestamp('2011-03-14'); // WHERE timestamp = '2011-03-14'
	 * $query->filterByTimestamp('now'); // WHERE timestamp = '2011-03-14'
	 * $query->filterByTimestamp(array('max' => 'yesterday')); // WHERE timestamp > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $timestamp The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByTimestamp($timestamp = null, $comparison = null)
	{
		if (is_array($timestamp)) {
			$useMinMax = false;
			if (isset($timestamp['min'])) {
				$this->addUsingAlias(AuditEventPeer::TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($timestamp['max'])) {
				$this->addUsingAlias(AuditEventPeer::TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(AuditEventPeer::TIMESTAMP, $timestamp, $comparison);
	}

	/**
	 * Filter the query on the sourcekey column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterBySourcekey('fooValue');   // WHERE sourcekey = 'fooValue'
	 * $query->filterBySourcekey('%fooValue%'); // WHERE sourcekey LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $sourcekey The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterBySourcekey($sourcekey = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($sourcekey)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $sourcekey)) {
				$sourcekey = str_replace('*', '%', $sourcekey);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AuditEventPeer::SOURCEKEY, $sourcekey, $comparison);
	}

	/**
	 * Filter the query on the action column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAction('fooValue');   // WHERE action = 'fooValue'
	 * $query->filterByAction('%fooValue%'); // WHERE action LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $action The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByAction($action = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($action)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $action)) {
				$action = str_replace('*', '%', $action);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AuditEventPeer::ACTION, $action, $comparison);
	}

	/**
	 * Filter the query on the details column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDetails('fooValue');   // WHERE details = 'fooValue'
	 * $query->filterByDetails('%fooValue%'); // WHERE details LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $details The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByDetails($details = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($details)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $details)) {
				$details = str_replace('*', '%', $details);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(AuditEventPeer::DETAILS, $details, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(AuditEventPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(AuditEventPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    AuditEventQuery The current query, for fluid interface
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
	 * @param     AuditEvent $auditEvent Object to remove from the list of results
	 *
	 * @return    AuditEventQuery The current query, for fluid interface
	 */
	public function prune($auditEvent = null)
	{
		if ($auditEvent) {
			$this->addUsingAlias(AuditEventPeer::ID, $auditEvent->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseAuditEventQuery