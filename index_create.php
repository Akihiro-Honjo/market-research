<?php

// var_dump($_POST);
// exit();


$product = $_POST['product'];
$location = $_POST['location'];
$price = $_POST['price'];
$date = $_POST['date'];

$write_data = array($product, $location, $price, $date);

$file = fopen('data/mr.csv','a');
flock($file, LOCK_EX);
fputcsv($file, $write_data);
flock($file, LOCK_UN);
fclose($file);
header("Location:market.php");

?>