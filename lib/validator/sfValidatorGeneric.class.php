<?php

class sfValidatorGeneric extends sfValidatorBase
{
/*
  public function checkUsernameAndPassword($validator, $values)
  {
    $logins_db   = new Logins_Db();
    $this->client_info = $logins_db->check_login($values['email'], $values['password']);
    if (!$this->client_info)
    {
      // password is not correct, throw an error
      throw new sfValidatorError($validator, 'Password or username is not valid');
    }
 
    // password is correct, return the clean values
    return $values;
  }


  protected function doClean($value)
  {
    return $value;
  }


*/
}
