<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version1 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->dropTable('piece');
        $this->dropTable('piece_type');
        $this->createTable('payout', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'amount' => 
             array(
              'type' => 'decimal',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'default' => '0.00',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '7',
              'scale' => '2',
             ),
             'date' => 
             array(
              'type' => 'timestamp',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '25',
             ),
             'courier_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'payment_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
        $this->createTable('zone_price', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'from_zone_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'to_zone_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'service_level_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'price' => 
             array(
              'type' => 'decimal',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '7',
              'scale' => '2',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
              1 => 'from_zone_id',
              2 => 'to_zone_id',
              3 => 'service_level_id',
             ),
             ));
        $this->createTable('zone_set', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'zone_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'element' => 
             array(
              'type' => 'string',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '127',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
        $this->createTable('zone_type', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'type' => 
             array(
              'type' => 'string',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '45',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->createTable('piece', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'package_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'name' => 
             array(
              'type' => 'string',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '',
              'autoincrement' => '',
              'length' => '45',
             ),
             'type_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '8',
             ),
             'description' => 
             array(
              'type' => 'string',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '',
              'autoincrement' => '',
              'length' => '255',
             ),
             ), array(
             'type' => '',
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => '',
             'charset' => '',
             ));
        $this->createTable('piece_type', array(
             'id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '1',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'type' => 
             array(
              'type' => 'string',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '',
              'notnull' => '1',
              'autoincrement' => '',
              'length' => '45',
             ),
             ), array(
             'type' => '',
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => '',
             'charset' => '',
             ));
        $this->dropTable('payout');
        $this->dropTable('zone_price');
        $this->dropTable('zone_set');
        $this->dropTable('zone_type');
    }
}