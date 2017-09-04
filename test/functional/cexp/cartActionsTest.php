<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/cart/index')->

  with('request')->begin()->
    isParameter('module', 'cart')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()->
  with('response')->begin()->
    checkElement('body', '/No packages in your shopping cart/')->
	end()
;
