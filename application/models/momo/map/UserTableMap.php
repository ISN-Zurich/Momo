<?php



/**
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.momo.map
 */
class UserTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.UserTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
		// attributes
		$this->setName('users');
		$this->setPhpName('User');
		$this->setClassname('User');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('FIRSTNAME', 'Firstname', 'VARCHAR', true, 100, null);
		$this->addColumn('LASTNAME', 'Lastname', 'VARCHAR', true, 100, null);
		$this->addColumn('EMAIL', 'Email', 'VARCHAR', true, 100, null);
		$this->addColumn('BIRTHDATE', 'Birthdate', 'DATE', true, null, null);
		$this->addColumn('LOGIN', 'Login', 'VARCHAR', true, 50, null);
		$this->addColumn('PASSWORD', 'Password', 'CHAR', false, 32, null);
		$this->addColumn('TYPE', 'Type', 'VARCHAR', true, 50, null);
		$this->addColumn('WORKLOAD', 'Workload', 'FLOAT', true, null, null);
		$this->addColumn('OFFDAYS', 'Offdays', 'LONGVARCHAR', true, null, null);
		$this->addColumn('ENTRYDATE', 'Entrydate', 'DATE', true, null, null);
		$this->addColumn('EXITDATE', 'Exitdate', 'DATE', true, null, null);
		$this->addColumn('ROLE', 'Role', 'VARCHAR', true, 50, null);
		$this->addColumn('ENABLED', 'Enabled', 'BOOLEAN', true, 1, true);
		$this->addColumn('ARCHIVED', 'Archived', 'BOOLEAN', true, 1, false);
		$this->addColumn('LASTLOGIN', 'Lastlogin', 'TIMESTAMP', false, null, null);
		$this->addColumn('PASSWORDRESETTOKEN', 'Passwordresettoken', 'CHAR', false, 36, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('TeamUser', 'TeamUser', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'TeamUsers');
		$this->addRelation('UserProject', 'UserProject', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), null, null, 'UserProjects');
		$this->addRelation('Tag', 'Tag', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'Tags');
		$this->addRelation('Entry', 'Entry', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'Entrys');
		$this->addRelation('AuditEvent', 'AuditEvent', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'AuditEvents');
		$this->addRelation('OOBooking', 'OOBooking', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'OOBookings');
		$this->addRelation('RegularEntry', 'RegularEntry', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'RegularEntrys');
		$this->addRelation('ProjectEntry', 'ProjectEntry', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'ProjectEntrys');
		$this->addRelation('OOEntry', 'OOEntry', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'OOEntrys');
		$this->addRelation('AdjustmentEntry', 'AdjustmentEntry', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'AdjustmentEntrys');
		$this->addRelation('Team', 'Team', RelationMap::MANY_TO_MANY, array(), null, null, 'Teams');
		$this->addRelation('Project', 'Project', RelationMap::MANY_TO_MANY, array(), null, null, 'Projects');
	} // buildRelations()

} // UserTableMap
