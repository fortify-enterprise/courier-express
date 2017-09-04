<?php

class Packages_Db extends Base_Lib
{

	// retrieve package types

  function get_package_types($is_assoc = true)
  {
    $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['PackageType']))
      {
        $res = $system_settings['PackageType'] = Doctrine::getTable('PackageType')->findAll()->toArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
      else
        $res = $system_settings['PackageType'];
    }
    else
      $res = $system_settings['PackageType'] = Doctrine::getTable('PackageType')->findAll()->toArray();


    // create associative array

    if ($is_assoc)
    {
    	$assoc_list = array();
      foreach ($res as $key => $value)
        $assoc_list[$value['id']] = $value['type'];

      return $assoc_list;
    }
    return $res;
  }


	//
	// before this function is called payment must be already created
	// then we pass newly generated package code, payment code from the payment
	// from and to addresses, package details, client who ships and courier who will deliver

	function create_package ($package_code, $payment_code, $from, $to, $details, $client_id, $courier_id)
	{
		if (!$client_id || !$courier_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->debug(__FUNCTION__ .
				"(): create package failed client id : $client_id, courier id : $courier_id");

			return null;
		}

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): create package with code : ' . $package_code);

		$address_db       = new Address_Db();
		$clients_db       = new Clients_Db();
		$package_details  = new PackageDetail();
		$settings_db      = new Settings_Db();
		$price_calculator = new Price_Calculator();

		// receive generated package code
    // insert into package details
	  
    $details['last_updated'] = date ("Y-m-d H:i:s", time());
		$package_details->synchronizeWithArray($details);
		$package_details->save();


		$sender_addr_id = $address_db->does_package_address_exists ($from);

		// if address already exists use existing address id
		// else create new address and details 
		// and insert the above into clients table
    
		if (!$sender_addr_id)
			$sender_addr_id  = $address_db->insert_new_address($from);

    // insert into addresses received address
		// check if recepient address already in the database

		$recep_addr_id = $address_db->does_package_address_exists ($to);

		// if not in the database insert new address and get the id

		if (!$recep_addr_id)
			$recep_addr_id = $address_db->insert_new_address($to);


		// calculate the amount for the package calculated price
    // query google or yahoo geographic api

    $geo_locate = new Geo_Locate();
    $lat_lng_from = $geo_locate->latLngByAddress($from);
    $lat_lng_to   = $geo_locate->latLngByAddress($to);

    $amount = $price_calculator->calculate_price($courier_id, $lat_lng_from, $lat_lng_to, $details);

    // insert into packages above info

		$p = new Package();
		$p['package_code']    = $package_code;
		$p['courier_id']      = $courier_id;
		$p['from_address_id'] = $sender_addr_id;
		$p['to_address_id']   = $recep_addr_id;
		$p['client_id']       = $client_id;
		$p['status_id']       = Doctrine::getTable('PackageStatus')->findOneByStatus('pending')->getId();
		$p['detail_id']       = $package_details->id;

