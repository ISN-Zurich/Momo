<?php


/**
 * Base class that represents a query for the 'projects' table.
 *
 * 
 *
 * @method     ProjectQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ProjectQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ProjectQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 * @method     ProjectQuery orderByArchived($order = Criteria::ASC) Order by the archived column
 *
 * @method     ProjectQuery groupById() Group by the id column
 * @method     ProjectQuery groupByName() Group by the name column
 * @method     ProjectQuery groupByEnabled() Group by the enabled column
 * @method     ProjectQuery groupByArchived() Group by the archived column
 *
 * @method     ProjectQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ProjectQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ProjectQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ProjectQuery leftJoinTeamProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamProject relation
 * @method     ProjectQuery rightJoinTeamProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamProject relation
 * @method     ProjectQuery innerJoinTeamProject($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamProject relation
 *
 * @method     ProjectQuery leftJoinUserProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserProject relation
 * @method     ProjectQuery rightJoinUserProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserProject relation
 * @method     ProjectQuery innerJoinUserProject($relationAlias = null) Adds a INNER JOIN clause to the query using the UserProject relation
 *
 * @method     ProjectQuery leftJoinProjectEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectEntry relation
 * @method     ProjectQuery rightJoinProjectEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectEntry relation
 * @method     ProjectQuery innerJoinProjectEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectEntry relation
 *
 * @method     Project findOne(PropelPDO $con = null) Return the first Project matching the query
 * @method     Project findOneOrCreate(PropelPDO $con = null) Return the first Project matching the query, or a new Project object populated from the query conditions when no match is found
 *
 * @method     Project findOneById(int $id) Return the first Project filtered by the id column
 * @method     Project findOneByName(string $name) Return the first Project filtered by the name column
 * @method     Project findOneByEnabled(boolean $enabled) Return the first Project filtered by the enabled column
 * @method     Project findOneByArchived(boolean $archived) Return the first Project filtered by the archived column
 *
 * @method     array findById(int $id) Return Project objects filtered by the id column
 * @method     array findByName(string $name) Return Project objects filtered by the name column
 * @method     array findByEnabled(boolean $enabled) Return Project objects filtered by the enabled column
 * @method     array findByArchived(boolean $archived) Return Project objects filtered by the archived column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseProjectQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseProjectQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Project', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new ProjectQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    ProjectQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof ProjectQuery) {
			return $criteria;
		}
		$query = new ProjectQuery();
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
	 * @return    Project|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = ProjectPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Project A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `NAME`, `ENABLED`, `ARCHIVED` FROM `projects` WHERE `ID` = :p0';
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
			$obj = new Project();
			$obj->hydrate($row);
			ProjectPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Project|array|mixed the result, formatted by the current formatter
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
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(ProjectPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(ProjectPeer::ID, $keys, Criteria::IN);
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
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(ProjectPeer::ID, $id, $comparison);
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
	 * @return    ProjectQuery The current query, for fluid interface
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
		return $this->addUsingAlias(ProjectPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the enabled column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByEnabled(true); // WHERE enabled = true
	 * $query->filterByEnabled('yes'); // WHERE enabled = true
	 * </code>
	 *
	 * @param     boolean|string $enabled The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByEnabled($enabled = null, $comparison = null)
	{
		if (is_string($enabled)) {
			$enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(ProjectPeer::ENABLED, $enabled, $comparison);
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
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByArchived($archived = null, $comparison = null)
	{
		if (is_string($archived)) {
			$archived = in_array(strtolower($archived), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(ProjectPeer::ARCHIVED, $archived, $comparison);
	}

	/**
	 * Filter the query by a related TeamProject object
	 *
	 * @param     TeamProject $teamProject  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByTeamProject($teamProject, $comparison = null)
	{
		if ($teamProject instanceof TeamProject) {
			return $this
				->addUsingAlias(ProjectPeer::ID, $teamProject->getProjectId(), $comparison);
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
	 * @return    ProjectQuery The current query, for fluid interface
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
	 * Filter the query by a related UserProject object
	 *
	 * @param     UserProject $userProject  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByUserProject($userProject, $comparison = null)
	{
		if ($userProject instanceof UserProject) {
			return $this
				->addUsingAlias(ProjectPeer::ID, $userProject->getProjectId(), $comparison);
		} elseif ($userProject instanceof PropelCollection) {
			return $this
				->useUserProjectQuery()
				->filterByPrimaryKeys($userProject->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByUserProject() only accepts arguments of type UserProject or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the UserProject relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function joinUserProject($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UserProject');

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
			$this->addJoinObject($join, 'UserProject');
		}

		return $this;
	}

	/**
	 * Use the UserProject relation UserProject object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserProjectQuery A secondary query class using the current class as primary query
	 */
	public function useUserProjectQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinUserProject($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserProject', 'UserProjectQuery');
	}

	/**
	 * Filter the query by a related ProjectEntry object
	 *
	 * @param     ProjectEntry $projectEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByProjectEntry($projectEntry, $comparison = null)
	{
		if ($projectEntry instanceof ProjectEntry) {
			return $this
				->addUsingAlias(ProjectPeer::ID, $projectEntry->getProjectId(), $comparison);
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
	 * @return    ProjectQuery The current query, for fluid interface
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
	 * Filter the query by a related Team object
	 * using the teams_projects table as cross reference
	 *
	 * @param     Team $team the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByTeam($team, $comparison = Criteria::EQUAL)
	{
		return $this
			->useTeamProjectQuery()
			->filterByTeam($team, $comparison)
			->endUse();
	}

	/**
	 * Filter the query by a related User object
	 * using the users_projects table as cross reference
	 *
	 * @param     User $user the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = Criteria::EQUAL)
	{
		return $this
			->useUserProjectQuery()
			->filterByUser($user, $comparison)
			->endUse();
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Project $project Object to remove from the list of results
	 *
	 * @return    ProjectQuery The current query, for fluid interface
	 */
	public function prune($project = null)
	{
		if ($project) {
			$this->addUsingAlias(ProjectPeer::ID, $project->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseProjectQuery