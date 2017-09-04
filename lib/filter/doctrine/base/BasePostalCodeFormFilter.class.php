<?php

/**
 * PostalCode filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePostalCodeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'postal_code'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'province'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'province_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city_type'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'latitude'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'longitude'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'postal_code'   => new sfValidatorPass(array('required' => false)),
      'city'          => new sfValidatorPass(array('required' => false)),
      'province'      => new sfValidatorPass(array('required' => false)),
      'province_code' => new sfValidatorPass(array('required' => false)),
      'city_type'     => new sfValidatorPass(array('required' => false)),
      'latitude'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'longitude'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('postal_code_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PostalCode';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'postal_code'   => 'Text',
      'city'          => 'Text',
      'province'      => 'Text',
      'province_code' => 'Text',
      'city_type'     => 'Text',
      'latitude'      => 'Number',
      'longitude'     => 'Number',
    );
  }
}
