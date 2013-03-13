<?php


/**
 * Base class that represents a row from the 'entries' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseEntry extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'EntryPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        EntryPeer
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
	 * The value for the day_id field.
	 * @var        int
	 */
	protected $day_id;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the descendant_class field.
	 * @var        string
	 */
	protected $descendant_class;

	/**
	 * @var        Day
	 */
	protected $aDay;

	/**
	 * @var        User
	 */
	protected $aUser;

	/**
	 * @var        RegularEntry one-to-one related RegularEntry object
	 */
	protected $singleRegularEntry;

	/**
	 * @var        ProjectEntry one-to-one related ProjectEntry object
	 */
	protected $singleProjectEntry;

	/**
	 * @var        OOEntry one-to-one related OOEntry object
	 */
	protected $singleOOEntry;

	/**
	 * @var        AdjustmentEntry one-to-one related AdjustmentEntry object
	 */
	protected $singleAdjustmentEntry;

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
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $projectEntrysScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $oOEntrysScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $adjustmentEntrysScheduledForDeletion = null;

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
	 * Get the [day_id] column value.
	 * 
	 * @return     int
	 */
	public function getDayId()
	{
		return $this->day_id;
	}

	/**
	 * Get the [user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * Get the [descendant_class] column value.
	 * 
	 * @return     string
	 */
	public function getDescendantClass()
	{
		return $this->descendant_class;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Entry The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = EntryPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [day_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Entry The current object (for fluent API support)
	 */
	public function setDayId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->day_id !== $v) {
			$this->day_id = $v;
			$this->modifiedColumns[] = EntryPeer::DAY_ID;
		}

		if ($this->aDay !== null && $this->aDay->getId() !== $v) {
			$this->aDay = null;
		}

		return $this;
	} // setDayId()

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Entry The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = EntryPeer::USER_ID;
		}

		if ($this->aUser !== null && $this->aUser->getId() !== $v) {
			$this->aUser = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [descendant_class] column.
	 * 
	 * @param      string $v new value
	 * @return     Entry The current object (for fluent API support)
	 */
	public function setDescendantClass($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->descendant_class !== $v) {
			$this->descendant_class = $v;
			$this->modifiedColumns[] = EntryPeer::DESCENDANT_CLASS;
		}

		return $this;
	} // setDescendantClass()

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
			$this->day_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->user_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->descendant_class = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 4; // 4 = EntryPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating Entry object", $e);
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

		if ($this->aDay !== null && $this->day_id !== $this->aDay->getId()) {
			$this->aDay = null;
		}
		if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
			$this->aUser = null;
		}
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
			$con = Propel::getConnection(EntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = EntryPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aDay = null;
			$this->aUser = null;
			$this->singleRegularEntry = null;

			$this->singleProjectEntry = null;

			$this->singleOOEntry = null;

			$this->singleAdjustmentEntry = null;

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
			$con = Propel::getConnection(EntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = EntryQuery::create()
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
			$con = Propel::getConnection(EntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				EntryPeer::addInstanceToPool($this);
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

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aDay !== null) {
				if ($this->aDay->isModified() || $this->aDay->isNew()) {
					$affectedRows += $this->aDay->save($con);
				}
				$this->setDay($this->aDay);
			}

			if ($this->aUser !== null) {
				if ($this->aUser->isModified() || $this->aUser->isNew()) {
					$affectedRows += $this->aUser->save($con);
				}
				$this->setUser($this->aUser);
			}

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

			if ($this->singleRegularEntry !== null) {
				if (!$this->singleRegularEntry->isDeleted()) {
						$affectedRows += $this->singleRegularEntry->save($con);
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

			if ($this->singleProjectEntry !== null) {
				if (!$this->singleProjectEntry->isDeleted()) {
						$affectedRows += $this->singleProjectEntry->save($con);
				}
			}

			if ($this->oOEntrysScheduledForDeletion !== null) {
				if (!$this->oOEntrysScheduledForDeletion->isEmpty()) {
					OOEntryQuery::create()
						->filterByPrimaryKeys($this->oOEntrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->oOEntrysScheduledForDeletion = null;
				}
			}

			if ($this->singleOOEntry !== null) {
				if (!$this->singleOOEntry->isDeleted()) {
						$affectedRows += $this->singleOOEntry->save($con);
				}
			}

			if ($this->adjustmentEntrysScheduledForDeletion !== null) {
				if (!$this->adjustmentEntrysScheduledForDeletion->isEmpty()) {
					AdjustmentEntryQuery::create()
						->filterByPrimaryKeys($this->adjustmentEntrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->adjustmentEntrysScheduledForDeletion = null;
				}
			}

			if ($this->singleAdjustmentEntry !== null) {
				if (!$this->singleAdjustmentEntry->isDeleted()) {
						$affectedRows += $this->singleAdjustmentEntry->save($con);
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

		$this->modifiedColumns[] = EntryPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . EntryPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(EntryPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(EntryPeer::DAY_ID)) {
			$modifiedColumns[':p' . $index++]  = '`DAY_ID`';
		}
		if ($this->isColumnModified(EntryPeer::USER_ID)) {
			$modifiedColumns[':p' . $index++]  = '`USER_ID`';
		}
		if ($this->isColumnModified(EntryPeer::DESCENDANT_CLASS)) {
			$modifiedColumns[':p' . $index++]  = '`DESCENDANT_CLASS`';
		}

		$sql = sprintf(
			'INSERT INTO `entries` (%s) VALUES (%s)',
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
					case '`DAY_ID`':						
						$stmt->bindValue($identifier, $this->day_id, PDO::PARAM_INT);
						break;
					case '`USER_ID`':						
						$stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
						break;
					case '`DESCENDANT_CLASS`':						
						$stmt->bindValue($identifier, $this->descendant_class, PDO::PARAM_STR);
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aDay !== null) {
				if (!$this->aDay->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aDay->getValidationFailures());
				}
			}

			if ($this->aUser !== null) {
				if (!$this->aUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
				}
			}


			if (($retval = EntryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->singleRegularEntry !== null) {
					if (!$this->singleRegularEntry->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleRegularEntry->getValidationFailures());
					}
				}

				if ($this->singleProjectEntry !== null) {
					if (!$this->singleProjectEntry->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleProjectEntry->getValidationFailures());
					}
				}

				if ($this->singleOOEntry !== null) {
					if (!$this->singleOOEntry->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleOOEntry->getValidationFailures());
					}
				}

				if ($this->singleAdjustmentEntry !== null) {
					if (!$this->singleAdjustmentEntry->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleAdjustmentEntry->getValidationFailures());
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
		$pos = EntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDayId();
				break;
			case 2:
				return $this->getUserId();
				break;
			case 3:
				return $this->getDescendantClass();
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
		if (isset($alreadyDumpedObjects['Entry'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['Entry'][$this->getPrimaryKey()] = true;
		$keys = EntryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDayId(),
			$keys[2] => $this->getUserId(),
			$keys[3] => $this->getDescendantClass(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aDay) {
				$result['Day'] = $this->aDay->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
			if (null !== $this->aUser) {
				$result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
			if (null !== $this->singleRegularEntry) {
				$result['RegularEntry'] = $this->singleRegularEntry->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
			}
			if (null !== $this->singleProjectEntry) {
				$result['ProjectEntry'] = $this->singleProjectEntry->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
			}
			if (null !== $this->singleOOEntry) {
				$result['OOEntry'] = $this->singleOOEntry->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
			}
			if (null !== $this->singleAdjustmentEntry) {
				$result['AdjustmentEntry'] = $this->singleAdjustmentEntry->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
		$pos = EntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDayId($value);
				break;
			case 2:
				$this->setUserId($value);
				break;
			case 3:
				$this->setDescendantClass($value);
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
		$keys = EntryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDayId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUserId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDescendantClass($arr[$keys[3]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(EntryPeer::DATABASE_NAME);

		if ($this->isColumnModified(EntryPeer::ID)) $criteria->add(EntryPeer::ID, $this->id);
		if ($this->isColumnModified(EntryPeer::DAY_ID)) $criteria->add(EntryPeer::DAY_ID, $this->day_id);
		if ($this->isColumnModified(EntryPeer::USER_ID)) $criteria->add(EntryPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(EntryPeer::DESCENDANT_CLASS)) $criteria->add(EntryPeer::DESCENDANT_CLASS, $this->descendant_class);

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
		$criteria = new Criteria(EntryPeer::DATABASE_NAME);
		$criteria->add(EntryPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Entry (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setDayId($this->getDayId());
		$copyObj->setUserId($this->getUserId());
		$copyObj->setDescendantClass($this->getDescendantClass());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			$relObj = $this->getRegularEntry();
			if ($relObj) {
				$copyObj->setRegularEntry($relObj->copy($deepCopy));
			}

			$relObj = $this->getProjectEntry();
			if ($relObj) {
				$copyObj->setProjectEntry($relObj->copy($deepCopy));
			}

			$relObj = $this->getOOEntry();
			if ($relObj) {
				$copyObj->setOOEntry($relObj->copy($deepCopy));
			}

			$relObj = $this->getAdjustmentEntry();
			if ($relObj) {
				$copyObj->setAdjustmentEntry($relObj->copy($deepCopy));
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
	 * @return     Entry Clone of current object.
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
	 * @return     EntryPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new EntryPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Day object.
	 *
	 * @param      Day $v
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setDay(Day $v = null)
	{
		if ($v === null) {
			$this->setDayId(NULL);
		} else {
			$this->setDayId($v->getId());
		}

		$this->aDay = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Day object, it will not be re-added.
		if ($v !== null) {
			$v->addEntry($this);
		}

		return $this;
	}


	/**
	 * Get the associated Day object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Day The associated Day object.
	 * @throws     PropelException
	 */
	public function getDay(PropelPDO $con = null)
	{
		if ($this->aDay === null && ($this->day_id !== null)) {
			$this->aDay = DayQuery::create()->findPk($this->day_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aDay->addEntrys($this);
			 */
		}
		return $this->aDay;
	}

	/**
	 * Declares an association between this object and a User object.
	 *
	 * @param      User $v
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setUser(User $v = null)
	{
		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getId());
		}

		$this->aUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the User object, it will not be re-added.
		if ($v !== null) {
			$v->addEntry($this);
		}

		return $this;
	}


	/**
	 * Get the associated User object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     User The associated User object.
	 * @throws     PropelException
	 */
	public function getUser(PropelPDO $con = null)
	{
		if ($this->aUser === null && ($this->user_id !== null)) {
			$this->aUser = UserQuery::create()->findPk($this->user_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aUser->addEntrys($this);
			 */
		}
		return $this->aUser;
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
	}

	/**
	 * Gets a single RegularEntry object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     RegularEntry
	 * @throws     PropelException
	 */
	public function getRegularEntry(PropelPDO $con = null)
	{

		if ($this->singleRegularEntry === null && !$this->isNew()) {
			$this->singleRegularEntry = RegularEntryQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleRegularEntry;
	}

	/**
	 * Sets a single RegularEntry object as related to this object by a one-to-one relationship.
	 *
	 * @param      RegularEntry $v RegularEntry
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setRegularEntry(RegularEntry $v = null)
	{
		$this->singleRegularEntry = $v;

		// Make sure that that the passed-in RegularEntry isn't already associated with this object
		if ($v !== null && $v->getEntry() === null) {
			$v->setEntry($this);
		}

		return $this;
	}

	/**
	 * Gets a single ProjectEntry object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     ProjectEntry
	 * @throws     PropelException
	 */
	public function getProjectEntry(PropelPDO $con = null)
	{

		if ($this->singleProjectEntry === null && !$this->isNew()) {
			$this->singleProjectEntry = ProjectEntryQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleProjectEntry;
	}

	/**
	 * Sets a single ProjectEntry object as related to this object by a one-to-one relationship.
	 *
	 * @param      ProjectEntry $v ProjectEntry
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setProjectEntry(ProjectEntry $v = null)
	{
		$this->singleProjectEntry = $v;

		// Make sure that that the passed-in ProjectEntry isn't already associated with this object
		if ($v !== null && $v->getEntry() === null) {
			$v->setEntry($this);
		}

		return $this;
	}

	/**
	 * Gets a single OOEntry object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     OOEntry
	 * @throws     PropelException
	 */
	public function getOOEntry(PropelPDO $con = null)
	{

		if ($this->singleOOEntry === null && !$this->isNew()) {
			$this->singleOOEntry = OOEntryQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleOOEntry;
	}

	/**
	 * Sets a single OOEntry object as related to this object by a one-to-one relationship.
	 *
	 * @param      OOEntry $v OOEntry
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setOOEntry(OOEntry $v = null)
	{
		$this->singleOOEntry = $v;

		// Make sure that that the passed-in OOEntry isn't already associated with this object
		if ($v !== null && $v->getEntry() === null) {
			$v->setEntry($this);
		}

		return $this;
	}

	/**
	 * Gets a single AdjustmentEntry object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     AdjustmentEntry
	 * @throws     PropelException
	 */
	public function getAdjustmentEntry(PropelPDO $con = null)
	{

		if ($this->singleAdjustmentEntry === null && !$this->isNew()) {
			$this->singleAdjustmentEntry = AdjustmentEntryQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleAdjustmentEntry;
	}

	/**
	 * Sets a single AdjustmentEntry object as related to this object by a one-to-one relationship.
	 *
	 * @param      AdjustmentEntry $v AdjustmentEntry
	 * @return     Entry The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setAdjustmentEntry(AdjustmentEntry $v = null)
	{
		$this->singleAdjustmentEntry = $v;

		// Make sure that that the passed-in AdjustmentEntry isn't already associated with this object
		if ($v !== null && $v->getEntry() === null) {
			$v->setEntry($this);
		}

		return $this;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->day_id = null;
		$this->user_id = null;
		$this->descendant_class = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
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
			if ($this->singleRegularEntry) {
				$this->singleRegularEntry->clearAllReferences($deep);
			}
			if ($this->singleProjectEntry) {
				$this->singleProjectEntry->clearAllReferences($deep);
			}
			if ($this->singleOOEntry) {
				$this->singleOOEntry->clearAllReferences($deep);
			}
			if ($this->singleAdjustmentEntry) {
				$this->singleAdjustmentEntry->clearAllReferences($deep);
			}
		} // if ($deep)

		if ($this->singleRegularEntry instanceof PropelCollection) {
			$this->singleRegularEntry->clearIterator();
		}
		$this->singleRegularEntry = null;
		if ($this->singleProjectEntry instanceof PropelCollection) {
			$this->singleProjectEntry->clearIterator();
		}
		$this->singleProjectEntry = null;
		if ($this->singleOOEntry instanceof PropelCollection) {
			$this->singleOOEntry->clearIterator();
		}
		$this->singleOOEntry = null;
		if ($this->singleAdjustmentEntry instanceof PropelCollection) {
			$this->singleAdjustmentEntry->clearIterator();
		}
		$this->singleAdjustmentEntry = null;
		$this->aDay = null;
		$this->aUser = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(EntryPeer::DEFAULT_STRING_FORMAT);
	}

	// concrete_inheritance_parent behavior
	
	/**
	 * Whether or not this object is the parent of a child object
	 *
	 * @return    bool
	 */
	public function hasChildObject()
	{
		return $this->getDescendantClass() !== null;
	}
	
	/**
	 * Get the child object of this object
	 *
	 * @return    mixed
	 */
	public function getChildObject()
	{
		if (!$this->hasChildObject()) {
			return null;
		}
		$childObjectClass = $this->getDescendantClass();
		$childObject = PropelQuery::from($childObjectClass)->findPk($this->getPrimaryKey());
		return $childObject->hasChildObject() ? $childObject->getChildObject() : $childObject;
	}

} // BaseEntry
