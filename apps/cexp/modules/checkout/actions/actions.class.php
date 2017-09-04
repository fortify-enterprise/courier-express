<?php

/**
 * checkout actions.
 *
 * @package    courierexpress
 * @subpackage checkout
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class checkoutActions extends sfActions
{

	public function preExecute ()
	{
		Tools_Lib::checkUnsupportedCountries();

    $this->dis        = Constants::emsize();
    $this->client_id  = $this->getUser()->getAttribute('client_id');

 	// shopping cart data
		$this->packages_cart = $this->getUser()->getAttribute('packages_cart');


		if (!$this->packages_cart)
			$this->redirect('main_page/index');

    if (!isset($this->client_id) || !$this->client_id)
      $this->redirect('register/client?in_process=1');


		$clients_db   = new Clients_Db();
    $profile_code = $clients_db->get_payment_profile_code($this->client_id);
    if (!$profile_code)
    {
      // redirect to payment settings to enter CC
      Tools_Lib::redirectSecurePage('client', 'payment', 'checkout_in_process=1');
    }

    // see if the url is ssl protected, if not go ssl
    if (!$this->getRequest()->isSecure())
      Tools_Lib::redirectSecurePage('checkout', 'index');

 	}


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(sfWebRequest $request)
  {

    // packages db

    $packages_db = new Packages_Db();

    // calculate the total price

    $price_calculator  = new Price_Calculator();
		$geo_locate = new Geo_Locate();
    foreach ($this->packages_cart as $id => $package)
    {
      $lat_lng_from = $geo_locate->latLngByAddress($package['sender']);
      $lat_lng_to   = $geo_locate->latLngByAddress($package['recep']);

      $amount = $price_calculator->calculate_price($package['couriers'][0]['id'], $lat_lng_from, $lat_lng_to, $package['PackageDetail']);
      list($partner_price, $partner_tax, $price, $tax) = $price_calculator->get_prices_list ($package['couriers'][0]['id'], $amount);
		
			$this->packages_cart[$id]['couriers'][0]['price']  = ($price+$tax);
      $this->total_price += ($price + $tax);
    }


    $this->getUser()->setAttribute('packages_cart', $this->packages_cart);


		// set the payment profile

		$payment_db = new Payment_Db();

		// query MangoDb!
		$mongo_db = new Mongo_Db();
		$this->mongo_profile_exists = $mongo_db->payment_profile_exists($this->client_id);
		if ($this->mongo_profile_exists)
			$this->payment_profile = $mongo_db->read_payment_profile($this->client_id);
		else
      Tools_Lib::redirectSecurePage('client', 'payment', 'checkout_in_process=1');
			
		//$this->payment_profile = $payment_db->restCallForQueryProfile($this->client_id);


		// set the items to be displayed before payment

    $current_page = $request->getParameter('page') ? $request->getParameter('page') : 1;

    $this->pager = new ArrayPager(null, 3); // pagination x per page
    $this->pager->setResultArray($this->packages_cart);
    $this->pager->setPage($current_page);
    $this->pager->init();

    $packages_db = new Packages_Db();
    $this->current_packages = $this->pager->getResults();
    $this->page_links = $this->pager->getLinks();
  }
}
