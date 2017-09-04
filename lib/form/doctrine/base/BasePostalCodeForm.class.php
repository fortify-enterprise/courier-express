<?php

/**
 * PostalCode form base class.
 *
 * @method PostalCode getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePostalCodeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'postal_code'   => new sfWidgetFormInputText(),
      'city'          => new sfWidgetFormInputText(),
      'province'      => new sfWidgetFormInputText(),
      'province_code' => new sfWidgetFormInputText(),
      'city_type'     => new sfWidgetFormInputText(),
      'latitude'      => new sfWidgetFormInputText(),
      'longitude'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'postal_code'   => new sfValidatorString(array('max_length' => 7)),
      'city'          => new sfValidatorString(array('max_length' => 255)),
      'province'      => new sfValidatorString(array('max_length' => 40)),
      'province_code' => new sfValidatorString(array('max_length' => 2)),
      'city_type'     => new sfValidatorString(array('max_length' => 1)),
      'latitude'      => new sfValidatorNumber(),
      'longitude'     => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('postal_code[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PostalCode';
  }

}
