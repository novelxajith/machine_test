<?php
include_once('header_config.php');
include 'upload.php';

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check loggin status
    if (isset($_SESSION["user_id"])) {
        $result = uploadImage($_FILES["image"]);
        echo $result;
    } else { 
        header("Location: login.php");
        exit();
    }
}


?>
