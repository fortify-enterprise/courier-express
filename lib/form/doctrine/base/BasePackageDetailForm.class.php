<?php

/**
 * PackageDetail form base class.
 *
 * @method PackageDetail getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePackageDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'ready_time'            => new sfWidgetFormInputText(),
      'ready_date'            => new sfWidgetFormInputText(),
      'num_pieces'            => new sfWidgetFormInputText(),
      'weight'                => new sfWidgetFormInputText(),
      'weight_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'), 'add_empty' => false)),
      'reference'             => new sfWidgetFormInputText(),
      'package_type_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageType'), 'add_empty' => false)),
      'service_level_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevelType'), 'add_empty' => false)),
      'delivery_type_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DeliveryType'), 'add_empty' => false)),
      'round_trip'            => new sfWidgetFormInputText(),
      'instructions'          => new sfWidgetFormTextarea(),
      'sender_phone'          => new sfWidgetFormInputText(),
      'sender_contact'        => new sfWidgetFormInputText(),
      'phone'                 => new sfWidgetFormInputText(),
      'contact'               => new sfWidgetFormInputText(),
      'signed_by'             => new sfWidgetFormInputText(),
      'last_updated'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ready_time'            => new sfValidatorString(array('max_length' => 80)),
      'ready_date'            => new sfValidatorString(array('max_length' => 80)),
      'num_pieces'            => new sfValidatorInteger(),
      'weight'                => new sfValidatorNumber(),
      'weight_type_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'))),
      'reference'             => new sfValidatorString(array('max_length' => 80, 'required' => false)),
      'package_type_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PackageType'))),
      'service_level_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevelType'))),
      'delivery_type_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DeliveryType'))),
      'round_trip'            => new sfValidatorInteger(),
      'instructions'          => new sfValidatorString(array('required' => false)),
      'sender_phone'          => new sfValidatorString(array('max_length' => 16)),
      'sender_contact'        => new sfValidatorString(array('max_length' => 128)),
      'phone'                 => new sfValidatorString(array('max_length' => 16)),
      'contact'               => new sfValidatorString(array('max_length' => 128)),
      'signed_by'             => new sfValidatorString(array('max_length' => 80, 'required' => false)),
      'last_updated'          => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('package_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PackageDetail';
  }

}
