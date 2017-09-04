<?php

class environmentFilter extends sfFilter
{
  public function execute ($filterChain)
  {
    $response = sfContext::getInstance()->getResponse();

    // Execute next filter
    $filterChain->execute();

    $index_file = 'index.php';

    $environment = sfConfig::get('sf_environment');
    switch($environment)
    {
      case 'prod':
      break;
      default:
        $index_file = 'cexp_' . $environment . '.php';
    }

    // ...
    // Set the browser variable in the tempalte
    $response->setContent(str_ireplace('%environment%', $index_file, $response->getContent()));
    // ...

  }
}

