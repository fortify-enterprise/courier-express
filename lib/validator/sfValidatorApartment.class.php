<?php

class sfValidatorApartment extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('apartment_not_valid', 'Invalid apartment');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
		if (preg_match('/^([0-9|A-Z|\-|\/])*$/i', $value))
			return $value;

		throw new sfValidatorError($this, 'apartment_not_valid', array('value' => $value));
  }
}