/// partner sales charges
		list($amount, $partner_tax, $price, $tax) = $price_calculator->get_prices_list ($courier_id, $amount);
		$p['partner_price'] 	= $amount;
		$p['partner_tax'] 		= $partner_tax;
		$p['price'] 					= $price;
		$p['tax']             = $tax;

		$p->save();

		// get package id

		$package_id = $p['id'];

		// insert payments
		$payment_obj = Doctrine::getTable('Payment')->findOneByPaymentCode($payment_code);
		$pp = new PackagePayment();
		$pp['payment_id'] = $payment_obj['id'];
		$pp['package_id'] = $package_id;
		$pp->save();

		return $package_id;
	}


	function create_payment ($payment_code, $client_id, $payment_type = "", $amount = 0, $ipn_string = "")
	{
		if (!$payment_code || !$client_id || trim($payment_code) == "")
			return -1;

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): create payment with code : ' . $payment_code);

	  $payment = new Payment();
		$payment->payment_code = $payment_code;
		$payment->client_id    = $client_id;

		if ($payment_type != "")
			$payment->payment_type = $payment_type;

		if ($amount != 0)
			$payment->amount = $amount;

		if ($ipn_string != "")
			$payment->ipn_string = $ipn_string;

		$payment->save();
		return $payment['id'];
	}


	// payment code - code for entire transaction
	// gateway like paypal
  // post information in the post

	function set_packages_paidfor ($payment_code, $trn_id, $gateway, $post)
	{
		if (!$payment_code || !$trn_id || trim($payment_code) == "")
		{
			sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): payment or trn_id code not given');
			return -1;
		}

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): payment for packages made : ' . $payment_code);

		// paypal and test IPN
    if ($gateway == 'paypal')
    {
	  	if (!isset($post['mc_gross']) && isset($post['mc_gross_1']))
			  $post['mc_gross'] = $post['mc_gross_1'];

		  // alertpay setting mc_gross
		  if (!isset($post['mc_gross']) && isset($post['ap_amount']))
	  		$post['mc_gross'] = $post['ap_amount'];
    }


		// set the payments table complete

		$payment = Doctrine::getTable('Payment')->findOneByPaymentCode($payment_code);
		$payment['payment_type'] = $gateway;
		$payment['ipn_string']   = serialize($post);
		$payment['trn_id']       = $trn_id;
		$payment['amount']       = ($gateway == 'paypal') ? $post['mc_gross'] - $post['mc_fee'] : $post['amount'] ;

		// save

		$payment->save();

		// update all packages statuses for this payment

		$package_status = Doctrine::getTable('PackageStatus')->findOneByStatus('paid');

		// get all package numbers associated with payment id

    $p = Doctrine_Query::create()
         ->select('p.id')
				 ->from('Package p')
         ->leftJoin('p.PackagePayment pp')
         ->leftJoin('pp.Payment pt')
         ->where('pt.payment_code = ?', $payment_code)
				 ->execute()
				 ->toArray();

		foreach ($p as $key => $value)
		{
			$curr_p = Doctrine::getTable('Package')->find($value['id']);
			$curr_p['status_id'] = $package_status['id'];
			$curr_p->save();
		}
	}


	function email_packages_paidfor ($payment_code, $settings = array(), $emails = '')
	{
		if (!$payment_code || trim($payment_code) == "")
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . "(): payment_code $payment_code is not valid");
			return null;
		}

    // email packages that have been paid for

		$this->packages_info = array();
		$q = Doctrine_Query::create()
				  ->select('p.*')
					->addSelect('cli.id')
					->addSelect('cd.name as courier_name')
					->addSelect('(SELECT TRIM(CONCAT_WS(" ", a1.apt_unit, a1.street_number, a1.street_name, a1.city, a1.postal_code)) FROM Address a1 WHERE a1.id = p.from_address_id) as from_text_address')
					->addSelect('(SELECT TRIM(CONCAT_WS(" ", a2.apt_unit, a2.street_number, a2.street_name, a2.city, a2.postal_code)) FROM Address a2 WHERE a2.id = p.to_address_id) as to_text_address')
					->addSelect('pd.ready_time')
					->addSelect('pd.ready_date')
					->addSelect('pd.num_pieces')
					->addSelect('pd.weight')
					->addSelect('wt.type as weight_type')
					->addSelect('pd.reference')
					->addSelect('pt.type as package_type')
					->addSelect('pd.round_trip')
					->addSelect('pd.instructions')
					->from('Package p')
					->leftJoin('p.Courier c')
					->leftJoin('p.Client cli')
					->leftJoin('cli.ClientDetail cd')
					->leftJoin('p.PackageDetail pd')
					->leftJoin('p.PackagePayment pp')
					->leftJoin('pd.PackageType pt')
					->leftJoin('pp.Payment pyt')
					->leftJoin('pd.WeightType wt')
					->where('pyt.payment_code = ?', $payment_code);

    $packages_info = array();

    foreach( $q->fetchArray() as $row)
      $this->packages[] = $row;

		// see if the email array set or if not then use the settings email
		$emails_to  = $emails;
		
		if (isset($emails_to) && sizeof($settings) > 0)
			$emails_to = isset($settings[0]['email']) ? array($settings[0]['email'] => $settings[0]['name']) : '';

		$action = sfContext::getInstance()->getController()->getAction('payment', 'index');
		$action->packages = $this->packages;
		$action->subject  = "Courier Express order $payment_code was completed";

		// if emails to is valid
		if (is_array($emails_to) && isset($this->packages))
		{
    	$emailer_db = new Emailer_Db();

			foreach ($emails_to as $email_to => $name)
    		$emailer_db->send_email
					($action, $email_to, $action->subject, 'email', 'paid_for', array('packages' => $this->packages));
		}
	}


	function email_single_package_paidfor($package_id, $settings)
	{
		if (!$package_id)
		{
			if (sfContext::hasInstance())
	    	sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): package id not given');

			return null;
		}

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): email for paid package : ' . $package_id);

		$q = Doctrine_Query::create()
				  ->select('p.id')
					->addSelect('cd.name')
					->addSelect('cli.id')
					->addSelect('(SELECT TRIM(CONCAT_WS(" ", a1.apt_unit, a1.street_number, a1.street_name, a1.city, a1.postal_code)) FROM Address a1 WHERE a1.id = p.from_address_id) as from_text_address')
					->addSelect('(SELECT TRIM(CONCAT_WS(" ", a2.apt_unit, a2.street_number, a2.street_name, a2.city, a2.postal_code)) FROM Address a2 WHERE a2.id = p.to_address_id) as to_text_address')
					->addSelect('pd.ready_time')
					->addSelect('pd.ready_date')
					->addSelect('pd.num_pieces')
					->addSelect('pd.weight')
					->addSelect('wt.type as weight_type')
					->addSelect('pd.reference')
					->addSelect('pt.type as package_type')
					->addSelect('pd.round_trip')
					->addSelect('pd.instructions')
					->from('Package p')
					->leftJoin('p.Client cli')
					->leftJoin('p.PackageDetail pd')
					->leftJoin('cli.ClientDetail cd')
					->leftJoin('pd.PackageType pt')
					->leftJoin('pd.WeightType wt')
					->where('p.id = ?', $package_id)
					->fetchOne()
					->toArray();

		$action = sfContext::getInstance()->getController()->getAction('payment', 'index');
		$action->package      = $q;
		$action->settings     = $settings;
		$action->subject      = "Courier Express package " . $q['package_code']." has been paid";
   
    $emailer_db = new Emailer_Db();
    $emailer_db->send_email
			($action, $action->settings['email'], $action->subject, 'email', 'single_paid_for',
			array('package' => $action->package, 'settings' => $action->settings));
	}


	// 
	// generate alphanumeric code for packages or payments

	function generate_alphanumeric_code ($length)
	{
		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): generate alphanumeric code');

		if (!is_numeric($length) || $length < 0)
		{
			if (sfContext::hasInstance())
	    	sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): error in length given length:' . $length);
			return -1;
		}

		$random= "";
		srand((double)microtime()*1000000);

		$data  = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data .= "0FGH45OP89";

		for($i = 0; $i < $length; $i++)
			$random .= substr($data, (rand()%(strlen($data))), 1);

		// capitalize
		$code = strtoupper($random);

		// check if package or payment id does exist, if it does run generate_alphanumeric_code again
		if ($this->does_package_exist($code) || $this->does_payment_exist($code))
			$random = $this->generate_alphanumeric_code($length);

		return $code;
	}

	
	function does_package_exist ($package_code)
	{
		if (!$package_code)
			return false;

		$package = Doctrine::getTable('Package')->findOneByPackageCode($package_code);
		return $package;
	}


	function does_payment_exist ($payment_code)
	{
		if (!$payment_code)
			return false;

		$payment = Doctrine::getTable('Payment')->findOneByPaymentCode($payment_code);
		return $payment;
	}


	function get_courier_received_packages ($courier_id, $q)
	{
		return $this->get_packages_list ($courier_id, 'paid', 'courier', $q);
	}


	function get_courier_delivered_packages($courier_id, $q)
	{
		return $this->get_packages_list ($courier_id, 'delivered', 'courier', $q);
	}


	function get_packages_list ($id, $status, $type, $q)
	{
		if ($id <= 0)
		  return -1;

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): get packages list id : ' . $id);

  	$q->select('(p.price + p.tax) as amount')
        ->addSelect('cd.name as company_name')
        ->addSelect('cd.phone as company_phone')
        ->addSelect('p.package_code')
        ->addSelect('pd.id')
        ->addSelect('( TIME_TO_SEC(pd.ready_time) + UNIX_TIMESTAMP(pd.ready_date) ) as package_datetime')
        ->addSelect('st.id')
        ->addSelect('ps.status as status')
        ->addSelect('(SELECT TRIM(CONCAT_WS(" ", a.apt_unit, a.street_number, a.street_name, a.city, a.postal_code)) FROM Address a WHERE a.id = p.from_address_id) as from_address')
        ->addSelect('(SELECT TRIM(CONCAT_WS(" ", ad.apt_unit, ad.street_number, ad.street_name, ad.city, ad.postal_code)) FROM Address ad WHERE ad.id = p.to_address_id) as to_address')
        ->addSelect('st.type as type')
        ->addSelect('p.partner_price')
        ->addSelect('p.partner_tax')
        ->addSelect('(p.partner_price + p.partner_tax) as payout_amount')
        ->from('Package p')
		    ->leftJoin('p.PackageDetail pd')
		    ->leftJoin('p.PackageStatus ps')
		    ->leftJoin('pd.ServiceLevelType st')
		    ->leftJoin('p.Courier c')
		    ->leftJoin('c.Client cli')
		    ->leftJoin('cli.ClientDetail cd');

		if (is_array($status))
		{
			foreach ($status as $curr_st)
				$q->orWhere("ps.status = ?", $curr_st);
		}
		else
			$q->where("ps.status = ?", $status);


		if ($type == 'courier')
        $q->addWhere('p.courier_id = ?', $id);
		else if ($type == 'client')
				$q->addWhere('p.client_id = ?', $id);

		// add ordering by datetime
		$q->orderBy('package_datetime desc');

    return $q;
	}


	function get_client_received_packages ($client_id, $filter_sql = "")
	{
		return $this->get_packages_list ($client_id, 'paid', 'client', $filter_sql);
	}


	// get packages list for client that were delivered

	function get_client_delivered_packages($client_id, $filter_sql)
	{
		return $this->get_packages_list ($client_id, 'delivered', 'client', $filter_sql);
	}


	// get packages list for client that were delivered and cancelled

	function get_client_delivered_and_cancelled_packages($client_id, $filter_sql)
	{
		return $this->get_packages_list ($client_id, array('delivered', 'cancelled'), 'client', $filter_sql);
	}


	// package code - to identify package
	// status - package status like paid, or delivered
	// signed by - recepient who signed for it
	
	function set_courier_package_status($package_code, $status, $signed_by = 'NULL')
	{
		if (!$package_code || !$status)
			return null;

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): set package status : ' . $package_code);

		$package = Doctrine::getTable('Package')->findOneByPackageCode($package_code);
		$package->PackageDetail['signed_by'] = $signed_by;
		$package['status_id'] = Doctrine::getTable('PackageStatus')->findOneByStatus($status)->getId();
		$package->save();

    // email to the customer that his shippment is delivered
    // extract the clients email address
    $info = $this->get_client_info_from_package($package_code);

    if (isset($info) && $info['email'] != "")
    {
   		$action = sfContext::getInstance()->getController()->getAction('payment', 'index');
    	$action->package_code = $package_code;
    	$action->status       = $status;
    	$action->signed_by    = $signed_by;
    	$action->info         = $info;
    	$action->subject      = "Courier Express Package status change";

    	$emailer_db = new Emailer_Db();
    	$emailer_db->send_email
        ($action, sfConfig::get('app_email_noreply'), $action->subject, 'email', 'package_status', array());

			// send sms with updated status change
			$res = Doctrine::getTable('Package')->findOneByPackageCode($package_code);
			if ($res)
				Tools_Lib::send_sms_client($res['client_id'], "Status for package $package_code has changed to $status");
    }
	}


	function get_client_info_from_package($package_code)
	{
		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): info for package : ' . $package_code);

    return Doctrine_Query::create()
          ->select('cd.email as email')
          ->addSelect('cd.name as name')
          ->addSelect('(SELECT TRIM(CONCAT_WS(" ", a1.apt_unit, a1.street_number, a1.street_name, a1.city, a1.postal_code)) FROM Address a1 WHERE a1.id = p.from_address_id) as from_address')
          ->addSelect('(SELECT TRIM(CONCAT_WS(" ", a2.apt_unit, a2.street_number, a2.street_name, a2.city, a2.postal_code)) FROM Address a2 WHERE a2.id = p.to_address_id) as to_address')
          ->from('Package p')
          ->leftJoin('p.Client c')
          ->leftJoin('c.ClientDetail cd')
          ->where('p.package_code = ?', $package_code)
 				  ->fetchOne()->toArray();
	}


	function get_package_ids_for_payment ($payment_code)
	{
    if (!$payment_code)
    {
			if (sfContext::hasInstance())
	      sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): payment code not given');
      return -1;
    }

  	return Doctrine_Query::create()
          ->select('p.id')
          ->from('Package p')
				  ->leftJoin('p.PackagePayment pp')
				  ->leftJoin('pp.Payment ps')
					->where('pp.payment_code = ?', $payment_code)
          ->fetchArray();
	}


	function get_package_info ($package_code)
	{
		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): get package info for : ' . $package_code);

		$p1 = Doctrine::getTable('Package')->findOneByPackageCode($package_code);
		$client_address = $from_address = $to_address = "";
		if ($p1)
		{
			$cli_prov = $cli_country = $prov1 = $prov2 = $country1 = $country2 = "";

			$client   = Doctrine::getTable('Client')->findOneById($p1->client_id);
			$address1 = Doctrine::getTable('Address')->findOneById($p1->from_address_id);
			$address2 = Doctrine::getTable('Address')->findOneById($p1->to_address_id);

			$address_db = new Address_Db();

			$prov1 = $address_db->get_code_by_province_id_and_country ($address1->province_id, $address1->country_id);
			$prov2 = $address_db->get_code_by_province_id_and_country ($address2->province_id, $address2->country_id);
			$cli_prov = $address_db->get_code_by_province_id_and_country ($client->Address->province_id, $client->Address->country_id);

			$country1 = $address_db->get_country_by_id ($address1->country_id);
			$country2 = $address_db->get_country_by_id ($address2->country_id);
			$cli_country = $address_db->get_country_by_id ($client->Address->country_id);

			// get actual addresses
			$client_address = $client->Address->apt_unit . ' ' .
												$client->Address->street_number . ' ' .
												$client->Address->street_name . ' ' .
												$client->Address->city . ' ' .
												$client->Address->postal_code . ' ' .
												$cli_prov . ' ' . $cli_country;

			$from_address   = $address1->apt_unit . ' ' .
												$address1->street_number . ' ' .
												$address1->street_name . ' ' .
												$address1->city . ' ' .
												$address1->postal_code . ' ' .
												$prov1 . ' ' . $country1;

			$to_address     = $address2->apt_unit . ' ' .
												$address2->street_number . ' ' .
												$address2->street_name . ' ' .
												$address2->city . ' ' .
												$address2->postal_code . ' ' .
												$prov2 . ' ' . $country2;
		}


    $q = Doctrine_Query::create()
         ->select('cd.name as name')
         ->addSelect('cd.email as email')
         ->addSelect('p.from_address_id')
         ->addSelect('"'.$client_address.'" as client_address')
         ->addSelect('"'.$from_address.'" as from_address')
         ->addSelect('"'.$to_address.'" as to_address')
         ->addSelect('pd.ready_time as ready_time')
         ->addSelect('pd.ready_date as ready_date')
         ->addSelect('( TIME_TO_SEC(pd.ready_time) + UNIX_TIMESTAMP(pd.ready_date) ) as package_datetime')
         ->addSelect('pd.num_pieces as num_pieces')
         ->addSelect('pd.weight as weight')
         ->addSelect('wt.type as weight_type')
         ->addSelect('pd.reference as reference')
         ->addSelect('ptype.type as package_type')
         ->addSelect('(SELECT slt.type FROM ServiceLevelType slt WHERE slt.id = pd.service_level_type_id) as service_level_type')
         ->addSelect('(SELECT dt.type FROM DeliveryType dt WHERE dt.id = pd.delivery_type_id) as delivery_type')
         ->addSelect('pd.round_trip as round_trip')
         ->addSelect('pd.instructions as instructions')
         ->addSelect('pd.signed_by as signed_by')
         ->addSelect('pd.last_updated as last_updated')
         ->from('Package p')
         ->leftJoin('p.Client c')
         ->leftJoin('c.ClientDetail cd')
         ->leftJoin('p.PackageDetail pd')
         ->leftJoin('pd.WeightType as wt')
         ->leftJoin('pd.PackageType as ptype')
         ->where('p.package_code = ?', $package_code);

    $info = array();
    foreach($q->fetchArray() as $row)
    {
			$row['round_trip']   = ($row['round_trip'])   ? 'Yes' : 'No';
			$row['reference']    = ($row['reference'])    ? $row['reference'] : 'None';
			$row['instructions'] = ($row['instructions']) ? $row['instructions'] : 'None';
      $info  = $row;
    }

    return $info;
	}


  // determine if package can be delivered

	function can_be_delivered ($pkg_info)
	{
		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): find if package can be delivered');

		// get all courier ids in the system
		$couriers_db      = new Couriers_Db();
		$price_calculator = new Price_Calculator();
		$courier_ids      = $couriers_db->get_courier_ids();
    
   	// query google or yahoo geographic api
    $geo_locate = new Geo_Locate();
    $lat_lng_from = $geo_locate->latLngByAddress($pkg_info['sender']);
    $lat_lng_to   = $geo_locate->latLngByAddress($pkg_info['recep']);


		// go through all courier ids and call price calculator to get price
		foreach ($courier_ids as $courier_id)
		{
			$curr_price =
			$price_calculator->calculate_price ($courier_id, $lat_lng_from, $lat_lng_to, $pkg_info['PackageDetail']);

			if ($curr_price > 0)
				return true;
		}

		return false;
	}
  

  function find_cheapest_courier ($package)
  {
		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): finding the cheapest courier for package');

    $cheapest_courier_id = NULL;

		// get all courier ids in the system
		$couriers_db      = new Couriers_Db();
		$price_calculator = new Price_Calculator();
		$courier_ids      = $couriers_db->get_courier_ids();
    
    $lowest_price = 0;
		// go through all courier ids and call price calculator to get price
    // assign the cheapest courier to cheapest_courier_id

    // query google or yahoo geographic api
    $geo_locate = new Geo_Locate();
    $lat_lng_from = $geo_locate->latLngByAddress($package['sender']);
    $lat_lng_to   = $geo_locate->latLngByAddress($package['recep']);

		// loop through the couriers
		foreach ($courier_ids as $courier_id)
		{
			$curr_price = $price_calculator
					->calculate_price($courier_id, $lat_lng_from, $lat_lng_to, $package['PackageDetail']);

      if ($lowest_price < $curr_price)
      {
        $lowest_price = $curr_price;
        $cheapest_courier_id = $courier_id;
      }
		}
    
		return array('courier_id' => $cheapest_courier_id, 'amount' => $lowest_price);
  }


	function find_payment_details_by_package ($package_code)
	{
		if (!$package_code)
			return null;

		if (sfContext::hasInstance())
	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): payment details for package :' . $package_code);

		$q = Doctrine_Query::create()
				->select('(p.price + p.tax) as amount')
				->addSelect('pay.payment_code as payment_code')
				->addSelect('pay.trn_id as trn_id')
				->from('Package p')
				->leftJoin('p.PackagePayment pp')
				->leftJoin('pp.Payment pay')
				->where('p.package_code = ?', $package_code)
				->fetchArray();

		return ($q[0]) ? $q[0] : null;
	}



	function get_address_from_contact_name ($client_id, $name)
	{
		if (!$name)
			return array();
		
		$r = $q = null;

		$q = Doctrine_Query::create()
				->select('from_address_id')
				->addSelect('p.id')
				->addSelect('pd.sender_phone')
				->from('Package p')
				->leftJoin('p.PackageDetail pd')
				->andWhere('pd.sender_contact = ?' , $name)
				->andWhere('p.client_id = ?', $client_id)
				->fetchOne();
		
		if ($q && $q['from_address_id'])
			 $r = Doctrine::getTable('Address')->findOneById($q['from_address_id']);

		if ($r)
			return array_merge($r->toArray(), array('phone' => $q->PackageDetail['sender_phone']));

		$q = Doctrine_Query::create()
				->select('to_address_id')
				->addSelect('p.id')
				->addSelect('pd.phone')
				->from('Package p')
				->leftJoin('p.PackageDetail pd')
				->andWhere('pd.contact = ?' , $name)
				->andWhere('p.client_id = ?', $client_id)
				->fetchOne();

		if ($q && $q['to_address_id'])
			 $r = Doctrine::getTable('Address')->findOneById($q['to_address_id']);

		if ($r)
			return array_merge($r->toArray(), array('phone' => $q->PackageDetail['phone']));
	}

}
