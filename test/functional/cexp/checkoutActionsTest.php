<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/checkout/index')->

  with('request')->begin()->
    isParameter('module', 'checkout')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isRedirected()->
    isStatusCode(302)->
    followRedirect()->
  end()
;

$browser->with('response')->
  checkElement('input[id="main_page"][type="hidden"][value="1"]');
;

