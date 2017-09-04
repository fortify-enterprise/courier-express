<?php

/**
 * ZipCode filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZipCodeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'zip_code'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lat'                      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lon'                      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'state_prefix'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'county'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'z_type'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'xaxis'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'yaxis'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zaxis'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'z_primary'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'worldregion'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'country'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'locationtext'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'population'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'housingunits'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'income'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'landarea'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'waterarea'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'decommisioned'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'militaryrestrictioncodes' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'decommisionedplace'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'zip_code'                 => new sfValidatorPass(array('required' => false)),
      'lat'                      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'lon'                      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'city'                     => new sfValidatorPass(array('required' => false)),
      'state_prefix'             => new sfValidatorPass(array('required' => false)),
      'county'                   => new sfValidatorPass(array('required' => false)),
      'z_type'                   => new sfValidatorPass(array('required' => false)),
      'xaxis'                    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'yaxis'                    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'zaxis'                    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'z_primary'                => new sfValidatorPass(array('required' => false)),
      'worldregion'              => new sfValidatorPass(array('required' => false)),
      'country'                  => new sfValidatorPass(array('required' => false)),
      'locationtext'             => new sfValidatorPass(array('required' => false)),
      'location'                 => new sfValidatorPass(array('required' => false)),
      'population'               => new sfValidatorPass(array('required' => false)),
      'housingunits'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'income'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'landarea'                 => new sfValidatorPass(array('required' => false)),
      'waterarea'                => new sfValidatorPass(array('required' => false)),
      'decommisioned'            => new sfValidatorPass(array('required' => false)),
      'militaryrestrictioncodes' => new sfValidatorPass(array('required' => false)),
      'decommisionedplace'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zip_code_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZipCode';
  }

  public function getFields()
  {
    return array(
      'zip_code'                 => 'Text',
      'lat'                      => 'Number',
      'lon'                      => 'Number',
      'city'                     => 'Text',
      'state_prefix'             => 'Text',
      'county'                   => 'Text',
      'z_type'                   => 'Text',
      'xaxis'                    => 'Number',
      'yaxis'                    => 'Number',
      'zaxis'                    => 'Number',
      'z_primary'                => 'Text',
      'worldregion'              => 'Text',
      'country'                  => 'Text',
      'locationtext'             => 'Text',
      'location'                 => 'Text',
      'population'               => 'Text',
      'housingunits'             => 'Number',
      'income'                   => 'Number',
      'landarea'                 => 'Text',
      'waterarea'                => 'Text',
      'decommisioned'            => 'Text',
      'militaryrestrictioncodes' => 'Text',
      'decommisionedplace'       => 'Text',
      'id'                       => 'Number',
    );
  }
}
