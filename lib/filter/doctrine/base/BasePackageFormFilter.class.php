<?php

/**
 * Package filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePackageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'package_code'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'courier_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => true)),
      'client_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => true)),
      'from_address_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
      'to_address_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address_7'), 'add_empty' => true)),
      'status_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageStatus'), 'add_empty' => true)),
      'detail_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageDetail'), 'add_empty' => true)),
      'partner_price'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'partner_tax'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tax'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'package_code'    => new sfValidatorPass(array('required' => false)),
      'courier_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Courier'), 'column' => 'id')),
      'client_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Client'), 'column' => 'id')),
      'from_address_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Address'), 'column' => 'id')),
      'to_address_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Address_7'), 'column' => 'id')),
      'status_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PackageStatus'), 'column' => 'id')),
      'detail_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PackageDetail'), 'column' => 'id')),
      'partner_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'partner_tax'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tax'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('package_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Package';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'package_code'    => 'Text',
      'courier_id'      => 'ForeignKey',
      'client_id'       => 'ForeignKey',
      'from_address_id' => 'ForeignKey',
      'to_address_id'   => 'ForeignKey',
      'status_id'       => 'ForeignKey',
      'detail_id'       => 'ForeignKey',
      'partner_price'   => 'Number',
      'partner_tax'     => 'Number',
      'price'           => 'Number',
      'tax'             => 'Number',
    );
  }
}
