<?php

/**
 * PackageDetail form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class PackageDetailForm extends BasePackageDetailForm
{
  protected static $round_trip   = array('no', 'yes');
  protected static $ready_times  = array
  (
    '08.00AM' => '08.00 AM',
    '08.30AM' => '08.30 AM',
    '09.00AM' => '09.00 AM',
    '09.30AM' => '09.30 AM',
    '10.00AM' => '10.00 AM',
    '10.30AM' => '10.30 AM',
    '11.00AM' => '11.00 AM',
    '11.30AM' => '11.30 AM',
    '12.00PM' => '12.00 PM',
    '12.30PM' => '12.30 PM',
    '13.00PM' => '01.00 PM',
    '13.30PM' => '01.30 PM',
    '14.00PM' => '02.00 PM',
    '14.30PM' => '02.30 PM',
    '15.00PM' => '03.00 PM',
    '15.30PM' => '03.30 PM',
    '16.00PM' => '04.00 PM',
  );


  public function configure()
  {
		parent::configure();
    
    $weight_types = array();
    $res = Doctrine::getTable('WeightType')->findAll()->toArray();
    foreach ($res as $key => $value)
      $weight_types[$value['id']] = $value['type'];
    

    $num_pieces = array();
    for($i = 1; $i < 7; $i++)
      $num_pieces[$i] = $i;

    $this->setWidgets(array(
      'ready_time'  =>  new sfWidgetFormSelect(array('choices' => self::$ready_times), array('tabindex' => 1, 'style' => 'width: 120px; margin-right: 10px')),
      'ready_date' => new sfWidgetFormInputText(array(), array('tabindex' => 2, 'style' => 'width: 120px; margin-right: 10px')),
      'num_pieces'  => new sfWidgetFormSelect(array('choices' => $num_pieces), array('style' => 'width: 120px')),
      'weight' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px; margin-right: 10px')),
      'weight_type_id' => new sfWidgetFormSelect(array('choices' => $weight_types), array('style' => 'width: 120px; margin-right: 10px')),
      'reference' => new sfWidgetFormInputText(array(), array('tabindex' => 5, 'style' => 'width: 120px; margin-right: 10px')),
      'round_trip' =>  new sfWidgetFormSelect(array('choices' => self::$round_trip), array('style' => 'width: 120px')),
      'instructions' => new sfWidgetFormTextarea(array(), array('tabindex' => 4, 'style' => 'width: 580px')),
      'sender_phone' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px')),
      'sender_contact' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px; margin-right: 10px')),
      'phone' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px; margin-right: 10px')),
      'contact' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px; margin-right: 10px')),
      'signed_by' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px')),
      'last_updated' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 220px')),
    ));

    $this->widgetSchema->setLabels(array(
      'ready_time' => 'Ready time',
      'ready_date' => 'Ready date',
      'num_pieces' => 'Number of pieces',
      'weight' => 'Weight',
      'weight_type_id' => 'Kg or Pound?',
      'reference' => 'Reference',
      'round_trip' => 'Is round trip?',
      'instructions' => 'Special instructions',
      'sender_phone' => 'Sender phone',
      'sender_contact' => 'Sender contact',
      'phone' => 'Phone',
      'contact' => 'Recepient',
      'signed_by' => 'Signed by',
      'last_updated' => 'Last updated',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'ready_time' => 'Time at which the package will be ready for pickup',
      'ready_date' => 'Ready date at which the package will be ready for pickup',
      'num_pieces' => 'Number of pieces package is composed of',
      'weight' => 'Type 1 if the package is less than 1 lb or kg',
      'weight_type_id' => 'What type of weight to use: Kilograms or Pounds?',
      'reference' => 'Someone who will be picking up the package',
      'round_trip' => 'Is round trip required?',
      'instructions' => 'Special instructions',
      'sender_phone' => 'Phone at which the sender can be reached',
      'sender_contact' => 'Sender contact',
      'phone' => 'Phone at which recepient can be reached',
      'contact' => 'Recepient contact',
      'signed_by' => 'Signed by',
      'last_updated' => 'Last updated',
    ));


    $this->setValidators(array(

      'ready_time' => new sfValidatorChoice(array('choices' => array_keys(self::$ready_times))),

      'ready_date' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 20), array(
        'min_length' => 'Must be at least %min_length% characters.',
        'max_length' => 'Must not exceed %max_length% characters.',
      )),

      'num_pieces' => new sfValidatorChoice(array('choices' => array_keys($num_pieces))),

      'weight' => new sfValidatorAnd(array(
					new sfValidatorString(array('min_length' => 1, 'max_length' => 5), array(
        		'required'   => 'Weight is required',
        		'min_length' => 'Must be at least %min_length% characters.',
       		  'max_length' => 'Must not exceed %max_length% characters.',
     		)),

				new sfValidatorWeight(),
		 	)),

      'weight_type_id' => new sfValidatorChoice(array('choices' => array_keys($weight_types))),

      'reference' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 45), array(
        'required'   => 'Reference is required',
        'min_length' => 'Must be at least %min_length% characters.',
        'max_length' => 'Must not exceed %max_length% characters.',
      )),

      'round_trip' => new sfValidatorChoice(array('choices' => array_keys(self::$round_trip))),

      'instructions' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 350), array(
        'min_length' => 'Must be at least %min_length% characters.',
        'max_length' => 'Must not exceed %max_length% characters.',
      )),

      'sender_phone' => new sfValidatorPhone(array('required' => false)),

      'sender_contact' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 150), array(
        'min_length' => 'Contact be at least %min_length% characters.',
        'max_length' => 'Contact must not exceed %max_length% characters.',
      )),

      'phone' => new sfValidatorPhone(array('required' => false)),

      'contact' => new sfValidatorString(array('required' => false, 'min_length' => 2, 'max_length' => 150), array(
        'min_length' => 'Contact must be at least %min_length% characters.',
        'max_length' => 'Contact must not exceed %max_length% characters.',
      )),

      'signed_by' => new sfValidatorString(array('min_length' => 2, 'max_length' => 20), array(
        'required'   => 'Name is required',
        'min_length' => 'Must be at least %min_length% characters.',
        'max_length' => 'Must not exceed %max_length% characters.',
      )),

      'last_updated' => new sfValidatorString(array('min_length' => 7, 'max_length' => 30), array(
        'required'   => 'Timestamp is required',
        'min_length' => 'Must be at least %min_length% characters.',
        'max_length' => 'Must not exceed %max_length% characters.',
      )),

    ));

    $this->embedRelation('PackageType');
    $this->embedRelation('ServiceLevelType');
    $this->embedRelation('DeliveryType');

    $this->widgetSchema->setNameFormat('package_detail[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

		// trim all input values
		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}

		// add a post validator for ready date and time
		// package ready date can not be in the past
    $this->mergePostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'checkReadyDateTime')))
    );

  }

	
	//
	// check if date and time are in the future
	public function checkReadyDateTime($validator, $values)
  {
		$service_db = new Service_Db();
    if ($service_db->is_datetime_in_the_past($values['ready_date'], $values['ready_time']))
    {
			$error = new sfValidatorError($validator, 'Set date and time to the future');
      // password is not correct, throw an error
      throw new sfValidatorErrorSchema($validator, array('ready_date' => $error));
    }
 
    // date and time are correct, return the clean values
    return $values;
  }

}
