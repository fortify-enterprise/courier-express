<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/register/client')->

  with('request')->begin()->
    isParameter('module', 'register')->
    isParameter('action', 'client')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/contact information/i')->
    checkElement('body', '/client login details/i')->
    checkElement('body', '/sender contact details/i')->
  end()
;
