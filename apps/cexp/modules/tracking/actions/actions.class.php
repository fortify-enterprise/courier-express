<?php

/**
 * tracking actions.
 *
 * @package    courierexpress
 * @subpackage tracking
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class trackingActions extends sfActions
{
	public function preExecute ()
	{
		$this->dis = Constants::emsize();
		$this->packages_cart = $this->getUser()->getAttribute('packages_cart');
	}

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	  // process tracking request
	
    $this->form = new TrackingForm();
    $this->form->addCSRFProtection($this->form->getCSRFToken());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('tracking'));
      if ($this->form->isValid())
      {
        $form_values = $this->form->getValues();
        // get the tracking intofmration
        $tracking_db            = new Tracking_Db();
			  $this->shipment_number  = $form_values['shipment_number'];
        $this->tracked_packages = $tracking_db->get_tracking_info($this->shipment_number);
			  $this->getUser()->setAttribute('tracked_packages', $this->tracked_packages);
			  $this->getUser()->setAttribute('track_number', $this->track_number);
      }
    }


    // prepopulate

		// get tracked packages from session
		if (!$this->tracked_packages)
			$this->tracked_packages = $this->getUser()->getAttribute('tracked_packages');

		if (!$this->track_number)
			$this->track_number = $this->getUser()->getAttribute('track_number');

		// paginate tracked packages
    $current_page = $request->getParameter('page') ? $request->getParameter('page') : 1;

		//
		// set pagination

		if ($this->tracked_packages)
		{
    	$this->pager = new ArrayPager(null, sfConfig::get('app_pagination_tracking'));
   		$this->pager->setResultArray($this->tracked_packages);
    	$this->pager->setPage($current_page);
    	$this->pager->init();

    	$packages_db = new Packages_Db();
    	$this->tracked_packages = $this->pager->getResults();
    	$this->page_links = $this->pager->getLinks();
  	}
	}
}
