<?php



/**
 * This class defines the structure of the 'regularentrytypes' table.
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
class RegularEntryTypeTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.RegularEntryTypeTableMap';

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
		$this->setName('regularentrytypes');
		$this->setPhpName('RegularEntryType');
		$this->setClassname('RegularEntryType');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('TYPE', 'Type', 'VARCHAR', true, 50, null);
		$this->addColumn('CREATOR', 'Creator', 'VARCHAR', true, 50, null);
		$this->addColumn('WORKTIMECREDITAWARDED', 'Worktimecreditawarded', 'BOOLEAN', true, 1, null);
		$this->addColumn('ENABLED', 'Enabled', 'BOOLEAN', true, 1, null);
		$this->addColumn('DEFAULTTYPE', 'Defaulttype', 'BOOLEAN', true, 1, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('RegularEntry', 'RegularEntry', RelationMap::ONE_TO_MANY, array('id' => 'regularentrytype_id', ), null, null, 'RegularEntrys');
	} // buildRelations()

} // RegularEntryTypeTableMap
