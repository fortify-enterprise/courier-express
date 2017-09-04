<?php

/**
 * Payment form base class.
 *
 * @method Payment getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePaymentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'payment_code' => new sfWidgetFormInputText(),
      'client_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => false)),
      'amount'       => new sfWidgetFormInputText(),
      'payment_type' => new sfWidgetFormInputText(),
      'ipn_string'   => new sfWidgetFormTextarea(),
      'trn_id'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'payment_code' => new sfValidatorString(array('max_length' => 255)),
      'client_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Client'))),
      'amount'       => new sfValidatorNumber(array('required' => false)),
      'payment_type' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ipn_string'   => new sfValidatorString(array('required' => false)),
      'trn_id'       => new sfValidatorString(array('max_length' => 16)),
    ));

    $this->widgetSchema->setNameFormat('payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }

}
