<?php



/**
 * This class defines the structure of the 'teams_users' table.
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
class TeamUserTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.TeamUserTableMap';

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
		$this->setName('teams_users');
		$this->setPhpName('TeamUser');
		$this->setClassname('TeamUser');
		$this->setPackage('momo');
		$this->setUseIdGenerator(false);
		$this->setIsCrossRef(true);
		// columns
		$this->addForeignPrimaryKey('TEAM_ID', 'TeamId', 'INTEGER' , 'teams', 'ID', true, null, null);
		$this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'users', 'ID', true, null, null);
		$this->addColumn('PRIMARY', 'Primary', 'BOOLEAN', true, 1, false);
		$this->addColumn('SECONDARY', 'Secondary', 'BOOLEAN', true, 1, false);
		$this->addColumn('LEADER', 'Leader', 'BOOLEAN', true, 1, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
		$this->addRelation('Team', 'Team', RelationMap::MANY_TO_ONE, array('team_id' => 'id', ), null, null);
	} // buildRelations()

} // TeamUserTableMap
