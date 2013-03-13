<?php



/**
 * This class defines the structure of the 'entries' table.
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
class EntryTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.EntryTableMap';

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
		$this->setName('entries');
		$this->setPhpName('Entry');
		$this->setClassname('Entry');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('DAY_ID', 'DayId', 'INTEGER', 'days', 'ID', true, null, null);
		$this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'users', 'ID', true, null, null);
		$this->addColumn('DESCENDANT_CLASS', 'DescendantClass', 'VARCHAR', false, 100, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Day', 'Day', RelationMap::MANY_TO_ONE, array('day_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('RegularEntry', 'RegularEntry', RelationMap::ONE_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
		$this->addRelation('ProjectEntry', 'ProjectEntry', RelationMap::ONE_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
		$this->addRelation('OOEntry', 'OOEntry', RelationMap::ONE_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
		$this->addRelation('AdjustmentEntry', 'AdjustmentEntry', RelationMap::ONE_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
	} // buildRelations()

	/**
	 *
	 * Gets the list of behaviors registered for this table
	 *
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'concrete_inheritance_parent' => array('descendant_column' => 'descendant_class', ),
		);
	} // getBehaviors()

} // EntryTableMap
