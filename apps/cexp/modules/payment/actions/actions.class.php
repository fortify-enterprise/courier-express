<?php

/**
 * payment actions.
 *
 * @package    courierexpress
 * @subpackage payment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class paymentActions extends sfActions
{

  public function preExecute ()
  {
		Tools_Lib::checkUnsupportedCountries();

    $this->dis        = Constants::emsize();
    $this->clients_db = new Clients_Db();
    $this->client_id  = $this->getUser()->getAttribute('client_id');

		// load helper url
		sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
		sfContext::getInstance()->getConfiguration()->loadHelpers('Debug');

    // shopping cart data
    $this->packages_cart = $this->getUser()->getAttribute('packages_cart');
  }


	// decide where to redirect
	// for direct payment
	//	/payment/direct -> leads to /payment/process_direct -> return to website
	//
	// for paypal or alertpay where gateway is used
	//	/payment/process_gateway -> leads to gateway -> return to website

	public function executeIndex (sfWebRequest $request)
	{
		// redirect url build - if you have shopping cart its in process registration
		// if not then you just entered payment url in browser redirect to main page

		// special redirect to registration page


    if (!isset($this->client_id) || $this->client_id == 0 && sizeof($this->packages_cart) > 0)
			$this->redirect(url_for('register/client?in_process=1'));
		
		if (!$this->packages_cart)
			$this->redirect(url_for('main_page/index'));

    // pay with credit card

		$payment_type = 'direct_payment';//$request->getParameter('payment_type');
		$res = '';
		switch($payment_type)
		{
			case 'paypal':
				$this->forward('payment', 'process_gateway');
			break;

			case 'direct_payment':
  			$res = $this->direct_payment();
			break;
		}
	}


	public function direct_payment ()
	{
		// see if we have credit card on file for this client id
		// if we do have assign $card_on_file to the card we have on file

    $this->accounts_db      = new Accounts_Db();
		$this->payment_db       = new Payment_Db();
    $this->price_calculator = new Price_Calculator();
    $this->service_db       = new Service_Db();

		// packages_cart is not set redirect to the front page
		if (!isset($this->packages_cart) || sizeof($this->packages_cart) <= 0)
			$this->redirect('main_page/index');

		// create packages in the system
    // get order payment code
 
		$payment_code = $this->create_packages();
		$cart_price   = 0;
		$geo_locate   = new Geo_Locate();
    foreach ($this->packages_cart as $id => $package)
    {
      $lat_lng_from = $geo_locate->latLngByAddress($package['sender']);
      $lat_lng_to   = $geo_locate->latLngByAddress($package['recep']);

      $amount = $this->price_calculator->calculate_price($package['couriers'][0]['id'], $lat_lng_from, $lat_lng_to, $package['PackageDetail']);

      list($partner_price, $partner_tax, $price, $tax) = $this->price_calculator->get_prices_list ($package['couriers'][0]['id'], $amount);
      $cart_price += ($price + $tax);
    }



		// check if we can retrieve customer code without having to create a profile
		// process transaction

		$profile_code     = $this->clients_db->get_payment_profile_code($this->client_id);
		$payment_response = '';

		$error = NULL;
		if ($profile_code)
		{
	    if (sfContext::hasInstance())
		    sfContext::getInstance()->getLogger()
				->debug(__FUNCTION__ . "() : rest call for payment : $profile_code, payment code : $payment_code, price : " . round($cart_price,2));

			$payment_response = $this->payment_db->restCallForPayment($profile_code, $payment_code, round($cart_price, 2));
			if ($payment_response['trnApproved'] == 1)
			{
				//
    		// if the result is success mark payent as complete // coming from payment processor

    		$this->packages_db
						 ->set_packages_paidfor
						 ($payment_code, $payment_response['trnId'], 'direct', array('amount' => round($cart_price, 2)));


    		// send notifications to all parties that delivery is complete
    		$this->send_paidfor_notifications ($payment_code);

		    if (sfContext::hasInstance())
				   sfContext::getInstance()->getLogger()
					 ->debug(__FUNCTION__ . "() : cc payment success for payment code : $payment_code, trn id : " . $payment_response['trnId']);

    		// clear the shopping cart payment success
    		$this->getUser()->getAttributeHolder()->remove('packages_cart');

			}
			else
			{
		    if (sfContext::hasInstance())
			    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : credit card payment error for payment code :"." $payment_code using profile code : $profile_code");
				$error = 'Credit card payment error: ' . $payment_response['messageText'];
			}
		}

		$template_redirect = '';

		if ($error)
		{
	    if (sfContext::hasInstance())
	      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : credit card payment error :" .$payment_response['messageText']);
			$template_redirect = 'template=payment_error&error_message=' . $error;
		}
		else
			$template_redirect = 'template=payment';

		//
    // redirect to non https main page thank you or error
		
		Tools_Lib::redirectNonSecurePage('form', 'thankyou', $template_redirect);
	}


	public function executeProcess_gateway (sfWebRequest $request)
	{
    $this->accounts_db      = new Accounts_Db();
    $this->price_calculator = new Price_Calculator();
    $this->service_db       = new Service_Db();

    if (sfContext::hasInstance())
      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : processing payment gateway");

		// packages_cart is not set redirect to the front page

		if (!isset($this->packages_cart) || sizeof($this->packages_cart) <= 0)
			$this->redirect(url_for('main_page/index'));

		// create packages in the system
     
		$payment_code = $this->create_packages();


		// if payment code generated follow to payment gateway

		if ($payment_code)
      $this->redirect_payment_gateway($payment_code);
	}


	// insert multiple packages and create payment code for them
	// using existing shopping cart

  public function create_packages ()
  {
	   if (sfContext::hasInstance())
	     sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '() : starting to create packages');

		// if no shopping cart data return
		if (!isset($this->packages_cart) || empty($this->packages_cart))
		{
	    if (sfContext::hasInstance())
	      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '() : packages cart is empty');
			return null;
		}

    $this->packages_db = new Packages_Db();
    $this->settings_db = new Settings_Db();

    $settings_info = $this->settings_db->get_settings_info();

    // generate payment code (accross all packages in shopping cart)
    $payment_code = $this->packages_db->generate_alphanumeric_code($settings_info['payment_id_length']);

		// loop through packages

    try
    {
      // start connection
      $conn = Doctrine_Manager::connection();
      $conn->beginTransaction();

      // create new partially completed payment
      $this->packages_db->create_payment ($payment_code, $this->client_id);

	    if (sfContext::hasInstance())
  	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : creating payment : $payment_code, for packages");

		  foreach ($this->packages_cart as $package_id => $package_data)
		  {
      // loop

        // generate package db entry
			  $courier_id = $package_data['couriers'][0]['id'];
			  $from       = $package_data['sender'];
			  $to         = $package_data['recep'];
		    $details    = $package_data['PackageDetail'];

        // normalize the details
        $details['delivery_type_id']      = $details['DeliveryType']['id'];
        $details['service_level_type_id'] = $details['ServiceLevelType']['id'];
        $details['package_type_id']       = $details['PackageType']['id'];

        // unset the fields
        unset($details['DeliveryType']);
        unset($details['ServiceLevelType']);
        unset($details['PackageType']);
  
		    if (sfContext::hasInstance())
		      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : creating package : $package_id, for payment : $payment_code, courier_id : $courier_id");

        $this->packages_db->create_package ($package_id, $payment_code, $from, $to, $details, $this->client_id, $courier_id);
		  }
      // end loop

      // commit changes
      $conn->commit();
    }
    catch (Exception $e)
    {
	    if (sfContext::hasInstance())
	      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : exception thrown : $e");

      $conn->rollBack();
      throw $e;
    }
    
		if (sfContext::hasInstance())
		  sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : completed creation of all packages for payment : $payment_code");

    return $payment_code;
  }


	// payment code is unique for all packages in the payment
	// before this function is called all packages must be inserted
  // and ssociated with the payment code

  function redirect_payment_gateway ($payment_code)
  {
		$cart_price = 0;
		$this->price_calculator = new Price_Calculator();

		if (!empty($this->packages_cart))
			$cart_price = $this->price_calculator->calculate_total_price($this->packages_cart);

		$payment_type = $this->getRequestParameter('payment_type');
    $service_name = "Courier Express Shipping service";


		// clear the shopping cart
		$this->getUser()->getAttributeHolder()->remove('packages_cart');


    switch ($payment_type)
    {
      // alertpay engine
      case 'alertpay':
      $this->redirect("https://www.alertpay.com/PayProcess.aspx?ap_purchasetype=item&ap_merchant=info@courierexpress.ca&ap_itemname=$service_name&ap_currency=CAD&ap_returnurl=http://courierexpress.ca/payment/success&ap_quantity=1&ap_itemcode=$payment_code&ap_description=delivery&ap_amount=$cart_price&ap_cancelurl=http://courierexpress.ca/payment/cancel");
      break;

      // paypal engine
      case 'paypal':
				if (sfConfig::get('sf_environment') == 'prod')
				{
      		$this->redirect("https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@courierexpress.ca&item_name=$service_name Delivery&amount=$cart_price&currency_code=CAD&shipping=&no_shipping=0&no_note=0&custom=$payment_code&return=http://www.courierexpress.ca/payment/success");
				}
				else
				{
      		$this->redirect("https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@courierexpress.ca&item_name=$service_name Delivery&amount=$cart_price&currency_code=CAD&shipping=&no_shipping=0&no_note=0&return=http://www.courierexpress.ca/payment/success");
				}
      break;

      default:
      break;
    }
  }


  public function executePaypal_ipn ()
  {
		$_POST = 
		array(
		
    'test_ipn' => 1,
    'payment_type' => 'instant',
    'payment_date' => '22:27:25 Dec. 18, 2009 PST',
    'payment_status' => 'Completed',
    'address_status' => 'confirmed',
    'payer_status' => 'unverified',
    'first_name' => 'John',
    'last_name' => 'Smith',
    'payer_email' => 'aktush@gmail.com',
    'payer_id' => 'TESTBUYERID01',
    'address_name' => 'John Smith',
    'address_country' => 'United States',
    'address_country_code' => 'US',
    'address_zip' => '95131',
    'address_state' => 'CA',
    'address_city' => 'San Jose',
    'address_street' => '123, any street',
    'receiver_email' => 'aktush@gmail.com',
    'receiver_id' => 'TESTSELLERID1',
    'residence_country' => 'US',
    'item_name1' => 'something',
    'item_number1' => 'AK-1234',
    'quantity1' => '1',
    'tax' => '2.02',
    'mc_currency' => 'USD',
    'mc_fee' => '0.44',
    'mc_gross_1' => '9.34',
    'mc_handling' => '2.06',
    'mc_handling1' => '1.67',
    'mc_shipping' => '3.02',
    'mc_shipping1' => '1.02',
    'txn_type' => 'cart',
    'txn_id' => '251219627',
    'notify_version' => '2.4',
    'custom' => 'T54UTX64M4',
    'invoice' => 'abc1234',
    'charset' => 'windows-1252',
    'verify_sign' => 'AE18v3r.YbT59-OXzvRUutcQUWUPACL2vegRTZNnrLxpjJMUibQpCL0y'

		);

		if (isset($_POST['custom']))
    	$this->generic_ipn_nofification ('paypal', 'custom', $_POST);
  }


  public function executeAlertpay_ipn ()
  {
    $this->generic_ipn_nofification ('alertpay', 'ap_itemcode', $_POST);
  }


  public function generic_ipn_nofification ($gateway, $custom_tracking_field, $post)
  {
    // log received ipn
    if (sfContext::hasInstance())
      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): payment ' . $post[$custom_tracking_field]);

    $payment_code = $post[$custom_tracking_field];

    // set all packages associated with payment status = paid
		$this->packages_db = new Packages_Db();
    $this->packages_db->set_packages_paidfor($payment_code, $gateway, $post);

    // set the payouts to the couriers based on their current rates and our profit cut
    $this->packages_db->set_packages_payout($payment_code);

    // send notifications to all parties that delivery is complete
    $this->send_paidfor_notifications ($payment_code);
  }


  public function send_paidfor_notifications ($payment_code)
  {
    // send email to the client saying pacakges have been paid for
    $this->clients_db->email_packages_paidfor($payment_code);

    // send an email to partner with all package details to deliver
    $this->couriers_db = new Couriers_Db();
    $this->couriers_db->email_packages_paidfor($payment_code);

    // send email to us saying that new payment has been made with payment code
    $this->admins_db = new Admins_Db();
    $this->admins_db->email_packages_paidfor($payment_code);

  }


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeProcess_direct(sfWebRequest $request)
  {
		// paypal direct payment
		// Setup API's credentials
		$cc = new prestaPaypal
		( sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.'prestaPaypalPlugin'.DIRECTORY_SEPARATOR.'sdk'.DIRECTORY_SEPARATOR.'lib' );
 
		// Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed.
 		$cc->setUserName(sfConfig::get('mod_registration_paypal_username'));
 		$cc->setPassword(sfConfig::get('mod_registration_paypal_password'));
 		// API signature
 		// How to get a signature ? https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics
 		$cc->setSignature(sfConfig::get('mod_registration_paypal_signature'));
 		// Usefull in development environment
 		$cc->setTestMode(sfConfig::get('mod_registration_paypal_test'));
 		// Amount payement incl. taxes
 		$cc->setTransactionTotal($total);
 		// A description for the transaction // payment id
 		$cc->setTransactionDescription($desc);
  
		// Client information :
		$cc->setBillingFirstName($this->getRequestParameter('firstname'));
		$cc->setBillingLastName($this->getRequestParameter('lastname'));
		$cc->setBillingStreet1($this->getRequestParameter('address'));
		$cc->setBillingStreet2($this->getRequestParameter('address2'));
		$cc->setBillingCity($this->getRequestParameter('city'));
		$cc->setBillingState($this->getRequestParameter('state'));
		$cc->setBillingZip($this->getRequestParameter('zip'));
	 
	 	$cc->setCardType($this->getRequestParameter('cctype'));
	 	$cc->setCardNumber($this->getRequestParameter('cc'));
	 	$cc->setCardVerificationNumber($this->getRequestParameter('ccv'));
	 	$cc->setCardExpirationMonth($this->getRequestParameter('expmonth'));
	 	$cc->setCardExpirationYear($this->getRequestParameter('expyear'));
	 	$cc->setBuyerIP($_SERVER['REMOTE_ADDR']);
	  
		// Do payement
		if ( !$cc->chargeDirect() )
		{
		    $error = $cc->getErrorString();
		}
		return $error;
  }


	public function executeSuccess (sfWebrequest $request)
	{
		
	}
}
