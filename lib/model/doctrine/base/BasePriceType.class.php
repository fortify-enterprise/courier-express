<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('PriceType', 'doctrine');

/**
 * BasePriceType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $type
 * @property Doctrine_Collection $WeightPrice
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string              getType()        Returns the current record's "type" value
 * @method Doctrine_Collection getWeightPrice() Returns the current record's "WeightPrice" collection
 * @method PriceType           setId()          Sets the current record's "id" value
 * @method PriceType           setType()        Sets the current record's "type" value
 * @method PriceType           setWeightPrice() Sets the current record's "WeightPrice" collection
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePriceType extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('price_type');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('type', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 30,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('WeightPrice', array(
             'local' => 'id',
             'foreign' => 'price_type_id'));
    }
}