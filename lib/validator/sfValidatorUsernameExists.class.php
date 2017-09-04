<?php

class sfValidatorUsernameExists extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('username_exists', 'Username already exists');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
    // get existing username - empty on signup
    $client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
    $login_address = '';

    if (isset($client_id))
    {
      $client = Doctrine::getTable('Client')->findOneById($client_id);
      if ($client)
      {
        $client_data = $client->ClientLogin->toArray();

        if ($client_data)
          $login_address = $client_data['email'];
      }
    }

    $login_exists = Doctrine::getTable('ClientLogin')->findOneByEmail($value);
    if ($login_exists['email'] != '' && $login_exists['email'] != $login_address)
      throw new sfValidatorError($this, 'username_exists', array('value' => $value));

    return $value;
  }
}
