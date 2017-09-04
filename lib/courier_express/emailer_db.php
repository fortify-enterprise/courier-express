<?php

class Emailer_Db extends Base_Lib
{
	// send out emails when packages status is updated when new user registers
	// future version will include addition of PDF invoice

	function send_email ($action, $to, $subject, $partial_module, $partial_action, $params = array())
	{
   	// get partials
    $html = $action->getComponent($partial_module, $partial_action, $params);
    $text = $action->getComponent($partial_module, $partial_action.'_txt', $params);

   	// prepare message html + text
		$message = Swift_Message::newInstance()
      ->setFrom(sfConfig::get('app_email_noreply'))
      ->setTo($to)
      ->setSubject($subject)
      ->setBody($html, 'text/html')
      ->addPart($text, 'text/plain');
      //->attach(Swift_Attachment::fromPath('/path/to/a/file.zip'))
			// could be used for attaching receipt

   	// send message
    $action->getMailer()->send($message);
		sfContext::getInstance()->getLogger()->debug(__FUNCTION__ . "() : sending email to : $to, $partial_module / $partial_action");
	}
}
