<?php

/**
* delete a product with given product ID
* @param    integer  $productID	 ID of product
*/
function deleteProduct($productID){
	global $shopify;
	$shopify('DELETE', '/admin/products/' . $productID . '.json');
}

/**
* get all products - specify in array
* @param    array  	$array	 array of product selection criteria
* @return   object
*/
function getProduct($array){
	global $shopify;
	$products = $shopify('GET', '/admin/products.json', $array );
	return $products;
}

/**
* Get product by ID
* @param    integer 	$productID	ID of product
* @param    array  		$array		array of product selection criteria
* @return   object
*/
function getProductById($productID, $array){
	global $shopify;
	$products = $shopify('GET', '/admin/products/' . $productID . '.json', $array );
	return $products;
}

/**
* Create product
* @param    array  $argument  array of arguments specifing the product to be created
*/
function createProduct($arguments){
	global $shopify;
	$shopify('POST', '/admin/products.json', $arguments);
}

/**
* Update product 
* @param    integer $productID	ID of product to be updated
* @param    array  $argument  	array of arguments with information to be updated
*/
function updateProduct($productID, $arguments){
		global $shopify;
		$shopify('PUT', '/admin/products/' . $productID . '.json', $arguments);
}

/**
* Update variant
* @param    integer $variantID	ID of variant to be updated
* @param    array  $argument  	array of arguments with information to be updated
*/
function updateVariant($variantID, $arguments){
		global $shopify;
		$shopify('PUT', '/admin/variants/' . $variantID . '.json', $arguments);
}


/**
* tag grouper
* @param    string  $subject			String of a subject
* @return   string  $subject		 	return correct tag
*/

function groupTag($subject){
	$rules = array(
		
		"Skønlitterære emner" => "Skønlitteratur",
		"Skønlitteratur (børn og unge)" => "Skønlitteratur",
		
		"Læsealder fra ca. 0 år" => "0 år",
		"Læsealder fra ca. 1 år" => "1 år",
		"Læsealder fra ca. 2 år" => "2 år",
		"Læsealder fra ca. 3 år" => "3 år",
		"Læsealder fra ca. 4 år" => "4 år",
		"Læsealder fra ca. 5 år" => "5 år",
		"Læsealder fra ca. 6 år" => "6 år",
		"Læsealder fra ca. 7 år" => "7 år",
		"Læsealder fra ca. 8 år" => "8 år",
		"Læsealder fra ca. 9 år" => "9 år",
		"Læsealder fra ca. 10 år" => "10 år",
		"Læsealder fra ca. 11 år" => "11 år",
		"Læsealder fra ca. 12 år" => "12 år",
		"Læsealder fra ca. 13 år" => "13 år",
		"Læsealder fra ca. 14 år" => "14 år",
		"Læsealder fra ca. 15 år" => "15 år",
	
	);

	//if subject is one of the following
	foreach ($rules as $key => $value){

		if ($subject == $key){

			$subject = $value;

		}
	}	
	
	return $subject;
}

/**
* correct encoding
* @param    string  $subject			String of a subject
* @return   string  $subject		 	return corrected string
*/

function correctEncoding($subject){
	$rules = array(
		
		"u00f8" => "ø",
		"u00e6" => "æ",
		"u00e5" => "å",
	
	);
	
	foreach ($rules as $key => $value){

		$subject = str_replace($key, $value, $subject);

	}	
	
	return $subject;
}


?>
