<?php

/**
 * ServiceAddon form base class.
 *
 * @method ServiceAddon getObject() Returns the current form's model object
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseServiceAddonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'service_level_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevel'), 'add_empty' => false)),
      'price_level_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => true)),
      'from_zone_polygon_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon'), 'add_empty' => true)),
      'to_zone_polygon_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon_4'), 'add_empty' => true)),
      'price'                 => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'service_level_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevel'))),
      'price_level_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'required' => false)),
      'from_zone_polygon_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon'), 'required' => false)),
      'to_zone_polygon_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon_4'), 'required' => false)),
      'price'                 => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('service_addon[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ServiceAddon';
  }

}
