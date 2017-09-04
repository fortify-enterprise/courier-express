<?php

class smsSendTask extends sfBaseTask
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
    $this->name             = 'send';
    $this->briefDescription = 'Send sms messages from database to outbox';
    $this->detailedDescription = <<<EOF
The [smsSendMessages|INFO] task does things.
Call it with:

  [php symfony smsSendMessages|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $res = Doctrine::getTable('SmsMessage')->findAll();
    foreach ($res as $message)
    {
      if ($message['sent_on'] != 0)
        continue;

      // update the time
      $this->logSection('sms send', 'Sending a message to ' . Tools_Lib::correct_phone($message['number']));
      $message['sent_on'] = date("Y-m-d H:i:s", time());
      $message->save();
      $this->writeMessageToOutbox($message);
    }
  }


  function writeMessageToOutbox ($message)
  {
		$number = Tools_Lib::correct_phone($message['number']);
    $contents = 'To: ' . $number . "\n\n" . $message['text'];

    $filename = sfConfig::get('app_sms_outgoing') . '/' . $number . '_' . substr(Tools_Lib::getHash(microtime() . $message), 0, 16) . '.sms';
    file_put_contents($filename, $contents);
  }
}
