<?php

// lib/form/ContactForm.class.php
class FeedbackContactForm extends ContactForm
{

  public function configure()
  {
    parent::configure();
 
    $this->wantedFields = array(
        'name',
        'email',
        'details'
    );

    $this->widgetSchema['name']->setAttribute('tabindex', 1);
    $this->widgetSchema['email']->setAttribute('tabindex', 2);
    $this->widgetSchema['details']->setAttribute('tabindex', 3);


 		$this->unsetAllExcept($this->wantedFields);
		$this->widgetSchema->setNameFormat('contact[%s]');
  }
}

