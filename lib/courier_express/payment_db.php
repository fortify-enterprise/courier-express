<?php

class Payment_Db extends Base_Lib
{
	protected $passcode    = null;
	protected $username    = null;
	protected $password    = null;
	protected $merchant_id = null;

	function __construct()
	{
		$mode = 'prod';
		$host = sfContext::getInstance()->getRequest()->getHost();
		if (preg_match('/^(staging|devbox|dev)/i', $host))
			$mode = 'dev';
	
		$this->passcode    = sfConfig::get("app_beanstream_".$mode."_passcode");
		$this->merchant_id = sfConfig::get("app_beanstream_".$mode."_merchant_id");
		$this->username    = sfConfig::get("app_beanstream_".$mode."_username");
		$this->password    = sfConfig::get("app_beanstream_".$mode."_password");
	}


	public function restCallForCreateProfile($client_id, $values)
  {
		$clients_db = new Clients_Db();		
    $address_db = new Address_Db();

    // get client information
    $client     = $clients_db->get_client($client_id);

    // Initialize curl
    $ch = curl_init();

    // Get curl to POST
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Instruct curl to suppress the output from Beanstream, and to directly
    // return the transfer instead. (Output will be stored in $txResult.)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // This is the location of the Beanstream payment gateway
    curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/payment_profile.asp" );
//
		// get province by country and province state ids
		
		$province = $address_db->get_code_by_province_id_and_country(
			$values['Address']['province_state_id'],
			$values['Address']['Country']['id']);

		$country_name = $address_db->get_code_by_country_id($values['Address']['Country']['id']);



    // These are the transaction parameters that we will POST
    // build curl url options
    $data = array(
              'serviceVersion' => '1.1',
              'requestType'=>'BACKEND',
              'operationType'=>'N',
              'responseFormat' => 'QS',
              'cardValidation' => '0',
              'passCode' => $this->passcode,
              'status' => 'A',
              'merchantId'=> $this->merchant_id,
              'trnCardOwner'=> $values['name'],
              'trnCardNumber'=> $values['card_number'],
              'trnExpMonth'=> $values['exp_month'],
              'trnExpYear'=> $values['exp_year'],
              'trnType'=> 'P',
              'ordEmailAddress'=> $client->ClientDetail['email'],
              'ordName'=> $client->ClientDetail['name'],
              'ordPhoneNumber'=> $client->ClientDetail['phone'],
              'ordAddress1'=> $values['address1'],
              'ordAddress2'=> $values['address2'],
              'ordCity'=> $values['Address']['city'],
              'ordProvince' => $province,
              'ordPostalCode'=> $values['Address']['postal_code'],
              'ordCountry'=> $country_name);


    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Now POST the transaction. $txResult will contain Beanstream's response
    $txResult = curl_exec( $ch );

    $parts = parse_url('http://beanstream.com/response?' . $txResult);
    parse_str($parts['query'], $query);

    curl_close( $ch );

    // return values
    /*
    [customerCode] => 29e377e10c6F4BB9BcA512c4644d48CD
    [responseCode] => 1
    [responseMessage] => Operation Successful
    [trnOrderNumber] => 
    [trnCardNumber] => 5XXXXXXXXXXX1004
    [cardType] => MC
    */
    return $query;
  }


