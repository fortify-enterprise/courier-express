<?php

/**
 * WeightPrice form base class.
 *
 * @method WeightPrice getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseWeightPriceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'weight_start'   => new sfWidgetFormInputText(),
      'weight_end'     => new sfWidgetFormInputText(),
      'price_level_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => false)),
      'price_type_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceType'), 'add_empty' => false)),
      'weight_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'), 'add_empty' => false)),
      'price'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'weight_start'   => new sfValidatorInteger(),
      'weight_end'     => new sfValidatorInteger(),
      'price_level_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'))),
      'price_type_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PriceType'))),
      'weight_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WeightType'))),
      'price'          => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('weight_price[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WeightPrice';
  }

}
