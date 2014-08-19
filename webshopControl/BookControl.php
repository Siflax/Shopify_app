<?php

include '../includes/mySQLconnect.php';
	
	
// if $_POST unselect remove book from SelectedBooks
	if ($_POST['submitUnselect']) { 
		$query = "DELETE FROM SelectedBooks WHERE BookId = '" . $_POST['submitUnselect'] . "'";
		$call = $db->query($query);

		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	}

	
	//if $_POST detailPris update column in SelectedBooks
	if ($_POST["submitRetailPrice"]) { 
		$query = "UPDATE SelectedBooks SET `retailPrice` = '" . $_POST['retailPrice'] . "' WHERE BookId = '" . $_POST["submitRetailPrice"] . "'";
		$call = $db->query($query);

		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	}
	
?>

<html>

	<head>
		<meta charset="utf-8"> 
	</head>
	
	<body>
	
	<?php

	
	$data = array('Titel','ISBN');
	include '../includes/form.php';	
	
	$titel =  $_POST['Titel'];
	$isbn = $_POST['ISBN'];

	// Select from AllPublizonBooks where $x = $titel AND $y = $isbn

	// Insert these values into selected table
	if ($_POST['submitBook']){
		
		// get column names
		$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'shopify_App' AND TABLE_NAME = 'AllPublizonBooks'";
		$call = $db->query($query);
	
		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}

		$columns = array();
		while ($row = $call->fetch_array()) {
			$columns[] = $row[0];
			
		}
		$columns = implode(', ', $columns);
		
		// insert book data from AllPublizonBooks into selected table
		$query = "INSERT INTO `SelectedBooks` (" .$columns . ") SELECT " . $columns . " FROM `AllPublizonBooks` WHERE Title= '".$titel ."' AND Identifier = '" . $isbn . "'";
		
		$call = $db->query($query);
	
		// if errors echo them
		if (!$call){
			echo $db->error . '</br>'. '</br>';
		}
	}

	// import books from selected table to view.
	$query = "SELECT * FROM `SelectedBooks`";
	$call2 = $db->query($query);

	// if errors echo them
	if (!$call){
		echo $db->error . '</br>'. '</br>';
	}

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
		<?php
		echo '</br>';
	}?>

	</body>
	
</html>	



