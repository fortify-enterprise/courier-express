<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(36, new lime_output_color());
 

function test_all ($t, $v, $tests)
{
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
}


// Weight tests

$tests = array(
  array(true, '14000', '14000 pounds allowed weight'),
  array(false, '16000', '16000 pounds not allowed'),
);
 
$v = new sfValidatorWeight;
sfConfig::set('app_package_max_weight', 15000);

$t->diag("Testing sfValidatorWeight");
test_all($t, $v, $tests);
 
// Username exists validator

$tests = array(
  array(false, 'aktush@gmail.com', 'aktush@gmail.com exists'),
  array(false, 'info@courierexpress.ca', 'info@courierexpress.ca exists'),
  array(false, 'badams@driverready.ca', 'badams@driverready.ca exists'),
  array(false, 'admin@quickasawinkcourier.com', 'admin@quickasawinkcourier.com exists'),
  array(false, 'andrei@courierexpress.ca', 'andrei@courierexpress.ca exists'),
);
 
$v = new sfValidatorUsernameExists;

$t->diag("Testing sfValidatorUsernameExists");
test_all($t, $v, $tests);
 

// sfValidatorPostalZip.class.php validator

$tests = array(
  array(true, '98012', '98012 valid'),
  array(false, '98012a3324', '98012a3324 invalid'),
  array(true, 'v6p3v7', 'v6p3v7 valid'),
  array(false, 'vv53u7', 'vv53u7 invalid'),
);
 
$v = new sfValidatorPostalZip;

$t->diag("Testing sfValidatorPostalZip");
test_all($t, $v, $tests);


// sfValidatorPhone.class.php validator

$tests = array(
  array(false, '', 'empty not required'),
  array(true, '6048056431', '6048056431 valid'),
  array(false, '98012a3324', '98012a3324 invalid'),
  array(true, '16048056431', '16048056431 valid'),
  array(false, 'vv53u7', 'vv53u7 invalid'),
);
 
$v = new sfValidatorPhone;

$t->diag("Testing sfValidatorPhone");
test_all($t, $v, $tests);


// sfValidatorEmailMx.class.php validator

$tests = array(
  array(true, 'aktush@gmail.com', 'aktush@gmail.com exists'),
  array(true, 'info@courierexpress.ca', 'info@courierexpress.ca exists'),
  array(true, 'badams@driverready.ca', 'badams@driverready.ca exists'),
  array(true, 'admin@quickasawinkcourier.com', 'admin@quickasawinkcourier.com exists'),
  array(true, 'andrei@courierexpress.ca', 'andrei@courierexpress.ca exists'),

  array(false, 'andreiasd@courier123express.ca', 'courier123express.ca does not exist'),
  array(false, 'andrei@c123ourierexpress.ca', 'c123ourierexpress.ca does not exist'),
);
 
$v = new sfValidatorEmailMx;

$t->diag("Testing sfValidatorEmailMx");
test_all($t, $v, $tests);



// sfValidatorCreditCard.class.php validator

$tests = array(
  array(false, '', 'empty card'),
  array(false, '123423432', '123423432 card'),
  array(false, 'asdasd1234234fd32__', 'asdasd1234234fd32__ card'),
  array(false, 'a__sdXXXX34fd32__', 'a__sdXXXX34fd32__ card'),
  array(true, '4030000010001234', '4030000010001234 card'),
  array(true, '4504481742333', '4504481742333 card'),
  array(true, '4123450131003312', '4123450131003312 card'),
  array(true, '4003050500040005', '4003050500040005 card'),
  array(true, '5100000010001004', '5100000010001004 card'),
  array(true, '5194930004875020', '5194930004875020 card'),
  array(true, '5100000020002000', '5100000020002000 card'),
  array(true, '371100001000131', '371100001000131 card'),
  array(true, '342400001000180', '342400001000180 card'),
);
 
$v = new sfValidatorCreditCard;

$t->diag("Testing sfValidatorCreditCard");
test_all($t, $v, $tests);

