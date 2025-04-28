<?php
$host = "localhost:3307";
$username = "root";
$password = ""; // leave blank if no password
$database = "your_local_buddy";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
