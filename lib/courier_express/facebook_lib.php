<?php

class Facebook_Lib extends Base_Lib
{
	function get_facebook_cookie()
	{
		// define app ip and secret
		$app_id = sfConfig::get('app_facebook_id');
		$application_secret = sfConfig::get('app_facebook_secret');

  	$args = array();
  	parse_str(trim(@$_COOKIE['fbs_' . $app_id], '\\"'), $args);
		if (sizeof($args) == 0)
			return null;

  	ksort($args);
  	$payload = '';
  	foreach ($args as $key => $value)
		{
    	if ($key != 'sig') {
      	$payload .= $key . '=' . $value;
    	}
  	}
  
		if (md5($payload . $application_secret) != $args['sig']) {
    	return null;
  	}
  	return $args;
	}
}

