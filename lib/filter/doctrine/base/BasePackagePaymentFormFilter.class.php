<?php

/**
 * PackagePayment filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePackagePaymentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'payment_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => true)),
      'package_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Package'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'payment_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Payment'), 'column' => 'id')),
      'package_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Package'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('package_payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PackagePayment';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'payment_id' => 'ForeignKey',
      'package_id' => 'ForeignKey',
    );
  }
}
