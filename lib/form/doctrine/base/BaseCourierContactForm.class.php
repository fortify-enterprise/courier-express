<?php

/**
 * CourierContact form base class.
 *
 * @method CourierContact getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCourierContactForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'courier_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => false)),
      'name'       => new sfWidgetFormInputText(),
      'surname'    => new sfWidgetFormInputText(),
      'telephone'  => new sfWidgetFormInputText(),
      'mobile'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'courier_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'))),
      'name'       => new sfValidatorString(array('max_length' => 128)),
      'surname'    => new sfValidatorString(array('max_length' => 128)),
      'telephone'  => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'mobile'     => new sfValidatorString(array('max_length' => 16, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('courier_contact[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CourierContact';
  }

}
