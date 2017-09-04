<?php

/**
 * Address form base class.
 *
 * @method Address getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'apt_unit'          => new sfWidgetFormInputText(),
      'street_number'     => new sfWidgetFormInputText(),
      'street_name'       => new sfWidgetFormInputText(),
      'street_type'       => new sfWidgetFormInputText(),
      'city'              => new sfWidgetFormInputText(),
      'province_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Province'), 'add_empty' => true)),
      'state_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'add_empty' => true)),
      'province_state_id' => new sfWidgetFormInputText(),
      'country_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'add_empty' => false)),
      'postal_code'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'apt_unit'          => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'street_number'     => new sfValidatorString(array('max_length' => 64)),
      'street_name'       => new sfValidatorString(array('max_length' => 128)),
      'street_type'       => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'city'              => new sfValidatorString(array('max_length' => 255)),
      'province_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Province'), 'required' => false)),
      'state_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'required' => false)),
      'province_state_id' => new sfValidatorInteger(array('required' => false)),
      'country_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Country'))),
      'postal_code'       => new sfValidatorString(array('max_length' => 10)),
    ));

    $this->widgetSchema->setNameFormat('address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Address';
  }

}
