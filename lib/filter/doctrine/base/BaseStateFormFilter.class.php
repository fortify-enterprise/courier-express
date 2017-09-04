<?php

/**
 * State filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'alpha_code'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'abbreviation'   => new sfWidgetFormFilterInput(),
      'numerical_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'alpha_code'     => new sfValidatorPass(array('required' => false)),
      'abbreviation'   => new sfValidatorPass(array('required' => false)),
      'numerical_code' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('state_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'State';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'name'           => 'Text',
      'alpha_code'     => 'Text',
      'abbreviation'   => 'Text',
      'numerical_code' => 'Number',
    );
  }
}
