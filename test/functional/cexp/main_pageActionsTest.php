<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/main_page/index')->

  with('request')->begin()->
    isParameter('module', 'main_page')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/expected delivery/i')->
  end()
;


$browser->get('/main_page/index')->click('Client register')->
  with('response')->begin()->
    info('register page')->
    isStatusCode(200)->
    checkElement('legend', '/contact information/i')->
  end()
;
