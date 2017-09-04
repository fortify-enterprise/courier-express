<?php

// lib/form/ContactForm.class.php
class PartnerEditForm extends PartnerGenericForm
{
  
  public function getCourierPriceLevels ()
  {
    $couriers_db = new Couriers_Db();
    $user = sfContext::getInstance()->getUser();

    return $couriers_db->get_price_level_names($user->getAttribute('courier_id'));
  }


  public function getCourierServiceLevels ()
  {
    $couriers_db = new Couriers_Db();
    $user = sfContext::getInstance()->getUser();

    list($service_level_ids, $names, $enabled) = $couriers_db->get_service_levels($user->getAttribute('courier_id'));
		return array_combine($service_level_ids, $names);
  }


  public function configure()
  {
		parent::configure();
    $this->wantedFields = array(
        'price',
    );
    $this->unsetAllExcept($this->wantedFields);



    $this->setWidgets(array(
      'price_level'  => new sfWidgetFormSelect(array('choices' => $this->getCourierPriceLevels())),
      'name' => new sfWidgetFormInputText(),
      'service_level' => new sfWidgetFormSelect(array('choices' => $this->getCourierServiceLevels())),
      'new_level' => new sfWidgetFormInputCheckbox (),
    ));

    $this->widgetSchema->setLabels(array(
      'price_level' => 'price level',
      'name'   => 'level name',
      'service_level' => 'service level',
      'new_level' => 'create new level',
    ));

    $this->getWidgetSchema()->setHelps(array(
      'price_level' => 'Price level name selection',
      'name' => 'Currently selected price level name',
      'service_level' => 'Service level (ex. Same day)',
      'new_level' => 'Create new price level'
    ));


    $this->setValidators(array(
      'name' => new sfValidatorString(array('min_length' => 1, 'max_length' => 10), array(
        'required'   => 'Level name is required',
        'min_length' => 'The level must be at least %min_length% characters.',
        'max_length' => 'The level must not exceed %max_length% characters.',
      )),


      'price_level' => new sfValidatorPass(),
      'service_level' => new sfValidatorPass(),
      'new_level' => new sfValidatorPass()

    ));

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}


    $this->widgetSchema->setNameFormat('price_level[%s]');
    $this->widgetSchema->setFormFormatterName('list');
  }
}

