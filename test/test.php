<?php
	
include '../Publizon/PublizonOperations.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';

// get all book IDs
$AllBookIDs = ListAllBookIds(licenseKey);

// create empty book ID array
$BookIdArray = array();

// load selection of book ids into array 
for ($i=0; $i<10; $i++){
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
	
	 // iterate through all key-value pairs of a book
	foreach ($arrayNull as $key => $value) {
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
	
	// * if one of the columns is not yet in the MySQL database add that column
	
	
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
	$query = "INSERT INTO AllPublizonBooks (" . $keysString .  ") VALUES (" . $valuesString .")";
	$db->query($query);
}

?>