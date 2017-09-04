<?php

/**
 * WeightPrice filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseWeightPriceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'weight_start'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'weight_end'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price_level_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => true)),
      'price_type_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceType'), 'add_empty' => true)),
      'weight_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'), 'add_empty' => true)),
      'price'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'weight_start'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'weight_end'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'price_level_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PriceLevel'), 'column' => 'id')),
      'price_type_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PriceType'), 'column' => 'id')),
      'weight_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('WeightType'), 'column' => 'id')),
      'price'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('weight_price_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WeightPrice';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'weight_start'   => 'Number',
      'weight_end'     => 'Number',
      'price_level_id' => 'ForeignKey',
      'price_type_id'  => 'ForeignKey',
      'weight_type_id' => 'ForeignKey',
      'price'          => 'Number',
    );
  }
}
