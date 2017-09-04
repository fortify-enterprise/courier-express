<?php

/**
 * ServiceLevel form base class.
 *
 * @method ServiceLevel getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseServiceLevelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'courier_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => false)),
      'type_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevelType'), 'add_empty' => false)),
      'is_enabled' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'courier_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'))),
      'type_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevelType'))),
      'is_enabled' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('service_level[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ServiceLevel';
  }

}
