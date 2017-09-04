<?php

/**
 * main_page actions.
 *
 * @package    courierexpress
 * @subpackage main_page
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class main_pageActions extends sfActions
{

  public function preExecute()
  {
		Tools_Lib::checkUnsupportedCountries();

    // load helper url
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    sfContext::getInstance()->getConfiguration()->loadHelpers('Debug');

    // see if the url is ssl protected, if yes go no ssl
    if ($this->getRequest()->isSecure())
		{
			$edit_package = "";
			$edit_package_param = $this->getRequestParameter('edit_package');
			if ($edit_package_param)
				$edit_package = 'edit_package=' . $edit_package_param;
			Tools_Lib::redirectNonSecurePage('main_page', 'index', $edit_package);
		}

    $this->dis           = Constants::emsize();
    $this->client_id     = $this->getUser()->getAttribute('client_id');
    $this->packages_cart = $this->getUser()->getAttribute('packages_cart');
    $this->edit_package  = $this->getRequestParameter('edit_package');
    $this->csrf = '';
  }


 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new NewPackageForm();
    $this->csrf = $this->form->getCSRFToken();
    $this->form->disableCSRFProtection();

    // find out the edit code if editing package
    $this->initialize_page();

    // process request
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('package'));

      if ($this->form->isValid())
      {
				// check if package can be delivered
				if (!$this->form->canBeDelivered($this->form->getValues()))
				{
					// set the data into the unable to deliver table
					$ud = new UnableToDeliver();
					$ud->package_data = print_r($this->form->getValues(),1);
					$ud->attempted_on = date( 'Y-m-d H:i:s', time());
					$ud->save();


					// notify through email that package could not be delivered
    			// send email message for account details
			    $emailer_db = new Emailer_Db();
			    $emailer_db->send_email
			      ($this, sfConfig::get('app_email_support'), 'Unable to deliver package', 'email', 'unable_to_deliver',
						 array('package_data' => $ud->package_data, 'attempted_on' => $ud->attempted_on));


					// display message to the user can not deliver at this time
					$this->cant_be_delivered = 'The package is undeliverable, currently we do not service the area chosen<br />
					Our support team is notified, and is constantly working on expanding service coverage';
				}
				else
				{

        	$packages_db = new Packages_Db();
        	$package_id  = '';

        	if ($this->edit_package)
          	$package_id = $this->edit_package;
        	else
        	{
          	$this->settings_db = new Settings_Db();
          	$settings_info     = $this->settings_db->get_settings_info();
          	$package_id        = $packages_db->generate_alphanumeric_code($settings_info['package_id_length']);

			    	if (sfContext::hasInstance())
		  	    	sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '() new package id ' . $package_id);

            if (!$package_id || $package_id == -1)
              throw new sfException("package_id $package_id is not generated correctly");
        	}
  
        	$package = $this->form->getValues();
        	unset($package['Client']);

			    if (sfContext::hasInstance())
		  	    sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . '() finding couriers for package ' . $package_id);

					$couriers_db = new Couriers_Db();
    			$price_calculator  = new Price_Calculator();
					$couriers = $couriers_db->get_couriers_info(1, 1, false, true);
					$this->packages_cart[$package_id] = $price_calculator->update_available_package_prices($couriers, $package);

        	// set package to cart
        	$this->getUser()->setAttribute('packages_cart', $this->packages_cart);

        	if ($this->edit_package)
						$this->redirect('main_page/index?edit_package=' . $this->edit_package);
					else
          	$this->redirect('main_page/index');
      	}
			}
     	/*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print  '<pre>'.$error.'</pre>' ;//. '<br />\n';
      }*/

    }
    // update packages cart
    else if ($this->edit_package && isset($this->packages_cart[$this->edit_package]))
    {
      $this->packages_cart[$this->edit_package]['_csrf_token'] =  $this->csrf;
			unset($this->packages_cart[$this->edit_package]['couriers']);
      $this->form->bind($this->packages_cart[$this->edit_package]);
    }
  }


  public function initialize_page()
  {
    $service_db = new Service_Db();

    // fill in username
    
    if ($this->getUser()->getAttribute('username'))
      $this->userid_enc = $service_db->get_id_from_username($this->getUser()->getAttribute('username'));

    // calculate the total price
    $price_calculator  = new Price_Calculator();
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

    		$amount = $price_calculator->calculate_price($package['couriers'][0]['id'], $lat_lng_from, $lat_lng_to, $package['PackageDetail']);

				list($partner_price, $partner_tax, $price, $tax) = $price_calculator->get_prices_list ($package['couriers'][0]['id'], $amount);
				$this->total_price += ($price + $tax);
			}
		}

    // start of pagination code

    $current_page = $this->getRequestParameter('page') ? $this->getRequestParameter('page') : 1;
    if ($this->packages_cart)
    {
      // pagination x per page
      $this->pager = new ArrayPager(null, sfConfig::get('app_pagination_main_page'));
      $this->pager->setResultArray($this->packages_cart);
      $this->pager->setPage($current_page);
      $this->pager->init();

      $this->current_packages = $this->pager->getResults();
      $this->page_links = $this->pager->getLinks();
    }
  }


  // update package in shopping cart

  public function executeUpdate(sfWebRequest $request)
  {
    // get package id which to update

    if (!$this->edit_package)
      return -1;

    // check if we can deliver updated package

    $packages_db = new Packages_Db();
    if ($packages_db->can_be_delivered($this->package_cart[$this->edit_package]))
    {
      $this->packages_cart[$this->edit_package] = $this->form->getValues();
      $this->getUser()->setAttribute('packages_cart', $packages_cart);
      unset($this->packages_cart[$this->edit_package]['courier_id']);

      $this->getUser()->setAttribute('packages_cart', $this->packages_cart);
      $this->redirect(url_for('main_page/index') . '?edit_package=' . $this->edit_package);
    }
    else
    {
      $this->redirect(url_for('main_page/non_deliverable') . '?edit_package=' . $this->edit_package);
    }
  }


  public function executeAutocomplete_address_by_name($request)
  {
    $query_str = $request->getParameter('query_str');
    if (!$this->client_id)
		{
			print "";
      return;
		}

    $clients_db = new Clients_Db();
    print $clients_db->get_contacts_for_client($this->client_id, $query_str);
		exit; // to prevent view
  }


  public function executeNon_deliverable (sfWebRequest $request)
  {
    $this->edit_package = $request->getParameter('edit_package');
  }
}

?>
