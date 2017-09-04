<?php

/**
 * Surcharge form base class.
 *
 * @method Surcharge getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSurchargeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'courier_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => false)),
      'surcharge_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SurchargeType'), 'add_empty' => false)),
      'amt_limit'         => new sfWidgetFormInputText(),
      'amount'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'courier_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'))),
      'surcharge_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SurchargeType'))),
      'amt_limit'         => new sfValidatorNumber(array('required' => false)),
      'amount'            => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('surcharge[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Surcharge';
  }

}
