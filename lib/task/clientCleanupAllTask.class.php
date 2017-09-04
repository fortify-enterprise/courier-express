<?php

class clientCleanupAllTask extends sfBaseTask
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
    $this->name             = 'cleanup-all';
    $this->briefDescription = 'Remove inconsistencies and empty data';
    $this->detailedDescription = <<<EOF
The [cleanup|INFO] task does things.
Call it with:

  [php symfony cleanup|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here


    // find addresses with no state and no province and remove them

    $res = Doctrine::getTable('Address')->findAll();
    $this->logSection('address', 'Checking for empty addresses and removing');
    foreach ($res as $address) {
      if (!$address['state_id'] && !$address['province_id'])
      {
        print 'Removing empty address with id: ' . $address['id'] . "\r\n";
        $address->delete();
      }
    }


    // find out clients with empty states as addresses and remove them

    $res = Doctrine::getTable('State')->findAll();
    $this->logSection('states', 'Checking for empty states and removing');
    foreach ($res as $state)
    {
      if (!$state['name'])
      {
        $this->logSection('Id: ' . $state['id'], 'Removing empty state');
        try
        {
          $state->delete();
        }
        catch (Exception $e)
        {
          print 'Removing empty address from an empty state: ' . $state['id'] . "\r\n";
          try
          {
            Doctrine::getTable('Address')->findByStateId($state['id'])->delete();
            $state->delete();
          }
          catch (Exception $ex)
          {
            $address = Doctrine::getTable('Address')->findOneByStateId($state['id']);
            print 'Removing client with incorrect address: ' . $address['id'] . "\r\n";
            Doctrine::getTable('Client')->findByAddressId($address['id'])->delete();
            $address->delete();
            $state->delete();
          }
        }
      }
    }


    // find all clients without ability to login or no type and remove them

    $res = Doctrine::getTable('Client')->findAll();
    $this->logSection('client', 'Checking for clients without login or type id');
    foreach ($res as $client) {
      if (!$client['login_id'] || !$client['type_id'])
      {
        $this->logSection('Id: ' . $client['id'], 'Removing client without login or type id');
        $client->delete();
      }
    }


    // building list of clients and removing addresses without clients

    $res = Doctrine::getTable('Address')->findAll();
    
    $this->logSection('address', 'Checking for clients without login or type id');
    foreach ($res as $address)
    {
      $res2 = Doctrine::getTable('Client')->findOneByAddressId($address['id']);
      if (!$res2['id'])
      {
        $this->logSection('Id: ' . $address['id'], 'Removing dangling address');
        try {
          $address->delete();
        }
        catch (Exception $e)
        {
          // removing locates without existing addresss id
          $this->logSection('Id: ' . $address['id'], 'Removing locate for a dangling address');
					// if locate delete
          $locate = Doctrine::getTable('Locate')->findOneByAddressId($address['id']);
					if ($locate)
						$locate->delete();

					// remove package for this address
					try
					{
          	$address->delete();
					}
					catch (Exception $e)
					{
          	$this->logSection('Id: ' . $address['id'], 'Removing packages with address id:' . $address['id']);
						$package1 = Doctrine::getTable('Package')->findOneByFromAddressId($address['id']);
						$package2 = Doctrine::getTable('Package')->findOneByToAddressId($address['id']);
						if ($package1)
							$package->delete();
						if ($package2)
							$package->delete();
					}


        }
      }
    }



    // remove client login entries without having a client
    $res = Doctrine::getTable('ClientLogin')->findAll();
    
    $this->logSection('client login', 'Checking for client login without client id');
    foreach ($res as $client_login)
    {
      $client = Doctrine::getTable('Client')->findOneByLoginId($client_login['id']);
      if (!$client['id'])
      {
        $this->logSection('Id: ' . $client_login['id'], 'Removing client login entry');
        $client_login->delete();
      }
    }


    // remove client detail entries without having a client
    $res = Doctrine::getTable('ClientDetail')->findAll();
    
    $this->logSection('client detail', 'Checking for client detail without client id');
    foreach ($res as $client_detail)
    {
      $client = Doctrine::getTable('Client')->findOneByDetailId($client_detail['id']);
      if (!$client['id'])
      {
        $this->logSection('Id: ' . $client_detail['id'], 'Removing client detail entry');
        $client_detail->delete();
      }
    }



  }
}
