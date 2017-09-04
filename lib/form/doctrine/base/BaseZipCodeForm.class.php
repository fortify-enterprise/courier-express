<?php

/**
 * ZipCode form base class.
 *
 * @method ZipCode getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseZipCodeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'zip_code'                 => new sfWidgetFormInputText(),
      'lat'                      => new sfWidgetFormInputText(),
      'lon'                      => new sfWidgetFormInputText(),
      'city'                     => new sfWidgetFormInputText(),
      'state_prefix'             => new sfWidgetFormInputText(),
      'county'                   => new sfWidgetFormInputText(),
      'z_type'                   => new sfWidgetFormInputText(),
      'xaxis'                    => new sfWidgetFormInputText(),
      'yaxis'                    => new sfWidgetFormInputText(),
      'zaxis'                    => new sfWidgetFormInputText(),
      'z_primary'                => new sfWidgetFormInputText(),
      'worldregion'              => new sfWidgetFormInputText(),
      'country'                  => new sfWidgetFormInputText(),
      'locationtext'             => new sfWidgetFormInputText(),
      'location'                 => new sfWidgetFormInputText(),
      'population'               => new sfWidgetFormInputText(),
      'housingunits'             => new sfWidgetFormInputText(),
      'income'                   => new sfWidgetFormInputText(),
      'landarea'                 => new sfWidgetFormInputText(),
      'waterarea'                => new sfWidgetFormInputText(),
      'decommisioned'            => new sfWidgetFormInputText(),
      'militaryrestrictioncodes' => new sfWidgetFormInputText(),
      'decommisionedplace'       => new sfWidgetFormInputText(),
      'id'                       => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'zip_code'                 => new sfValidatorString(array('max_length' => 5)),
      'lat'                      => new sfValidatorNumber(),
      'lon'                      => new sfValidatorNumber(),
      'city'                     => new sfValidatorString(array('max_length' => 100)),
      'state_prefix'             => new sfValidatorString(array('max_length' => 100)),
      'county'                   => new sfValidatorString(array('max_length' => 100)),
      'z_type'                   => new sfValidatorString(array('max_length' => 100)),
      'xaxis'                    => new sfValidatorNumber(),
      'yaxis'                    => new sfValidatorNumber(),
      'zaxis'                    => new sfValidatorNumber(),
      'z_primary'                => new sfValidatorString(array('max_length' => 100)),
      'worldregion'              => new sfValidatorString(array('max_length' => 100)),
      'country'                  => new sfValidatorString(array('max_length' => 100)),
      'locationtext'             => new sfValidatorString(array('max_length' => 255)),
      'location'                 => new sfValidatorString(array('max_length' => 255)),
      'population'               => new sfValidatorString(array('max_length' => 255)),
      'housingunits'             => new sfValidatorInteger(),
      'income'                   => new sfValidatorInteger(),
      'landarea'                 => new sfValidatorString(array('max_length' => 255)),
      'waterarea'                => new sfValidatorString(array('max_length' => 255)),
      'decommisioned'            => new sfValidatorString(array('max_length' => 100)),
      'militaryrestrictioncodes' => new sfValidatorString(array('max_length' => 255)),
      'decommisionedplace'       => new sfValidatorString(array('max_length' => 255)),
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zip_code[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZipCode';
  }

}
