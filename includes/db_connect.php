<?php
$dbHost = 'localhost';
$dbUser = 'root'; 
$dbPass = 'Password1!'; 
$dbName = 'my_website_db';


$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");
    
?>
