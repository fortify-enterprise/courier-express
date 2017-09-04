<?php

/**
 * ClientLogin filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseClientLoginFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'email'         => new sfWidgetFormFilterInput(),
      'password_hash' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'email'         => new sfValidatorPass(array('required' => false)),
      'password_hash' => new sfValidatorPass(array('required' => false)),
      'password'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('client_login_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClientLogin';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'email'         => 'Text',
      'password_hash' => 'Text',
      'password'      => 'Text',
    );
  }
}
