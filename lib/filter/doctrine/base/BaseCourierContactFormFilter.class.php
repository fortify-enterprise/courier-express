<?php

/**
 * CourierContact filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCourierContactFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'courier_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => true)),
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'surname'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'telephone'  => new sfWidgetFormFilterInput(),
      'mobile'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'courier_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Courier'), 'column' => 'id')),
      'name'       => new sfValidatorPass(array('required' => false)),
      'surname'    => new sfValidatorPass(array('required' => false)),
      'telephone'  => new sfValidatorPass(array('required' => false)),
      'mobile'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('courier_contact_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CourierContact';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'courier_id' => 'ForeignKey',
      'name'       => 'Text',
      'surname'    => 'Text',
      'telephone'  => 'Text',
      'mobile'     => 'Text',
    );
  }
}
