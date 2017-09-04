<?php

class smsReadReplySendTask extends sfBaseTask
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
    $this->name             = 'read-reply-send';
    $this->briefDescription = 'Read reply and send messages in one action';
    $this->detailedDescription = <<<EOF
The [smsReadReplySend|INFO] task does things.
Call it with:

  [php symfony smsReadReplySend|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $this->runTask('sms-messages:read');
    $this->runTask('sms-messages:reply');
    $this->runTask('sms-messages:send');
  }
}
