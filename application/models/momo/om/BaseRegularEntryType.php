<?php


/**
 * Base class that represents a row from the 'regularentrytypes' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseRegularEntryType extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'RegularEntryTypePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        RegularEntryTypePeer
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
	 * The value for the type field.
	 * @var        string
	 */
	protected $type;

	/**
	 * The value for the creator field.
	 * @var        string
	 */
	protected $creator;

	/**
	 * The value for the worktimecreditawarded field.
	 * @var        boolean
	 */
	protected $worktimecreditawarded;

	/**
	 * The value for the enabled field.
	 * @var        boolean
	 */
	protected $enabled;

	/**
	 * The value for the defaulttype field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $defaulttype;

	/**
	 * @var        array RegularEntry[] Collection to store aggregation of RegularEntry objects.
	 */
	protected $collRegularEntrys;

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
	protected $regularEntrysScheduledForDeletion = null;

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->defaulttype = false;
	}

	/**
	 * Initializes internal state of BaseRegularEntryType object.
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
	 * Get the [type] column value.
	 * 
	 * @return     string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the [creator] column value.
	 * 
	 * @return     string
	 */
	public function getCreator()
	{
		return $this->creator;
	}

	/**
	 * Get the [worktimecreditawarded] column value.
	 * 
	 * @return     boolean
	 */
	public function getWorktimecreditawarded()
	{
		return $this->worktimecreditawarded;
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
	 * Get the [defaulttype] column value.
	 * 
	 * @return     boolean
	 */
	public function getDefaulttype()
	{
		return $this->defaulttype;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = RegularEntryTypePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function setType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = RegularEntryTypePeer::TYPE;
		}

		return $this;
	} // setType()

	/**
	 * Set the value of [creator] column.
	 * 
	 * @param      string $v new value
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function setCreator($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creator !== $v) {
			$this->creator = $v;
			$this->modifiedColumns[] = RegularEntryTypePeer::CREATOR;
		}

		return $this;
	} // setCreator()

	/**
	 * Sets the value of the [worktimecreditawarded] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function setWorktimecreditawarded($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->worktimecreditawarded !== $v) {
			$this->worktimecreditawarded = $v;
			$this->modifiedColumns[] = RegularEntryTypePeer::WORKTIMECREDITAWARDED;
		}

		return $this;
	} // setWorktimecreditawarded()

	/**
	 * Sets the value of the [enabled] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     RegularEntryType The current object (for fluent API support)
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
			$this->modifiedColumns[] = RegularEntryTypePeer::ENABLED;
		}

		return $this;
	} // setEnabled()

	/**
	 * Sets the value of the [defaulttype] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function setDefaulttype($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->defaulttype !== $v) {
			$this->defaulttype = $v;
			$this->modifiedColumns[] = RegularEntryTypePeer::DEFAULTTYPE;
		}

		return $this;
	} // setDefaulttype()

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
			if ($this->defaulttype !== false) {
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
			$this->type = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->creator = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->worktimecreditawarded = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->enabled = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->defaulttype = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 6; // 6 = RegularEntryTypePeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating RegularEntryType object", $e);
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
			$con = Propel::getConnection(RegularEntryTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = RegularEntryTypePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collRegularEntrys = null;

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
			$con = Propel::getConnection(RegularEntryTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = RegularEntryTypeQuery::create()
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
			$con = Propel::getConnection(RegularEntryTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				RegularEntryTypePeer::addInstanceToPool($this);
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

			if ($this->regularEntrysScheduledForDeletion !== null) {
				if (!$this->regularEntrysScheduledForDeletion->isEmpty()) {
					RegularEntryQuery::create()
						->filterByPrimaryKeys($this->regularEntrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->regularEntrysScheduledForDeletion = null;
				}
			}

			if ($this->collRegularEntrys !== null) {
				foreach ($this->collRegularEntrys as $referrerFK) {
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

		$this->modifiedColumns[] = RegularEntryTypePeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . RegularEntryTypePeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(RegularEntryTypePeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(RegularEntryTypePeer::TYPE)) {
			$modifiedColumns[':p' . $index++]  = '`TYPE`';
		}
		if ($this->isColumnModified(RegularEntryTypePeer::CREATOR)) {
			$modifiedColumns[':p' . $index++]  = '`CREATOR`';
		}
		if ($this->isColumnModified(RegularEntryTypePeer::WORKTIMECREDITAWARDED)) {
			$modifiedColumns[':p' . $index++]  = '`WORKTIMECREDITAWARDED`';
		}
		if ($this->isColumnModified(RegularEntryTypePeer::ENABLED)) {
			$modifiedColumns[':p' . $index++]  = '`ENABLED`';
		}
		if ($this->isColumnModified(RegularEntryTypePeer::DEFAULTTYPE)) {
			$modifiedColumns[':p' . $index++]  = '`DEFAULTTYPE`';
		}

		$sql = sprintf(
			'INSERT INTO `regularentrytypes` (%s) VALUES (%s)',
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
					case '`TYPE`':						
						$stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
						break;
					case '`CREATOR`':						
						$stmt->bindValue($identifier, $this->creator, PDO::PARAM_STR);
						break;
					case '`WORKTIMECREDITAWARDED`':
						$stmt->bindValue($identifier, (int) $this->worktimecreditawarded, PDO::PARAM_INT);
						break;
					case '`ENABLED`':
						$stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
						break;
					case '`DEFAULTTYPE`':
						$stmt->bindValue($identifier, (int) $this->defaulttype, PDO::PARAM_INT);
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


			if (($retval = RegularEntryTypePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collRegularEntrys !== null) {
					foreach ($this->collRegularEntrys as $referrerFK) {
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
		$pos = RegularEntryTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getType();
				break;
			case 2:
				return $this->getCreator();
				break;
			case 3:
				return $this->getWorktimecreditawarded();
				break;
			case 4:
				return $this->getEnabled();
				break;
			case 5:
				return $this->getDefaulttype();
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
		if (isset($alreadyDumpedObjects['RegularEntryType'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['RegularEntryType'][$this->getPrimaryKey()] = true;
		$keys = RegularEntryTypePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getType(),
			$keys[2] => $this->getCreator(),
			$keys[3] => $this->getWorktimecreditawarded(),
			$keys[4] => $this->getEnabled(),
			$keys[5] => $this->getDefaulttype(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->collRegularEntrys) {
				$result['RegularEntrys'] = $this->collRegularEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = RegularEntryTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setType($value);
				break;
			case 2:
				$this->setCreator($value);
				break;
			case 3:
				$this->setWorktimecreditawarded($value);
				break;
			case 4:
				$this->setEnabled($value);
				break;
			case 5:
				$this->setDefaulttype($value);
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
		$keys = RegularEntryTypePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCreator($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setWorktimecreditawarded($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setEnabled($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setDefaulttype($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RegularEntryTypePeer::DATABASE_NAME);

		if ($this->isColumnModified(RegularEntryTypePeer::ID)) $criteria->add(RegularEntryTypePeer::ID, $this->id);
		if ($this->isColumnModified(RegularEntryTypePeer::TYPE)) $criteria->add(RegularEntryTypePeer::TYPE, $this->type);
		if ($this->isColumnModified(RegularEntryTypePeer::CREATOR)) $criteria->add(RegularEntryTypePeer::CREATOR, $this->creator);
		if ($this->isColumnModified(RegularEntryTypePeer::WORKTIMECREDITAWARDED)) $criteria->add(RegularEntryTypePeer::WORKTIMECREDITAWARDED, $this->worktimecreditawarded);
		if ($this->isColumnModified(RegularEntryTypePeer::ENABLED)) $criteria->add(RegularEntryTypePeer::ENABLED, $this->enabled);
		if ($this->isColumnModified(RegularEntryTypePeer::DEFAULTTYPE)) $criteria->add(RegularEntryTypePeer::DEFAULTTYPE, $this->defaulttype);

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
		$criteria = new Criteria(RegularEntryTypePeer::DATABASE_NAME);
		$criteria->add(RegularEntryTypePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of RegularEntryType (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setType($this->getType());
		$copyObj->setCreator($this->getCreator());
		$copyObj->setWorktimecreditawarded($this->getWorktimecreditawarded());
		$copyObj->setEnabled($this->getEnabled());
		$copyObj->setDefaulttype($this->getDefaulttype());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getRegularEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addRegularEntry($relObj->copy($deepCopy));
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
	 * @return     RegularEntryType Clone of current object.
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
	 * @return     RegularEntryTypePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RegularEntryTypePeer();
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
		if ('RegularEntry' == $relationName) {
			return $this->initRegularEntrys();
		}
	}

	/**
	 * Clears out the collRegularEntrys collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addRegularEntrys()
	 */
	public function clearRegularEntrys()
	{
		$this->collRegularEntrys = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collRegularEntrys collection.
	 *
	 * By default this just sets the collRegularEntrys collection to an empty array (like clearcollRegularEntrys());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initRegularEntrys($overrideExisting = true)
	{
		if (null !== $this->collRegularEntrys && !$overrideExisting) {
			return;
		}
		$this->collRegularEntrys = new PropelObjectCollection();
		$this->collRegularEntrys->setModel('RegularEntry');
	}

	/**
	 * Gets an array of RegularEntry objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this RegularEntryType is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array RegularEntry[] List of RegularEntry objects
	 * @throws     PropelException
	 */
	public function getRegularEntrys($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collRegularEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collRegularEntrys) {
				// return empty collection
				$this->initRegularEntrys();
			} else {
				$collRegularEntrys = RegularEntryQuery::create(null, $criteria)
					->filterByRegularEntryType($this)
					->find($con);
				if (null !== $criteria) {
					return $collRegularEntrys;
				}
				$this->collRegularEntrys = $collRegularEntrys;
			}
		}
		return $this->collRegularEntrys;
	}

	/**
	 * Sets a collection of RegularEntry objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $regularEntrys A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setRegularEntrys(PropelCollection $regularEntrys, PropelPDO $con = null)
	{
		$this->regularEntrysScheduledForDeletion = $this->getRegularEntrys(new Criteria(), $con)->diff($regularEntrys);

		foreach ($regularEntrys as $regularEntry) {
			// Fix issue with collection modified by reference
			if ($regularEntry->isNew()) {
				$regularEntry->setRegularEntryType($this);
			}
			$this->addRegularEntry($regularEntry);
		}

		$this->collRegularEntrys = $regularEntrys;
	}

	/**
	 * Returns the number of related RegularEntry objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related RegularEntry objects.
	 * @throws     PropelException
	 */
	public function countRegularEntrys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collRegularEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collRegularEntrys) {
				return 0;
			} else {
				$query = RegularEntryQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByRegularEntryType($this)
					->count($con);
			}
		} else {
			return count($this->collRegularEntrys);
		}
	}

	/**
	 * Method called to associate a RegularEntry object to this object
	 * through the RegularEntry foreign key attribute.
	 *
	 * @param      RegularEntry $l RegularEntry
	 * @return     RegularEntryType The current object (for fluent API support)
	 */
	public function addRegularEntry(RegularEntry $l)
	{
		if ($this->collRegularEntrys === null) {
			$this->initRegularEntrys();
		}
		if (!$this->collRegularEntrys->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddRegularEntry($l);
		}

		return $this;
	}

	/**
	 * @param	RegularEntry $regularEntry The regularEntry object to add.
	 */
	protected function doAddRegularEntry($regularEntry)
	{
		$this->collRegularEntrys[]= $regularEntry;
		$regularEntry->setRegularEntryType($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this RegularEntryType is new, it will return
	 * an empty collection; or if this RegularEntryType has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in RegularEntryType.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array RegularEntry[] List of RegularEntry objects
	 */
	public function getRegularEntrysJoinEntry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = RegularEntryQuery::create(null, $criteria);
		$query->joinWith('Entry', $join_behavior);

		return $this->getRegularEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this RegularEntryType is new, it will return
	 * an empty collection; or if this RegularEntryType has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in RegularEntryType.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array RegularEntry[] List of RegularEntry objects
	 */
	public function getRegularEntrysJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = RegularEntryQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

		return $this->getRegularEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this RegularEntryType is new, it will return
	 * an empty collection; or if this RegularEntryType has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in RegularEntryType.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array RegularEntry[] List of RegularEntry objects
	 */
	public function getRegularEntrysJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = RegularEntryQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getRegularEntrys($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->type = null;
		$this->creator = null;
		$this->worktimecreditawarded = null;
		$this->enabled = null;
		$this->defaulttype = null;
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
			if ($this->collRegularEntrys) {
				foreach ($this->collRegularEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collRegularEntrys instanceof PropelCollection) {
			$this->collRegularEntrys->clearIterator();
		}
		$this->collRegularEntrys = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(RegularEntryTypePeer::DEFAULT_STRING_FORMAT);
	}

} // BaseRegularEntryType
