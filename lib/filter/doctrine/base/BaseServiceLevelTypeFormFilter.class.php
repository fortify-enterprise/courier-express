<?php

/**
 * ServiceLevelType filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseServiceLevelTypeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hours' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'type'  => new sfValidatorPass(array('required' => false)),
      'hours' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('service_level_type_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ServiceLevelType';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'type'  => 'Text',
      'hours' => 'Text',
    );
  }
}
