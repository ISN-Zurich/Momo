<?php


/**
 * Base class that represents a query for the 'teams_projects' table.
 *
 * 
 *
 * @method     TeamProjectQuery orderByTeamId($order = Criteria::ASC) Order by the team_id column
 * @method     TeamProjectQuery orderByProjectId($order = Criteria::ASC) Order by the project_id column
 *
 * @method     TeamProjectQuery groupByTeamId() Group by the team_id column
 * @method     TeamProjectQuery groupByProjectId() Group by the project_id column
 *
 * @method     TeamProjectQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     TeamProjectQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     TeamProjectQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     TeamProjectQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method     TeamProjectQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method     TeamProjectQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method     TeamProjectQuery leftJoinTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the Team relation
 * @method     TeamProjectQuery rightJoinTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Team relation
 * @method     TeamProjectQuery innerJoinTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the Team relation
 *
 * @method     TeamProject findOne(PropelPDO $con = null) Return the first TeamProject matching the query
 * @method     TeamProject findOneOrCreate(PropelPDO $con = null) Return the first TeamProject matching the query, or a new TeamProject object populated from the query conditions when no match is found
 *
 * @method     TeamProject findOneByTeamId(int $team_id) Return the first TeamProject filtered by the team_id column
 * @method     TeamProject findOneByProjectId(int $project_id) Return the first TeamProject filtered by the project_id column
 *
 * @method     array findByTeamId(int $team_id) Return TeamProject objects filtered by the team_id column
 * @method     array findByProjectId(int $project_id) Return TeamProject objects filtered by the project_id column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseTeamProjectQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseTeamProjectQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'TeamProject', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new TeamProjectQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    TeamProjectQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof TeamProjectQuery) {
			return $criteria;
		}
		$query = new TeamProjectQuery();
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
	 * @param     array[$team_id, $project_id] $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    TeamProject|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = TeamProjectPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(TeamProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    TeamProject A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `TEAM_ID`, `PROJECT_ID` FROM `teams_projects` WHERE `TEAM_ID` = :p0 AND `PROJECT_ID` = :p1';
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
			$obj = new TeamProject();
			$obj->hydrate($row);
			TeamProjectPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
	 * @return    TeamProject|array|mixed the result, formatted by the current formatter
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
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(TeamProjectPeer::TEAM_ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(TeamProjectPeer::PROJECT_ID, $key[1], Criteria::EQUAL);

		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		if (empty($keys)) {
			return $this->add(null, '1<>1', Criteria::CUSTOM);
		}
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(TeamProjectPeer::TEAM_ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(TeamProjectPeer::PROJECT_ID, $key[1], Criteria::EQUAL);
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
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByTeamId($teamId = null, $comparison = null)
	{
		if (is_array($teamId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(TeamProjectPeer::TEAM_ID, $teamId, $comparison);
	}

	/**
	 * Filter the query on the project_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByProjectId(1234); // WHERE project_id = 1234
	 * $query->filterByProjectId(array(12, 34)); // WHERE project_id IN (12, 34)
	 * $query->filterByProjectId(array('min' => 12)); // WHERE project_id > 12
	 * </code>
	 *
	 * @see       filterByProject()
	 *
	 * @param     mixed $projectId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByProjectId($projectId = null, $comparison = null)
	{
		if (is_array($projectId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(TeamProjectPeer::PROJECT_ID, $projectId, $comparison);
	}

	/**
	 * Filter the query by a related Project object
	 *
	 * @param     Project|PropelCollection $project The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByProject($project, $comparison = null)
	{
		if ($project instanceof Project) {
			return $this
				->addUsingAlias(TeamProjectPeer::PROJECT_ID, $project->getId(), $comparison);
		} elseif ($project instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(TeamProjectPeer::PROJECT_ID, $project->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByProject() only accepts arguments of type Project or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Project relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function joinProject($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Project');

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
			$this->addJoinObject($join, 'Project');
		}

		return $this;
	}

	/**
	 * Use the Project relation Project object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ProjectQuery A secondary query class using the current class as primary query
	 */
	public function useProjectQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinProject($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Project', 'ProjectQuery');
	}

	/**
	 * Filter the query by a related Team object
	 *
	 * @param     Team|PropelCollection $team The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function filterByTeam($team, $comparison = null)
	{
		if ($team instanceof Team) {
			return $this
				->addUsingAlias(TeamProjectPeer::TEAM_ID, $team->getId(), $comparison);
		} elseif ($team instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(TeamProjectPeer::TEAM_ID, $team->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    TeamProjectQuery The current query, for fluid interface
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
	 * @param     TeamProject $teamProject Object to remove from the list of results
	 *
	 * @return    TeamProjectQuery The current query, for fluid interface
	 */
	public function prune($teamProject = null)
	{
		if ($teamProject) {
			$this->addCond('pruneCond0', $this->getAliasedColName(TeamProjectPeer::TEAM_ID), $teamProject->getTeamId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(TeamProjectPeer::PROJECT_ID), $teamProject->getProjectId(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
		}

		return $this;
	}

} // BaseTeamProjectQuery