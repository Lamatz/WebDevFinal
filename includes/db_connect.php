<?php
$dbHost = 'localhost';
$dbUser = 'root'; // Default XAMPP user - use a dedicated user in production!
$dbPass = 'Password1!'; // Default XAMPP password - set a password in production!
$dbName = 'my_website_db'; // Your database name

// Using MySQLi (Object-Oriented)
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set
$conn->set_charset("utf8mb4");
    
?>
