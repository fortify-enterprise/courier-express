<?php

class Couriers_Db extends Base_Lib
{
	function get_courier_name ($courier_id)
	{
		if ($courier_id <= 0)
		{
			sfContext::getInstance()->getLogger()->err('get_courier_name(): courier id is not valid');
			return -1;
		}

		$c = Doctrine::getTable('Courier')->find($courier_id);
		return $c->Client->ClientDetail['name'];
	}


	function get_login_id_from_clientid ($client_id)
	{
		if (!$client_id)
			return -1;

    return Doctrine_Query::create()
           ->select('login_id')
           ->from('Client')
           ->where('id = ?', $client_id)
           ->fetchOne()->getLoginId();
	}


	function get_detail_id_from_clientid ($client_id)
	{
		if (!$client_id)
			return -1;

    return Doctrine_Query::create()
           ->select('detail_id')
           ->from('Client')
           ->where('id = ?', $client_id)
           ->fetchOne()->getDetailId();
	}


	function set_courier_name ($client_id, $name)
	{
		if (!$client_id || !$name || trim($name) == "")
			return -1;

		$detail_id = $this->get_detail_id_from_clientid($client_id);
		$client_detail = Doctrine::getTable('ClientDetail')->find($detail_id);
		$client_detail->name = $name;
		$client_detail->save();
	}


	function set_address ($client_id, $address)
	{
		if (!$client_id || !$address || !is_array($address))
			return -1;

    $address_db = new Address_Db();
		$address_db->set_client_address($client_id, $address);
	}


	function set_email ($client_id, $email)
	{
		if (!$client_id || !$email || trim($email) == "")
			return -1;

		$client = Doctrine::getTable('Client')->findOneById($client_id);
		$client->ClientDetail['email'] = $email;
		$client->save();
	}


	function set_courier_surcharges ($courier_id, $surcharges)
	{
		if (!$courier_id || !$surcharges || !is_array($surcharges))
			return -1;

	  $weight_surcharge = $surcharges['weight'];
		$gas_surcharge 		= $surcharges['gas'];
		$weight_limit 		= $surcharges['weight_limit'];

		if (!$weight_surcharge)
			$weight_surcharge = 0;
		if (!$gas_surcharge)
			$gas_surcharge= 1;
		if (!$weight_limit)
			$weight_limit = 25;

    $weight_sur_id = Doctrine::getTable('SurchargeType')->findOneByType('weight')->getId();
		$gas_sur_id    = Doctrine::getTable('SurchargeType')->findOneByType('gas')->getId();

		$sur_weight = new Surcharge();
		$sur_weight->courier_id = $courier_id;
		$sur_weight->amt_limit = $weight_limit;
		$sur_weight->amount = $weight_surcharge;
		$sur_weight->surcharge_type_id = $weight_sur_id;
		$sur_weight->replace();

		$sur_weight = new Surcharge();
		$sur_weight->courier_id = $courier_id;
		$sur_weight->amount = $gas_surcharge;
		$sur_weight->surcharge_type_id = $gas_sur_id;
		$sur_weight->replace();
	}


	function get_courier_surcharges ($courier_id)
	{
		if (!$courier_id)
		{
			sfContext::getInstance()->getLogger()->err('get_courier_surcharges(): courier_id given not valid');
			return -1;
		}

		$weight_sur_id = Doctrine::getTable('SurchargeType')->findOneByType('weight')->getId();
		$gas_sur_id    = Doctrine::getTable('SurchargeType')->findOneByType('gas')->getId();

    $q = Doctrine_Query::create()
         ->select('amount as weight')
         ->addSelect('amt_limit as weight_limit')
         ->from('Surcharge')
         ->where('courier_id = ?', $courier_id)
         ->addWhere('surcharge_type_id = ?', $weight_sur_id);

    $weight = '';
    $weight_limit = '';
    foreach($q->fetchArray() as $row)
		{
      $weight = $row['weight'];
      $weight_limit = $row['weight_limit'];
		}

    $q = Doctrine_Query::create()
         ->select('amount as gas')
         ->from('Surcharge')
         ->where('courier_id = ?', $courier_id)
         ->addWhere('surcharge_type_id = ?', $gas_sur_id);

    $gas = '';
    foreach($q->fetchArray() as $row)
      $gas = $row['gas'];

		return array('weight' => $weight, 'gas' => $gas, 'weight_limit' => $weight_limit);
	}


