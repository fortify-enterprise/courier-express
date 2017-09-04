<?php

class Geo_Locate extends Base_Lib
{

	//
	// address could be either array or address_id
	
  public function latLngByAddress ($address /* or Address id*/)
  {
		$fetched_address_id = 0;
		if (is_array($address))
		{
    	$address_db = new Address_Db();
    	$fetched_address_id = $address_db->insert_new_address ($address, true);
    }
		else
			$fetched_address_id = $address;
		
		return $this->latLngByAddressId($fetched_address_id);
  }


	// uses session cache, if requested once kept in the user session
	private function get_latlng_by_address_id ($address_id)
	{
    if (!$address_id)
      return null;

    $latlng = array();

    // cache in session
    if (sfContext::hasInstance())
    {
      $system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
			if (isset($system_settings['Locate']) && isset($system_settings['Locate'][$address_id]))
				$latlng = $system_settings['Locate'][$address_id];
			else
			{
				$res = Doctrine::getTable('Locate')->findOneByAddressId($address_id);
				if ($res)
				{
					$latlng = $system_settings['Locate'][$address_id] = $res->toArray();
					sfContext::getInstance()->getUser()->setAttribute('system_settings', $system_settings);
     		}
				else
					$latlng = Doctrine::getTable('Locate')->findOneByAddressId($address_id);
			}
    }
    else
			$latlng = Doctrine::getTable('Locate')->findOneByAddressId($address_id);
  
    return $latlng;
	}


  public function latLngByAddressId ($address_id, $use_cache = 1)
	{
		// check for negative address
		
    if (!$address_id || $address_id < 0)
			return -1;

		// see if its in the cache

    if ($use_cache)
		{
			$res = $this->get_latlng_by_address_id ($address_id);

			if ($res)
			{
				if ($res['lat'] != "" && $res['lng'] != "")
				{
    			if (sfContext::hasInstance())
		  			sfContext::getInstance()->getLogger()->debug
						(__FUNCTION__ . '(): returning cached lng lat : ' . $res['lng'] .
						' ' . $res['lat'] . ' address_id: ' . $address_id);

					return $res['lng'] . ' ' . $res['lat'];
				}
			}
		}

		// ...
		// prepare address

		$address_db  = new Address_Db();
		$address_str = $address_db->get_obj_text_address($address_id);

		if ($address_str == -1)
			return $address_str;

		// replace with + keep this line
		$address_str = str_replace(' ', '+', $address_str);

		$latlng = $this->google_locate($address_str);
		//if (!$latlng)
		//	$latlng = $this->yahoo_locate($address_str);

		// write to locate cache table
		
		if ($latlng)
		{
    	$l = new Locate();
			$l['address_id'] = $address_id;
			$l['lat'] = $latlng->lat;
			$l['lng'] = $latlng->lng;
			$l->replace();

    	if (sfContext::hasInstance())
				sfContext::getInstance()->getLogger()->debug
				(__FUNCTION__ . '(), new locate id : ' . $l['id'] . ' for address_id : ' . $address_id);
		}

		// Print out all of the XML Object
		return $latlng->lng . ' ' . $latlng->lat;
	}


	function google_locate ($address_str)
	{

		$api_key = sfConfig::get('app_google_maps_api_key');
		$geourl  = sfConfig::get('app_google_maps_url') . "$address_str&sensor=true&key=".$api_key;

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $geourl);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($c));
		curl_close($c);

		// Create JSON object from Content

    $jsonObj = json_decode($content);
		if (!$jsonObj)
			return null;

		$latlng  = $jsonObj->results[0]->geometry->location;

		// log locate
    if (sfContext::hasInstance())
			sfContext::getInstance()->getLogger()->debug
			(__FUNCTION__ . '(), locate for : ' . $address_str);

		// log locate
    if (sfContext::hasInstance())
			sfContext::getInstance()->getLogger()->debug
			(__FUNCTION__ . '(), locate coordinates lat lng: ' . $latlng->lat . ' ' . $latlng->lng);



		return $latlng;
	}


	function yahoo_locate ($address_str)
	{
		$api_key = sfConfig::get('app_yahoo_maps_api_key');
		$geourl  = sfConfig::get('app_yahoo_maps_url') . "$address_str&appid=" . $api_key;

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $geourl);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($c));
		curl_close($c);

		// Create JSON object from Content
		
    $kml_object   = simplexml_load_string($content);
		if (!$kml_object)
			return null;

		$lat = $kml_object->ResultSet[0]->Result->latitude;
		$lng = $kml_object->ResultSet[0]->Result->longitude;
		return $lat . ' ' . $lng;
	}




	//
	// get polygon points from kml

  function get_polygon_from_kml ($kml_data)
  {
    $kml_object   = simplexml_load_string($kml_data);

    $points_array = (array)$kml_object->Document->Placemark->LineString->coordinates;
		if (!$points_array)
			$points_array = (array)$kml_object->Document->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;

		if (!$points_array)
			return -1;

    $points_str   = $points_array[0];

    // work with points
    $points = explode("\n", trim($points_str));

    $from = array("/,0$/", "/,/", "/0(\s)$/", "/\s0.*$/");
    $to   = array("", " ", "", "");

    foreach ($points as &$point)
      $point = preg_replace($from, $to, $point);

    return $points;
  }

}


?>
