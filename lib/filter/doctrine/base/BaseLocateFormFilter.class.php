<?php

/**
 * Locate filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseLocateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'address_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
      'lat'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lng'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'address_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Address'), 'column' => 'id')),
      'lat'        => new sfValidatorPass(array('required' => false)),
      'lng'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('locate_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Locate';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'address_id' => 'ForeignKey',
      'lat'        => 'Text',
      'lng'        => 'Text',
    );
  }
}
