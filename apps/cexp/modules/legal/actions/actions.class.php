<?php

/**
 * legal actions.
 *
 * @package    cexp
 * @subpackage legal
 * @author     Courier Express
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class legalActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(sfWebRequest $request)
  {
  }


  public function executePrivacy(sfWebRequest $request)
  {
  }


  public function executeRefund(sfWebRequest $request)
  {
		//
		// add refund percentage for refunded orders

		$this->refund_percentage = sfConfig::get('app_package_refund_percentage');
  }
}
