<?php

/**
 * Locate form base class.
 *
 * @method Locate getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseLocateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'address_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => false)),
      'lat'        => new sfWidgetFormInputText(),
      'lng'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'address_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'))),
      'lat'        => new sfValidatorString(array('max_length' => 10)),
      'lng'        => new sfValidatorString(array('max_length' => 10)),
    ));

    $this->widgetSchema->setNameFormat('locate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Locate';
  }

}
