<?php

/**
 * Package form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class PackageForm extends BasePackageForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'package_code'  =>  new sfWidgetFormInputText(array(), array('tabindex' => 1, 'style' => 'width: 120px')),
      'amount' => new sfWidgetFormInputText(array(), array('tabindex' => 2, 'style' => 'width: 120px')),
      'current_profit_cut' => new sfWidgetFormInputText(array(), array('tabindex' => 3, 'style'=>'width: 120px')),

      'courier_id' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 120px')),
      'client_id' => new sfWidgetFormInputText(array(), array('tabindex' => 5, 'style' => 'width: 120px')),
      'from_address_id' => new sfWidgetFormInputText(array(), array('tabindex' => 6, 'style' => 'width: 120px')),
      'to_address_id' => new sfWidgetFormInputText(array(), array('tabindex' => 7, 'style' => 'width: 120px')),
      'status_id' => new sfWidgetFormInputText(array(), array('tabindex' => 8, 'style' => 'width: 120px')),
      'detail_id' => new sfWidgetFormInputText(array(), array('tabindex' => 9, 'style' => 'width: 120px')),
    ));

    $this->widgetSchema->setLabels(array(
      'package_code' => 'Package code',
      'amount' => 'Amount',
      'current_profit_cut' => 'Profit cut',
      'courier_id' => 'Courier Id',
      'client_id' => 'Client id',
      'from_address_id' => 'From address id',
      'to_address_id' => 'To Address id',
      'status_id' => 'Status id',
      'detail_id' => 'Detail id',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'package_code' => 'Package code',
      'amount' => 'Amount',
      'current_profit_cut' => 'Profit cut',
      'courier_id' => 'Courier Id',
      'client_id' => 'Client id',
      'from_address_id' => 'From address id',
      'to_address_id' => 'To Address id',
      'status_id' => 'Status id',
      'detail_id' => 'Detail id',
    ));


    $this->setValidators(array(

      'package_code' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'amount' => new sfValidatorString(array('min_length' => 1, 'max_length' => 10), array(
        'required'   => 'Amount is required',
        'min_length' => 'Amount must be at least %min_length% characters.',
        'max_length' => 'Amount must not exceed %max_length% characters.',
      )),

      'current_profit_cut' => new sfValidatorString(array('min_length' => 2, 'max_length' => 50), array(
        'required'   => 'Profit cut is required',
        'min_length' => 'Street name must be at least %min_length% characters.',
        'max_length' => 'Street name must not exceed %max_length% characters.',
      )),

      'courier_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'client_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'from_address_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'to_address_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'status_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),

      'detail_id' => new sfValidatorString(array('required' => false, 'min_length' => 0, 'max_length' => 10), array(
        'min_length' => 'Package code be at least %min_length% characters.',
        'max_length' => 'Package code not exceed %max_length% characters.',
      )),



    ));

    $client = sfContext::getInstance()->getUser()->getAttribute('client');
    $address_id = 0;
    if ($client)
      $address_id = $client['address_id'];


    $this->embedRelation('PackageStatus');
    $this->embedRelation('PackageDetail');


    $sender_address = new AddressForm();
    $sender_address->setDefault('address_id', $address_id);
    $this->embedForm('sender', $sender_address);

    $recep_address = new AddressForm();
    $this->embedForm('recep', $recep_address);



    $this->widgetSchema->setFormFormatterName('Vertical');
    $this->enableLocalCSRFProtection();
		$this->widgetSchema->setNameFormat('package[%s]');


		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}

  }
}
