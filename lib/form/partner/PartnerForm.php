<?php

// lib/form/PartnerForm.class.php
class PartnerGenericForm extends BaseForm
{

  public function configure()
  {
		parent::configure();
    $this->widgetSchema->setNameFormat('partner[%s]');
  }
}

