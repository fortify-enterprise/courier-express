<?php

/**
 * ZonePolygon filter form base class.
 *
 * @package    cexp
 * @subpackage filter
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseZonePolygonFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ZonePolygonType'), 'add_empty' => true)),
      'pdata'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ZonePolygonType'), 'column' => 'id')),
      'pdata'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zone_polygon_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZonePolygon';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'type_id' => 'ForeignKey',
      'pdata'   => 'Text',
    );
  }
}
