<?php

// lib/form/ContactForm.class.php
class UploadForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'userfile'    => new sfWidgetFormInputFile(),
    ));
 
    $this->setValidators(array(
      'userfile'    => new sfValidatorFile(),
    ));
  }
}
