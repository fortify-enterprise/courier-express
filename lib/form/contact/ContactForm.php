<?php

// lib/form/ContactForm.class.php
class ContactForm extends BaseForm
{

  public function configure()
  {
		parent::configure();

    $this->wantedFields = array(
        'name',
        'phone',
        'company',
        'email',
        'details'
    );

    $this->widgetSchema['name']->setAttribute('tabindex', 1);
    $this->widgetSchema['phone']->setAttribute('tabindex', 2);
    $this->widgetSchema['company']->setAttribute('tabindex', 3);
    $this->widgetSchema['email']->setAttribute('tabindex', 4);
    $this->widgetSchema['details']->setAttribute('tabindex', 5);


    // if client is logged in read his information
    $client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
    if ($client_id)
    {
      $client = Doctrine::getTable('Client')->findOneById($client_id);
      if ($client)
      {
        $this->widgetSchema['name']->setAttribute('value', $client->ClientDetail->getName());
        $this->widgetSchema['phone']->setAttribute('value', $client->ClientDetail->getPhone());
        $this->widgetSchema['company']->setAttribute('value', $client->ClientDetail->getDetails());
        $this->widgetSchema['email']->setAttribute('value', $client->ClientDetail->getEmail());
      }
    }


    $this->unsetAllExcept($this->wantedFields);
    $this->widgetSchema->setNameFormat('contact[%s]');
		$this->widgetSchema->setLabel('email', 'Email address');

		foreach( $this->validatorSchema->getFields() as $field){
   		$field->setOption('trim', true);
		}
  }
}

