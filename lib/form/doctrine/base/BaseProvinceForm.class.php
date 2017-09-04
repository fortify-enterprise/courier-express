<?php

/**
 * Province form base class.
 *
 * @method Province getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProvinceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'province_territory' => new sfWidgetFormInputText(),
      'sgc_code'           => new sfWidgetFormInputText(),
      'alpha_code'         => new sfWidgetFormInputText(),
      'abbreviation'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'province_territory' => new sfValidatorString(array('max_length' => 40)),
      'sgc_code'           => new sfValidatorInteger(),
      'alpha_code'         => new sfValidatorString(array('max_length' => 2)),
      'abbreviation'       => new sfValidatorString(array('max_length' => 10)),
    ));

    $this->widgetSchema->setNameFormat('province[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Province';
  }

}
