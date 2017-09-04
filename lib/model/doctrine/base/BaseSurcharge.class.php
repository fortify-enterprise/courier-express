<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Surcharge', 'doctrine');

/**
 * BaseSurcharge
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $courier_id
 * @property integer $surcharge_type_id
 * @property decimal $amt_limit
 * @property decimal $amount
 * @property Courier $Courier
 * @property Courier $Courier_2
 * @property SurchargeType $SurchargeType
 * @property SurchargeType $SurchargeType_4
 * 
 * @method integer       getId()                Returns the current record's "id" value
 * @method integer       getCourierId()         Returns the current record's "courier_id" value
 * @method integer       getSurchargeTypeId()   Returns the current record's "surcharge_type_id" value
 * @method decimal       getAmtLimit()          Returns the current record's "amt_limit" value
 * @method decimal       getAmount()            Returns the current record's "amount" value
 * @method Courier       getCourier()           Returns the current record's "Courier" value
 * @method Courier       getCourier2()          Returns the current record's "Courier_2" value
 * @method SurchargeType getSurchargeType()     Returns the current record's "SurchargeType" value
 * @method SurchargeType getSurchargeType4()    Returns the current record's "SurchargeType_4" value
 * @method Surcharge     setId()                Sets the current record's "id" value
 * @method Surcharge     setCourierId()         Sets the current record's "courier_id" value
 * @method Surcharge     setSurchargeTypeId()   Sets the current record's "surcharge_type_id" value
 * @method Surcharge     setAmtLimit()          Sets the current record's "amt_limit" value
 * @method Surcharge     setAmount()            Sets the current record's "amount" value
 * @method Surcharge     setCourier()           Sets the current record's "Courier" value
 * @method Surcharge     setCourier2()          Sets the current record's "Courier_2" value
 * @method Surcharge     setSurchargeType()     Sets the current record's "SurchargeType" value
 * @method Surcharge     setSurchargeType4()    Sets the current record's "SurchargeType_4" value
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSurcharge extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('surcharge');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('courier_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('surcharge_type_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('amt_limit', 'decimal', 7, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0.00',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 7,
             'scale' => '2',
             ));
        $this->hasColumn('amount', 'decimal', 7, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 7,
             'scale' => '2',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Courier', array(
             'local' => 'courier_id',
             'foreign' => 'id'));

        $this->hasOne('Courier as Courier_2', array(
             'local' => 'courier_id',
             'foreign' => 'id'));

        $this->hasOne('SurchargeType', array(
             'local' => 'surcharge_type_id',
             'foreign' => 'id'));

        $this->hasOne('SurchargeType as SurchargeType_4', array(
             'local' => 'surcharge_type_id',
             'foreign' => 'id'));
    }
}