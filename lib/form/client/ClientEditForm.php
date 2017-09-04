<?php

// lib/form/PartnerForm.class.php
class ClientEditForm extends BaseForm
{

  public function configure()
  {
		parent::configure();
    $this->widgetSchema->setNameFormat('client[%s]');
  }
}

