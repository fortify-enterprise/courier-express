<?php

/**
 * ClientLogin form base class.
 *
 * @method ClientLogin getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientLoginForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'email'         => new sfWidgetFormInputText(),
      'password_hash' => new sfWidgetFormInputText(),
      'password'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'email'         => new sfValidatorString(array('max_length' => 80, 'required' => false)),
      'password_hash' => new sfValidatorString(array('max_length' => 255)),
      'password'      => new sfValidatorString(array('max_length' => 80)),
    ));

    $this->widgetSchema->setNameFormat('client_login[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientLogin';
  }

}
