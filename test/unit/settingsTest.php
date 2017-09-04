<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(4, new lime_output_color());
$settings_db = new Settings_Db();

$t->diag('Settings DB test');


// inserting and reading sample settings
$t->isa_ok(new Settings_Db(), 'Settings_Db', 'Settings object was created correctly');

$t->diag("Getting sample settings");
$settings = $settings_db->get_settings_info();

$t->diag("Comparing to original data");
$t->cmp_ok($settings['tax_amount'], '>', '1', 'checking tax amount is valid: ' . $settings['tax_amount']);
$t->cmp_ok($settings['package_id_length'], '>=', '15', 'checking package_id_length is valid: ' . $settings['package_id_length']);
$t->cmp_ok($settings['payment_id_length'], '>=', '10', 'checking payment_id_length is valid: ' . $settings['payment_id_length']);
