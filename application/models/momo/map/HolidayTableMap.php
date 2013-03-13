<?php



/**
 * This class defines the structure of the 'holidays' table.
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
class HolidayTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.HolidayTableMap';

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
		$this->setName('holidays');
		$this->setPhpName('Holiday');
		$this->setClassname('Holiday');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('WORKPLAN_ID', 'WorkplanId', 'INTEGER', 'workplans', 'ID', true, null, null);
		$this->addColumn('DATEOFHOLIDAY', 'Dateofholiday', 'DATE', true, null, null);
		$this->addColumn('FULLDAY', 'Fullday', 'BOOLEAN', true, 1, null);
		$this->addColumn('HALFDAY', 'Halfday', 'BOOLEAN', true, 1, null);
		$this->addColumn('ONEHOUR', 'Onehour', 'BOOLEAN', true, 1, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Workplan', 'Workplan', RelationMap::MANY_TO_ONE, array('workplan_id' => 'id', ), 'CASCADE', null);
	} // buildRelations()

} // HolidayTableMap
