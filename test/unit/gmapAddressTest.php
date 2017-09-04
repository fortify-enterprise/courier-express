<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(8, new lime_output_color());
 
$tests = array(
  array(false, '', 'empty value'),
  array(false, 'string value', 'string value'),
  array(false, array(), 'empty array'),
  array(false, array('address' => 'my awesome address'), 'incomplete address'),
  array(false, array('address' => 'my awesome address', 'latitude' => 'String', 'longitude' => 23), 'invalid values'),
  array(false, array('address' => 'my awesome address', 'latitude' => 200, 'longitude' => 23), 'invalid values'),
  array(true, array('address' => 'my awesome address', 'latitude' => '2.294359', 'longitude' => '48.858205'), 'valid value')
);
 
$v = new sfValidatorGMapAddress();
 
$t->diag("Testing sfValidatorGMapAddress");
 
foreach($tests as $test)
{
  list($validity, $value, $message) = $test;
 
  try
  {
    $v->clean($value);
    $catched = false;
  }
  catch(sfValidatorError $e)
  {
    $catched = true;
  }
 
  $t->ok($validity != $catched, '::clean() '.$message);
}

// test address convertion
$geo_locate = new Geo_Locate();

$address = array();
$address['apt_unit']          = "123";
$address['street_number']     = "8755";
$address['street_name']       = "Laurel street";
$address['street_type']       = "st";
$address['country_id']        = 1;
$address['province_id']       = 10;
$address['state_id']          = 1;
$address['postal_code']       = "V6p3V7";
$address['city']              = "Vancouver";

$latlon = $geo_locate->latLngByAddress($address);
$t->is($latlon, '-123.12632 49.206836', 'lat = '.$latlon);
