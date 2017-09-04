<?php

/**
 * form actions.
 *
 * @package    cexp
 * @subpackage form
 * @author     Courier Express
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class formActions extends sfActions
{

	public function preExecute()
	{
		$this->client_id   = $this->getUser()->getAttribute('client_id');
	}

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(sfWebRequest $request)
  {
  }


  public function executeThankyou(sfWebRequest $request)
  {
    switch($request->getParameter('template'))
    {
      case 'register':
        $this->message = 'Thank you for registering with Courier Express.<br />You will receive an email shortly, with your registration details';
        $this->link = array('title' => 'continue to you home page &rarr;', 'url' => 'client/index');
      break;

      case 'payment':
        $this->message = 'Thank you for the order! you will receive confirmation email shortly';
        $this->link = array('title' => 'continue to pending orders &rarr;', 'url' => 'client/pending');
      break;

      case 'payment_error':
				$error = $request->getParameter('error_message');
        $this->message = 'Unfortunately your order has not been processed because of the following error:<br />' . $error;
        $this->link = array('title' => 'back to checkout page', 'url' => 'checkout/index');
      break;
      
      default:
        $this->message = 'Thank you for submiting a form, we will contact you within 24 hours';
        $this->link = array('title' => 'back to main page', 'url' => 'main_page/index');
      break;
    }
  }


  public function executeFeedback(sfWebRequest $request)
  {
    $this->form = new FeedbackContactForm();
		$this->form->addCSRFProtection($this->form->getCSRFToken());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        $form_values = $this->form->getValues();
				$this->sendContactInfo($form_values);
        $this->redirect('form/thankyou');
      }
    }
  }


  public function executeEnquiry(sfWebRequest $request)
  {
		$client = Doctrine::getTable('Client')->find($this->client_id);
		$client_detail = ($client) ? $client->ClientDetail : null;
    $this->form = new EnquiryContactForm($client_detail);
		$this->form->addCSRFProtection($this->form->getCSRFToken());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        $form_values = $this->form->getValues();
				$this->sendContactInfo($form_values);
        $this->redirect('form/thankyou');
      }
    }
  }


  public function executePartner_contact(sfWebRequest $request)
  {
    $this->form = new PartnerContactForm();
		$this->form->addCSRFProtection($this->form->getCSRFToken());
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        $form_values = $this->form->getValues();
				$this->sendContactInfo($form_values);
        $this->redirect('form/thankyou');
      }
    }
  }


	public function sendContactInfo ($form_values)
	{
  	$emailer_db = new Emailer_Db();

    $emails = array(
				sfConfig::get('app_email_support'),
				sfConfig::get('app_email_marketing'),
				sfConfig::get('app_email_relations'));
		
		// parameteres
		//$email_info = array('login_url' => url_for('auth/index', true));

    foreach ($emails as $to)
     	$emailer_db->send_email
				(
					$this,
					$to,
					'Courier Express Contact',
					'email',
					'contact',
					array('info' => $form_values));

    // if valid email given send back thank you for
    // submiting your comment

    if ($form_values['email'] != "")
      $emailer_db->send_email
				(	$this,
					$form_values['email'],
					'Thank you for contacting Courier Express',
					'email',
					'contact_reply',
					array());
	}
}
