<?php

/**
 * ServiceAddon filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseServiceAddonFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'service_level_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceLevel'), 'add_empty' => true)),
      'price_level_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PriceLevel'), 'add_empty' => true)),
      'from_zone_polygon_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon'), 'add_empty' => true)),
      'to_zone_polygon_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygon_4'), 'add_empty' => true)),
      'price'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'service_level_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ServiceLevel'), 'column' => 'id')),
      'price_level_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PriceLevel'), 'column' => 'id')),
      'from_zone_polygon_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZonePolygon'), 'column' => 'id')),
      'to_zone_polygon_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZonePolygon_4'), 'column' => 'id')),
      'price'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('service_addon_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ServiceAddon';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'service_level_type_id' => 'ForeignKey',
      'price_level_id'        => 'ForeignKey',
      'from_zone_polygon_id'  => 'ForeignKey',
      'to_zone_polygon_id'    => 'ForeignKey',
      'price'                 => 'Number',
    );
  }
}
