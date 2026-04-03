<?php
require 'db.php';
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $sql = "DELETE FROM users WHERE id = $id";
    if($conn->query($sql) === TRUE){
        header('Location: index.php'); 
        exit();
    } 
} else {
    echo "Invalid request.";
}

?>