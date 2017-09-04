<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(10, new lime_output_color());
$clients_db = new Clients_Db();

$t->diag('Clients DB test');


// inserting and reading sample settings
$t->isa_ok(new Clients_Db(), 'Clients_Db', 'Clients object was created correctly');

$t->diag("Checking types");
$t->cmp_ok($clients_db->id_from_type("client"), '>', '0', 'checking id for client type');
$t->cmp_ok($clients_db->id_from_type("partner"), '>', '0', 'checking id for partner type');
$t->cmp_ok($clients_db->id_from_type("admin"), '>', '0', 'checking id for admin type');
$t->is($clients_db->id_from_type("fake"), '-1', 'error result for wrong data is -1');


$t->diag("Checking cliend details isnert");
$t->is($clients_db->insert_client_details(array()), '-1', 'checking garbage insert into client details');


$t->diag("Set client name");
$t->is($clients_db->set_client_name(0, ""), -1, 'first and second parameter check');
$t->is($clients_db->set_client_name(0, "Name"), -1, 'first parameter check');
$t->is($clients_db->set_client_name(1, ""), -1, 'second parameter check');

$t->diag("Check client types");
$types_list = array('client', 'partner', 'admin');
$t->is($clients_db->get_client_types(), $types_list, 'client types exist');
