<?php

/**
 * Staff form base class.
 *
 * @method Staff getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStaffForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'surname'    => new sfWidgetFormInputText(),
      'address_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => false)),
      'client_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 128)),
      'surname'    => new sfValidatorString(array('max_length' => 128)),
      'address_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'))),
      'client_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Client'))),
    ));

    $this->widgetSchema->setNameFormat('staff[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Staff';
  }

}
