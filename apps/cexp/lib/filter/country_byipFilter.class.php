<?php

class country_byipFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $filterChain->execute();

    try
    {
		  // Execute this filter only once
			$stored_visitor = $this->getContext()->getUser()->getAttribute('visitor');
    	$ip_address = getenv('HTTP_X_FORWARDED_FOR');
	   
			// fix the ip address
			if (preg_match('/,/', $ip_address))
				$ip_address = getenv('HTTP_X_REAL_IP');

			/*$this->isFirstCall() ||*/ 
		  if (!is_array($stored_visitor) || ( is_array($stored_visitor) && $stored_visitor['ip'] == '0.0.0.0') || $ip_address != $stored_visitor['ip'])
		  {
				if ($ip_address != '0.0.0.0')
				{
					$geourl = "http://api.ipinfodb.com/v2/ip_query.php?key=".sfConfig::get('app_ipinfodb_api_key')."&ip=$ip_address&timezone=true";

	    		$c = curl_init();
  	  		curl_setopt($c, CURLOPT_URL, $geourl);
    	    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
   	     	$content = trim(curl_exec($c));
        	curl_close($c);
    
        	// Create XML object from Content
        	$values = simplexml_load_string($content);
    
					// only write to log if valid request
        	if ($ip_address && ((string)$values->Status) == 'OK')
					{
						// set agent
						$agent = '';
						$agent = $_SERVER['HTTP_USER_AGENT'];
						if (trim($agent) == "")
							$agent = print_r($_SERVER,1);

        		$visitor = array();
       			$visitor['ip'] = (string)$ip_address;
        		$visitor['status'] = (string)$values->Status;
        		$visitor['country_code'] = (string)$values->CountryCode;
       	 		$visitor['country_name'] = (string)$values->CountryName;
        		$visitor['region_code'] = (string)$values->RegionCode;
        		$visitor['region_name'] = (string)$values->RegionName;
        		$visitor['city'] = (string)$values->City;
        		$visitor['zip_postal_code'] = (string)$values->ZipPostalCode;
        		$visitor['latitude'] = (string)$values->Latitude;
        		$visitor['longitude'] = (string)$values->Longitude;
        		$visitor['timezone_name'] = (string)$values->TimezoneName;
        		$visitor['gmtoffset'] = (string)$values->Gmtoffset;
        		$visitor['isdst'] = (string)$values->Isdst;
        		$visitor['agent'] = $agent;
        		$visitor['updated_ts'] = date( 'Y-m-d H:i:s', time());
    
        		$vs = new Visitor();
        		$vs->synchronizeWithArray($visitor);
        		$vs->save();
    
          	$this->getContext()->getUser()->setAttribute('visitor', $visitor);
		  		}
				}
			}
    }
    catch (Exception $e)
    {
    }

	}
}
