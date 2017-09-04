<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');


// Initialize the test object
$t = new lime_test(4, new lime_output_color());
$accounts_db = new Accounts_Db();
$logins_db   = new Logins_Db();

$t->diag('Accounts DB test');
$t->isa_ok(new Accounts_Db(), 'Accounts_Db', 'accounts object was created correctly');
$t->is(strlen($logins_db->generate_password()), '10', 'password is generated correctly');


// testing email address exists function
$email = $logins_db->generate_password(). 'test@test.com';
$t->diag("Generating sample login email '$email' creation and existance test");

$login = new ClientLogin();
$login['password_hash'] = Tools_Lib::getHash('test');
$login['password']      = 'test';
$login['email']         = $email;
$login->save();

// will be to do tests
/*$t->todo('account creation with empty type');
$t->todo('account creation with incorrect type non client or partner');
$t->todo('account creation with empty address');
$t->todo('account creation with empty client detail');
$t->todo('account creation with empty client login');*/


$login->delete();
$t->is($logins_db->get_clientid_from_login($email), -1, 'email deletion test');


// testing function to filter correct emails
$t->diag("Correct email validation");
$v = new sfValidatorEmailMx();

  try {
    $v->clean($value);
    $catched = false;
  }
  catch(sfValidatorError $e)
  {
    $catched = true;
  }
 
  $t->ok($validity != $catched, '::clean() '.$message);
