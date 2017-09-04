<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());


$browser->
  get('/services/get_delivered_by/date/2009-12-20/time/09.00AM/service/1')->

  with('request')->begin()->
		info('Get delivered by')->
    isParameter('module', 'services')->
    isParameter('action', 'get_delivered_by')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"delivered_by":"11:00AM - Mon 21 Dec 2009"}')->
	end()->

	// check city and province from postal

  get('/services/get_city_province_from_postal/postal/92120/country_id/2')->

  with('request')->begin()->
		info('Get city province from postal')->
    isParameter('module', 'services')->
    isParameter('action', 'get_city_province_from_postal')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"city":"San diego","province":"5"}')->
  end()->


  get('/services/get_city_province_from_postal/postal/v6p3v7/country_id/1')->

  with('request')->begin()->
		info('Get city province from postal')->
    isParameter('module', 'services')->
    isParameter('action', 'get_city_province_from_postal')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"city":"Vancouver","province":"10"}')->
  end()->


  get('/services/get_city_province_from_postal/postal/98012/country_id/2')->

  with('request')->begin()->
		info('Get city province from postal')->
    isParameter('module', 'services')->
    isParameter('action', 'get_city_province_from_postal')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"city":"Bothell","province":"48"}')->
  end()->



  get('/services/get_city_province_from_postal/postal/10286/country_id/2')->

  with('request')->begin()->
		info('Get city province from postal')->
    isParameter('module', 'services')->
    isParameter('action', 'get_city_province_from_postal')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"city":"Bank of new york","province":"33"}')->
  end()->



  get('/services/get_city_province_from_postal/postal/M4B1B4')->

  with('request')->begin()->
		info('Get city province from postal')->
    isParameter('module', 'services')->
    isParameter('action', 'get_city_province_from_postal')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '{"city":"East York","province":"6"}')->
  end()


;
