<?php

/**
 * ClientPaymentDetail filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientPaymentDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'card_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CreditCardType'), 'add_empty' => true)),
      'card_number'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'exp_month'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'exp_year'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ccv_number'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address1'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address2'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
      'is_default'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'profile_code' => new sfValidatorPass(array('required' => false)),
      'card_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('CreditCardType'), 'column' => 'id')),
      'card_number'  => new sfValidatorPass(array('required' => false)),
      'exp_month'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'exp_year'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ccv_number'   => new sfValidatorPass(array('required' => false)),
      'name'         => new sfValidatorPass(array('required' => false)),
      'address1'     => new sfValidatorPass(array('required' => false)),
      'address2'     => new sfValidatorPass(array('required' => false)),
      'address_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Address'), 'column' => 'id')),
      'is_default'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('client_payment_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientPaymentDetail';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'profile_code' => 'Text',
      'card_type_id' => 'ForeignKey',
      'card_number'  => 'Text',
      'exp_month'    => 'Number',
      'exp_year'     => 'Number',
      'ccv_number'   => 'Text',
      'name'         => 'Text',
      'address1'     => 'Text',
      'address2'     => 'Text',
      'address_id'   => 'ForeignKey',
      'is_default'   => 'Number',
    );
  }
}
