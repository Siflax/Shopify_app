<?php
	
include 'publizonFunctions.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';

// insert name, date and time of the soap call in database
$time = gmdate('Y-m-d\TH:i:s');
$query = "INSERT INTO SoapCalls (`soapCall`, `dateTime`)
		 VALUES ('Import all books','" . $time . "')";
$call = $db->query($query);	

	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}

// get all book IDs
$AllBookIDs = ListAllBookIds(licenseKey);
$countBookIDs = count($AllBookIDs["ListAllBookIdsResult"]["BookId"]);

// load selection of book ids into array
$BookIdArray = array();
for ($i=0; $i<20; $i++){
	$BookIdArray[] = $AllBookIDs["ListAllBookIdsResult"]["BookId"][$i]["_"];
}

// divide $BookIdArray into chunks to avoid server restriction on data.
$chunkedBookIdArray = array_chunk($BookIdArray, 100);

// iterate through $chunkedBookIdArray
foreach ($chunkedBookIdArray as $BookIdArraySelection) {
 
	// list books specified in chunked array
	$books = ListBooks(licenseKey, $BookIdArraySelection);

	// define empty array to be used to save books in foreach
	$arrayNull;

	// iterate through all books
	foreach ($books["ListBooksResult"]['Book'] as $bookArray) {
		// insert each book into table
		$tableName = "AllPublizonBooks";
		BookToDB($bookArray, $tableName); 
	}
}
