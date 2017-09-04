<?php

/**
 * ServiceLevelType form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class ServiceLevelTypeForm extends BaseServiceLevelTypeForm
{
  public function configure()
  {
    $service_db   = new Service_Db();
    $service_types = $service_db->get_services();

    $this->setWidgets(array(
      'id' => new sfWidgetFormSelect(array('choices' => $service_types), array('tabindex' => 1, 'style' => 'width: 220px; margin-right: 10px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Service type',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'How fast will your package be delivered',
    ));

    $this->setValidators(array(
      'id' => new sfValidatorChoice(array('choices' => array_keys($service_types))),
    ));

    $this->widgetSchema->setNameFormat('service_level_type[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

  }
}
