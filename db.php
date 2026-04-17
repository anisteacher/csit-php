<?php

$host = '127.0.0.1';//127.0.0.1
$username = 'root';
$password = 'root';
$database = 'mydb';

$conn = mysqli_connect($host, $username, $password, $database);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$imageColumn = $conn->query("SHOW COLUMNS FROM property LIKE 'image_path'");
if ($imageColumn && $imageColumn->num_rows === 0) {
    $conn->query("ALTER TABLE property ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER status");
}

?>
