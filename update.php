<?php
require 'db.php';

if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $check_user = "SELECT * FROM users WHERE id = $id";
   
    $result = $conn->query($check_user);
    
    if($result->num_rows > 0){
        $username = $_REQUEST['username'] ?? '';
$name = $_REQUEST['name'] ?? '';
$phone = $_REQUEST['phone'] ?? '';
$email = $_REQUEST['email'] ?? '';

$sql = "UPDATE users SET username='$username', name='$name', phone='$phone', email='$email' WHERE id=$id";
//UPDATE `users` SET `username`='sdfsd', `name`='sdfsd', `email`='das@gmai.com', `phone`='1234567890' WHERE `id`=1;
if($conn->query($sql) === TRUE){
    // echo "New record created successfully";
    header('Location: index.php'); 
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;

}
    }
    



}




?>