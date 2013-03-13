<?php


/**
 * Base class that represents a row from the 'oobookingtypes' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseOOBookingType extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'OOBookingTypePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        OOBookingTypePeer
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
	 * The value for the paid field.
	 * @var        boolean
	 */
	protected $paid;

	/**
	 * The value for the creator field.
	 * @var        string
	 */
	protected $creator;

	/**
	 * The value for the bookableindays field.
	 * @var        boolean
	 */
	protected $bookableindays;

	/**
	 * The value for the bookableinhalfdays field.
	 * @var        boolean
	 */
	protected $bookableinhalfdays;

	/**
	 * The value for the rgbcolorvalue field.
	 * @var        string
	 */
	protected $rgbcolorvalue;

	/**
	 * The value for the enabled field.
	 * @var        boolean
	 */
	protected $enabled;

	/**
	 * @var        array OOBooking[] Collection to store aggregation of OOBooking objects.
	 */
	protected $collOOBookings;

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
	protected $oOBookingsScheduledForDeletion = null;

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
	 * Get the [paid] column value.
	 * 
	 * @return     boolean
	 */
	public function getPaid()
	{
		return $this->paid;
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
	 * Get the [bookableindays] column value.
	 * 
	 * @return     boolean
	 */
	public function getBookableindays()
	{
		return $this->bookableindays;
	}

	/**
	 * Get the [bookableinhalfdays] column value.
	 * 
	 * @return     boolean
	 */
	public function getBookableinhalfdays()
	{
		return $this->bookableinhalfdays;
	}

	/**
	 * Get the [rgbcolorvalue] column value.
	 * 
	 * @return     string
	 */
	public function getRgbcolorvalue()
	{
		return $this->rgbcolorvalue;
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
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::TYPE;
		}

		return $this;
	} // setType()

	/**
	 * Sets the value of the [paid] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setPaid($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->paid !== $v) {
			$this->paid = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::PAID;
		}

		return $this;
	} // setPaid()

	/**
	 * Set the value of [creator] column.
	 * 
	 * @param      string $v new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setCreator($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creator !== $v) {
			$this->creator = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::CREATOR;
		}

		return $this;
	} // setCreator()

	/**
	 * Sets the value of the [bookableindays] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setBookableindays($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->bookableindays !== $v) {
			$this->bookableindays = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::BOOKABLEINDAYS;
		}

		return $this;
	} // setBookableindays()

	/**
	 * Sets the value of the [bookableinhalfdays] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setBookableinhalfdays($v)
	{
		if ($v !== null) {
			if (is_string($v)) {
				$v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
			} else {
				$v = (boolean) $v;
			}
		}

		if ($this->bookableinhalfdays !== $v) {
			$this->bookableinhalfdays = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::BOOKABLEINHALFDAYS;
		}

		return $this;
	} // setBookableinhalfdays()

	/**
	 * Set the value of [rgbcolorvalue] column.
	 * 
	 * @param      string $v new value
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function setRgbcolorvalue($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->rgbcolorvalue !== $v) {
			$this->rgbcolorvalue = $v;
			$this->modifiedColumns[] = OOBookingTypePeer::RGBCOLORVALUE;
		}

		return $this;
	} // setRgbcolorvalue()

	/**
	 * Sets the value of the [enabled] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     OOBookingType The current object (for fluent API support)
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
			$this->modifiedColumns[] = OOBookingTypePeer::ENABLED;
		}

		return $this;
	} // setEnabled()

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
			$this->type = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->paid = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
			$this->creator = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->bookableindays = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->bookableinhalfdays = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->rgbcolorvalue = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->enabled = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 8; // 8 = OOBookingTypePeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating OOBookingType object", $e);
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
			$con = Propel::getConnection(OOBookingTypePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = OOBookingTypePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collOOBookings = null;

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
			$con = Propel::getConnection(OOBookingTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = OOBookingTypeQuery::create()
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
			$con = Propel::getConnection(OOBookingTypePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				OOBookingTypePeer::addInstanceToPool($this);
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

			if ($this->oOBookingsScheduledForDeletion !== null) {
				if (!$this->oOBookingsScheduledForDeletion->isEmpty()) {
					OOBookingQuery::create()
						->filterByPrimaryKeys($this->oOBookingsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->oOBookingsScheduledForDeletion = null;
				}
			}

			if ($this->collOOBookings !== null) {
				foreach ($this->collOOBookings as $referrerFK) {
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

		$this->modifiedColumns[] = OOBookingTypePeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . OOBookingTypePeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(OOBookingTypePeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::TYPE)) {
			$modifiedColumns[':p' . $index++]  = '`TYPE`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::PAID)) {
			$modifiedColumns[':p' . $index++]  = '`PAID`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::CREATOR)) {
			$modifiedColumns[':p' . $index++]  = '`CREATOR`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::BOOKABLEINDAYS)) {
			$modifiedColumns[':p' . $index++]  = '`BOOKABLEINDAYS`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::BOOKABLEINHALFDAYS)) {
			$modifiedColumns[':p' . $index++]  = '`BOOKABLEINHALFDAYS`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::RGBCOLORVALUE)) {
			$modifiedColumns[':p' . $index++]  = '`RGBCOLORVALUE`';
		}
		if ($this->isColumnModified(OOBookingTypePeer::ENABLED)) {
			$modifiedColumns[':p' . $index++]  = '`ENABLED`';
		}

		$sql = sprintf(
			'INSERT INTO `oobookingtypes` (%s) VALUES (%s)',
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
					case '`PAID`':
						$stmt->bindValue($identifier, (int) $this->paid, PDO::PARAM_INT);
						break;
					case '`CREATOR`':						
						$stmt->bindValue($identifier, $this->creator, PDO::PARAM_STR);
						break;
					case '`BOOKABLEINDAYS`':
						$stmt->bindValue($identifier, (int) $this->bookableindays, PDO::PARAM_INT);
						break;
					case '`BOOKABLEINHALFDAYS`':
						$stmt->bindValue($identifier, (int) $this->bookableinhalfdays, PDO::PARAM_INT);
						break;
					case '`RGBCOLORVALUE`':						
						$stmt->bindValue($identifier, $this->rgbcolorvalue, PDO::PARAM_STR);
						break;
					case '`ENABLED`':
						$stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
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


			if (($retval = OOBookingTypePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collOOBookings !== null) {
					foreach ($this->collOOBookings as $referrerFK) {
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
		$pos = OOBookingTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPaid();
				break;
			case 3:
				return $this->getCreator();
				break;
			case 4:
				return $this->getBookableindays();
				break;
			case 5:
				return $this->getBookableinhalfdays();
				break;
			case 6:
				return $this->getRgbcolorvalue();
				break;
			case 7:
				return $this->getEnabled();
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
		if (isset($alreadyDumpedObjects['OOBookingType'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['OOBookingType'][$this->getPrimaryKey()] = true;
		$keys = OOBookingTypePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getType(),
			$keys[2] => $this->getPaid(),
			$keys[3] => $this->getCreator(),
			$keys[4] => $this->getBookableindays(),
			$keys[5] => $this->getBookableinhalfdays(),
			$keys[6] => $this->getRgbcolorvalue(),
			$keys[7] => $this->getEnabled(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->collOOBookings) {
				$result['OOBookings'] = $this->collOOBookings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = OOBookingTypePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPaid($value);
				break;
			case 3:
				$this->setCreator($value);
				break;
			case 4:
				$this->setBookableindays($value);
				break;
			case 5:
				$this->setBookableinhalfdays($value);
				break;
			case 6:
				$this->setRgbcolorvalue($value);
				break;
			case 7:
				$this->setEnabled($value);
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
		$keys = OOBookingTypePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPaid($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCreator($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setBookableindays($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setBookableinhalfdays($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setRgbcolorvalue($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEnabled($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(OOBookingTypePeer::DATABASE_NAME);

		if ($this->isColumnModified(OOBookingTypePeer::ID)) $criteria->add(OOBookingTypePeer::ID, $this->id);
		if ($this->isColumnModified(OOBookingTypePeer::TYPE)) $criteria->add(OOBookingTypePeer::TYPE, $this->type);
		if ($this->isColumnModified(OOBookingTypePeer::PAID)) $criteria->add(OOBookingTypePeer::PAID, $this->paid);
		if ($this->isColumnModified(OOBookingTypePeer::CREATOR)) $criteria->add(OOBookingTypePeer::CREATOR, $this->creator);
		if ($this->isColumnModified(OOBookingTypePeer::BOOKABLEINDAYS)) $criteria->add(OOBookingTypePeer::BOOKABLEINDAYS, $this->bookableindays);
		if ($this->isColumnModified(OOBookingTypePeer::BOOKABLEINHALFDAYS)) $criteria->add(OOBookingTypePeer::BOOKABLEINHALFDAYS, $this->bookableinhalfdays);
		if ($this->isColumnModified(OOBookingTypePeer::RGBCOLORVALUE)) $criteria->add(OOBookingTypePeer::RGBCOLORVALUE, $this->rgbcolorvalue);
		if ($this->isColumnModified(OOBookingTypePeer::ENABLED)) $criteria->add(OOBookingTypePeer::ENABLED, $this->enabled);

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
		$criteria = new Criteria(OOBookingTypePeer::DATABASE_NAME);
		$criteria->add(OOBookingTypePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of OOBookingType (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setType($this->getType());
		$copyObj->setPaid($this->getPaid());
		$copyObj->setCreator($this->getCreator());
		$copyObj->setBookableindays($this->getBookableindays());
		$copyObj->setBookableinhalfdays($this->getBookableinhalfdays());
		$copyObj->setRgbcolorvalue($this->getRgbcolorvalue());
		$copyObj->setEnabled($this->getEnabled());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getOOBookings() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addOOBooking($relObj->copy($deepCopy));
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
	 * @return     OOBookingType Clone of current object.
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
	 * @return     OOBookingTypePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new OOBookingTypePeer();
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
		if ('OOBooking' == $relationName) {
			return $this->initOOBookings();
		}
	}

	/**
	 * Clears out the collOOBookings collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addOOBookings()
	 */
	public function clearOOBookings()
	{
		$this->collOOBookings = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collOOBookings collection.
	 *
	 * By default this just sets the collOOBookings collection to an empty array (like clearcollOOBookings());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initOOBookings($overrideExisting = true)
	{
		if (null !== $this->collOOBookings && !$overrideExisting) {
			return;
		}
		$this->collOOBookings = new PropelObjectCollection();
		$this->collOOBookings->setModel('OOBooking');
	}

	/**
	 * Gets an array of OOBooking objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this OOBookingType is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array OOBooking[] List of OOBooking objects
	 * @throws     PropelException
	 */
	public function getOOBookings($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collOOBookings || null !== $criteria) {
			if ($this->isNew() && null === $this->collOOBookings) {
				// return empty collection
				$this->initOOBookings();
			} else {
				$collOOBookings = OOBookingQuery::create(null, $criteria)
					->filterByOOBookingType($this)
					->find($con);
				if (null !== $criteria) {
					return $collOOBookings;
				}
				$this->collOOBookings = $collOOBookings;
			}
		}
		return $this->collOOBookings;
	}

	/**
	 * Sets a collection of OOBooking objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $oOBookings A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setOOBookings(PropelCollection $oOBookings, PropelPDO $con = null)
	{
		$this->oOBookingsScheduledForDeletion = $this->getOOBookings(new Criteria(), $con)->diff($oOBookings);

		foreach ($oOBookings as $oOBooking) {
			// Fix issue with collection modified by reference
			if ($oOBooking->isNew()) {
				$oOBooking->setOOBookingType($this);
			}
			$this->addOOBooking($oOBooking);
		}

		$this->collOOBookings = $oOBookings;
	}

	/**
	 * Returns the number of related OOBooking objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related OOBooking objects.
	 * @throws     PropelException
	 */
	public function countOOBookings(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collOOBookings || null !== $criteria) {
			if ($this->isNew() && null === $this->collOOBookings) {
				return 0;
			} else {
				$query = OOBookingQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByOOBookingType($this)
					->count($con);
			}
		} else {
			return count($this->collOOBookings);
		}
	}

	/**
	 * Method called to associate a OOBooking object to this object
	 * through the OOBooking foreign key attribute.
	 *
	 * @param      OOBooking $l OOBooking
	 * @return     OOBookingType The current object (for fluent API support)
	 */
	public function addOOBooking(OOBooking $l)
	{
		if ($this->collOOBookings === null) {
			$this->initOOBookings();
		}
		if (!$this->collOOBookings->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddOOBooking($l);
		}

		return $this;
	}

	/**
	 * @param	OOBooking $oOBooking The oOBooking object to add.
	 */
	protected function doAddOOBooking($oOBooking)
	{
		$this->collOOBookings[]= $oOBooking;
		$oOBooking->setOOBookingType($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this OOBookingType is new, it will return
	 * an empty collection; or if this OOBookingType has previously
	 * been saved, it will retrieve related OOBookings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in OOBookingType.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOBooking[] List of OOBooking objects
	 */
	public function getOOBookingsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOBookingQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getOOBookings($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->type = null;
		$this->paid = null;
		$this->creator = null;
		$this->bookableindays = null;
		$this->bookableinhalfdays = null;
		$this->rgbcolorvalue = null;
		$this->enabled = null;
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
			if ($this->collOOBookings) {
				foreach ($this->collOOBookings as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collOOBookings instanceof PropelCollection) {
			$this->collOOBookings->clearIterator();
		}
		$this->collOOBookings = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(OOBookingTypePeer::DEFAULT_STRING_FORMAT);
	}

} // BaseOOBookingType
