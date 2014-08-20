<?php

require 'vendor/autoload.php';
use sandeepshetty\shopify_api;
include 'ShopifyFunctions.php';
include 'includes/mySQLconnect.php';

session_start(); //start a session

// * prepare shopify data

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


// * Handling of unselect feature 
if ($_POST['submitUnselect']) { 
	
	// remove book from SelectedBooks
	$query = "DELETE FROM SelectedBooks WHERE BookId = '" . $_POST['submitUnselect'] . "'";
	$call = $db->query($query);

		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	
	// delete book from Shopify
		
		// get product ID where sku is equal to BookId
	
		// delete product with the according productID
		
		//deleteProduct();
	
}

	
// * Handling of update price feature
if ($_POST["submitRetailPrice"]) { 
	
	// if $_POST detailPris update column in SelectedBooks
	$query = "UPDATE SelectedBooks SET `retailPrice` = '" . $_POST['retailPrice'] . "' WHERE BookId = '" . $_POST["submitRetailPrice"] . "'";
	$call = $db->query($query);

		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	
	// update product on shopify
}
	
	
// * Handling of select book feature 	
if ($_POST['submitBook']){
	
	// store time into table to use later to get product ID
	
	//  insert into selected books
	
		// get column names from AllPublizonBooks
		$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'shopify_App' AND TABLE_NAME = 'AllPublizonBooks'";
		$call = $db->query($query);

			// if errors echo them
			if (!$call){
				echo $db->error . '</br>'. '</br>';
			}

		// make column name string
		$columns = array();
		while ($row = $call->fetch_array()) {
			$columns[] = $row[0];
		
		}
		$columns = implode(', ', $columns);
	
		// insert book data from AllPublizonBooks into SelectedBooks table
		$titel =  $_POST['Titel'];
		$isbn = $_POST['ISBN'];
		$query = "INSERT INTO `SelectedBooks` (" .$columns . ") SELECT " . $columns . " FROM `AllPublizonBooks` WHERE Title= '".$titel ."' AND Identifier = '" . $isbn . "'";	
		$call = $db->query($query);

			// if errors echo them
			if (!$call){
				echo $db->error . '</br>'. '</br>';
			}
	
	// add books to shopify
	
		// get book data from Selected Books
		$query="SELECT * FROM SelectedBooks WHERE Title= '".$titel ."' AND Identifier = '" . $isbn . "'";
		$call = $db->query($query);
	
			// echo if errors
			if (!$call){
				echo $db->error . '</br>'. '</br>';
			}
	
		// declare variables corresponding to each column
		foreach ($call->fetch_array() as $key => $value) {
			${$key} = $value;		
		}
	
		// prepare values to be inserted 
			//prepare images to be inserted
			$images = json_decode($Images, true);
			$images=$images['Image'][0];
			$images=array_chunk($images,1);
			$images=$images[0];
			$images['src'] = $images[0];
			unset($images[0]);
	
			//prepare MainDescription to be inserted
			$MainDescription = addslashes($MainDescription);
	
			//create variants array
			$variants = array();
			$variants['option1'] =$Title;
			$price=json_decode($Price, true);
			$variants['price'] = $price['_'];
			$variants['sku'] = $BookId ;
		
			// prepare subjects to be inserted
			$subjects = json_decode($Subjects,true);
					$subjects = $subjects["SimpleSubject"];
				
					// Check how deep array is
					function array_depth(array $array) {
					    $max_depth = 1;

					    foreach ($array as $value) {
					        if (is_array($value)) {
					            $depth = array_depth($value) + 1;

					            if ($depth > $max_depth) {
					                $max_depth = $depth;
					            }
					        }
					    }
					
					    return $max_depth;
					}
				
					$arrayDepth = array_depth($subjects);
					
					// make data into string			
					if ($arrayDepth==1){
						$subjectsString = $subjects['Description'];
					}
				
					if ($arrayDepth==2) {
						$subjectsArray = array();
						foreach ($subjects as $subject) {
							$subjectsArray[] = $subject['Description'];
						}
						$subjectsString = implode($subjectsArray, ", ");
					} 
	
	
		// insert data into array
		
		$arguments = array
			        (
			            "product"=>array
			            (
							"title" => $Title,
							"body_html" => $MainDescription,
							"product_type"=> $BookType,
							"images"=>$images,
							"variants"=>$variants,
							"tags"=>$subjectsString
			            )
			        );
		// create new product
		createProduct($arguments);
	
	// get product ID from shopify and store in selected books table
	
}


// import books from selected table to view.
$query = "SELECT * FROM `SelectedBooks`";
$call2 = $db->query($query);

	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}
	
	
	
	
	
	
	
	
	
	
// shopify commands



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
$productArray = getProduct($array);
//var_dump($productArray[0]['id']);	
	
	
	
	
	
?>

<html>

	<head>
		<meta charset="utf-8"> 
	</head>
	
	<body>
		
		<?php
		$data = array('Titel','ISBN');
		include 'includes/form.php';
		
		while ($row = $call2->fetch_array()) {?>
		
			<form id="unselect" name="unselect" method="post" action="">		
					<button type="submitUnselect" name="submitUnselect" value="<?php echo $row['BookId'] ;?>">Fjern</button>
			</form>	
		
			<?php
			$test = json_decode($row["Price"]);
		    echo $row["Title"]. "&nbsp;&nbsp;&nbsp;&nbsp af " . $row["Authors"];
			echo " &nbsp;&nbsp;&nbsp;&nbsp;" . "engros pris: " . $test->_ . " " . $test->CurrencyCode;
			echo " &nbsp;&nbsp;&nbsp;&nbsp;" . "detail pris: ";		
			?>
		
			<form id="retailPrice" name="retailPrice" method="post" action="">
			
					<label> 
						<input type="text" name="retailPrice" id="retailPrice" value = "<?php echo $row["retailPrice"]; ?>"/>
					</label>			
		
					<label>
					
						<button type="submitRetailPrice" name="submitRetailPrice" value="<?php echo $row["BookId"]; ?>" >Submit</button>
					</label>	
			</form>
		
			<?php echo '</br>';
		
		}?>

	</body>
	
</html>	


	
