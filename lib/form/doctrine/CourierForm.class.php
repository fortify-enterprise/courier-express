<?php

/**
 * Courier form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CourierForm extends BaseCourierForm
{
  public function configure()
  {
   	parent::configure();



    $this->embedRelation('Client');


	 	unset($this->widgetSchema['Client']['ClientType']);

		foreach ($this->widgetSchema['Client']['Address']['Country']->getFields() as $key => $value)
		{
			if ($key != 'id')
	 			unset($this->widgetSchema['Client']['Address']['Country'][$key]);
		}


		foreach ($this->widgetSchema['Client']['Address']['Province']->getFields() as $key => $value)
		{
			if ($key != 'id')
	 			unset($this->widgetSchema['Client']['Address']['Province'][$key]);
		}

		foreach ($this->widgetSchema['Client']['Address']['State']->getFields() as $key => $value)
		{
			if ($key != 'id')
	 			unset($this->widgetSchema['Client']['Address']['State'][$key]);
		}

		
    foreach ($this->validatorSchema['Client']['Address']['Country']->getFields() as $key => $value)
      $this->validatorSchema['Client']['Address']['Country'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['Client']['ClientType']->getFields() as $key => $value)
      $this->validatorSchema['Client']['ClientType'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['Client']['Address']['Province']->getFields() as $key => $value)
      $this->validatorSchema['Client']['Address']['Province'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['Client']['Address']['State']->getFields() as $key => $value)
      $this->validatorSchema['Client']['Address']['State'][$key]->setOption('required', false);

    $this->validatorSchema['Client']['address_id']->setOption('required', false);
    $this->validatorSchema['Client']['detail_id']->setOption('required', false);

    $this->validatorSchema['Client']['Address']['Country']['name']->setOption('required', false);
    $this->validatorSchema['Client']['Address']['Province']['province_territory']->setOption('required', false);

    $this->widgetSchema['Client']['type_id'] = new sfWidgetFormInputHidden(array(), array('value'=>'2'));


    $this->widgetSchema['Client']['ClientDetail']['name']->setAttribute('tabindex', 1);
    $this->widgetSchema['Client']['Address']['apt_unit']->setAttribute('tabindex', 2);
    $this->widgetSchema['Client']['Address']['street_number']->setAttribute('tabindex', 3);
    $this->widgetSchema['Client']['Address']['street_name']->setAttribute('tabindex', 4);
    $this->widgetSchema['Client']['Address']['Country']['id']->setAttribute('tabindex', 5);
    $this->widgetSchema['Client']['Address']['postal_code']->setAttribute('tabindex', 6);
    $this->widgetSchema['Client']['Address']['city']->setAttribute('tabindex', 7);
    $this->widgetSchema['Client']['Address']['Province']['id']->setAttribute('tabindex', 8);
    $this->widgetSchema['Client']['ClientDetail']['contact']->setAttribute('tabindex', 9);
    $this->widgetSchema['Client']['ClientDetail']['phone']->setAttribute('tabindex', 10);
    $this->widgetSchema['Client']['ClientDetail']['email']->setAttribute('tabindex', 11);
    $this->widgetSchema['Client']['ClientLogin']['email']->setAttribute('tabindex', 12);
    $this->widgetSchema['Client']['ClientLogin']['password']->setAttribute('tabindex', 13);
    $this->widgetSchema['Client']['ClientLogin']['password_again']->setAttribute('tabindex', 14);
    $this->widgetSchema['Client']['ClientDetail']['how_did_u_hear']->setAttribute('tabindex', 15);

    $this->enableLocalCSRFProtection();
  }


  public function doSave($con = null)
  {
    $this->updateObject();

    $address = $this->embeddedForms['Address']->getObject();

    // province id
    $province_id = $address->getProvince()->getId() ?  $address->getProvince()->getId() : 1;

    // state id
    $state_id = $address->getState()->getId() ?  $address->getState()->getId() : 1;

    $address->setProvince(Doctrine::getTable('Province')->find($province_id));
    $address->setState(Doctrine::getTable('State')->find($state_id));
    $address->setCountry(Doctrine::getTable('Country')->find($address->getCountry()->getId()));
    $address->save();

    $client_detail = $this->embeddedForms['ClientDetail']->getObject();
    $client_detail->save();
    $client_login  = $this->embeddedForms['ClientLogin']->getObject();
    $client_login->setPasswordHash(Tools_Lib::getHash($client_login->getPassword()));
    $client_login->save();

    $client_type = Doctrine::getTable('ClientType')->findOneByType('client');

    $this->getObject()->setAddress($address);
    $this->getObject()->setClientDetail($client_detail);
    $this->getObject()->setClientLogin($client_login);
    $this->getObject()->setClientType($client_type);

    $this->getObject()->save();

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }
}
