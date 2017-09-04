<?php

/**
 * Surcharge filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSurchargeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'courier_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => true)),
      'surcharge_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SurchargeType'), 'add_empty' => true)),
      'amt_limit'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'courier_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Courier'), 'column' => 'id')),
      'surcharge_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SurchargeType'), 'column' => 'id')),
      'amt_limit'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'amount'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('surcharge_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Surcharge';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'courier_id'        => 'ForeignKey',
      'surcharge_type_id' => 'ForeignKey',
      'amt_limit'         => 'Number',
      'amount'            => 'Number',
    );
  }
}
