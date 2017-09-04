<?php

// test/bootstrap/Doctrine.php
include(dirname(__FILE__).'/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration( 'cexp', 'test', true);
class myContext extends sfContext
{
  public function initialize(sfApplicationConfiguration $configuration)
  {
  }
}

$frontend_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('cexp', 'test', true));
$i18n_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('cexp', 'test', true));
$cache_context = sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('cexp', 'test', true));


new sfDatabaseManager($configuration);
 
Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');
