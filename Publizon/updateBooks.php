<?php
	
include 'PublizonOperations.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';

// get afterUTC from database
$query = "SELECT dateTime FROM SoapCalls ORDER BY ID DESC LIMIT 1";
$call = $db->query($query);	

// if errors echo them
if (!$call){
	echo $db->error . '</br>'. '</br>';
}

// get date and time
$afterUTC=$call->fetch_object()->dateTime;

// list modified book IDs
$modifiedBooks = ListModifiedBooks(licenseKey,$afterUTC);

// * handle new and modified books
// define empty array to be used to save books in foreach
$arrayNull;

// iterate through all books
foreach ($modifiedBooks["ListModifiedBooksResult"]['NewAndModifiedBooks']['Book'] as $bookArray) {

	// insert each book into table
	$tableName = "AllPublizonBooks";
	BookToDB($bookArray, $tableName); 
	
}

// * handle removed books
foreach ($modifiedBooks["ListModifiedBooksResult"]['RemovedBooks']["BookId"] as $bookID) {
	echo $bookID["_"]. '</br>'. '</br>'. '</br>';
	// insert each book into table
	$tableName = "AllPublizonBooks";
	$query= "DELETE FROM " . $tableName . "	WHERE bookId='" . $bookID["_"] . "'";
	$call = $db->query($query);	
	
	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}
	
	
}