  public function restCallForPayment($customer_code, $payment_code, $amount)
  {
    // Initialize curl
    $ch = curl_init();

    // Get curl to POST
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Instruct curl to suppress the output from Beanstream, and to directly
    // return the transfer instead. (Output will be stored in $txResult.)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // This is the location of the Beanstream payment gateway
    curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp" );

    // These are the transaction parameters that we will POST
    // build curl url options

    $data = array(
              'merchant_id'=> $this->merchant_id,
              'requestType'=>'BACKEND',
              'trnType' => 'P',
              'username' => $this->username,
              'password' => $this->password,
              'trnOrderNumber'=> $payment_code,
              'trnAmount'=> $amount,
              'customerCode' => $customer_code);

    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Now POST the transaction. $txResult will contain Beanstream's response
    $txResult = curl_exec( $ch );

    $parts = parse_url('http://beanstream.com/response?' . $txResult);
    parse_str($parts['query'], $query);

    curl_close( $ch );

    return $query;
    // return values
    /*
    [trnApproved] => 1
    [trnId] => 10000046
    [messageId] => 1
    [messageText] => Approved
    [trnOrderNumber] => 9H3BN8H22Q
    [authCode] => TEST
    [errorType] => N
    [errorFields] => 
   [responseType] => T
    [trnAmount] => 9.90
    [trnDate] => 8/10/2010 7:50:00 PM
    [avsProcessed] => 0
    [avsId] => 0
    [avsResult] => 0
    [avsAddrMatch] => 0
    [avsPostalMatch] => 0
    [avsMessage] => Address Verification not performed for this transaction.
    [cvdId] => 2
    [cardType] => MC
    [trnType] => P
    [paymentMethod] => CC
    */
  }

	
  public function restCallForRefund($payment_code, $trn_id, $amount)
  {
    // Initialize curl
    $ch = curl_init();

    // Get curl to POST
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Instruct curl to suppress the output from Beanstream, and to directly
    // return the transfer instead. (Output will be stored in $txResult.)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // This is the location of the Beanstream payment gateway
    curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp" );

    // These are the transaction parameters that we will POST
    // build curl url options

    $data = array(
              'merchant_id'=> $this->merchant_id,
              'requestType'=>'BACKEND',
              'trnType' => 'R',
              'username' => $this->username,
              'password' => $this->password,
              'trnOrderNumber'=> $payment_code,
							'adjId' => $trn_id,
              'trnAmount'=> $amount
            );

    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Now POST the transaction. $txResult will contain Beanstream's response
    $txResult = curl_exec( $ch );

    $parts = parse_url('http://beanstream.com/response?' . $txResult);
    parse_str($parts['query'], $query);

    curl_close( $ch );

    return $query;
    // return values
    /*
    [trnApproved] => 1
    [trnId] => 10000046
    [messageId] => 1
    [messageText] => Approved
    [trnOrderNumber] => 9H3BN8H22Q
    [authCode] => TEST
    [errorType] => N
    [errorFields] => 
   [responseType] => T
    [trnAmount] => 9.90
    [trnDate] => 8/10/2010 7:50:00 PM
    [avsProcessed] => 0
    [avsId] => 0
    [avsResult] => 0
    [avsAddrMatch] => 0
    [avsPostalMatch] => 0
    [avsMessage] => Address Verification not performed for this transaction.
    [cvdId] => 2
    [cardType] => MC
    [trnType] => P
    [paymentMethod] => CC
    */
  }



	function restCallForQueryProfile ($client_id)
	{
		$clients_db   = new Clients_Db();
    $profile_code = $clients_db->get_payment_profile_code($client_id);
    if (!$profile_code)
			return -1;

    // Initialize curl
    $ch = curl_init();

    // Get curl to POST
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Instruct curl to suppress the output from Beanstream, and to directly
    // return the transfer instead. (Output will be stored in $txResult.)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // This is the location of the Beanstream payment gateway
    curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/payment_profile.asp" );

    // These are the transaction parameters that we will POST
    // build curl url options

    $data = array(
              'serviceVersion' => '1.1',
							'operationType' => 'Q',
              'merchantId'=> $this->merchant_id,
              'passCode' => $this->passcode,
              'customerCode' => $profile_code);

    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Now POST the transaction. $txResult will contain Beanstream's response
    $txResult = curl_exec( $ch );
    curl_close( $ch );
		$values = simplexml_load_string($txResult);
    return $values;

/*
SimpleXMLElement Object
(
    [customerCode] => 92F5948f57D947C69824e1096c6767dE
    [responseCode] => 1
    [responseMessage] => Operation Successful
    [status] => A
    [ordName] => Paul+Randal
    [ordAddress1] => 1047+Main+Street
    [ordAddress2] => SimpleXMLElement Object
        (
        )

    [ordCity] => Vancouver
    [ordProvince] => BC
    [ordCountry] => CA
    [ordPostalCode] => V8R+1J6
    [ordEmailAddress] => prandal@mydomain.net
    [ordPhoneNumber] => 9999999
    [profileGroup] => SimpleXMLElement Object
        (
        )

    [velocityGroup] => SimpleXMLElement Object
        (
        )

    [trnCardOwner] => Paul+Randal
    [trnCardNumber] => 5XXXXXXXXXXX1004
    [trnCardExpiry] => 0112
    [cardType] => MC
    [bankAccountType] => SimpleXMLElement Object
        (
            [0] =>   
        )

    [lastCCTransDate] => 8/11/2010 1:41:17 AM
    [paymentModifiedDate] => 8/10/2010 11:45:07 PM
)


*/

	}


