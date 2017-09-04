<?php

/**
 * State form base class.
 *
 * @method State getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInputText(),
      'alpha_code'     => new sfWidgetFormInputText(),
      'abbreviation'   => new sfWidgetFormInputText(),
      'numerical_code' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 45)),
      'alpha_code'     => new sfValidatorString(array('max_length' => 2)),
      'abbreviation'   => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'numerical_code' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('state[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'State';
  }

}
