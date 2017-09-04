<?php

class Tools_Lib extends Base_Lib
{
	//
	// array clean

	public function clean ($str, $set = null)
	{
    if(is_Array($str) || is_Object($str))
		{
      foreach($str as &$s)
        $s = $this->clean ($s, $set);
		}
    elseif($set===null)
			$str = trim ($str);
		else
			$str = trim ($str,$set);
		return $str;
	}

  
  // get a new salt - 8 hexadecimal characters long
  // current PHP installations should not exceed 8 characters
  // on dechex( mt_rand() )
  // but we future proof it anyway with substr()
  //return substr( str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 );

  static function getSalt()
  {
    return sfConfig::get('app_password_salt');
  }


	//
  // calculate the hash from a salt and a password

  static function getHash( $password )
  {
    $salt = Tools_Lib::getSalt();
    return hash(sfConfig::get('app_password_function'),  $salt . $password);
  }


	//
  // compare a password to a hash

  static function compareHash( $password, $hash )
  {
    //$salt = substr ($hash, 0, 8);
    return $hash == Tools_Lib::getHash($password);
  }

  
	//
  // display the current time at which the event happened

  static function getSavedMessage ($datestr = 'l jS \of F Y h:i:s A')
  {
    return 'Changes saved on '.date($datestr);
  }


	//
	// message with nothing to update

  static function nothingToUpdateMessage ($datestr = 'l jS \of F Y h:i:s A')
  {
    return 'Nothing to update, no changes were saved on '.date($datestr);
  }

  
	//
  // secure the page with ssl

  static function redirectSecurePage ($module, $action, $params = '')
  {
    // load helper url
    $instance = sfContext::getInstance();
    $instance->getConfiguration()->loadHelpers('Url');

    $host = $instance->getRequest()->getHost();
    $uri  = url_for($module . '/'. $action . (($params) ? '?'.$params : ''));
    $instance->getController()->getAction($module, $action)->redirect(sprintf('%s://%s%s', 'https', $host, $uri));
  }


	//
  // unsecure the page

  static function redirectNonSecurePage ($module, $action, $params = '')
  {
    // load helper url
    $instance = sfContext::getInstance();
    $instance->getConfiguration()->loadHelpers('Url');

    $host = $instance->getRequest()->getHost();
    $uri  = url_for($module . '/'. $action . (($params) ? '?'.$params : ''));
		$instance->getController()->getAction($module, $action)->redirect(sprintf('%s://%s%s', 'http', $host, $uri));
  }


	static function send_sms_client($client_id, $message)
	{
		$client = Doctrine::getTable('Client')->findOneById($client_id);		
		if (!$client || !$message || !$client->ClientDetail['phone'])
			return null;
		
    // send sms notification
    $sms = new SmsMessage();
    $sms['number'] = Tools_Lib::correct_phone($client->ClientDetail['phone']);
    $sms['text']   = $message;
    $sms->save();
	}


	static function send_sms_payment($client_id, $payment_code)
	{
		$client  = Doctrine::getTable('Client')->findOneById($client_id);		
		$payment = Doctrine::getTable('Payment')->findOneByPaymentCode($payment_code);		

		if (!$client || !$payment_code || !$client->ClientDetail['phone'])
			return null;
	
    // send sms notification
    $sms = new SmsMessage();
    $sms['number'] = Tools_Lib::correct_phone($client->ClientDetail['phone']);
    $sms['text']   = "Payment was made: $payment_code\nPlease login to partner portal to view it" ;
    $sms->save();
	}


	static function send_sms_phone($phone, $message)
	{
		if (!$phone || !$message)
			return null;
	 	
    // send sms notification
    $sms = new SmsMessage();
    $sms['number'] = Tools_Lib::correct_phone($phone);
    $sms['text']   = $message;
    $sms->save();
	}


	static function correct_phone($phone)
	{
		$complete_phone = '1'.$phone;
		return (!preg_match('/^1/', $phone)) ? $complete_phone : $phone;
	}


	static function checkUnsupportedCountries()
	{
		$instance = sfContext::getInstance();
		$visitor  = $instance->getUser()->getAttribute('visitor');

		if ($visitor)
		{
			$allowed_countries = array('ca', 'us');
			if (!in_array(strtolower($visitor['country_code']), $allowed_countries))
			{
    		$instance->getConfiguration()->loadHelpers('Url');
				// redirect to unsupported
    		$uri  = url_for('exception/country_not_supported');
    		$instance->getController()->getAction('main_page', 'index')->redirect($uri);
			}
		}
	}
}
