<?php

/**
 * ZonePriceLevel form base class.
 *
 * @method ZonePriceLevel getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseZonePriceLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'from_zone_id'   => new sfWidgetFormInputHidden(),
      'to_zone_id'     => new sfWidgetFormInputHidden(),
      'price_level_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'from_zone_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('from_zone_id')), 'empty_value' => $this->getObject()->get('from_zone_id'), 'required' => false)),
      'to_zone_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('to_zone_id')), 'empty_value' => $this->getObject()->get('to_zone_id'), 'required' => false)),
      'price_level_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'))),
    ));

    $this->widgetSchema->setNameFormat('zone_price_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZonePriceLevel';
  }

}
