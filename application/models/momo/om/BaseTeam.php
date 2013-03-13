<?php


/**
 * Base class that represents a row from the 'teams' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseTeam extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'TeamPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        TeamPeer
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
	 * The value for the parent_id field.
	 * @var        int
	 */
	protected $parent_id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the archived field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $archived;

	/**
	 * @var        Team
	 */
	protected $aTeamRelatedByParentId;

	/**
	 * @var        array Team[] Collection to store aggregation of Team objects.
	 */
	protected $collTeamsRelatedById;

	/**
	 * @var        array TeamUser[] Collection to store aggregation of TeamUser objects.
	 */
	protected $collTeamUsers;

	/**
	 * @var        array TeamProject[] Collection to store aggregation of TeamProject objects.
	 */
	protected $collTeamProjects;

	/**
	 * @var        array ProjectEntry[] Collection to store aggregation of ProjectEntry objects.
	 */
	protected $collProjectEntrys;

	/**
	 * @var        array User[] Collection to store aggregation of User objects.
	 */
	protected $collUsers;

	/**
	 * @var        array Project[] Collection to store aggregation of Project objects.
	 */
	protected $collProjects;

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
	protected $usersScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $projectsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamsRelatedByIdScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamUsersScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamProjectsScheduledForDeletion = null;

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
		$this->archived = false;
	}

	/**
	 * Initializes internal state of BaseTeam object.
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
	 * Get the [parent_id] column value.
	 * 
	 * @return     int
	 */
	public function getParentId()
	{
		return $this->parent_id;
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
	 * @return     Team The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = TeamPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [parent_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Team The current object (for fluent API support)
	 */
	public function setParentId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->parent_id !== $v) {
			$this->parent_id = $v;
			$this->modifiedColumns[] = TeamPeer::PARENT_ID;
		}

		if ($this->aTeamRelatedByParentId !== null && $this->aTeamRelatedByParentId->getId() !== $v) {
			$this->aTeamRelatedByParentId = null;
		}

		return $this;
	} // setParentId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Team The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = TeamPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Sets the value of the [archived] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     Team The current object (for fluent API support)
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
			$this->modifiedColumns[] = TeamPeer::ARCHIVED;
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
			$this->parent_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->archived = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 4; // 4 = TeamPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating Team object", $e);
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

		if ($this->aTeamRelatedByParentId !== null && $this->parent_id !== $this->aTeamRelatedByParentId->getId()) {
			$this->aTeamRelatedByParentId = null;
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
			$con = Propel::getConnection(TeamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = TeamPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aTeamRelatedByParentId = null;
			$this->collTeamsRelatedById = null;

			$this->collTeamUsers = null;

			$this->collTeamProjects = null;

			$this->collProjectEntrys = null;

			$this->collUsers = null;
			$this->collProjects = null;
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
			$con = Propel::getConnection(TeamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = TeamQuery::create()
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
			$con = Propel::getConnection(TeamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				TeamPeer::addInstanceToPool($this);
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

			if ($this->aTeamRelatedByParentId !== null) {
				if ($this->aTeamRelatedByParentId->isModified() || $this->aTeamRelatedByParentId->isNew()) {
					$affectedRows += $this->aTeamRelatedByParentId->save($con);
				}
				$this->setTeamRelatedByParentId($this->aTeamRelatedByParentId);
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

			if ($this->usersScheduledForDeletion !== null) {
				if (!$this->usersScheduledForDeletion->isEmpty()) {
					TeamUserQuery::create()
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

			if ($this->projectsScheduledForDeletion !== null) {
				if (!$this->projectsScheduledForDeletion->isEmpty()) {
					TeamProjectQuery::create()
						->filterByPrimaryKeys($this->projectsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->projectsScheduledForDeletion = null;
				}

				foreach ($this->getProjects() as $project) {
					if ($project->isModified()) {
						$project->save($con);
					}
				}
			}

			if ($this->teamsRelatedByIdScheduledForDeletion !== null) {
				if (!$this->teamsRelatedByIdScheduledForDeletion->isEmpty()) {
					TeamQuery::create()
						->filterByPrimaryKeys($this->teamsRelatedByIdScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->teamsRelatedByIdScheduledForDeletion = null;
				}
			}

			if ($this->collTeamsRelatedById !== null) {
				foreach ($this->collTeamsRelatedById as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->teamUsersScheduledForDeletion !== null) {
				if (!$this->teamUsersScheduledForDeletion->isEmpty()) {
					TeamUserQuery::create()
						->filterByPrimaryKeys($this->teamUsersScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->teamUsersScheduledForDeletion = null;
				}
			}

			if ($this->collTeamUsers !== null) {
				foreach ($this->collTeamUsers as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
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

		$this->modifiedColumns[] = TeamPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . TeamPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(TeamPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(TeamPeer::PARENT_ID)) {
			$modifiedColumns[':p' . $index++]  = '`PARENT_ID`';
		}
		if ($this->isColumnModified(TeamPeer::NAME)) {
			$modifiedColumns[':p' . $index++]  = '`NAME`';
		}
		if ($this->isColumnModified(TeamPeer::ARCHIVED)) {
			$modifiedColumns[':p' . $index++]  = '`ARCHIVED`';
		}

		$sql = sprintf(
			'INSERT INTO `teams` (%s) VALUES (%s)',
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
					case '`PARENT_ID`':						
						$stmt->bindValue($identifier, $this->parent_id, PDO::PARAM_INT);
						break;
					case '`NAME`':						
						$stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aTeamRelatedByParentId !== null) {
				if (!$this->aTeamRelatedByParentId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aTeamRelatedByParentId->getValidationFailures());
				}
			}


			if (($retval = TeamPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collTeamsRelatedById !== null) {
					foreach ($this->collTeamsRelatedById as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collTeamUsers !== null) {
					foreach ($this->collTeamUsers as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collTeamProjects !== null) {
					foreach ($this->collTeamProjects as $referrerFK) {
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
		$pos = TeamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getParentId();
				break;
			case 2:
				return $this->getName();
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
		if (isset($alreadyDumpedObjects['Team'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['Team'][$this->getPrimaryKey()] = true;
		$keys = TeamPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getParentId(),
			$keys[2] => $this->getName(),
			$keys[3] => $this->getArchived(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aTeamRelatedByParentId) {
				$result['TeamRelatedByParentId'] = $this->aTeamRelatedByParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
			if (null !== $this->collTeamsRelatedById) {
				$result['TeamsRelatedById'] = $this->collTeamsRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collTeamUsers) {
				$result['TeamUsers'] = $this->collTeamUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collTeamProjects) {
				$result['TeamProjects'] = $this->collTeamProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = TeamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setParentId($value);
				break;
			case 2:
				$this->setName($value);
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
		$keys = TeamPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setParentId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setArchived($arr[$keys[3]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(TeamPeer::DATABASE_NAME);

		if ($this->isColumnModified(TeamPeer::ID)) $criteria->add(TeamPeer::ID, $this->id);
		if ($this->isColumnModified(TeamPeer::PARENT_ID)) $criteria->add(TeamPeer::PARENT_ID, $this->parent_id);
		if ($this->isColumnModified(TeamPeer::NAME)) $criteria->add(TeamPeer::NAME, $this->name);
		if ($this->isColumnModified(TeamPeer::ARCHIVED)) $criteria->add(TeamPeer::ARCHIVED, $this->archived);

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
		$criteria = new Criteria(TeamPeer::DATABASE_NAME);
		$criteria->add(TeamPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Team (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setParentId($this->getParentId());
		$copyObj->setName($this->getName());
		$copyObj->setArchived($this->getArchived());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getTeamsRelatedById() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTeamRelatedById($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getTeamUsers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTeamUser($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getTeamProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTeamProject($relObj->copy($deepCopy));
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
	 * @return     Team Clone of current object.
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
	 * @return     TeamPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new TeamPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Team object.
	 *
	 * @param      Team $v
	 * @return     Team The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setTeamRelatedByParentId(Team $v = null)
	{
		if ($v === null) {
			$this->setParentId(NULL);
		} else {
			$this->setParentId($v->getId());
		}

		$this->aTeamRelatedByParentId = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Team object, it will not be re-added.
		if ($v !== null) {
			$v->addTeamRelatedById($this);
		}

		return $this;
	}


	/**
	 * Get the associated Team object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Team The associated Team object.
	 * @throws     PropelException
	 */
	public function getTeamRelatedByParentId(PropelPDO $con = null)
	{
		if ($this->aTeamRelatedByParentId === null && ($this->parent_id !== null)) {
			$this->aTeamRelatedByParentId = TeamQuery::create()->findPk($this->parent_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aTeamRelatedByParentId->addTeamsRelatedById($this);
			 */
		}
		return $this->aTeamRelatedByParentId;
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
		if ('TeamRelatedById' == $relationName) {
			return $this->initTeamsRelatedById();
		}
		if ('TeamUser' == $relationName) {
			return $this->initTeamUsers();
		}
		if ('TeamProject' == $relationName) {
			return $this->initTeamProjects();
		}
		if ('ProjectEntry' == $relationName) {
			return $this->initProjectEntrys();
		}
	}

	/**
	 * Clears out the collTeamsRelatedById collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTeamsRelatedById()
	 */
	public function clearTeamsRelatedById()
	{
		$this->collTeamsRelatedById = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTeamsRelatedById collection.
	 *
	 * By default this just sets the collTeamsRelatedById collection to an empty array (like clearcollTeamsRelatedById());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initTeamsRelatedById($overrideExisting = true)
	{
		if (null !== $this->collTeamsRelatedById && !$overrideExisting) {
			return;
		}
		$this->collTeamsRelatedById = new PropelObjectCollection();
		$this->collTeamsRelatedById->setModel('Team');
	}

	/**
	 * Gets an array of Team objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Team is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array Team[] List of Team objects
	 * @throws     PropelException
	 */
	public function getTeamsRelatedById($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collTeamsRelatedById || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamsRelatedById) {
				// return empty collection
				$this->initTeamsRelatedById();
			} else {
				$collTeamsRelatedById = TeamQuery::create(null, $criteria)
					->filterByTeamRelatedByParentId($this)
					->find($con);
				if (null !== $criteria) {
					return $collTeamsRelatedById;
				}
				$this->collTeamsRelatedById = $collTeamsRelatedById;
			}
		}
		return $this->collTeamsRelatedById;
	}

	/**
	 * Sets a collection of TeamRelatedById objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $teamsRelatedById A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTeamsRelatedById(PropelCollection $teamsRelatedById, PropelPDO $con = null)
	{
		$this->teamsRelatedByIdScheduledForDeletion = $this->getTeamsRelatedById(new Criteria(), $con)->diff($teamsRelatedById);

		foreach ($teamsRelatedById as $teamRelatedById) {
			// Fix issue with collection modified by reference
			if ($teamRelatedById->isNew()) {
				$teamRelatedById->setTeamRelatedByParentId($this);
			}
			$this->addTeamRelatedById($teamRelatedById);
		}

		$this->collTeamsRelatedById = $teamsRelatedById;
	}

	/**
	 * Returns the number of related Team objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Team objects.
	 * @throws     PropelException
	 */
	public function countTeamsRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collTeamsRelatedById || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamsRelatedById) {
				return 0;
			} else {
				$query = TeamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByTeamRelatedByParentId($this)
					->count($con);
			}
		} else {
			return count($this->collTeamsRelatedById);
		}
	}

	/**
	 * Method called to associate a Team object to this object
	 * through the Team foreign key attribute.
	 *
	 * @param      Team $l Team
	 * @return     Team The current object (for fluent API support)
	 */
	public function addTeamRelatedById(Team $l)
	{
		if ($this->collTeamsRelatedById === null) {
			$this->initTeamsRelatedById();
		}
		if (!$this->collTeamsRelatedById->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddTeamRelatedById($l);
		}

		return $this;
	}

	/**
	 * @param	TeamRelatedById $teamRelatedById The teamRelatedById object to add.
	 */
	protected function doAddTeamRelatedById($teamRelatedById)
	{
		$this->collTeamsRelatedById[]= $teamRelatedById;
		$teamRelatedById->setTeamRelatedByParentId($this);
	}

	/**
	 * Clears out the collTeamUsers collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTeamUsers()
	 */
	public function clearTeamUsers()
	{
		$this->collTeamUsers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTeamUsers collection.
	 *
	 * By default this just sets the collTeamUsers collection to an empty array (like clearcollTeamUsers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initTeamUsers($overrideExisting = true)
	{
		if (null !== $this->collTeamUsers && !$overrideExisting) {
			return;
		}
		$this->collTeamUsers = new PropelObjectCollection();
		$this->collTeamUsers->setModel('TeamUser');
	}

	/**
	 * Gets an array of TeamUser objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Team is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array TeamUser[] List of TeamUser objects
	 * @throws     PropelException
	 */
	public function getTeamUsers($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collTeamUsers || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamUsers) {
				// return empty collection
				$this->initTeamUsers();
			} else {
				$collTeamUsers = TeamUserQuery::create(null, $criteria)
					->filterByTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collTeamUsers;
				}
				$this->collTeamUsers = $collTeamUsers;
			}
		}
		return $this->collTeamUsers;
	}

	/**
	 * Sets a collection of TeamUser objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $teamUsers A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTeamUsers(PropelCollection $teamUsers, PropelPDO $con = null)
	{
		$this->teamUsersScheduledForDeletion = $this->getTeamUsers(new Criteria(), $con)->diff($teamUsers);

		foreach ($teamUsers as $teamUser) {
			// Fix issue with collection modified by reference
			if ($teamUser->isNew()) {
				$teamUser->setTeam($this);
			}
			$this->addTeamUser($teamUser);
		}

		$this->collTeamUsers = $teamUsers;
	}

	/**
	 * Returns the number of related TeamUser objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related TeamUser objects.
	 * @throws     PropelException
	 */
	public function countTeamUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collTeamUsers || null !== $criteria) {
			if ($this->isNew() && null === $this->collTeamUsers) {
				return 0;
			} else {
				$query = TeamUserQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByTeam($this)
					->count($con);
			}
		} else {
			return count($this->collTeamUsers);
		}
	}

	/**
	 * Method called to associate a TeamUser object to this object
	 * through the TeamUser foreign key attribute.
	 *
	 * @param      TeamUser $l TeamUser
	 * @return     Team The current object (for fluent API support)
	 */
	public function addTeamUser(TeamUser $l)
	{
		if ($this->collTeamUsers === null) {
			$this->initTeamUsers();
		}
		if (!$this->collTeamUsers->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddTeamUser($l);
		}

		return $this;
	}

	/**
	 * @param	TeamUser $teamUser The teamUser object to add.
	 */
	protected function doAddTeamUser($teamUser)
	{
		$this->collTeamUsers[]= $teamUser;
		$teamUser->setTeam($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related TeamUsers from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array TeamUser[] List of TeamUser objects
	 */
	public function getTeamUsersJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TeamUserQuery::create(null, $criteria);
		$query->joinWith('User', $join_behavior);

		return $this->getTeamUsers($query, $con);
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
	 * If this Team is new, it will return
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
					->filterByTeam($this)
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
				$teamProject->setTeam($this);
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
					->filterByTeam($this)
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
	 * @return     Team The current object (for fluent API support)
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
		$teamProject->setTeam($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related TeamProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array TeamProject[] List of TeamProject objects
	 */
	public function getTeamProjectsJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TeamProjectQuery::create(null, $criteria);
		$query->joinWith('Project', $join_behavior);

		return $this->getTeamProjects($query, $con);
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
	 * If this Team is new, it will return
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
					->filterByTeam($this)
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
				$projectEntry->setTeam($this);
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
					->filterByTeam($this)
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
	 * @return     Team The current object (for fluent API support)
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
		$projectEntry->setTeam($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
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
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
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
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
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
	 * Otherwise if this Team is new, it will return
	 * an empty collection; or if this Team has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Team.
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
	 * to the current object by way of the teams_users cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Team is new, it will return
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
					->filterByTeam($this)
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
	 * to the current object by way of the teams_users cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $users A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setUsers(PropelCollection $users, PropelPDO $con = null)
	{
		$teamUsers = TeamUserQuery::create()
			->filterByUser($users)
			->filterByTeam($this)
			->find($con);

		$currentTeamUsers = $this->getTeamUsers();

		$this->usersScheduledForDeletion = $currentTeamUsers->diff($teamUsers);
		$this->collTeamUsers = $teamUsers;

		foreach ($users as $user) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentTeamUsers as $teamUser) {
				if ($teamUser->getUser() == $user) {
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
	 * to the current object by way of the teams_users cross-reference table.
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
					->filterByTeam($this)
					->count($con);
			}
		} else {
			return count($this->collUsers);
		}
	}

	/**
	 * Associate a User object to this object
	 * through the teams_users cross reference table.
	 *
	 * @param      User $user The TeamUser object to relate
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
		$teamUser = new TeamUser();
		$teamUser->setUser($user);
		$this->addTeamUser($teamUser);
	}

	/**
	 * Clears out the collProjects collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addProjects()
	 */
	public function clearProjects()
	{
		$this->collProjects = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collProjects collection.
	 *
	 * By default this just sets the collProjects collection to an empty collection (like clearProjects());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initProjects()
	{
		$this->collProjects = new PropelObjectCollection();
		$this->collProjects->setModel('Project');
	}

	/**
	 * Gets a collection of Project objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Team is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     PropelCollection|array Project[] List of Project objects
	 */
	public function getProjects($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collProjects) {
				// return empty collection
				$this->initProjects();
			} else {
				$collProjects = ProjectQuery::create(null, $criteria)
					->filterByTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collProjects;
				}
				$this->collProjects = $collProjects;
			}
		}
		return $this->collProjects;
	}

	/**
	 * Sets a collection of Project objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $projects A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setProjects(PropelCollection $projects, PropelPDO $con = null)
	{
		$teamProjects = TeamProjectQuery::create()
			->filterByProject($projects)
			->filterByTeam($this)
			->find($con);

		$currentTeamProjects = $this->getTeamProjects();

		$this->projectsScheduledForDeletion = $currentTeamProjects->diff($teamProjects);
		$this->collTeamProjects = $teamProjects;

		foreach ($projects as $project) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentTeamProjects as $teamProject) {
				if ($teamProject->getProject() == $project) {
					$isInCurrent = true;
					break;
				}
			}
			if ($isInCurrent) {
				continue;
			}

			// Fix issue with collection modified by reference
			if ($project->isNew()) {
				$this->doAddProject($project);
			} else {
				$this->addProject($project);
			}
		}

		$this->collProjects = $projects;
	}

	/**
	 * Gets the number of Project objects related by a many-to-many relationship
	 * to the current object by way of the teams_projects cross-reference table.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      boolean $distinct Set to true to force count distinct
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     int the number of related Project objects
	 */
	public function countProjects($criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collProjects || null !== $criteria) {
			if ($this->isNew() && null === $this->collProjects) {
				return 0;
			} else {
				$query = ProjectQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByTeam($this)
					->count($con);
			}
		} else {
			return count($this->collProjects);
		}
	}

	/**
	 * Associate a Project object to this object
	 * through the teams_projects cross reference table.
	 *
	 * @param      Project $project The TeamProject object to relate
	 * @return     void
	 */
	public function addProject(Project $project)
	{
		if ($this->collProjects === null) {
			$this->initProjects();
		}
		if (!$this->collProjects->contains($project)) { // only add it if the **same** object is not already associated
			$this->doAddProject($project);

			$this->collProjects[]= $project;
		}
	}

	/**
	 * @param	Project $project The project object to add.
	 */
	protected function doAddProject($project)
	{
		$teamProject = new TeamProject();
		$teamProject->setProject($project);
		$this->addTeamProject($teamProject);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->parent_id = null;
		$this->name = null;
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
			if ($this->collTeamsRelatedById) {
				foreach ($this->collTeamsRelatedById as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTeamUsers) {
				foreach ($this->collTeamUsers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTeamProjects) {
				foreach ($this->collTeamProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collProjectEntrys) {
				foreach ($this->collProjectEntrys as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collUsers) {
				foreach ($this->collUsers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collProjects) {
				foreach ($this->collProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collTeamsRelatedById instanceof PropelCollection) {
			$this->collTeamsRelatedById->clearIterator();
		}
		$this->collTeamsRelatedById = null;
		if ($this->collTeamUsers instanceof PropelCollection) {
			$this->collTeamUsers->clearIterator();
		}
		$this->collTeamUsers = null;
		if ($this->collTeamProjects instanceof PropelCollection) {
			$this->collTeamProjects->clearIterator();
		}
		$this->collTeamProjects = null;
		if ($this->collProjectEntrys instanceof PropelCollection) {
			$this->collProjectEntrys->clearIterator();
		}
		$this->collProjectEntrys = null;
		if ($this->collUsers instanceof PropelCollection) {
			$this->collUsers->clearIterator();
		}
		$this->collUsers = null;
		if ($this->collProjects instanceof PropelCollection) {
			$this->collProjects->clearIterator();
		}
		$this->collProjects = null;
		$this->aTeamRelatedByParentId = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(TeamPeer::DEFAULT_STRING_FORMAT);
	}

} // BaseTeam
