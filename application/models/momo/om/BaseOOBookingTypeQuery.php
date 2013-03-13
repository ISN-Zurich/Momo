<?php


/**
 * Base class that represents a query for the 'oobookingtypes' table.
 *
 * 
 *
 * @method     OOBookingTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     OOBookingTypeQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     OOBookingTypeQuery orderByPaid($order = Criteria::ASC) Order by the paid column
 * @method     OOBookingTypeQuery orderByCreator($order = Criteria::ASC) Order by the creator column
 * @method     OOBookingTypeQuery orderByBookableindays($order = Criteria::ASC) Order by the bookableInDays column
 * @method     OOBookingTypeQuery orderByBookableinhalfdays($order = Criteria::ASC) Order by the bookableInHalfDays column
 * @method     OOBookingTypeQuery orderByRgbcolorvalue($order = Criteria::ASC) Order by the rgbColorValue column
 * @method     OOBookingTypeQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 *
 * @method     OOBookingTypeQuery groupById() Group by the id column
 * @method     OOBookingTypeQuery groupByType() Group by the type column
 * @method     OOBookingTypeQuery groupByPaid() Group by the paid column
 * @method     OOBookingTypeQuery groupByCreator() Group by the creator column
 * @method     OOBookingTypeQuery groupByBookableindays() Group by the bookableInDays column
 * @method     OOBookingTypeQuery groupByBookableinhalfdays() Group by the bookableInHalfDays column
 * @method     OOBookingTypeQuery groupByRgbcolorvalue() Group by the rgbColorValue column
 * @method     OOBookingTypeQuery groupByEnabled() Group by the enabled column
 *
 * @method     OOBookingTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     OOBookingTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     OOBookingTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     OOBookingTypeQuery leftJoinOOBooking($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOBooking relation
 * @method     OOBookingTypeQuery rightJoinOOBooking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOBooking relation
 * @method     OOBookingTypeQuery innerJoinOOBooking($relationAlias = null) Adds a INNER JOIN clause to the query using the OOBooking relation
 *
 * @method     OOBookingType findOne(PropelPDO $con = null) Return the first OOBookingType matching the query
 * @method     OOBookingType findOneOrCreate(PropelPDO $con = null) Return the first OOBookingType matching the query, or a new OOBookingType object populated from the query conditions when no match is found
 *
 * @method     OOBookingType findOneById(int $id) Return the first OOBookingType filtered by the id column
 * @method     OOBookingType findOneByType(string $type) Return the first OOBookingType filtered by the type column
 * @method     OOBookingType findOneByPaid(boolean $paid) Return the first OOBookingType filtered by the paid column
 * @method     OOBookingType findOneByCreator(string $creator) Return the first OOBookingType filtered by the creator column
 * @method     OOBookingType findOneByBookableindays(boolean $bookableInDays) Return the first OOBookingType filtered by the bookableInDays column
 * @method     OOBookingType findOneByBookableinhalfdays(boolean $bookableInHalfDays) Return the first OOBookingType filtered by the bookableInHalfDays column
 * @method     OOBookingType findOneByRgbcolorvalue(string $rgbColorValue) Return the first OOBookingType filtered by the rgbColorValue column
 * @method     OOBookingType findOneByEnabled(boolean $enabled) Return the first OOBookingType filtered by the enabled column
 *
 * @method     array findById(int $id) Return OOBookingType objects filtered by the id column
 * @method     array findByType(string $type) Return OOBookingType objects filtered by the type column
 * @method     array findByPaid(boolean $paid) Return OOBookingType objects filtered by the paid column
 * @method     array findByCreator(string $creator) Return OOBookingType objects filtered by the creator column
 * @method     array findByBookableindays(boolean $bookableInDays) Return OOBookingType objects filtered by the bookableInDays column
 * @method     array findByBookableinhalfdays(boolean $bookableInHalfDays) Return OOBookingType objects filtered by the bookableInHalfDays column
 * @method     array findByRgbcolorvalue(string $rgbColorValue) Return OOBookingType objects filtered by the rgbColorValue column
 * @method     array findByEnabled(boolean $enabled) Return OOBookingType objects filtered by the enabled column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseOOBookingTypeQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseOOBookingTypeQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'OOBookingType', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new OOBookingTypeQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    OOBookingTypeQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof OOBookingTypeQuery) {
			return $criteria;
		}
		$query = new OOBookingTypeQuery();
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
	 * @return    OOBookingType|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = OOBookingTypePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(OOBookingTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    OOBookingType A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `TYPE`, `PAID`, `CREATOR`, `BOOKABLEINDAYS`, `BOOKABLEINHALFDAYS`, `RGBCOLORVALUE`, `ENABLED` FROM `oobookingtypes` WHERE `ID` = :p0';
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
			$obj = new OOBookingType();
			$obj->hydrate($row);
			OOBookingTypePeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    OOBookingType|array|mixed the result, formatted by the current formatter
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(OOBookingTypePeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(OOBookingTypePeer::ID, $keys, Criteria::IN);
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(OOBookingTypePeer::ID, $id, $comparison);
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
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
		return $this->addUsingAlias(OOBookingTypePeer::TYPE, $type, $comparison);
	}

	/**
	 * Filter the query on the paid column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPaid(true); // WHERE paid = true
	 * $query->filterByPaid('yes'); // WHERE paid = true
	 * </code>
	 *
	 * @param     boolean|string $paid The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByPaid($paid = null, $comparison = null)
	{
		if (is_string($paid)) {
			$paid = in_array(strtolower($paid), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(OOBookingTypePeer::PAID, $paid, $comparison);
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
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
		return $this->addUsingAlias(OOBookingTypePeer::CREATOR, $creator, $comparison);
	}

	/**
	 * Filter the query on the bookableInDays column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByBookableindays(true); // WHERE bookableInDays = true
	 * $query->filterByBookableindays('yes'); // WHERE bookableInDays = true
	 * </code>
	 *
	 * @param     boolean|string $bookableindays The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByBookableindays($bookableindays = null, $comparison = null)
	{
		if (is_string($bookableindays)) {
			$bookableInDays = in_array(strtolower($bookableindays), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(OOBookingTypePeer::BOOKABLEINDAYS, $bookableindays, $comparison);
	}

	/**
	 * Filter the query on the bookableInHalfDays column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByBookableinhalfdays(true); // WHERE bookableInHalfDays = true
	 * $query->filterByBookableinhalfdays('yes'); // WHERE bookableInHalfDays = true
	 * </code>
	 *
	 * @param     boolean|string $bookableinhalfdays The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByBookableinhalfdays($bookableinhalfdays = null, $comparison = null)
	{
		if (is_string($bookableinhalfdays)) {
			$bookableInHalfDays = in_array(strtolower($bookableinhalfdays), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(OOBookingTypePeer::BOOKABLEINHALFDAYS, $bookableinhalfdays, $comparison);
	}

	/**
	 * Filter the query on the rgbColorValue column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByRgbcolorvalue('fooValue');   // WHERE rgbColorValue = 'fooValue'
	 * $query->filterByRgbcolorvalue('%fooValue%'); // WHERE rgbColorValue LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $rgbcolorvalue The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByRgbcolorvalue($rgbcolorvalue = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($rgbcolorvalue)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $rgbcolorvalue)) {
				$rgbcolorvalue = str_replace('*', '%', $rgbcolorvalue);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(OOBookingTypePeer::RGBCOLORVALUE, $rgbcolorvalue, $comparison);
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByEnabled($enabled = null, $comparison = null)
	{
		if (is_string($enabled)) {
			$enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(OOBookingTypePeer::ENABLED, $enabled, $comparison);
	}

	/**
	 * Filter the query by a related OOBooking object
	 *
	 * @param     OOBooking $oOBooking  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function filterByOOBooking($oOBooking, $comparison = null)
	{
		if ($oOBooking instanceof OOBooking) {
			return $this
				->addUsingAlias(OOBookingTypePeer::ID, $oOBooking->getOobookingtypeId(), $comparison);
		} elseif ($oOBooking instanceof PropelCollection) {
			return $this
				->useOOBookingQuery()
				->filterByPrimaryKeys($oOBooking->getPrimaryKeys())
				->endUse();
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
	 * @return    OOBookingTypeQuery The current query, for fluid interface
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
	 * @param     OOBookingType $oOBookingType Object to remove from the list of results
	 *
	 * @return    OOBookingTypeQuery The current query, for fluid interface
	 */
	public function prune($oOBookingType = null)
	{
		if ($oOBookingType) {
			$this->addUsingAlias(OOBookingTypePeer::ID, $oOBookingType->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseOOBookingTypeQuery