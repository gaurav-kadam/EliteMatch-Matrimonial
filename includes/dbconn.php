<?php 
$host = "localhost";
$username = "root";
$password = "";
$db_name = "matrimonial_db";

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>