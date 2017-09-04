<?php

/**
 * Client form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class NewPackageForm extends PackageForm
{
  public function configure()
  {
    parent::configure();

    // set tabs
		
		$this->widgetSchema['PackageDetail']['DeliveryType']['id']->setAttribute('tabindex', 1);
		$this->widgetSchema['PackageDetail']['ServiceLevelType']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['ready_date']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['ready_time']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['sender_contact']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['sender_phone']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['apt_unit']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['street_number']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['street_name']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['Country']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['postal_code']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['city']->setAttribute('tabindex', 2);
		$this->widgetSchema['sender']['Province']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['contact']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['phone']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['apt_unit']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['street_number']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['street_name']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['Country']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['postal_code']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['city']->setAttribute('tabindex', 2);
		$this->widgetSchema['recep']['Province']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['PackageType']['id']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['weight']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['weight_type_id']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['num_pieces']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['reference']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['round_trip']->setAttribute('tabindex', 2);
		$this->widgetSchema['PackageDetail']['instructions']->setAttribute('tabindex', 2);
    

    // set validators
    $this->validatorSchema['amount']->setOption('required', false);
    $this->validatorSchema['current_profit_cut']->setOption('required', false);
    $this->validatorSchema['package_code']->setOption('required', false);


    $this->validatorSchema['PackageDetail']['signed_by']->setOption('required', false);
    $this->validatorSchema['PackageDetail']['last_updated']->setOption('required', false);
    $this->validatorSchema['PackageDetail']['num_pieces']->setOption('required', false);

    $this->validatorSchema['PackageStatus']['status']->setOption('required', false);


    // disable the sender fields
    foreach ($this->validatorSchema['sender']['Country']->getFields() as $key => $value)
      $this->validatorSchema['sender']['Country'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['sender']['Province']->getFields() as $key => $value)
      $this->validatorSchema['sender']['Province'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['sender']['State']->getFields() as $key => $value)
      $this->validatorSchema['sender']['State'][$key]->setOption('required', false);

    $this->validatorSchema['sender']['Country']['id']->setOption('required', true);
    
    // disable the recepient fields
    foreach ($this->validatorSchema['recep']['Country']->getFields() as $key => $value)
      $this->validatorSchema['recep']['Country'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['recep']['Province']->getFields() as $key => $value)
      $this->validatorSchema['recep']['Province'][$key]->setOption('required', false);

    foreach ($this->validatorSchema['recep']['State']->getFields() as $key => $value)
      $this->validatorSchema['recep']['State'][$key]->setOption('required', false);

    $this->validatorSchema['recep']['Country']['id']->setOption('required', true);
    

   	// implement custom validation logic
   	// add a post validator

    $this->widgetSchema->setNameFormat('package[%s]');
    $this->widgetSchema->setFormFormatterName('Vertical');
		$this->enableLocalCSRFProtection();
  }


  public function canBeDelivered($values)
  {
   	// if package deliverable
    $packages_db = new Packages_Db();
		$package = sfContext::getInstance()->getRequest()->getParameter('package');


		if (!isset($values['PackageDetail']['ServiceLevelType']['id']))
			$values = $package;

		return $packages_db->can_be_delivered($values);
  }


}
