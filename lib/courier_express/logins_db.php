<?php

class Logins_Db extends Base_Lib
{
	function check_login ($username, $password)
	{
		if (!$username || $username == "" || !$password || $password == "")
			return null;

    $q = Doctrine_Query::create()
         ->select("1 as pass_match")
         ->addSelect('c.id as client_id')
         ->addSelect('ct.type as client_type')
         ->from('Client c')
         ->innerJoin('c.ClientLogin cl')
         ->innerJoin('c.ClientType ct')
         ->where('cl.email = ?', $username)
         ->addWhere('cl.password_hash = ?', Tools_Lib::getHash($password))
         ->fetchArray();

    $pass_match = $client_id  = $type = '';
    foreach($q as $row)
    {
      $pass_match = $row['pass_match'];
      $client_id  = $row['client_id'];
      $type       = $row['client_type'];
    }
 
		return ($pass_match) ? array($type, $client_id) : null;
	}


	function get_clientid_from_login ($login_name)
	{
		if (!$login_name || $login_name == "")
			return -1;

    $q = Doctrine_Query::create()
         ->select('c.id as id')
         ->from('Client c')
         ->leftJoin('c.ClientLogin cl')
         ->where('cl.email = ?', $login_name)
				 ->fetchOne();

		return (isset($q['id']) ? $q['id'] : -1);
	}


	function notification_email_exists ($login_name)
	{
		if (!$login_name)
			return false;

    $q = Doctrine_Query::create()
         ->select('cli.id')
         ->from('Client cli')
         ->leftJoin('cli.ClientDetail cd')
         ->where('cd.email = ?', $login_name)
				 ->fetchOne();

		return isset($q['id']) ? $q['id'] : false;
	}


	// reset password for the client

	function reset_password ($client_id)
	{
		if (!$client_id || $client_id <= 0)
			return -1;

		$new_password = $this->generate_password();
		$c = Doctrine::getTable('Client')->find($client_id);
		$c->ClientLogin['password']      = $new_password;
		$c->ClientLogin['password_hash'] = Tools_Lib::getHash($new_password);
		$c->save();

		return $c->ClientLogin['password'];
	}


	function get_courierid_from_login ($login_name)
	{
		if (!$login_name || trim($login_name) == "")
			return 0;

    $q = Doctrine_Query::create()
         ->select('c.id')
         ->from('Client cli')
         ->leftJoin('cli.ClientLogin cl')
         ->leftJoin('cli.Courier c')
         ->leftJoin('cli.ClientType ct')
         ->where('cl.email = ?', $login_name)
         ->andWhere('ct.type = ?', 'courier');

    $result = $q->execute();
    $courier_id  = '';
    foreach($result as $row)
      $courier_id = $row['id'];
 
		return $courier_id;
	}

  
  // given email proceed with login

  function execute_login ($username, $password, $login_type = null, $client_id = null)
	{
		// check logintype and client id

    if (!$login_type || !$client_id)
    {
      // get client type and client id
      list($login_type, $client_id) = $this->check_login($username, $password);
    }

    if ($login_type && $client_id)
    {
    	// add correct credential to the session
    	$user = sfContext::getInstance()->getUser();
      $user->addCredential($login_type);

      $user->setAttribute('username', $username);
      $user->setAttribute('login_type', $login_type);
      $user->setAttribute('client_id', $client_id);
      
      if ($user->hasCredential('partner'))
			{
				$courier = Doctrine::getTable('Courier')->findOneByClientId($client_id);
				if ($courier)
        	$user->setAttribute('courier_id', $courier->getId());
				else
				{
					$couriers_db = new Couriers_Db();
					$couriers_db->create_partner($client_id);
				}
			}
      // authenticate user
      $user->setAuthenticated(true);
    }
	}


  // facebook login

  function execute_facebook_login ($username, $fb_uid, $login_type, $client_id)
	{
		// check logintype and client id
    if ($login_type && $client_id)
    {
    	// add correct credential to the session
    	$user = sfContext::getInstance()->getUser();
      $user->addCredential($login_type);

      $user->setAttribute('username', $username);
      $user->setAttribute('login_type', $login_type);
      $user->setAttribute('client_id', $client_id);
      $user->setAttribute('fb_uid', $fb_uid);
  
      // authenticate user
      $user->setAuthenticated(true);
    }
	}



	// logout to redirect_to

	function execute_logout (sfActions $actions, $redirect_to)
	{
		$fb_uid = $actions->getUser()->getAttribute('fb_uid');

    // clear authenticated sessions
    $actions->getUser()->clearCredentials();
    $actions->getUser()->setAuthenticated(false);

    // clear all authenticated users
    $actions->getUser()->getAttributeHolder()->clear();

		if ($fb_uid)
			$redirect_to = 'main_page/index';

    // go to the login page continue for fb
		if ($redirect_to)
    	$actions->redirect($redirect_to);
	}


	// secure password generation method
  // this is the password itself and not the hash of it

  function generate_password ()
  {
    $strength   = 4;
    $length     = 10;
    $vowels     = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';

    if ($strength & 1)
      $consonants .= 'BDGHJLMNPQRSTVWXZ';

    if ($strength & 2)
      $vowels .= "AEUY";

    if ($strength & 4)
      $consonants .= '23456789';

    if ($strength & 8)
      $consonants .= '@#$%';


    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++)
    {
      if ($alt == 1)
      {
        $password .= $consonants[(rand() % strlen($consonants))]; $alt = 0;
      }
      else
      {
        $password .= $vowels[(rand() % strlen($vowels))];
        $alt = 1;
      }
    }
    return $password;
  }
}
