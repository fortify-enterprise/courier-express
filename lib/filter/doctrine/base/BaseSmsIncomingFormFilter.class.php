<?php

/**
 * SmsIncoming filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSmsIncomingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'carrier'        => new sfWidgetFormFilterInput(),
      'toa'            => new sfWidgetFormFilterInput(),
      'smsc'           => new sfWidgetFormFilterInput(),
      'sent_on'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'received_on'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'imsi'           => new sfWidgetFormFilterInput(),
      'length'         => new sfWidgetFormFilterInput(),
      'text'           => new sfWidgetFormFilterInput(),
      'reply_required' => new sfWidgetFormFilterInput(),
      'replied_on'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'carrier'        => new sfValidatorPass(array('required' => false)),
      'toa'            => new sfValidatorPass(array('required' => false)),
      'smsc'           => new sfValidatorPass(array('required' => false)),
      'sent_on'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'received_on'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'imsi'           => new sfValidatorPass(array('required' => false)),
      'length'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'text'           => new sfValidatorPass(array('required' => false)),
      'reply_required' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'replied_on'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('sms_incoming_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmsIncoming';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'carrier'        => 'Text',
      'toa'            => 'Text',
      'smsc'           => 'Text',
      'sent_on'        => 'Date',
      'received_on'    => 'Date',
      'imsi'           => 'Text',
      'length'         => 'Number',
      'text'           => 'Text',
      'reply_required' => 'Number',
      'replied_on'     => 'Date',
    );
  }
}
