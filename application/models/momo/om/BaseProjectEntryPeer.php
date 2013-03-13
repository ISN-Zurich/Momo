<?php


/**
 * Base static class for performing query and update operations on the 'projectentries' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseProjectEntryPeer extends EntryPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'momo';

	/** the table name for this class */
	const TABLE_NAME = 'projectentries';

	/** the related Propel class for this table */
	const OM_CLASS = 'ProjectEntry';

	/** the related TableMap class for this table */
	const TM_CLASS = 'ProjectEntryTableMap';

	/** The total number of columns. */
	const NUM_COLUMNS = 6;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
	const NUM_HYDRATE_COLUMNS = 6;

	/** the column name for the PROJECT_ID field */
	const PROJECT_ID = 'projectentries.PROJECT_ID';

	/** the column name for the TEAM_ID field */
	const TEAM_ID = 'projectentries.TEAM_ID';

	/** the column name for the TIME_INTERVAL field */
	const TIME_INTERVAL = 'projectentries.TIME_INTERVAL';

	/** the column name for the ID field */
	const ID = 'projectentries.ID';

	/** the column name for the DAY_ID field */
	const DAY_ID = 'projectentries.DAY_ID';

	/** the column name for the USER_ID field */
	const USER_ID = 'projectentries.USER_ID';

	/** The default string format for model objects of the related table **/
	const DEFAULT_STRING_FORMAT = 'YAML';

	/**
	 * An identiy map to hold any loaded instances of ProjectEntry objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array ProjectEntry[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	protected static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('ProjectId', 'TeamId', 'TimeInterval', 'Id', 'DayId', 'UserId', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('projectId', 'teamId', 'timeInterval', 'id', 'dayId', 'userId', ),
		BasePeer::TYPE_COLNAME => array (self::PROJECT_ID, self::TEAM_ID, self::TIME_INTERVAL, self::ID, self::DAY_ID, self::USER_ID, ),
		BasePeer::TYPE_RAW_COLNAME => array ('PROJECT_ID', 'TEAM_ID', 'TIME_INTERVAL', 'ID', 'DAY_ID', 'USER_ID', ),
		BasePeer::TYPE_FIELDNAME => array ('project_id', 'team_id', 'time_interval', 'id', 'day_id', 'user_id', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	protected static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('ProjectId' => 0, 'TeamId' => 1, 'TimeInterval' => 2, 'Id' => 3, 'DayId' => 4, 'UserId' => 5, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('projectId' => 0, 'teamId' => 1, 'timeInterval' => 2, 'id' => 3, 'dayId' => 4, 'userId' => 5, ),
		BasePeer::TYPE_COLNAME => array (self::PROJECT_ID => 0, self::TEAM_ID => 1, self::TIME_INTERVAL => 2, self::ID => 3, self::DAY_ID => 4, self::USER_ID => 5, ),
		BasePeer::TYPE_RAW_COLNAME => array ('PROJECT_ID' => 0, 'TEAM_ID' => 1, 'TIME_INTERVAL' => 2, 'ID' => 3, 'DAY_ID' => 4, 'USER_ID' => 5, ),
		BasePeer::TYPE_FIELDNAME => array ('project_id' => 0, 'team_id' => 1, 'time_interval' => 2, 'id' => 3, 'day_id' => 4, 'user_id' => 5, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. ProjectEntryPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(ProjectEntryPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      Criteria $criteria object containing the columns to add.
	 * @param      string   $alias    optional table alias
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria, $alias = null)
	{
		if (null === $alias) {
			$criteria->addSelectColumn(ProjectEntryPeer::PROJECT_ID);
			$criteria->addSelectColumn(ProjectEntryPeer::TEAM_ID);
			$criteria->addSelectColumn(ProjectEntryPeer::TIME_INTERVAL);
			$criteria->addSelectColumn(ProjectEntryPeer::ID);
			$criteria->addSelectColumn(ProjectEntryPeer::DAY_ID);
			$criteria->addSelectColumn(ProjectEntryPeer::USER_ID);
		} else {
			$criteria->addSelectColumn($alias . '.PROJECT_ID');
			$criteria->addSelectColumn($alias . '.TEAM_ID');
			$criteria->addSelectColumn($alias . '.TIME_INTERVAL');
			$criteria->addSelectColumn($alias . '.ID');
			$criteria->addSelectColumn($alias . '.DAY_ID');
			$criteria->addSelectColumn($alias . '.USER_ID');
		}
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Selects one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     ProjectEntry
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = ProjectEntryPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Selects several row from the DB.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return ProjectEntryPeer::populateObjects(ProjectEntryPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      ProjectEntry $value A ProjectEntry object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool($obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A ProjectEntry object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof ProjectEntry) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or ProjectEntry object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     ProjectEntry Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to projectentries
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol + 3] === null) {
			return null;
		}
		return (string) $row[$startcol + 3];
	}

	/**
	 * Retrieves the primary key from the DB resultset row
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, an array of the primary key columns will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     mixed The primary key of the row
	 */
	public static function getPrimaryKeyFromRow($row, $startcol = 0)
	{
		return (int) $row[$startcol + 3];
	}
	
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = ProjectEntryPeer::getOMClass();
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = ProjectEntryPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				ProjectEntryPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Populates an object of the default type or an object that inherit from the default.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     array (ProjectEntry object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = ProjectEntryPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		} else {
			$cls = ProjectEntryPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			ProjectEntryPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Project table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinProject(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Team table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Entry table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinEntry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Day table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinDay(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related User table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with their Project objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinProject(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		ProjectPeer::addSelectColumns($criteria);

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = ProjectPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (ProjectEntry) to $obj2 (Project)
				$obj2->addProjectEntry($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with their Team objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		TeamPeer::addSelectColumns($criteria);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = TeamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = TeamPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					TeamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (ProjectEntry) to $obj2 (Team)
				$obj2->addProjectEntry($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with their Entry objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinEntry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		EntryPeer::addSelectColumns($criteria);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = EntryPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = EntryPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					EntryPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (ProjectEntry) to $obj2 (Entry)
				// one to one relationship
				$obj1->setEntry($obj2);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with their Day objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinDay(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		DayPeer::addSelectColumns($criteria);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = DayPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = DayPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					DayPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (ProjectEntry) to $obj2 (Day)
				$obj2->addProjectEntry($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with their User objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;
		UserPeer::addSelectColumns($criteria);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = UserPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = UserPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					UserPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (ProjectEntry) to $obj2 (User)
				$obj2->addProjectEntry($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}

	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		ProjectPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + ProjectPeer::NUM_HYDRATE_COLUMNS;

		TeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + TeamPeer::NUM_HYDRATE_COLUMNS;

		EntryPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + EntryPeer::NUM_HYDRATE_COLUMNS;

		DayPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + DayPeer::NUM_HYDRATE_COLUMNS;

		UserPeer::addSelectColumns($criteria);
		$startcol7 = $startcol6 + UserPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined Project rows

			$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = ProjectPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Project)
				$obj2->addProjectEntry($obj1);
			} // if joined row not null

			// Add objects for joined Team rows

			$key3 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = TeamPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$cls = TeamPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					TeamPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Team)
				$obj3->addProjectEntry($obj1);
			} // if joined row not null

			// Add objects for joined Entry rows

			$key4 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = EntryPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$cls = EntryPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					EntryPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Entry)
				$obj1->setEntry($obj4);
			} // if joined row not null

			// Add objects for joined Day rows

			$key5 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = DayPeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$cls = DayPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					DayPeer::addInstanceToPool($obj5, $key5);
				} // if obj5 loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (Day)
				$obj5->addProjectEntry($obj1);
			} // if joined row not null

			// Add objects for joined User rows

			$key6 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = UserPeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$cls = UserPeer::getOMClass();

					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					UserPeer::addInstanceToPool($obj6, $key6);
				} // if obj6 loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj6 (User)
				$obj6->addProjectEntry($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Project table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptProject(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY should not affect count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Team table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY should not affect count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Entry table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptEntry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY should not affect count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Day table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptDay(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY should not affect count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related User table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProjectEntryPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY should not affect count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects except Project.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptProject(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		TeamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + TeamPeer::NUM_HYDRATE_COLUMNS;

		EntryPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + EntryPeer::NUM_HYDRATE_COLUMNS;

		DayPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + DayPeer::NUM_HYDRATE_COLUMNS;

		UserPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Team rows

				$key2 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = TeamPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = TeamPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					TeamPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Team)
				$obj2->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Entry rows

				$key3 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = EntryPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = EntryPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					EntryPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Entry)
				$obj1->setEntry($obj3);

			} // if joined row is not null

				// Add objects for joined Day rows

				$key4 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = DayPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = DayPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					DayPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Day)
				$obj4->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined User rows

				$key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = UserPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = UserPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					UserPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (User)
				$obj5->addProjectEntry($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects except Team.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		ProjectPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + ProjectPeer::NUM_HYDRATE_COLUMNS;

		EntryPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + EntryPeer::NUM_HYDRATE_COLUMNS;

		DayPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + DayPeer::NUM_HYDRATE_COLUMNS;

		UserPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Project rows

				$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = ProjectPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Project)
				$obj2->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Entry rows

				$key3 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = EntryPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = EntryPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					EntryPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Entry)
				$obj1->setEntry($obj3);

			} // if joined row is not null

				// Add objects for joined Day rows

				$key4 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = DayPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = DayPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					DayPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Day)
				$obj4->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined User rows

				$key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = UserPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = UserPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					UserPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (User)
				$obj5->addProjectEntry($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects except Entry.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptEntry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		ProjectPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + ProjectPeer::NUM_HYDRATE_COLUMNS;

		TeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + TeamPeer::NUM_HYDRATE_COLUMNS;

		DayPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + DayPeer::NUM_HYDRATE_COLUMNS;

		UserPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Project rows

				$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = ProjectPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Project)
				$obj2->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Team rows

				$key3 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = TeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = TeamPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					TeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Team)
				$obj3->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Day rows

				$key4 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = DayPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = DayPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					DayPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Day)
				$obj4->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined User rows

				$key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = UserPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = UserPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					UserPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (User)
				$obj5->addProjectEntry($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects except Day.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptDay(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		ProjectPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + ProjectPeer::NUM_HYDRATE_COLUMNS;

		TeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + TeamPeer::NUM_HYDRATE_COLUMNS;

		EntryPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + EntryPeer::NUM_HYDRATE_COLUMNS;

		UserPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::USER_ID, UserPeer::ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Project rows

				$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = ProjectPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Project)
				$obj2->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Team rows

				$key3 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = TeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = TeamPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					TeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Team)
				$obj3->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Entry rows

				$key4 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = EntryPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = EntryPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					EntryPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Entry)
				$obj1->setEntry($obj4);

			} // if joined row is not null

				// Add objects for joined User rows

				$key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = UserPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = UserPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					UserPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (User)
				$obj5->addProjectEntry($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of ProjectEntry objects pre-filled with all related objects except User.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of ProjectEntry objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		ProjectEntryPeer::addSelectColumns($criteria);
		$startcol2 = ProjectEntryPeer::NUM_HYDRATE_COLUMNS;

		ProjectPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + ProjectPeer::NUM_HYDRATE_COLUMNS;

		TeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + TeamPeer::NUM_HYDRATE_COLUMNS;

		EntryPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + EntryPeer::NUM_HYDRATE_COLUMNS;

		DayPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + DayPeer::NUM_HYDRATE_COLUMNS;

		$criteria->addJoin(ProjectEntryPeer::PROJECT_ID, ProjectPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::TEAM_ID, TeamPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::ID, EntryPeer::ID, $join_behavior);

		$criteria->addJoin(ProjectEntryPeer::DAY_ID, DayPeer::ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = ProjectEntryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = ProjectEntryPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = ProjectEntryPeer::getOMClass();

				$obj1 = new $cls();
				$obj1->hydrate($row);
				ProjectEntryPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Project rows

				$key2 = ProjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = ProjectPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = ProjectPeer::getOMClass();

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					ProjectPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj2 (Project)
				$obj2->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Team rows

				$key3 = TeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = TeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = TeamPeer::getOMClass();

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					TeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj3 (Team)
				$obj3->addProjectEntry($obj1);

			} // if joined row is not null

				// Add objects for joined Entry rows

				$key4 = EntryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = EntryPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = EntryPeer::getOMClass();

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					EntryPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj4 (Entry)
				$obj1->setEntry($obj4);

			} // if joined row is not null

				// Add objects for joined Day rows

				$key5 = DayPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = DayPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = DayPeer::getOMClass();

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					DayPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (ProjectEntry) to the collection in $obj5 (Day)
				$obj5->addProjectEntry($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseProjectEntryPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseProjectEntryPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new ProjectEntryTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 *
	 * @return     string ClassName
	 */
	public static function getOMClass()
	{
		return ProjectEntryPeer::OM_CLASS;
	}

	/**
	 * Performs an INSERT on the database, given a ProjectEntry or Criteria object.
	 *
	 * @param      mixed $values Criteria or ProjectEntry object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from ProjectEntry object
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Performs an UPDATE on the database, given a ProjectEntry or Criteria object.
	 *
	 * @param      mixed $values Criteria or ProjectEntry object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(ProjectEntryPeer::ID);
			$value = $criteria->remove(ProjectEntryPeer::ID);
			if ($value) {
				$selectCriteria->add(ProjectEntryPeer::ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(ProjectEntryPeer::TABLE_NAME);
			}

		} else { // $values is ProjectEntry object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Deletes all rows from the projectentries table.
	 *
	 * @param      PropelPDO $con the connection to use
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll(PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(ProjectEntryPeer::TABLE_NAME, $con, ProjectEntryPeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			ProjectEntryPeer::clearInstancePool();
			ProjectEntryPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs a DELETE on the database, given a ProjectEntry or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or ProjectEntry object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			ProjectEntryPeer::clearInstancePool();
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof ProjectEntry) { // it's a model object
			// invalidate the cache for this single object
			ProjectEntryPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ProjectEntryPeer::ID, (array) $values, Criteria::IN);
			// invalidate the cache for this object(s)
			foreach ((array) $values as $singleval) {
				ProjectEntryPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			ProjectEntryPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given ProjectEntry object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      ProjectEntry $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate($obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ProjectEntryPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ProjectEntryPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(ProjectEntryPeer::DATABASE_NAME, ProjectEntryPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     ProjectEntry
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = ProjectEntryPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(ProjectEntryPeer::DATABASE_NAME);
		$criteria->add(ProjectEntryPeer::ID, $pk);

		$v = ProjectEntryPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProjectEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(ProjectEntryPeer::DATABASE_NAME);
			$criteria->add(ProjectEntryPeer::ID, $pks, Criteria::IN);
			$objs = ProjectEntryPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseProjectEntryPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseProjectEntryPeer::buildTableMap();

