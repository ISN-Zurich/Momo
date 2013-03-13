<?php


/**
 * Base class that represents a query for the 'settings' table.
 *
 * 
 *
 * @method     SettingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     SettingQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method     SettingQuery orderByValue($order = Criteria::ASC) Order by the value column
 *
 * @method     SettingQuery groupById() Group by the id column
 * @method     SettingQuery groupByKey() Group by the key column
 * @method     SettingQuery groupByValue() Group by the value column
 *
 * @method     SettingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     SettingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     SettingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     Setting findOne(PropelPDO $con = null) Return the first Setting matching the query
 * @method     Setting findOneOrCreate(PropelPDO $con = null) Return the first Setting matching the query, or a new Setting object populated from the query conditions when no match is found
 *
 * @method     Setting findOneById(int $id) Return the first Setting filtered by the id column
 * @method     Setting findOneByKey(string $key) Return the first Setting filtered by the key column
 * @method     Setting findOneByValue(string $value) Return the first Setting filtered by the value column
 *
 * @method     array findById(int $id) Return Setting objects filtered by the id column
 * @method     array findByKey(string $key) Return Setting objects filtered by the key column
 * @method     array findByValue(string $value) Return Setting objects filtered by the value column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseSettingQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseSettingQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'Setting', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new SettingQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    SettingQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof SettingQuery) {
			return $criteria;
		}
		$query = new SettingQuery();
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
	 * @return    Setting|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = SettingPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(SettingPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    Setting A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `KEY`, `VALUE` FROM `settings` WHERE `ID` = :p0';
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
			$obj = new Setting();
			$obj->hydrate($row);
			SettingPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    Setting|array|mixed the result, formatted by the current formatter
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
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(SettingPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(SettingPeer::ID, $keys, Criteria::IN);
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
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(SettingPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the key column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByKey('fooValue');   // WHERE key = 'fooValue'
	 * $query->filterByKey('%fooValue%'); // WHERE key LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $key The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function filterByKey($key = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($key)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $key)) {
				$key = str_replace('*', '%', $key);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(SettingPeer::KEY, $key, $comparison);
	}

	/**
	 * Filter the query on the value column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByValue('fooValue');   // WHERE value = 'fooValue'
	 * $query->filterByValue('%fooValue%'); // WHERE value LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $value The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function filterByValue($value = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($value)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $value)) {
				$value = str_replace('*', '%', $value);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(SettingPeer::VALUE, $value, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Setting $setting Object to remove from the list of results
	 *
	 * @return    SettingQuery The current query, for fluid interface
	 */
	public function prune($setting = null)
	{
		if ($setting) {
			$this->addUsingAlias(SettingPeer::ID, $setting->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseSettingQuery