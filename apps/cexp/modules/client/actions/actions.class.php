<?php

/**
 * client actions.
 *
 * @package    courierexpress
 * @subpackage client
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class clientActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function preExecute()
  {
		Tools_Lib::checkUnsupportedCountries();

    $this->dis        = Constants::emsize();
		$this->clients_db = new Clients_Db();
		$this->client_id  = $this->getUser()->getAttribute('client_id');

		if (!$this->clients_db->client_exists($this->client_id))
		{
			$logins_db = new Logins_Db();
			$logins_db->execute_logout($this, 'auth/index');
		}

    // shopping cart data
		$this->packages_cart = $this->getUser()->getAttribute('packages_cart');
  }


  public function executeContact_information ($request)
  {
		$client_id = $request->getParameter('client_id');

    if (!$client_id)
      return;

    $address_db = new Address_Db();
    print json_encode($address_db->get_contact_information($client_id));
  }


  function executeFetch_name_address ($request)
  {
		$client_id = $request->getParameter('username');
		$name      = $request->getParameter('name');

    if (!$client_id || !$name)
		{
			print "";
      return;
		}

    $address_db = new Address_Db();
    $packages_db = new Packages_Db();
    $name = str_replace('_', ' ', $name);

    $address = $address_db->normalize_province_id($packages_db->get_address_from_contact_name($client_id, $name));
		print json_encode($address);
  }


  public function executeIndex(sfWebRequest $request)
  {
    $menu_list = sfYaml::load(sfConfig::get('sf_data_dir') . "/menus/navigation/client.yml");
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
        $this->redirect('client/details');
      }
      /*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print $error . '<br />';
      }*/
    }
	}


	public function executePayment(sfWebRequest $request)
	{
		$this->in_process = $request->getParameter('in_process');
		$this->checkout_in_process = $request->getParameter('checkout_in_process');
   	$profile_code     = $this->clients_db->get_payment_profile_code($this->client_id);
    if ($profile_code && isset($in_process))
    {
      // redirect to payment settings to enter CC
      Tools_Lib::redirectSecurePage('payment', 'index', 'in_process=1');
    }

		// initialize the form
    $this->form = new ClientPaymentDetailForm();
    $this->form->addCSRFProtection($this->form->getCSRFToken());

		// set the message
		$this->message = $this->getUser()->getFlash('message');

    // get flash message
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('payment'));
      if ($this->form->isValid())
      {
        $this->form->save();
				$this->resp = $this->form->getLastResponse();

				$this->message = $this->resp['responseMessage'];
				if ($this->resp['responseCode'] == 1)
					$this->message .= "<br />" . Tools_Lib::getSavedMessage();
				else
					$this->message .= "<br />" . $this->resp['errorMessage'];
				
				$this->getUser()->setFlash('message', $this->message);

				if ($this->checkout_in_process)
					$this->redirect('checkout/index');
				else
					$this->redirect('client/payment');
      }
      /*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print $error . '<br />';
      }*/
    }

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
        $this->redirect('client/address');
      }
      /*else
      {
        foreach ($this->form->getErrorSchema() as $error)
          print $error . '<br />';
      }*/
    }
	}


  public function executeFiregrid_received_data (sfWebRequest $request)
  {
    $packages_db = new Packages_Db();

    $q = Doctrine_Query::create();
    $q = $packages_db->get_client_received_packages($this->client_id, $q);
    $rows = $this->firegrid_get_request_filter ($request, $q);
    $this->firegrid_display_body ($rows, true);
  }


  public function executeFiregrid_delivered_data (sfWebRequest $request)
  {
    $packages_db = new Packages_Db();

    $q = Doctrine_Query::create();
    $q = $packages_db->get_client_delivered_and_cancelled_packages($this->client_id, $q);
    $rows = $this->firegrid_get_request_filter ($request, $q);
    $this->firegrid_display_body ($rows);
  }


  function firegrid_get_request_filter ($request, $q)
  {
    $filter_sql = "";
    if ($request->getParameter('firescope_grid_filterCol') == 0 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
        $q->addWhere('p.package_code LIKE ?', '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if($request->getParameter('firescope_grid_filterCol') == 1 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
      $q->addWhere('(SELECT TRIM(CONCAT_WS(" ", add.apt_unit, add.street_number, add.street_name, add.city, add.postal_code)) as text_address FROM Address add WHERE add.id = p.from_address_id) LIKE ?',
                   '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if ($request->getParameter('firescope_grid_filterCol') == 2 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
      $q->addWhere('(SELECT TRIM( CONCAT_WS(" ", add.apt_unit, add.street_number, add.street_name, add.city, add.postal_code)) as text_address FROM Address add WHERE add.id = p.to_address_id) LIKE ?',
                   '%'.$request->getParameter('firescope_grid_filterText')."%");

    else if ($request->getParameter('firescope_grid_filterCol') == 3 && strlen($request->getParameter('firescope_grid_filterText')) > 0)
      $q->addWhere('comapny_name LIKE ?', '%'.$request->getParameter('firescope_grid_filterText')."%");

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


  function firegrid_display_body ($rows, $pending_orders = false)
  {
    $total  = count($rows);

    $offset = ($_REQUEST['firescope_grid_page'] - 1) * $_REQUEST['firescope_grid_rows'];
    $rows   = array_slice($rows, $offset, $_REQUEST['firescope_grid_rows']);

    $output = '
    <br /><table class="white_text" style="font-family: Arial;text-transform: none">
        <tr>
          <th>Package code</th>
          <th>From address</th>
          <th>To address</th>
          <th>Delivery company</th>
          <th>Status</th>
        </tr>
    ';

    foreach ($rows as $row)
    {
			$package_datetime = $row['package_datetime'];

      $output .= '<tr>
      <td style="width: 25%;padding: 8px"><a class="tooltip" href="/services/package_details_tooltip/package_code/'.$row['package_code'].'/pending_orders/'.$pending_orders.'"
      rel="/services/package_details_tooltip/package_code/'.$row['package_code'].'/pending_orders/'.$pending_orders.'" title="Package details ('.$row['package_code'].')"

      style="text-decoration: none; border-bottom: 1px dashed; font-weight: normal">'.$row['package_code'].'</a>
			<br />
			<div style="font-weight: bold">'.date("h:iA - D d M Y", $package_datetime).'</div>
			</td>

      <td style="width: 20%;padding: 8px">'.$row['from_address'].'</td>
      <td style="width: 20%;padding: 8px">'.$row['to_address'].'</td>
      <td style="width: 20%;padding: 8px">'.$row['company_name'].'<br />'.$row['company_phone'].'</td>
      <td style="width: 15%;padding: 8px; font-weight: bold">'.ucwords($row['status']).'</td>
      </tr>';
    }

    $output .= '</table>
    <script type="text/javascript">
    $(document).ready(function() {

      $(".tooltip").cluetip(
				{
					activation: "click",
				 	width: 450,
				 	height: 380,
				 	cursor: "pointer",
				 	arrows: true,
				 	closePosition: "title",
				 	sticky: true
			 });

    });
    </script>
    ';
    print '<span id="firescope_grid_example_total" style="display:none">'.$total.'</span><span>'.$output.'</span>';
	}
}
