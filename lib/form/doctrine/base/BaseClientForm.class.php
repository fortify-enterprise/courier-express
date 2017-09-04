<?php

/**
 * Client form base class.
 *
 * @method Client getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'address_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => false)),
      'type_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientType'), 'add_empty' => false)),
      'detail_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientDetail'), 'add_empty' => false)),
      'payment_detail_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientPaymentDetail'), 'add_empty' => true)),
      'login_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientLogin'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'address_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'))),
      'type_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ClientType'))),
      'detail_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ClientDetail'))),
      'payment_detail_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ClientPaymentDetail'), 'required' => false)),
      'login_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ClientLogin'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('client[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Client';
  }

}
