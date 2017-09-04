<?php

class language_detectFilter extends sfFilter
{
  public function execute($filterChain)
  {
    // Nothing to do before the action
  	$languages = $this->getContext()->getRequest()->getLanguages();
		$this->getContext()->getUser()->setCulture($languages[0]);
    $filterChain->execute();
  }
}
