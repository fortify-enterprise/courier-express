<?php

// lib/form/ContactForm.class.php
class PartnerContactForm extends ContactForm
{

  public function configure()
  {
		parent::configure();

    $this->widgetSchema['name']->setAttribute('tabindex', 1);
    $this->widgetSchema['company']->setAttribute('tabindex', 2);
    $this->widgetSchema['phone']->setAttribute('tabindex', 3);
    $this->widgetSchema['email']->setAttribute('tabindex', 4);
    $this->widgetSchema['details']->setAttribute('tabindex', 5);


		$this->widgetSchema->setNameFormat('contact[%s]');
  }
}

