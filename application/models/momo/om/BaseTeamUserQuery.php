<?php


/**
 * Base class that represents a query for the 'teams_users' table.
 *
 * 
 *
 * @method     TeamUserQuery orderByTeamId($order = Criteria::ASC) Order by the team_id column
 * @method     TeamUserQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     TeamUserQuery orderByPrimary($order = Criteria::ASC) Order by the primary column
 * @method     TeamUserQuery orderBySecondary($order = Criteria::ASC) Order by the secondary column
 * @method     TeamUserQuery orderByLeader($order = Criteria::ASC) Order by the leader column
 *
 * @method     TeamUserQuery groupByTeamId() Group by the team_id column
 * @method     TeamUserQuery groupByUserId() Group by the user_id column
 * @method     TeamUserQuery groupByPrimary() Group by the primary column
 * @method     TeamUserQuery groupBySecondary() Group by the secondary column
 * @method     TeamUserQuery groupByLeader() Group by the leader column
 *
 * @method     TeamUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     TeamUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     TeamUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     TeamUserQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     TeamUserQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     TeamUserQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     TeamUserQuery leftJoinTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the Team relation
 * @method     TeamUserQuery rightJoinTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Team relation
 * @method     TeamUserQuery innerJoinTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the Team relation
 *
 * @method     TeamUser findOne(PropelPDO $con = null) Return the first TeamUser matching the query
 * @method     TeamUser findOneOrCreate(PropelPDO $con = null) Return the first TeamUser matching the query, or a new TeamUser object populated from the query conditions when no match is found
 *
 * @method     TeamUser findOneByTeamId(int $team_id) Return the first TeamUser filtered by the team_id column
 * @method     TeamUser findOneByUserId(int $user_id) Return the first TeamUser filtered by the user_id column
 * @method     TeamUser findOneByPrimary(boolean $primary) Return the first TeamUser filtered by the primary column
 * @method     TeamUser findOneBySecondary(boolean $secondary) Return the first TeamUser filtered by the secondary column
 * @method     TeamUser findOneByLeader(boolean $leader) Return the first TeamUser filtered by the leader column
 *
 * @method     array findByTeamId(int $team_id) Return TeamUser objects filtered by the team_id column
 * @method     array findByUserId(int $user_id) Return TeamUser objects filtered by the user_id column
 * @method     array findByPrimary(boolean $primary) Return TeamUser objects filtered by the primary column
 * @method     array findBySecondary(boolean $secondary) Return TeamUser objects filtered by the secondary column
 * @method     array findByLeader(boolean $leader) Return TeamUser objects filtered by the leader column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseTeamUserQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseTeamUserQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'TeamUser', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new TeamUserQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    TeamUserQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof TeamUserQuery) {
			return $criteria;
		}
		$query = new TeamUserQuery();
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
	 * $obj = $c->findPk(array(12, 34), $con);
	 * </code>
	 *
	 * @param     array[$team_id, $user_id] $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    TeamUser|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = TeamUserPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(TeamUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    TeamUser A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `TEAM_ID`, `USER_ID`, `PRIMARY`, `SECONDARY`, `LEADER` FROM `teams_users` WHERE `TEAM_ID` = :p0 AND `USER_ID` = :p1';
		try {
			$stmt = $con->prepare($sql);			
			$stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);			
			$stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new TeamUser();
			$obj->hydrate($row);
			TeamUserPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
	 * @return    TeamUser|array|mixed the result, formatted by the current formatter
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
	 * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(TeamUserPeer::TEAM_ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(TeamUserPeer::USER_ID, $key[1], Criteria::EQUAL);

		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		if (empty($keys)) {
			return $this->add(null, '1<>1', Criteria::CUSTOM);
		}
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(TeamUserPeer::TEAM_ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(TeamUserPeer::USER_ID, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}

		return $this;
	}

	/**
	 * Filter the query on the team_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByTeamId(1234); // WHERE team_id = 1234
	 * $query->filterByTeamId(array(12, 34)); // WHERE team_id IN (12, 34)
	 * $query->filterByTeamId(array('min' => 12)); // WHERE team_id > 12
	 * </code>
	 *
	 * @see       filterByTeam()
	 *
	 * @param     mixed $teamId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByTeamId($teamId = null, $comparison = null)
	{
		if (is_array($teamId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(TeamUserPeer::TEAM_ID, $teamId, $comparison);
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
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(TeamUserPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the primary column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPrimary(true); // WHERE primary = true
	 * $query->filterByPrimary('yes'); // WHERE primary = true
	 * </code>
	 *
	 * @param     boolean|string $primary The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByPrimary($primary = null, $comparison = null)
	{
		if (is_string($primary)) {
			$primary = in_array(strtolower($primary), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(TeamUserPeer::PRIMARY, $primary, $comparison);
	}

	/**
	 * Filter the query on the secondary column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterBySecondary(true); // WHERE secondary = true
	 * $query->filterBySecondary('yes'); // WHERE secondary = true
	 * </code>
	 *
	 * @param     boolean|string $secondary The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterBySecondary($secondary = null, $comparison = null)
	{
		if (is_string($secondary)) {
			$secondary = in_array(strtolower($secondary), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(TeamUserPeer::SECONDARY, $secondary, $comparison);
	}

	/**
	 * Filter the query on the leader column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLeader(true); // WHERE leader = true
	 * $query->filterByLeader('yes'); // WHERE leader = true
	 * </code>
	 *
	 * @param     boolean|string $leader The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByLeader($leader = null, $comparison = null)
	{
		if (is_string($leader)) {
			$leader = in_array(strtolower($leader), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(TeamUserPeer::LEADER, $leader, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(TeamUserPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(TeamUserPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    TeamUserQuery The current query, for fluid interface
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
	 * Filter the query by a related Team object
	 *
	 * @param     Team|PropelCollection $team The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function filterByTeam($team, $comparison = null)
	{
		if ($team instanceof Team) {
			return $this
				->addUsingAlias(TeamUserPeer::TEAM_ID, $team->getId(), $comparison);
		} elseif ($team instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(TeamUserPeer::TEAM_ID, $team->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByTeam() only accepts arguments of type Team or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Team relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function joinTeam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Team');

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
			$this->addJoinObject($join, 'Team');
		}

		return $this;
	}

	/**
	 * Use the Team relation Team object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery A secondary query class using the current class as primary query
	 */
	public function useTeamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Team', 'TeamQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     TeamUser $teamUser Object to remove from the list of results
	 *
	 * @return    TeamUserQuery The current query, for fluid interface
	 */
	public function prune($teamUser = null)
	{
		if ($teamUser) {
			$this->addCond('pruneCond0', $this->getAliasedColName(TeamUserPeer::TEAM_ID), $teamUser->getTeamId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(TeamUserPeer::USER_ID), $teamUser->getUserId(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
		}

		return $this;
	}

} // BaseTeamUserQuery