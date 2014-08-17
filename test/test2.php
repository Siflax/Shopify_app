<?php

function BookToDB($bookArray, $tableName) {
	
	global $db;

    $arrayNull = $bookArray;
    $keys = array();
    $values = array();
    $lastKey="";

    // iterate through all key-value pairs of a book
   foreach ($arrayNull as $key => $value) {

	   	// * escape value before inserting in MySQL
	   	// if the value is an array: JSON_encode (escapes as well)
	   	if (is_array($value)){
		
	   		// JSON encode array - single and double quotes are formatted (remember to check if output formatting is automatic)
	   		$value = JSON_encode($value, JSON_HEX_APOS, JSON_HEX_QUOT);
				
	   	} else { // escape strings
		
	   		$value = mysqli_real_escape_string($db, $value);
		
	   	}

	   	// push keys and values to arrays 
	   	$keys[]= $key;
	   	$values[]= "'". $value ."'";		

	   	// * if key is not a column in databse insert it
	   	// if key is not yet a column in the database the following will return 0
	   	$query =  "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $tableName ."' AND table_schema = 'shopify_app' AND column_name =" . "'" . $key  . "'";
	   	$columnCheck= $db->query($query);
	   	$columnCheckNumRows = $columnCheck->num_rows;

	   	// if the key is not in database insert it as column.
	   	if ($columnCheckNumRows == 0) {
	   		$query = "ALTER TABLE " . $tableName . " ADD " . $key . " VARCHAR(10000) default NULL after " . $lastKey;
	   		$db->query($query);
	   	}
	   	// save key as lastKey so next iteration can use it
	   	$lastKey = $key;
			
   }

   // * prepare to insert into database
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

   // create key-value pair string
   $keyValueString= "";
   for($i=0; $i<$keysLength; $i++) {
    	$keyValueString .= $keys[$i] . " = " . $values[$i];
   	if ($i<$keysLengthMin1){
   		$keyValueString .= ", ";
   	}
   }

   // insert into database
   $query = "INSERT INTO " . $tableName . " (" . $keysString .  ") VALUES (" . $valuesString .") ON DUPLICATE KEY UPDATE " . $keyValueString ;
   $call = $db->query($query);	

   // if errors echo them
   if (!$call){
   	echo $db->error . '</br>'. '</br>';
   }

}

?>