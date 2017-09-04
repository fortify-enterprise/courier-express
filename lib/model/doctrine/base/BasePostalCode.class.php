<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('PostalCode', 'doctrine');

/**
 * BasePostalCode
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $postal_code
 * @property string $city
 * @property string $province
 * @property string $province_code
 * @property string $city_type
 * @property float $latitude
 * @property float $longitude
 * 
 * @method integer    getId()            Returns the current record's "id" value
 * @method string     getPostalCode()    Returns the current record's "postal_code" value
 * @method string     getCity()          Returns the current record's "city" value
 * @method string     getProvince()      Returns the current record's "province" value
 * @method string     getProvinceCode()  Returns the current record's "province_code" value
 * @method string     getCityType()      Returns the current record's "city_type" value
 * @method float      getLatitude()      Returns the current record's "latitude" value
 * @method float      getLongitude()     Returns the current record's "longitude" value
 * @method PostalCode setId()            Sets the current record's "id" value
 * @method PostalCode setPostalCode()    Sets the current record's "postal_code" value
 * @method PostalCode setCity()          Sets the current record's "city" value
 * @method PostalCode setProvince()      Sets the current record's "province" value
 * @method PostalCode setProvinceCode()  Sets the current record's "province_code" value
 * @method PostalCode setCityType()      Sets the current record's "city_type" value
 * @method PostalCode setLatitude()      Sets the current record's "latitude" value
 * @method PostalCode setLongitude()     Sets the current record's "longitude" value
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePostalCode extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('postal_code');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('postal_code', 'string', 7, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 7,
             ));
        $this->hasColumn('city', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('province', 'string', 40, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 40,
             ));
        $this->hasColumn('province_code', 'string', 2, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('city_type', 'string', 1, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 1,
             ));
        $this->hasColumn('latitude', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('longitude', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}