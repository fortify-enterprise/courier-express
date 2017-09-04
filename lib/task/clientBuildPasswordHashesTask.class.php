<?php

class clientBuildPasswordHashesTask extends sfBaseTask
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

    $this->namespace        = 'client';
    $this->name             = 'build-password-hashes';
    $this->briefDescription = 'Rebuild password hashes';
    $this->detailedDescription = <<<EOF
The [clientRecalculateHashes|INFO] task does things.
Call it with:

  [php symfony clientRecalculateHashes|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // get all client ids
    $res = Doctrine::getTable('ClientLogin')->findAll();
    foreach ($res as $login)
    {
      print 'Setting password for client ' . $login['email'] . "\r\n";
      print  Tools_Lib::getHash($login['password']) . "\r\n";
      $login['password_hash'] = Tools_Lib::getHash($login['password']);
      $login->save();
    }
  }
}
