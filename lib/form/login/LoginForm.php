<?php

// lib/form/ContactForm.class.php
class LoginForm extends ClientLoginForm
{
  protected $client_info = '';


  public function configure()
  {
		parent::configure();
    unset($this['password_again']);

    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(
			array(), array('style' => 'width: 220px'));

    $this->validatorSchema['email'] = 
      new sfValidatorAnd
        (array(
         new sfValidatorEmail(array(), array('invalid' => 'The email address is invalid.')),
         new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
          'min_length' => 'Email must be at least %min_length% characters long.',
          'max_length' => 'Email length must not exceed %max_length% characters.',
         )),
         new sfValidatorEmailMx(),
      ));

    // implement custom validation logic
    // add a post validator

    $this->validatorSchema->setPostValidator(
		new sfValidatorCallback(array('callback' => array($this, 'checkUsernameAndPassword'))));
    $this->widgetSchema->setNameFormat('login[%s]');


		// set tab orders
    $this->widgetSchema['email']->setAttribute('tabindex', 1);
		$this->widgetSchema['password']->setAttribute('tabindex', 2);

  	foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }


  public function checkUsernameAndPassword($validator, $values)
  {
    $logins_db   = new Logins_Db();
    $this->client_info = $logins_db->check_login($values['email'], $values['password']);
		
    if (!$this->client_info)
    {
      // password is not correct, throw an error
      throw new sfValidatorError($validator, 'Password or username is not valid');
    }

    // password is correct, return the clean values
    return $values;
  }


  public function getClientInfo ()
  {
    return ($this->client_info) ? $this->client_info : array(null, null);
  }
  

  public function addCSRFProtection($secret = null)
  {
    parent::addCSRFProtection($secret);
    if (array_key_exists(self::$CSRFFieldName, $this->getValidatorSchema())) {
      $this->getValidator(self::$CSRFFieldName)->setMessage('csrf_attack',
      'This session has expired. Please return to the home page and try again.');
    }
  }
}


/*
		// start of remember me password email code
    $default_password = $default_username = $default_remember_me = '';


    // set default values for username and password based on cookie
    $rk_object = null;
    $remember_pair = unserialize(base64_decode(sfContext::getInstance()
                      ->getRequest()->getCookie('courier_express')));

    // create remember key object
    if ($remember_pair)
    {
      $rk_object = Doctrine::getTable('LoginRememberKey')
                    ->findOneByLoginIdAndRememberKey($remember_pair['login_id'], $remember_pair['remember_key']);

      $login = null;

      // get login information based on the remember me
      if ($rk_object && $rk_object->login_id)
      {
        // if cookie is ok
        $login = loginDelegate::getInstance()->retrieveById($rk_object->login_id);
      }

      if ($login && $login->id)
      {
        $default_password = '********';
        $default_username = $login->username;
        $default_remember_me = true;
      }
      else
      {
        $default_password = '';
        $default_username = '';
        $default_remember_me = false;
      }
    }
    else
    {
      $default_password = '';
      $default_username = '';
      $default_remember_me = false;
    }
		// end of remember me password email code
    */

