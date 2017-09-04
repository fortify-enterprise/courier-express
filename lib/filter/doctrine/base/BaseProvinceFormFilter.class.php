<?php

/**
 * Province filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProvinceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'province_territory' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sgc_code'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'alpha_code'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'abbreviation'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'province_territory' => new sfValidatorPass(array('required' => false)),
      'sgc_code'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alpha_code'         => new sfValidatorPass(array('required' => false)),
      'abbreviation'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('province_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Province';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'province_territory' => 'Text',
      'sgc_code'           => 'Number',
      'alpha_code'         => 'Text',
      'abbreviation'       => 'Text',
    );
  }
}
