<?php

require 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

$username = $_POST['username'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password = password_hash($password, PASSWORD_DEFAULT); 

$sql = "INSERT INTO users (username, name, phone, email, password) VALUES ('$username', '$name', '$phone', '$email', '$password')";
//INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `password`) VALUES (NULL, 'sdfsd', 'sdfsd', 'das@gmai.com', '1234567890', 'fsd');

if($conn->query($sql) === TRUE){
    // echo "New record created successfully";
    header('Location: index.php'); 
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;

}

//redirection
}



?>