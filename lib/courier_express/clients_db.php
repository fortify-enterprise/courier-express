<?php

class Clients_Db extends Base_Lib
{
	function info_by_facebook_uid_or_email ($fb_uid, $email)
	{
		if (!$fb_uid)
			return null;

		$q =  Doctrine_Query::create()
         ->select('ct.type')
         ->addSelect('c.id as client_id')
         ->addSelect('c.type_id')
         ->addSelect('cd.*')
         ->from('Client c')
         ->leftJoin('c.ClientDetail cd')
         ->leftJoin('c.ClientType ct')
         ->leftJoin('c.ClientLogin cl')
         ->where('cd.facebook_uid = ?', $fb_uid)
				 ->orWhere('cl.email = ?', $email)
         ->fetchArray();

		return (isset($q)) ? $q[0] : null;
	}


	function get_client ($client_id)
	{
		if (!$client_id)
			return array();

		return Doctrine::getTable('Client')->find($client_id);
	}


	function insert_client_details ($details)
	{
		if (empty($details))
		{
			if (sfContext::hasInstance())
				 sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): empty details given');

			return -1;
		}

		$cd = new ClientDetail();
		$details['registration_date'] = date("Y-m-d H:i:s",time());
		$cd->synchronizeWithArray($details);
		$cd->replace();

