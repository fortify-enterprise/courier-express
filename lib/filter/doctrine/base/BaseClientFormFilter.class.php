<?php

/**
 * Client filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'address_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
      'type_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientType'), 'add_empty' => true)),
      'detail_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientDetail'), 'add_empty' => true)),
      'payment_detail_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientPaymentDetail'), 'add_empty' => true)),
      'login_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClientLogin'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'address_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Address'), 'column' => 'id')),
      'type_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ClientType'), 'column' => 'id')),
      'detail_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ClientDetail'), 'column' => 'id')),
      'payment_detail_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ClientPaymentDetail'), 'column' => 'id')),
      'login_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ClientLogin'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('client_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Client';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'address_id'        => 'ForeignKey',
      'type_id'           => 'ForeignKey',
      'detail_id'         => 'ForeignKey',
      'payment_detail_id' => 'ForeignKey',
      'login_id'          => 'ForeignKey',
    );
  }
}
