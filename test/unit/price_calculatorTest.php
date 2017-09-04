<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(5, new lime_output_color());
$price_calculator = new Price_Calculator();


$t->diag('Price calculator test');
$t->isa_ok(new Price_Calculator(), 'Price_Calculator', 'object was created correctly');

list($amount, $partner_tax, $price, $tax) = $price_calculator->get_prices_list(34, 120);
$t->is($amount, 120, 'checking for caclulating surcharge amounts 1');
$t->is($partner_tax, 14.4, 'checking for caclulating surcharge amounts 2');
$t->is($price, 132, 'checking for caclulating surcharge amounts 3');
$t->is($tax, 15.84, 'checking for caclulating surcharge amounts 4');

