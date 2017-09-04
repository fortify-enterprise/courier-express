<?php

class clientRemoveTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('login_email', sfCommandArgument::REQUIRED, 'Client login email'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cexp'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'client';
    $this->name             = 'remove';
    $this->briefDescription = 'Remove client from the system';
    $this->detailedDescription = <<<EOF
The [clientRemove|INFO] task removes client from db.
Call it with:

  [php symfony clientRemove|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    // remove client from system
    $email = $arguments['login_email'];
    $res = Doctrine_Query::create()
      ->select('id')
      ->from('Client c')
      ->leftJoin('c.ClientLogin cl')
      ->where('cl.email = ?', $email)
      ->fetchOne();

		$client = '';

    if ($res['id'])
   	{
     	$this->logSection('Id: ' . $res['id'], 'Removing client id');
     	$client = Doctrine::getTable('Client')->find($res['id']);
 		}

		try
		{
    	if ($res['id'])
   		{
      	$client->Package->PackageDetail->delete();
      	$client->Package->delete();
      	$client->Payment->delete();
      	$client->ClientDetail->delete();
      	$client->ClientLogin->delete();
      	$client->Address->delete();
				$client->ClientPaymentDetail->Address->delete();
      	$client->ClientPaymentDetail->delete();
      	$client->delete();
    	}
    	else
      	$this->logSection('client', 'Does not exist');
		}
		catch (Exception $e)
		{
      $client->delete();
     	$client->ClientDetail->delete();
      $client->ClientLogin->delete();
      $client->ClientPaymentDetail->delete();
      $client->ClientPaymentDetail->Address->delete();
     	$client->Address->delete();
		}
  }
}
