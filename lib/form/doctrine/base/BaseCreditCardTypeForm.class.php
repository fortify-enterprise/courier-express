<?php

/**
 * CreditCardType form base class.
 *
 * @method CreditCardType getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCreditCardTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'type'    => new sfWidgetFormInputText(),
      'abbr'    => new sfWidgetFormInputText(),
      'enabled' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'    => new sfValidatorString(array('max_length' => 100)),
      'abbr'    => new sfValidatorString(array('max_length' => 10)),
      'enabled' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('credit_card_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CreditCardType';
  }

}
