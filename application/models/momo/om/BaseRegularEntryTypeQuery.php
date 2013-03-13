<?php


/**
 * Base class that represents a query for the 'regularentrytypes' table.
 *
 * 
 *
 * @method     RegularEntryTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     RegularEntryTypeQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     RegularEntryTypeQuery orderByCreator($order = Criteria::ASC) Order by the creator column
 * @method     RegularEntryTypeQuery orderByWorktimecreditawarded($order = Criteria::ASC) Order by the worktimeCreditAwarded column
 * @method     RegularEntryTypeQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 * @method     RegularEntryTypeQuery orderByDefaulttype($order = Criteria::ASC) Order by the defaultType column
 *
 * @method     RegularEntryTypeQuery groupById() Group by the id column
 * @method     RegularEntryTypeQuery groupByType() Group by the type column
 * @method     RegularEntryTypeQuery groupByCreator() Group by the creator column
 * @method     RegularEntryTypeQuery groupByWorktimecreditawarded() Group by the worktimeCreditAwarded column
 * @method     RegularEntryTypeQuery groupByEnabled() Group by the enabled column
 * @method     RegularEntryTypeQuery groupByDefaulttype() Group by the defaultType column
 *
 * @method     RegularEntryTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     RegularEntryTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     RegularEntryTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     RegularEntryTypeQuery leftJoinRegularEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegularEntry relation
 * @method     RegularEntryTypeQuery rightJoinRegularEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegularEntry relation
 * @method     RegularEntryTypeQuery innerJoinRegularEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the RegularEntry relation
 *
 * @method     RegularEntryType findOne(PropelPDO $con = null) Return the first RegularEntryType matching the query
 * @method     RegularEntryType findOneOrCreate(PropelPDO $con = null) Return the first RegularEntryType matching the query, or a new RegularEntryType object populated from the query conditions when no match is found
 *
 * @method     RegularEntryType findOneById(int $id) Return the first RegularEntryType filtered by the id column
 * @method     RegularEntryType findOneByType(string $type) Return the first RegularEntryType filtered by the type column
 * @method     RegularEntryType findOneByCreator(string $creator) Return the first RegularEntryType filtered by the creator column
 * @method     RegularEntryType findOneByWorktimecreditawarded(boolean $worktimeCreditAwarded) Return the first RegularEntryType filtered by the worktimeCreditAwarded column
 * @method     RegularEntryType findOneByEnabled(boolean $enabled) Return the first RegularEntryType filtered by the enabled column
 * @method     RegularEntryType findOneByDefaulttype(boolean $defaultType) Return the first RegularEntryType filtered by the defaultType column
 *
 * @method     array findById(int $id) Return RegularEntryType objects filtered by the id column
 * @method     array findByType(string $type) Return RegularEntryType objects filtered by the type column
 * @method     array findByCreator(string $creator) Return RegularEntryType objects filtered by the creator column
 * @method     array findByWorktimecreditawarded(boolean $worktimeCreditAwarded) Return RegularEntryType objects filtered by the worktimeCreditAwarded column
 * @method     array findByEnabled(boolean $enabled) Return RegularEntryType objects filtered by the enabled column
 * @method     array findByDefaulttype(boolean $defaultType) Return RegularEntryType objects filtered by the defaultType column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseRegularEntryTypeQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseRegularEntryTypeQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'RegularEntryType', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new RegularEntryTypeQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    RegularEntryTypeQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof RegularEntryTypeQuery) {
			return $criteria;
		}
		$query = new RegularEntryTypeQuery();
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
	 * @return    RegularEntryType|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = RegularEntryTypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(RegularEntryTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    RegularEntryType A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `TYPE`, `CREATOR`, `WORKTIMECREDITAWARDED`, `ENABLED`, `DEFAULTTYPE` FROM `regularentrytypes` WHERE `ID` = :p0';
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
			$obj = new RegularEntryType();
			$obj->hydrate($row);
			RegularEntryTypePeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    RegularEntryType|array|mixed the result, formatted by the current formatter
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(RegularEntryTypePeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(RegularEntryTypePeer::ID, $keys, Criteria::IN);
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(RegularEntryTypePeer::ID, $id, $comparison);
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
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
		return $this->addUsingAlias(RegularEntryTypePeer::TYPE, $type, $comparison);
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
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
		return $this->addUsingAlias(RegularEntryTypePeer::CREATOR, $creator, $comparison);
	}

	/**
	 * Filter the query on the worktimeCreditAwarded column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByWorktimecreditawarded(true); // WHERE worktimeCreditAwarded = true
	 * $query->filterByWorktimecreditawarded('yes'); // WHERE worktimeCreditAwarded = true
	 * </code>
	 *
	 * @param     boolean|string $worktimecreditawarded The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByWorktimecreditawarded($worktimecreditawarded = null, $comparison = null)
	{
		if (is_string($worktimecreditawarded)) {
			$worktimeCreditAwarded = in_array(strtolower($worktimecreditawarded), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(RegularEntryTypePeer::WORKTIMECREDITAWARDED, $worktimecreditawarded, $comparison);
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByEnabled($enabled = null, $comparison = null)
	{
		if (is_string($enabled)) {
			$enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(RegularEntryTypePeer::ENABLED, $enabled, $comparison);
	}

	/**
	 * Filter the query on the defaultType column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByDefaulttype(true); // WHERE defaultType = true
	 * $query->filterByDefaulttype('yes'); // WHERE defaultType = true
	 * </code>
	 *
	 * @param     boolean|string $defaulttype The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByDefaulttype($defaulttype = null, $comparison = null)
	{
		if (is_string($defaulttype)) {
			$defaultType = in_array(strtolower($defaulttype), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(RegularEntryTypePeer::DEFAULTTYPE, $defaulttype, $comparison);
	}

	/**
	 * Filter the query by a related RegularEntry object
	 *
	 * @param     RegularEntry $regularEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function filterByRegularEntry($regularEntry, $comparison = null)
	{
		if ($regularEntry instanceof RegularEntry) {
			return $this
				->addUsingAlias(RegularEntryTypePeer::ID, $regularEntry->getRegularentrytypeId(), $comparison);
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
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     RegularEntryType $regularEntryType Object to remove from the list of results
	 *
	 * @return    RegularEntryTypeQuery The current query, for fluid interface
	 */
	public function prune($regularEntryType = null)
	{
		if ($regularEntryType) {
			$this->addUsingAlias(RegularEntryTypePeer::ID, $regularEntryType->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseRegularEntryTypeQuery