<?php
$host = 'localhost';
$db = 'user_database';
$user = 'root';
$pass = 'abc123!@#';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
