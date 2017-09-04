<?php

class sfValidatorEmailExists extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('notification_exists', 'Notification email already in use');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
    // get existing username - empty on signup
    $client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
    $client_address = '';
    if (isset($client_id))
    {
      $client = Doctrine::getTable('Client')->findOneById($client_id);
      if ($client)
      {
        $client_data = $client->toArray();

        if ($client_data)
          $client_address = $client_data['ClientDetail']['email'];
      }
    }

    $username_exists = Doctrine::getTable('ClientDetail')->findOneByEmail($value);
    if ($username_exists['email'] != '' && $username_exists['email'] != $client_address)
      throw new sfValidatorError($this, 'notification_exists', array('value' => $value));

    return $value;
  }
}
