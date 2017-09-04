<?php

/**
 * PackagePayment form base class.
 *
 * @method PackagePayment getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePackagePaymentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'payment_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => false)),
      'package_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Package'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'payment_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'))),
      'package_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Package'))),
    ));

    $this->widgetSchema->setNameFormat('package_payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PackagePayment';
  }

}
