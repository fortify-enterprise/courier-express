<?php

class Settings_Db extends Base_Lib
{
  function set_settings_info($info)
  {
		foreach ($info as $key => $value)
		{
			$setting = new Settings();
			$setting->setting = $key;
			$setting->value   = $value;
			$setting->replace();
		}
 	}


  function delete_settings_info($info)
  {
		if (!is_array($info))
			return null;

		foreach ($info as $key => $value)
		{
			Doctrine_Query::create()
  			->delete('Settings s')
  			->where('s.setting = ?', $key)
  			->execute();
		}
		return 1;
 	}


  function get_settings_info()
  {
 	  $q = Doctrine_Query::create()
         ->select('s.setting')
         ->addSelect('s.value')
         ->from('Settings s');

		$result = $q->execute();
    $info = array();
    foreach($result as $row)
      $info[$row['setting']]= $row['value'];

    return $info;
  }
}
