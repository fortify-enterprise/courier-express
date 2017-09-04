<?php

/**
 * Visitor filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseVisitorFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ip'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'country_code'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'country_name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'region_code'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'region_name'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zip_postal_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'latitude'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'longitude'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'timezone_name'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gmtoffset'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'isdst'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'agent'           => new sfWidgetFormFilterInput(),
      'updated_ts'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ip'              => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorPass(array('required' => false)),
      'country_code'    => new sfValidatorPass(array('required' => false)),
      'country_name'    => new sfValidatorPass(array('required' => false)),
      'region_code'     => new sfValidatorPass(array('required' => false)),
      'region_name'     => new sfValidatorPass(array('required' => false)),
      'city'            => new sfValidatorPass(array('required' => false)),
      'zip_postal_code' => new sfValidatorPass(array('required' => false)),
      'latitude'        => new sfValidatorPass(array('required' => false)),
      'longitude'       => new sfValidatorPass(array('required' => false)),
      'timezone_name'   => new sfValidatorPass(array('required' => false)),
      'gmtoffset'       => new sfValidatorPass(array('required' => false)),
      'isdst'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agent'           => new sfValidatorPass(array('required' => false)),
      'updated_ts'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('visitor_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Visitor';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'ip'              => 'Text',
      'status'          => 'Text',
      'country_code'    => 'Text',
      'country_name'    => 'Text',
      'region_code'     => 'Text',
      'region_name'     => 'Text',
      'city'            => 'Text',
      'zip_postal_code' => 'Text',
      'latitude'        => 'Text',
      'longitude'       => 'Text',
      'timezone_name'   => 'Text',
      'gmtoffset'       => 'Text',
      'isdst'           => 'Number',
      'agent'           => 'Text',
      'updated_ts'      => 'Date',
    );
  }
}
