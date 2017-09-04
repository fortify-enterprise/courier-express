<?php

class session_timeoutFilter extends sfFilter
{
  public function execute ($filterChain)
  {
    // ...
    // Execute next filter
    $filterChain->execute();


    $response = sfContext::getInstance()->getResponse();
    $options = sfContext::getInstance()->getUser()->getOptions();
    // ...
    // Set the browser variable in the tempalte
    $response->setContent(str_ireplace('%session_timeout%', $options['timeout'], $response->getContent()));
  }
}

