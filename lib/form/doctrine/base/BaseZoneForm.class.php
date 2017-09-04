<?php

/**
 * Zone form base class.
 *
 * @method Zone getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseZoneForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'courier_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'), 'add_empty' => false)),
      'zone_polygon_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 45)),
      'courier_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Courier'))),
      'zone_polygon_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon'))),
    ));

    $this->widgetSchema->setNameFormat('zone[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Zone';
  }

}
