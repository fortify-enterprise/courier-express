<?php

/**
 * Courier filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCourierFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'client_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => true)),
      'profit_cut' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'available'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'enabled'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'client_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Client'), 'column' => 'id')),
      'profit_cut' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'available'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'enabled'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('courier_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Courier';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'client_id'  => 'ForeignKey',
      'profit_cut' => 'Number',
      'available'  => 'Number',
      'enabled'    => 'Number',
    );
  }
}
