<?php

/**
 * ClientDetail form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientDetailForm extends BaseClientDetailForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'details'  =>  new sfWidgetFormInputText(array(), array('tabindex' => 1, 'style' => 'width: 220px')),
      'name'  =>  new sfWidgetFormInputText(array(), array('tabindex' => 2, 'style' => 'width: 220px')),
      'phone' => new sfWidgetFormInputText(array(), array('tabindex' => 3, 'style' => 'width: 120px')),
      'email'  => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 220px')),
      'contact' => new sfWidgetFormInputText(array(), array('tabindex' => 5, 'style' => 'width: 220px')),
      'how_did_u_hear' => new sfWidgetFormTextArea(array(), array('tabindex' => 6, 'style' => 'width: 420px')),
    ));

    $this->widgetSchema->setLabels(array(
      'details' => 'Company name',
      'name' => 'Your name',
      'phone' => 'Phone number',
      'email' => 'Notification email',
      'contact' => 'Contact details',
      'how_did_u_hear' => 'How did you hear about us?',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'details' => 'Your company name',
      'name' => 'Your personal name',
      'phone' => 'Phone number we could contact you at',
      'email' => 'Notification email',
      'contact' => 'Name of person in charge of shipping',
      'how_did_u_hear' => 'How did you hear about us?',
    ));

    $this->setValidators(array(

      'details' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 40), array(
        'min_length' => 'Name must be at least %min_length% characters.',
        'max_length' => 'name must not exceed %max_length% characters.',
      )),

      'name' => new sfValidatorString(array('min_length' => 2, 'max_length' => 40), array(
        'required'   => 'Name is required',
        'min_length' => 'Name must be at least %min_length% characters.',
        'max_length' => 'name must not exceed %max_length% characters.',
      )),

      'phone' => new sfValidatorPhone(array(), array('required' => 'Phone number required')),

      'email' => new sfValidatorAnd(array(
         new sfValidatorEmail(array(), array('invalid' => 'The email address is invalid.')),
         new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
          'min_length' => 'Email must be at least %min_length% characters long.',
          'max_length' => 'Email length must not exceed %max_length% characters.',
         )),
				 new sfValidatorEmailMx(),
				 new sfValidatorEmailExists(),
      )),

      'contact' => new sfValidatorString(array('min_length' => 2, 'max_length' => 20), array(
        'required'   => 'Name is required',
        'min_length' => 'Contact must be at least %min_length% characters.',
        'max_length' => 'Contact must not exceed %max_length% characters.',
      )),

      'how_did_u_hear' => new sfValidatorString(array('required' => false, 'min_length' => 15, 'max_length' => 300), array(
        'min_length' => 'Message must be at least %min_length% characters.',
        'max_length' => 'Message must not exceed %max_length% characters.',
      )),

		));


    $this->widgetSchema->setNameFormat('detail[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');
		$this->validatorSchema['email']->setMessage('required', 'Email address is required.');
  }
}
