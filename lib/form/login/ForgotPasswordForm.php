<?php

// lib/form/ContactForm.class.php
class ForgotPasswordForm extends ClientLoginForm
{
  protected $client_info = '';


  public function configure()
  {
		parent::configure();
    unset($this['password_again']);
    unset($this['password']);

    $this->validatorSchema['email'] = 
      new sfValidatorAnd
        (array(
         new sfValidatorEmail(array(), array(
				 'invalid' => 'The email address is invalid.')),
         new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
          'min_length' => 'Email must be at least %min_length% characters long.',
          'max_length' => 'Email length must not exceed %max_length% characters.',
         )),
      ));
			
		$this->widgetSchema->setNameFormat('recovery[%s]');
		$this->validatorSchema['email']->setMessage('required', 'Email address is required.');
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
