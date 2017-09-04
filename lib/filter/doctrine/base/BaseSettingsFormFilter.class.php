<?php

/**
 * Settings filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSettingsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'setting' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'setting' => new sfValidatorPass(array('required' => false)),
      'value'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('settings_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Settings';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'setting' => 'Text',
      'value'   => 'Text',
    );
  }
}
