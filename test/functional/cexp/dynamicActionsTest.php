<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$b = new sfTestBrowser();
$b->get('dynamic/get_states_provinces_for_country/country_id/1');
$request  = $b->getRequest();
$context  = $b->getContext();
$response = $b->getResponse();
 
// Get access to the lime_test methods via the test() method
$b->test()->is($request->getParameter('country_id'), 1, 'Country id is 1');
$b->test()->is($response->getStatuscode(), 200, 'Checking status code 200');
$b->test()->is($response->getHttpHeader('content-type'), 'text/html; charset=utf-8', 'Content type text/html; charset=utf-8');
$b->test()->like($response->getContent(), '/{.*}/', 'Contains json strings');
