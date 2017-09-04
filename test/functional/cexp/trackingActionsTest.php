<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/tracking/index')->

  with('request')->begin()->
    isParameter('module', 'tracking')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
		info('Tracking button on the page')->
    isStatusCode(200)->
    checkElement('legend', '/Tracking information/i')->
  end()
;

$browser->get('/tracking/index')->click('Submit', array('tracking' => array('shipment_number' => 'dummy')))->
	with('response')->begin()->
		info('No packages on page')->
		isStatusCode(200)->
		checkElement('body', '/Shipment or package number must be at least 10 characters/i')->
end()
;
