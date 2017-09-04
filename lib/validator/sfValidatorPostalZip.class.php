<?php

class sfValidatorPostalZip extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('postal_not_valid', 'Invalid postal code');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
		if (preg_match('/[a-z]\d[a-z][ ]*\d[a-z]\d/i', $value))
			return $value;

		if (preg_match('/^\d{5}$/i', $value))
			return $value;

		if (preg_match('/[0-9]{5}-[0-9]{4}/i', $value))
			return $value;

		throw new sfValidatorError($this, 'postal_not_valid', array('value' => $value));
  }
}
