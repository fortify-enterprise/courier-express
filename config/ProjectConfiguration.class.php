<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfSmartyPlugin');
//    $this->enablePlugins('prestaPaypalPlugin');
//    $this->enablePlugins('zsI18nExtractTranslatePlugin');
//    $this->enablePlugins('sfOptimizerPlugin');
    $this->enablePlugins('sfWebBrowserPlugin');


		// set the widget formatter
		sfWidgetFormSchema::setDefaultFormFormatterName('Horizontal');
    $this->enablePlugins('sfFormExtraPlugin');
  }

  public function configureDoctrine(Doctrine_Manager $manager)
  {
    // if we use memcached
    $servers = array(
        'host' => 'localhost',
        'port' => 11211,
        'persistent' => true
    );

    $cacheDriver = new Doctrine_Cache_Memcache(array(
        'servers' => $servers,
        'compression' => false
    ));

    //enable Doctrine cache
    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);


    // 5 min cache time
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 60);

		

    /*
    // if we use APC
    if (ini_get("apc.enabled")==1)
    {
     if (sfApplicationConfiguration::getEnvironment() != "dev")
        $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, new Doctrine_Cache_Apc());
        $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, new Doctrine_Cache_Apc());
      else apc_clear_cache("user");
    }
    */
  }


}
