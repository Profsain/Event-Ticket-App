<?php
$host = 'localhost';
$dbname = 'eventticketdatabase';
$username = 'root'; // XAMPP's default username
$password = ''; // XAMPP's default password is empty

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
