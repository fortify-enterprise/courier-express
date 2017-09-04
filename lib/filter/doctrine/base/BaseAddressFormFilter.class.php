<?php

/**
 * Address filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAddressFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'apt_unit'          => new sfWidgetFormFilterInput(),
      'street_number'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'street_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'street_type'       => new sfWidgetFormFilterInput(),
      'city'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'province_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Province'), 'add_empty' => true)),
      'state_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'add_empty' => true)),
      'province_state_id' => new sfWidgetFormFilterInput(),
      'country_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'add_empty' => true)),
      'postal_code'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'apt_unit'          => new sfValidatorPass(array('required' => false)),
      'street_number'     => new sfValidatorPass(array('required' => false)),
      'street_name'       => new sfValidatorPass(array('required' => false)),
      'street_type'       => new sfValidatorPass(array('required' => false)),
      'city'              => new sfValidatorPass(array('required' => false)),
      'province_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Province'), 'column' => 'id')),
      'state_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('State'), 'column' => 'id')),
      'province_state_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'country_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Country'), 'column' => 'id')),
      'postal_code'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('address_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Address';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'apt_unit'          => 'Text',
      'street_number'     => 'Text',
      'street_name'       => 'Text',
      'street_type'       => 'Text',
      'city'              => 'Text',
      'province_id'       => 'ForeignKey',
      'state_id'          => 'ForeignKey',
      'province_state_id' => 'Number',
      'country_id'        => 'ForeignKey',
      'postal_code'       => 'Text',
    );
  }
}
