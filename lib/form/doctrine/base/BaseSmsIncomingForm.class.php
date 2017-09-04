<?php

/**
 * SmsIncoming form base class.
 *
 * @method SmsIncoming getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSmsIncomingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'carrier'        => new sfWidgetFormInputText(),
      'toa'            => new sfWidgetFormInputText(),
      'smsc'           => new sfWidgetFormInputText(),
      'sent_on'        => new sfWidgetFormDateTime(),
      'received_on'    => new sfWidgetFormDateTime(),
      'imsi'           => new sfWidgetFormInputText(),
      'length'         => new sfWidgetFormInputText(),
      'text'           => new sfWidgetFormTextarea(),
      'reply_required' => new sfWidgetFormInputText(),
      'replied_on'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'carrier'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'toa'            => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'smsc'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'sent_on'        => new sfValidatorDateTime(array('required' => false)),
      'received_on'    => new sfValidatorDateTime(array('required' => false)),
      'imsi'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'length'         => new sfValidatorInteger(array('required' => false)),
      'text'           => new sfValidatorString(array('required' => false)),
      'reply_required' => new sfValidatorInteger(array('required' => false)),
      'replied_on'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sms_incoming[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmsIncoming';
  }

}
