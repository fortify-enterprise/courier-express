<?php

/**
 * ZonePriceLevel filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZonePriceLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'price_level_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'price_level_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PriceLevel'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('zone_price_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZonePriceLevel';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'from_zone_id'   => 'Number',
      'to_zone_id'     => 'Number',
      'price_level_id' => 'ForeignKey',
    );
  }
}
