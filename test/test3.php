
<?php
include '../includes/mySQLconnect.php';

$value = array( array('A' => "He'llo", 'B' => "Wor'ld"),
                array('A' => "Good'night", 'B' => "V'ienna")
              );

if (is_array($value)){
	//function to escape values in array - i have not added mysqli ($db) data, so if encoding problems occur check this
	function escapeArray(&$item) {
	    $item = mysql_real_escape_string($item);
	}
	//run function on every value of array
	array_walk_recursive($value,'escapeArray');
	
	// JSON encode array
	$value = JSON_encode($value);
} else { // escape string
	$value = mysqli_real_escape_string($db, $value);
}


if (is_array($value)){

} else { // escape string
	$value = mysqli_real_escape_string($db, $value);
}



/*
function escapeArray(&$item)
{
	$item = mysql_real_escape_string($item);
}

array_walk_recursive($value, 'escapeArray');




$sweet = array('a' => 'apple', 'b' => 'banana');
$fruits = array('sweet' => $sweet, 'sour' => 'lemon');

function test_print($item, $key)
{
    echo "$key holds $item\n";
}

array_walk_recursive($fruits, 'test_print');















$array = array( array('A' => "He'llo", 'B' => "World"),
                array('A' => "Goodnight", 'B' => "Vienna")
              );

function myFunc(&$item, $key) {
    $item = mysql_real_escape_string($item);
}

array_walk_recursive($array,'myFunc');

var_dump($array);
	
	
?>
