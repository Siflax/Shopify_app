<?php
	
include '../Publizon/PublizonOperations.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';



//function to escape values in array - i have not added mysqli ($db) data, so if encoding problems occur check this
function escapeArray(&$item) {
    $item = mysql_real_escape_string($item);
}



// get all book IDs
$AllBookIDs = ListAllBookIds(licenseKey);

// create empty book ID array
$BookIdArray = array();

// load selection of book ids into array 
for ($i=0; $i<15; $i++){
	$BookIdArray[] = $AllBookIDs["ListAllBookIdsResult"]["BookId"][$i]["_"];
}

// temporary replacement of $arrayTest;
//$bookIdsTest = Array ("ca47350d-d282-4fcd-af4b-d0a9f866d219", "66202a04-d8c2-4d8f-ab4b-0e374e47b9ea", "9073a824-71bd-4f96-be97-37f5633fbcb4");
 
// list books specified in selection of books array -- change between $bookIdsTest and $BookIdArray depending if testing.
$books = ListBooks("fdc226ef-e1ca-4379-b7b2-bf0b51847328", $BookIdArray);

// define empty array to be used to save books in foreach
$arrayNull;

// iterate through all books
foreach ($books["ListBooksResult"]['Book'] as $bookArray) {
	 $arrayNull = $bookArray;
	 $keys = array();
	 $values = array();
	 $lastKey="";
	
	 // iterate through all key-value pairs of a book
	foreach ($arrayNull as $key => $value) {
		
		// * escape value before inserting in MySQL
		// if the value is an array: escape and JSON_encode
		if (is_array($value)){
	
			// JSON encode array (is escaping) - single and double quotes are formatted (remember to check if output formatting is automatic)
			$value = JSON_encode($value, JSON_HEX_APOS, JSON_HEX_QUOT);
						
		} else {
			$value = mysqli_real_escape_string($db, $value);
		}
		
		// create variable
	 	${$key} = "'". $value ."'";
		// push keys and values to arrays 
		$keys[]= $key;
		$values[]= "'". $value ."'";		
		
		// * if key is not a column in databse insert it
		// if key is not yet a column in the database the following will return 0
		$query =  "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'AllPublizonBooks' AND table_schema = 'shopify_app' AND 			column_name =" . "'" . $key  . "'";
		$columnCheck= $db->query($query);
		$columnCheckNumRows = $columnCheck->num_rows;
		
		// if the key is not in database insert it as column.
		if ($columnCheckNumRows == 0) {
			$query = "ALTER TABLE AllPublizonBooks ADD " . $key . " VARCHAR(10000) default NULL after " . $lastKey;
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
	
	// * insert into database
	// ******** have to add: if (book) does not already exist in databse ********
	$query = "INSERT IGNORE INTO AllPublizonBooks (" . $keysString .  ") VALUES (" . $valuesString .")";
	$db->query($query);	

}


?>