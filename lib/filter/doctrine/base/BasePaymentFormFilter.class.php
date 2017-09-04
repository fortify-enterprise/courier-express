<?php

/**
 * Payment filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePaymentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'payment_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'client_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => true)),
      'amount'       => new sfWidgetFormFilterInput(),
      'payment_type' => new sfWidgetFormFilterInput(),
      'ipn_string'   => new sfWidgetFormFilterInput(),
      'trn_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'payment_code' => new sfValidatorPass(array('required' => false)),
      'client_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Client'), 'column' => 'id')),
      'amount'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'payment_type' => new sfValidatorPass(array('required' => false)),
      'ipn_string'   => new sfValidatorPass(array('required' => false)),
      'trn_id'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'payment_code' => 'Text',
      'client_id'    => 'ForeignKey',
      'amount'       => 'Number',
      'payment_type' => 'Text',
      'ipn_string'   => 'Text',
      'trn_id'       => 'Text',
    );
  }
}
