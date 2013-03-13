<?php


/**
 * Base class that represents a query for the 'oorequests' table.
 *
 * 
 *
 * @method     OORequestQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     OORequestQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     OORequestQuery orderByOriginatorComment($order = Criteria::ASC) Order by the originator_comment column
 *
 * @method     OORequestQuery groupById() Group by the id column
 * @method     OORequestQuery groupByStatus() Group by the status column
 * @method     OORequestQuery groupByOriginatorComment() Group by the originator_comment column
 *
 * @method     OORequestQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     OORequestQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     OORequestQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     OORequestQuery leftJoinOOBooking($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOBooking relation
 * @method     OORequestQuery rightJoinOOBooking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOBooking relation
 * @method     OORequestQuery innerJoinOOBooking($relationAlias = null) Adds a INNER JOIN clause to the query using the OOBooking relation
 *
 * @method     OORequest findOne(PropelPDO $con = null) Return the first OORequest matching the query
 * @method     OORequest findOneOrCreate(PropelPDO $con = null) Return the first OORequest matching the query, or a new OORequest object populated from the query conditions when no match is found
 *
 * @method     OORequest findOneById(int $id) Return the first OORequest filtered by the id column
 * @method     OORequest findOneByStatus(string $status) Return the first OORequest filtered by the status column
 * @method     OORequest findOneByOriginatorComment(string $originator_comment) Return the first OORequest filtered by the originator_comment column
 *
 * @method     array findById(int $id) Return OORequest objects filtered by the id column
 * @method     array findByStatus(string $status) Return OORequest objects filtered by the status column
 * @method     array findByOriginatorComment(string $originator_comment) Return OORequest objects filtered by the originator_comment column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseOORequestQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseOORequestQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'OORequest', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new OORequestQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    OORequestQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof OORequestQuery) {
			return $criteria;
		}
		$query = new OORequestQuery();
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
	 * @return    OORequest|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = OORequestPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(OORequestPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    OORequest A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `STATUS`, `ORIGINATOR_COMMENT` FROM `oorequests` WHERE `ID` = :p0';
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
			$obj = new OORequest();
			$obj->hydrate($row);
			OORequestPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    OORequest|array|mixed the result, formatted by the current formatter
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
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(OORequestPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(OORequestPeer::ID, $keys, Criteria::IN);
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
	 * @see       filterByOOBooking()
	 *
	 * @param     mixed $id The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(OORequestPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the status column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
	 * $query->filterByStatus('%fooValue%'); // WHERE status LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $status The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterByStatus($status = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($status)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $status)) {
				$status = str_replace('*', '%', $status);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(OORequestPeer::STATUS, $status, $comparison);
	}

	/**
	 * Filter the query on the originator_comment column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOriginatorComment('fooValue');   // WHERE originator_comment = 'fooValue'
	 * $query->filterByOriginatorComment('%fooValue%'); // WHERE originator_comment LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $originatorComment The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterByOriginatorComment($originatorComment = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($originatorComment)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $originatorComment)) {
				$originatorComment = str_replace('*', '%', $originatorComment);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(OORequestPeer::ORIGINATOR_COMMENT, $originatorComment, $comparison);
	}

	/**
	 * Filter the query by a related OOBooking object
	 *
	 * @param     OOBooking|PropelCollection $oOBooking The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function filterByOOBooking($oOBooking, $comparison = null)
	{
		if ($oOBooking instanceof OOBooking) {
			return $this
				->addUsingAlias(OORequestPeer::ID, $oOBooking->getId(), $comparison);
		} elseif ($oOBooking instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(OORequestPeer::ID, $oOBooking->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
	 * @return    OORequestQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     OORequest $oORequest Object to remove from the list of results
	 *
	 * @return    OORequestQuery The current query, for fluid interface
	 */
	public function prune($oORequest = null)
	{
		if ($oORequest) {
			$this->addUsingAlias(OORequestPeer::ID, $oORequest->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseOORequestQuery