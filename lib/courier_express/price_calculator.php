<?php

class Price_Calculator extends Base_Lib
{

  private $couriers_db = null;
  private $geo_locate  = null;


  function Price_Calculator ()
  {
    $this->couriers_db = new Couriers_Db();
		
   	// query google or yahoo geographic api
    $this->geo_locate = new Geo_Locate();
  }


	function update_courier_selections ($courier_selections, &$packages_cart)
	{
    if (!isset($packages_cart) || !is_array($packages_cart))
      return null;

		foreach($courier_selections as $package_id => $courier_id)
		{
			for ($i = 0; $i < sizeof($packages_cart[$package_id]['couriers']); $i++)
			{
				if ($packages_cart[$package_id]['couriers'][$i]['id'] == $courier_id)
				{
					// move to the top of the list
					$curr_courier = $packages_cart[$package_id]['couriers'][$i];
					unset($packages_cart[$package_id]['couriers'][$i]);
					array_unshift($packages_cart[$package_id]['couriers'], $curr_courier);
				}
			}
		}
	}



  // calculate the price for the order

	function calculate_total_price ($packages_cart)
	{
		if (!is_array($packages_cart))
			return null;

		$overall_price = 0;

    if (!isset($packages_cart) || !is_array($packages_cart))
      return $overall_price;


		foreach ($packages_cart as $package_id => $package_data)
		{
  		// in case package did not have latlng coordinates
	    $lat_lng_from = $this->geo_locate->latLngByAddress($package_data['sender']);
     	$lat_lng_to = $this->geo_locate->latLngByAddress($package_data['recep']);

			$overall_price +=
					$this->calculate_price
					($package_data['couriers'][0]['id'],
					 $lat_lng_from,
					 $lat_lng_to,
					 $package_data['PackageDetail']
			); 
		}
		return $overall_price;
	}


	function update_available_cart_prices ($packages_cart)
	{
	  if (!isset($packages_cart) || !is_array($packages_cart))
      return null;
	

		// get available and enabled couriers list
		$couriers = $this->couriers_db->get_couriers_info(1, 1, false, true);

		foreach ($packages_cart as $package_id => $package_data)
			$packages_cart[$package_id] = $this->update_available_package_prices ($couriers, $package_data);

		return $packages_cart;
	}


	function update_available_package_prices ($couriers, $package_data)
	{
		$from           = $package_data['sender'];
		$lat_lng_from   = @$from['latlng'];
		$to             = $package_data['recep'];
		$lat_lng_to     = @$to['latlng'];
		$details        = $package_data['PackageDetail'];
		$courier_id     = $package_data['courier_id'];

		// in case package did not have latlng coordinates
		if (!$lat_lng_from)
			$lat_lng_from = $this->geo_locate->latLngByAddress($from);

		if (!$lat_lng_to)
			$lat_lng_to = $this->geo_locate->latLngByAddress($to);

		unset($package_data['couriers']);

		// loop through to get available prices
		for($i = 0; $i < sizeof($couriers); $i++)
		{
			// last parameter means get all couriers with their prices as array
			// instead of the cheapest courier available
			$courier_id = $couriers[$i]['id'];
			$price = $this->calculate_price($courier_id, $lat_lng_from, $lat_lng_to, $details);
			if ($price)
	   		$package_data['couriers'][$courier_id] = array('id' => $courier_id, 'name' => $couriers[$i]['name'], 'price' => $price);
		}

		// sort by price
		$volume = array();
		foreach ($package_data['couriers'] as $key => $row)
    	$volume[$key] = $row['price'];

		array_multisort($volume, SORT_ASC, $package_data['couriers']);
		return $package_data;
	}


	// from location -
	// country = based on country id, city, postal
	// to location -
	// country = based on country id, city, postal

	// details -
	// like weight etc ..

	function calculate_price ($courier_id, $lat_lng_from, $lat_lng_to, $details)
	{
		$address_db       = new Address_Db();
    $point_locate     = new Point_Locate();
		$service_level_type_id  = isset($details['ServiceLevelType']['id']) ?
		$details['ServiceLevelType']['id'] : $details['service_level_type_id'];

		if (!$service_level_type_id || !$lat_lng_from || !$lat_lng_to)
		{
   		if (sfContext::hasInstance())
			{
      	sfContext::getInstance()->getLogger()->err(__FUNCTION__ . 
				"(): service_level_type_id: $service_level_type_id, courier_id : $courier_id, lat_lng_from : $lat_lng_from, lat_lng_to : $lat_lng_to");
				// to prevent redirect loop we exit
				die(__FUNCTION__ . "(): service_level_type_id: $service_level_type_id, courier_id : $courier_id, lat_lng_from : $lat_lng_from, lat_lng_to : $lat_lng_to");
			}
		}

		// log
   	if (sfContext::hasInstance())
      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): calculate price, courier_id : ' . $courier_id);



    // get all current courier polygonal zones
    $polygonal_zones = $this->couriers_db->get_courier_polygonal_zones($courier_id);

		if (!$polygonal_zones || !is_array($polygonal_zones) || $polygonal_zones == -1)
			return 0;
  
    // loop through polygonal zones seing if we can match
    $from_zone_id = 0;
    $to_zone_id   = 0;


    foreach ($polygonal_zones as $i => $zone)
    {
      $polygon = null;

      if ($zone['ZonePolygonType']['type'] == 'kml')
        $polygon = $this->geo_locate->get_polygon_from_kml($zone['pdata']);
  
			// can not find proper location
			if (!$polygon || $polygon == -1)
				continue;

   		// find potential zone id that matches given from lat/lon's

      if ($point_locate->pointInPolygon($lat_lng_from, $polygon))
			{
        $from_zone_id = $this->couriers_db->get_zone_id_from_polygon_id($zone['id']);
 	
				if (sfContext::hasInstance())
				{
    			sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . 
					"(): lat_lng_from : $lat_lng_from, hit polygon id : ".$zone['id']);
				}
			}
   		// find potential zone id that matches given to lat/lon's

      if ($point_locate->pointInPolygon($lat_lng_to, $polygon))
			{
        $to_zone_id = $this->couriers_db->get_zone_id_from_polygon_id($zone['id']);
				if (sfContext::hasInstance())
				{
    			sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "(): lat_lng_to : $lat_lng_to, hit polygon id : ".$zone['id']);
				}

			}
			// if both zones found continue further
			if ($from_zone_id && $to_zone_id)
				break;
		}
  
		// if at least one of the zones not defined return 0
		if (!$from_zone_id || !$to_zone_id)
			return 0;




		/** compute price_level_id and service add on charge if present **/

		$price_level_info =
			$this->get_pricelevel_id_and_service_addon_price($from_zone_id, $to_zone_id, $service_level_type_id, $lat_lng_from);

		//if ($from_zone_id == 33)
		//print_r($price_level_info);
		//exit;
		// try reversing zones
		if (!$price_level_info)
			$price_level_info =
				$this->get_pricelevel_id_and_service_addon_price($to_zone_id, $from_zone_id, $service_level_type_id, $lat_lng_from);

		// have not found any valid serivce levels for price level and zones given
		if(!$price_level_info)
		{
			$service_info_text = is_array($price_level_info) ? print_r($price_level_info,1) : "none";
			if (sfContext::hasInstance())
			{
   			sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "(): price_level_info : " . $service_info_text);
   			sfContext::getInstance()->getLogger()->debug("");
			}
			return 0;
		}

		// assign price
		$addon_amount   = $price_level_info['price'];
		$price_level_id = $price_level_info['price_level_id'];


		/** end of -- compute price_level_id and service add on charge if present **/




		$service_db = new Service_Db();

		// convert weight to kg and lb
		$weight_kg = $weight_lb = 0;
		$weight_type = $service_db->get_weight_type_by_id($details['weight_type_id']);

		switch ($weight_type)
		{
			case 'kg':
				$weight_kg = $details['weight'];
				$weight_lb = ($details['weight'] * (1 / sfConfig::get('app_constant_pounds_in_kg')));
			break;

		  case 'lb':
				$weight_kg = $details['weight'] * sfConfig::get('app_constant_pounds_in_kg');
				$weight_lb = $details['weight'];
			break;

			default:
				return 0;
			break;
		}

		// lb_weight_id kg_weight_id
		$lb_weight_id = $service_db->get_weight_id_by_type('lb');
		$kg_weight_id = $service_db->get_weight_id_by_type('kg');


		// start lb selection weight_price for lb
		$weight_price = $this->compute_weight_price ($weight_lb, $lb_weight_id, $price_level_id);
		if (!$weight_price)
		{
			// weight_price for kg
			$weight_price = $this->compute_weight_price ($weight_kg, $kg_weight_id, $price_level_id);
		}
		// do not exit if weight price is zero this means that they dont have weight surcharge
		// at this level

		if (sfContext::hasInstance())
   		sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "(): weight price : $weight_price");

		return $this->compute_surcharge_discount(
			$weight_price + $addon_amount,
			$courier_id,
			$details
		);
	}


	
	function compute_weight_price ($weight, $weight_id, $price_level_id)
	{
		// start kg selection
		$q = Doctrine_Query::create()
			->select('pt.type as price_type')
			->addSelect('wp.price as price')
			->from('WeightPrice wp')
			->leftJoin('wp.PriceType as pt')
			->where('wp.weight_start <= ?', $weight)
			->addwhere('wp.weight_end >= ?', $weight)
			->addWhere('wp.weight_type_id = ?', $weight_id)
			->addWhere('wp.price_level_id = ?', $price_level_id)
			->fetchArray();
   
		$computed_price = 0;
		if (isset($q[0]))
		{
			if ($q[0]['price_type'] == 'static')
				$computed_price = $q[0]['price'];
			else
				$computed_price = $q[0]['price'] * $weight;
		}

		return $computed_price;
	}
  

	//
	// add all the surcharges and discounts to the price calculated above

  function compute_surcharge_discount ($price, $courier_id, $details)
  {
    if (!$price || !$courier_id)
			return 0;

    // apply surcharges to the final price
    $surcharges = $this->couriers_db->get_courier_surcharges($courier_id);

    // apply gas surcharge as % to overall price
		if ($surcharges['gas'])
	    $price *= (1 + ($surcharges['gas'] / 100));

    // apply discounts to the final price
    $discounts = $this->couriers_db->get_courier_discount($courier_id);
		if ($discounts['discount_percentage'])
	    $price *= (1 - ($discounts['discount_percentage'] / 100));

   	// apply double the price in case of round trip
    if ($details['round_trip'])
      $price *= 2;

		// round price to 2 decimal
		$price = round($price, 2);

		return $price;
  }

	
	// compute the amounts and taxes

	function get_prices_list ($courier_id, $amount)
	{
		if (!$courier_id || !$amount)
		{
			if (sfContext::hasInstance())	
			  sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "(): courier id : $courier_id, amount : $amount");

			return null;
		}

   	$profit_margin = Doctrine::getTable('Courier')->findOneById($courier_id)->getProfitCut();
		$settings_db   = new Settings_Db();
    $settings      = $settings_db->get_settings_info();
  
    $partner_tax   = $amount * ($settings['tax_amount'] / 100);
    $price         = $amount * (1 + $profit_margin / 100);
    $tax           = $price * ($settings['tax_amount'] / 100);
	
		return array($amount, $partner_tax, $price, $tax);
	}


	function get_pricelevel_id_and_service_addon_price($from_zone_id, $to_zone_id, $service_level_type_id, $lat_lng_from)
	{

    $point_locate     = new Point_Locate();
		// 
		$r = Doctrine_query::create()
			->select('sa.price as price')
			->addSelect('sa.from_zone_polygon_id as from_zone_polygon_id')
			->addSelect('zpl.id as id')
			->addSelect('pl.id as price_level_id')
			->from('ZonePriceLevel zpl')
			->innerJoin('zpl.PriceLevel pl')
			->innerJoin('pl.ServiceAddon sa')
			->where('zpl.from_zone_id = ?', $from_zone_id)
			->andWhere('zpl.to_zone_id = ?', $to_zone_id)
			->andWhere('sa.service_level_type_id <= ?', $service_level_type_id)
			->orderBy('sa.service_level_type_id DESC')
			->fetchOne();
			//->fetchOne();

		//print $r;

		//exit;

		/*if ($from_zone_id == 33)
		{
			print_r($q);
			exit;
		}*/

		if (!$r)
			return null;
		
		// create result
		$q = $r->toArray();
		
		// if sa.from_zone_polygon_id present check the zone
		// to make sure we can pick up package from that zone given that time

		if (isset($q['from_zone_polygon_id']))
		{
			$res = Doctrine::getTable('ZonePolygon')->findOneById($q['from_zone_polygon_id']);
			if ($res)
			{
				$point_in_polygon = $point_locate->pointInPolygon($lat_lng_from, $res['pdata']);
				if (!$point_in_polygon)
					return null;
			}
			else
				// zone pdata does not exist
				return null;
		}


		if (sfContext::hasInstance())
		{
    	sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . 
			"(): found ZonePriceLevel match from zone id : $from_zone_id, to zone id : $to_zone_id, price level id : " . $q['price_level_id']);
		}

		if ($q['price_level_id'])
			return array('price' => $q['price'], 'price_level_id' => $q['price_level_id']);
	
 		return null;
	}

}
