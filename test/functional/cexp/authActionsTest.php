<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/auth/index')->

  with('request')->begin()->
    isParameter('module', 'auth')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
	 	info('Login name appears on auth page')->
    isStatusCode(200)->
    checkElement('body', '/Login email address/i')->
  end()
;

// new tests to see how parameter change affects page

	$browser->
  	get("/auth/index")->

  	with('request')->begin()->
    	isParameter('module', 'auth')->
    	isParameter('action', 'index')->
  end()->

  with('response')->begin()->
	 	info("$type field appears on login page")->
    isStatusCode(200)->
    checkElement('legend', "/Enter login information/i")->
    checkElement('body', "/Forgot password/i")->
  end()
	;


// tests for incorrect password attempt

	$browser->
  	get("/auth/index")->
  	click('Login', array('login[email]' => 'aktush@gmail.com', 'login[password]' => '5hkjl34hkj'))->
  	with('request')->begin()->
 	end()->

  with('response')->begin()->
	 	info("attempt to login using incorrect password")->
    isStatusCode(200)->
    checkElement('body', "/Enter login information/i")->
  end()
	;