	function restCallForUpdateProfile($client_id, $info)
  {
		$clients_db = new Clients_Db();		
    $address_db = new Address_Db();

    // get client information
    $client     = $clients_db->get_client($client_id);
		$profile_code = $clients_db->get_payment_profile_code($client_id);
		if (!$profile_code)
			return -1;


    // Initialize curl
    $ch = curl_init();

    // Get curl to POST
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // Instruct curl to suppress the output from Beanstream, and to directly
    // return the transfer instead. (Output will be stored in $txResult.)
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // This is the location of the Beanstream payment gateway
    curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/payment_profile.asp" );
//

		// get country alpha code
		$country_alpha_code = $address_db->get_code_by_country_id($info['Address']['Country']['id']);

		// get province alpha code
		$province_alpha_code = $address_db->get_code_by_province_id_and_country
			($info['Address']['province_state_id'], $info['Address']['Country']['id']);


    // These are the transaction parameters that we will POST
    // build curl url options
    $data = array(
              'serviceVersion' => '1.1',
              'requestType'=>'BACKEND',
              'operationType'=>'M',
              'responseFormat' => 'QS',
              'cardValidation' => '0',
              'passCode' => $this->passcode,
              'status' => 'A',
              'merchantId'=> $this->merchant_id,
							'customerCode' => $profile_code,
              'trnCardOwner'=> $info['name'],
              'trnCardNumber'=> $info['card_number'],
              'trnExpMonth'=> $info['exp_month'],
              'trnExpYear'=> $info['exp_year'],
              'trnType'=> 'P',
							'ordName' => $info['name'],
              'ordAddress1'=> $info['address1'],
              'ordAddress2'=> $info['address2'],
              'ordCity'=> $info['Address']['city'],
              'ordProvince' => $province_alpha_code,
              'ordPostalCode'=> $info['Address']['postal_code'],
              'ordCountry'=> $country_alpha_code);


    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Now POST the transaction. $txResult will contain Beanstream's response
    $txResult = curl_exec( $ch );

    $parts = parse_url('http://beanstream.com/response?' . $txResult);
    parse_str($parts['query'], $query);

    curl_close( $ch );
		
    // return values
    /*
    [customerCode] => 29e377e10c6F4BB9BcA512c4644d48CD
    [responseCode] => 1
    [responseMessage] => Operation Successful
    [trnOrderNumber] => 
    [trnCardNumber] => 5XXXXXXXXXXX1004
    [cardType] => MC
    */

    return $query;
  }


	function get_card_types ($are_enabled = false)
	{
		$res = '';
		if ($are_enabled)
		{
			$res = Doctrine::getTable('CreditCardType')->findByEnabled(1);
		}
		else
		{
			$res = Doctrine::getTable('CreditCardType')->createQuery('c')->fetchArray();
		}

    $assoc_list = array();
    if ($res)
    {
      foreach ($res as $key => $value)
        $assoc_list[$value['id']] = $value['type'];

      return $assoc_list;
  	}  
    return $res;
	}


	function get_card_id_by_abbr ($abbr)
	{
		if (!$abbr || $abbr == null)
			return -1;

		$res = Doctrine::getTable('CreditCardType')->findOneByAbbr($abbr);
		if (!$res)
			return -1;
		return $res['id'];
	}


	function get_card_abbr_by_id ($id)
	{
		$res = Doctrine::getTable('CreditCardType')->findOneById($id);
		if (!$res)
			return -1;
		return $res['abbr'];
	}

}
