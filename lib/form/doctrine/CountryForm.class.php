<?php

/**
 * Country form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CountryForm extends BaseCountryForm
{

  public function configure()
  {
		// intialize country names
		$address_db = new Address_Db();
		$country_names = $address_db->get_countries_list(true);

    $this->setWidgets(array(
      'id' => new sfWidgetFormSelect(array('choices' => $country_names, 'default' => '1'),
          array('style' => 'width: 250px')),
      'name' => new sfWidgetFormInputText(array(), array('style' => 'width: 120px')),
      'code2' => new sfWidgetFormInputText(array(), array('style' => 'width: 30px')),
      'code3' => new sfWidgetFormInputText(array(), array('style' => 'width: 30px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Country name',
      'name' => 'Country name',
      'code2' => 'Country Code(2)',
      'code3' => 'Country Code (3)',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Name of country: Canada',
      'name' => 'Country name',
      'code2' => 'Country code 2: CA - for Canada',
      'code3' => 'Country code 3: CAN - for Canada',
    ));

    $this->setValidators(array(

      'id' => new sfValidatorChoice(array('choices' => array_keys($country_names))),

      'name' => new sfValidatorString(array('min_length' => 0, 'max_length' => 40), array(
        'required'   => 'Country name is required',
        'min_length' => 'Country name must be at least %min_length% characters.',
        'max_length' => 'Country name must not exceed %max_length% characters.',
      )),

      'code2' => new sfValidatorString(array('min_length' => 2, 'max_length' => 2), array(
        'required'   => 'Code 2 is required',
        'min_length' => 'Code 2 must be at least %min_length% characters.',
        'max_length' => 'Code 2 must not exceed %max_length% characters.',
      )),

      'code3' => new sfValidatorString(array('min_length' => 3, 'max_length' => 3), array(
        'required'   => 'Code 3 is required',
        'min_length' => 'Code 3 must be at least %min_length% characters.',
        'max_length' => 'Code 3 code must not exceed %max_length% characters.',
      )),

    ));


    //$this->widgetSchema->setNameFormat('contact[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

  }


  protected function doSave($con = null)
  {
  }
}
