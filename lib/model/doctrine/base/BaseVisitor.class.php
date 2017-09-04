<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Visitor', 'doctrine');

/**
 * BaseVisitor
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $ip
 * @property string $status
 * @property string $country_code
 * @property string $country_name
 * @property string $region_code
 * @property string $region_name
 * @property string $city
 * @property string $zip_postal_code
 * @property string $latitude
 * @property string $longitude
 * @property string $timezone_name
 * @property string $gmtoffset
 * @property integer $isdst
 * @property string $agent
 * @property timestamp $updated_ts
 * 
 * @method integer   getId()              Returns the current record's "id" value
 * @method string    getIp()              Returns the current record's "ip" value
 * @method string    getStatus()          Returns the current record's "status" value
 * @method string    getCountryCode()     Returns the current record's "country_code" value
 * @method string    getCountryName()     Returns the current record's "country_name" value
 * @method string    getRegionCode()      Returns the current record's "region_code" value
 * @method string    getRegionName()      Returns the current record's "region_name" value
 * @method string    getCity()            Returns the current record's "city" value
 * @method string    getZipPostalCode()   Returns the current record's "zip_postal_code" value
 * @method string    getLatitude()        Returns the current record's "latitude" value
 * @method string    getLongitude()       Returns the current record's "longitude" value
 * @method string    getTimezoneName()    Returns the current record's "timezone_name" value
 * @method string    getGmtoffset()       Returns the current record's "gmtoffset" value
 * @method integer   getIsdst()           Returns the current record's "isdst" value
 * @method string    getAgent()           Returns the current record's "agent" value
 * @method timestamp getUpdatedTs()       Returns the current record's "updated_ts" value
 * @method Visitor   setId()              Sets the current record's "id" value
 * @method Visitor   setIp()              Sets the current record's "ip" value
 * @method Visitor   setStatus()          Sets the current record's "status" value
 * @method Visitor   setCountryCode()     Sets the current record's "country_code" value
 * @method Visitor   setCountryName()     Sets the current record's "country_name" value
 * @method Visitor   setRegionCode()      Sets the current record's "region_code" value
 * @method Visitor   setRegionName()      Sets the current record's "region_name" value
 * @method Visitor   setCity()            Sets the current record's "city" value
 * @method Visitor   setZipPostalCode()   Sets the current record's "zip_postal_code" value
 * @method Visitor   setLatitude()        Sets the current record's "latitude" value
 * @method Visitor   setLongitude()       Sets the current record's "longitude" value
 * @method Visitor   setTimezoneName()    Sets the current record's "timezone_name" value
 * @method Visitor   setGmtoffset()       Sets the current record's "gmtoffset" value
 * @method Visitor   setIsdst()           Sets the current record's "isdst" value
 * @method Visitor   setAgent()           Sets the current record's "agent" value
 * @method Visitor   setUpdatedTs()       Sets the current record's "updated_ts" value
 * 
 * @package    cexp
 * @subpackage model
 * @author     Courier Express
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseVisitor extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('visitor');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('ip', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('status', 'string', 10, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('country_code', 'string', 10, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('country_name', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('region_code', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('region_name', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('city', 'string', 128, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 128,
             ));
        $this->hasColumn('zip_postal_code', 'string', 16, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 16,
             ));
        $this->hasColumn('latitude', 'string', 10, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('longitude', 'string', 10, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('timezone_name', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('gmtoffset', 'string', 16, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 16,
             ));
        $this->hasColumn('isdst', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('agent', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('updated_ts', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}