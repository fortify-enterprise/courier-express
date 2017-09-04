<?php

/**
 * exception actions.
 *
 * @package    cexp
 * @subpackage exception
 * @author     Courier Express
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class exceptionActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }


  public function executeError(sfWebRequest $request)
  {
  }


  public function executeUnavailable(sfWebRequest $request)
  {
  }

  public function executeCountry_not_supported(sfWebRequest $request)
  {
		$this->visitor = $this->getUser()->getAttribute('visitor');
  }
}
