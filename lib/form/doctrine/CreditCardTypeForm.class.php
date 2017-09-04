<?php

/**
 * CreditCardType form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreditCardTypeForm extends BaseCreditCardTypeForm
{
  public function configure()
  {
		$payment_db = new Payment_Db();
    $card_types = $payment_db->get_card_types(true);

    $this->setWidgets(array(
      'id' => new sfWidgetFormSelect(array('choices' => $card_types, 'default' => '1'),
          array('style' => 'width: 120px')),
      'type' => new sfWidgetFormInputText(array(), array('style' => 'width: 120px')),
    ));

    $this->widgetSchema->setLabels(array(
      'id' => 'Card type',
      'type' => 'Card type name',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'id' => 'Credit card type: ex. MasterCard, or Visa',
      'type' => 'Country name',
    ));

    $this->setValidators(array(

      'id' => new sfValidatorChoice(array('choices' => array_keys($card_types))),
      'type' => new sfValidatorString(array('min_length' => 0, 'max_length' => 50), array(
        'required'   => 'Credit card type is required',
        'min_length' => 'Credit card type must be at least %min_length% characters.',
        'max_length' => 'Credit card type must not exceed %max_length% characters.',
      )),

    ));


    //$this->widgetSchema->setNameFormat('contact[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
	}
}
