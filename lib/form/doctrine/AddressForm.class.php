<?php

/**
 * Address form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AddressForm extends BaseAddressForm
{
  public function configure()
  {
    //$this->updateObject();
    $address = $this->getObject();
    $address_db  = new Address_Db();
    $default_id  = 1;
    $state_province_names = array();

    // default
    if ($address['id'])
    {
			list($state_province_names, $default_id) = $address_db->get_state_province_names
			($address['Country']['name'], $address['Province']['id'], $address['State']['id']);
    }

   
    $this->setWidgets(array(
      'apt_unit'  =>  new sfWidgetFormInputText(array(), array('tabindex' => 1, 'style' => 'width: 120px')),
      'street_number' => new sfWidgetFormInputText(array(), array('tabindex' => 2, 'style' => 'width: 120px; margin-right: 10px')),
      'street_name' => new sfWidgetFormInputText(array(), array('tabindex' => 3, 'style' => 'width: 120px')),
      'city' => new sfWidgetFormInputText(array(), array('tabindex' => 4, 'style' => 'width: 250px')),
      'postal_code' => new sfWidgetFormInputText(array(), array('tabindex' => 5, 'style' => 'width: 120px')),
      'province_state_id' => new sfWidgetFormSelect(array('choices' => $state_province_names,
			'default' => $default_id), array('tabindex' => 6, 'style' => 'width: 250px')),
    ));

    $this->widgetSchema->setLabels(array(
      'apt_unit' => 'Apartnment unit',
      'street_number' => 'Str. number',
      'street_name' => 'Street name',
      'city' => 'City',
      'postal_code' => 'Postal/Zip code',
      'province_state_id' => 'Province or State',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'apt_unit' => 'Apartment unit, ex: 1235',
      'street_number' => 'Street number, ex: 850',
      'street_name' => 'Name of the street, ex: Laurel St.',
      'city' => 'Name of the city, ex: Vancouver',
      'postal_code' => "Postal code, ex: V4J3B2 or zip code: 98012, <br />You can identify Canadian postal code for an address:<br />
			http://www.canadapost.ca/cpotools/apps/fpc/personal/findByCity?execution=e3s1",
      'province_state_id' => 'Province or State',
    ));

    $this->setValidators(array(

      'apt_unit' => new sfValidatorAnd(array(
					new sfValidatorString(array('min_length' => 0, 'max_length' => 10), array(
        		'min_length' => 'Apartment must be at least %min_length% characters.',
        		'max_length' => 'Apartment must not exceed %max_length% characters.',
      	)),
				new sfValidatorApartment(),
			)),

      'street_number' => new sfValidatorString(array('min_length' => 1, 'max_length' => 10), array(
        'required'   => 'Number is required',
        'min_length' => 'Street number must be at least %min_length% characters.',
        'max_length' => 'Street number must not exceed %max_length% characters.',
      )),

      'street_name' => new sfValidatorString(array('min_length' => 2, 'max_length' => 50), array(
        'required'   => 'Name is required',
        'min_length' => 'Street name must be at least %min_length% characters.',
        'max_length' => 'Street name must not exceed %max_length% characters.',
      )),

      'city' => new sfValidatorString(array('min_length' => 3, 'max_length' => 50), array(
        'required'   => 'City is required',
        'min_length' => 'City must be at least %min_length% characters.',
        'max_length' => 'City must not exceed %max_length% characters.',
      )),

      'postal_code' => new sfValidatorAnd(array(
				new sfValidatorString(array('min_length' => 5, 'max_length' => 10), array(
        	'required'   => 'Postal/Zip code is required',
        	'min_length' => 'Postal code must be at least %min_length% characters.',
        	'max_length' => 'Postal code must not exceed %max_length% characters.',
      	)),
				new sfValidatorPostalZip(),
			)),

      'province_state_id' => new sfValidatorPass(),
		));


    //$this->widgetSchema->setNameFormat('address[%s]');
    $this->validatorSchema['apt_unit']->setOption('required', false);

		// allow more relations

    $this->embedRelation('Country');

    foreach ($this->validatorSchema['Country']->getFields() as $key => $value)
      $this->validatorSchema['Country'][$key]->setOption('required', false);
    $this->validatorSchema['Country']['id']->setOption('required', true);


    $this->embedRelation('Province');

    foreach ($this->validatorSchema['Province']->getFields() as $key => $value)
      $this->validatorSchema['Province'][$key]->setOption('required', false);
    $this->validatorSchema['Province']['id']->setOption('required', true);

    $this->embedRelation('State');
    foreach ($this->validatorSchema['State']->getFields() as $key => $value)
      $this->validatorSchema['State'][$key]->setOption('required', false);
    $this->validatorSchema['State']['id']->setOption('required', true);

  
    $this->validatorSchema['Province']['id'] = new sfValidatorPass();
    $this->validatorSchema['State']['id']    = new sfValidatorPass();


    $this->widgetSchema->setFormFormatterName('Vertical');
		$this->enableLocalCSRFProtection();
  }


  protected function doSave($con = null)
  {
    $this->updateObject();
    $country     = $this->embeddedForms['Country']->getObject();
    $country_id  = $country->getId() ?  $country->getId() : 1;
    $country     = Doctrine::getTable('Country')->findOneById($country_id);
    $province_state_id = $this['province_state_id']->getValue();

    switch (strtolower($country['name']))
    {
      case 'canada':
        $province_id = $province_state_id;
        $state_id = 1;
      break;

      case 'united states':
        $state_id = $province_state_id;
        $province_id = 1;
      break;
    }


    $state = Doctrine::getTable('State')->findOneById($state_id);
    $this->getObject()->setState($state);
    $province = Doctrine::getTable('Province')->findOneById($province_id);
    $this->getObject()->setProvince($province);

    // get province and state objects
    $this->getObject()->setCountry($country);

    $this->getObject()->save();
  }
}
