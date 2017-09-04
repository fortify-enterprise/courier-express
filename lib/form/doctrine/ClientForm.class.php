<?php

/**
 * Client form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class ClientForm extends BaseClientForm
{
  public function configure()
  {
		$this->embedRelation('Address');
		$this->embedRelation('ClientType');
		$this->embedRelation('ClientDetail');
		$this->embedRelation('ClientLogin');


		foreach ($this->validatorSchema['Address']['Country']->getFields() as $key => $value)
			$this->validatorSchema['Address']['Country'][$key]->setOption('required', false);

		foreach ($this->validatorSchema['Address']['Province']->getFields() as $key => $value)
			$this->validatorSchema['Address']['Province'][$key]->setOption('required', false);

		foreach ($this->validatorSchema['Address']['State']->getFields() as $key => $value)
			$this->validatorSchema['Address']['State'][$key]->setOption('required', false);

		$this->validatorSchema['address_id']->setOption('required', false);
		$this->validatorSchema['detail_id']->setOption('required', false);
		$this->validatorSchema['Address']['Country']['name']->setOption('required', false);
		$this->validatorSchema['Address']['Province']['province_territory']->setOption('required', false);

		$this->widgetSchema['type_id'] = new sfWidgetFormInputHidden(array(), array('value'=>'1'));


		$this->widgetSchema['ClientDetail']['name']->setAttribute('tabindex', 1);
		$this->widgetSchema['Address']['apt_unit']->setAttribute('tabindex', 2);
		$this->widgetSchema['Address']['street_number']->setAttribute('tabindex', 3);
		$this->widgetSchema['Address']['street_name']->setAttribute('tabindex', 4);
		$this->widgetSchema['Address']['Country']['id']->setAttribute('tabindex', 5);
		$this->widgetSchema['Address']['postal_code']->setAttribute('tabindex', 6);
		$this->widgetSchema['Address']['city']->setAttribute('tabindex', 7);
		$this->widgetSchema['Address']['Province']['id']->setAttribute('tabindex', 8);
		$this->widgetSchema['ClientDetail']['contact']->setAttribute('tabindex', 9);
		$this->widgetSchema['ClientDetail']['phone']->setAttribute('tabindex', 10);
		$this->widgetSchema['ClientDetail']['email']->setAttribute('tabindex', 11);
		$this->widgetSchema['ClientLogin']['email']->setAttribute('tabindex', 12);
		$this->widgetSchema['ClientLogin']['password']->setAttribute('tabindex', 13);
		$this->widgetSchema['ClientLogin']['password_again']->setAttribute('tabindex', 14);
		$this->widgetSchema['ClientDetail']['how_did_u_hear']->setAttribute('tabindex', 15);
		
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

		$client = new Client();

		$client->setAddress($address);
		$client->setClientDetail($client_detail);
		$client->setClientLogin($client_login);
		$client->setClientType($client_type);

		$client->save();
  }
}
