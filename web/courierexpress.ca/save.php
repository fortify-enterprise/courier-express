<?php

$con = file_get_contents("php://input");

$user_data = json_decode($con, true);
//file_put_contents("/tmp/test.txt", print_r($user_data, 1). "\n", FILE_APPEND);

// connect
$m = new Mongo("localhost");

// select a database
$db = $m->logcentric;

//  authenticate make sure user can write
// select a collection (analogous to a relational database's table)
$subscribers = $db->subscribers;

$subscriber_exists = $subscribers->count(array('subscriber' => $user_data['subscriber']));
//file_put_contents("/tmp/test.txt", print_r($subscriber_exists, 1). "\n", FILE_APPEND);
if (!$subscriber_exists)
{
	print 'no_subscriber';
	exit;
}
// see if password matches
$password_matches = $subscribers->count(array('subscriber' => $user_data['subscriber'], 'hash' => $user_data['hash']));
file_put_contents("/tmp/test.txt", $password_matches. "\n", FILE_APPEND);
if (!$password_matches)
{
	print 'password_no_match';
	exit;
}


// select a collection (analogous to a relational database's table)
$logs = $db->logs;

// unsert customer aad hash
unset($user_data['hash']);
$user_data['date'] = time();

// add a record
//$obj = array( $user_data );
$logs->insert( $user_data );


// add another record, with a different "shape"
//$obj = array( "title" => "XKCD", "online" => true );
//$collection->insert($obj);

// find everything in the collection
//$cursor = $collection->find();

// iterate through the results
//foreach ($cursor as $obj) {
//    echo $obj["title"] . "\n";
//}



/*



$m = new Mongo(); 
$adminDB = $m->admin; //require admin priviledge 

//rename collection 'colA' in db 'yourdbA' to collection 'colB' in another db 'yourdbB' 

$res = $adminDB->command(array( 
    "renameCollection" => "yourdbA.colA", 
		    "to" => "yourdbB.colB" 
				)); 

				var_dump($res); 

				*/
