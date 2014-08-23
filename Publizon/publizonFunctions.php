<?php 
include '../includes/config.php';

// call to soap client
$url= soapServer;
$config = array( "login" => login, "password" => password, "trace" => 1,"exceptions" => 0);
$objSoapClient = new SoapClient($url,$config);

// general functions:

	/**
	* Convert an object to an array
	* @param    object  $object	 The object to convert
	* @return   array
	*/
		function objectToArray($object)
		{
		    if( !is_object( $object ) && !is_array( $object ) )
		    {
		        return $object;
		    }
		    if( is_object( $object ) )
		    {
		        $object = get_object_vars( $object );
		    }
		    return array_map( 'objectToArray', $object );
		}
	
	
	/**
	* insert data from an array with data for a single book into table
	* @param    array  $bookArray 	array with data for single book
	* @param    string  $tableName	 table to insert into
	*/
		function BookToDB($bookArray, $tableName) {
	
			global $db;

		    $arrayNull = $bookArray;
		    $keys = array();
		    $values = array();
		    $lastKey="";

		    // iterate through all key-value pairs of a book
		   foreach ($arrayNull as $key => $value) {

			   	// escape value before inserting in MySQL
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

			   	// if key is not a column in databse insert it
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

		   // prepare to insert into database
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
	
	
	/**
	* insert data from an array with data for a single book into table where column is as specified
	* @param    array  $bookArray 	array with data for single book
	* @param    string  $tableName	 table to insert into
	* @param    string  $whereColumn	 column to to identify book - has to be BookId - otherwise change function
	*/
		function BookToDBWhere($bookArray, $tableName, $whereColumn) {

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
			
				// create variable
			 	${$key} = "'". $value ."'";

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
		   $query = "UPDATE " . $tableName . " SET " . $keyValueString .  " WHERE " . $whereColumn ." = " . $BookId ;
		   $call = $db->query($query);	

		   // if errors echo them
		   if (!$call){
		   	echo $db->error . '</br>'. '</br>';
		   }

		}	

// Publizon functions: 

	/** CancelOrder 
	* Cancel an order.
	* @param integer licenseKey 	Guid identifying the retailer (required)
	* @param integer orderNumber	The retailer order number identifying the order to be cancelled (required)
	* @return 	
	*/

	/** CreateSupportCase 
	* Creates a support case.
	* @param integer	licenseKey 			Guid identifying the retailer (required)
	* @param string		message 			The support message (required)
	* @param string		creatorEmailAddress	The creator's email address
	* @param integer	orderNumber			The order number to which the support case belongs (required)
	* @return 	
	*/

	/** 
	* Get book with the id given by "bookId".
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @param integer	bookId 		The bookId to return (required)
	* @return 	
	*/
		function GetBook($licenseKey,$bookId){
			// get book object
			global $url, $config, $objSoapClient;
			$soapObject = $objSoapClient->getBook(array("licenseKey" => $licenseKey, bookId => $bookId));	
			// convert the array to object 
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
		}	


	/**
	* Lists all available book IDs.
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @return 
	*/	 
		function ListAllBookIds($licenseKey){
			global $url, $config, $objSoapClient;
			$soapObject = $objSoapClient->listAllBookIds(array("licenseKey" => $licenseKey));
			// convert the array to object 
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
		
		}	


	/**
	* Lists all book subjects.
	* @param string		licenseKey 	Guid identifying the retailer (required)
	* @param string		language  	Enum defining the language of the returned subjects (DAN=Danish / ENG=English) (required)
	* @return
	*/
		function ListAllBookSubjects($licenseKey,$language){
			global $url, $config, $objSoapClient;
			print_r($objSoapClient->ListAllBookSubjects(array("licenseKey" => $licenseKey, "language"=>$language)));
		}	 


	/** ListAllBooks 
	* Lists all available books.
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @return 
	*/
		function ListAllBooks($licenseKey){
			global $url, $config, $objSoapClient;
			// retreive object
			$soapObject = $objSoapClient->ListAllBooks(array("licenseKey" => $licenseKey));
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
		}	


	/** ListBooks 
	* Lists books with the ids given by "bookIds".
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @param array		bookIds 	An array of bookIds(required)
	* @return 
	*/
		function ListBooks($licenseKey, $bookIds){
			global $url, $config, $objSoapClient;
			// retreive object
			$soapObject = $objSoapClient->ListBooks(array("licenseKey" => $licenseKey, "bookIds"=>$bookIds));
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
		}	
	

	/** ListModifiedBookIds 
	* Lists book ids added, updated and deleted after "afterUTC".
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @param integer	afterUTC	UTC date and time after which the books should have been added/updated/deleted (required)
	* @return 
	*/	
		function ListModifiedBookIds($licenseKey, $afterUTC){
			global $url, $config, $objSoapClient;
			// retreive object
			$soapObject = $objSoapClient->ListModifiedBookIds(array("licenseKey" => $licenseKey, "afterUtc"=>$afterUTC));
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
		}	


	/** ListModifiedBooks 
	* Lists books that have been added, updated and deleted after "afterUTC".
	* @param integer	licenseKey 	Guid identifying the retailer (required)
	* @param integer	afterUTC	UTC date and time after which the books should have been added/updated/deleted (required)
	* @return 
	*/
		function ListModifiedBooks($licenseKey, $afterUTC) {
			global $url, $config, $objSoapClient;
			// retreive object
			$soapObject = $objSoapClient->ListModifiedBooks(array("licenseKey" => $licenseKey, "afterUtc"=>$afterUTC));
			$soapArray = objectToArray( $soapObject );
			// return array
			return $soapArray;
	
		}

	/** ListOrders 
	* Lists all orders between two dates
	* @return 
	*/


	/** OrderBook 
	* Orders a book.
	* @param integer	licenseKey 				Guid identifying the retailer (required)
	* @param integer	orderNumber				The order number to which the support case belongs (required)
	* @param integer	bookId 					The bookId to return (required)
	* @param string		enduserEmailAddress		Optionally the end user's email address to send an email containing the download url directly to the end user. Use NULL to not send an e-mail.
	* @return 	
	*/


	/** OrderBookByIsbn 
	* Orders a book by Isbn13.
	* @param integer	licenseKey 				Guid identifying the retailer (required)
	* @param integer	orderNumber				The order number to which the support case belongs (required)
	* @param integer	isbn13					Isbn13 number of the book to order (required)
	* @param string		enduserEmailAddress		Optionally the end user's email address to send an email containing the download url directly to the end user. Use NULL to not send an e-mail.
	* @return 	
	*/

?>