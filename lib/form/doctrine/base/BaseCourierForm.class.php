<?php

/**
 * Courier form base class.
 *
 * @method Courier getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCourierForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'client_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => false)),
      'profit_cut' => new sfWidgetFormInputText(),
      'available'  => new sfWidgetFormInputText(),
      'enabled'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'client_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Client'))),
      'profit_cut' => new sfValidatorInteger(),
      'available'  => new sfValidatorInteger(),
      'enabled'    => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('courier[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Courier';
  }

}