	function set_courier_discount ($courier_id, $discounts)
	{
		if (!$courier_id)
		{
			sfContext::getInstance()->getLogger()->err('set_courier_discount(): courier_id given not valid');
			return -1;
		}

	  $discount_percentage = $discounts['discount_percentage'];

		if (!$discount_percentage)
			$discount_percentage = 0;

		$cd = Doctrine::getTable('CourierDiscount')->findOneByCourierId($courier_id);

		if (!$cd)
		{
    	$cd = new CourierDiscount();
    	$cd['discount']   = $discount_percentage;
    	$cd['courier_id'] = $courier_id;
    	$cd->save();
		}
		else
		{
			$cd['discount'] = $discount_percentage;
			$cd->replace();
		}
	}


	function get_courier_discount ($courier_id)
	{
		if (!$courier_id)
		{
			sfContext::getInstance()->getLogger()->err('get_courier_discount(): courier_id given not valid');
			return -1;
		}

		$q = Doctrine::getTable('CourierDiscount')->findOneByCourierId($courier_id);
		return array('discount_percentage' => isset($q['discount']) ? $q['discount'] : 0);
	}


	function get_courier_availability ($courier_id)
	{
		if (!isset($courier_id))
		{
			sfContext::getInstance()->getLogger()->err('get_courier_availability(): courier_id given not valid');
			return -1;
		}
		return Doctrine::getTable('Courier')->find($courier_id)->getAvailable();
	}


	function set_courier_availability ($courier_id, $available)
	{
		if ($courier_id <= 0)
		{
			sfContext::getInstance()->getLogger()->err('set_courier_availability(): courier_id given not valid');
			return -1;
		}

		$courier = Doctrine::getTable('Courier')->find($courier_id);
		$courier->available = $available;
		$courier->save();
	}


	function is_courier_enabled($courier_id)
	{
		if ($courier_id <= 0)
		{
			sfContext::getInstance()->getLogger()->err('is_courier_enabled(): courier_id given not valid');
			return -1;
		}

		return Doctrine::getTable('Courier')->find($courier_id)->getEnabled();
	}


	function get_polygon_type ($zone_id)
	{
		if (!$zone_id)
		{
			sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): zone_id given not valid');
			return -1;
		}

