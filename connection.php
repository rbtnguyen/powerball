<?php

//define constants for db_host, db_user, db_pass, and db_database
//adjust the values below to match your database settings
define('DB_HOST', 'us-cdbr-east-05.cleardb.net');
define('DB_USER', 'b13de379921aa1');
define('DB_PASS', '228bcef0'); //set DB_PASS as 'root' if you're using MAMP
define('DB_DATABASE', 'heroku_d2d479b04a807a8');

//connect to database host
$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect to the database host (please double check the settings in connection.php): ' . mysql_error());

//connect to the database
$db_selected = mysql_select_db(DB_DATABASE, $connection) or die ('Could not find a database with the name "'.DB_DATABASE.'" (please double check your settings in connection.php): ' . mysql_error());

//fetches all records from the query and returns an array with the fetched records
function fetch_all($query)
{
 $data = array();

 $result = mysql_query($query);
 while($row = mysql_fetch_assoc($result))
 {
  $data[] = $row;
 }
 return $data;
}

//fetch the first record obtained from the query
function fetch_record($query)
{
 $result = mysql_query($query);
 return mysql_fetch_assoc($result);
}
?>