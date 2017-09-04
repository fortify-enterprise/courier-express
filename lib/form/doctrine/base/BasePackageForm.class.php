<?php

/**
 * Package form base class.
 *
 * @method Package getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePackageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'package_code'    => new sfWidgetFormInputText(),
      'courier_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => false)),
      'client_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Client'), 'add_empty' => false)),
      'from_address_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => false)),
      'to_address_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address_7'), 'add_empty' => false)),
      'status_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageStatus'), 'add_empty' => false)),
      'detail_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PackageDetail'), 'add_empty' => false)),
      'partner_price'   => new sfWidgetFormInputText(),
      'partner_tax'     => new sfWidgetFormInputText(),
      'price'           => new sfWidgetFormInputText(),
      'tax'             => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'package_code'    => new sfValidatorString(array('max_length' => 48)),
      'courier_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'))),
      'client_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Client'))),
      'from_address_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'))),
      'to_address_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address_7'))),
      'status_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PackageStatus'))),
      'detail_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PackageDetail'))),
      'partner_price'   => new sfValidatorNumber(),
      'partner_tax'     => new sfValidatorNumber(),
      'price'           => new sfValidatorNumber(),
      'tax'             => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('package[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Package';
  }

}
