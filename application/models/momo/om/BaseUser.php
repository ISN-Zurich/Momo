<?php


/**
 * Base class that represents a row from the 'users' table.
 *
 * 
 *
 * @package    propel.generator.momo.om
 */
abstract class BaseUser extends BaseObject 
{

	/**
	 * Peer class name
	 */
	const PEER = 'UserPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        UserPeer
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
	 * The value for the firstname field.
	 * @var        string
	 */
	protected $firstname;

	/**
	 * The value for the lastname field.
	 * @var        string
	 */
	protected $lastname;

	/**
	 * The value for the email field.
	 * @var        string
	 */
	protected $email;

	/**
	 * The value for the birthdate field.
	 * @var        string
	 */
	protected $birthdate;

	/**
	 * The value for the login field.
	 * @var        string
	 */
	protected $login;

	/**
	 * The value for the password field.
	 * @var        string
	 */
	protected $password;

	/**
	 * The value for the type field.
	 * @var        string
	 */
	protected $type;

	/**
	 * The value for the workload field.
	 * @var        double
	 */
	protected $workload;

	/**
	 * The value for the offdays field.
	 * @var        string
	 */
	protected $offdays;

	/**
	 * The value for the entrydate field.
	 * @var        string
	 */
	protected $entrydate;

	/**
	 * The value for the exitdate field.
	 * @var        string
	 */
	protected $exitdate;

	/**
	 * The value for the role field.
	 * @var        string
	 */
	protected $role;

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
	 * The value for the lastlogin field.
	 * @var        string
	 */
	protected $lastlogin;

	/**
	 * The value for the passwordresettoken field.
	 * @var        string
	 */
	protected $passwordresettoken;

	/**
	 * @var        array TeamUser[] Collection to store aggregation of TeamUser objects.
	 */
	protected $collTeamUsers;

	/**
	 * @var        array UserProject[] Collection to store aggregation of UserProject objects.
	 */
	protected $collUserProjects;

	/**
	 * @var        array Tag[] Collection to store aggregation of Tag objects.
	 */
	protected $collTags;

	/**
	 * @var        array Entry[] Collection to store aggregation of Entry objects.
	 */
	protected $collEntrys;

	/**
	 * @var        array AuditEvent[] Collection to store aggregation of AuditEvent objects.
	 */
	protected $collAuditEvents;

	/**
	 * @var        array OOBooking[] Collection to store aggregation of OOBooking objects.
	 */
	protected $collOOBookings;

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
	 * @var        array Team[] Collection to store aggregation of Team objects.
	 */
	protected $collTeams;

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
	protected $teamsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $projectsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $teamUsersScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $userProjectsScheduledForDeletion = null;

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
	protected $auditEventsScheduledForDeletion = null;

