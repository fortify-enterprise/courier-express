<?php

/**
 * services actions.
 *
 * @package    courierexpress
 * @subpackage services
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class servicesActions extends sfActions
{

	public function preExecute ()
	{
		Tools_Lib::checkUnsupportedCountries();
	}


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }


  public function executeGet_delivered_by(sfWebRequest $request)
  {
    $delivered_by = '';
    $service_db   = new Service_Db();

    $date    = $request->getParameter('date');
    $time    = $request->getParameter('time');
    $service = $request->getParameter('service');

    // get hours from service_level_types
    // s - same day
    // o - overnight
    $service_hours = $service_db->get_service_hours ($service);
		if (!$service_hours)
		{
			print json_encode(array('delivered_by' => 'service not defined'));
			return;
		}

    // add hours to the time
    $time_hours   = substr($time, 0, 2);
    $time_minutes = substr($time, 3, 2);
    $ampm         = substr($time, 5, 2);


    if ($ampm == 'PM' && $time_hours < 12)
      $time_hours += 12;

    // get the date month and day
    $date_split = preg_split('/-/',$date);
    $date_year  = $date_split[0];
    $date_month = $date_split[1];
    $date_day   = $date_split[2];


    // convert extra minutes into hours
    $extra_time_hours = $time_minutes / 60;

    if ($service_hours == 's')
    {
      if ($time_hours + $extra_time_hours <= 11)
        $delivered_by = mktime(17, 0, 0, $date_month, $date_day, $date_year);
      else if ($time_hours + $extra_time_hours > 11)
        $delivered_by = mktime(17, 0, 0, $date_month, $date_day+1, $date_year);

      // adjust for weekend
      if (date('D', $delivered_by) == 'Sat')
         $delivered_by = strtotime("+2 days", $delivered_by);
      else if ( date('D', $delivered_by) == 'Sun')
        $delivered_by = strtotime("+1 days", $delivered_by);
    }
    else if ($service_hours == 'o')
    {
      //print $time_hours . ' ' . $extra_time_hours . ' ' . $service_hours;

      if ($time_hours + $extra_time_hours <= 15)
        $delivered_by = mktime(17, 0, 0, $date_month, $date_day+1, $date_year);
      else if ($time_hours + $extra_time_hours > 15)
        $delivered_by = mktime(17, 0, 0, $date_month, $date_day+2, $date_year);


      //  adjust for weekend
      if (date('D', $delivered_by) == 'Sat')
         $delivered_by = strtotime("+3 days", $delivered_by);
      else if ( date('D', $delivered_by) == 'Sun')
        $delivered_by = strtotime("+2 days", $delivered_by);
      else if ( date('D', $delivered_by) == 'Mon')
        $delivered_by = strtotime("+1 days", $delivered_by);
    }
    else
    {
      if (($time_hours + $extra_time_hours + $service_hours) >= 17)
        $delivered_by = mktime(9, 0, 0, $date_month, $date_day+1, $date_year);
      else if (($time_hours + $service_hours) < 9)
        $delivered_by = mktime(9, 0, 0, $date_month, $date_day, $date_year);
      else
        $delivered_by = mktime($time_hours + $service_hours, $time_minutes, 0, $date_month, $date_day, $date_year);

      // adjust for weekend
      if (date('D', $delivered_by) == 'Sat')
        $delivered_by = strtotime("+2 days", $delivered_by);
      else if ( date('D', $delivered_by) == 'Sun')
        $delivered_by = strtotime("+1 days", $delivered_by);
    }

    // if the time is greater then 5pm then increment date by one day and set time to 9.00am
    //$delivered_by = concatinate date and time
    print json_encode(array('delivered_by' => date("h:iA - D d M Y", $delivered_by)));
  }


  function executePackage_details_tooltip (sfWebRequest $request)
  {
    $this->package_code = $request->getParameter('package_code');
		if (!$this->package_code)
			return;

    $this->pending_orders = $request->getParameter('pending_orders');
    $packages_db  = new Packages_Db();
    $this->info   = $packages_db->get_package_info($this->package_code);
		$this->info['signed_by'] = str_replace('_', ' ', $this->info['signed_by']);
  }


	function executeEdit_package (sfWebRequest $request)
	{
   	$this->package_code = $request->getParameter('package_code');

		if (!$this->package_code)
			$this->redirect('client/pending');
		
		if ($request->isMethod('post'))
		{
			// cancel order == 1
   		$cancel_order = $request->getParameter('cancel_order');

			if ($cancel_order)
			{
				// find out payment code and amount
				$packages_db = new Packages_Db();
				$res = $packages_db->find_payment_details_by_package($this->package_code);

				// refund actual payment - refund charge
				$payment_db = new Payment_Db();

				// adjust the amount to -X% from the original payment
				$res['amount'] *= (1 - sfConfig::get('app_package_refund_percentage') / 100);

				$ref_res = $payment_db->restCallForRefund($res['payment_code'], $res['trn_id'], $res['amount']);

				// update the state to cancelled
				$packages_db->set_courier_package_status($this->package_code, 'cancelled');

				// redirect to cancelled and history page
				$this->redirect('client/delivered');
			}
		}
		else
		{
			// find out of the time has passed to cancel
			$service_db = new Service_Db();
			if ($service_db->can_cancel_package_order($this->package_code))
				$this->can_cancel_package = true;

    	$packages_db  = new Packages_Db();
    	$this->info   = $packages_db->get_package_info($this->package_code);
    	$this->info['signed_by'] = str_replace('_', ' ', $this->info['signed_by']);
		}
	}


  public function executeGet_city_province_from_postal (sfWebRequest $request)
  {
    $postal_code = $request->getParameter('postal');
    $country_id  = $request->getParameter('country_id');

    if ($postal_code == "")
      return;

    $address_db = new Address_Db();
    $city_province = $address_db->get_city_province_from_postal($postal_code, $country_id);

    // return city and province id
    print json_encode(array('city' => $city_province[0], 'province' => $city_province[1]));
  }


}
