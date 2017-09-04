<?php

class Mongo_Db
{
	function init_db($conn)
	{
		$db = $conn->courier_express_prod;
    $host = sfContext::getInstance()->getRequest()->getHost();
    if (preg_match('/^(staging|devbox|dev)/i', $host))
      $db = $conn->courier_express_dev;

		return $db;
	}


	function create_payment_profile ($client_id, $values)
	{
    try
		{
      // open connection to MongoDB server
      $conn = new Mongo('localhost');
  
      // access database
      $db = $this->init_db($conn);
  
      // access collection
      $collection = $db->payment_profiles;
			$collection->insert(array('client_id' => $client_id, 'values' => $values)); 

  		// disconnect from server
  		$conn->close();
		}
		catch (MongoConnectionException $e)
		{
  		die('Error connecting to MongoDB server');
		}
		catch (MongoException $e)
		{
  		die('Error: ' . $e->getMessage());
		}
	}


	function update_payment_profile ($client_id, $values)
	{
    try
		{
      // open connection to MongoDB server
      $conn = new Mongo('localhost');
  
      // access database
      $db = $this->init_db($conn);
  
      // access collection
      $collection = $db->payment_profiles;
			$collection->remove(array('client_id' => $client_id));


			// insert updated profile
			$collection->insert(array('client_id' => $client_id, 'values' => $values));

  		// disconnect from server
  		$conn->close();
		}
		catch (MongoConnectionException $e)
		{
  		die('Error connecting to MongoDB server');
		}
		catch (MongoException $e)
		{
  		die('Error: ' . $e->getMessage());
		}
	}


	function read_payment_profile ($client_id)
	{
    try
		{
      // open connection to MongoDB server
      $conn = new Mongo('localhost');
  
      // access database
      $db = $this->init_db($conn);
  
      // access collection
      $collection = $db->payment_profiles;

			// execute query
			// retrieve all documents
			$doc = $collection->findOne(array('client_id' => $client_id));

  		// disconnect from server
  		$conn->close();

			// 
			return ($doc['values']) ? $doc['values'] : null;
		}
		catch (MongoConnectionException $e)
		{
  		die('Error connecting to MongoDB server');
		}
		catch (MongoException $e)
		{
  		die('Error: ' . $e->getMessage());
		}
	}


	function payment_profile_exists ($client_id)
	{
    try
		{
      // open connection to MongoDB server
      $conn = new Mongo('localhost');
  
      // access database
      $db = $this->init_db($conn);
  
      // access collection
      $collection = $db->payment_profiles;

			// execute query
			// retrieve document
			$doc = $collection->findOne(array('client_id' => $client_id));

  		// disconnect from server
  		$conn->close();

			// 
			return isset($doc);
		}
		catch (MongoConnectionException $e)
		{
  		die('Error connecting to MongoDB server');
		}
		catch (MongoException $e)
		{
  		die('Error: ' . $e->getMessage());
		}
	}


}

//$mongo_db = new Mongo_Db();
//$mongo_db->create_payment_profile('123', array('data' => 'gfdgsd', 'profile_code' => 'code'));
