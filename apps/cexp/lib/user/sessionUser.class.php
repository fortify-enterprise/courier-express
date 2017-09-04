<?php


// apps/frontend/lib/myUser.class.php
class sessionUser extends sfBasicSecurityUser
{
  public function signIn($login_info)
  {
    // login object and is login success
    $login = null;
    $rk_object = null;
    $is_login_success = false;


    // get customer subdomain domain
    $subdomain_name = domainDelegate::getInstance()->getUserCustomerSubdomainName();

    // get customer from table based on subdomain name
    $customer = Doctrine::getTable('Customer')->getByUniqueName($subdomain_name);



    // make sure we know which customer it is
    if ($customer && $customer->id)
    {

      // if we have cookie
      $remember_pair = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('courier_express')));

      // create remember key object
      if ($remember_pair && $login_info['remember_me'])
        $rk_object = Doctrine::getTable('LoginRememberKey')->findOneByLoginIdAndRememberKey($remember_pair['login_id'], $remember_pair['remember_key']);
      else
      {
        if ($remember_pair['login_id'])
        {
          $old_key = Doctrine::getTable('LoginRememberKey')->findOneByLoginId($remember_pair['login_id']);
          if ($old_key)
            $old_key->delete();
        }
      }


      // check the cookie against the database
      if ($rk_object && $rk_object->login_id)
      {
        // if cookie is ok
        $login = loginDelegate::getInstance()->retrieveById($rk_object->login_id);
      }
      else
      {
        // check the login table with username and password hash
        $login = loginDelegate::getInstance()->retrieveByCredentials($subdomain_name, $login_info['username'], md5($login_info['password']));

        // save key in table
        if ($login && $login->id && $login_info['remember_me'])
        {
          $rk = new MobyLoginRememberKey();
          $rk['remember_key'] = $this->generate_random_key();
          $rk['login_id']     = $login->id;

          $rk->replace();

          // save the key to the cookie
          $remember_pair = base64_encode(serialize(array('remember_key' => $rk->getRememberKey(), 'login_id' => $rk->getLoginId())));
          setcookie('courier_express', $remember_pair, time()+3600*24*15, '/');
        }
        else
        {
          $res = Doctrine::getTable('Login')->findOneByUsername($login_info['username']);

          if (!$res || !$res->username)
            $this->signin_errors['username'] = 'username does not exist';
          $this->signin_errors['password'] = 'password is not correct';
        }
      }


      // at this point we have login object by either cookie or straight login method
      if ($login && $login->id)
      {
        // set session variables after login success
        $this->setAuthenticated(true);
        $this->setAttribute(userConstants::USER_USERNAME, $login_info['username']);
        $this->setAttribute(userConstants::USER_LOGIN_ID, $login->id);
        $this->setAttribute(userConstants::USER_SUBSCRIBER_ID, $login->subscriber_id);

        // add access id to user session
        $access_id = Doctrine::getTable('MasterUser')->getAccessID($login->id);
        userDelegate::getInstance()->setUserAccessId($access_id);

        // tag an entry to database audit succesfull login
        $login_info['subscriber_id'] = $login->subscriber_id;
        $login_info['login_id']      = $login->id;
        loginAuditDelegate::getInstance()->auditValidLogin($login_info, false);

        // set successful login
        $is_login_success = true;
      }
      // end of login success section
    }


    // if not succesfull login
    if (!$is_login_success)
      LoginAudit::getInstance()->auditInvalidLogin($login_info, false);


    return $is_login_success;
  }


  public function generate_random_key()
  {
    return hash('whirlpool', $this->generate_random_string());
  }


  public function generate_random_string ($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
  {
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};

    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
      // Grab a random character from our list
      $r = $chars{rand(0, $chars_length)};

      // Make sure the same two characters don't appear next to each other
      if ($r != $string{$i - 1}) $string .=  $r;
    }

    // Return the string
    return $string;
  }


  public function getSigninErrors()
  {
    return $this->signin_errors;
  }


  public function signOut()
  {
    $this->setAuthenticated(false);
    sfContext::getInstance()->getResponse()->setCookie('courier_express', '', time() - 3600, '/');
  }


  /**
   * Sets authentication for user.
   *
   * @param  bool $authenticated
   */

  public function setAuthenticated($authenticated)
  {
    parent::setAuthenticated($authenticated);
  }
}
