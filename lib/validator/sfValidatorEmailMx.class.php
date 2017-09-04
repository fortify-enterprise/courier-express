<?php

class sfValidatorEmailMx extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    // Setup some basic error messages
    $this->addMessage('domain_does_not_exist', 'Email domain does not exist');
  }


  /**
   * @see sfValidatorBase
   */

  protected function doClean($value)
  {
		list($username,$domain)= preg_split('/@/',$value);

		//But every value other than "ANY" will work
		if(!checkdnsrr($domain,'MX'))
			throw new sfValidatorError($this, 'domain_does_not_exist', array('value' => $value));
		
		return $value;
  }
}
