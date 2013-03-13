<?php


/**
 * Base class that represents a query for the 'users' table.
 *
 * 
 *
 * @method     UserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     UserQuery orderByFirstname($order = Criteria::ASC) Order by the firstName column
 * @method     UserQuery orderByLastname($order = Criteria::ASC) Order by the lastName column
 * @method     UserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     UserQuery orderByBirthdate($order = Criteria::ASC) Order by the birthdate column
 * @method     UserQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method     UserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     UserQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     UserQuery orderByWorkload($order = Criteria::ASC) Order by the workload column
 * @method     UserQuery orderByOffdays($order = Criteria::ASC) Order by the offDays column
 * @method     UserQuery orderByEntrydate($order = Criteria::ASC) Order by the entryDate column
 * @method     UserQuery orderByExitdate($order = Criteria::ASC) Order by the exitDate column
 * @method     UserQuery orderByRole($order = Criteria::ASC) Order by the role column
 * @method     UserQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 * @method     UserQuery orderByArchived($order = Criteria::ASC) Order by the archived column
 * @method     UserQuery orderByLastlogin($order = Criteria::ASC) Order by the lastLogin column
 * @method     UserQuery orderByPasswordresettoken($order = Criteria::ASC) Order by the passwordResetToken column
 *
 * @method     UserQuery groupById() Group by the id column
 * @method     UserQuery groupByFirstname() Group by the firstName column
 * @method     UserQuery groupByLastname() Group by the lastName column
 * @method     UserQuery groupByEmail() Group by the email column
 * @method     UserQuery groupByBirthdate() Group by the birthdate column
 * @method     UserQuery groupByLogin() Group by the login column
 * @method     UserQuery groupByPassword() Group by the password column
 * @method     UserQuery groupByType() Group by the type column
 * @method     UserQuery groupByWorkload() Group by the workload column
 * @method     UserQuery groupByOffdays() Group by the offDays column
 * @method     UserQuery groupByEntrydate() Group by the entryDate column
 * @method     UserQuery groupByExitdate() Group by the exitDate column
 * @method     UserQuery groupByRole() Group by the role column
 * @method     UserQuery groupByEnabled() Group by the enabled column
 * @method     UserQuery groupByArchived() Group by the archived column
 * @method     UserQuery groupByLastlogin() Group by the lastLogin column
 * @method     UserQuery groupByPasswordresettoken() Group by the passwordResetToken column
 *
 * @method     UserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     UserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     UserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     UserQuery leftJoinTeamUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the TeamUser relation
 * @method     UserQuery rightJoinTeamUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TeamUser relation
 * @method     UserQuery innerJoinTeamUser($relationAlias = null) Adds a INNER JOIN clause to the query using the TeamUser relation
 *
 * @method     UserQuery leftJoinUserProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserProject relation
 * @method     UserQuery rightJoinUserProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserProject relation
 * @method     UserQuery innerJoinUserProject($relationAlias = null) Adds a INNER JOIN clause to the query using the UserProject relation
 *
 * @method     UserQuery leftJoinTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tag relation
 * @method     UserQuery rightJoinTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tag relation
 * @method     UserQuery innerJoinTag($relationAlias = null) Adds a INNER JOIN clause to the query using the Tag relation
 *
 * @method     UserQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method     UserQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method     UserQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method     UserQuery leftJoinAuditEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuditEvent relation
 * @method     UserQuery rightJoinAuditEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuditEvent relation
 * @method     UserQuery innerJoinAuditEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the AuditEvent relation
 *
 * @method     UserQuery leftJoinOOBooking($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOBooking relation
 * @method     UserQuery rightJoinOOBooking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOBooking relation
 * @method     UserQuery innerJoinOOBooking($relationAlias = null) Adds a INNER JOIN clause to the query using the OOBooking relation
 *
 * @method     UserQuery leftJoinRegularEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the RegularEntry relation
 * @method     UserQuery rightJoinRegularEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RegularEntry relation
 * @method     UserQuery innerJoinRegularEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the RegularEntry relation
 *
 * @method     UserQuery leftJoinProjectEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectEntry relation
 * @method     UserQuery rightJoinProjectEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectEntry relation
 * @method     UserQuery innerJoinProjectEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectEntry relation
 *
 * @method     UserQuery leftJoinOOEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the OOEntry relation
 * @method     UserQuery rightJoinOOEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OOEntry relation
 * @method     UserQuery innerJoinOOEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the OOEntry relation
 *
 * @method     UserQuery leftJoinAdjustmentEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdjustmentEntry relation
 * @method     UserQuery rightJoinAdjustmentEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdjustmentEntry relation
 * @method     UserQuery innerJoinAdjustmentEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the AdjustmentEntry relation
 *
 * @method     User findOne(PropelPDO $con = null) Return the first User matching the query
 * @method     User findOneOrCreate(PropelPDO $con = null) Return the first User matching the query, or a new User object populated from the query conditions when no match is found
 *
 * @method     User findOneById(int $id) Return the first User filtered by the id column
 * @method     User findOneByFirstname(string $firstName) Return the first User filtered by the firstName column
 * @method     User findOneByLastname(string $lastName) Return the first User filtered by the lastName column
 * @method     User findOneByEmail(string $email) Return the first User filtered by the email column
 * @method     User findOneByBirthdate(string $birthdate) Return the first User filtered by the birthdate column
 * @method     User findOneByLogin(string $login) Return the first User filtered by the login column
 * @method     User findOneByPassword(string $password) Return the first User filtered by the password column
 * @method     User findOneByType(string $type) Return the first User filtered by the type column
 * @method     User findOneByWorkload(double $workload) Return the first User filtered by the workload column
 * @method     User findOneByOffdays(string $offDays) Return the first User filtered by the offDays column
 * @method     User findOneByEntrydate(string $entryDate) Return the first User filtered by the entryDate column
 * @method     User findOneByExitdate(string $exitDate) Return the first User filtered by the exitDate column
 * @method     User findOneByRole(string $role) Return the first User filtered by the role column
 * @method     User findOneByEnabled(boolean $enabled) Return the first User filtered by the enabled column
 * @method     User findOneByArchived(boolean $archived) Return the first User filtered by the archived column
 * @method     User findOneByLastlogin(string $lastLogin) Return the first User filtered by the lastLogin column
 * @method     User findOneByPasswordresettoken(string $passwordResetToken) Return the first User filtered by the passwordResetToken column
 *
 * @method     array findById(int $id) Return User objects filtered by the id column
 * @method     array findByFirstname(string $firstName) Return User objects filtered by the firstName column
 * @method     array findByLastname(string $lastName) Return User objects filtered by the lastName column
 * @method     array findByEmail(string $email) Return User objects filtered by the email column
 * @method     array findByBirthdate(string $birthdate) Return User objects filtered by the birthdate column
 * @method     array findByLogin(string $login) Return User objects filtered by the login column
 * @method     array findByPassword(string $password) Return User objects filtered by the password column
 * @method     array findByType(string $type) Return User objects filtered by the type column
 * @method     array findByWorkload(double $workload) Return User objects filtered by the workload column
 * @method     array findByOffdays(string $offDays) Return User objects filtered by the offDays column
 * @method     array findByEntrydate(string $entryDate) Return User objects filtered by the entryDate column
 * @method     array findByExitdate(string $exitDate) Return User objects filtered by the exitDate column
 * @method     array findByRole(string $role) Return User objects filtered by the role column
 * @method     array findByEnabled(boolean $enabled) Return User objects filtered by the enabled column
 * @method     array findByArchived(boolean $archived) Return User objects filtered by the archived column
 * @method     array findByLastlogin(string $lastLogin) Return User objects filtered by the lastLogin column
 * @method     array findByPasswordresettoken(string $passwordResetToken) Return User objects filtered by the passwordResetToken column
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseUserQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseUserQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'momo', $modelName = 'User', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new UserQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    UserQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof UserQuery) {
			return $criteria;
		}
		$query = new UserQuery();
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
	 * @return    User|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = UserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    User A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `FIRSTNAME`, `LASTNAME`, `EMAIL`, `BIRTHDATE`, `LOGIN`, `PASSWORD`, `TYPE`, `WORKLOAD`, `OFFDAYS`, `ENTRYDATE`, `EXITDATE`, `ROLE`, `ENABLED`, `ARCHIVED`, `LASTLOGIN`, `PASSWORDRESETTOKEN` FROM `users` WHERE `ID` = :p0';
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
			$obj = new User();
			$obj->hydrate($row);
			UserPeer::addInstanceToPool($obj, (string) $key);
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
	 * @return    User|array|mixed the result, formatted by the current formatter
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
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(UserPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(UserPeer::ID, $keys, Criteria::IN);
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
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(UserPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the firstName column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByFirstname('fooValue');   // WHERE firstName = 'fooValue'
	 * $query->filterByFirstname('%fooValue%'); // WHERE firstName LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $firstname The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByFirstname($firstname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($firstname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $firstname)) {
				$firstname = str_replace('*', '%', $firstname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::FIRSTNAME, $firstname, $comparison);
	}

	/**
	 * Filter the query on the lastName column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLastname('fooValue');   // WHERE lastName = 'fooValue'
	 * $query->filterByLastname('%fooValue%'); // WHERE lastName LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $lastname The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByLastname($lastname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($lastname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $lastname)) {
				$lastname = str_replace('*', '%', $lastname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::LASTNAME, $lastname, $comparison);
	}

	/**
	 * Filter the query on the email column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
	 * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $email The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByEmail($email = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($email)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $email)) {
				$email = str_replace('*', '%', $email);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::EMAIL, $email, $comparison);
	}

	/**
	 * Filter the query on the birthdate column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByBirthdate('2011-03-14'); // WHERE birthdate = '2011-03-14'
	 * $query->filterByBirthdate('now'); // WHERE birthdate = '2011-03-14'
	 * $query->filterByBirthdate(array('max' => 'yesterday')); // WHERE birthdate > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $birthdate The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByBirthdate($birthdate = null, $comparison = null)
	{
		if (is_array($birthdate)) {
			$useMinMax = false;
			if (isset($birthdate['min'])) {
				$this->addUsingAlias(UserPeer::BIRTHDATE, $birthdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($birthdate['max'])) {
				$this->addUsingAlias(UserPeer::BIRTHDATE, $birthdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(UserPeer::BIRTHDATE, $birthdate, $comparison);
	}

	/**
	 * Filter the query on the login column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLogin('fooValue');   // WHERE login = 'fooValue'
	 * $query->filterByLogin('%fooValue%'); // WHERE login LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $login The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByLogin($login = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($login)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $login)) {
				$login = str_replace('*', '%', $login);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::LOGIN, $login, $comparison);
	}

	/**
	 * Filter the query on the password column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
	 * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $password The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByPassword($password = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($password)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $password)) {
				$password = str_replace('*', '%', $password);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::PASSWORD, $password, $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
		return $this->addUsingAlias(UserPeer::TYPE, $type, $comparison);
	}

	/**
	 * Filter the query on the workload column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByWorkload(1234); // WHERE workload = 1234
	 * $query->filterByWorkload(array(12, 34)); // WHERE workload IN (12, 34)
	 * $query->filterByWorkload(array('min' => 12)); // WHERE workload > 12
	 * </code>
	 *
	 * @param     mixed $workload The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByWorkload($workload = null, $comparison = null)
	{
		if (is_array($workload)) {
			$useMinMax = false;
			if (isset($workload['min'])) {
				$this->addUsingAlias(UserPeer::WORKLOAD, $workload['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($workload['max'])) {
				$this->addUsingAlias(UserPeer::WORKLOAD, $workload['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(UserPeer::WORKLOAD, $workload, $comparison);
	}

	/**
	 * Filter the query on the offDays column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByOffdays('fooValue');   // WHERE offDays = 'fooValue'
	 * $query->filterByOffdays('%fooValue%'); // WHERE offDays LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $offdays The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByOffdays($offdays = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($offdays)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $offdays)) {
				$offdays = str_replace('*', '%', $offdays);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::OFFDAYS, $offdays, $comparison);
	}

	/**
	 * Filter the query on the entryDate column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByEntrydate('2011-03-14'); // WHERE entryDate = '2011-03-14'
	 * $query->filterByEntrydate('now'); // WHERE entryDate = '2011-03-14'
	 * $query->filterByEntrydate(array('max' => 'yesterday')); // WHERE entryDate > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $entrydate The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByEntrydate($entrydate = null, $comparison = null)
	{
		if (is_array($entrydate)) {
			$useMinMax = false;
			if (isset($entrydate['min'])) {
				$this->addUsingAlias(UserPeer::ENTRYDATE, $entrydate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($entrydate['max'])) {
				$this->addUsingAlias(UserPeer::ENTRYDATE, $entrydate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(UserPeer::ENTRYDATE, $entrydate, $comparison);
	}

	/**
	 * Filter the query on the exitDate column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByExitdate('2011-03-14'); // WHERE exitDate = '2011-03-14'
	 * $query->filterByExitdate('now'); // WHERE exitDate = '2011-03-14'
	 * $query->filterByExitdate(array('max' => 'yesterday')); // WHERE exitDate > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $exitdate The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByExitdate($exitdate = null, $comparison = null)
	{
		if (is_array($exitdate)) {
			$useMinMax = false;
			if (isset($exitdate['min'])) {
				$this->addUsingAlias(UserPeer::EXITDATE, $exitdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($exitdate['max'])) {
				$this->addUsingAlias(UserPeer::EXITDATE, $exitdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(UserPeer::EXITDATE, $exitdate, $comparison);
	}

	/**
	 * Filter the query on the role column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByRole('fooValue');   // WHERE role = 'fooValue'
	 * $query->filterByRole('%fooValue%'); // WHERE role LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $role The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByRole($role = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($role)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $role)) {
				$role = str_replace('*', '%', $role);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::ROLE, $role, $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByEnabled($enabled = null, $comparison = null)
	{
		if (is_string($enabled)) {
			$enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(UserPeer::ENABLED, $enabled, $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByArchived($archived = null, $comparison = null)
	{
		if (is_string($archived)) {
			$archived = in_array(strtolower($archived), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(UserPeer::ARCHIVED, $archived, $comparison);
	}

	/**
	 * Filter the query on the lastLogin column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLastlogin('2011-03-14'); // WHERE lastLogin = '2011-03-14'
	 * $query->filterByLastlogin('now'); // WHERE lastLogin = '2011-03-14'
	 * $query->filterByLastlogin(array('max' => 'yesterday')); // WHERE lastLogin > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $lastlogin The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByLastlogin($lastlogin = null, $comparison = null)
	{
		if (is_array($lastlogin)) {
			$useMinMax = false;
			if (isset($lastlogin['min'])) {
				$this->addUsingAlias(UserPeer::LASTLOGIN, $lastlogin['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($lastlogin['max'])) {
				$this->addUsingAlias(UserPeer::LASTLOGIN, $lastlogin['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(UserPeer::LASTLOGIN, $lastlogin, $comparison);
	}

	/**
	 * Filter the query on the passwordResetToken column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPasswordresettoken('fooValue');   // WHERE passwordResetToken = 'fooValue'
	 * $query->filterByPasswordresettoken('%fooValue%'); // WHERE passwordResetToken LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $passwordresettoken The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByPasswordresettoken($passwordresettoken = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($passwordresettoken)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $passwordresettoken)) {
				$passwordresettoken = str_replace('*', '%', $passwordresettoken);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(UserPeer::PASSWORDRESETTOKEN, $passwordresettoken, $comparison);
	}

	/**
	 * Filter the query by a related TeamUser object
	 *
	 * @param     TeamUser $teamUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByTeamUser($teamUser, $comparison = null)
	{
		if ($teamUser instanceof TeamUser) {
			return $this
				->addUsingAlias(UserPeer::ID, $teamUser->getUserId(), $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related UserProject object
	 *
	 * @param     UserProject $userProject  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByUserProject($userProject, $comparison = null)
	{
		if ($userProject instanceof UserProject) {
			return $this
				->addUsingAlias(UserPeer::ID, $userProject->getUserId(), $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related Tag object
	 *
	 * @param     Tag $tag  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByTag($tag, $comparison = null)
	{
		if ($tag instanceof Tag) {
			return $this
				->addUsingAlias(UserPeer::ID, $tag->getUserId(), $comparison);
		} elseif ($tag instanceof PropelCollection) {
			return $this
				->useTagQuery()
				->filterByPrimaryKeys($tag->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByTag() only accepts arguments of type Tag or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the Tag relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function joinTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Tag');

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
			$this->addJoinObject($join, 'Tag');
		}

		return $this;
	}

	/**
	 * Use the Tag relation Tag object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    TagQuery A secondary query class using the current class as primary query
	 */
	public function useTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinTag($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Tag', 'TagQuery');
	}

	/**
	 * Filter the query by a related Entry object
	 *
	 * @param     Entry $entry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByEntry($entry, $comparison = null)
	{
		if ($entry instanceof Entry) {
			return $this
				->addUsingAlias(UserPeer::ID, $entry->getUserId(), $comparison);
		} elseif ($entry instanceof PropelCollection) {
			return $this
				->useEntryQuery()
				->filterByPrimaryKeys($entry->getPrimaryKeys())
				->endUse();
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related AuditEvent object
	 *
	 * @param     AuditEvent $auditEvent  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByAuditEvent($auditEvent, $comparison = null)
	{
		if ($auditEvent instanceof AuditEvent) {
			return $this
				->addUsingAlias(UserPeer::ID, $auditEvent->getUserId(), $comparison);
		} elseif ($auditEvent instanceof PropelCollection) {
			return $this
				->useAuditEventQuery()
				->filterByPrimaryKeys($auditEvent->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByAuditEvent() only accepts arguments of type AuditEvent or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the AuditEvent relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function joinAuditEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AuditEvent');

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
			$this->addJoinObject($join, 'AuditEvent');
		}

		return $this;
	}

	/**
	 * Use the AuditEvent relation AuditEvent object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AuditEventQuery A secondary query class using the current class as primary query
	 */
	public function useAuditEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAuditEvent($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AuditEvent', 'AuditEventQuery');
	}

	/**
	 * Filter the query by a related OOBooking object
	 *
	 * @param     OOBooking $oOBooking  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByOOBooking($oOBooking, $comparison = null)
	{
		if ($oOBooking instanceof OOBooking) {
			return $this
				->addUsingAlias(UserPeer::ID, $oOBooking->getUserId(), $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related RegularEntry object
	 *
	 * @param     RegularEntry $regularEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByRegularEntry($regularEntry, $comparison = null)
	{
		if ($regularEntry instanceof RegularEntry) {
			return $this
				->addUsingAlias(UserPeer::ID, $regularEntry->getUserId(), $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related ProjectEntry object
	 *
	 * @param     ProjectEntry $projectEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByProjectEntry($projectEntry, $comparison = null)
	{
		if ($projectEntry instanceof ProjectEntry) {
			return $this
				->addUsingAlias(UserPeer::ID, $projectEntry->getUserId(), $comparison);
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
	 * @return    UserQuery The current query, for fluid interface
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
	 * Filter the query by a related OOEntry object
	 *
	 * @param     OOEntry $oOEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByOOEntry($oOEntry, $comparison = null)
	{
		if ($oOEntry instanceof OOEntry) {
			return $this
				->addUsingAlias(UserPeer::ID, $oOEntry->getUserId(), $comparison);
		} elseif ($oOEntry instanceof PropelCollection) {
			return $this
				->useOOEntryQuery()
				->filterByPrimaryKeys($oOEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByOOEntry() only accepts arguments of type OOEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the OOEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function joinOOEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('OOEntry');

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
			$this->addJoinObject($join, 'OOEntry');
		}

		return $this;
	}

	/**
	 * Use the OOEntry relation OOEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    OOEntryQuery A secondary query class using the current class as primary query
	 */
	public function useOOEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinOOEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'OOEntry', 'OOEntryQuery');
	}

	/**
	 * Filter the query by a related AdjustmentEntry object
	 *
	 * @param     AdjustmentEntry $adjustmentEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByAdjustmentEntry($adjustmentEntry, $comparison = null)
	{
		if ($adjustmentEntry instanceof AdjustmentEntry) {
			return $this
				->addUsingAlias(UserPeer::ID, $adjustmentEntry->getUserId(), $comparison);
		} elseif ($adjustmentEntry instanceof PropelCollection) {
			return $this
				->useAdjustmentEntryQuery()
				->filterByPrimaryKeys($adjustmentEntry->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByAdjustmentEntry() only accepts arguments of type AdjustmentEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the AdjustmentEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function joinAdjustmentEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AdjustmentEntry');

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
			$this->addJoinObject($join, 'AdjustmentEntry');
		}

		return $this;
	}

	/**
	 * Use the AdjustmentEntry relation AdjustmentEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AdjustmentEntryQuery A secondary query class using the current class as primary query
	 */
	public function useAdjustmentEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAdjustmentEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AdjustmentEntry', 'AdjustmentEntryQuery');
	}

	/**
	 * Filter the query by a related Team object
	 * using the teams_users table as cross reference
	 *
	 * @param     Team $team the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByTeam($team, $comparison = Criteria::EQUAL)
	{
		return $this
			->useTeamUserQuery()
			->filterByTeam($team, $comparison)
			->endUse();
	}

	/**
	 * Filter the query by a related Project object
	 * using the users_projects table as cross reference
	 *
	 * @param     Project $project the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function filterByProject($project, $comparison = Criteria::EQUAL)
	{
		return $this
			->useUserProjectQuery()
			->filterByProject($project, $comparison)
			->endUse();
	}

	/**
	 * Exclude object from result
	 *
	 * @param     User $user Object to remove from the list of results
	 *
	 * @return    UserQuery The current query, for fluid interface
	 */
	public function prune($user = null)
	{
		if ($user) {
			$this->addUsingAlias(UserPeer::ID, $user->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseUserQuery