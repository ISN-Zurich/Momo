<?php


/**
 * Base class that represents a row from the 'projects' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseProject extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'ProjectPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ProjectPeer
	 */
	protected static $peer;

	/**
	 * The flag var to prevent infinit loop in deep copy
	 * @var       boolean
	 */
	protected $startCopy = false;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the enabled field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $enabled;

	/**
	 * The value for the archived field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $archived;

	/**
	 * @var        array TeamProject[] Collection to store aggregation of TeamProject objects.
	 */
	protected $collTeamProjects;

	/**
	 * @var        array UserProject[] Collection to store aggregation of UserProject objects.
	 */
	protected $collUserProjects;

	/**
	 * @var        array ProjectEntry[] Collection to store aggregation of ProjectEntry objects.
	 */
	protected $collProjectEntrys;

	/**
	 * @var        array Team[] Collection to store aggregation of Team objects.
	 */
	protected $collTeams;

	/**
	 * @var        array User[] Collection to store aggregation of User objects.
	 */
	protected $collUsers;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $usersScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamProjectsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $userProjectsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $projectEntrysScheduledForDeletion = null;

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->enabled = true;
		$this->archived = false;
	}

	/**
	 * Initializes internal state of BaseProject object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return     string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the [enabled] column value.
	 * 
	 * @return     boolean
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Get the [archived] column value.
	 * 
	 * @return     boolean
	 */
	public function getArchived()
	{
		return $this->archived;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ProjectPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = ProjectPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Sets the value of the [enabled] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setEnabled($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->enabled !== $v) {
			$this->enabled = $v;
			$this->modifiedColumns[] = ProjectPeer::ENABLED;
		}

		return $this;
	} // setEnabled()

	/**
	 * Sets the value of the [archived] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     Project The current object (for fluent API support)
	 */
	public function setArchived($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->archived !== $v) {
			$this->archived = $v;
			$this->modifiedColumns[] = ProjectPeer::ARCHIVED;
		}

		return $this;
	} // setArchived()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			if ($this->enabled !== true) {
				return false;
			}

			if ($this->archived !== false) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->enabled = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
			$this->archived = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 4; // 4 = ProjectPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating Project object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = ProjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collTeamProjects = null;

			$this->collUserProjects = null;

			$this->collProjectEntrys = null;

			$this->collTeams = null;
			$this->collUsers = null;
		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = ProjectQuery::create()
				->filterByPrimaryKey($this->getPrimaryKey());
			$ret = $this->preDelete($con);
			if ($ret) {
				$deleteQuery->delete($con);
				$this->postDelete($con);
				$con->commit();
				$this->setDeleted(true);
			} else {
				$con->commit();
			}
		} catch (Exception $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				ProjectPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (Exception $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			if ($this->isNew() || $this->isModified()) {
				// persist changes
				if ($this->isNew()) {
					$this->doInsert($con);
				} else {
					$this->doUpdate($con);
				}
				$affectedRows += 1;
				$this->resetModified();
			}

			if ($this->teamsScheduledForDeletion !== null) {
				if (!$this->teamsScheduledForDeletion->isEmpty()) {
					TeamProjectQuery::create()
						->filterByPrimaryKeys($this->teamsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->teamsScheduledForDeletion = null;
				}

				foreach ($this->getTeams() as $team) {
					if ($team->isModified()) {
						$team->save($con);
					}
				}
			}

			if ($this->usersScheduledForDeletion !== null) {
				if (!$this->usersScheduledForDeletion->isEmpty()) {
					UserProjectQuery::create()
						->filterByPrimaryKeys($this->usersScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->usersScheduledForDeletion = null;
				}

				foreach ($this->getUsers() as $user) {
					if ($user->isModified()) {
						$user->save($con);
					}
				}
			}

			if ($this->teamProjectsScheduledForDeletion !== null) {
				if (!$this->teamProjectsScheduledForDeletion->isEmpty()) {
					TeamProjectQuery::create()
						->filterByPrimaryKeys($this->teamProjectsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->teamProjectsScheduledForDeletion = null;
				}
			}

			if ($this->collTeamProjects !== null) {
				foreach ($this->collTeamProjects as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->userProjectsScheduledForDeletion !== null) {
				if (!$this->userProjectsScheduledForDeletion->isEmpty()) {
					UserProjectQuery::create()
						->filterByPrimaryKeys($this->userProjectsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->userProjectsScheduledForDeletion = null;
				}
			}

			if ($this->collUserProjects !== null) {
				foreach ($this->collUserProjects as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->projectEntrysScheduledForDeletion !== null) {
				if (!$this->projectEntrysScheduledForDeletion->isEmpty()) {
					ProjectEntryQuery::create()
						->filterByPrimaryKeys($this->projectEntrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->projectEntrysScheduledForDeletion = null;
				}
			}

			if ($this->collProjectEntrys !== null) {
				foreach ($this->collProjectEntrys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Insert the row in the database.
	 *
	 * @param      PropelPDO $con
	 *
	 * @throws     PropelException
	 * @see        doSave()
	 */
	protected function doInsert(PropelPDO $con)
	{
		$modifiedColumns = array();
		$index = 0;

		$this->modifiedColumns[] = ProjectPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . ProjectPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(ProjectPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(ProjectPeer::NAME)) {
			$modifiedColumns[':p' . $index++]  = '`NAME`';
		}
		if ($this->isColumnModified(ProjectPeer::ENABLED)) {
			$modifiedColumns[':p' . $index++]  = '`ENABLED`';
		}
		if ($this->isColumnModified(ProjectPeer::ARCHIVED)) {
			$modifiedColumns[':p' . $index++]  = '`ARCHIVED`';
		}

		$sql = sprintf(
			'INSERT INTO `projects` (%s) VALUES (%s)',
			implode(', ', $modifiedColumns),
			implode(', ', array_keys($modifiedColumns))
		);

		try {
			$stmt = $con->prepare($sql);
			foreach ($modifiedColumns as $identifier => $columnName) {
				switch ($columnName) {
					case '`ID`':						
						$stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
						break;
					case '`NAME`':						
						$stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
						break;
					case '`ENABLED`':
						$stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
						break;
					case '`ARCHIVED`':
						$stmt->bindValue($identifier, (int) $this->archived, PDO::PARAM_INT);
						break;
				}
			}
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
		}

		try {
			$pk = $con->lastInsertId();
		} catch (Exception $e) {
			throw new PropelException('Unable to get autoincrement id.', $e);
		}
		$this->setId($pk);

		$this->setNew(false);
	}

	/**
	 * Update the row in the database.
	 *
	 * @param      PropelPDO $con
	 *
	 * @see        doSave()
	 */
	protected function doUpdate(PropelPDO $con)
	{
		$selectCriteria = $this->buildPkeyCriteria();
		$valuesCriteria = $this->buildCriteria();
		BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
	}

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = ProjectPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collTeamProjects !== null) {
					foreach ($this->collTeamProjects as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUserProjects !== null) {
					foreach ($this->collUserProjects as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collProjectEntrys !== null) {
					foreach ($this->collProjectEntrys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                     Defaults to BasePeer::TYPE_PHPNAME
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getEnabled();
				break;
			case 3:
				return $this->getArchived();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                    Defaults to BasePeer::TYPE_PHPNAME.
	 * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
	 * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
	 * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
	{
		if (isset($alreadyDumpedObjects['Project'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['Project'][$this->getPrimaryKey()] = true;
		$keys = ProjectPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getEnabled(),
			$keys[3] => $this->getArchived(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->collTeamProjects) {
				$result['TeamProjects'] = $this->collTeamProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collUserProjects) {
				$result['UserProjects'] = $this->collUserProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collProjectEntrys) {
				$result['ProjectEntrys'] = $this->collProjectEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
		}
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                     Defaults to BasePeer::TYPE_PHPNAME
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setEnabled($value);
				break;
			case 3:
				$this->setArchived($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's BasePeer::TYPE_PHPNAME
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProjectPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setEnabled($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setArchived($arr[$keys[3]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ProjectPeer::DATABASE_NAME);

		if ($this->isColumnModified(ProjectPeer::ID)) $criteria->add(ProjectPeer::ID, $this->id);
		if ($this->isColumnModified(ProjectPeer::NAME)) $criteria->add(ProjectPeer::NAME, $this->name);
		if ($this->isColumnModified(ProjectPeer::ENABLED)) $criteria->add(ProjectPeer::ENABLED, $this->enabled);
		if ($this->isColumnModified(ProjectPeer::ARCHIVED)) $criteria->add(ProjectPeer::ARCHIVED, $this->archived);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ProjectPeer::DATABASE_NAME);
		$criteria->add(ProjectPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Project (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setName($this->getName());
		$copyObj->setEnabled($this->getEnabled());
		$copyObj->setArchived($this->getArchived());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getTeamProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTeamProject($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getUserProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addUserProject($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getProjectEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addProjectEntry($relObj->copy($deepCopy));
				}
			}

			//unflag object copy
			$this->startCopy = false;
		} // if ($deepCopy)

		if ($makeNew) {
			$copyObj->setNew(true);
			$copyObj->setId(NULL); // this is a auto-increment column, so set to default value
		}
	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Project Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     ProjectPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ProjectPeer();
		}
		return self::$peer;
	}


	/**
	 * Initializes a collection based on the name of a relation.
	 * Avoids crafting an 'init[$relationName]s' method name
	 * that wouldn't work when StandardEnglishPluralizer is used.
	 *
	 * @param      string $relationName The name of the relation to initialize
	 * @return     void
	 */
	public function initRelation($relationName)
	{
		if ('TeamProject' == $relationName) {
			return $this->initTeamProjects();
		}
		if ('UserProject' == $relationName) {
			return $this->initUserProjects();
		}
		if ('ProjectEntry' == $relationName) {
			return $this->initProjectEntrys();
		}
	}

	/**
	 * Clears out the collTeamProjects collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTeamProjects()
	 */
	public function clearTeamProjects()
	{
		$this->collTeamProjects = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTeamProjects collection.
	 *
	 * By default this just sets the collTeamProjects collection to an empty array (like clearcollTeamProjects());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initTeamProjects($overrideExisting = true)
	{
		if (null !== $this->collTeamProjects && !$overrideExisting) {
			return;
		}
		$this->collTeamProjects = new PropelObjectCollection();
		$this->collTeamProjects->setModel('TeamProject');
	}

	/**
	 * Gets an array of TeamProject objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Project is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array TeamProject[] List of TeamProject objects
	 * @throws     PropelException
	 */
	public function getTeamProjects($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collTeamProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamProjects) {
				// return empty collection
				$this->initTeamProjects();
			} else {
				$collTeamProjects = TeamProjectQuery::create(null, $criteria)
					->filterByProject($this)
					->find($con);
				if (null !== $criteria) {
					return $collTeamProjects;
				}
				$this->collTeamProjects = $collTeamProjects;
			}
		}
		return $this->collTeamProjects;
	}

	/**
	 * Sets a collection of TeamProject objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $teamProjects A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTeamProjects(PropelCollection $teamProjects, PropelPDO $con = null)
	{
		$this->teamProjectsScheduledForDeletion = $this->getTeamProjects(new Criteria(), $con)->diff($teamProjects);

		foreach ($teamProjects as $teamProject) {
			// Fix issue with collection modified by reference
			if ($teamProject->isNew()) {
				$teamProject->setProject($this);
			}
			$this->addTeamProject($teamProject);
		}

		$this->collTeamProjects = $teamProjects;
	}

	/**
	 * Returns the number of related TeamProject objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related TeamProject objects.
	 * @throws     PropelException
	 */
	public function countTeamProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collTeamProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamProjects) {
				return 0;
			} else {
				$query = TeamProjectQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByProject($this)
					->count($con);
			}
		} else {
			return count($this->collTeamProjects);
		}
	}

	/**
	 * Method called to associate a TeamProject object to this object
	 * through the TeamProject foreign key attribute.
	 *
	 * @param      TeamProject $l TeamProject
	 * @return     Project The current object (for fluent API support)
	 */
	public function addTeamProject(TeamProject $l)
	{
		if ($this->collTeamProjects === null) {
			$this->initTeamProjects();
		}
		if (!$this->collTeamProjects->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddTeamProject($l);
		}

		return $this;
	}

	/**
	 * @param	TeamProject $teamProject The teamProject object to add.
	 */
	protected function doAddTeamProject($teamProject)
	{
		$this->collTeamProjects[]= $teamProject;
		$teamProject->setProject($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related TeamProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array TeamProject[] List of TeamProject objects
	 */
	public function getTeamProjectsJoinTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TeamProjectQuery::create(null, $criteria);
		$query->joinWith('Team', $join_behavior);

		return $this->getTeamProjects($query, $con);
	}

	/**
	 * Clears out the collUserProjects collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addUserProjects()
	 */
	public function clearUserProjects()
	{
		$this->collUserProjects = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collUserProjects collection.
	 *
	 * By default this just sets the collUserProjects collection to an empty array (like clearcollUserProjects());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initUserProjects($overrideExisting = true)
	{
		if (null !== $this->collUserProjects && !$overrideExisting) {
			return;
		}
		$this->collUserProjects = new PropelObjectCollection();
		$this->collUserProjects->setModel('UserProject');
	}

	/**
	 * Gets an array of UserProject objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Project is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array UserProject[] List of UserProject objects
	 * @throws     PropelException
	 */
	public function getUserProjects($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collUserProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collUserProjects) {
				// return empty collection
				$this->initUserProjects();
			} else {
				$collUserProjects = UserProjectQuery::create(null, $criteria)
					->filterByProject($this)
					->find($con);
				if (null !== $criteria) {
					return $collUserProjects;
				}
				$this->collUserProjects = $collUserProjects;
			}
		}
		return $this->collUserProjects;
	}

	/**
	 * Sets a collection of UserProject objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $userProjects A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setUserProjects(PropelCollection $userProjects, PropelPDO $con = null)
	{
		$this->userProjectsScheduledForDeletion = $this->getUserProjects(new Criteria(), $con)->diff($userProjects);

		foreach ($userProjects as $userProject) {
			// Fix issue with collection modified by reference
			if ($userProject->isNew()) {
				$userProject->setProject($this);
			}
			$this->addUserProject($userProject);
		}

		$this->collUserProjects = $userProjects;
	}

	/**
	 * Returns the number of related UserProject objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related UserProject objects.
	 * @throws     PropelException
	 */
	public function countUserProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collUserProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collUserProjects) {
				return 0;
			} else {
				$query = UserProjectQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByProject($this)
					->count($con);
			}
		} else {
			return count($this->collUserProjects);
		}
	}

	/**
	 * Method called to associate a UserProject object to this object
	 * through the UserProject foreign key attribute.
	 *
	 * @param      UserProject $l UserProject
	 * @return     Project The current object (for fluent API support)
	 */
	public function addUserProject(UserProject $l)
	{
		if ($this->collUserProjects === null) {
			$this->initUserProjects();
		}
		if (!$this->collUserProjects->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddUserProject($l);
		}

		return $this;
	}

	/**
	 * @param	UserProject $userProject The userProject object to add.
	 */
	protected function doAddUserProject($userProject)
	{
		$this->collUserProjects[]= $userProject;
		$userProject->setProject($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related UserProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array UserProject[] List of UserProject objects
	 */
	public function getUserProjectsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = UserProjectQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getUserProjects($query, $con);
	}

	/**
	 * Clears out the collProjectEntrys collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addProjectEntrys()
	 */
	public function clearProjectEntrys()
	{
		$this->collProjectEntrys = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collProjectEntrys collection.
	 *
	 * By default this just sets the collProjectEntrys collection to an empty array (like clearcollProjectEntrys());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initProjectEntrys($overrideExisting = true)
	{
		if (null !== $this->collProjectEntrys && !$overrideExisting) {
			return;
		}
		$this->collProjectEntrys = new PropelObjectCollection();
		$this->collProjectEntrys->setModel('ProjectEntry');
	}

	/**
	 * Gets an array of ProjectEntry objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Project is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 * @throws     PropelException
	 */
	public function getProjectEntrys($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collProjectEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collProjectEntrys) {
				// return empty collection
				$this->initProjectEntrys();
			} else {
				$collProjectEntrys = ProjectEntryQuery::create(null, $criteria)
					->filterByProject($this)
					->find($con);
				if (null !== $criteria) {
					return $collProjectEntrys;
				}
				$this->collProjectEntrys = $collProjectEntrys;
			}
		}
		return $this->collProjectEntrys;
	}

	/**
	 * Sets a collection of ProjectEntry objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $projectEntrys A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setProjectEntrys(PropelCollection $projectEntrys, PropelPDO $con = null)
	{
		$this->projectEntrysScheduledForDeletion = $this->getProjectEntrys(new Criteria(), $con)->diff($projectEntrys);

		foreach ($projectEntrys as $projectEntry) {
			// Fix issue with collection modified by reference
			if ($projectEntry->isNew()) {
				$projectEntry->setProject($this);
			}
			$this->addProjectEntry($projectEntry);
		}

		$this->collProjectEntrys = $projectEntrys;
	}

	/**
	 * Returns the number of related ProjectEntry objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related ProjectEntry objects.
	 * @throws     PropelException
	 */
	public function countProjectEntrys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collProjectEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collProjectEntrys) {
				return 0;
			} else {
				$query = ProjectEntryQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByProject($this)
					->count($con);
			}
		} else {
			return count($this->collProjectEntrys);
		}
	}

	/**
	 * Method called to associate a ProjectEntry object to this object
	 * through the ProjectEntry foreign key attribute.
	 *
	 * @param      ProjectEntry $l ProjectEntry
	 * @return     Project The current object (for fluent API support)
	 */
	public function addProjectEntry(ProjectEntry $l)
	{
		if ($this->collProjectEntrys === null) {
			$this->initProjectEntrys();
		}
		if (!$this->collProjectEntrys->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddProjectEntry($l);
		}

		return $this;
	}

	/**
	 * @param	ProjectEntry $projectEntry The projectEntry object to add.
	 */
	protected function doAddProjectEntry($projectEntry)
	{
		$this->collProjectEntrys[]= $projectEntry;
		$projectEntry->setProject($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 */
	public function getProjectEntrysJoinTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = ProjectEntryQuery::create(null, $criteria);
		$query->joinWith('Team', $join_behavior);

		return $this->getProjectEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 */
	public function getProjectEntrysJoinEntry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = ProjectEntryQuery::create(null, $criteria);
		$query->joinWith('Entry', $join_behavior);

		return $this->getProjectEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 */
	public function getProjectEntrysJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = ProjectEntryQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

		return $this->getProjectEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Project is new, it will return
	 * an empty collection; or if this Project has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Project.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 */
	public function getProjectEntrysJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = ProjectEntryQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getProjectEntrys($query, $con);
	}

	/**
	 * Clears out the collTeams collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTeams()
	 */
	public function clearTeams()
	{
		$this->collTeams = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTeams collection.
	 *
	 * By default this just sets the collTeams collection to an empty collection (like clearTeams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initTeams()
	{
		$this->collTeams = new PropelObjectCollection();
		$this->collTeams->setModel('Team');
	}

	/**
	 * Gets a collection of Team objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Project is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     PropelCollection|array Team[] List of Team objects
	 */
	public function getTeams($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collTeams || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeams) {
				// return empty collection
				$this->initTeams();
			} else {
				$collTeams = TeamQuery::create(null, $criteria)
					->filterByProject($this)
					->find($con);
				if (null !== $criteria) {
					return $collTeams;
				}
				$this->collTeams = $collTeams;
			}
		}
		return $this->collTeams;
	}

	/**
	 * Sets a collection of Team objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $teams A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTeams(PropelCollection $teams, PropelPDO $con = null)
	{
		$teamProjects = TeamProjectQuery::create()
			->filterByTeam($teams)
			->filterByProject($this)
			->find($con);

		$currentTeamProjects = $this->getTeamProjects();

		$this->teamsScheduledForDeletion = $currentTeamProjects->diff($teamProjects);
		$this->collTeamProjects = $teamProjects;

		foreach ($teams as $team) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentTeamProjects as $teamProject) {
				if ($teamProject->getTeam() == $team) {
					$isInCurrent = true;
					break;
				}
			}
			if ($isInCurrent) {
				continue;
			}

			// Fix issue with collection modified by reference
			if ($team->isNew()) {
				$this->doAddTeam($team);
			} else {
				$this->addTeam($team);
			}
		}

		$this->collTeams = $teams;
	}

	/**
	 * Gets the number of Team objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      boolean $distinct Set to true to force count distinct
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     int the number of related Team objects
	 */
	public function countTeams($criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collTeams || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeams) {
				return 0;
			} else {
				$query = TeamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByProject($this)
					->count($con);
			}
		} else {
			return count($this->collTeams);
		}
	}

	/**
	 * Associate a Team object to this object
	 * through the teams_projects cross reference table.
	 *
	 * @param      Team $team The TeamProject object to relate
	 * @return     void
	 */
	public function addTeam(Team $team)
	{
		if ($this->collTeams === null) {
			$this->initTeams();
		}
		if (!$this->collTeams->contains($team)) { // only add it if the **same** object is not already associated
			$this->doAddTeam($team);

			$this->collTeams[]= $team;
		}
	}

	/**
	 * @param	Team $team The team object to add.
	 */
	protected function doAddTeam($team)
	{
		$teamProject = new TeamProject();
		$teamProject->setTeam($team);
		$this->addTeamProject($teamProject);
	}

	/**
	 * Clears out the collUsers collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addUsers()
	 */
	public function clearUsers()
	{
		$this->collUsers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collUsers collection.
	 *
	 * By default this just sets the collUsers collection to an empty collection (like clearUsers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initUsers()
	{
		$this->collUsers = new PropelObjectCollection();
		$this->collUsers->setModel('User');
	}

	/**
	 * Gets a collection of User objects related by a many-to-many relationship
	 * to the current object by way of the users_projects cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Project is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     PropelCollection|array User[] List of User objects
	 */
	public function getUsers($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collUsers || null !== $criteria) {
			if ($this->isNew() && null === $this->collUsers) {
				// return empty collection
				$this->initUsers();
			} else {
				$collUsers = UserQuery::create(null, $criteria)
					->filterByProject($this)
					->find($con);
				if (null !== $criteria) {
					return $collUsers;
				}
				$this->collUsers = $collUsers;
			}
		}
		return $this->collUsers;
	}

	/**
	 * Sets a collection of User objects related by a many-to-many relationship
	 * to the current object by way of the users_projects cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $users A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setUsers(PropelCollection $users, PropelPDO $con = null)
	{
		$userProjects = UserProjectQuery::create()
			->filterByUser($users)
			->filterByProject($this)
			->find($con);

		$currentUserProjects = $this->getUserProjects();

		$this->usersScheduledForDeletion = $currentUserProjects->diff($userProjects);
		$this->collUserProjects = $userProjects;

		foreach ($users as $user) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentUserProjects as $userProject) {
				if ($userProject->getUser() == $user) {
					$isInCurrent = true;
					break;
				}
			}
			if ($isInCurrent) {
				continue;
			}

			// Fix issue with collection modified by reference
			if ($user->isNew()) {
				$this->doAddUser($user);
			} else {
				$this->addUser($user);
			}
		}

		$this->collUsers = $users;
	}

	/**
	 * Gets the number of User objects related by a many-to-many relationship
	 * to the current object by way of the users_projects cross-reference table.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      boolean $distinct Set to true to force count distinct
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     int the number of related User objects
	 */
	public function countUsers($criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collUsers || null !== $criteria) {
			if ($this->isNew() && null === $this->collUsers) {
				return 0;
			} else {
				$query = UserQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByProject($this)
					->count($con);
			}
		} else {
			return count($this->collUsers);
		}
	}

	/**
	 * Associate a User object to this object
	 * through the users_projects cross reference table.
	 *
	 * @param      User $user The UserProject object to relate
	 * @return     void
	 */
	public function addUser(User $user)
	{
		if ($this->collUsers === null) {
			$this->initUsers();
		}
		if (!$this->collUsers->contains($user)) { // only add it if the **same** object is not already associated
			$this->doAddUser($user);

			$this->collUsers[]= $user;
		}
	}

	/**
	 * @param	User $user The user object to add.
	 */
	protected function doAddUser($user)
	{
		$userProject = new UserProject();
		$userProject->setUser($user);
		$this->addUserProject($userProject);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->name = null;
		$this->enabled = null;
		$this->archived = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
		$this->applyDefaultValues();
		$this->resetModified();
		$this->setNew(true);
		$this->setDeleted(false);
	}

	/**
	 * Resets all references to other model objects or collections of model objects.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect
	 * objects with circular references (even in PHP 5.3). This is currently necessary
	 * when using Propel in certain daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all referrer objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collTeamProjects) {
				foreach ($this->collTeamProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collUserProjects) {
				foreach ($this->collUserProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collProjectEntrys) {
				foreach ($this->collProjectEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTeams) {
				foreach ($this->collTeams as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collUsers) {
				foreach ($this->collUsers as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collTeamProjects instanceof PropelCollection) {
			$this->collTeamProjects->clearIterator();
		}
		$this->collTeamProjects = null;
		if ($this->collUserProjects instanceof PropelCollection) {
			$this->collUserProjects->clearIterator();
		}
		$this->collUserProjects = null;
		if ($this->collProjectEntrys instanceof PropelCollection) {
			$this->collProjectEntrys->clearIterator();
		}
		$this->collProjectEntrys = null;
		if ($this->collTeams instanceof PropelCollection) {
			$this->collTeams->clearIterator();
		}
		$this->collTeams = null;
		if ($this->collUsers instanceof PropelCollection) {
			$this->collUsers->clearIterator();
		}
		$this->collUsers = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(ProjectPeer::DEFAULT_STRING_FORMAT);
	}

} // BaseProject
