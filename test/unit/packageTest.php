<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(4, new lime_output_color());
$packages_db = new Packages_Db();


$t->diag('Packages DB test');
$t->isa_ok(new Packages_Db(), 'Packages_Db', 'packages object was created correctly');
$t->is($packages_db->generate_alphanumeric_code('asd'), -1, 'numeric test for alphanumeric length');
$t->is(strlen($packages_db->generate_alphanumeric_code(12)), '12', '12 digit alphanumeric code is generated correctly');
$t->is(strlen($packages_db->generate_alphanumeric_code(7)), '7', '7 digit alphanumeric code is generated correctly');
