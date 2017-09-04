<?php

/**
 * ClientDetail filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientDetailFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'details'           => new sfWidgetFormFilterInput(),
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'             => new sfWidgetFormFilterInput(),
      'contact'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'how_did_u_hear'    => new sfWidgetFormFilterInput(),
      'registration_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'details'           => new sfValidatorPass(array('required' => false)),
      'name'              => new sfValidatorPass(array('required' => false)),
      'phone'             => new sfValidatorPass(array('required' => false)),
      'email'             => new sfValidatorPass(array('required' => false)),
      'contact'           => new sfValidatorPass(array('required' => false)),
      'how_did_u_hear'    => new sfValidatorPass(array('required' => false)),
      'registration_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('client_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientDetail';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'details'           => 'Text',
      'name'              => 'Text',
      'phone'             => 'Text',
      'email'             => 'Text',
      'contact'           => 'Text',
      'how_did_u_hear'    => 'Text',
      'registration_date' => 'Date',
    );
  }
}
