<?php

/**
 * ClientDetail form base class.
 *
 * @method ClientDetail getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClientDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'details'           => new sfWidgetFormTextarea(),
      'name'              => new sfWidgetFormInputText(),
      'phone'             => new sfWidgetFormInputText(),
      'email'             => new sfWidgetFormInputText(),
      'contact'           => new sfWidgetFormInputText(),
      'how_did_u_hear'    => new sfWidgetFormTextarea(),
      'registration_date' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'details'           => new sfValidatorString(array('required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 80)),
      'phone'             => new sfValidatorString(array('max_length' => 80)),
      'email'             => new sfValidatorString(array('max_length' => 80, 'required' => false)),
      'contact'           => new sfValidatorString(array('max_length' => 80)),
      'how_did_u_hear'    => new sfValidatorString(array('required' => false)),
      'registration_date' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('client_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientDetail';
  }

}
