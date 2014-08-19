<?php
// include functions
include 'PublizonOperations.php';
// include mySQL connection
include '../includes/mySQLconnect.php';

// get book data array
$GetBook = GetBook("fdc226ef-e1ca-4379-b7b2-bf0b51847328","ca47350d-d282-4fcd-af4b-d0a9f866d219");

// create arrays which will contain the keys and values
$keys = array();
$values = array();
// iterate through book data array - dynamically create variables with names and value corresponding to the key-value pairs.
foreach ($GetBook['GetBookResult'] as $key => $value) {
	// if the value is an array: JSON_encode
	if (is_array($value)){
		$value = JSON_encode($value);
	}
	// create variable
 	${$key} = "'". $value ."'";
	
	// push keys and values to arrays 
	$keys[]= $key;
	$values[]= "'". $value ."'";	
}

// count keys array
$keysLength= count($keys);
$keysLengthMin1= $keysLength - 1;

// create keys string
$keysString= "";
for($i=0; $i<$keysLength; $i++) {
 	$keysString .= $keys[$i];
	if ($i<$keysLengthMin1){
		$keysString .= ", ";
	}
}

// create values string
$valuesString= "";
for($i=0; $i<$keysLength; $i++) {
 	$valuesString .= $values[$i];
	if ($i<$keysLengthMin1){
		$valuesString .= ", ";
	}
}

// insert into database
$query = "INSERT INTO AllPublizonBooks (" . $keysString .  ") VALUES (" . $valuesString .")";
$db->query($query);