		return $cd['id'];
	}


	function get_client_types ()
	{
		$types = Doctrine::getTable('ClientType')->findAll();
		$types_list = array();
		foreach ($types as $key)
			$types_list []= $key['type'];

		if (sizeof($types_list) < 3)
			throw new sfException("too few client types present in database");

		return $types_list;
	}


	function id_from_type ($type)
	{
		$ct = Doctrine::getTable('ClientType')->findOneByType($type);
		return isset($ct['id']) ? $ct['id'] : -1;
	}


	function get_payment_profile_code ($client_id)
	{
		if (!$client_id)
			throw new sfException("client_id: '$client_id' is null");
  
		$q =  Doctrine_Query::create()
         ->select('cp.profile_code')
         ->addSelect('c.id')
         ->from('Client c')
         ->leftJoin('c.ClientPaymentDetail cp')
         ->where('c.id = ?', $client_id)
         ->fetchOne();

		$res = array();
		if ($q)
			$res = $q->toArray();

		return isset($q['ClientPaymentDetail']['profile_code']) ? $q['ClientPaymentDetail']['profile_code'] : NULL;
	}

	
	//
	//	Save payment profile code, for the client also need card type
	
	function set_payment_profile_code ($client_id, $profile_code, $card_type_id)
	{
		if (!$client_id || $client_id <= 0 || !$profile_code || $profile_code == "" || !$card_type_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): client_id is not valid or profile_code is empty');
			throw new sfException("client_id: '$client_id' or profile_code: '$profile_code' or card_type_id: '$card_type_id' are null");
		}

		$client = Doctrine::getTable('Client')->findOneById($client_id);
		$payment_detail = '';

		if (!$client->ClientPaymentDetail)
		{
			$payment_detail = new ClientPaymentDetail();
			$payment_detail->profile_code = $profile_code;
			$payment_detail->card_type_id = $card_type_id;
			$payment_detail->address_id = $client['address_id'];
			$payment_detail->save();

			$client['payment_detail_id'] = $payment_detail['id'];
		}
		else
			$client->ClientPaymentDetail['profile_code'] = $profile_code;

		$client->save();
	}


	//
	// get client name based on the client id

	function get_client_name ($client_id)
	{
		if (!$client_id)
			throw new sfException("client_id: '$client_id' is null");

  	$q =  Doctrine_Query::create()
         ->select('cd.name')
         ->addSelect('c.id')
         ->from('Client c')
         ->leftJoin('c.ClientDetail cd')
         ->where('c.id = ?', $client_id)
         ->fetchArray();

		return (isset($q['name'])) ? $q['name'] : -1;
	}


	//
	// find client id based on name

	function get_client_id_from_name ($name)
	{
		if (!$name || $name == "")
			return -1;

    $r = Doctrine_Query::create()
         ->select('c.id')
			   ->from('Client c')
         ->leftJoin('c.ClientDetail cd')
         ->where('cd.name = ?', $name)
         ->fetchOne();

    return $r['id'];
	}


	function set_client_name ($client_id, $name)
	{
		if ($client_id <= 0 || $name == "")
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): client_id is not valid or name is empty');
			return -1;
		}

		$client = Doctrine::getTable('Client')->findOneById($client_id);
		$client->ClientDetail['name'] = $name;
		$client->ClientDetail->save();

		if (!$client['detail_id'])
		{
			$client['detail_id'] = $client->ClientDetail['id'];
			$client->save();
		}

	}


	function get_client_settings ($client_id)
	{
		if (!$client_id)
			return array();

		$c = Doctrine::getTable('Client')->find($client_id);
		$client_detail  = $c->ClientDetail->toArray();
		$client_login   = $c->ClientLogin->toArray();
		$client_address = $c->Address->toArray();
		return array($client_detail, $client_login, $client_address);
	}


	function get_client_payment ($client_id)
	{
		if (!$client_id || $client_id <= 0)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): client_id is not valid');
			return -1;
		}

		$address_db = new Address_Db();
		$client     = Doctrine::getTable('Client')->find($client_id);

		if (!$client)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): non existent client id given');
			return -1;
		}

		if (!$client->ClientPaymentDetail)
			return -1;

		$payment = $client->ClientPaymentDetail->toArray();
		return $address_db->denormalize_province_id($payment);
	}


	function set_client_payment ($client_id, $payment)
	{
		if (!$client_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): client_id is not valid');
			return -1;
		}

		// set province id according to country
		$address_db = new Address_Db();
		$payment    = $address_db->normalize_province_id($payment);

		$client = Doctrine::getTable('Client')->find($client_id);
		$client->ClientPaymentDetail->synchronizeWithArray($payment);
		$client->save();
	}


	function email_packages_paidfor($payment_code)
	{
		// if payment code not valid return
		if (!$payment_code || trim($payment_code) == "")
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): payment_code is not valid');

			throw new sfException("payment code is not valid: '$payment_code'");
		}

  	$r = Doctrine_Query::create()
         ->select('p.client_id')
         ->from('Payment p')
         ->where('p.payment_code = ?', $payment_code)
         ->fetchOne();
		
		$client_id = '';
		if ($r)
      $client_id = $r['client_id'];
		else
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()
						->getLogger()
						->err(__FUNCTION__ . '(): client_id not found for payment code : ' . $payment_code);
			
			return null;
		}

		// get the notification email for this client
		$settings    = $this->get_client_settings ($client_id);
		$packages_db = new Packages_Db();
		$packages_db->email_packages_paidfor($payment_code, $settings);

   	// send sms notifications
    // ...
    // send sms to client that his order is paid for

    Tools_Lib::send_sms_client($client_id,
    "Courier Express payment for payment id: $payment_code was completed succesfully");

	}


	function get_contacts_for_client($client_id, $query_str)
	{
		if (!$client_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): client_id is not valid');

			return "";
		}

		$q = Doctrine_Query::create()
					->select('DISTINCT p.from_address_id')
					->addSelect('p.to_address_id')
					->addSelect('pd.contact')
					->addSelect('pd.sender_contact')
					->from('Package p')
					->leftJoin('p.PackageDetail pd')
					->where('p.client_id = ?', $client_id)
					->addWhere('pd.contact <> ""')
					->addWhere('pd.sender_contact <> ""')
					->fetchArray();

		if ($q)
		{
			$contacts = '';
			$unique_contacts = array();
   		foreach($q as $row)
			{
      	$contact_from = $row["PackageDetail"]["sender_contact"] ."|".$row["from_address_id"]."\n";
      	$contact_to   = $row["PackageDetail"]["contact"] ."|".$row["to_address_id"]."\n";
				@$unique_contacts[$contact_from] = 1;
				@$unique_contacts[$contact_to] = 1;
			}

			// make array into a string
			foreach ($unique_contacts as $contact => $value)
				$contacts .= $contact;

    	return $contacts;
		}

		return null;
	}


	function client_exists ($client_id)
	{
		if (!$client_id)
			return false;

		return Doctrine::getTable('Client')->findOneById($client_id);
	}

	
	function has_packages_pending ($client_id)
	{
		if (!$client_id)
			return false;

		$paid_status_id = Doctrine::getTable('PackageStatus')->findOneByStatus('paid')->getId();

		$q = Doctrine_Query::create()
				->select('p.id')
				->from('Package p')
				->leftJoin('p.PackageDetail pd')
				->where('UNIX_TIMESTAMP(NOW()) < (TIME_TO_SEC(pd.ready_time) +
				  UNIX_TIMESTAMP(pd.ready_date))')
				->andWhere('p.client_id = ?', $client_id)
				->andWhere('p.status_id = ?', $paid_status_id)
				->fetchArray();
  
		if (!$q[0])
			return false;

		if (count($q) > 0)
			return true;
	}
}
