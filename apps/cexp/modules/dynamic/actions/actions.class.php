<?php

/**
 * dynamic actions.
 *
 * @package    courierexpress
 * @subpackage dynamic
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dynamicActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }


	public function executeGet_states_provinces_for_country (sfWebrequest $request)
	{
		$country_id   = $request->getParameter('country_id');
		if (!$country_id)
			return;

		$address_db   = new Address_Db();
		$country_name = strtolower($address_db->get_country_by_id($country_id));

		$ids = array();
		$elements = array();
		$states_provinces = array();

		switch ($country_name)
		{
			case 'canada':
				$states_provinces = $address_db->get_provinces_list();
				foreach ($states_provinces as $key => $value) {
					$ids []= $value['id'];
					$elements []=  $value['province_territory'];
				}
			break;

			case 'united states':
				$states_provinces = $address_db->get_states_list();
				foreach ($states_provinces as $key => $value) {
					$ids []= $value['id'];
					$elements []=  $value['name'];
				}

			break;
		}
		
    print json_encode(array_combine($ids, $elements));
	}

	
  public function executeSession_check (sfWebRequest $request)
  {
		if ($this->getUser()->setAuthenticated(true))
			$this->session_check = 'session marker';
  }

}
