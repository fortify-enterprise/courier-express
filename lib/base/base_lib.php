<?php

class Base_Lib  {

  protected $c_logger;
  static protected $c_instance;

	function __construct()
	{
		$this->c_logger = sfContext::getInstance()->getLogger();
	}


  // singleton
  static public function getInstance()
  {
    if (!self::$c_instance instanceof self)
      self::$c_instance = new self;

    return self::$c_instance;
  }
}
