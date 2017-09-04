<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Settings', 'doctrine');

/**
 * BaseSettings
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $setting
 * @property string $value
 * 
 * @method integer  getId()      Returns the current record's "id" value
 * @method string   getSetting() Returns the current record's "setting" value
 * @method string   getValue()   Returns the current record's "value" value
 * @method Settings setId()      Sets the current record's "id" value
 * @method Settings setSetting() Sets the current record's "setting" value
 * @method Settings setValue()   Sets the current record's "value" value
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSettings extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('settings');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('setting', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('value', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}