	/**
	 * An array of objects scheduled for deletion.
	 * @var		array
	 */
	protected $oOBookingsScheduledForDeletion = null;

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
	 * Initializes internal state of BaseUser object.
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
	 * Get the [firstname] column value.
	 * 
	 * @return     string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Get the [lastname] column value.
	 * 
	 * @return     string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Get the [email] column value.
	 * 
	 * @return     string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the [optionally formatted] temporal [birthdate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getBirthdate($format = NULL)
	{
		if ($this->birthdate === null) {
			return null;
		}


		if ($this->birthdate === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->birthdate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birthdate, true), $x);
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
	 * Get the [login] column value.
	 * 
	 * @return     string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * Get the [password] column value.
	 * 
	 * @return     string
	 */
	public function getPassword()
	{
		return $this->password;
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
	 * Get the [workload] column value.
	 * 
	 * @return     double
	 */
	public function getWorkload()
	{
		return $this->workload;
	}

	/**
	 * Get the [offdays] column value.
	 * 
	 * @return     string
	 */
	public function getOffdays()
	{
		return $this->offdays;
	}

	/**
	 * Get the [optionally formatted] temporal [entrydate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getEntrydate($format = NULL)
	{
		if ($this->entrydate === null) {
			return null;
		}


		if ($this->entrydate === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->entrydate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->entrydate, true), $x);
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
	 * Get the [optionally formatted] temporal [exitdate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getExitdate($format = NULL)
	{
		if ($this->exitdate === null) {
			return null;
		}


		if ($this->exitdate === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->exitdate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->exitdate, true), $x);
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
	 * Get the [role] column value.
	 * 
	 * @return     string
	 */
	public function getRole()
	{
		return $this->role;
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
	 * Get the [optionally formatted] temporal [lastlogin] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getLastlogin($format = NULL)
	{
		if ($this->lastlogin === null) {
			return null;
		}


		if ($this->lastlogin === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->lastlogin);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->lastlogin, true), $x);
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
	 * Get the [passwordresettoken] column value.
	 * 
	 * @return     string
	 */
	public function getPasswordresettoken()
	{
		return $this->passwordresettoken;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = UserPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [firstname] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setFirstname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->firstname !== $v) {
			$this->firstname = $v;
			$this->modifiedColumns[] = UserPeer::FIRSTNAME;
		}

		return $this;
	} // setFirstname()

	/**
	 * Set the value of [lastname] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setLastname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->lastname !== $v) {
			$this->lastname = $v;
			$this->modifiedColumns[] = UserPeer::LASTNAME;
		}

		return $this;
	} // setLastname()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = UserPeer::EMAIL;
		}

		return $this;
	} // setEmail()

	/**
	 * Sets the value of [birthdate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.
	 *               Empty strings are treated as NULL.
	 * @return     User The current object (for fluent API support)
	 */
	public function setBirthdate($v)
	{
		$dt = PropelDateTime::newInstance($v, null, 'DateTime');
		if ($this->birthdate !== null || $dt !== null) {
			$currentDateAsString = ($this->birthdate !== null && $tmpDt = new DateTime($this->birthdate)) ? $tmpDt->format('Y-m-d') : null;
			$newDateAsString = $dt ? $dt->format('Y-m-d') : null;
			if ($currentDateAsString !== $newDateAsString) {
				$this->birthdate = $newDateAsString;
				$this->modifiedColumns[] = UserPeer::BIRTHDATE;
			}
		} // if either are not null

		return $this;
	} // setBirthdate()

	/**
	 * Set the value of [login] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setLogin($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->login !== $v) {
			$this->login = $v;
			$this->modifiedColumns[] = UserPeer::LOGIN;
		}

		return $this;
	} // setLogin()

	/**
	 * Set the value of [password] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setPassword($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->password !== $v) {
			$this->password = $v;
			$this->modifiedColumns[] = UserPeer::PASSWORD;
		}

		return $this;
	} // setPassword()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = UserPeer::TYPE;
		}

		return $this;
	} // setType()

	/**
	 * Set the value of [workload] column.
	 * 
	 * @param      double $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setWorkload($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->workload !== $v) {
			$this->workload = $v;
			$this->modifiedColumns[] = UserPeer::WORKLOAD;
		}

		return $this;
	} // setWorkload()

	/**
	 * Set the value of [offdays] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setOffdays($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->offdays !== $v) {
			$this->offdays = $v;
			$this->modifiedColumns[] = UserPeer::OFFDAYS;
		}

		return $this;
	} // setOffdays()

	/**
	 * Sets the value of [entrydate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.
	 *               Empty strings are treated as NULL.
	 * @return     User The current object (for fluent API support)
	 */
	public function setEntrydate($v)
	{
		$dt = PropelDateTime::newInstance($v, null, 'DateTime');
		if ($this->entrydate !== null || $dt !== null) {
			$currentDateAsString = ($this->entrydate !== null && $tmpDt = new DateTime($this->entrydate)) ? $tmpDt->format('Y-m-d') : null;
			$newDateAsString = $dt ? $dt->format('Y-m-d') : null;
			if ($currentDateAsString !== $newDateAsString) {
				$this->entrydate = $newDateAsString;
				$this->modifiedColumns[] = UserPeer::ENTRYDATE;
			}
		} // if either are not null

		return $this;
	} // setEntrydate()

	/**
	 * Sets the value of [exitdate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.
	 *               Empty strings are treated as NULL.
	 * @return     User The current object (for fluent API support)
	 */
	public function setExitdate($v)
	{
		$dt = PropelDateTime::newInstance($v, null, 'DateTime');
		if ($this->exitdate !== null || $dt !== null) {
			$currentDateAsString = ($this->exitdate !== null && $tmpDt = new DateTime($this->exitdate)) ? $tmpDt->format('Y-m-d') : null;
			$newDateAsString = $dt ? $dt->format('Y-m-d') : null;
			if ($currentDateAsString !== $newDateAsString) {
				$this->exitdate = $newDateAsString;
				$this->modifiedColumns[] = UserPeer::EXITDATE;
			}
		} // if either are not null

		return $this;
	} // setExitdate()

	/**
	 * Set the value of [role] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setRole($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->role !== $v) {
			$this->role = $v;
			$this->modifiedColumns[] = UserPeer::ROLE;
		}

		return $this;
	} // setRole()

	/**
	 * Sets the value of the [enabled] column.
	 * Non-boolean arguments are converted using the following rules:
	 *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * 
	 * @param      boolean|integer|string $v The new value
	 * @return     User The current object (for fluent API support)
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
			$this->modifiedColumns[] = UserPeer::ENABLED;
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
	 * @return     User The current object (for fluent API support)
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
			$this->modifiedColumns[] = UserPeer::ARCHIVED;
		}

		return $this;
	} // setArchived()

	/**
	 * Sets the value of [lastlogin] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.
	 *               Empty strings are treated as NULL.
	 * @return     User The current object (for fluent API support)
	 */
	public function setLastlogin($v)
	{
		$dt = PropelDateTime::newInstance($v, null, 'DateTime');
		if ($this->lastlogin !== null || $dt !== null) {
			$currentDateAsString = ($this->lastlogin !== null && $tmpDt = new DateTime($this->lastlogin)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
			if ($currentDateAsString !== $newDateAsString) {
				$this->lastlogin = $newDateAsString;
				$this->modifiedColumns[] = UserPeer::LASTLOGIN;
			}
		} // if either are not null

		return $this;
	} // setLastlogin()

	/**
	 * Set the value of [passwordresettoken] column.
	 * 
	 * @param      string $v new value
	 * @return     User The current object (for fluent API support)
	 */
	public function setPasswordresettoken($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->passwordresettoken !== $v) {
			$this->passwordresettoken = $v;
			$this->modifiedColumns[] = UserPeer::PASSWORDRESETTOKEN;
		}

		return $this;
	} // setPasswordresettoken()

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
			$this->firstname = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->lastname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->email = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->birthdate = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->login = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->password = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->type = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->workload = ($row[$startcol + 8] !== null) ? (double) $row[$startcol + 8] : null;
			$this->offdays = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->entrydate = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->exitdate = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->role = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->enabled = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
			$this->archived = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
			$this->lastlogin = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->passwordresettoken = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 17; // 17 = UserPeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating User object", $e);
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collTeamUsers = null;

			$this->collUserProjects = null;

			$this->collTags = null;

			$this->collEntrys = null;

			$this->collAuditEvents = null;

			$this->collOOBookings = null;

			$this->collRegularEntrys = null;

			$this->collProjectEntrys = null;

			$this->collOOEntrys = null;

			$this->collAdjustmentEntrys = null;

			$this->collTeams = null;
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = UserQuery::create()
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
			$con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				UserPeer::addInstanceToPool($this);
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
					TeamUserQuery::create()
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

			if ($this->projectsScheduledForDeletion !== null) {
				if (!$this->projectsScheduledForDeletion->isEmpty()) {
					UserProjectQuery::create()
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

			if ($this->auditEventsScheduledForDeletion !== null) {
				if (!$this->auditEventsScheduledForDeletion->isEmpty()) {
					AuditEventQuery::create()
						->filterByPrimaryKeys($this->auditEventsScheduledForDeletion->getPrimaryKeys(false))
						->delete($con);
					$this->auditEventsScheduledForDeletion = null;
				}
			}

			if ($this->collAuditEvents !== null) {
				foreach ($this->collAuditEvents as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
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

		$this->modifiedColumns[] = UserPeer::ID;
		if (null !== $this->id) {
			throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::ID . ')');
		}

		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(UserPeer::ID)) {
			$modifiedColumns[':p' . $index++]  = '`ID`';
		}
		if ($this->isColumnModified(UserPeer::FIRSTNAME)) {
			$modifiedColumns[':p' . $index++]  = '`FIRSTNAME`';
		}
		if ($this->isColumnModified(UserPeer::LASTNAME)) {
			$modifiedColumns[':p' . $index++]  = '`LASTNAME`';
		}
		if ($this->isColumnModified(UserPeer::EMAIL)) {
			$modifiedColumns[':p' . $index++]  = '`EMAIL`';
		}
		if ($this->isColumnModified(UserPeer::BIRTHDATE)) {
			$modifiedColumns[':p' . $index++]  = '`BIRTHDATE`';
		}
		if ($this->isColumnModified(UserPeer::LOGIN)) {
			$modifiedColumns[':p' . $index++]  = '`LOGIN`';
		}
		if ($this->isColumnModified(UserPeer::PASSWORD)) {
			$modifiedColumns[':p' . $index++]  = '`PASSWORD`';
		}
		if ($this->isColumnModified(UserPeer::TYPE)) {
			$modifiedColumns[':p' . $index++]  = '`TYPE`';
		}
		if ($this->isColumnModified(UserPeer::WORKLOAD)) {
			$modifiedColumns[':p' . $index++]  = '`WORKLOAD`';
		}
		if ($this->isColumnModified(UserPeer::OFFDAYS)) {
			$modifiedColumns[':p' . $index++]  = '`OFFDAYS`';
		}
		if ($this->isColumnModified(UserPeer::ENTRYDATE)) {
			$modifiedColumns[':p' . $index++]  = '`ENTRYDATE`';
		}
		if ($this->isColumnModified(UserPeer::EXITDATE)) {
			$modifiedColumns[':p' . $index++]  = '`EXITDATE`';
		}
		if ($this->isColumnModified(UserPeer::ROLE)) {
			$modifiedColumns[':p' . $index++]  = '`ROLE`';
		}
		if ($this->isColumnModified(UserPeer::ENABLED)) {
			$modifiedColumns[':p' . $index++]  = '`ENABLED`';
		}
		if ($this->isColumnModified(UserPeer::ARCHIVED)) {
			$modifiedColumns[':p' . $index++]  = '`ARCHIVED`';
		}
		if ($this->isColumnModified(UserPeer::LASTLOGIN)) {
			$modifiedColumns[':p' . $index++]  = '`LASTLOGIN`';
		}
		if ($this->isColumnModified(UserPeer::PASSWORDRESETTOKEN)) {
			$modifiedColumns[':p' . $index++]  = '`PASSWORDRESETTOKEN`';
		}

		$sql = sprintf(
			'INSERT INTO `users` (%s) VALUES (%s)',
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
					case '`FIRSTNAME`':						
						$stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
						break;
					case '`LASTNAME`':						
						$stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
						break;
					case '`EMAIL`':						
						$stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
						break;
					case '`BIRTHDATE`':						
						$stmt->bindValue($identifier, $this->birthdate, PDO::PARAM_STR);
						break;
					case '`LOGIN`':						
						$stmt->bindValue($identifier, $this->login, PDO::PARAM_STR);
						break;
					case '`PASSWORD`':						
						$stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
						break;
					case '`TYPE`':						
						$stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
						break;
					case '`WORKLOAD`':						
						$stmt->bindValue($identifier, $this->workload, PDO::PARAM_STR);
						break;
					case '`OFFDAYS`':						
						$stmt->bindValue($identifier, $this->offdays, PDO::PARAM_STR);
						break;
					case '`ENTRYDATE`':						
						$stmt->bindValue($identifier, $this->entrydate, PDO::PARAM_STR);
						break;
					case '`EXITDATE`':						
						$stmt->bindValue($identifier, $this->exitdate, PDO::PARAM_STR);
						break;
					case '`ROLE`':						
						$stmt->bindValue($identifier, $this->role, PDO::PARAM_STR);
						break;
					case '`ENABLED`':
						$stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
						break;
					case '`ARCHIVED`':
						$stmt->bindValue($identifier, (int) $this->archived, PDO::PARAM_INT);
						break;
					case '`LASTLOGIN`':						
						$stmt->bindValue($identifier, $this->lastlogin, PDO::PARAM_STR);
						break;
					case '`PASSWORDRESETTOKEN`':						
						$stmt->bindValue($identifier, $this->passwordresettoken, PDO::PARAM_STR);
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


			if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collTeamUsers !== null) {
					foreach ($this->collTeamUsers as $referrerFK) {
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

				if ($this->collAuditEvents !== null) {
					foreach ($this->collAuditEvents as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collOOBookings !== null) {
					foreach ($this->collOOBookings as $referrerFK) {
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
		$pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getFirstname();
				break;
			case 2:
				return $this->getLastname();
				break;
			case 3:
				return $this->getEmail();
				break;
			case 4:
				return $this->getBirthdate();
				break;
			case 5:
				return $this->getLogin();
				break;
			case 6:
				return $this->getPassword();
				break;
			case 7:
				return $this->getType();
				break;
			case 8:
				return $this->getWorkload();
				break;
			case 9:
				return $this->getOffdays();
				break;
			case 10:
				return $this->getEntrydate();
				break;
			case 11:
				return $this->getExitdate();
				break;
			case 12:
				return $this->getRole();
				break;
			case 13:
				return $this->getEnabled();
				break;
			case 14:
				return $this->getArchived();
				break;
			case 15:
				return $this->getLastlogin();
				break;
			case 16:
				return $this->getPasswordresettoken();
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
		if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
		$keys = UserPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getFirstname(),
			$keys[2] => $this->getLastname(),
			$keys[3] => $this->getEmail(),
			$keys[4] => $this->getBirthdate(),
			$keys[5] => $this->getLogin(),
			$keys[6] => $this->getPassword(),
			$keys[7] => $this->getType(),
			$keys[8] => $this->getWorkload(),
			$keys[9] => $this->getOffdays(),
			$keys[10] => $this->getEntrydate(),
			$keys[11] => $this->getExitdate(),
			$keys[12] => $this->getRole(),
			$keys[13] => $this->getEnabled(),
			$keys[14] => $this->getArchived(),
			$keys[15] => $this->getLastlogin(),
			$keys[16] => $this->getPasswordresettoken(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->collTeamUsers) {
				$result['TeamUsers'] = $this->collTeamUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collUserProjects) {
				$result['UserProjects'] = $this->collUserProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collTags) {
				$result['Tags'] = $this->collTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collEntrys) {
				$result['Entrys'] = $this->collEntrys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collAuditEvents) {
				$result['AuditEvents'] = $this->collAuditEvents->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
			}
			if (null !== $this->collOOBookings) {
				$result['OOBookings'] = $this->collOOBookings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
		$pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setFirstname($value);
				break;
			case 2:
				$this->setLastname($value);
				break;
			case 3:
				$this->setEmail($value);
				break;
			case 4:
				$this->setBirthdate($value);
				break;
			case 5:
				$this->setLogin($value);
				break;
			case 6:
				$this->setPassword($value);
				break;
			case 7:
				$this->setType($value);
				break;
			case 8:
				$this->setWorkload($value);
				break;
			case 9:
				$this->setOffdays($value);
				break;
			case 10:
				$this->setEntrydate($value);
				break;
			case 11:
				$this->setExitdate($value);
				break;
			case 12:
				$this->setRole($value);
				break;
			case 13:
				$this->setEnabled($value);
				break;
			case 14:
				$this->setArchived($value);
				break;
			case 15:
				$this->setLastlogin($value);
				break;
			case 16:
				$this->setPasswordresettoken($value);
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
		$keys = UserPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFirstname($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setLastname($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setEmail($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setBirthdate($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setLogin($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPassword($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setType($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setWorkload($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setOffdays($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setEntrydate($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setExitdate($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setRole($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setEnabled($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setArchived($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setLastlogin($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setPasswordresettoken($arr[$keys[16]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(UserPeer::DATABASE_NAME);

		if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
		if ($this->isColumnModified(UserPeer::FIRSTNAME)) $criteria->add(UserPeer::FIRSTNAME, $this->firstname);
		if ($this->isColumnModified(UserPeer::LASTNAME)) $criteria->add(UserPeer::LASTNAME, $this->lastname);
		if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
		if ($this->isColumnModified(UserPeer::BIRTHDATE)) $criteria->add(UserPeer::BIRTHDATE, $this->birthdate);
		if ($this->isColumnModified(UserPeer::LOGIN)) $criteria->add(UserPeer::LOGIN, $this->login);
		if ($this->isColumnModified(UserPeer::PASSWORD)) $criteria->add(UserPeer::PASSWORD, $this->password);
		if ($this->isColumnModified(UserPeer::TYPE)) $criteria->add(UserPeer::TYPE, $this->type);
		if ($this->isColumnModified(UserPeer::WORKLOAD)) $criteria->add(UserPeer::WORKLOAD, $this->workload);
		if ($this->isColumnModified(UserPeer::OFFDAYS)) $criteria->add(UserPeer::OFFDAYS, $this->offdays);
		if ($this->isColumnModified(UserPeer::ENTRYDATE)) $criteria->add(UserPeer::ENTRYDATE, $this->entrydate);
		if ($this->isColumnModified(UserPeer::EXITDATE)) $criteria->add(UserPeer::EXITDATE, $this->exitdate);
		if ($this->isColumnModified(UserPeer::ROLE)) $criteria->add(UserPeer::ROLE, $this->role);
		if ($this->isColumnModified(UserPeer::ENABLED)) $criteria->add(UserPeer::ENABLED, $this->enabled);
		if ($this->isColumnModified(UserPeer::ARCHIVED)) $criteria->add(UserPeer::ARCHIVED, $this->archived);
		if ($this->isColumnModified(UserPeer::LASTLOGIN)) $criteria->add(UserPeer::LASTLOGIN, $this->lastlogin);
		if ($this->isColumnModified(UserPeer::PASSWORDRESETTOKEN)) $criteria->add(UserPeer::PASSWORDRESETTOKEN, $this->passwordresettoken);

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
		$criteria = new Criteria(UserPeer::DATABASE_NAME);
		$criteria->add(UserPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of User (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setFirstname($this->getFirstname());
		$copyObj->setLastname($this->getLastname());
		$copyObj->setEmail($this->getEmail());
		$copyObj->setBirthdate($this->getBirthdate());
		$copyObj->setLogin($this->getLogin());
		$copyObj->setPassword($this->getPassword());
		$copyObj->setType($this->getType());
		$copyObj->setWorkload($this->getWorkload());
		$copyObj->setOffdays($this->getOffdays());
		$copyObj->setEntrydate($this->getEntrydate());
		$copyObj->setExitdate($this->getExitdate());
		$copyObj->setRole($this->getRole());
		$copyObj->setEnabled($this->getEnabled());
		$copyObj->setArchived($this->getArchived());
		$copyObj->setLastlogin($this->getLastlogin());
		$copyObj->setPasswordresettoken($this->getPasswordresettoken());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			foreach ($this->getTeamUsers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTeamUser($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getUserProjects() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addUserProject($relObj->copy($deepCopy));
				}
			}

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

			foreach ($this->getAuditEvents() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addAuditEvent($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getOOBookings() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addOOBooking($relObj->copy($deepCopy));
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
	 * @return     User Clone of current object.
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
	 * @return     UserPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new UserPeer();
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
		if ('TeamUser' == $relationName) {
			return $this->initTeamUsers();
		}
		if ('UserProject' == $relationName) {
			return $this->initUserProjects();
		}
		if ('Tag' == $relationName) {
			return $this->initTags();
		}
		if ('Entry' == $relationName) {
			return $this->initEntrys();
		}
		if ('AuditEvent' == $relationName) {
			return $this->initAuditEvents();
		}
		if ('OOBooking' == $relationName) {
			return $this->initOOBookings();
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$teamUser->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$teamUser->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related TeamUsers from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array TeamUser[] List of TeamUser objects
	 */
	public function getTeamUsersJoinTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TeamUserQuery::create(null, $criteria);
		$query->joinWith('Team', $join_behavior);

		return $this->getTeamUsers($query, $con);
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$userProject->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$userProject->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related UserProjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array UserProject[] List of UserProject objects
	 */
	public function getUserProjectsJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = UserProjectQuery::create(null, $criteria);
		$query->joinWith('Project', $join_behavior);

		return $this->getUserProjects($query, $con);
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$tag->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$tag->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related Tags from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array Tag[] List of Tag objects
	 */
	public function getTagsJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = TagQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$entry->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$entry->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related Entrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array Entry[] List of Entry objects
	 */
	public function getEntrysJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EntryQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

		return $this->getEntrys($query, $con);
	}

	/**
	 * Clears out the collAuditEvents collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addAuditEvents()
	 */
	public function clearAuditEvents()
	{
		$this->collAuditEvents = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collAuditEvents collection.
	 *
	 * By default this just sets the collAuditEvents collection to an empty array (like clearcollAuditEvents());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @param      boolean $overrideExisting If set to true, the method call initializes
	 *                                        the collection even if it is not empty
	 *
	 * @return     void
	 */
	public function initAuditEvents($overrideExisting = true)
	{
		if (null !== $this->collAuditEvents && !$overrideExisting) {
			return;
		}
		$this->collAuditEvents = new PropelObjectCollection();
		$this->collAuditEvents->setModel('AuditEvent');
	}

	/**
	 * Gets an array of AuditEvent objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this User is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array AuditEvent[] List of AuditEvent objects
	 * @throws     PropelException
	 */
	public function getAuditEvents($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collAuditEvents || null !== $criteria) {
			if ($this->isNew() && null === $this->collAuditEvents) {
				// return empty collection
				$this->initAuditEvents();
			} else {
				$collAuditEvents = AuditEventQuery::create(null, $criteria)
					->filterByUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collAuditEvents;
				}
				$this->collAuditEvents = $collAuditEvents;
			}
		}
		return $this->collAuditEvents;
	}

	/**
	 * Sets a collection of AuditEvent objects related by a one-to-many relationship
	 * to the current object.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $auditEvents A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setAuditEvents(PropelCollection $auditEvents, PropelPDO $con = null)
	{
		$this->auditEventsScheduledForDeletion = $this->getAuditEvents(new Criteria(), $con)->diff($auditEvents);

		foreach ($auditEvents as $auditEvent) {
			// Fix issue with collection modified by reference
			if ($auditEvent->isNew()) {
				$auditEvent->setUser($this);
			}
			$this->addAuditEvent($auditEvent);
		}

		$this->collAuditEvents = $auditEvents;
	}

	/**
	 * Returns the number of related AuditEvent objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related AuditEvent objects.
	 * @throws     PropelException
	 */
	public function countAuditEvents(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collAuditEvents || null !== $criteria) {
			if ($this->isNew() && null === $this->collAuditEvents) {
				return 0;
			} else {
				$query = AuditEventQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByUser($this)
					->count($con);
			}
		} else {
			return count($this->collAuditEvents);
		}
	}

	/**
	 * Method called to associate a AuditEvent object to this object
	 * through the AuditEvent foreign key attribute.
	 *
	 * @param      AuditEvent $l AuditEvent
	 * @return     User The current object (for fluent API support)
	 */
	public function addAuditEvent(AuditEvent $l)
	{
		if ($this->collAuditEvents === null) {
			$this->initAuditEvents();
		}
		if (!$this->collAuditEvents->contains($l)) { // only add it if the **same** object is not already associated
			$this->doAddAuditEvent($l);
		}

		return $this;
	}

	/**
	 * @param	AuditEvent $auditEvent The auditEvent object to add.
	 */
	protected function doAddAuditEvent($auditEvent)
	{
		$this->collAuditEvents[]= $auditEvent;
		$auditEvent->setUser($this);
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$oOBooking->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$oOBooking->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related OOBookings from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOBooking[] List of OOBooking objects
	 */
	public function getOOBookingsJoinOOBookingType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOBookingQuery::create(null, $criteria);
		$query->joinWith('OOBookingType', $join_behavior);

		return $this->getOOBookings($query, $con);
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$regularEntry->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$regularEntry->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related RegularEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$projectEntry->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$projectEntry->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related ProjectEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$oOEntry->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$oOEntry->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related OOEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array OOEntry[] List of OOEntry objects
	 */
	public function getOOEntrysJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = OOEntryQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

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
	 * If this User is new, it will return
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
					->filterByUser($this)
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
				$adjustmentEntry->setUser($this);
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
					->filterByUser($this)
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
	 * @return     User The current object (for fluent API support)
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
		$adjustmentEntry->setUser($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related AdjustmentEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
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
	 * Otherwise if this User is new, it will return
	 * an empty collection; or if this User has previously
	 * been saved, it will retrieve related AdjustmentEntrys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in User.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array AdjustmentEntry[] List of AdjustmentEntry objects
	 */
	public function getAdjustmentEntrysJoinDay($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = AdjustmentEntryQuery::create(null, $criteria);
		$query->joinWith('Day', $join_behavior);

		return $this->getAdjustmentEntrys($query, $con);
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
	 * to the current object by way of the teams_users cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this User is new, it will return
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
					->filterByUser($this)
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
	 * to the current object by way of the teams_users cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $teams A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setTeams(PropelCollection $teams, PropelPDO $con = null)
	{
		$teamUsers = TeamUserQuery::create()
			->filterByTeam($teams)
			->filterByUser($this)
			->find($con);

		$currentTeamUsers = $this->getTeamUsers();

		$this->teamsScheduledForDeletion = $currentTeamUsers->diff($teamUsers);
		$this->collTeamUsers = $teamUsers;

		foreach ($teams as $team) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentTeamUsers as $teamUser) {
				if ($teamUser->getTeam() == $team) {
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
	 * to the current object by way of the teams_users cross-reference table.
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
					->filterByUser($this)
					->count($con);
			}
		} else {
			return count($this->collTeams);
		}
	}

	/**
	 * Associate a Team object to this object
	 * through the teams_users cross reference table.
	 *
	 * @param      Team $team The TeamUser object to relate
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
		$teamUser = new TeamUser();
		$teamUser->setTeam($team);
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
	 * to the current object by way of the users_projects cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this User is new, it will return
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
					->filterByUser($this)
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
	 * to the current object by way of the users_projects cross-reference table.
	 * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
	 * and new objects from the given Propel collection.
	 *
	 * @param      PropelCollection $projects A Propel collection.
	 * @param      PropelPDO $con Optional connection object
	 */
	public function setProjects(PropelCollection $projects, PropelPDO $con = null)
	{
		$userProjects = UserProjectQuery::create()
			->filterByProject($projects)
			->filterByUser($this)
			->find($con);

		$currentUserProjects = $this->getUserProjects();

		$this->projectsScheduledForDeletion = $currentUserProjects->diff($userProjects);
		$this->collUserProjects = $userProjects;

		foreach ($projects as $project) {
			// Skip objects that are already in the current collection.
			$isInCurrent = false;
			foreach ($currentUserProjects as $userProject) {
				if ($userProject->getProject() == $project) {
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
	 * to the current object by way of the users_projects cross-reference table.
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
					->filterByUser($this)
					->count($con);
			}
		} else {
			return count($this->collProjects);
		}
	}

	/**
	 * Associate a Project object to this object
	 * through the users_projects cross reference table.
	 *
	 * @param      Project $project The UserProject object to relate
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
		$userProject = new UserProject();
		$userProject->setProject($project);
		$this->addUserProject($userProject);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->firstname = null;
		$this->lastname = null;
		$this->email = null;
		$this->birthdate = null;
		$this->login = null;
		$this->password = null;
		$this->type = null;
		$this->workload = null;
		$this->offdays = null;
		$this->entrydate = null;
		$this->exitdate = null;
		$this->role = null;
		$this->enabled = null;
		$this->archived = null;
		$this->lastlogin = null;
		$this->passwordresettoken = null;
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
			if ($this->collTeamUsers) {
				foreach ($this->collTeamUsers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collUserProjects) {
				foreach ($this->collUserProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
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
			if ($this->collAuditEvents) {
				foreach ($this->collAuditEvents as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collOOBookings) {
				foreach ($this->collOOBookings as $o) {
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
			if ($this->collTeams) {
				foreach ($this->collTeams as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collProjects) {
				foreach ($this->collProjects as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		if ($this->collTeamUsers instanceof PropelCollection) {
			$this->collTeamUsers->clearIterator();
		}
		$this->collTeamUsers = null;
		if ($this->collUserProjects instanceof PropelCollection) {
			$this->collUserProjects->clearIterator();
		}
		$this->collUserProjects = null;
		if ($this->collTags instanceof PropelCollection) {
			$this->collTags->clearIterator();
		}
		$this->collTags = null;
		if ($this->collEntrys instanceof PropelCollection) {
			$this->collEntrys->clearIterator();
		}
		$this->collEntrys = null;
		if ($this->collAuditEvents instanceof PropelCollection) {
			$this->collAuditEvents->clearIterator();
		}
		$this->collAuditEvents = null;
		if ($this->collOOBookings instanceof PropelCollection) {
			$this->collOOBookings->clearIterator();
		}
		$this->collOOBookings = null;
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
		if ($this->collTeams instanceof PropelCollection) {
			$this->collTeams->clearIterator();
		}
		$this->collTeams = null;
		if ($this->collProjects instanceof PropelCollection) {
			$this->collProjects->clearIterator();
		}
		$this->collProjects = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(UserPeer::DEFAULT_STRING_FORMAT);
	}

} // BaseUser
