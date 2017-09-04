<?php

/**
 * PackageDetail filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePackageDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ready_time'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ready_date'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'num_pieces'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'weight'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'weight_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'), 'add_empty' => true)),
      'reference'             => new sfWidgetFormFilterInput(),
      'package_type_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageType'), 'add_empty' => true)),
      'service_level_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevelType'), 'add_empty' => true)),
      'delivery_type_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DeliveryType'), 'add_empty' => true)),
      'round_trip'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'instructions'          => new sfWidgetFormFilterInput(),
      'sender_phone'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sender_contact'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'contact'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'signed_by'             => new sfWidgetFormFilterInput(),
      'last_updated'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'ready_time'            => new sfValidatorPass(array('required' => false)),
      'ready_date'            => new sfValidatorPass(array('required' => false)),
      'num_pieces'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'weight'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'weight_type_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('WeightType'), 'column' => 'id')),
      'reference'             => new sfValidatorPass(array('required' => false)),
      'package_type_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PackageType'), 'column' => 'id')),
      'service_level_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ServiceLevelType'), 'column' => 'id')),
      'delivery_type_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DeliveryType'), 'column' => 'id')),
      'round_trip'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'instructions'          => new sfValidatorPass(array('required' => false)),
      'sender_phone'          => new sfValidatorPass(array('required' => false)),
      'sender_contact'        => new sfValidatorPass(array('required' => false)),
      'phone'                 => new sfValidatorPass(array('required' => false)),
      'contact'               => new sfValidatorPass(array('required' => false)),
      'signed_by'             => new sfValidatorPass(array('required' => false)),
      'last_updated'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('package_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PackageDetail';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'ready_time'            => 'Text',
      'ready_date'            => 'Text',
      'num_pieces'            => 'Number',
      'weight'                => 'Number',
      'weight_type_id'        => 'ForeignKey',
      'reference'             => 'Text',
      'package_type_id'       => 'ForeignKey',
      'service_level_type_id' => 'ForeignKey',
      'delivery_type_id'      => 'ForeignKey',
      'round_trip'            => 'Number',
      'instructions'          => 'Text',
      'sender_phone'          => 'Text',
      'sender_contact'        => 'Text',
      'phone'                 => 'Text',
      'contact'               => 'Text',
      'signed_by'             => 'Text',
      'last_updated'          => 'Date',
    );
  }
}
