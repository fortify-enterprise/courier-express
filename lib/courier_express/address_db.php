<?php

class Address_Db extends Base_Lib
{
  function get_country_name($client_id)
  {
    if (!$client_id)
      throw new sfException("client_id: '$client_id' is empty");

    $client = Doctrine::getTable('Client')->findOneById($client_id);
    if (!$client)
      return "";

    return $client->Address->Country['name'];
  }


  function get_province_name($client_id)
  {
    if (!$client_id)
      return "";
		
		// check for country name
		$country_name = $this->get_country_name($client_id);
		if (!$country_name)
			return null;

    $client  = Doctrine::getTable('Client')->findOneById($client_id);
    $name    = NULL;

    switch (strtolower($country))
    {
      case 'canada':
        $name = $client->Address->Province['province_territory'];
      break;

      case 'united states':
        $name = $client->Address->State['name'];
      break;

      default:
        $name = $client->Address->Province['province_territory'];
      break;
    }

    return $name;
  }


	// get address as a string
  function get_address_string ($client_id)
  {
    if (!$client_id || $client_id <= 0)
      return null;

		$address_str = Doctrine_Query::create()
			->select('TRIM (CONCAT_WS (" ", a.apt_unit, a.street_number, a.street_name, a.street_type, a.city, a.postal_code))')
			->from('Client c')
			->leftJoin('c.Address a')
			->where('c.id = ? ', $client_id)
			->fetchOne();

		// return and compress spaces
    return preg_replace('/\s+/', '\s', $address_str);
  }


  function get_text_address ($address)
  {
    if (!isset($address['country_id']))
      throw new sfException("country id is not defined for address ".print_r($address,1));

    $address  = $this->denormalize_province_id($address);
    $province = $this->get_province_by_ids($address['province_id'], $address['state_id'], $address['country_id']);

    return $address['apt_unit'] . ' ' . $address['street_number'] . ' ' .
    $address['street_name'] . ', ' . $address['city'] . ' ' . $address['postal_code'] . ', ' .
    $province . ', ' . $this->get_country_by_id($address['country_id']);
  }


	// get text address from an object
  function get_obj_text_address ($address_id)
  {
    if (!isset($address_id))
      return -1;

		$address  = Doctrine::getTable('Address')->findOneById($address_id);
		if (!$address)
			return -1;

    $province = $this->get_province_by_ids($address['province_id'], $address['state_id'], $address['country_id']);

  	$address_str = trim($address['apt_unit'] . ' ' . $address['street_number'] . ' ' . $address['street_name'] . ', ' .
		$address['city'] . ' ' . $address['postal_code'] . ', ' . $province . ', ' . $this->get_country_by_id($address['Country']['id']));
		return $address_str;
  }



  // get street types in the system

  function get_street_types ()
  {
    return Doctrine::getTable('StreeType')->createQuery('st')->fetchArray();
  }


  // get countries list supported by the system

