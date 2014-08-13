<?php
	
require 'vendor/autoload.php';
use sandeepshetty\shopify_api;
include 'ShopifyFunctions.php';
include 'includes/mySQLconnect.php';

session_start(); //start a session

// get shop name
$shop = $_SESSION['shop'];

// get app and user data from databases
$select_settings = $db->query("SELECT * FROM tbl_appsettings WHERE id = 1");
$app_settings = $select_settings->fetch_object();

$select_userSettings = $db->query("SELECT * FROM tbl_usersettings WHERE store_name = '$shop'");
$shop_data = $select_userSettings->fetch_object();

//connect to shopify client
$shopify = shopify_api\client(
  $shop, $shop_data->access_token, $app_settings->api_key, $app_settings->shared_secret
);

// shopify commands

// * delete product
//deleteProduct();

// * update product
$productID = 334862555;
$arguments = array
        (
            "product"=>array
            (
				"title" => "updateret - Burton Custom Freestlye 151",
				"body_html" => "Good snowboard!",
				"vendor"=> "Burton",
				"product_type"=> "Snowboard",
				"tags"=> "Barnes & Noble, John's Fav"
            )
        );

// updateProduct($productID,$arguments);

// * create new product
$arguments = array
	        (
	            "product"=>array
	            (
					"title" => "Burton Custom Freestlye 159",
					"body_html" => "Good snowboard!",
					"vendor"=> "Burton",
					"product_type"=> "Snowboard",
					"tags"=> "Barnes & Noble, John's Fav"
	            )
	        );
		
//createProduct($arguments);


// * get product 
$array = array('published_status'=>'published');
var_dump(getProduct($array));


	
