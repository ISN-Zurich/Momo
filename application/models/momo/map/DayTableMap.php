<?php



/**
 * This class defines the structure of the 'days' table.
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
class DayTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.DayTableMap';

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
		$this->setName('days');
		$this->setPhpName('Day');
		$this->setClassname('Day');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('WORKPLAN_ID', 'WorkplanId', 'INTEGER', 'workplans', 'ID', true, null, null);
		$this->addColumn('DATEOFDAY', 'Dateofday', 'DATE', true, null, null);
		$this->addColumn('WEEKDAYNAME', 'Weekdayname', 'CHAR', true, 3, null);
		$this->addColumn('ISO8601WEEK', 'Iso8601week', 'INTEGER', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Workplan', 'Workplan', RelationMap::MANY_TO_ONE, array('workplan_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('Tag', 'Tag', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'Tags');
		$this->addRelation('Entry', 'Entry', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'Entrys');
		$this->addRelation('RegularEntry', 'RegularEntry', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'RegularEntrys');
		$this->addRelation('ProjectEntry', 'ProjectEntry', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'ProjectEntrys');
		$this->addRelation('OOEntry', 'OOEntry', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'OOEntrys');
		$this->addRelation('AdjustmentEntry', 'AdjustmentEntry', RelationMap::ONE_TO_MANY, array('id' => 'day_id', ), 'CASCADE', null, 'AdjustmentEntrys');
	} // buildRelations()

} // DayTableMap