    return Doctrine_Query::create()
         ->select('pt.id as id')
         ->from('Zone z')
         ->leftJoin('z.PolygonType pt')
         ->where('z.id = ?', $zone_id)
				 ->fetchOne()->getId();
	}


	function get_zone_id_by_name ($zone_name)
	{
		if ($zone_name == "")
			return -1;

		$res = Doctrine::getTable('Zone')->findOneByName($zone_name);
		return $res['id'];
	}


	// get elements from the zone given by zone id

	function get_zone_polygon ($zone_id)
	{
		if (!$zone_id)
		{
			sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): zone_id given not valid');
			return -1;
		}

  	$r = Doctrine_Query::create()
         ->select('zp.*')
         ->from('Zone z')
         ->leftJoin('z.ZonePolygon zp')
         ->where('z.id = ?', $zone_id)
				 ->fetchOne();

    return $r;
	}


  function get_zone_id_from_polygon_id ($polygon_id)
  {
		if (!$polygon_id)
		{
			sfContext::getInstance()->getLogger()->err(__FUNCTION__ . '(): polygon_id given not valid');
			return -1;
		}

    $res = Doctrine::getTable('Zone')->findOneByZonePolygonId($polygon_id);
    return isset($res['id']) ? $res['id'] : -1;
  }


  // zonetype_id - calculated by
	// if (from_country == to_country)
	// if (from_postal == to_postal)
	// else ..

  function get_courier_service_level_price ($service_level_id, $from_zone_id, $to_zone_id)
  {
		if (!$from_zone_id || !$to_zone_id)
			return 0;

		$price = 0;

 		$q = Doctrine_Query::create()
         ->select('pl.price')
         ->addSelect('zp.id')
         ->from('ZonePrice zp')
         ->leftJoin('zp.PriceLevel pl')
         ->andWhere('zp.service_level_id = ?', $service_level_id)
         ->andWhere('zp.from_zone_id = ?', $from_zone_id)
         ->andWhere('zp.to_zone_id = ?', $to_zone_id)
         ->fetchOne();

	  return ($q['price']) ? $q['price'] : 0;
	}


	// get price depending on service level type, where from, where to and which
	// courier id is shipping the parcel

  function get_courier_service_price ($service_level_type_id, $from_zone_id, $to_zone_id, $courier_id)
  {
		if (!$from_zone_id || !$to_zone_id)
			return 0;

		$price = 0;

 		$q = Doctrine_Query::create()
         ->select('pl.price as price')
				 ->from('ZonePrice zp')
         ->leftJoin('zp.ServiceLevel sl')
         ->leftJoin('zp.PriceLevel pl')
         ->andWhere('zp.from_zone_id = ?', $from_zone_id)
         ->andWhere('zp.to_zone_id = ?', $to_zone_id)
				 ->andWhere('sl.courier_id = ?', $courier_id)
         ->andWhere('sl.type_id = ?', $service_level_type_id)
         ->andWhere('sl.is_enabled = 1')
         ->fetchOne();

	  return ($q['price']) ? $q['price'] : 0;
	}


  function get_courier_polygonal_zones ($courier_id, $type = 'kml')
  {
		if (!$courier_id)
			return 0;

    $q =  Doctrine_Query::create()
            ->select('zpt.*')
            ->addSelect('zp.pdata')
            ->from('ZonePolygon zp')
            ->leftJoin('zp.ZonePolygonType zpt')
            ->leftJoin('zp.Zone z')
            ->where('z.courier_id = ?', $courier_id)
            ->andWhere('zpt.type = ?', $type)
            ->fetchArray();
		return ($q) ? $q : -1;
  }


	// get all zones for the courier

	function get_courier_zone_list ($courier_id)
	{
		$result    = Doctrine::getTable('Zone')->findByCourierId($courier_id)->toArray();
		$zone_list = array();
		$ids       = array();
		$names     = array();

		foreach($result as $row)
		{
			$ids[]   = $row['id'];
			$names[] = $row['name'];
		}
		$zone_list[] = $ids;
		$zone_list[] = $names;
		return $zone_list;
	}


	// get all couriers ids who are available to deliver and enabled
	// by the system

	function get_courier_ids ()
	{
		$c   = Doctrine::getTable('Courier');
		$res = $c->findByAvailableAndEnabled(1, 1)->toArray();

		$ids = array();
		foreach ($res as $row)
			$ids[] = $row['id'];

		return $ids;
	}


	// get information about courier, filter based on enabled and available
	// only triggered when filter_off is on

	function get_couriers_info ($is_available = 1, $is_enabled = 1, $filter_off = 0, $is_assoc = false)
	{
  	$q =
         Doctrine_Query::create()
         ->select('c.id as id')
         ->addSelect('cli.id')
         ->addSelect('cd.id')
         ->addSelect('cl.id')
         ->addSelect('cd.name as name')
         ->addSelect('cd.email as email')
         ->addSelect('c.profit_cut')
         ->addSelect('cl.email as email')
         ->addSelect('cl.password as password')
         ->from('Courier c')
				 ->innerJoin('c.Client cli')
         ->leftJoin('cli.ClientDetail cd')
         ->leftJoin('cli.ClientLogin cl');

		if (!$filter_off)
		{
			$q->where('c.available = ?', $is_available);
			$q->andWhere('c.enabled = ?', $is_enabled);
		}

		$ids 	  = array();
		$names  = array();
		$emails = array();
		$profit_cuts  = array();
		$emails = array();
		$password     = array();
		
		$res = $q->fetchArray();
		if ($is_assoc)
			return $res;

		foreach($res as $row)
		{
			$ids[] 		      = $row['id'];
			$names[] 	      = $row['name'];
			$emails[]       = $row['email'];
			$profit_cuts[]  = $row['profit_cut'];
			$emails[] = $row['email'];
			$password[]     = $row['password'];
		}

		$info[] = $ids;
		$info[] = $names;
		$info[] = $emails;
		$info[] = $profit_cuts;
		$info[] = $emails;
		$info[] = $password;

		return $info;
	}


	// get single courier information, more detailed

	function get_courier_info ($courier_id)
	{
		if (!$courier_id)
			return -1;

   return Doctrine_Query::create()
         ->select('c.id as id')
         ->addSelect('cli.id as client_id')
         ->addSelect('p.id as province_id')
         ->addSelect('cd.name as name')
         ->addSelect('cd.email as email')
         ->addSelect('cd.details as details')
         ->addSelect('cd.phone as phone')
         ->addSelect('cd.contact contact')
         ->addSelect('a.apt_unit apt_unit')
         ->addSelect('a.street_number as street_number')
         ->addSelect('a.street_name street_name')
         ->addSelect('a.postal_code as postal_code')
         ->addSelect('a.city as city')
         ->addSelect('a.country_id as country_id')
         ->addSelect('p.alpha_code as alpha_code')
         ->addSelect('c.profit_cut as profit_cut')
         ->addSelect('c.available as available')
         ->addSelect('c.enabled as enabled')
         ->addSelect('cl.email as email')
         ->addSelect('cl.password as password')
         ->from('Courier c')
         ->leftJoin('c.Client cli')
         ->leftJoin('cli.Address a')
         ->leftJoin('cli.ClientDetail cd')
         ->leftJoin('cli.ClientLogin cl')
         ->leftJoin('a.Province p')
         ->where('c.id = ?', $courier_id)
         ->fetchOne()->toArray();
	}


	function get_courier_password ($client_id)
	{
		if ($client_id <= 0)
			return -1;

    return Doctrine_Query::create()
         ->select('cl.password as password')
         ->from('Client c')
         ->leftJoin('c.ClientLogin cl')
         ->where('c.id = ?', $client_id)
         ->fetchOne()->getPasswd();
	}


	function set_courier_info ($courier_id, $courier, $client_detail, $client_login, $address)
	{
		if (!$courier_id)
			return -1;

		$q = Doctrine_Query::create()
				->select('c.id, cl.id, cli.id, cd.id, a.id')
				->from('Courier c')
				->leftJoin('c.Client cli')
				->leftJoin('cli.ClientLogin cl')
				->leftJoin('cli.ClientDetail cd')
				->leftJoin('cli.Address a')
				->where('c.id = ?', $courier_id)
				->fetchOne();

		$sync = $q->toArray();
		$client_login['password_hash'] = Tools_Lib::getHash(trim($client_login['password']));


    // only update if email not already exists
		
		$logins_db = new Logins_Db();
//    if ($logins_db->get_clientid_from_login($client_login['email']) <= 0)
//    {
 			$cl_obj = Doctrine::getTable('ClientLogin')->find($sync['Client']['ClientLogin']['id']);
			$cl_obj->synchronizeWithArray($client_login);
			$cl_obj->save();
//   }

    // only update if email not already exists

//    if (!$logins_db->notification_email_exists($client_detail['email']))
//    {
			$cd_obj = Doctrine::getTable('ClientDetail')->find($sync['Client']['ClientDetail']['id']);
			$cd_obj->synchronizeWithArray($client_detail);
			$cd_obj->save();
//    }


		$addr_obj = Doctrine::getTable('Address')->find($sync['Client']['Address']['id']);
		$addr_obj->synchronizeWithArray($address);
		$addr_obj->save();

		$courier_obj = Doctrine::getTable('Courier')->find($courier_id);
		$courier['id'] = $courier_id;
		$courier['client_id'] = $sync['Client']['id'];
		$courier_obj->synchronizeWithArray($courier);
		$courier_obj->save();
	}


	// enabled - 1 // only enabled service levels for this courier
	// enabeld - 0 // all service levels for this courier

	function get_service_levels($courier_id, $enabled = 1)
	{
 		$q = Doctrine_Query::create()
         ->select('slt.id')
         ->addSelect('slt.type as type')
         ->addSelect('sl.courier_id')
         ->addSelect('sl.is_enabled')
         ->from('ServiceLevel sl')
         ->leftJoin('sl.ServiceLevelType slt')
         ->where('sl.courier_id = ?', $courier_id);

		if ($enabled)
			$q->addWhere('sl.is_enabled = 1');

    $ids     = array();
    $types   = array();
    $enabled = array();

    foreach($q->fetchArray() as $row)
    {
      $ids[]     = $row['id'];
      $types[]   = $row['type'];
      $enabled[] = $row['is_enabled'];
    }
    return array($ids, $types, $enabled);
	}


  function get_price_level_names ($courier_id)
  {
    $price_levels = array();
    $res = Doctrine::getTable('PriceLevel')->findByCourierId($courier_id);
    foreach ($res as $i => $level)
      $price_levels[$level['id']] = $level['name'];

    return $price_levels;
  }


  function get_price_level_prices ($courier_id)
  {
    $price_levels = array();
    $res = Doctrine::getTable('PriceLevel')->findByCourierId($courier_id);
    return (isset($res)) ? $res->toArray() : -1;
  }


  function insert_price_level ($price_level)
  {
    if (!is_array($price_level))
      return -1;

    $pl = new PriceLevel();
    $pl->synchronizeWithArray($price_level);
    $pl->save();
  }


  function update_price_level ($price_level)
  {
    $res = Doctrine_Query::create()
            ->update('PriceLevel pl')
            ->set('pl.price', '?', $price_level['price'])
            ->where('pl.courier_id = ?', $price_level['courier_id'])
            ->andWhere('pl.name = ?', $price_level['name'])
            ->execute();
  }


	function set_courier_service_levels ($courier_id, $service_levels)
	{
		$type_ids = Doctrine::getTable('ServiceLevelType')->findAll();
		$ids = array();
		if ($service_levels)
		{
			foreach ($service_levels as $position => $id)
				$ids[] = $id;
		}

		$service_levels = Doctrine::getTable('ServiceLevel')->findByCourierId($courier_id);
		if ($service_levels->count() != sizeof($type_ids))
		{
			$service_db = new Service_Db();
			$service_db->initialize_courier_with_services(Doctrine::getTable('Courier')->findOneById($courier_id)->getClientId());
		}

		foreach($service_levels as $service_level)
		{
			$is_enabled = in_array($service_level['id'], $ids) ? 1 : 0;
			$service_level['is_enabled'] = $is_enabled;
			$service_level->save();
		}
	}


	function insert_new_zone ($zone_name, $courier_id, $zone_type_id)
	{
		if ($zone_name && $courier_id && $zone_type_id)
		{
			$zone = new Zone();
			$zone['name']         = $zone_name;
			$zone['courier_id']   = $courier_id;
			$zone['zone_type_id'] = $zone_type_id;
		  $zone->save();
			return $zone['id'];
		}
		else
			die('update_zone_element: zone_element_id or element are missing');

		sfContext::getInstance()->getLogger()->err('insert_new_zone(): was unable to insert a new zone for courier id: ' . $courier_id);
		return -1;
	}


	function update_zone ($zone_id, $zone_name, $zone_type_id)
	{
		$z = Doctrine::getTable('Zone')->find($zone_id);
		$z['name']         = $zone_name;
		$z['zone_type_id'] = $zone_type_id;
		$z->save();
	}


	function delete_zone ($zone_id)
	{
		if (!$zone_id)
		{
			sfContext::getInstance()->getLogger()->err('delete_zone(): zone id is missing');
			return -1;
		}
		Doctrine::getTable('Zone')->find($zone_id)->delete();
	}


	function update_zones_price($courier_id, $from_zone_id, $to_zone_id, $service_level_id, $price)
	{
		if (!$courier_id || !$from_zone_id || !$to_zone_id || !$service_level_id || $price < 0)
		{
			sfContext::getInstance()
			->getLogger()
			->err("update_zones_price(): parameters not valid :
				courier_id = $courier_id
				from_zone_id = $from_zone_id
				to_zone_id = $to_zone_id
				service_level_id = $service_level_id
				price = $price
			");
			return -1;
		}

		$zp = new ZonePrice();
		$zp->price            = $price;
		$zp->from_zone_id     = $from_zone_id;
		$zp->to_zone_id       = $to_zone_id;
		$zp->service_level_id = $service_level_id;
		$zp->replace();
	}


  function email_packages_paidfor($payment_code)
  {
		$packages_db = new Packages_Db();
		$p  = Doctrine::getTable('Payment')->findOneByPaymentCode($payment_code);
		$pp = Doctrine::getTable('PackagePayment')->findByPaymentId($p['id'])->toArray();

		foreach($pp as $key => $value)
		{
			$cid = Doctrine::getTable('Package')->findOneById($value['package_id'])->getCourierId();

    	// get the notification email for this client
    	$settings = $this->get_courier_info ($cid);
			$packages_db->email_single_package_paidfor($value['package_id'], $settings);

   		// ...
			// send sms notifications
    	Tools_Lib::send_sms_payment($settings['client_id'], $payment_code);
		}
  }


	function create_partner ($client_id)
	{
		$partner = new Courier();
		$partner['client_id'] = $client_id;
		$partner['profit_cut'] = 10;
		$partner['available'] = 1;
		$partner['enabled'] = 1;
		$partner->save();
	}


	// ..
	// find out if courier has any packages to deliver
	// on login redirect to pending packages then

  function has_packages_to_deliver ($client_id)
  {
    if (!$client_id)
      return false;

		$courier_id = Doctrine::getTable('Courier')->findOneByClientId($client_id)->getId();
		$paid_status_id = Doctrine::getTable('PackageStatus')->findOneByStatus('paid')->getId(); 

    $q = Doctrine_Query::create()
        ->select('p.id')
        ->from('Package p')
        ->leftJoin('p.PackageDetail pd')
        ->where('UNIX_TIMESTAMP(NOW()) < (TIME_TO_SEC(pd.ready_time) +
          UNIX_TIMESTAMP(pd.ready_date))')
        ->andWhere('p.courier_id = ?', $courier_id)
        ->andWhere('p.status_id = ?', $paid_status_id)
        ->fetchArray();

    if (!$q[0])
      return false;

    if (count($q) > 0)
      return true;
  }
}
