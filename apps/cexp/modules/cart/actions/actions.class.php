<?php

/**
 * cart actions.
 *
 * @package    courierexpress
 * @subpackage cart
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class cartActions extends sfActions
{
	private $price_calculator = null;

	public function preExecute()
	{
		Tools_Lib::checkUnsupportedCountries();

    $this->price_calculator = new Price_Calculator();

    // load helper url
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

    // see if the url is ssl protected, if yes go non ssl
    if ($this->getRequest()->isSecure())
      Tools_Lib::redirectNonSecurePage('cart', 'index');

		// loop through the packages creating drop downs of companies that can ship
		// updating default drop down select of cheapest price courier
		// and attach the cheapest drop down array to each of the packages
		$this->packages_cart = $this->price_calculator->update_available_cart_prices(
			$this->getUser()->getAttribute('packages_cart')
		);
	}


	public function postExecute()
	{
		$this->getUser()->setAttribute('packages_cart', $this->packages_cart);
	}


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(sfWebRequest $request)
	{
		// update shopping cart

		$submitted = $request->getParameter('submitted');
		if ($request->isMethod('post') && $submitted)
		{
			$packages_to_remove = $request->getParameter('remove_packages');

			if (sizeof($packages_to_remove) > 0)
			{
				foreach ($packages_to_remove as $key => $value)
					unset($this->packages_cart[$value]);

			  $this->getUser()->setAttribute('packages_cart', $this->packages_cart);
			}

			// update delivery selections
			$courier_selections = array();
			$package_ids = array_keys($this->packages_cart);
			foreach ($package_ids as $package_id)
				$courier_selections[$package_id] = $request->getParameter('courier_selection_'.$package_id);
	
			$this->price_calculator->update_courier_selections ($courier_selections, $this->packages_cart);
			$this->message  = Tools_Lib::getSavedMessage();
		}


    // calculate total price
		$price_calculator = new Price_Calculator();
    $geo_locate = new Geo_Locate();

		if (!$this->packages_cart)
		{
			$this->total_price = 0;
		}
		else
		{
    	foreach ($this->packages_cart as $id => $package)
    	{
      	$lat_lng_from = $geo_locate->latLngByAddress($package['sender']);
      	$lat_lng_to   = $geo_locate->latLngByAddress($package['recep']);

				$low_price = 0;
				foreach ($this->packages_cart[$id]['couriers'] as $i => $courier)
				{
      		$amount = $price_calculator->calculate_price($package['couriers'][$i]['id'], $lat_lng_from, $lat_lng_to, $package['PackageDetail']);
      		list($partner_price, $partner_tax, $price, $tax) = $price_calculator->get_prices_list ($package['couriers'][$i]['id'], $amount);
					$this->packages_cart[$id]['couriers'][$i]['price'] = $price + $tax;

					if ($i == 0)
						$low_price = $price + $tax;
				}
				$this->total_price += $low_price;
    	}
		}



		// end of update code

		$current_page = $request->getParameter('page') ? $request->getParameter('page') : 1;

		//
		// set pagination

		if ($this->packages_cart)
		{
			$this->pager = new ArrayPager(null, sfConfig::get('app_pagination_cart'));
			$this->pager->setResultArray($this->packages_cart);
			$this->pager->setPage($current_page);
			$this->pager->init();

			$this->current_packages = $this->pager->getResults();
			$this->page_links = $this->pager->getLinks();
		}
	}


	// clear the shopping cart
	public function executeEmpty(sfWebRequest $request)
	{
		$this->getUser()->getAttributeHolder()->remove('packages_cart');
		$this->redirect('cart/index');
	}
}
