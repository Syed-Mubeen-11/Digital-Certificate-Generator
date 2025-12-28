<?php
// Database connection
$servername = "localhost";
$username = "root";      // default XAMPP username
$password = "";          // default XAMPP password
$dbname = "digital_certificates";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
