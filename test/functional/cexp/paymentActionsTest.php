<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/payment/index')->

  with('request')->begin()->
    isParameter('module', 'payment')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
	  isRedirected()->
    isStatusCode(302)->
		followRedirect()->
  end()
;

$browser->with('response')->
	checkElement('body', '/expected delivery/i')
;

