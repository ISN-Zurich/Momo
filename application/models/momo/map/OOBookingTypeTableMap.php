<?php



/**
 * This class defines the structure of the 'oobookingtypes' table.
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
class OOBookingTypeTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.OOBookingTypeTableMap';

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
		$this->setName('oobookingtypes');
		$this->setPhpName('OOBookingType');
		$this->setClassname('OOBookingType');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('TYPE', 'Type', 'VARCHAR', true, 50, null);
		$this->addColumn('PAID', 'Paid', 'BOOLEAN', true, 1, null);
		$this->addColumn('CREATOR', 'Creator', 'VARCHAR', true, 50, null);
		$this->addColumn('BOOKABLEINDAYS', 'Bookableindays', 'BOOLEAN', true, 1, null);
		$this->addColumn('BOOKABLEINHALFDAYS', 'Bookableinhalfdays', 'BOOLEAN', true, 1, null);
		$this->addColumn('RGBCOLORVALUE', 'Rgbcolorvalue', 'CHAR', false, 6, null);
		$this->addColumn('ENABLED', 'Enabled', 'BOOLEAN', true, 1, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('OOBooking', 'OOBooking', RelationMap::ONE_TO_MANY, array('id' => 'oobookingtype_id', ), null, null, 'OOBookings');
	} // buildRelations()

} // OOBookingTypeTableMap
