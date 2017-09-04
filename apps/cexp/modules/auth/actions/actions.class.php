<?php

/**
 * auth actions.
 *
 * @package    courierexpress
 * @subpackage auth
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class authActions extends sfActions
{

	public function preExecute ()
	{
		Tools_Lib::checkUnsupportedCountries();
		// shopping cart data
		$this->packages_cart = $this->getUser()->getAttribute('packages_cart');
		$this->clients_db = new Clients_Db();
	}


	public function facebook_login_check ()
	{
		// get cookie
		$fb_lib = new Facebook_Lib();
		$cookie = $fb_lib->get_facebook_cookie();

		// depending on cookie if account does not exist create one
		$fb_uid = @$cookie['uid'];

		// if has not logged in continue
		if (!$fb_uid || !$cookie['access_token'])
			return;

			// get client info from facebook
		$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' .
					    $cookie['access_token']));
	
		// check if account exists with this facebook id
		$client_info = $this->clients_db->info_by_facebook_uid_or_email($fb_uid, $user->email);

		//print_r($user);
		//exit;
		// if not create one
		if (!$client_info)
		{
			// create client type
			$cd = new ClientDetail();
			$cd['details'] = $user->name;
			$cd['name'] = $user->first_name . ' ' . $user->last_name;
			$cd['contact'] = $user->first_name . ' ' . $user->last_name;
			$cd['email'] = $user->email;
			$cd['facebook_uid'] = $user->facebook_uid;
			$cd['how_did_u_hear'] = 'Facebook';
			$cd['registration_date'] = date("Y-m-d H:i:s",time());
			
			print_r($client_info);
			exit;
			$c = new Client();
			$c->ClientDetail = $cd;
			$c->save();
			$client_info['client_id'] = $c['id'];
		}

		if (!isset($client_info['email']))
			$client_info['email'] = $user->email;

		// then use fb id to do facebook login
		// partner can not login using fb
    $logins_db  = new Logins_Db();
		$login_type = 'client'; 
    $logins_db->execute_facebook_login($client_info['email'], $fb_uid, $login_type, $client_info['client_id']);

    if ($this->in_process == 1)
      Tools_Lib::redirectSecurePage('checkout', 'index');

    $this->redirect('main_page/index');
	}


  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new LoginForm();
    $this->form->addCSRFProtection($this->form->getCSRFToken());
    $this->in_process = $request->getParameter('in_process');

		// find out if this is a facebook login
		$this->facebook_login_check();
		// end of facebook login

    if ($request->isMethod('post'))
    {
	    if (sfContext::hasInstance())
	      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): Login post action');

      $this->form->bind($request->getParameter('login'));
      if ($this->form->isValid())
      {
			  // get all client types in the system
			  $login_param = $this->form->getValues();

 		  	$username    = $login_param['email'];
    		$password    = $login_param['password'];
				list($login_type, $client_id) = $this->form->getClientInfo();

		    if (sfContext::hasInstance())
		      sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '(): Login with username : ' . $username . 'pass : ' . $password);

				// if client id set and we can proceed to log in
    		if ($login_type)
    		{
          $logins_db = new Logins_Db();
          $logins_db->execute_login($username, $password, $login_type, $client_id);

          // where to redirect for each login type after login success
          $redirect_url = 'main_page/index';
          switch ($login_type)
          {
            case 'partner':
							// if has pending packages redirect to pending
							$couriers_db = new Couriers_Db();
              if ($couriers_db->has_packages_to_deliver($client_id))
                $redirect_url = 'partner/pending';
							else
              	$redirect_url = $login_type.'/index';
						break;

            case 'admin':
              $redirect_url = $login_type.'/index';
						break;

						case 'client':
							// if has pending packages redirect to pending
							if ($this->clients_db->has_packages_pending($client_id))
								$redirect_url = 'client/pending';
            break;
          }

	

          if ($this->in_process == 1)
            Tools_Lib::redirectSecurePage('checkout', 'index');

        	$this->redirect($redirect_url);
				}
      }
    }
  }


  public function executeLogout(sfWebRequest $request)
  {
		$logins_db = new Logins_Db();
		$this->fb_uid = $this->getUser()->getAttribute('fb_uid');
		$redirect_url = ($this->fb_uid != null ? 'auth/index' : null);
		$logins_db->execute_logout($this, $redirect_url);
  }


	public function executeRecovery(sfWebRequest $request)
	{
    $this->form = new ForgotPasswordForm();

		if ($request->isMethod('post'))
		{
      $this->form->bind($request->getParameter('recovery'));
      if ($this->form->isValid())
      {
			  $logins_db   = new Logins_Db();
        $form_values = $this->form->getValues();
			  $client_id   = $logins_db->get_clientid_from_login($form_values['email']);

				// resending password
			  if ($client_id > 0)
			  {
			    $accounts_db = new Accounts_Db();
				  $accounts_db->send_account_details ($this, $client_id, sfConfig::get('app_messages_password_recovery'));
				  $this->message = 'Password has been sent to ' . $form_values['email'];
		  	}
				else
					$this->message = 'Login email not found';
		  }
    }
	}
}
