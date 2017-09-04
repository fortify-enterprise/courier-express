<?php

/**
 * DeliveryType form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class DeliveryTypeForm extends BaseDeliveryTypeForm
{
  public function configure()
  {
    // get the pacakge types
    $service_db   = new Service_Db();
    $delivery_types = $service_db->get_delivery_types();

    $this->setWidgets(array(
      'id' => new sfWidgetFormSelect(array('choices' => $delivery_types), array('tabindex' => 1, 'style' => 'width: 220px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Delivery type',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Prepaid - package is picked up at your location delivered elsewhere<br /><br />Collect - package to be picked up somewhere else and delivered to you<br /><br />Third party - package to be picked up from somewhere else and delivered to another party',
    ));

    $this->setValidators(array(
      'id' => new sfValidatorChoice(array('choices' => array_keys($delivery_types))),
    ));

    $this->widgetSchema->setNameFormat('delivery_type[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

		foreach( $this->validatorSchema->getFields() as $field){
 	  	$field->setOption('trim', true);
		}

  }
}
