<?php



/**
 * This class defines the structure of the 'teams_projects' table.
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
class TeamProjectTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.TeamProjectTableMap';

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
		$this->setName('teams_projects');
		$this->setPhpName('TeamProject');
		$this->setClassname('TeamProject');
		$this->setPackage('momo');
		$this->setUseIdGenerator(false);
		$this->setIsCrossRef(true);
		// columns
		$this->addForeignPrimaryKey('TEAM_ID', 'TeamId', 'INTEGER' , 'teams', 'ID', true, null, null);
		$this->addForeignPrimaryKey('PROJECT_ID', 'ProjectId', 'INTEGER' , 'projects', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Project', 'Project', RelationMap::MANY_TO_ONE, array('project_id' => 'id', ), null, null);
		$this->addRelation('Team', 'Team', RelationMap::MANY_TO_ONE, array('team_id' => 'id', ), null, null);
	} // buildRelations()

} // TeamProjectTableMap
