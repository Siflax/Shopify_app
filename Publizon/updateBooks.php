<?php
	
include 'publizonFunctions.php';
include '../includes/mySQLconnect.php';
include '../includes/config.php';

// get afterUTC from database
$query = 	"SELECT dateTime 
			FROM SoapCalls 
			ORDER BY ID DESC LIMIT 1";
$call = $db->query($query);	

	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}

	// get date and time
	$afterUTC=$call->fetch_object()->dateTime;

// list modified book IDs
$modifiedBooks = ListModifiedBooks(licenseKey,$afterUTC);

// handle new and modified books

	// define empty array to be used to save books in foreach
	$arrayNull;

	// iterate through all books
	foreach ($modifiedBooks["ListModifiedBooksResult"]['NewAndModifiedBooks']['Book'] as $bookArray) {

		// insert each book into AllPublizonBooks table 
		$tableName = "AllPublizonBooks";
		BookToDB($bookArray, $tableName); 
		// update books in selectBooks table
		$tableName = "SelectedBooks";
		$whereColumn = 'BookId';		
		BookToDBWhere($bookArray, $tableName, $whereColumn);
	
	}

// handle removed books
foreach ($modifiedBooks["ListModifiedBooksResult"]['RemovedBooks']["BookId"] as $bookID) {

	// Delete from AllPublizonBooks table
	$tableName = "AllPublizonBooks";
	$query= "DELETE FROM " . $tableName . "	WHERE bookId='" . $bookID["_"] . "'";
	$call = $db->query($query);	
	
		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	
	// Delete from SelectedBooks table
	$tableName = "SelectedBooks";
	$query= "DELETE FROM " . $tableName . "	
			WHERE bookId='" . $bookID["_"] . "'";
	$call = $db->query($query);	
	
		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
		
	//	Delete from shopify
		// get product ID where sku is equal to BookId
		$query= "SELECT ShopifyBookId FROM SelectedBooks 
				WHERE BookId = '" . $bookID["_"] . "'";
		$call = $db->query($query);
		
			// echo if errors
			if (!$call){
				echo $db->error . '</br>'. '</br>';
			}
	
		$callArray = $call->fetch_array();	
		$productId = $callArray["ShopifyBookId"];
	
		// delete product with the according productID
		deleteProduct($productId);
	 
}

// insert name, date and time of the soap call in database
$time = gmdate('Y-m-d\TH:i:s');
$query = 	"INSERT INTO SoapCalls (`soapCall`, `dateTime`) 
			VALUES ('Update books','" . $time . "')";
$call = $db->query($query);	

	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}
