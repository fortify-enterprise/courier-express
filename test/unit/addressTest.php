<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(75, new lime_output_color());
$address_db = new Address_Db();

$t->diag('Address DB test');
$t->isa_ok(new Address_Db(), 'Address_Db', 'address object was created correctly');


$t->diag("Checking if Canada is one of the available countries");
$clist = $address_db->get_countries_list();
$names = array();
foreach ($clist as $key)
  $names[] = $key['name'];
$t->is( in_array('Canada', $names), true, 'Canada is in the list of available countries');
$t->is( in_array('United States', $names), true, 'United States is in the list of available countries');


$t->diag("Checking all available provinces");
$plist = $address_db->get_provinces_list();
$names = array();
foreach ($plist as $key)
  $names[] = $key['province_territory'];

$t->is( in_array('Newfoundland and Labrador', $names), true, 'Newfoundland and Labrador');
$t->is( in_array('Prince Edward Island', $names), true, 'Prince Edward Island');
$t->is( in_array('Nova Scotia', $names), true, 'Nova Scotia');
$t->is( in_array('New Brunswick', $names), true, 'New Brunswick');
$t->is( in_array('Quebec', $names), true, 'Quebec');
$t->is( in_array('Ontario', $names), true, 'Ontario available');
$t->is( in_array('Manitoba', $names), true, 'Manitoba');
$t->is( in_array('Saskatchewan', $names), true, 'Saskatchewan');
$t->is( in_array('Alberta', $names), true, 'Alberta');
$t->is( in_array('British Columbia', $names), true, 'British Columbia available');
$t->is( in_array('Yukon Territory', $names), true, 'Yukon Territory');
$t->is( in_array('Northwest Territories', $names), true, 'Northwest Territories');
$t->is( in_array('Nunavut', $names), true, 'Nunavut');


$t->diag("Checking all available states");
$plist = $address_db->get_states_list();
$names = array();
foreach ($plist as $key)
  $names[] = $key['name'];

$t->is( in_array("Alabama", $names), true, "Alabama");
$t->is( in_array("Alaska", $names), true, "Alaska");
$t->is( in_array("Arizona", $names), true, "Arizona");
$t->is( in_array("Arkansas", $names), true, "Arkansas");
$t->is( in_array("California", $names), true, "California");
$t->is( in_array("Colorado", $names), true, "Colorado");
$t->is( in_array("Connecticut", $names), true, "Connecticut");
$t->is( in_array("Delaware", $names), true, "Delaware");
$t->is( in_array("District of Columbia", $names), true, "District of Columbia");
$t->is( in_array("Florida", $names), true, "Florida");
$t->is( in_array("Georgia", $names), true, "Georgia");
$t->is( in_array("Hawaii", $names), true, "Hawaii");
$t->is( in_array("Idaho", $names), true, "Idaho");
$t->is( in_array("Illinois", $names), true, "Illinois");
$t->is( in_array("Indiana", $names), true, "Indiana");
$t->is( in_array("Iowa", $names), true, "Iowa");
$t->is( in_array("Kansas", $names), true, "Kansas");
$t->is( in_array("Kentucky", $names), true, "Kentucky");
$t->is( in_array("Louisiana", $names), true, "Louisiana");
$t->is( in_array("Maine", $names), true, "Maine");
$t->is( in_array("Maryland", $names), true, "Maryland");
$t->is( in_array("Massachusetts", $names), true, "Massachusetts");
$t->is( in_array("Michigan", $names), true, "Michigan");
$t->is( in_array("Minnesota", $names), true, "Minnesota");
$t->is( in_array("Mississippi", $names), true, "Mississippi");
$t->is( in_array("Missouri", $names), true, "Missouri");
$t->is( in_array("Montana", $names), true, "Montana");
$t->is( in_array("Nebraska", $names), true, "Nebraska");
$t->is( in_array("Nevada", $names), true, "Nevada");
$t->is( in_array("New Hampshire", $names), true, "New Hampshire");
$t->is( in_array("New Jersey", $names), true, "New Jersey");
$t->is( in_array("New Mexico", $names), true, "New Mexico");
$t->is( in_array("New York", $names), true, "New York");
$t->is( in_array("North Carolina", $names), true, "North Carolina");
$t->is( in_array("North Dakota", $names), true, "North Dakota");
$t->is( in_array("Ohio", $names), true, "Ohio");
$t->is( in_array("Oklahoma", $names), true, "Oklahoma");
$t->is( in_array("Oregon", $names), true, "Oregon");
$t->is( in_array("Pennsylvania", $names), true, "Pennsylvania");
$t->is( in_array("Rhode Island", $names), true, "Rhode Island");
$t->is( in_array("South Carolina", $names), true, "South Carolina");
$t->is( in_array("South Dakota", $names), true, "South Dakota");
$t->is( in_array("Tennessee", $names), true, "Tennessee");
$t->is( in_array("Texas", $names), true, "Texas");
$t->is( in_array("Utah", $names), true, "Utah");
$t->is( in_array("Vermont", $names), true, "Vermont");
$t->is( in_array("Virginia", $names), true, "Virginia");
$t->is( in_array("Washington", $names), true, "Washington");
$t->is( in_array("West Virginia", $names), true, "West Virginia");
$t->is( in_array("Wisconsin", $names), true, "Wisconsin");
$t->is( in_array("Wyoming", $names), true, "Wyoming");



$t->diag("Inserting / checking for existance of address");

$address = array();
$address['apt_unit']          = "123";
$address['street_number']     = "4342345432344";
$address['street_name']       = "Some interesting street";
$address['street_type']       = "st";
$address['country_id']        = 1;
$address['province_id']       = 10;
$address['state_id']          = 1;
$address['postal_code']       = "V6P3V7";
$address['city']              = "Vancouver";

$id = $address_db->insert_new_address($address);
$t->diag("Inserted address with id $id");

$t->diag("Test address text with id $id");
$t->is(strtolower($address_db->get_obj_text_address($id)), '123 4342345432344 some interesting street, vancouver v6p3v7, british columbia, canada', 'Compare get_obj_text_address text address');

$t->diag("Test address text with array");
$t->is(strtolower($address_db->get_text_address($address)), '123 4342345432344 some interesting street, vancouver v6p3v7, british columbia, canada', 'Compare get_text_address text address');

$t->is($address_db->does_address_exists($address), true, 'Tested address exists in the database');

$t->diag("Attempt to delete address id: $id");
$address_db->remove_address($id);
$t->is($address_db->does_address_id_exists($id), false, "Does removed address exists with id: $id");


$t->diag("Test get country from ids");
$country = $address_db->get_country_by_id(1);
$t->like($country, '/Canada/', 'Test get country');



$t->diag("Test get_province_id_by_country_and_code");
$country_obj = Doctrine::getTable('Country')->findOneByName('Canada');
$province_code = $address_db->get_province_id_by_country_and_code($country_obj->getId(), 'BC');
$province_territory = Doctrine::getTable('Province')->findOneById($province_code)->getProvinceTerritory();
$t->is($province_territory, 'British Columbia', 'Test province code by country and code: ' . $province_territory);

$t->diag("Test get_code_by_country_id");

$canada_id = $country_obj->getId();
$t->is($address_db->get_code_by_country_id($canada_id), 'CA', 'Test get_code_by_country_id by country id: CA');


$t->diag("Test get_city_province_from_postal, given V6P3V7 and country id : $canada_id");
list($city, $province) = $address_db->get_city_province_from_postal('V6P3V7', $canada_id);
$t->is($city, 'Vancouver', 'Test get_city_province_from_postal , city: ' . $city);

