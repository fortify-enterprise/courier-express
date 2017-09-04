<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('ZonePriceLevel', 'doctrine');

/**
 * BaseZonePriceLevel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $from_zone_id
 * @property integer $to_zone_id
 * @property integer $price_level_id
 * @property Zone $Zone
 * @property Zone $Zone_2
 * @property PriceLevel $PriceLevel
 * 
 * @method integer        getId()             Returns the current record's "id" value
 * @method integer        getFromZoneId()     Returns the current record's "from_zone_id" value
 * @method integer        getToZoneId()       Returns the current record's "to_zone_id" value
 * @method integer        getPriceLevelId()   Returns the current record's "price_level_id" value
 * @method Zone           getZone()           Returns the current record's "Zone" value
 * @method Zone           getZone2()          Returns the current record's "Zone_2" value
 * @method PriceLevel     getPriceLevel()     Returns the current record's "PriceLevel" value
 * @method ZonePriceLevel setId()             Sets the current record's "id" value
 * @method ZonePriceLevel setFromZoneId()     Sets the current record's "from_zone_id" value
 * @method ZonePriceLevel setToZoneId()       Sets the current record's "to_zone_id" value
 * @method ZonePriceLevel setPriceLevelId()   Sets the current record's "price_level_id" value
 * @method ZonePriceLevel setZone()           Sets the current record's "Zone" value
 * @method ZonePriceLevel setZone2()          Sets the current record's "Zone_2" value
 * @method ZonePriceLevel setPriceLevel()     Sets the current record's "PriceLevel" value
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseZonePriceLevel extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('zone_price_level');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('from_zone_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('to_zone_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('price_level_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Zone', array(
             'local' => 'from_zone_id',
             'foreign' => 'id'));

        $this->hasOne('Zone as Zone_2', array(
             'local' => 'to_zone_id',
             'foreign' => 'id'));

        $this->hasOne('PriceLevel', array(
             'local' => 'price_level_id',
             'foreign' => 'id'));
    }
}