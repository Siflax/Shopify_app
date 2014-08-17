<?php
	
include '../Publizon/PublizonOperations.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';
 include 'test2.php';


// insert name, date and time of the soap call in database
$query = "INSERT INTO SoapCalls (`soapCall`) VALUES ('Import all books')";
$call = $db->query($query);	

// if errors echo them
if (!$call){
	echo $db->error . '</br>'. '</br>';
}

// get all book IDs
$AllBookIDs = ListAllBookIds(licenseKey);

// count book IDs
$countBookIDs = count($AllBookIDs["ListAllBookIdsResult"]["BookId"]);

// create empty book ID array
$BookIdArray = array();

// load selection of book ids into array
for ($i=0; $i<18; $i++){
	$BookIdArray[] = $AllBookIDs["ListAllBookIdsResult"]["BookId"][$i]["_"];
}

// divide $BookIdArray into chunks to avoid server restriction on data.
$chunkedBookIdArray = array_chunk($BookIdArray, 100);

// iterate through $chunkedBookIdArray

foreach ($chunkedBookIdArray as $BookIdArraySelection) {
 
	// list books specified in selection of books array
	$books = ListBooks(licenseKey, $BookIdArraySelection);

	// define empty array to be used to save books in foreach
	$arrayNull;

	// iterate through all books
	foreach ($books["ListBooksResult"]['Book'] as $bookArray) {
		
		BookToDB($bookArray, "AllPublizonBooks"); 
	}
}

?>