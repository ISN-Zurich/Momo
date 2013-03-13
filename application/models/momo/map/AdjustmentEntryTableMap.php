<?php



/**
 * This class defines the structure of the 'adjustmententries' table.
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
class AdjustmentEntryTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.AdjustmentEntryTableMap';

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
		$this->setName('adjustmententries');
		$this->setPhpName('AdjustmentEntry');
		$this->setClassname('AdjustmentEntry');
		$this->setPackage('momo');
		$this->setUseIdGenerator(false);
		// columns
		$this->addColumn('TYPE', 'Type', 'VARCHAR', true, 50, null);
		$this->addColumn('CREATOR', 'Creator', 'VARCHAR', true, 50, null);
		$this->addColumn('VALUE', 'Value', 'FLOAT', true, null, null);
		$this->addColumn('REASON', 'Reason', 'VARCHAR', false, 255, null);
		$this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'entries', 'ID', true, null, null);
		$this->addForeignKey('DAY_ID', 'DayId', 'INTEGER', 'days', 'ID', true, null, null);
		$this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'users', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Entry', 'Entry', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
		$this->addRelation('Day', 'Day', RelationMap::MANY_TO_ONE, array('day_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
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
			'concrete_inheritance' => array('extends' => 'entries', 'descendant_column' => 'descendant_class', 'copy_data_to_parent' => 'true', 'schema' => '', ),
		);
	} // getBehaviors()

} // AdjustmentEntryTableMap
