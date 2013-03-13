<?php


/**
 * Base class that represents a row from the 'days' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseDay extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'DayPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        DayPeer
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
	 * The value for the workplan_id field.
	 * @var        int
	 */
	protected $workplan_id;

	/**
	 * The value for the dateofday field.
	 * @var        string
	 */
	protected $dateofday;

	/**
	 * The value for the weekdayname field.
	 * @var        string
	 */
	protected $weekdayname;

	/**
	 * The value for the iso8601week field.
	 * @var        int
	 */
	protected $iso8601week;

	/**
	 * @var        Workplan
	 */
	protected $aWorkplan;

	/**
	 * @var        array Tag[] Collection to store aggregation of Tag objects.
	 */
	protected $collTags;

	/**
	 * @var        array Entry[] Collection to store aggregation of Entry objects.
	 */
	protected $collEntrys;

	/**
	 * @var        array RegularEntry[] Collection to store aggregation of RegularEntry objects.
	 */
	protected $collRegularEntrys;

	/**
	 * @var        array ProjectEntry[] Collection to store aggregation of ProjectEntry objects.
	 */
	protected $collProjectEntrys;

	/**
	 * @var        array OOEntry[] Collection to store aggregation of OOEntry objects.
	 */
	protected $collOOEntrys;

	/**
	 * @var        array AdjustmentEntry[] Collection to store aggregation of AdjustmentEntry objects.
	 */
	protected $collAdjustmentEntrys;

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
	protected $tagsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $entrysScheduledForDeletion = null;

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
	 * Get the [workplan_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkplanId()
	{
		return $this->workplan_id;
	}

	/**
	 * Get the [optionally formatted] temporal [dateofday] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDateofday($format = NULL)
	{
		if ($this->dateofday === null) {
			return null;
		}


		if ($this->dateofday === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->dateofday);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->dateofday, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [weekdayname] column value.
	 * 
	 * @return     string
	 */
	public function getWeekdayname()
	{
		return $this->weekdayname;
	}

	/**
	 * Get the [iso8601week] column value.
	 * 
	 * @return     int
	 */
	public function getIso8601week()
	{
		return $this->iso8601week;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Day The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = DayPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [workplan_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Day The current object (for fluent API support)
	 */
	public function setWorkplanId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workplan_id !== $v) {
			$this->workplan_id = $v;
			$this->modifiedColumns[] = DayPeer::WORKPLAN_ID;
		}

		if ($this->aWorkplan !== null && $this->aWorkplan->getId() !== $v) {
			$this->aWorkplan = null;
		}

		return $this;
	} // setWorkplanId()

	/**
	 * Sets the value of [dateofday] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.
	 *               Empty strings are treated as NULL.
	 * @return     Day The current object (for fluent API support)
	 */
	public function setDateofday($v)
	{
		$dt = PropelDateTime::newInstance($v, null, 'DateTime');
		if ($this->dateofday !== null || $dt !== null) {
			$currentDateAsString = ($this->dateofday !== null && $tmpDt = new DateTime($this->dateofday)) ? $tmpDt->format('Y-m-d') : null;
			$newDateAsString = $dt ? $dt->format('Y-m-d') : null;
			if ($currentDateAsString !== $newDateAsString) {
				$this->dateofday = $newDateAsString;
				$this->modifiedColumns[] = DayPeer::DATEOFDAY;
			}
		} // if either are not null

		return $this;
	} // setDateofday()

	/**
	 * Set the value of [weekdayname] column.
	 * 
	 * @param      string $v new value
	 * @return     Day The current object (for fluent API support)
	 */
	public function setWeekdayname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->weekdayname !== $v) {
			$this->weekdayname = $v;
			$this->modifiedColumns[] = DayPeer::WEEKDAYNAME;
		}

		return $this;
	} // setWeekdayname()

	/**
	 * Set the value of [iso8601week] column.
	 * 
	 * @param      int $v new value
	 * @return     Day The current object (for fluent API support)
	 */
	public function setIso8601week($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->iso8601week !== $v) {
			$this->iso8601week = $v;
			$this->modifiedColumns[] = DayPeer::ISO8601WEEK;
		}

		return $this;
	} // setIso8601week()

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
			$this->workplan_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->dateofday = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->weekdayname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->iso8601week = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 5; // 5 = DayPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating Day object", $e);
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

		if ($this->aWorkplan !== null && $this->workplan_id !== $this->aWorkplan->getId()) {
			$this->aWorkplan = null;
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
			$con = Propel::getConnection(DayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = DayPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWorkplan = null;
			$this->collTags = null;

			$this->collEntrys = null;

			$this->collRegularEntrys = null;

			$this->collProjectEntrys = null;

			$this->collOOEntrys = null;

			$this->collAdjustmentEntrys = null;

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
			$con = Propel::getConnection(DayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = DayQuery::create()
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
			$con = Propel::getConnection(DayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				DayPeer::addInstanceToPool($this);
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

			if ($this->aWorkplan !== null) {
				if ($this->aWorkplan->isModified() || $this->aWorkplan->isNew()) {
					$affectedRows += $this->aWorkplan->save($con);
				}
				$this->setWorkplan($this->aWorkplan);
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

			if ($this->tagsScheduledForDeletion !== null) {
				if (!$this->tagsScheduledForDeletion->isEmpty()) {
					TagQuery::create()
						->filterByPrimaryKeys($this->tagsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->tagsScheduledForDeletion = null;
				}
			}

			if ($this->collTags !== null) {
				foreach ($this->collTags as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->entrysScheduledForDeletion !== null) {
				if (!$this->entrysScheduledForDeletion->isEmpty()) {
					EntryQuery::create()
						->filterByPrimaryKeys($this->entrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->entrysScheduledForDeletion = null;
				}
			}

			if ($this->collEntrys !== null) {
				foreach ($this->collEntrys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
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

			if ($this->oOEntrysScheduledForDeletion !== null) {
				if (!$this->oOEntrysScheduledForDeletion->isEmpty()) {
					OOEntryQuery::create()
						->filterByPrimaryKeys($this->oOEntrysScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->oOEntrysScheduledForDeletion = null;
				}
			}

			if ($this->collOOEntrys !== null) {
				foreach ($this->collOOEntrys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
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

			if ($this->collAdjustmentEntrys !== null) {
				foreach ($this->collAdjustmentEntrys as $referrerFK) {
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

		$this->modifiedColumns[] = DayPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . DayPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(DayPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(DayPeer::WORKPLAN_ID)) {
			$modifiedColumns[':p' . $index++]  = '`WORKPLAN_ID`';
		}
		if ($this->isColumnModified(DayPeer::DATEOFDAY)) {
			$modifiedColumns[':p' . $index++]  = '`DATEOFDAY`';
		}
		if ($this->isColumnModified(DayPeer::WEEKDAYNAME)) {
			$modifiedColumns[':p' . $index++]  = '`WEEKDAYNAME`';
		}
		if ($this->isColumnModified(DayPeer::ISO8601WEEK)) {
			$modifiedColumns[':p' . $index++]  = '`ISO8601WEEK`';
		}

		$sql = sprintf(
			'INSERT INTO `days` (%s) VALUES (%s)',
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
					case '`WORKPLAN_ID`':						
						$stmt->bindValue($identifier, $this->workplan_id, PDO::PARAM_INT);
						break;
					case '`DATEOFDAY`':						
						$stmt->bindValue($identifier, $this->dateofday, PDO::PARAM_STR);
						break;
					case '`WEEKDAYNAME`':						
						$stmt->bindValue($identifier, $this->weekdayname, PDO::PARAM_STR);
						break;
					case '`ISO8601WEEK`':						
						$stmt->bindValue($identifier, $this->iso8601week, PDO::PARAM_INT);
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

			if ($this->aWorkplan !== null) {
				if (!$this->aWorkplan->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkplan->getValidationFailures());
				}
			}


			if (($retval = DayPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collTags !== null) {
					foreach ($this->collTags as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collEntrys !== null) {
					foreach ($this->collEntrys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRegularEntrys !== null) {
					foreach ($this->collRegularEntrys as $referrerFK) {
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

				if ($this->collOOEntrys !== null) {
					foreach ($this->collOOEntrys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collAdjustmentEntrys !== null) {
					foreach ($this->collAdjustmentEntrys as $referrerFK) {
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
		$pos = DayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getWorkplanId();
				break;
			case 2:
				return $this->getDateofday();
				break;
			case 3:
				return $this->getWeekdayname();
				break;
			case 4:
				return $this->getIso8601week();
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
		if (isset($alreadyDumpedObjects['Day'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['Day'][$this->getPrimaryKey()] = true;
		$keys = DayPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWorkplanId(),
			$keys[2] => $this->getDateofday(),
			$keys[3] => $this->getWeekdayname(),
			$keys[4] => $this->getIso8601week(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aWorkplan) {
				$result['Workplan'] = $this->aWorkplan->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
			if (null !== $this->collTags) {
				$result['Tags'] = $this->collTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collEntrys) {
				$result['Entrys'] = $this->collEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collRegularEntrys) {
				$result['RegularEntrys'] = $this->collRegularEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collProjectEntrys) {
				$result['ProjectEntrys'] = $this->collProjectEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collOOEntrys) {
				$result['OOEntrys'] = $this->collOOEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collAdjustmentEntrys) {
				$result['AdjustmentEntrys'] = $this->collAdjustmentEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = DayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setWorkplanId($value);
				break;
			case 2:
				$this->setDateofday($value);
				break;
			case 3:
				$this->setWeekdayname($value);
				break;
			case 4:
				$this->setIso8601week($value);
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
		$keys = DayPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWorkplanId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDateofday($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setWeekdayname($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setIso8601week($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(DayPeer::DATABASE_NAME);

		if ($this->isColumnModified(DayPeer::ID)) $criteria->add(DayPeer::ID, $this->id);
		if ($this->isColumnModified(DayPeer::WORKPLAN_ID)) $criteria->add(DayPeer::WORKPLAN_ID, $this->workplan_id);
		if ($this->isColumnModified(DayPeer::DATEOFDAY)) $criteria->add(DayPeer::DATEOFDAY, $this->dateofday);
		if ($this->isColumnModified(DayPeer::WEEKDAYNAME)) $criteria->add(DayPeer::WEEKDAYNAME, $this->weekdayname);
		if ($this->isColumnModified(DayPeer::ISO8601WEEK)) $criteria->add(DayPeer::ISO8601WEEK, $this->iso8601week);

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
		$criteria = new Criteria(DayPeer::DATABASE_NAME);
		$criteria->add(DayPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Day (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setWorkplanId($this->getWorkplanId());
		$copyObj->setDateofday($this->getDateofday());
		$copyObj->setWeekdayname($this->getWeekdayname());
		$copyObj->setIso8601week($this->getIso8601week());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getTags() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTag($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addEntry($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getRegularEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addRegularEntry($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getProjectEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addProjectEntry($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getOOEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addOOEntry($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getAdjustmentEntrys() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addAdjustmentEntry($relObj->copy($deepCopy));
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
	 * @return     Day Clone of current object.
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
	 * @return     DayPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new DayPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Workplan object.
	 *
	 * @param      Workplan $v
	 * @return     Day The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWorkplan(Workplan $v = null)
	{
		if ($v === null) {
			$this->setWorkplanId(NULL);
		} else {
			$this->setWorkplanId($v->getId());
		}

		$this->aWorkplan = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Workplan object, it will not be re-added.
		if ($v !== null) {
			$v->addDay($this);
		}

		return $this;
	}


	/**
	 * Get the associated Workplan object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Workplan The associated Workplan object.
	 * @throws     PropelException
	 */
	public function getWorkplan(PropelPDO $con = null)
	{
		if ($this->aWorkplan === null && ($this->workplan_id !== null)) {
			$this->aWorkplan = WorkplanQuery::create()->findPk($this->workplan_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aWorkplan->addDays($this);
			 */
		}
		return $this->aWorkplan;
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
		if ('Tag' == $relationName) {
			return $this->initTags();
		}
		if ('Entry' == $relationName) {
			return $this->initEntrys();
		}
		if ('RegularEntry' == $relationName) {
			return $this->initRegularEntrys();
		}
		if ('ProjectEntry' == $relationName) {
			return $this->initProjectEntrys();
		}
		if ('OOEntry' == $relationName) {
			return $this->initOOEntrys();
		}
		if ('AdjustmentEntry' == $relationName) {
			return $this->initAdjustmentEntrys();
		}
	}

	/**
	 * Clears out the collTags collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTags()
	 */
	public function clearTags()
	{
		$this->collTags = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTags collection.
	 *
	 * By default this just sets the collTags collection to an empty array (like clearcollTags());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initTags($overrideExisting = true)
	{
		if (null !== $this->collTags && !$overrideExisting) {
			return;
		}
		$this->collTags = new PropelObjectCollection();
		$this->collTags->setModel('Tag');
	}

	/**
	 * Gets an array of Tag objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Day is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array Tag[] List of Tag objects
	 * @throws     PropelException
	 */
	public function getTags($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collTags || null !== $criteria) {
			if ($this->isNew() && null === $this->collTags) {
				// return empty collection
				$this->initTags();
			} else {
				$collTags = TagQuery::create(null, $criteria)
					->filterByDay($this)
					->find($con);
				if (null !== $criteria) {
					return $collTags;
				}
				$this->collTags = $collTags;
			}
		}
		return $this->collTags;
	}

	/**
	 * Sets a collection of Tag objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $tags A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTags(PropelCollection $tags, PropelPDO $con = null)
	{
		$this->tagsScheduledForDeletion = $this->getTags(new Criteria(), $con)->diff($tags);

		foreach ($tags as $tag) {
			// Fix issue with collection modified by reference
			if ($tag->isNew()) {
				$tag->setDay($this);
			}
			$this->addTag($tag);
		}

		$this->collTags = $tags;
	}

	/**
	 * Returns the number of related Tag objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Tag objects.
	 * @throws     PropelException
	 */
	public function countTags(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collTags || null !== $criteria) {
			if ($this->isNew() && null === $this->collTags) {
				return 0;
			} else {
				$query = TagQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByDay($this)
					->count($con);
			}
		} else {
			return count($this->collTags);
		}
	}

	/**
	 * Method called to associate a Tag object to this object
	 * through the Tag foreign key attribute.
	 *
	 * @param      Tag $l Tag
	 * @return     Day The current object (for fluent API support)
	 */
	public function addTag(Tag $l)
	{
		if ($this->collTags === null) {
			$this->initTags();
		}
		if (!$this->collTags->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddTag($l);
		}

		return $this;
	}

	/**
	 * @param	Tag $tag The tag object to add.
	 */
	protected function doAddTag($tag)
	{
		$this->collTags[]= $tag;
		$tag->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related Tags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array Tag[] List of Tag objects
	 */
	public function getTagsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TagQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getTags($query, $con);
	}

	/**
	 * Clears out the collEntrys collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addEntrys()
	 */
	public function clearEntrys()
	{
		$this->collEntrys = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collEntrys collection.
	 *
	 * By default this just sets the collEntrys collection to an empty array (like clearcollEntrys());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initEntrys($overrideExisting = true)
	{
		if (null !== $this->collEntrys && !$overrideExisting) {
			return;
		}
		$this->collEntrys = new PropelObjectCollection();
		$this->collEntrys->setModel('Entry');
	}

	/**
	 * Gets an array of Entry objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Day is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array Entry[] List of Entry objects
	 * @throws     PropelException
	 */
	public function getEntrys($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collEntrys) {
				// return empty collection
				$this->initEntrys();
			} else {
				$collEntrys = EntryQuery::create(null, $criteria)
					->filterByDay($this)
					->find($con);
				if (null !== $criteria) {
					return $collEntrys;
				}
				$this->collEntrys = $collEntrys;
			}
		}
		return $this->collEntrys;
	}

	/**
	 * Sets a collection of Entry objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $entrys A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setEntrys(PropelCollection $entrys, PropelPDO $con = null)
	{
		$this->entrysScheduledForDeletion = $this->getEntrys(new Criteria(), $con)->diff($entrys);

		foreach ($entrys as $entry) {
			// Fix issue with collection modified by reference
			if ($entry->isNew()) {
				$entry->setDay($this);
			}
			$this->addEntry($entry);
		}

		$this->collEntrys = $entrys;
	}

	/**
	 * Returns the number of related Entry objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Entry objects.
	 * @throws     PropelException
	 */
	public function countEntrys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collEntrys) {
				return 0;
			} else {
				$query = EntryQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByDay($this)
					->count($con);
			}
		} else {
			return count($this->collEntrys);
		}
	}

	/**
	 * Method called to associate a Entry object to this object
	 * through the Entry foreign key attribute.
	 *
	 * @param      Entry $l Entry
	 * @return     Day The current object (for fluent API support)
	 */
	public function addEntry(Entry $l)
	{
		if ($this->collEntrys === null) {
			$this->initEntrys();
		}
		if (!$this->collEntrys->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddEntry($l);
		}

		return $this;
	}

	/**
	 * @param	Entry $entry The entry object to add.
	 */
	protected function doAddEntry($entry)
	{
		$this->collEntrys[]= $entry;
		$entry->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related Entrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array Entry[] List of Entry objects
	 */
	public function getEntrysJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EntryQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getEntrys($query, $con);
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
	 * If this Day is new, it will return
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
					->filterByDay($this)
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
				$regularEntry->setDay($this);
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
					->filterByDay($this)
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
	 * @return     Day The current object (for fluent API support)
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
		$regularEntry->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array RegularEntry[] List of RegularEntry objects
	 */
	public function getRegularEntrysJoinRegularEntryType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = RegularEntryQuery::create(null, $criteria);
		$query->joinWith('RegularEntryType', $join_behavior);

		return $this->getRegularEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
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
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
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
	 * If this Day is new, it will return
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
					->filterByDay($this)
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
				$projectEntry->setDay($this);
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
					->filterByDay($this)
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
	 * @return     Day The current object (for fluent API support)
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
		$projectEntry->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array ProjectEntry[] List of ProjectEntry objects
	 */
	public function getProjectEntrysJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = ProjectEntryQuery::create(null, $criteria);
		$query->joinWith('Project', $join_behavior);

		return $this->getProjectEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
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
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
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
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
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
	 * Clears out the collOOEntrys collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addOOEntrys()
	 */
	public function clearOOEntrys()
	{
		$this->collOOEntrys = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collOOEntrys collection.
	 *
	 * By default this just sets the collOOEntrys collection to an empty array (like clearcollOOEntrys());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initOOEntrys($overrideExisting = true)
	{
		if (null !== $this->collOOEntrys && !$overrideExisting) {
			return;
		}
		$this->collOOEntrys = new PropelObjectCollection();
		$this->collOOEntrys->setModel('OOEntry');
	}

	/**
	 * Gets an array of OOEntry objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Day is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array OOEntry[] List of OOEntry objects
	 * @throws     PropelException
	 */
	public function getOOEntrys($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collOOEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collOOEntrys) {
				// return empty collection
				$this->initOOEntrys();
			} else {
				$collOOEntrys = OOEntryQuery::create(null, $criteria)
					->filterByDay($this)
					->find($con);
				if (null !== $criteria) {
					return $collOOEntrys;
				}
				$this->collOOEntrys = $collOOEntrys;
			}
		}
		return $this->collOOEntrys;
	}

	/**
	 * Sets a collection of OOEntry objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $oOEntrys A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setOOEntrys(PropelCollection $oOEntrys, PropelPDO $con = null)
	{
		$this->oOEntrysScheduledForDeletion = $this->getOOEntrys(new Criteria(), $con)->diff($oOEntrys);

		foreach ($oOEntrys as $oOEntry) {
			// Fix issue with collection modified by reference
			if ($oOEntry->isNew()) {
				$oOEntry->setDay($this);
			}
			$this->addOOEntry($oOEntry);
		}

		$this->collOOEntrys = $oOEntrys;
	}

	/**
	 * Returns the number of related OOEntry objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related OOEntry objects.
	 * @throws     PropelException
	 */
	public function countOOEntrys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collOOEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collOOEntrys) {
				return 0;
			} else {
				$query = OOEntryQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByDay($this)
					->count($con);
			}
		} else {
			return count($this->collOOEntrys);
		}
	}

	/**
	 * Method called to associate a OOEntry object to this object
	 * through the OOEntry foreign key attribute.
	 *
	 * @param      OOEntry $l OOEntry
	 * @return     Day The current object (for fluent API support)
	 */
	public function addOOEntry(OOEntry $l)
	{
		if ($this->collOOEntrys === null) {
			$this->initOOEntrys();
		}
		if (!$this->collOOEntrys->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddOOEntry($l);
		}

		return $this;
	}

	/**
	 * @param	OOEntry $oOEntry The oOEntry object to add.
	 */
	protected function doAddOOEntry($oOEntry)
	{
		$this->collOOEntrys[]= $oOEntry;
		$oOEntry->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOEntry[] List of OOEntry objects
	 */
	public function getOOEntrysJoinOOBooking($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOEntryQuery::create(null, $criteria);
		$query->joinWith('OOBooking', $join_behavior);

		return $this->getOOEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOEntry[] List of OOEntry objects
	 */
	public function getOOEntrysJoinEntry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOEntryQuery::create(null, $criteria);
		$query->joinWith('Entry', $join_behavior);

		return $this->getOOEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOEntry[] List of OOEntry objects
	 */
	public function getOOEntrysJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOEntryQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getOOEntrys($query, $con);
	}

	/**
	 * Clears out the collAdjustmentEntrys collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addAdjustmentEntrys()
	 */
	public function clearAdjustmentEntrys()
	{
		$this->collAdjustmentEntrys = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collAdjustmentEntrys collection.
	 *
	 * By default this just sets the collAdjustmentEntrys collection to an empty array (like clearcollAdjustmentEntrys());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initAdjustmentEntrys($overrideExisting = true)
	{
		if (null !== $this->collAdjustmentEntrys && !$overrideExisting) {
			return;
		}
		$this->collAdjustmentEntrys = new PropelObjectCollection();
		$this->collAdjustmentEntrys->setModel('AdjustmentEntry');
	}

	/**
	 * Gets an array of AdjustmentEntry objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Day is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array AdjustmentEntry[] List of AdjustmentEntry objects
	 * @throws     PropelException
	 */
	public function getAdjustmentEntrys($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collAdjustmentEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collAdjustmentEntrys) {
				// return empty collection
				$this->initAdjustmentEntrys();
			} else {
				$collAdjustmentEntrys = AdjustmentEntryQuery::create(null, $criteria)
					->filterByDay($this)
					->find($con);
				if (null !== $criteria) {
					return $collAdjustmentEntrys;
				}
				$this->collAdjustmentEntrys = $collAdjustmentEntrys;
			}
		}
		return $this->collAdjustmentEntrys;
	}

	/**
	 * Sets a collection of AdjustmentEntry objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $adjustmentEntrys A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setAdjustmentEntrys(PropelCollection $adjustmentEntrys, PropelPDO $con = null)
	{
		$this->adjustmentEntrysScheduledForDeletion = $this->getAdjustmentEntrys(new Criteria(), $con)->diff($adjustmentEntrys);

		foreach ($adjustmentEntrys as $adjustmentEntry) {
			// Fix issue with collection modified by reference
			if ($adjustmentEntry->isNew()) {
				$adjustmentEntry->setDay($this);
			}
			$this->addAdjustmentEntry($adjustmentEntry);
		}

		$this->collAdjustmentEntrys = $adjustmentEntrys;
	}

	/**
	 * Returns the number of related AdjustmentEntry objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related AdjustmentEntry objects.
	 * @throws     PropelException
	 */
	public function countAdjustmentEntrys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collAdjustmentEntrys || null !== $criteria) {
			if ($this->isNew() && null === $this->collAdjustmentEntrys) {
				return 0;
			} else {
				$query = AdjustmentEntryQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByDay($this)
					->count($con);
			}
		} else {
			return count($this->collAdjustmentEntrys);
		}
	}

	/**
	 * Method called to associate a AdjustmentEntry object to this object
	 * through the AdjustmentEntry foreign key attribute.
	 *
	 * @param      AdjustmentEntry $l AdjustmentEntry
	 * @return     Day The current object (for fluent API support)
	 */
	public function addAdjustmentEntry(AdjustmentEntry $l)
	{
		if ($this->collAdjustmentEntrys === null) {
			$this->initAdjustmentEntrys();
		}
		if (!$this->collAdjustmentEntrys->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddAdjustmentEntry($l);
		}

		return $this;
	}

	/**
	 * @param	AdjustmentEntry $adjustmentEntry The adjustmentEntry object to add.
	 */
	protected function doAddAdjustmentEntry($adjustmentEntry)
	{
		$this->collAdjustmentEntrys[]= $adjustmentEntry;
		$adjustmentEntry->setDay($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related AdjustmentEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array AdjustmentEntry[] List of AdjustmentEntry objects
	 */
	public function getAdjustmentEntrysJoinEntry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = AdjustmentEntryQuery::create(null, $criteria);
		$query->joinWith('Entry', $join_behavior);

		return $this->getAdjustmentEntrys($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Day is new, it will return
	 * an empty collection; or if this Day has previously
	 * been saved, it will retrieve related AdjustmentEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Day.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array AdjustmentEntry[] List of AdjustmentEntry objects
	 */
	public function getAdjustmentEntrysJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = AdjustmentEntryQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getAdjustmentEntrys($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->workplan_id = null;
		$this->dateofday = null;
		$this->weekdayname = null;
		$this->iso8601week = null;
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
			if ($this->collTags) {
				foreach ($this->collTags as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collEntrys) {
				foreach ($this->collEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collRegularEntrys) {
				foreach ($this->collRegularEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collProjectEntrys) {
				foreach ($this->collProjectEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collOOEntrys) {
				foreach ($this->collOOEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collAdjustmentEntrys) {
				foreach ($this->collAdjustmentEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collTags instanceof PropelCollection) {
			$this->collTags->clearIterator();
		}
		$this->collTags = null;
		if ($this->collEntrys instanceof PropelCollection) {
			$this->collEntrys->clearIterator();
		}
		$this->collEntrys = null;
		if ($this->collRegularEntrys instanceof PropelCollection) {
			$this->collRegularEntrys->clearIterator();
		}
		$this->collRegularEntrys = null;
		if ($this->collProjectEntrys instanceof PropelCollection) {
			$this->collProjectEntrys->clearIterator();
		}
		$this->collProjectEntrys = null;
		if ($this->collOOEntrys instanceof PropelCollection) {
			$this->collOOEntrys->clearIterator();
		}
		$this->collOOEntrys = null;
		if ($this->collAdjustmentEntrys instanceof PropelCollection) {
			$this->collAdjustmentEntrys->clearIterator();
		}
		$this->collAdjustmentEntrys = null;
		$this->aWorkplan = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(DayPeer::DEFAULT_STRING_FORMAT);
	}

} // BaseDay
