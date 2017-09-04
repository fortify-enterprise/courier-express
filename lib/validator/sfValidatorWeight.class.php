<?php

class sfValidatorWeight extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('weight_not_valid', 'Invalid weight');
    $this->addMessage('over_weight', 'Over allowed weight');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
		if ($value > sfConfig::get('app_package_max_weight'))
			throw new sfValidatorError($this, 'over_weight', array('value' => $value));

		if (preg_match('/^([0-9])+$/i', $value))
			return $value;

		throw new sfValidatorError($this, 'weight_not_valid', array('value' => $value));
  }
}
