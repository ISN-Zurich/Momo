<?php


/**
 * Base class that represents a row from the 'workplans' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseWorkplan extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'WorkplanPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkplanPeer
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
	 * The value for the year field.
	 * @var        int
	 */
	protected $year;

	/**
	 * The value for the weeklyworkhours field.
	 * @var        int
	 */
	protected $weeklyworkhours;

	/**
	 * The value for the annualvacationdaysupto19 field.
	 * @var        int
	 */
	protected $annualvacationdaysupto19;

	/**
	 * The value for the annualvacationdays20to49 field.
	 * @var        int
	 */
	protected $annualvacationdays20to49;

	/**
	 * The value for the annualvacationdaysfrom50 field.
	 * @var        int
	 */
	protected $annualvacationdaysfrom50;

	/**
	 * @var        array Holiday[] Collection to store aggregation of Holiday objects.
	 */
	protected $collHolidays;

	/**
	 * @var        array Day[] Collection to store aggregation of Day objects.
	 */
	protected $collDays;

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
	protected $holidaysScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $daysScheduledForDeletion = null;

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
	 * Get the [year] column value.
	 * 
	 * @return     int
	 */
	public function getYear()
	{
		return $this->year;
	}

	/**
	 * Get the [weeklyworkhours] column value.
	 * 
	 * @return     int
	 */
	public function getWeeklyworkhours()
	{
		return $this->weeklyworkhours;
	}

	/**
	 * Get the [annualvacationdaysupto19] column value.
	 * 
	 * @return     int
	 */
	public function getAnnualvacationdaysupto19()
	{
		return $this->annualvacationdaysupto19;
	}

	/**
	 * Get the [annualvacationdays20to49] column value.
	 * 
	 * @return     int
	 */
	public function getAnnualvacationdays20to49()
	{
		return $this->annualvacationdays20to49;
	}

	/**
	 * Get the [annualvacationdaysfrom50] column value.
	 * 
	 * @return     int
	 */
	public function getAnnualvacationdaysfrom50()
	{
		return $this->annualvacationdaysfrom50;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = WorkplanPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [year] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setYear($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->year !== $v) {
			$this->year = $v;
			$this->modifiedColumns[] = WorkplanPeer::YEAR;
		}

		return $this;
	} // setYear()

	/**
	 * Set the value of [weeklyworkhours] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setWeeklyworkhours($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->weeklyworkhours !== $v) {
			$this->weeklyworkhours = $v;
			$this->modifiedColumns[] = WorkplanPeer::WEEKLYWORKHOURS;
		}

		return $this;
	} // setWeeklyworkhours()

	/**
	 * Set the value of [annualvacationdaysupto19] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setAnnualvacationdaysupto19($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->annualvacationdaysupto19 !== $v) {
			$this->annualvacationdaysupto19 = $v;
			$this->modifiedColumns[] = WorkplanPeer::ANNUALVACATIONDAYSUPTO19;
		}

		return $this;
	} // setAnnualvacationdaysupto19()

	/**
	 * Set the value of [annualvacationdays20to49] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setAnnualvacationdays20to49($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->annualvacationdays20to49 !== $v) {
			$this->annualvacationdays20to49 = $v;
			$this->modifiedColumns[] = WorkplanPeer::ANNUALVACATIONDAYS20TO49;
		}

		return $this;
	} // setAnnualvacationdays20to49()

	/**
	 * Set the value of [annualvacationdaysfrom50] column.
	 * 
	 * @param      int $v new value
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function setAnnualvacationdaysfrom50($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->annualvacationdaysfrom50 !== $v) {
			$this->annualvacationdaysfrom50 = $v;
			$this->modifiedColumns[] = WorkplanPeer::ANNUALVACATIONDAYSFROM50;
		}

		return $this;
	} // setAnnualvacationdaysfrom50()

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
			$this->year = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->weeklyworkhours = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->annualvacationdaysupto19 = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->annualvacationdays20to49 = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->annualvacationdaysfrom50 = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 6; // 6 = WorkplanPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating Workplan object", $e);
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
			$con = Propel::getConnection(WorkplanPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkplanPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collHolidays = null;

			$this->collDays = null;

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
			$con = Propel::getConnection(WorkplanPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = WorkplanQuery::create()
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
			$con = Propel::getConnection(WorkplanPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				WorkplanPeer::addInstanceToPool($this);
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

			if ($this->holidaysScheduledForDeletion !== null) {
				if (!$this->holidaysScheduledForDeletion->isEmpty()) {
					HolidayQuery::create()
						->filterByPrimaryKeys($this->holidaysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->holidaysScheduledForDeletion = null;
				}
			}

			if ($this->collHolidays !== null) {
				foreach ($this->collHolidays as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->daysScheduledForDeletion !== null) {
				if (!$this->daysScheduledForDeletion->isEmpty()) {
					DayQuery::create()
						->filterByPrimaryKeys($this->daysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->daysScheduledForDeletion = null;
				}
			}

			if ($this->collDays !== null) {
				foreach ($this->collDays as $referrerFK) {
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

		$this->modifiedColumns[] = WorkplanPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . WorkplanPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(WorkplanPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(WorkplanPeer::YEAR)) {
			$modifiedColumns[':p' . $index++]  = '`YEAR`';
		}
		if ($this->isColumnModified(WorkplanPeer::WEEKLYWORKHOURS)) {
			$modifiedColumns[':p' . $index++]  = '`WEEKLYWORKHOURS`';
		}
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYSUPTO19)) {
			$modifiedColumns[':p' . $index++]  = '`ANNUALVACATIONDAYSUPTO19`';
		}
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYS20TO49)) {
			$modifiedColumns[':p' . $index++]  = '`ANNUALVACATIONDAYS20TO49`';
		}
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYSFROM50)) {
			$modifiedColumns[':p' . $index++]  = '`ANNUALVACATIONDAYSFROM50`';
		}

		$sql = sprintf(
			'INSERT INTO `workplans` (%s) VALUES (%s)',
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
					case '`YEAR`':						
						$stmt->bindValue($identifier, $this->year, PDO::PARAM_INT);
						break;
					case '`WEEKLYWORKHOURS`':						
						$stmt->bindValue($identifier, $this->weeklyworkhours, PDO::PARAM_INT);
						break;
					case '`ANNUALVACATIONDAYSUPTO19`':						
						$stmt->bindValue($identifier, $this->annualvacationdaysupto19, PDO::PARAM_INT);
						break;
					case '`ANNUALVACATIONDAYS20TO49`':						
						$stmt->bindValue($identifier, $this->annualvacationdays20to49, PDO::PARAM_INT);
						break;
					case '`ANNUALVACATIONDAYSFROM50`':						
						$stmt->bindValue($identifier, $this->annualvacationdaysfrom50, PDO::PARAM_INT);
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


			if (($retval = WorkplanPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collHolidays !== null) {
					foreach ($this->collHolidays as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collDays !== null) {
					foreach ($this->collDays as $referrerFK) {
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
		$pos = WorkplanPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getYear();
				break;
			case 2:
				return $this->getWeeklyworkhours();
				break;
			case 3:
				return $this->getAnnualvacationdaysupto19();
				break;
			case 4:
				return $this->getAnnualvacationdays20to49();
				break;
			case 5:
				return $this->getAnnualvacationdaysfrom50();
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
		if (isset($alreadyDumpedObjects['Workplan'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['Workplan'][$this->getPrimaryKey()] = true;
		$keys = WorkplanPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getYear(),
			$keys[2] => $this->getWeeklyworkhours(),
			$keys[3] => $this->getAnnualvacationdaysupto19(),
			$keys[4] => $this->getAnnualvacationdays20to49(),
			$keys[5] => $this->getAnnualvacationdaysfrom50(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->collHolidays) {
				$result['Holidays'] = $this->collHolidays->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collDays) {
				$result['Days'] = $this->collDays->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = WorkplanPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setYear($value);
				break;
			case 2:
				$this->setWeeklyworkhours($value);
				break;
			case 3:
				$this->setAnnualvacationdaysupto19($value);
				break;
			case 4:
				$this->setAnnualvacationdays20to49($value);
				break;
			case 5:
				$this->setAnnualvacationdaysfrom50($value);
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
		$keys = WorkplanPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setYear($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setWeeklyworkhours($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setAnnualvacationdaysupto19($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAnnualvacationdays20to49($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setAnnualvacationdaysfrom50($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WorkplanPeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkplanPeer::ID)) $criteria->add(WorkplanPeer::ID, $this->id);
		if ($this->isColumnModified(WorkplanPeer::YEAR)) $criteria->add(WorkplanPeer::YEAR, $this->year);
		if ($this->isColumnModified(WorkplanPeer::WEEKLYWORKHOURS)) $criteria->add(WorkplanPeer::WEEKLYWORKHOURS, $this->weeklyworkhours);
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYSUPTO19)) $criteria->add(WorkplanPeer::ANNUALVACATIONDAYSUPTO19, $this->annualvacationdaysupto19);
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYS20TO49)) $criteria->add(WorkplanPeer::ANNUALVACATIONDAYS20TO49, $this->annualvacationdays20to49);
		if ($this->isColumnModified(WorkplanPeer::ANNUALVACATIONDAYSFROM50)) $criteria->add(WorkplanPeer::ANNUALVACATIONDAYSFROM50, $this->annualvacationdaysfrom50);

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
		$criteria = new Criteria(WorkplanPeer::DATABASE_NAME);
		$criteria->add(WorkplanPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Workplan (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setYear($this->getYear());
		$copyObj->setWeeklyworkhours($this->getWeeklyworkhours());
		$copyObj->setAnnualvacationdaysupto19($this->getAnnualvacationdaysupto19());
		$copyObj->setAnnualvacationdays20to49($this->getAnnualvacationdays20to49());
		$copyObj->setAnnualvacationdaysfrom50($this->getAnnualvacationdaysfrom50());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getHolidays() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addHoliday($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getDays() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addDay($relObj->copy($deepCopy));
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
	 * @return     Workplan Clone of current object.
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
	 * @return     WorkplanPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkplanPeer();
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
		if ('Holiday' == $relationName) {
			return $this->initHolidays();
		}
		if ('Day' == $relationName) {
			return $this->initDays();
		}
	}

	/**
	 * Clears out the collHolidays collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addHolidays()
	 */
	public function clearHolidays()
	{
		$this->collHolidays = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collHolidays collection.
	 *
	 * By default this just sets the collHolidays collection to an empty array (like clearcollHolidays());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initHolidays($overrideExisting = true)
	{
		if (null !== $this->collHolidays && !$overrideExisting) {
			return;
		}
		$this->collHolidays = new PropelObjectCollection();
		$this->collHolidays->setModel('Holiday');
	}

	/**
	 * Gets an array of Holiday objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Workplan is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array Holiday[] List of Holiday objects
	 * @throws     PropelException
	 */
	public function getHolidays($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collHolidays || null !== $criteria) {
			if ($this->isNew() && null === $this->collHolidays) {
				// return empty collection
				$this->initHolidays();
			} else {
				$collHolidays = HolidayQuery::create(null, $criteria)
					->filterByWorkplan($this)
					->find($con);
				if (null !== $criteria) {
					return $collHolidays;
				}
				$this->collHolidays = $collHolidays;
			}
		}
		return $this->collHolidays;
	}

	/**
	 * Sets a collection of Holiday objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $holidays A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setHolidays(PropelCollection $holidays, PropelPDO $con = null)
	{
		$this->holidaysScheduledForDeletion = $this->getHolidays(new Criteria(), $con)->diff($holidays);

		foreach ($holidays as $holiday) {
			// Fix issue with collection modified by reference
			if ($holiday->isNew()) {
				$holiday->setWorkplan($this);
			}
			$this->addHoliday($holiday);
		}

		$this->collHolidays = $holidays;
	}

	/**
	 * Returns the number of related Holiday objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Holiday objects.
	 * @throws     PropelException
	 */
	public function countHolidays(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collHolidays || null !== $criteria) {
			if ($this->isNew() && null === $this->collHolidays) {
				return 0;
			} else {
				$query = HolidayQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWorkplan($this)
					->count($con);
			}
		} else {
			return count($this->collHolidays);
		}
	}

	/**
	 * Method called to associate a Holiday object to this object
	 * through the Holiday foreign key attribute.
	 *
	 * @param      Holiday $l Holiday
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function addHoliday(Holiday $l)
	{
		if ($this->collHolidays === null) {
			$this->initHolidays();
		}
		if (!$this->collHolidays->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddHoliday($l);
		}

		return $this;
	}

	/**
	 * @param	Holiday $holiday The holiday object to add.
	 */
	protected function doAddHoliday($holiday)
	{
		$this->collHolidays[]= $holiday;
		$holiday->setWorkplan($this);
	}

	/**
	 * Clears out the collDays collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addDays()
	 */
	public function clearDays()
	{
		$this->collDays = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collDays collection.
	 *
	 * By default this just sets the collDays collection to an empty array (like clearcollDays());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initDays($overrideExisting = true)
	{
		if (null !== $this->collDays && !$overrideExisting) {
			return;
		}
		$this->collDays = new PropelObjectCollection();
		$this->collDays->setModel('Day');
	}

	/**
	 * Gets an array of Day objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Workplan is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array Day[] List of Day objects
	 * @throws     PropelException
	 */
	public function getDays($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collDays || null !== $criteria) {
			if ($this->isNew() && null === $this->collDays) {
				// return empty collection
				$this->initDays();
			} else {
				$collDays = DayQuery::create(null, $criteria)
					->filterByWorkplan($this)
					->find($con);
				if (null !== $criteria) {
					return $collDays;
				}
				$this->collDays = $collDays;
			}
		}
		return $this->collDays;
	}

	/**
	 * Sets a collection of Day objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $days A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setDays(PropelCollection $days, PropelPDO $con = null)
	{
		$this->daysScheduledForDeletion = $this->getDays(new Criteria(), $con)->diff($days);

		foreach ($days as $day) {
			// Fix issue with collection modified by reference
			if ($day->isNew()) {
				$day->setWorkplan($this);
			}
			$this->addDay($day);
		}

		$this->collDays = $days;
	}

	/**
	 * Returns the number of related Day objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Day objects.
	 * @throws     PropelException
	 */
	public function countDays(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collDays || null !== $criteria) {
			if ($this->isNew() && null === $this->collDays) {
				return 0;
			} else {
				$query = DayQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWorkplan($this)
					->count($con);
			}
		} else {
			return count($this->collDays);
		}
	}

	/**
	 * Method called to associate a Day object to this object
	 * through the Day foreign key attribute.
	 *
	 * @param      Day $l Day
	 * @return     Workplan The current object (for fluent API support)
	 */
	public function addDay(Day $l)
	{
		if ($this->collDays === null) {
			$this->initDays();
		}
		if (!$this->collDays->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddDay($l);
		}

		return $this;
	}

	/**
	 * @param	Day $day The day object to add.
	 */
	protected function doAddDay($day)
	{
		$this->collDays[]= $day;
		$day->setWorkplan($this);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->year = null;
		$this->weeklyworkhours = null;
		$this->annualvacationdaysupto19 = null;
		$this->annualvacationdays20to49 = null;
		$this->annualvacationdaysfrom50 = null;
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
			if ($this->collHolidays) {
				foreach ($this->collHolidays as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collDays) {
				foreach ($this->collDays as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collHolidays instanceof PropelCollection) {
			$this->collHolidays->clearIterator();
		}
		$this->collHolidays = null;
		if ($this->collDays instanceof PropelCollection) {
			$this->collDays->clearIterator();
		}
		$this->collDays = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(WorkplanPeer::DEFAULT_STRING_FORMAT);
	}

} // BaseWorkplan
