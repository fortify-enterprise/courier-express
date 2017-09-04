<?php

/**
 * register actions.
 *
 * @package    courierexpress
 * @subpackage register
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class registerActions extends sfActions
{
	public function preExecute ()
	{
		Tools_Lib::checkUnsupportedCountries();

    if ($this->getRequest()->isSecure())
    {
      $in_process = "";
      $in_process_param = $this->getRequestParameter('in_process');
      if ($in_process_param)
        $in_process = 'in_process=' . $in_process_param;
      Tools_Lib::redirectNonSecurePage('register', 'client', $in_process);
    }
	}


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(sfWebRequest $request)
  {
    $packages_cart = $this->getUser()->getAttribute('packages_cart');

    $in_process = $request->getParameter('in_process');
    if (isset($packages_cart) && $packages_cart)
      $this->redirect('register/client?in_process='.$in_process);
    else
      $this->redirect('main_page/index');
  }


  // register client

  public function executeClient (sfWebRequest $request)
  {
    $this->in_process = $request->getParameter('in_process');

    $this->form = new ClientForm();
    $this->form->addCSRFProtection($this->form->getCSRFToken());


    if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter('client')))
    {
      // set the client in session
      $client = $this->form->getObject()->toArray();
      $this->getUser()->setAttribute('client', $client);

      // send email with account details
      $accounts_db = new Accounts_Db();
      $accounts_db->send_account_details ($this, $client['id']);

      // login the newly created client
      $logins_db = new Logins_Db();
      $logins_db->execute_login($client['ClientLogin']['email'], $client['ClientLogin']['password']);


      // redirect to success https payment

      if ($this->in_process == 1)
        Tools_Lib::redirectSecurePage('checkout', 'index');
      else
        $this->redirect('form/thankyou?template=register');
    }
    /*else
    {
      foreach ($this->form->getErrorSchema() as $error)
      print $error . '<br />';
    }*/
  }


  public function executeComplete (sfWebRequest $request)
  {
  }
}
