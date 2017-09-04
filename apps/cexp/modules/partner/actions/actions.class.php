<?php

/**
 * partner actions.
 *
 * @package    courierexpress
 * @subpackage partner
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class partnerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function preExecute()
  {
		Tools_Lib::checkUnsupportedCountries();

    $this->dis         = Constants::emsize();
    $this->client_id   = $this->getUser()->getAttribute('client_id');
    $this->courier_id  = $this->getUser()->getAttribute('courier_id');
		$this->couriers_db = new Couriers_Db();
		$this->username    = $this->getUser()->getAttribute('username');;
  }


  public function executeIndex(sfWebRequest $request)
  {
    $menu_list = sfYaml::load(sfConfig::get('sf_data_dir') . "/menus/navigation/partner.yml");
    $current_page = $request->getParameter('page') ? $request->getParameter('page') : 1;
    if ($menu_list)
    {
      $this->pager = new ArrayPager(null, 2); // pagination x per page
      $this->pager->setResultArray($menu_list);
      $this->pager->setPage($current_page);
      $this->pager->init();

      $this->menu = $this->pager->getResults();
      $this->page_links = $this->pager->getLinks();
    }
  }


	public function executePending(sfWebRequest $request)
	{
	}


	public function executeDelivered(sfWebRequest $request)
	{
	}


	public function executeAddress(sfWebRequest $request)
	{
    $client = Doctrine::getTable('Client')->find($this->client_id); 
    $this->form = new GenericAddressForm($client->Address);
    $this->form->addCSRFProtection($this->form->getCSRFToken());

    // get flash message
    $this->message = $this->getUser()->getFlash('message');
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('address'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('message', Tools_Lib::getSavedMessage());
        $this->redirect('partner/address');
      }
      /*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print $error . '<br />';
      }*/
    }
	}


	public function executeDetails(sfWebRequest $request)
	{
    // provinces list

    $client = Doctrine::getTable('Client')->find($this->client_id); 
    $this->form = new ClientDetailLoginForm($client->ClientDetail);
    $this->form->addCSRFProtection($this->form->getCSRFToken());

    $this->message = $this->getUser()->getFlash('message');
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('detail'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('message', Tools_Lib::getSavedMessage());
        $this->redirect('partner/details');
      }
      /*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print $error . '<br />';
      }*/
    }
	}


	public function executeAvailability(sfWebRequest $request)
	{
    $this->availability = $this->couriers_db->get_courier_availability($this->courier_id);

    if ($request->getParameter('is_available') == 'available')
    {
      $this->couriers_db->set_courier_availability($this->courier_id, 1);
      $this->availability = 1;
    }
    elseif ($request->isMethod('post'))
    {
      $this->couriers_db->set_courier_availability($this->courier_id, 0);
      $this->availability = 0;
    }

		if ($request->isMethod('post'))
      $this->message = Tools_Lib::getSavedMessage();
	}


	public function executeSurcharges(sfWebRequest $request)
	{
    $this->surcharges = $request->getParameter('surcharge');
    if ($request->isMethod('post'))
    {
      $this->couriers_db->set_courier_surcharges($this->courier_id, $this->surcharges);
      $this->message = Tools_Lib::getSavedMessage();
    }

    $this->surcharges = $this->couriers_db->get_courier_surcharges($this->courier_id);
	}


	public function executeDiscounts(sfWebRequest $request)
	{
    $discounts['discount_percentage'] = $request->getParameter('discount_percentage');

    if ($request->isMethod('post'))
    {
      $this->message = Tools_Lib::getSavedMessage();
      $this->couriers_db->set_courier_discount($this->courier_id, $discounts);
    }
    $this->discounts = $this->couriers_db->get_courier_discount($this->courier_id);
	}


	public function executeService_levels(sfWebRequest $request)
	{
		// check if courier does not have needed service levels
		// available add them

		$service_db = new Service_Db();
		if (!$service_db->all_courier_services_present($this->courier_id))
			$service_db->initialize_courier_with_services($this->courier_id);

    if ($request->isMethod('post'))
    {
    	$service_levels_in = $request->getParameter('service_levels');
      $this->message = Tools_Lib::getSavedMessage();
      $this->couriers_db->set_courier_service_levels($this->courier_id, $service_levels_in);
    }

    $service_levels = $this->couriers_db->get_service_levels($this->courier_id, 0);
    $this->ids      = $service_levels[0];
    $this->types    = $service_levels[1];
    $this->enabled  = $service_levels[2];
	}


	public function executeZones(sfWebRequest $request)
	{
		if ($request->isMethod('post'))
		{
    	// insert new zones
    	$this->zone_name    = $request->getParameter('zone_name');
   		$this->zone_id      = $request->getParameter('zone_list');
    	$this->is_new_zone  = $request->getParameter('is_new_zone');
    	$this->zone_type_id = $request->getParameter('zone_type');

    	// create new zone insert
    	if ($this->is_new_zone == 'on')
      	$this->couriers_db->insert_new_zone($this->zone_name, $this->courier_id, $this->zone_type_id);
    	// we update existing zone
    	else if ($this->zone_name != "")
			{
      	if ($this->zone_type_id != "")
          	$this->couriers_db->update_zone($this->zone_id, $this->zone_name, $this->zone_type_id);
    	}
			else if ($this->zone_id != "")
			{
      	$this->couriers_db->delete_zone($this->zone_id);
			}

      $this->message = Tools_Lib::getSavedMessage();
		}

		// end of inserting of new zones
   	$zone_list = $this->couriers_db->get_courier_zone_list($this->courier_id);
   	$this->zone_ids =   $zone_list[0];
   	$this->zone_names = $zone_list[1];
   	// get all zone types
   	$zone_types = $this->couriers_db->get_zone_types();
   	$this->zone_type_ids =   $zone_types[0];
   	$this->zone_type_names = $zone_types[1];
	}


	public function executeZone_elements(sfWebRequest $request)
	{
    $zone_list        = $this->couriers_db->get_courier_zone_list($this->courier_id);
    $this->zone_ids   = $zone_list[0];
    $this->zone_names = $zone_list[1];

    if ($request->isMethod('post'))
    {
    	$this->zone_id = $request->getParameter('zone_name');
    	$this->element = $request->getParameter('element');
    	$this->zone_element_list = $request->getParameter('zone_element_list');
    	$this->is_new_element    = $request->getParameter('is_new_element');

      $zone_elements = $this->couriers_db->get_zone_elements ($this->zone_id);
      $this->element_ids   = $zone_elements[0];
      $this->element_names = $zone_elements[1];

      // delete element
      if ($this->element == "" && $this->zone_element_list)
        $this->couriers_db->delete_zone_element($this->zone_element_list);
      elseif ($this->is_new_element)
        $this->couriers_db->insert_zone_element($this->zone_id, $this->element);
      else
        $this->couriers_db->update_zone_element($this->zone_element_list, $this->element);

      $this->message = Tools_Lib::getSavedMessage();
    }
	}


  public function executePrice_level (sfWebRequest $request)
  {
    $this->form = new PriceLevelForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('price_level'));
      if ($this->form->isValid())
      {
        $form_values = $this->form->getValues();
        $form_values['courier_id'] = $this->courier_id;

        // ..
        // insert or update
        if ($form_values['new_level'] == 'on')
          $this->couriers_db->insert_price_level($form_values);
        else
          $this->couriers_db->update_price_level($form_values);

        $this->redirect($this->getModuleName() . '/' . $this->getActionName() );
        ///thankyou?'.http_build_query($this->form->getValues()));
      }
    }

    // .. query the existing price levels
    $this->price_levels = $this->couriers_db->get_price_level_prices($this->courier_id);
  }



	public function executeZone_prices(sfWebRequest $request)
	{
    // create new zone insert
    if ($request->isMethod('post'))
		{
			// set zone prices
    	$this->service_level_id = $request->getParameter('service_level');
    	$this->from_zone_id     = $request->getParameter('from_zone');
    	$this->to_zone_id       = $request->getParameter('to_zone');
    	$this->price            = $request->getParameter('pair_price');

      $this->couriers_db->update_zones_price($this->courier_id, $this->from_zone_id, $this->to_zone_id, $this->service_level_id, $this->price);
      $this->message = Tools_Lib::getSavedMessage();
		}

    // set zone price
    // service levels for the courier
    $levels_list = $this->couriers_db->get_service_levels($this->courier_id);
    $this->level_ids   = $levels_list[0];
    $this->level_names = $levels_list[1];

    $zone_list = $this->couriers_db->get_courier_zone_list($this->courier_id);
    $this->zone_ids   = $zone_list[0];
    $this->zone_names = $zone_list[1];
	}


  public function process_upload_prices ($courier_id, $line)
  {
    // line looks like price [(3 Hour Rush)(zone1;zone2:15.00)]
    // get contents in [ .* ]
    preg_match("/^\s*price\s*\[(.*)\]/i", $line, $matches);
    $contents = $matches[1];

    // split contents by :
    $service_zones_price = preg_match("/\((.*)\)\s*\((.*)\)/", $contents, $matches);
    $service_type = trim($matches[1]);
    $zone_price   = trim($matches[2]);

    // get zones and get price
    $zones_price = preg_split('/:/', $zone_price);
    $zones = trim($zones_price[0]);
    $price = trim($zones_price[1]);

    // split zones by ;
    $zones_list = preg_split("/;/", $zones);

    // get zone 1 and zone 2 and price
		$service_db  = new Service_Db();


    $service_level_id = $service_db->get_service_level_id_from_type ($courier_id, $service_type);
    $first_zone_id    = $this->couriers_db->get_zone_id_by_name(trim($zones_list[0]));
    $second_zone_id   = $this->couriers_db->get_zone_id_by_name(trim($zones_list[1]));

		if ($service_level_id)
		{
    	// set zone price from zone 1 -> zone 2 with price
    	$this->couriers_db->update_zones_price($courier_id,
    	$first_zone_id,
    	$second_zone_id,
    	$service_level_id, $price);

    	// set zone price from zone 2 -> zone 1 with price
    	$this->couriers_db->update_zones_price($courier_id,
    	$second_zone_id,
    	$first_zone_id,
    	$service_level_id, $price);
		}
		else
		{
			throw new sfException("service_level_id is null");
		}
  }


  public function create_upload_zone ($courier_id, $line)
  {
    // line looks like zone1 (city: el1;el2;el3)
    // split contents by (
    $zone_type_elements = preg_split('/\(/', $line);

    if (!isset($zone_type_elements[1]))
			return;

    // left side is zone name
    $zone_name     = trim($zone_type_elements[0]);
    $type_elements = trim($zone_type_elements[1]);

    // get the contents in ^ .* )
    preg_match("/^(.*)\)/", $type_elements, $matches);
    $type_elements = trim($matches[1]);

    // split contents by :
    $type_elements_split = preg_split('/:/', $type_elements);

    // left side is type right side is elements
    $zone_type = trim($type_elements_split[0]);
    $elements  = trim($type_elements_split[1]);

    // splie elements by ;
    $elements_list = preg_split('/;/', $elements);


    // insert zone with zone type
    $zone_type_id = $this->couriers_db->get_zone_id_by_type ($zone_type);
    $zone_id      = $this->couriers_db->insert_new_zone ($zone_name, $courier_id, $zone_type_id);

    // loop throgh elements 
    foreach($elements_list as $element)
    {
       // every element do insert zone element
      $this->couriers_db->insert_zone_element ($zone_id, trim($element));
    }
    // end loop
  }


	public function executeZone_prices_upload(sfWebRequest $request)
	{
    // set zone price
    if ($request->isMethod('post'))
    {
			$fileName = "";
      foreach ($request->getFiles() as $uploadedFile)
	    {
        $uploadDir = sfConfig::get('sf_upload_dir');
				$fileName = $uploadedFile['tmp_name'];
			}

			// save a file	

			if ($fileName)
			{
     		// remove all current curier zones and prices for them
     		$this->couriers_db->remove_all_zone_prices($this->courier_id);

     		// loop through file
     		$file = fopen($fileName, "r") or exit("Unable to open file!");

      	// get current line
      	while(!feof($file))
      	{
        	$line = stream_get_line( $file, 4096, "\r\n");
        	// if line contains price(.*)
        	if (preg_match("/^\s*price\s*\[(.*)\]/i", $line))
           	$this->process_upload_prices($this->courier_id, $line);
        	else
          	$this->create_upload_zone($this->courier_id, $line);
     	 	}

		   	fclose($file);
    	 	// end loop

     		unlink($fileName);
     		$this->message = Tools_Lib::getSavedMessage();
			}
			else
				 $this->message = 'file was not uploaded';
		}
	}


	public function executeGet_zone_type(sfWebRequest $request)
	{
    $zone_type_id = $this->couriers_db->get_zone_type ($request->getParameter('zone_id'));
    print json_encode(array("zone_type_id" => $zone_type_id));
	}


	public function executeGet_zone_pricepair(sfWebRequest $request)
	{
    print $this->couriers_db->get_courier_service_level_price (
          $request->getParameter('service_level'),
          $request->getParameter('from_zone'),
          $request->getParameter('to_zone'));
	}


  public function executeFiregrid_received_data (sfWebRequest $request)
  {
    $packages_db = new Packages_Db();

		$q = Doctrine_Query::create();
    $q = $packages_db->get_courier_received_packages($this->courier_id, $q);
    $rows = $this->firegrid_get_request_filter ($request, $q);
    $this->firegrid_display_body ($rows, 'Please enter the name of the person\r\nwho signed the package', 'delivered');
  }


  public function executeFiregrid_delivered_data (sfWebRequest $request)
	{
    $packages_db = new Packages_Db();

		$q = Doctrine_Query::create();
    $q = $packages_db->get_courier_delivered_packages($this->courier_id, $q);
    $rows = $this->firegrid_get_request_filter ($request, $q);
    $this->firegrid_display_body ($rows, 'Please enter the reason\r\nwhy this package is being restored', 'restored');
	}


  function firegrid_get_request_filter ($request, $q)
  {
    $filter_sql = "";
    if ($request->getParameter('firescope_grid_filterCol') == 0 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
				$q->addWhere('p.package_code LIKE ?', '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if($request->getParameter('firescope_grid_filterCol') == 1 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
			$q->addWhere('(SELECT TRIM( CONCAT_WS(" ", add.apt_unit, add.street_number, add.street_name, add.city, add.postal_code)) FROM Address add WHERE add.id = p.from_address_id) LIKE ?',
			             '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if ($request->getParameter('firescope_grid_filterCol') == 2 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
			$q->addWhere('(SELECT TRIM(CONCAT_WS(" ", add.apt_unit, add.street_number, add.street_name, add.city, add.postal_code)) FROM Address add WHERE add.id = p.to_address_id) LIKE ?',
			             '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if ($request->getParameter('firescope_grid_filterCol') == 3 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
			$q->addWhere('company_name LIKE ?', '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if ($request->getParameter('firescope_grid_filterCol') == 4 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
			$q->addWhere('(p.amount * (1 - p.current_profit_cut / 100)) LIKE', '%'.$request->getParameter('firescope_grid_filterText')."%");


    if ($request->getParameter('firescope_grid_sortCol') == 0)
			$q->addOrderBy('p.package_code ' . $request->getParameter('firescope_grid_sortOrder'));

    else if ($request->getParameter('firescope_grid_sortCol') == 1)
			$q->addOrderBy('from_address ' . $request->getParameter('firescope_grid_sortOrder'));

    else if ($request->getParameter('firescope_grid_sortCol') == 2)
			$q->addOrderBy('to_address ' . $request->getParameter('firescope_grid_sortOrder'));

    else if ($request->getParameter('firescope_grid_sortCol') == 3)
			$q->addOrderBy('company_name ' . $request->getParameter('firescope_grid_sortOrder'));

    else if ($request->getParameter('firescope_grid_sortCol') == 4)
			$q->addOrderBy('(p.amount * (1 - p.current_profit_cut / 100))' . $request->getParameter('firescope_grid_sortOrder'));

    return $q->fetchArray();
  }


  function firegrid_display_body ($rows, $prompt, $action_type)
  {
    $total  = count($rows);

    $offset = ($_REQUEST['firescope_grid_page'] - 1) * $_REQUEST['firescope_grid_rows'];
    $rows   = array_slice($rows, $offset, $_REQUEST['firescope_grid_rows']);

    $output = '
    <br /><table class="white_text" style="text-transform: none">
        <tr>
          <th>Package code</th>
          <th>From address</th>
          <th>To address</th>
          <th>Company name</th>
          <th>Sale price</th>
          <th>Action</th>
        </tr>
    ';
		

    foreach ($rows as $row)
    {
			$package_datetime = $row['package_datetime'];

      $output .= '<tr>
      <td style="width: 27%;padding: 8px"><a class="tooltip" href="/services/package_details_tooltip/package_code/'.$row['package_code'].'"
      rel="/services/package_details_tooltip/package_code/'.$row['package_code'].'" title="Package details ('.$row['package_code'].')"

      style="text-decoration: none;border-bottom: 1px dashed; font-weight: normal">'.$row['package_code'].'</a>
			
			<br />
      <span style="font-weight: bold">'.date("h:iA - D d M Y", $package_datetime).'</span>
      </td>

      <td style="width: 20%;padding: 8px">'.$row['from_address'].'</td>
      <td style="width: 20%;padding: 8px">'.$row['to_address'].'</td>
      <td style="width: 20%;padding: 8px">'.$row['company_name'].'<br />'.$row['company_phone'].'</td>
      <td style="width: 20%;padding: 8px; font-weight: bold">$'.$row['partner_price'].' + '.$row['partner_tax'].' tax</td>
        <td>
        <button class="fg-button ui-state-default ui-corner-all" id="ID_'.$row['package_code'].'">';

        if ($action_type == 'delivered')
          $output .= 'Complete';
        else if ($action_type == 'restored')
          $output .= 'Restore';

        $output .= '</button>
        <script type="text/javascript">
        $("#ID_'.$row['package_code'].'").click(function (){

            // ask for recepient name who signed for the packages
            answer = prompt("'.$prompt.'", "");

            if (answer != null && answer != "")
            {
              // go ahead and set package status

              $.ajaxSetup({ cache: false });
              $.ajax({
                  type: "POST",
                  url:  "/partner/set_package_status/package_code/'.$row['package_code'];

                  if ($action_type == 'delivered')
                    $output .= '/status/delivered/signed_by/" + answer,';
                  else if ($action_type == 'restored')
                    $output .= '/status/paid/signed_by/Restore_Reason: " + answer,';

                  $output .= 'success: function(msg)
                  {}
              });

              $("#firegrid_refresh_button").click();
            }

        });
        </script>
      </td>
      </tr>';
    }

    $output .= '</table>
    <script type="text/javascript">
    $(document).ready(function() {

      $(".tooltip").cluetip({
				activation: "click",
				width: 450,
				height: 380,
				cursor: "pointer",
				arrows: true,
				closePosition: "title",
				sticky: true});

    });
    </script>
    ';

    print '<span id="firescope_grid_example_total" style="display:none">'.$total.'</span><span>'.$output.'</span>';
	}


  function executeSet_package_status (sfWebRequest $request)
  {
		$package_code = $request->getParameter('package_code');
		$status       = $request->getParameter('status');
		$signed_by    = $request->getParameter('signed_by');
		$packages_db  = new Packages_Db();
    $packages_db->set_courier_package_status($package_code, $status, $signed_by);
  }


  function executeGet_zone_elements (sfWebRequest $request)
  {
		$zone_id = $request->getParameter('zone_id');
		$zone_elements = $this->couriers_db->get_zone_elements ($zone_id);
    $ids = $zone_elements[0];
    $elements = $zone_elements[1];

    if (!$ids)
    {
      $ids = array('');
      $elements = array('');
    }

    print json_encode(array_combine($ids, $elements));
  }


  public function executePayment(sfWebRequest $request)
  {
    $address_db = new Address_Db();
    $clients_db = new Clients_Db();
    // fill in $this->province_ids, $this->province_names
    // fill in $this->country_ids, $this->country_names
    $address_db->init_province_country_ids ($this);


    if ($request->isMethod('post'))
    {
      $this->payment = $request->getParameter('payment');
      $this->message  = Tools_Lib::getSavedMessage();
      $clients_db->set_client_payment($this->client_id, $this->payment);
    }
		
    $this->payment = $clients_db->get_client_payment($this->client_id);
  }


	public function executeWeight_prices (sfWebRequest $request)
	{

	}

}
