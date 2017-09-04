<?php

/**
 * Base project form.
 * 
 * @package    cexp
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfForm
{
  private
		$systen_settings     = null,
		$max_shipping_length = 0,
		$min_shipping_length = 0;


  function __construct ()
  {
		$this->system_settings = sfContext::getInstance()->getUser()->getAttribute('system_settings');
    
		if (!$this->system_settings)
		{
    	$settings_db = new Settings_Db();
    	$this->system_settings = $settings_db->get_settings_info();

			$this->system_settings['max_shipping_length'] =
			max($this->system_settings['package_id_length'], $this->system_settings['payment_id_length']);

    	$this->system_settings['min_shipping_length'] =
			min($this->system_settings['package_id_length'], $this->system_settings['payment_id_length']);

			sfContext::getInstance()->getUser()->setAttribute('system_settings', $this->system_settings);
    }

		parent::__construct();
  }


	public function configure ()
	{
    $this->setWidgets(array(
      'name'  =>  new sfWidgetFormInputText(array(), array('style' => 'width: 220px')),
      'company' => new sfWidgetFormInputText(array(), array('style' => 'width: 220px')),
      'phone' => new sfWidgetFormInputText(array(), array('style' => 'width: 220px')),
      'details' => new sfWidgetFormTextArea(array(), array('style' => 'width: 420px')),
			'price' => new sfWidgetFormInputText(),
			'email'  => new sfWidgetFormInputText(),
			'password' => new sfWidgetFormInputPassword(),
			'remember_me' => new sfWidgetFormInputCheckbox(),
      'shipment_number' => new sfWidgetFormInputText(array(), array('style' => 'width: 220px')),
    ));

    $this->widgetSchema->setLabels(array(
      'name' => 'Your name',
      'company'   => 'Company name',
      'phone' => 'Phone number',
      'details' => 'Contact message',
			'price' => 'Price',
			'email' => 'Login name',
			'password' => 'Password',
			'remember_me' => 'Remember password?',
			'shipment_number' => 'Shipment number',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'name' => 'Your personal name',
      'company' => 'Name of the company you represent',
      'phone' => 'Phone number we could contact you at',
      'details' => 'Details of your message',
			'price' => 'Selected price',
      'email' => 'Email address you use to login',
			'password' => 'Password for logging in',
			'shipment_number' => 'Package or shipment number',
    ));


    $this->setValidators(array(

      'name' => new sfValidatorString(array('min_length' => 2, 'max_length' => 40), array(
        'required'   => 'Name is required',
        'min_length' => 'Name must be at least %min_length% characters.',
        'max_length' => 'name must not exceed %max_length% characters.',
      )),

      'company' => new sfValidatorString(array('min_length' => 2, 'max_length' => 20), array(
        'required'   => 'Comapny name is required',
        'min_length' => 'Company must be at least %min_length% characters.',
        'max_length' => 'Company must not exceed %max_length% characters.',
      )),

      'phone' => new sfValidatorPhone(array(), array('required' => 'Phone number required')),


      'details' => new sfValidatorString(array('min_length' => 30, 'max_length' => 300), array(
        'required'   => 'Contact message is required',
        'min_length' => 'Message must be at least %min_length% characters.',
        'max_length' => 'Message must not exceed %max_length% characters.',
      )),

      'price' => new sfValidatorAnd(array(
        new sfValidatorString(array(
            'min_length' => 1, 'max_length' => 5),
              array(
                'min_length' => 'Price must be at least %min_length% characters.',
                'max_length' => 'Price must not exceed %max_length% characters.',
              ), array('required' => 'The message field is required.')
        ),
        new sfValidatorRegex(array('pattern' => '/\d+\.\d+|\d+/'), array(
            'invalid' => 'price format needs to be 0.00')
      ))),

      'email' => new sfValidatorAnd(array(
         new sfValidatorEmail(array(), array('invalid' => 'The email address is invalid.')),
         new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
          'min_length' => 'Email must be at least %min_length% characters long.',
          'max_length' => 'Email length must not exceed %max_length% characters.',
         )),
      )),

      'password' => new sfValidatorString(array('min_length' => 6, 'max_length' => 30), array(
        'required'   => 'Password is required',
        'min_length' => 'Password must be at least %min_length% characters.',
        'max_length' => 'Password must not exceed %max_length% characters.',
      )),

      'remember_me' => new sfValidatorPass(),

      'shipment_number' => new sfValidatorString(
				array(
				'min_length' => $this->system_settings['min_shipping_length'],
        'max_length' => $this->system_settings['max_shipping_length']),
				array(
        'required'   => 'Shipment or package number is required',
        'min_length' => 'Shipment or package number must be at least %min_length% characters.',
        'max_length' => 'Shipment or package number must not exceed %max_length% characters.',
      )),
    ));


		$this->widgetSchema->setNameFormat('contact[%s]');
    $this->validatorSchema['email']->setMessage('required', 'Email address is required.');
    $this->validatorSchema['price']->setMessage('required', 'Price is required.');

		
		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
	}


  /** 
   *  unset all fields except given parameters 
   * 
   * @param array $fields Array of fields 
   */   

	public function unsetAllExcept($fields = array())  
  {  
    $tmp = array_keys($this->widgetSchema->getFields());  
    foreach(array_diff($tmp, $fields) as $value)
      unset($this[$value]);
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
