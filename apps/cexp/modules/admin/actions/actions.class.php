<?php

/**
 * admin actions.
 *
 * @package    courierexpress
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */

class adminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function preExecute()
  {
		Tools_Lib::checkUnsupportedCountries();
    $this->dis = Constants::emsize();
  }


  public function executeIndex(sfWebRequest $request)
  {
    $settings_db = new Settings_Db();
    $this->info  = array();

		if ($request->isMethod('post'))
    {
      // get partners
      $this->info['tax_amount']        = $request->getParameter('tax_amount');
      $this->info['package_id_length'] = $request->getParameter('package_id_length');
      $this->info['payment_id_length'] = $request->getParameter('payment_id_length');

      // set settings info
      $settings_db->set_settings_info($this->info);
      $this->message = Tools_Lib::getSavedMessage();
    }

    $this->info = $settings_db->get_settings_info();
  }


  public function executeEdit_partners(sfWebRequest $request)
  {
    $couriers_db = new Couriers_Db();
    $address_db  = new Address_Db();
		$courier_id  = $request->getParameter('country_id');

    // fill in $this->province_ids, $this->province_names
		// fill in $this->country_ids, $this->country_names
		if ($courier_id)
			$address_db->init_province_country_ids ($this, $country_id);
    // for the dropdown
    $couriers_list = $couriers_db->get_couriers_info(1,1,1);


    // assign options arrays
    $this->courier_ids = $couriers_list[0];
    $this->couriers    = $couriers_list[1];
		$courier = new Courier();

		if ($courier_id)
			$courier = Doctrine::getTable('Courier')->findOneById($courier_id);
 		
		$this->form = new CourierForm($courier);

    if ($request->isMethod('post'))
    {
      $this->courier       = $request->getParameter('courier');
      $this->client_detail = $request->getParameter('client_detail');
      $this->client_login  = $request->getParameter('client_login');
      $this->address       = $request->getParameter('address');

			if (isset($this->courier['courier_id']))
	      $this->courier_id  = $this->courier['courier_id'];

      $this->courier['enabled'] = isset($this->courier['enabled']) ? 1 : 0;

      // delete element
      if ($this->client_detail['name'] == "" && isset($this->courier_id))
			{
        //$couriers_db->delete_partner($courier_id);
      }
			else if ($this->is_new_partner)
			{
        $this->courier_id = $couriers_db->insert_partner($this->courier, $this->client_detail, $this->client_login, $this->address);
      }
			else
			{
        $couriers_db->set_courier_info($this->courier_id, $this->courier, $this->client_detail, $this->client_login, $this->address);
			}

      $this->message = Tools_Lib::getSavedMessage();
    }


 }


  public function executeGet_courier_information (sfWebRequest $request)
  {
		$courier_id = $request->getParameter('courier_id');
		if (!$courier_id)
			return;

    $couriers_db = new Couriers_Db();
		$courier_id  = $request->getParameter('courier_id');
    print json_encode($couriers_db->get_courier_info ($courier_id));
  }


	public function executeWho_accessed (sfWebRequest $request)
	{
		/*$this->visitors*/ $res = Doctrine_Query::create()
						->select('DISTINCT v.ip as ip')
						->addSelect('v.country_name, v.region_name, v.city, v.zip_postal_code')
						->addSelect('UNIX_TIMESTAMP(v.updated_ts) as updated_ts')
						->from('Visitor v')
						->orderBy('v.updated_ts desc')
						->fetchArray();

		$this->visitors = array();
		foreach ($res as $visitor)
		{
			if (!isset($this->visitors[$visitor['ip']]))
				$this->visitors[$visitor['ip']] = $visitor;
		}

    // end of update code

    $this->current_page = $request->getParameter('page') ? $request->getParameter('page') : 1;

    //
    // set pagination

    if ($this->visitors)
    {
      $this->pager = new ArrayPager(null, sfConfig::get('app_pagination_visitors'));
      $this->pager->setResultArray($this->visitors);
      $this->pager->setPage($this->current_page);
      $this->pager->init();

      $this->visitors = $this->pager->getResults();
      $this->page_links = $this->pager->getLinks();
    }


	}


	public function executeWho_accessed_detail (sfWebRequest $request)
	{
		$ip = $request->getParameter('ip');
		$this->current_page = $request->getParameter('page');
		if (!$ip)
			$this->redirect('admin/who_accessed');

		$this->visitors = Doctrine_Query::create()
						->select('DISTINCT v.ip as ip')
						->addSelect('v.country_name, v.region_name, v.city, v.zip_postal_code')
						->addSelect('UNIX_TIMESTAMP(v.updated_ts) as updated_ts')
						->addSelect('v.agent')
						->from('Visitor v')
						->where('v.ip = ?', $ip)
						->orderBy('v.updated_ts desc')
						->fetchArray();
	}
}

