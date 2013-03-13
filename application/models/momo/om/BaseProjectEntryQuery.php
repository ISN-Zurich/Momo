<?php


/**
 * Base class that represents a query for the 'projectentries' table.
 *
 * 
 *
 * @method     ProjectEntryQuery orderByProjectId($order = Criteria::ASC) Order by the project_id column
 * @method     ProjectEntryQuery orderByTeamId($order = Criteria::ASC) Order by the team_id column
 * @method     ProjectEntryQuery orderByTimeInterval($order = Criteria::ASC) Order by the time_interval column
 * @method     ProjectEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ProjectEntryQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method     ProjectEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     ProjectEntryQuery groupByProjectId() Group by the project_id column
 * @method     ProjectEntryQuery groupByTeamId() Group by the team_id column
 * @method     ProjectEntryQuery groupByTimeInterval() Group by the time_interval column
 * @method     ProjectEntryQuery groupById() Group by the id column
 * @method     ProjectEntryQuery groupByDayId() Group by the day_id column
 * @method     ProjectEntryQuery groupByUserId() Group by the user_id column
 *
 * @method     ProjectEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ProjectEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ProjectEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ProjectEntryQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method     ProjectEntryQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method     ProjectEntryQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method     ProjectEntryQuery leftJoinTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the Team relation
 * @method     ProjectEntryQuery rightJoinTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Team relation
 * @method     ProjectEntryQuery innerJoinTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the Team relation
 *
 * @method     ProjectEntryQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     ProjectEntryQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     ProjectEntryQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     ProjectEntryQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method     ProjectEntryQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method     ProjectEntryQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method     ProjectEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ProjectEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ProjectEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ProjectEntry findOne(PropelPDO $con = null) Return the first ProjectEntry matching the query
 * @method     ProjectEntry findOneOrCreate(PropelPDO $con = null) Return the first ProjectEntry matching the query, or a new ProjectEntry object populated from the query conditions when no match is found
 *
 * @method     ProjectEntry findOneByProjectId(int $project_id) Return the first ProjectEntry filtered by the project_id column
 * @method     ProjectEntry findOneByTeamId(int $team_id) Return the first ProjectEntry filtered by the team_id column
 * @method     ProjectEntry findOneByTimeInterval(int $time_interval) Return the first ProjectEntry filtered by the time_interval column
 * @method     ProjectEntry findOneById(int $id) Return the first ProjectEntry filtered by the id column
 * @method     ProjectEntry findOneByDayId(int $day_id) Return the first ProjectEntry filtered by the day_id column
 * @method     ProjectEntry findOneByUserId(int $user_id) Return the first ProjectEntry filtered by the user_id column
 *
 * @method     array findByProjectId(int $project_id) Return ProjectEntry objects filtered by the project_id column
 * @method     array findByTeamId(int $team_id) Return ProjectEntry objects filtered by the team_id column
 * @method     array findByTimeInterval(int $time_interval) Return ProjectEntry objects filtered by the time_interval column
 * @method     array findById(int $id) Return ProjectEntry objects filtered by the id column
 * @method     array findByDayId(int $day_id) Return ProjectEntry objects filtered by the day_id column
 * @method     array findByUserId(int $user_id) Return ProjectEntry objects filtered by the user_id column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseProjectEntryQuery extends EntryQuery
{
	
	/**
	 * Initializes internal state of BaseProjectEntryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'ProjectEntry', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new ProjectEntryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    ProjectEntryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof ProjectEntryQuery) {
			return $criteria;
		}
		$query = new ProjectEntryQuery();
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
	 * @return    ProjectEntry|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = ProjectEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    ProjectEntry A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `PROJECT_ID`, `TEAM_ID`, `TIME_INTERVAL`, `ID`, `DAY_ID`, `USER_ID` FROM `projectentries` WHERE `ID` = :p0';
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
			$obj = new ProjectEntry();
			$obj->hydrate($row);
			ProjectEntryPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    ProjectEntry|array|mixed the result, formatted by the current formatter
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(ProjectEntryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(ProjectEntryPeer::ID, $keys, Criteria::IN);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByProjectId($projectId = null, $comparison = null)
	{
		if (is_array($projectId)) {
			$useMinMax = false;
			if (isset($projectId['min'])) {
				$this->addUsingAlias(ProjectEntryPeer::PROJECT_ID, $projectId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($projectId['max'])) {
				$this->addUsingAlias(ProjectEntryPeer::PROJECT_ID, $projectId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(ProjectEntryPeer::PROJECT_ID, $projectId, $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByTeamId($teamId = null, $comparison = null)
	{
		if (is_array($teamId)) {
			$useMinMax = false;
			if (isset($teamId['min'])) {
				$this->addUsingAlias(ProjectEntryPeer::TEAM_ID, $teamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($teamId['max'])) {
				$this->addUsingAlias(ProjectEntryPeer::TEAM_ID, $teamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(ProjectEntryPeer::TEAM_ID, $teamId, $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByTimeInterval($timeInterval = null, $comparison = null)
	{
		if (is_array($timeInterval)) {
			$useMinMax = false;
			if (isset($timeInterval['min'])) {
				$this->addUsingAlias(ProjectEntryPeer::TIME_INTERVAL, $timeInterval['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($timeInterval['max'])) {
				$this->addUsingAlias(ProjectEntryPeer::TIME_INTERVAL, $timeInterval['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(ProjectEntryPeer::TIME_INTERVAL, $timeInterval, $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(ProjectEntryPeer::ID, $id, $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByDayId($dayId = null, $comparison = null)
	{
		if (is_array($dayId)) {
			$useMinMax = false;
			if (isset($dayId['min'])) {
				$this->addUsingAlias(ProjectEntryPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dayId['max'])) {
				$this->addUsingAlias(ProjectEntryPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(ProjectEntryPeer::DAY_ID, $dayId, $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId)) {
			$useMinMax = false;
			if (isset($userId['min'])) {
				$this->addUsingAlias(ProjectEntryPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userId['max'])) {
				$this->addUsingAlias(ProjectEntryPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(ProjectEntryPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query by a related Project object
	 *
	 * @param     Project|PropelCollection $project The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByProject($project, $comparison = null)
	{
		if ($project instanceof Project) {
			return $this
				->addUsingAlias(ProjectEntryPeer::PROJECT_ID, $project->getId(), $comparison);
		} elseif ($project instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(ProjectEntryPeer::PROJECT_ID, $project->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByTeam($team, $comparison = null)
	{
		if ($team instanceof Team) {
			return $this
				->addUsingAlias(ProjectEntryPeer::TEAM_ID, $team->getId(), $comparison);
		} elseif ($team instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(ProjectEntryPeer::TEAM_ID, $team->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function joinTeam($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
	public function useTeamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Team', 'TeamQuery');
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry|PropelCollection $entry The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(ProjectEntryPeer::ID, $entry->getId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(ProjectEntryPeer::ID, $entry->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByDay($day, $comparison = null)
	{
		if ($day instanceof Day) {
			return $this
				->addUsingAlias(ProjectEntryPeer::DAY_ID, $day->getId(), $comparison);
		} elseif ($day instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(ProjectEntryPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(ProjectEntryPeer::USER_ID, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(ProjectEntryPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    ProjectEntryQuery The current query, for fluid interface
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
	 * @param     ProjectEntry $projectEntry Object to remove from the list of results
	 *
	 * @return    ProjectEntryQuery The current query, for fluid interface
	 */
	public function prune($projectEntry = null)
	{
		if ($projectEntry) {
			$this->addUsingAlias(ProjectEntryPeer::ID, $projectEntry->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseProjectEntryQuery