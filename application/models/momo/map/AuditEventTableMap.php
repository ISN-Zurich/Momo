<?php



/**
 * This class defines the structure of the 'auditevents' table.
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
class AuditEventTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'momo.map.AuditEventTableMap';

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
		$this->setName('auditevents');
		$this->setPhpName('AuditEvent');
		$this->setClassname('AuditEvent');
		$this->setPackage('momo');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'users', 'ID', true, null, null);
		$this->addColumn('TIMESTAMP', 'Timestamp', 'TIMESTAMP', true, null, null);
		$this->addColumn('SOURCEKEY', 'Sourcekey', 'VARCHAR', true, 50, null);
		$this->addColumn('ACTION', 'Action', 'VARCHAR', true, 50, null);
		$this->addColumn('DETAILS', 'Details', 'LONGVARCHAR', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
	} // buildRelations()

} // AuditEventTableMap
