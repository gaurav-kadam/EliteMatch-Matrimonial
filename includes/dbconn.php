<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "matrimonial_db";

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    error_log("[EliteMatch] Database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    exit("Database service is temporarily unavailable. Please try again later.");
}

mysqli_set_charset($conn, "utf8mb4");
?>
