<?php

class smsReplyTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cexp'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'sms-messages';
    $this->name             = 'reply';
    $this->briefDescription = 'Reply to required sms messages from database';
    $this->detailedDescription = <<<EOF
The [smsReply|INFO] task does things.
Call it with:

  [php symfony smsReply|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $res = Doctrine::getTable('SmsIncoming')->findAll();

    foreach ($res as $message)
    {
      // if has not replied and message required out attention
      if ($message['reply_required'] && !$message['replied_on'])
      {
        $this->logSection('sms reply', 'Replying to message: ' . $message['id']);

        // create reply message
        
        $rmsg = new SmsMessage();
        $rmsg['text']   = $this->build_reply_information($message);
        $rmsg['number'] = $message['carrier'];
        $rmsg->save();

        $message['replied_on'] = date("Y-m-d H:i:s", time());
        $message->save();
      }
    }
  }


  // here we build reply information based on message type
  protected function build_reply_information ($message)
  {
    return $message['text'];
  }
}
