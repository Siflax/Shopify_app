<?php 
// make call to soap client
$url= "http://service.pubhub.dk/Retailer/V15/MediaService.asmx?WSDL";
$config = array( "login" => "bogertilborn@outlook.com", "password" => "Sneb8r", "trace" => 1,"exceptions" => 0);
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

// Publizon Operations: 

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
	print_r($objSoapClient->listAllBookIds(array("licenseKey" => $licenseKey)));
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


/** ListBooks 
* Lists books with the ids given by "bookIds".
* @param integer	licenseKey 	Guid identifying the retailer (required)
* @param array		bookIds 	An array of bookIds(required)
* @return 
*/
	

/** ListModifiedBookIds 
* Lists book ids added, updated and deleted after "afterUTC".
* @param integer	licenseKey 	Guid identifying the retailer (required)
* @param integer	afterUTC	UTC date and time after which the books should have been added/updated/deleted (required)
* @return 
*/


/** ListModifiedBooks 
* Lists books that have been added, updated and deleted after "afterUTC".
* @param integer	licenseKey 	Guid identifying the retailer (required)
* @param integer	afterUTC	UTC date and time after which the books should have been added/updated/deleted (required)
* @return 
*/


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