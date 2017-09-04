<?php

/**
 * Visitor form base class.
 *
 * @method Visitor getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVisitorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ip'              => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'country_code'    => new sfWidgetFormInputText(),
      'country_name'    => new sfWidgetFormInputText(),
      'region_code'     => new sfWidgetFormInputText(),
      'region_name'     => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(),
      'zip_postal_code' => new sfWidgetFormInputText(),
      'latitude'        => new sfWidgetFormInputText(),
      'longitude'       => new sfWidgetFormInputText(),
      'timezone_name'   => new sfWidgetFormInputText(),
      'gmtoffset'       => new sfWidgetFormInputText(),
      'isdst'           => new sfWidgetFormInputText(),
      'agent'           => new sfWidgetFormTextarea(),
      'updated_ts'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ip'              => new sfValidatorString(array('max_length' => 20)),
      'status'          => new sfValidatorString(array('max_length' => 10)),
      'country_code'    => new sfValidatorString(array('max_length' => 10)),
      'country_name'    => new sfValidatorString(array('max_length' => 64)),
      'region_code'     => new sfValidatorString(array('max_length' => 20)),
      'region_name'     => new sfValidatorString(array('max_length' => 64)),
      'city'            => new sfValidatorString(array('max_length' => 128)),
      'zip_postal_code' => new sfValidatorString(array('max_length' => 16)),
      'latitude'        => new sfValidatorString(array('max_length' => 10)),
      'longitude'       => new sfValidatorString(array('max_length' => 10)),
      'timezone_name'   => new sfValidatorString(array('max_length' => 64)),
      'gmtoffset'       => new sfValidatorString(array('max_length' => 16)),
      'isdst'           => new sfValidatorInteger(),
      'agent'           => new sfValidatorString(array('required' => false)),
      'updated_ts'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('visitor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Visitor';
  }

}