  function get_countries_list ($is_assoc = false)
  {
    $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['Country']))
      {
        $res = $system_settings['Country'] = Doctrine::getTable('Country')->findAll()->toArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
			else
				$res = $system_settings['Country'];
    }
    else
    {
    	$res = Doctrine::getTable('Country')->createQuery('c')->fetchArray();
    }


		// create associative array

    if ($is_assoc)
    {
    	$assoc_list = array();
      foreach ($res as $key => $value)
        $assoc_list[$value['id']] = $value['name'];

      return $assoc_list;
    }
    return $res;
  }


  function get_provinces_list ($is_assoc = false)
  {
    $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['Province']))
      {
    		$res = $system_settings['Province'] = Doctrine::getTable('Province')->createQuery('p')
								->orderBy('p.province_territory ASC')->fetchArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
			else
				$res = $system_settings['Province'];
    }
    else
    {
    	$res = $system_settings['Province'] = Doctrine::getTable('Province')->createQuery('p')
								->orderBy('p.province_territory ASC')->fetchArray();
    }


		// create associative array

    $assoc_list = array();
    if ($is_assoc)
    {
      foreach ($res as $key => $value)
        $assoc_list[$value['id']] = $value['province_territory'];

      return $assoc_list;
    }
    return $res;
  }


  function get_states_list ($is_assoc = false)
  {
    $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['State']))
      {
    		$res = $system_settings['State'] = Doctrine::getTable('State')->createQuery('s')
								->orderBy('s.name ASC')->fetchArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
			else
				$res = $system_settings['State'];
    }
    else
    {
    	$res = $system_settings['State'] = Doctrine::getTable('State')->createQuery('s')
						->orderBy('s.name ASC')->fetchArray();
    }


		// create associative array

    $assoc_list = array();
    if ($is_assoc)
    {
      foreach ($res as $key => $value)
        $assoc_list[$value['id']] = $value['name'];

      return $assoc_list;
    }
    return $res;
  }


  function get_province_by_ids ($province_id, $state_id, $country_id)
  {
		if (!$country_id || (!$province_id && !$state_id))
      throw new sfException("country_id: '$country_id' or province_id: '$province_id' or '$state_id' are null");
  
    $province = '';
    $country  = $this->get_country_by_id($country_id);

    switch(strtolower($country))
    {
      case 'canada':
        $province_obj = Doctrine::getTable('Province')->find($province_id);
        if ($province_obj)
          $province = $province_obj->getProvince_Territory();
      break;

      case 'united states':
        $state_obj = Doctrine::getTable('State')->find($state_id);
        if ($state_obj)
          $province = $state_obj->getName();
      break;

    }

    return $province;
  }


  function get_province_id_by_country_and_code ($country_id, $province_code)
  {
    if (!$country_id || !$province_code)
      throw new sfException("country_id: '$country_id' or province_code: '$province_code' are null");

    $province_id = '';
    $country     = $this->get_country_by_id($country_id);

    switch(strtolower($country))
    {
      case 'canada':
        $province_obj = Doctrine::getTable('Province')->findOneByAlphaCode($province_code);
        if ($province_obj)
          $province_id = $province_obj['id'];
      break;

      case 'united states':
        $state_obj = Doctrine::getTable('State')->findOneByAlphaCode($province_code);
        if ($state_obj)
          $province_id = $state_obj['id'];
      break;

    }

    return $province_id;
  }


  // get province alpha code from province id and country

  function get_code_by_province_id_and_country ($province_id, $country_id)
  {
    if (!$country_id || !$province_id)
      throw new sfException("country_id: '$country_id' or province_id: '$province_id' are null");

    $code = '';
    $country  = $this->get_country_by_id($country_id);

		if (!$country)
			throw new sfException("country name undefined");

    switch(strtolower($country))
    {
      case 'canada':
        $province_obj = Doctrine::getTable('Province')->find($province_id);
        if ($province_obj)
          $code = $province_obj['alpha_code'];
      break;

      case 'united states':
        $state_obj = Doctrine::getTable('State')->find($province_id);
        if ($state_obj)
          $code = $state_obj['alpha_code'];
      break;

    }

    return strtoupper($code);
  }


  // country name by id
  function get_country_by_id ($country_id)
  {
    if (!$country_id)
      throw new sfException("country_id: '$country_id' is null");

		$name = '';

		// cache in session
    if (sfContext::hasInstance())
		{
			$system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
			if (!isset($system_settings['Country']))
			{
				$system_settings['Country']= Doctrine::getTable('Country')->findAll()->toArray();
				sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
			}
			foreach ($system_settings['Country'] as $country)
				if ($country['id'] == $country_id)
				{
					$name = $country['name'];
					break;
				}
		}
		else
		{
			$name = Doctrine_Query::create()->select('name')
			->from('Country')->where('id = ? ', $country_id)->fetchOne();
  	}
		return $name;
	}


  // set province id according to country
  function denormalize_province_id ($info)
  {
    if (!is_array($info))
      return array();

    if (!$info || !isset($info['Country']['id']))
      return $info;

    if (isset($info['province_id']))
    {
      $info['Province']['id'] = $info['province_id'];
      unset($info['province_id']);
    }

    if (isset($info['state_id']))
    {
      $info['State']['id'] = $info['state_id'];
      unset($info['state_id']);
    }

    if (isset($info['country_id']))
    {
      $info['Country']['id'] = $info['country_id'];
      unset($info['country_id']);
    }

    return $info;
  }


  // set province id according to country
  function normalize_province_id ($info)
  {
		if (!is_array($info))
			throw new sfException("info needs to be an array");

		if (isset($info['Province']))
		{
    	if (isset($info['Province']['id']))
     		$info['province_id'] = $info['Province']['id'];
    	else
     		$info['province_id'] = 1;
		}

		if (isset($info['State']))
		{
    	if (isset($info['State']['id']))
      	$info['state_id'] = $info['State']['id'];
   	 	else
      	$info['state_id'] = 1;
		}

    if (isset($info['Country']['id']))
      $info['country_id'] = $info['Country']['id'];

		$country_name = strtolower($this->get_country_by_id($info['country_id']));

    // 
		// check if province is set but country id is states
		// then set province to 1 and state to province id

		if ($country_name == 'united states' && $info['province_id'] > 1)
		{
			$info['state_id'] = $info['province_id'];
			$info['province_state_id'] = $info['province_id'];
			$info['province_id'] = 1;
		}

		if ($country_name == 'united states' && $info['state_id'] > 1)
			$info['province_state_id'] = $info['state_id'];

		if ($country_name == 'canada' && $info['province_id'] > 1)
			$info['province_state_id'] = $info['province_id'];

    unset($info['Province']);
    unset($info['State']);
    unset($info['Country']);
    return $info;
  }


  function insert_new_address ($new_address, $return_existing_id_if_match = false)
  {
    if (!$new_address ||!is_array($new_address))
      throw new sfException("new address is not an array or undefined");

    if (sfContext::hasInstance())
      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): inserting new address');

    $new_address = $this->normalize_province_id($new_address);
    $address_id  = $this->does_address_exists($new_address);


		if ($address_id)
		{
    	if (sfContext::hasInstance())
      	sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): existing address present, using id : ' . $address_id);
		}

    if ($address_id > 0 && $return_existing_id_if_match)
      return $address_id;

    $new_address['postal_code'] = strtoupper($new_address['postal_code']);
    $address = new Address();
    $address->synchronizeWithArray($new_address);
    $address->save();  

    return $address['id'];
  }


  function remove_address ($address_id)
  {
    if (!$address_id || $address_id <= 0)
      throw new sfException("attempt to remove address with id : '$address_id'");
	
		// remove all related locates
		Doctrine_Query::create()
    	->delete('Locate l')
    	->where('l.address_id = ?', $address_id)
			->execute();

		// delete address
		Doctrine_Query::create()
    	->delete('Address a')
    	->where('a.id = ?', $address_id)
			->execute();

		/*$addr = Doctrine::getTable('Address')->find($address_id);
		if ($addr)
			$addr->delete();*/
  }


  function get_address_id_from_clientid ($client_id)
  {
    if (!$client_id || $client_id < 0)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): error client_id is not valid');
      throw new sfException("client_id: '$client_id' is null");
    }
   
    $c = Doctrine::getTable('Client')->find($client_id);

    if (!$c)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): error client does not exist in db');
      return -1;
    }

    return $c['address_id'];
  }


  function set_client_address ($client_id, $address)
  {
    if (!$client_id || !is_array($address))
      return;

    $address['postal_code']  = strtoupper($address['postal_code']);
    $address_id              = $this->get_address_id_from_clientid($client_id);
    $existing_address        = Doctrine::getTable('Address')->find($address_id);
    $country_id              = $address['country_id'];
    $country_name            = strtolower($this->get_country_by_id($country_id));

    // decide which id to use

    switch ($country_name)
    {
      case 'canada':
        $address['province_id'] = $address['state_province_id'];
      break;

      case 'united states':
        $address['state_id'] = $address['state_province_id'];
      break;
    }

    $existing_address->synchronizeWithArray($address);
    $existing_address->save();
  }


  function get_client_address ($client_id)
  {
    if ($client_id <= 0)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): error client_id is not valid');
      return null;
    }

    $c = Doctrine::getTable('Client')->find($client_id);
    return $c->Address->toArray();
  }


	function does_address_id_exists ($id)
	{
		$res = Doctrine::getTable('Address')->findOneById($id);
		return ($res != null);
	}


  // check if address exists and if yes returns the address id
  function does_address_exists ($address)
  {
    if (!is_array($address) || sizeof($address) == 0)
      return false;

    $address = $this->normalize_province_id($address);
		
    $q = Doctrine_Query::create()
         ->select('a.id')
         ->from('Address a');

		if ($address['apt_unit'])
      $q->addWhere('lcase(a.apt_unit) = ?', strtolower($address['apt_unit']));

    $q->addWhere('lcase(a.street_number) = ?', strtolower($address['street_number']));
    $q->addWhere('lcase(a.street_name) = ?', strtolower($address['street_name']));
    $q->addWhere('a.country_id = ?', $address['country_id']);
    $q->addWhere('lcase(a.postal_code) = ?', strtolower($address['postal_code']));
    $q->addWhere('lcase(a.city) = ?', strtolower($address['city']));

		$country = $this->get_country_by_id($address['country_id']);
		switch(strtolower($country))
		{
			case 'canada':
        $q->addWhere('a.province_id = ?', $address['province_id']);
			break;

			case 'united states':
        $q->addWhere('a.state_id = ?', $address['state_id']);
			break;
    }
		$r = $q->fetchOne();
		return isset($r) ? $r['id'] : false;
  }


	function does_package_address_exists ($address)
	{
		// address id already exists ?
		$address_id = $this->does_address_exists($address);
		if (!$address_id)
			return false;
  
	
		// is this address id taken by any client ?
		$res = Doctrine::getTable('Client')->findOneByAddressId($address_id);
		if ($res)
			return false;

		return (($address_id) ? $address_id : false);
	}


  function init_province_country_ids (sfActions &$actions, $country_id = null)
  {
    if (!$country_id)
      return;

    // switch ocuntry name
    $country_name = ($country_id) ? strtolower($this->get_country_by_id($country_id)) : 'canada';

    switch ($country_name)
    {
      case 'canada':
        // provinces list

        $provinces = $this->get_provinces_list();
        foreach ($provinces as $province)
         {
           $province_ids[]   = $province['id'];
           $province_names[] = $province['province_territory'];
         }
      break;

      case 'united states':
          $states = $this->get_states_list();
        foreach ($states as $state)
         {
           $province_ids[]   = $state['id'];
            $province_names[] = $state['name'];
         }
      
      break;
    }


    // assign options arrays
    $actions->province_ids    = $province_ids;
    $actions->province_names  = $province_names;


    // countries list

    $country_ids   = array();
    $country_names = array();
    $countries     = $this->get_countries_list();

    foreach ($countries as $country)
    {
      $country_ids[]   = $country['id'];
      $country_names[] = $country['name'];
    }


    // assign options arrays

    $actions->country_ids    = $country_ids;
    $actions->country_names  = $country_names;
  }


  function get_contact_information ($client_id)
  {
    if (!$client_id)
      return array();

    // choose which country client is coming form
    $c = Doctrine::getTable('Client')->find($client_id);
    $country_name = strtolower($this->get_country_by_id($c->Address['country_id']));

    $q = Doctrine_Query::create()
         ->select('a.apt_unit')
         ->addSelect('c.id')
         ->addSelect('a.street_number')
         ->addSelect('a.street_name')
         ->addSelect('a.street_type')
         ->addSelect('a.city')
         ->addSelect('a.postal_code')
         ->addSelect('cd.name')
         ->addSelect('cd.phone')
         ->addSelect('cd.contact');

    switch ($country_name)
    {
      case 'canada':
        $q->addSelect('p.alpha_code')
          ->addSelect('p.id as state_province_id');

      break;
      case 'united states':
        $q->addSelect('s.alpha_code')
          ->addSelect('s.id as state_province_id');
      break;
    }

    $q->from('Client c')
       ->leftJoin('c.Address a')
       ->leftJoin('c.ClientDetail cd')
       ->leftJoin('c.ClientLogin cl')
       ->leftJoin('a.Province p')
       ->leftJoin('a.State s')
       ->where('c.id = ?', $client_id);

    return $q->fetchOne()->toArray();
  }


	function country_by_postal_code ($postal)
	{
		$res = array();

		if (preg_match('/[ABCEGHJKLMNPRSTVXY]\d[A-Z][ ]*\d[A-Z]\d$/i', $postal))
		{
			$res = Doctrine::getTable('Country')->findOneByName('Canada');
		}
		else if (preg_match('/\d{5}(-\d{4})?/i', $postal))
		{
			$res = Doctrine::getTable('Country')->findOneByName('United States');
		}

		return isset($res['id']) ? $res['id'] : null;
	}


  function get_city_province_from_postal ($postal_code, $country_id)
  {
		// patch if no country id given detect country by postal code
		// note this might change for more then 2 countries
		if (!$country_id)
			$country_id = $this->country_by_postal_code($postal_code);

		if (!$postal_code || !$country_id || trim($postal_code) == "")
			return array();

    $country = strtolower($this->get_country_by_id($country_id));
    $r = array();
    $q = array();


    switch ($country)
    {
      case 'canada':

        $r = Doctrine::getTable('PostalCode')->findOneByPostalCode($postal_code);
        if ($r && $r['province_code'])
          $q = Doctrine::getTable('Province')->findOneByAlphaCode($r['province_code']);

      break;

      case 'united states':

        $r = Doctrine::getTable('ZipCode')->findOneByZipCode($postal_code);
        if ($r && $r['state_prefix'])
          $q = Doctrine::getTable('State')->findOneByAlphaCode($r['state_prefix']);

      break;
    }

    return ($r) ? array($r->getCity(), $q->getId()) : null;
  }


  // get country id from 2 or 3 digit code

  function get_country_id_by_code ($code)
  {
    if (!$code)
      throw new sfException("code given is not valid code: '$code'");
    
    $res = array();

    if (strlen($code) == 2)
      $res = Doctrine::getTable('Country')->findOneByCode2(strtoupper($code));
    else if (strlen($code) == 3)
      $res = Doctrine::getTable('Country')->findOneByCode3(strtoupper($code));

    if (sizeof($res) <= 0)
      return -1;

    return $res['id'];
  }


  function get_code_by_country_id ($country_id)
  {
    if (!$country_id)
      throw new sfException("country_id: '$country_id' is null");
  
    $res = Doctrine::getTable('Country')->findOneById($country_id);
    return isset($res['code2']) ? $res['code2'] : -1;
  }


  function get_state_province_names ($country_name, $province_id = 1, $state_id = 1)
  {
		if (!$country_name)
			throw new sfException("country name: '$country_name' is null");

    $default_id = 1;
    $state_province_names = $this->get_provinces_list(true);
    switch (strtolower($country_name))
    {
      case 'canada':
        $default_id = $province_id;
      break;

      case 'united states':
        $state_province_names = $this->get_states_list(true);
        $default_id = $state_id;
      break;
    }
    return array($state_province_names, $default_id);
  }
}

