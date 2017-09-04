<?php

/**
 * ClientPaymentDetail form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientPaymentDetailForm extends BaseClientPaymentDetailForm
{
  public function configure()
  {
   	$payment_db = new Payment_Db();
		$address_db = new Address_Db();

		$exp_months = array(
			'01' => '01',
			'02' => '02',
			'03' => '03',
			'04' => '04',
			'05' => '05',
			'06' => '06',
			'07' => '07',
			'08' => '08',
			'09' => '09',
			'10' => '10',
			'11' => '11',
			'12' => '12');

		$exp_years  = array ();
		$start_year = date("y");
		$end_year   = $start_year + 12;

		for ($i = $start_year; $i < $end_year; $i++)
			$exp_years[$i] = '20' . $i;

		$exp_month_def = 01;
		$exp_year_def  = 10;
		$country_id    = 1;



		$res = array();
		$client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
		if (!$client_id)
			return -1;

	

		$mongo_profile_exists = true;

		// initialize address and credit card type obj
		$address_obj = Doctrine::getTable('Client')->findOneById($client_id)->Address;
		$credit_card_type_obj = null;
		

   	// query profile data MongoDB!
	  $mongo_db = new Mongo_Db();
		$mongo_profile_exists = $mongo_db->payment_profile_exists($client_id);


		if (! $mongo_profile_exists )
		{

			// query profile beanstream
   		$res = $payment_db->restCallForQueryProfile($client_id);

			if (isset($res->responseCode) && $res->responseCode == 1)
			{
				// split the expiration month and year
				$exp_month_def = substr($res->trnCardExpiry, 0, 2);
				$exp_year_def  = substr($res->trnCardExpiry, 2, 4);
				// get country name by code2
				$country_id = $address_db->get_country_id_by_code($res->ordCountry);

				$address_obj_arr = $address_obj->toArray();

				// get provinces id for country name and province code2
				$province_id = @$address_db->get_province_id_by_country_and_code($country_id, $res->ordProvince);

				// populate province_state
				$country_name = $address_db->get_country_by_id($country_id);

				$address_obj_arr['postal_code'] = @$res->ordPostalCode;
				$address_obj_arr['city'] = @$res->ordCity;
				$address_obj_arr['country_id'] = @$country_id;
				$address_obj_arr['province_state_id'] = @$province_id;
				$address_obj_arr['Country']['id'] = @$country_id;
				$address_obj_arr['Country']['name'] = @$country_name;

				$address_obj->synchronizeWithArray($address_obj_arr);
	
				// fill in credit card types
				$credit_card_type_obj = @Doctrine::getTable('CreditCardType')->find($payment_db->get_card_id_by_abbr($res->cardType));

			}
		}
		else
		{
			$res = $mongo_db->read_payment_profile($client_id);

			$country_id = $res['Address']['Country']['id'];
			$address_obj['postal_code'] = $res['Address']['postal_code'];
			$address_obj['city'] = $res['Address']['city'];
			$address_obj['province_state_id'] = $res['Address']['province_state_id'];
			$address_obj['province_id'] = $res['Address']['Province']['id'];
			$address_obj['state_id'] = $res['Address']['State']['id'];
			$address_obj['country_id'] = $country_id;
			$exp_month_def = $res['exp_month'];
			$exp_year_def  = $res['exp_year'];

			$credit_card_type_obj = @Doctrine::getTable('CreditCardType')->find($res['CreditCardType']['id']);
		}



    $this->setWidgets(array(
      'card_number' => new sfWidgetFormInputText(array(), array('style' => 'width: 200px')),

			'exp_month' => new sfWidgetFormSelect(array(
										 'choices' => $exp_months,
										 'default' => $exp_month_def),
										 array('style' => 'width: 100px')
										 ),
	
      'exp_year' => new sfWidgetFormSelect(array(
										 'choices' => $exp_years,
										 'default' => $exp_year_def),
										 array('style' => 'width: 100px')
			               ),

      'ccv_number' => new sfWidgetFormInputText(array(), array('style' => 'width: 100px')),
      'name' => new sfWidgetFormInputText(array(), array('style' => 'width: 200px')),
      'address1' => new sfWidgetFormInputText(array(), array('style' => 'width: 200px')),
      'address2' => new sfWidgetFormInputText(array(), array('style' => 'width: 200px')),
      'is_default' => new sfWidgetFormInputText(array(), array('style' => 'width: 120px')),
    ));

		

    $this->widgetSchema->setLabels(array(
      'card_number' => 'Number',
      'exp_month' => 'Exp. month',
      'exp_year' => 'Exp. year',
      'ccv_number' => 'CCV number',
      'name' => 'Owner name',
      'address1' => 'Address 1',
      'address2' => 'Address 2',
      'is_default' => 'Use as default payment',
   ));

   $this->getWidgetSchema()->setHelps(array(
      'card_number' => 'Credit card number: ex. 4417123456789112',
      'exp_month' => 'Credit card expiration month',
      'exp_year' => 'Credit card expiration year',
      'ccv_number' => 'CCV number',
      'name' => 'Credit card holer name',
      'address1' => 'Address 1 field: ex. 2343',
      'address2' => 'Address 2 field: ex. 4377 Pacific Ave',
      'is_default' => 'Use as default payment',
    ));

    $this->setValidators(array(

      'card_number' => new sfValidatorAnd(array(
        new sfValidatorString(array('min_length' => 16, 'max_length' => 19), array(
          'required'   => 'Credit card number is required',
          'min_length' => 'Card must be at least %min_length% characters.',
          'max_length' => 'Card must not exceed %max_length% characters.',
        )),
        new sfValidatorCreditCard(),
      )),


      'exp_month' => new sfValidatorChoice(array('choices' => array_keys($exp_months))),
      'exp_year' => new sfValidatorChoice(array('choices' => array_keys($exp_years))),

      'ccv_number' => new sfValidatorString(array('required' => false,
			'min_length' => 2, 'max_length' => 10), array(
        'min_length' => 'CCV must be at least %min_length% characters.',
        'max_length' => 'CCV must not exceed %max_length% characters.',
      )),

      'name' => new sfValidatorString(array('min_length' => 1, 'max_length' => 100), array(
        'required'   => 'Owner name is required',
        'min_length' => 'Minimum name must be at least %min_length% characters.',
        'max_length' => 'Maximum name length must not exceed %max_length% characters.',
      )),

      'address1' => new sfValidatorString(array('min_length' => 1, 'max_length' => 50), array(
        'required'   => 'Address 1 field is required',
        'min_length' => 'Address 1 field must be at least %min_length% characters.',
        'max_length' => 'Address 1 must not exceed %max_length% characters.',
      )),

      'address2' => new sfValidatorString(array('required' => false,
			'min_length' => 2, 'max_length' => 100), array(
        'min_length' => 'Address 2 field must be at least %min_length% characters.',
        'max_length' => 'Address 2 field must not exceed %max_length% characters.',
      )),

      'is_default' => new sfValidatorString(array('required' => false,
			'min_length' => 5, 'max_length' => 10), array(
        'min_length' => 'Postal code must be at least %min_length% characters.',
        'max_length' => 'Postal code must not exceed %max_length% characters.',
      )),

		));

	

		
		// add embeded forms
		$address_form_obj = new AddressForm($address_obj);
		$this->embedForm('Address', $address_form_obj);

		$credit_card_type_from_obj = new CreditCardTypeForm($credit_card_type_obj);
		$this->embedForm('CreditCardType', $credit_card_type_from_obj);



    $this->widgetSchema['card_number']->setAttribute('tabindex', 1);
    $this->widgetSchema['exp_month']->setAttribute('tabindex', 2);
    $this->widgetSchema['exp_year']->setAttribute('tabindex', 3);
    $this->widgetSchema['ccv_number']->setAttribute('tabindex', 4);
    $this->widgetSchema['name']->setAttribute('tabindex', 5);
    $this->widgetSchema['address1']->setAttribute('tabindex', 6);
    $this->widgetSchema['address2']->setAttribute('tabindex', 7);
    $this->widgetSchema['Address']['Country']['id']->setAttribute('tabindex', 8);
    $this->widgetSchema['Address']['postal_code']->setAttribute('tabindex', 9);
    $this->widgetSchema['Address']['city']->setAttribute('tabindex', 10);
    $this->widgetSchema['Address']['province_state_id']->setAttribute('tabindex', 11);

    $this->validatorSchema['Address']['street_number']->setOption('required', false);
    $this->validatorSchema['Address']['street_name']->setOption('required', false);
    $this->validatorSchema['CreditCardType']['type']->setOption('required', false);

    // update the profile
		if (! $mongo_profile_exists )
		{
			if (sizeof($res) > 1)
			{
    		$this->widgetSchema['name']->setAttribute('value', $res->trnCardOwner);
    		$this->widgetSchema['card_number']->setAttribute('value', $res->trnCardNumber);
    		$this->widgetSchema['address1']->setAttribute('value', $res->ordAddress1);
    		$this->widgetSchema['address2']->setAttribute('value', $res->ordAddress2);
    		$this->widgetSchema['Address']['province_state_id']->setAttribute('tabindex', 11);
			}
		}
		else
		{
			if (sizeof($res) > 1)
			{
				// load MangoDb
	    	$this->widgetSchema['name']->setAttribute('value', $res['name']);
    		$this->widgetSchema['card_number']->setAttribute('value', $res['card_number']);
    		$this->widgetSchema['address1']->setAttribute('value', $res['address1']);
    		$this->widgetSchema['address2']->setAttribute('value', $res['address2']);
    		$this->widgetSchema['Address']['province_state_id']->setAttribute('tabindex', 11);
			}
		}
		// allow more relations

    $this->widgetSchema->setFormFormatterName('Vertical');
		$this->enableLocalCSRFProtection();
    $this->widgetSchema->setNameFormat('payment[%s]');
  }


  protected function doSave($con = null)
	{
		// update the changes on beanstream side
    $this->updateObject();
		$values = $this->getValues();

		$client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
		if (!$client_id)
			return -1;

    $address = $this->embeddedForms['Address']->getObject()->toArray();
    $credit_card_type = $this->embeddedForms['CreditCardType']->getObject()->toArray();
	
		$payment_db   = new Payment_Db();
		$profile_code = null;
		
		if (Doctrine::getTable('Client')->findOneById($client_id)->ClientPaymentDetail)
			$profile_code = Doctrine::getTable('Client')->findOneById($client_id)->ClientPaymentDetail->getProfileCode();
	
		// check if the payment profile exists if no create
		if (!$profile_code || $profile_code == '')
		{
			$profile_response = $payment_db->restCallForCreateProfile($client_id, $values);

			// if new or duplicate profile info
      if ($profile_response['responseCode'] == 1 || $profile_response['responseCode'] == 17)
      {
        // save profile code to the database
				$clients_db = new Clients_Db();
        $clients_db->set_payment_profile_code(
						$client_id,
						$profile_response['customerCode'],
						$values['CreditCardType']['id']);


				// save profile data to MangoDB!
				$mongo_db = new Mongo_Db();
				$mongo_db->create_payment_profile($client_id, $values);


				// set response message
				$this->response['responseMessage'] = ($profile_response['responseCode'] == 17) ? "Operation Successfull" : $profile_response['responseMessage'];
      }
			else
			{
				$this->response['responseMessage'] =
				$profile_response['responseMessage'] . '<br />' .
				$profile_response['errorMessage'] . '<br />' .
				$profile_response['errorFields'];
			}
		}
		else
		{
			// if payment profile exists update
			$this->response = $payment_db->restCallForUpdateProfile($client_id, $values);

			// update profile data to MangoDB!
			$mongo_db = new Mongo_Db();
			$values['card_number'] = substr_replace($values['card_number'], 'XXXXXX', 6, -4);
			$mongo_db->update_payment_profile($client_id, $values);
		}
	}


	public function getLastResponse ()
	{
		return $this->response;
	}
}
