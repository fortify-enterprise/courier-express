<?php

/**
 * ClientLogin form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientLoginForm extends BaseClientLoginForm
{
  public function configure()
  {
    // intialize country names
    $this->setWidgets(array(
      'email'    => new sfWidgetFormInputText(array(), array('style' => 'width: 220px')),
      'password'       => new sfWidgetFormInputPassword(array(), array('style' => 'width: 120px')),
      'password_again' => new sfWidgetFormInputPassword(array(), array('style' => 'width: 120px')),
    ));

    $this->widgetSchema->setLabels(array(
      'email'    => 'Login email address',
      'password'       => 'Password',
      'password_again' => 'Repeat password',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'email' => 'Login email address',
      'password' => 'Password to access the system',
      'password_again' => 'Password confirm',
    ));

    $this->setValidators(array(
      'email' => new sfValidatorAnd(array(
         new sfValidatorEmail(array(), array('invalid' => 'The email address is invalid.')),
         new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
          'min_length' => 'Email must be at least %min_length% characters long.',
          'max_length' => 'Email length must not exceed %max_length% characters.',
         )),
				 new sfValidatorEmailMx(),
				 new sfValidatorUsernameExists(),
      )),

      'password' => new sfValidatorString(array('min_length' => 6, 'max_length' => 30), array(
        'required'   => 'Password is required',
        'min_length' => 'Password must be at least %min_length% characters.',
        'max_length' => 'Password must not exceed %max_length% characters.',
      )),

      'password_again' => new sfValidatorString(array('min_length' => 6, 'max_length' => 30), array(
        'required'   => 'Password confirm is required',
        'min_length' => 'Password must be at least %min_length% characters.',
        'max_length' => 'Password must not exceed %max_length% characters.',
      )),
    ));


    // implement custom validation logic
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare('password_again', '==', 'password',
      array('throw_global_error' => false), array('invalid' => 'The passwords must be equal')),
    ))
		);


    $this->widgetSchema->setFormFormatterName('Vertical');
		$this->validatorSchema['email']->setMessage('required', 'Email address is required.');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }
}
