<?php

/**
 * Address form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GenericAddressForm extends AddressForm
{
  public function configure()
  {
    parent::configure();

    foreach ($this->validatorSchema['Country']->getFields() as $key => $value)
     $this->validatorSchema['Country'][$key]->setOption('required', false);
  
    $this->validatorSchema['Country']['id']->setOption('required', true);


    $this->widgetSchema['apt_unit']->setAttribute('tabindex', 1);
    $this->widgetSchema['street_number']->setAttribute('tabindex', 2);
    $this->widgetSchema['street_number']->setAttribute('style', 'width: 120px');
    $this->widgetSchema['street_name']->setAttribute('tabindex', 3);
    $this->widgetSchema['Country']['id']->setAttribute('tabindex', 4);
    $this->widgetSchema['postal_code']->setAttribute('tabindex', 5);
    $this->widgetSchema['city']->setAttribute('tabindex', 6);

    $this->widgetSchema->setNameFormat('address[%s]');

    $this->widgetSchema->setFormFormatterName('Horizontal');
    $this->widgetSchema['Country']->setFormFormatterName('Horizontal');
		$this->enableLocalCSRFProtection();

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }
}
