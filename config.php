<?php
// db confs
$host = 'localhost';
$dbname = 'simple_blog';
$username = 'root';
$password = '09050840072';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
