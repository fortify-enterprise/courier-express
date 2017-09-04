<?php

// lib/form/ContactForm.class.php
class TrackingForm extends BaseForm
{

  public function configure()
  {
		parent::configure();

    $this->wantedFields = array('shipment_number');
    $this->unsetAllExcept($this->wantedFields);
    $this->widgetSchema->setNameFormat('tracking[%s]');
  }
}

