<?php

class Service_Db extends Base_Lib
{
  function get_service_name_by_id($courier_id, $service_id)
  {
    if (!$service_id || !$courier_id)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): courier id : $courier_id, service id : $service_id");
      return null;
    }

 	  $r = Doctrine_Query::create()
         ->select('slt.type as service_name')
         ->from('ServiceLevel sl')
				 ->leftJoin('sl.ServiceLevelType slt')
         ->where('sl.courier_id = ?', $courier_id)
				 ->addWhere('sl.type_id = ?', $service_id)
				 ->addWhere('sl.is_enabled = 1')
				 ->fetchArray();

		$service_name = '';
		foreach($r as $row)
			$service_name = $row['service_name'];

		return $service_name;
	}

	
	function get_service_level_id_from_type ($courier_id, $type)
	{
    if (!$type || !$courier_id)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): courier id : $courier_id, type : $type");
      return null;
    }


		$r = Doctrine_Query::create()
					->select('sl.id as id')
					->from('ServiceLevel sl')
					->leftJoin('sl.ServiceLevelType slt')
					->where('sl.courier_id = ?', $courier_id)
					->andWhere('slt.type = ?', $type)
					->fetchOne();

		return $r['id'];
	}

	
	function get_id_from_service_type ($service_type)
	{
		if (!$service_type || trim($service_type) == "")
			return -1;

    $r = Doctrine_Query::create()
         ->select('slt.id')
         ->from('ServiceLevelType slt')
         ->where('slt.type = ?', $service_type)
				 ->fetchOne()->toArray();

		return (isset($r['id']) ? $r['id'] : -1);
	}


	function get_service_type_by_id ($id)
	{
    if (!$id)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): id : $id");
      return null;
    }

		$services = $this->get_services (false);
		return $services[$id]['type'];
	}


  function get_service_hours($service_id)
  {
    if (!$service_id)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): service id : $service_id");
      return null;
    }

		$services = $this->get_services (false);
		return $services[$service_id]['hours'];
	}


  function get_services ($assoc = true)
  {
   $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['ServiceLevelType']))
      {
        $res = $system_settings['ServiceLevelType'] = Doctrine::getTable('ServiceLevelType')->createQuery('s')->fetchArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
      else
        $res = $system_settings['ServiceLevelType'];
    }
    else
      $res = Doctrine::getTable('ServiceLevelType')->createQuery('s')->fetchArray();


    // create associative array
		$services = '';
		if ($assoc)
		{
			foreach($res as $row)
				$services[$row['id']] = $row['type'];
		}
		else
			$services = $res;

    return $services;
	}


  function get_delivery_types ($assoc = true)
  {

   $res = '';
    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['DeliveryType']))
      {
        $res = $system_settings['DeliveryType'] = Doctrine::getTable('DeliveryType')->createQuery('d')->fetchArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
      else
        $res = $system_settings['DeliveryType'];
    }
    else
      $res = Doctrine::getTable('DeliveryType')->createQuery('d')->fetchArray();


    // create associative array
    $types = '';
    if ($assoc)
    {
      foreach($res as $row)
        $types[$row['id']] = $row['type'];
    }
    else
      $types = $res;

    return $types;
  }


	function insert_new_service ($service, $hours)
	{
    if (!$service || !$hours)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): hours : $hours, service : $service");
      return null;
    }

		$sl = new ServiceLevel();
		$sl['type']  = $service;
		$sl['hours'] = $hours;
		$sl->save();
	}


	function initialize_courier_with_services ($courier_id)
	{
		if (!$courier_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__.'(): courier id missing');
			return null;
		}

		$services = $this->get_services();
		foreach ($services as $id => $service)
		{
			try
			{
				$sl = new ServiceLevel();
				$sl['courier_id'] = $courier_id;
				$sl['type_id']    = $id;
				$sl['is_enabled'] = 1;
				$sl->save();
			}
			catch (Exception $e)
			{
			}
		}
	}


	// is every service level needed associated with courier ?
	function all_courier_services_present ($courier_id)
	{
		if (!$courier_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__.'(): courier id missing');
			return false;
		}

		$res_sl = Doctrine::getTable('ServiceLevelType')->findAll()->toArray();
		$service_level_ids = array();
		foreach ($res_sl as $service)
			$service_level_ids []= $service['id'];

		$res_sl_courier = Doctrine::getTable('ServiceLevel')->findByCourierId($courier_id);
		if ($res_sl_courier)
		{
			$courier_service_ids = array();
			$courier_services = $res_sl_courier->toArray();
			foreach ($courier_services as $courier_service)
				$courier_service_ids []= $courier_service['type_id'];

			foreach ($service_level_ids as $service_level)
			{
				if (!in_array($service_level, $courier_service_ids))
					return false;
			}
		}

		return true;
	}


	function insert_courier_service ($service, $courier_id)
	{
		if (!$service || !$courier_id)
		{
			if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): courier id : $courier_id, service : $service");
			return null;
		}

		$slt = new ServiceLevelType();
		$slt['type']       = $service;
		$slt['courier_id'] = $courier_id;
		$slt->save();
	}


	function get_id_from_username ($username)
	{
    if (!$username)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): username : $username");
      return null;
    }

    $q = Doctrine_Query::create()
         ->select('c.id')
         ->from('Client c')
         ->leftJoin('c.ClientLogin cl')
         ->where('cl.email = ?', $username)
				 ->fetchOne();

		return (isset($q['id']) ? $q['id'] : -1);
	}


	function can_cancel_package_order ($package_code)
	{
		if (!$package_code)
			return false;

		$q = Doctrine_Query::create()
				->select('(UNIX_TIMESTAMP(NOW()) < TIME_TO_SEC(pd.ready_time) +
				UNIX_TIMESTAMP(pd.ready_date)) as can_cancel')
				->from('Package p')
				->leftJoin('p.PackageDetail pd')
				->where('p.package_code = ?', $package_code)
				->fetchArray();
	
		if (!$q[0])
			return false;
		
		return ($q[0]['can_cancel'] == 1);
	}

	// date 2010-9-10 example
	// time 8:00AM example
	function is_datetime_in_the_past ($date, $time)
	{
		if (!$date || !$time)
			return true;
	
		$stmt = Doctrine_Manager::getInstance()->connection();
		$results = $stmt->execute("SELECT (UNIX_TIMESTAMP(NOW()) > TIME_TO_SEC('$time') +
		UNIX_TIMESTAMP('$date')) as is_in_the_past");
		$q = $results->fetchAll();

		if (!$q[0])
			return true;

		return ($q[0]['is_in_the_past'] == 1);
	}


	function get_weight_id_by_type ($type)
	{
    if (!$type)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): type : $type");
      return null;
    }

    $weight_id = '';

    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['WeightType']))
      {
        $system_settings['WeightType']= Doctrine::getTable('WeightType')->findAll()->toArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
      foreach ($system_settings['WeightType'] as $weight)
        if ($weight['type'] == $type)
        {
          $weight_id = $weight['id'];
          break;
        }
    }
    else
    {
      $weight_id = Doctrine_Query::create()->select('id')
      ->from('WeightType')->where('type = ? ', $type)->fetchOne();
    }
		
    return $weight_id;
	}


	function get_weight_type_by_id ($id)
	{
    if (!$id)
    {
      if (sfContext::hasInstance())
        sfContext::getInstance()->getLogger()->err(__FUNCTION__. "(): id : $id");
      return null;
    }

    $type = '';

    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
      if (!isset($system_settings['WeightType']))
      {
        $system_settings['WeightType']= Doctrine::getTable('WeightType')->findAll()->toArray();
        sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
      }
      foreach ($system_settings['WeightType'] as $weight)
        if ($weight['id'] == $id)
        {
          $type = $weight['type'];
          break;
        }
    }
    else
    {
      $type = Doctrine_Query::create()->select('type')
      ->from('WeightType')->where('id = ? ', $id)->fetchOne();
    }
    return $type;

	}
}
