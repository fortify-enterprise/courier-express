<?php

// lib/form/ContactForm.class.php
class EnquiryContactForm extends ContactForm
{

  public function configure()
  {
    parent::configure();

    $this->wantedFields = array(
        'name',
        'phone',
        'email',
        'details'
    );

    $this->widgetSchema['name']->setAttribute('tabindex', 1);
    $this->widgetSchema['phone']->setAttribute('tabindex', 2);
    $this->widgetSchema['email']->setAttribute('tabindex', 3);
    $this->widgetSchema['details']->setAttribute('tabindex', 4);


		$this->widgetSchema->setNameFormat('contact[%s]');
 		$this->unsetAllExcept($this->wantedFields);
  }
}

