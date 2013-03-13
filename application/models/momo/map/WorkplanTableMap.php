<?php



/**
 * This class defines the structure of the 'workplans' table.
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
class WorkplanTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.WorkplanTableMap';

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
		$this->setName('workplans');
		$this->setPhpName('Workplan');
		$this->setClassname('Workplan');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('YEAR', 'Year', 'INTEGER', true, null, null);
		$this->addColumn('WEEKLYWORKHOURS', 'Weeklyworkhours', 'INTEGER', true, null, null);
		$this->addColumn('ANNUALVACATIONDAYSUPTO19', 'Annualvacationdaysupto19', 'INTEGER', true, null, null);
		$this->addColumn('ANNUALVACATIONDAYS20TO49', 'Annualvacationdays20to49', 'INTEGER', true, null, null);
		$this->addColumn('ANNUALVACATIONDAYSFROM50', 'Annualvacationdaysfrom50', 'INTEGER', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Holiday', 'Holiday', RelationMap::ONE_TO_MANY, array('id' => 'workplan_id', ), 'CASCADE', null, 'Holidays');
		$this->addRelation('Day', 'Day', RelationMap::ONE_TO_MANY, array('id' => 'workplan_id', ), 'CASCADE', null, 'Days');
	} // buildRelations()

} // WorkplanTableMap
