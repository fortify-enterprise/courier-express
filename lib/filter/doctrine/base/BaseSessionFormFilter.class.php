<?php

/**
 * Session filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSessionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'time'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'session_data' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'time'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'session_data' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('session_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Session';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'time'         => 'Number',
      'session_data' => 'Text',
    );
  }
}
