<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/landing_page/about_us')->

  with('request')->begin()->
    isParameter('module', 'landing_page')->
    isParameter('action', 'about_us')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Corporate Head office address/i')->
  end()
;
