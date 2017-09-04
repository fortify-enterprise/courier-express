<?php

class sfValidatorPhone extends sfValidatorBase
{
	private $required = false;

  protected function configure($options = array(), $messages = array())
  {
		if (isset($options['required']) || isset($messages['required']))
			$this->required = true;

    // Setup some basic error messages
    $this->addMessage('phone_error', 'Invalid phone number');
    $this->addMessage('phone_empty_error', 'Phone number required');
  }


  /**
   * @see sfValidatorBase
   */
 
  protected function doClean($value)
  {
		if ($this->required)
		{
			if ($value == "")
				throw new sfValidatorError($this, 'phone_empty_error', array('value' => $value));

			if (!preg_match("/^(1)?[0-9]{10}$/i", $value))
				throw new sfValidatorError($this, 'phone_error', array('value' => $value));
		}
		else
		{
			if ($value != "")
				if (!preg_match("/^(1)?[0-9]{10}$/i", $value))
					throw new sfValidatorError($this, 'phone_error', array('value' => $value));
		}

		return $value;
	}
}
