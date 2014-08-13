<?php	
include 'config.php';
// connect to database
$db = new Mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if($db->connect_errno){
  die('Connect Error: ' . $db->connect_errno);
}
$db->query("SET NAMES 'utf8'");




