<?php

class Accounts_Db extends Base_Lib
{
	function send_account_details ($action, $client_id, $subject = 'New Courier Express registration')
	{
		if (!$action || !$client_id)
			throw new sfException("action: '$action' or client_id: '$client_id' are empty");

		$client = $action->getUser()->getAttribute('client');

		if (!$client)
    {
      $res  = Doctrine_Query::create()
          ->from('Client c')
          ->leftJoin('c.ClientDetail cd')
          ->leftJoin('c.ClientLogin cl')
          ->leftJoin('c.ClientType ct')
          ->where('c.id = ?', $client_id)
          ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
      $client = $res[0];
    }

		// send email message for account details
		$emailer_db = new Emailer_Db();
		$emailer_db->send_email
			($action, $client['ClientLogin']['email'], $subject, 'email', 'register_client', array('client' => $client));

    if ($client['ClientDetail']['phone'])
    {
      // send sms account creation message with login details
      $sms = new SmsMessage();
      $sms['number'] = $client['ClientDetail']['phone'];
      $sms['text']   = 'www.courierexpress.ca account created\nlogin: ' . $client['ClientLogin']['email'] . '\npassword: ' . $client['ClientLogin']['password'];
      $sms->save();
    }
	}
}
