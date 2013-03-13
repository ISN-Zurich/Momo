<?php


/**
 * Base class that represents a query for the 'teams' table.
 *
 * 
 *
 * @method     TeamQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     TeamQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     TeamQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     TeamQuery orderByArchived($order = Criteria::ASC) Order by the archived column
 *
 * @method     TeamQuery groupById() Group by the id column
 * @method     TeamQuery groupByParentId() Group by the parent_id column
 * @method     TeamQuery groupByName() Group by the name column
 * @method     TeamQuery groupByArchived() Group by the archived column
 *
 * @method     TeamQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     TeamQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     TeamQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     TeamQuery leftJoinTeamRelatedByParentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamRelatedByParentId relation
 * @method     TeamQuery rightJoinTeamRelatedByParentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamRelatedByParentId relation
 * @method     TeamQuery innerJoinTeamRelatedByParentId($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamRelatedByParentId relation
 *
 * @method     TeamQuery leftJoinTeamRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamRelatedById relation
 * @method     TeamQuery rightJoinTeamRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamRelatedById relation
 * @method     TeamQuery innerJoinTeamRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamRelatedById relation
 *
 * @method     TeamQuery leftJoinTeamUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamUser relation
 * @method     TeamQuery rightJoinTeamUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamUser relation
 * @method     TeamQuery innerJoinTeamUser($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamUser relation
 *
 * @method     TeamQuery leftJoinTeamProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamProject relation
 * @method     TeamQuery rightJoinTeamProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamProject relation
 * @method     TeamQuery innerJoinTeamProject($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamProject relation
 *
 * @method     TeamQuery leftJoinProjectEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectEntry relation
 * @method     TeamQuery rightJoinProjectEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectEntry relation
 * @method     TeamQuery innerJoinProjectEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectEntry relation
 *
 * @method     Team findOne(PropelPDO $con = null) Return the first Team matching the query
 * @method     Team findOneOrCreate(PropelPDO $con = null) Return the first Team matching the query, or a new Team object populated from the query conditions when no match is found
 *
 * @method     Team findOneById(int $id) Return the first Team filtered by the id column
 * @method     Team findOneByParentId(int $parent_id) Return the first Team filtered by the parent_id column
 * @method     Team findOneByName(string $name) Return the first Team filtered by the name column
 * @method     Team findOneByArchived(boolean $archived) Return the first Team filtered by the archived column
 *
 * @method     array findById(int $id) Return Team objects filtered by the id column
 * @method     array findByParentId(int $parent_id) Return Team objects filtered by the parent_id column
 * @method     array findByName(string $name) Return Team objects filtered by the name column
 * @method     array findByArchived(boolean $archived) Return Team objects filtered by the archived column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseTeamQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseTeamQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Team', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new TeamQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    TeamQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof TeamQuery) {
			return $criteria;
		}
		$query = new TeamQuery();
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
	 * @return    Team|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = TeamPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(TeamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Team A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `PARENT_ID`, `NAME`, `ARCHIVED` FROM `teams` WHERE `ID` = :p0';
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
			$obj = new Team();
			$obj->hydrate($row);
			TeamPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Team|array|mixed the result, formatted by the current formatter
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
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(TeamPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(TeamPeer::ID, $keys, Criteria::IN);
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
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(TeamPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the parent_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByParentId(1234); // WHERE parent_id = 1234
	 * $query->filterByParentId(array(12, 34)); // WHERE parent_id IN (12, 34)
	 * $query->filterByParentId(array('min' => 12)); // WHERE parent_id > 12
	 * </code>
	 *
	 * @see       filterByTeamRelatedByParentId()
	 *
	 * @param     mixed $parentId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByParentId($parentId = null, $comparison = null)
	{
		if (is_array($parentId)) {
			$useMinMax = false;
			if (isset($parentId['min'])) {
				$this->addUsingAlias(TeamPeer::PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($parentId['max'])) {
				$this->addUsingAlias(TeamPeer::PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(TeamPeer::PARENT_ID, $parentId, $comparison);
	}

	/**
	 * Filter the query on the name column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
	 * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $name The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($name)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $name)) {
				$name = str_replace('*', '%', $name);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(TeamPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the archived column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByArchived(true); // WHERE archived = true
	 * $query->filterByArchived('yes'); // WHERE archived = true
	 * </code>
	 *
	 * @param     boolean|string $archived The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByArchived($archived = null, $comparison = null)
	{
		if (is_string($archived)) {
			$archived = in_array(strtolower($archived), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(TeamPeer::ARCHIVED, $archived, $comparison);
	}

	/**
	 * Filter the query by a related Team object
	 *
	 * @param     Team|PropelCollection $team The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByTeamRelatedByParentId($team, $comparison = null)
	{
		if ($team instanceof Team) {
			return $this
				->addUsingAlias(TeamPeer::PARENT_ID, $team->getId(), $comparison);
		} elseif ($team instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(TeamPeer::PARENT_ID, $team->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByTeamRelatedByParentId() only accepts arguments of type Team or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the TeamRelatedByParentId relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function joinTeamRelatedByParentId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('TeamRelatedByParentId');

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
			$this->addJoinObject($join, 'TeamRelatedByParentId');
		}

		return $this;
	}

	/**
	 * Use the TeamRelatedByParentId relation Team object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery A secondary query class using the current class as primary query
	 */
	public function useTeamRelatedByParentIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinTeamRelatedByParentId($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'TeamRelatedByParentId', 'TeamQuery');
	}

	/**
	 * Filter the query by a related Team object
	 *
	 * @param     Team $team  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByTeamRelatedById($team, $comparison = null)
	{
		if ($team instanceof Team) {
			return $this
				->addUsingAlias(TeamPeer::ID, $team->getParentId(), $comparison);
		} elseif ($team instanceof PropelCollection) {
			return $this
				->useTeamRelatedByIdQuery()
				->filterByPrimaryKeys($team->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByTeamRelatedById() only accepts arguments of type Team or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the TeamRelatedById relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function joinTeamRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('TeamRelatedById');

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
			$this->addJoinObject($join, 'TeamRelatedById');
		}

		return $this;
	}

	/**
	 * Use the TeamRelatedById relation Team object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery A secondary query class using the current class as primary query
	 */
	public function useTeamRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinTeamRelatedById($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'TeamRelatedById', 'TeamQuery');
	}

	/**
	 * Filter the query by a related TeamUser object
	 *
	 * @param     TeamUser $teamUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByTeamUser($teamUser, $comparison = null)
	{
		if ($teamUser instanceof TeamUser) {
			return $this
				->addUsingAlias(TeamPeer::ID, $teamUser->getTeamId(), $comparison);
		} elseif ($teamUser instanceof PropelCollection) {
			return $this
				->useTeamUserQuery()
				->filterByPrimaryKeys($teamUser->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByTeamUser() only accepts arguments of type TeamUser or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the TeamUser relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function joinTeamUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('TeamUser');

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
			$this->addJoinObject($join, 'TeamUser');
		}

		return $this;
	}

	/**
	 * Use the TeamUser relation TeamUser object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamUserQuery A secondary query class using the current class as primary query
	 */
	public function useTeamUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinTeamUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'TeamUser', 'TeamUserQuery');
	}

	/**
	 * Filter the query by a related TeamProject object
	 *
	 * @param     TeamProject $teamProject  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByTeamProject($teamProject, $comparison = null)
	{
		if ($teamProject instanceof TeamProject) {
			return $this
				->addUsingAlias(TeamPeer::ID, $teamProject->getTeamId(), $comparison);
		} elseif ($teamProject instanceof PropelCollection) {
			return $this
				->useTeamProjectQuery()
				->filterByPrimaryKeys($teamProject->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByTeamProject() only accepts arguments of type TeamProject or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the TeamProject relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function joinTeamProject($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('TeamProject');

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
			$this->addJoinObject($join, 'TeamProject');
		}

		return $this;
	}

	/**
	 * Use the TeamProject relation TeamProject object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamProjectQuery A secondary query class using the current class as primary query
	 */
	public function useTeamProjectQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinTeamProject($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'TeamProject', 'TeamProjectQuery');
	}

	/**
	 * Filter the query by a related ProjectEntry object
	 *
	 * @param     ProjectEntry $projectEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByProjectEntry($projectEntry, $comparison = null)
	{
		if ($projectEntry instanceof ProjectEntry) {
			return $this
				->addUsingAlias(TeamPeer::ID, $projectEntry->getTeamId(), $comparison);
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
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function joinProjectEntry($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
	public function useProjectEntryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinProjectEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'ProjectEntry', 'ProjectEntryQuery');
	}

	/**
	 * Filter the query by a related User object
	 * using the teams_users table as cross reference
	 *
	 * @param     User $user the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = Criteria::EQUAL)
	{
		return $this
			->useTeamUserQuery()
			->filterByUser($user, $comparison)
			->endUse();
	}

	/**
	 * Filter the query by a related Project object
	 * using the teams_projects table as cross reference
	 *
	 * @param     Project $project the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function filterByProject($project, $comparison = Criteria::EQUAL)
	{
		return $this
			->useTeamProjectQuery()
			->filterByProject($project, $comparison)
			->endUse();
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Team $team Object to remove from the list of results
	 *
	 * @return    TeamQuery The current query, for fluid interface
	 */
	public function prune($team = null)
	{
		if ($team) {
			$this->addUsingAlias(TeamPeer::ID, $team->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseTeamQuery