<?php

class Admins_Db extends Base_Lib
{
  function email_packages_paidfor($payment_code)
  {
		if (!isset($payment_code) || $payment_code == "")
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): error client_id is blank');

			throw new sfException("payment code: '$payment_code' is not defined");
		}

		$emails = array
		(sfConfig::get('app_email_support')   => 'New Courier Express order placed',
     sfConfig::get('app_email_marketing') => 'New Courier Express order placed',
     sfConfig::get('app_email_relations') => 'New Courier Express order placed');

		$packages_db = new Packages_Db();

		foreach ($emails as $key => $value)
		{
			$settings['email'] = $key;
			$settings['name']  = $value;

	   	// get the notification email for this client
 	  	$packages_db->email_packages_paidfor($payment_code, $settings);

			// find out client id for this email
			$res = Doctrine_Query::create()
							->select('c.id as client_id')
							->from('Client c')
              ->leftJoin('c.ClientDetail cd')
              ->where('cd.email = ?', $settings['email'])
              ->fetchOne();

			if ($res)
			{
				// ...
      	// send sms notifications
      	Tools_Lib::send_sms_payment($res['client_id'], $payment_code);
			}
		}
  }
}
