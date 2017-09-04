<?php

/**
 * PriceLevel form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PriceLevelForm extends BasePriceLevelForm
{
  public function configure()
  {
    $courier_id = sfContext::getInstance()->getUser()->getAttribute('courier_id');
    $res = Doctrine::getTable('Courier')->find(($courier_id) ? $courier_id : '')->toArray();

    if ($courier_id)
    {
      $courier_names[$res['id']] = $res['id'];
    }

    $this->setWidgets(array(
      'courier_id' => new sfWidgetFormSelect(array('choices' => $courier_names)),
      'name' => new sfWidgetFormInputText(array(), array('tabindex' => 2, 'style' => 'width: 120px')),
    ));

    $this->widgetSchema->setLabels(array(
      'courier_id' => 'Courier',
      'name' => 'Price level name',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'courier_id' => 'Courier name',
      'name' => 'Price level name',
    ));


    $this->setValidators(array(

      'courier_id' => new sfValidatorPass(),

      'name' => new sfValidatorString(array('min_length' => 2, 'max_length' => 10), array(
        'required'   => 'Name is required',
        'min_length' => 'Name be at least %min_length% characters.',
        'max_length' => 'Name not exceed %max_length% characters.',
      )),

    ));

    $this->widgetSchema->setNameFormat('price_level[%s]');
    $this->widgetSchema->setFormFormatterName('Horizontal');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		 }
  }
}
