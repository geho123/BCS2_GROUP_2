<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "social";

// Create database connection
$conn   = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) { 
    die("ERROR: Could not connect " . $conn->connect_error);
}

?>