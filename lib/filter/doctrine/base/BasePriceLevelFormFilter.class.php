<?php

/**
 * PriceLevel filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePriceLevelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'courier_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => true)),
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'courier_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Courier'), 'column' => 'id')),
      'name'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('price_level_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PriceLevel';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'courier_id' => 'ForeignKey',
      'name'       => 'Text',
    );
  }
}
