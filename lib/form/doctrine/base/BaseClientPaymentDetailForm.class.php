<?php

/**
 * ClientPaymentDetail form base class.
 *
 * @method ClientPaymentDetail getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientPaymentDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'profile_code' => new sfWidgetFormInputText(),
      'card_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CreditCardType'), 'add_empty' => false)),
      'card_number'  => new sfWidgetFormInputText(),
      'exp_month'    => new sfWidgetFormInputText(),
      'exp_year'     => new sfWidgetFormInputText(),
      'ccv_number'   => new sfWidgetFormInputText(),
      'name'         => new sfWidgetFormInputText(),
      'address1'     => new sfWidgetFormInputText(),
      'address2'     => new sfWidgetFormInputText(),
      'address_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => false)),
      'is_default'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'profile_code' => new sfValidatorString(array('max_length' => 127)),
      'card_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('CreditCardType'))),
      'card_number'  => new sfValidatorString(array('max_length' => 40)),
      'exp_month'    => new sfValidatorInteger(),
      'exp_year'     => new sfValidatorInteger(),
      'ccv_number'   => new sfValidatorString(array('max_length' => 10)),
      'name'         => new sfValidatorString(array('max_length' => 100)),
      'address1'     => new sfValidatorString(array('max_length' => 255)),
      'address2'     => new sfValidatorString(array('max_length' => 255)),
      'address_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'))),
      'is_default'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('client_payment_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientPaymentDetail';
  }

}
