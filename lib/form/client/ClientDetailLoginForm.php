<?php

/**
 * Address form.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClientDetailLoginForm extends ClientDetailForm
{
  public function configure()
  {
    parent::configure();

		$client_id = sfContext::getInstance()->getUser()->getAttribute('client_id');
    if (!$client_id)
      return -1;

		// add login information
  	$client_obj_arr   = Doctrine::getTable('Client')->find($client_id)->toArray();
    $client_login_id  = $client_obj_arr['login_id'];
    $client_login_obj = Doctrine::getTable('ClientLogin')->find($client_login_id);
    $client_login_obj_arr = $client_login_obj->toArray();

    $client_login_obj->synchronizeWithArray($client_login_obj_arr);
    $login_form_obj = new ClientLoginForm($client_login_obj);

		$this->embedForm('ClientLogin', $login_form_obj);

		// set custom size for password fields
		$this->widgetSchema['ClientLogin']['password']->setAttribute('value', $client_login_obj_arr['password']);
		$this->widgetSchema['ClientLogin']['password_again']->setAttribute('value', $client_login_obj_arr['password']);

		$this->widgetSchema['ClientLogin']['password']->setAttribute('style', 'width: 200px');
		$this->widgetSchema['ClientLogin']['password_again']->setAttribute('style', 'width: 200px');

		// horizontal layout
    $this->widgetSchema['ClientLogin']->setFormFormatterName('Horizontal');
    $this->widgetSchema->setFormFormatterName('Horizontal');


		// set tab order
    $this->widgetSchema['details']->setAttribute('tabindex', 1);
    $this->widgetSchema['name']->setAttribute('tabindex', 2);
    $this->widgetSchema['phone']->setAttribute('tabindex', 3);
    $this->widgetSchema['contact']->setAttribute('tabindex', 4);
    $this->widgetSchema['email']->setAttribute('tabindex', 5);
    $this->widgetSchema['ClientLogin']['email']->setAttribute('tabindex', 6);
    $this->widgetSchema['ClientLogin']['password']->setAttribute('tabindex', 7);
    $this->widgetSchema['ClientLogin']['password_again']->setAttribute('tabindex', 8);
  }


  public function doSave($con = null)
  {
    $this->updateObject();
		
		// update the hash password
		$client_login = $this->embeddedForms['ClientLogin']->getObject();
    $client_login->setPasswordHash(Tools_Lib::getHash($client_login->getPassword()));
    $client_login->save();

		return parent::doSave($con);
  }


}
