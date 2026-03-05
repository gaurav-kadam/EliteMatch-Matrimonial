<?php
session_start();
require_once("../includes/dbconn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

$myusername = trim($_POST['username']);
$mypassword = $_POST['password'];

// Prepare statement to fetch user
$stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");

if(!$stmt){
    die("SQL Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $myusername);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if($row = mysqli_fetch_assoc($result)){

    // Check password using hash
    if(password_verify($mypassword, $row['password'])){

        // Login success
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        header("Location: ../userhome.php?id=" . $row['id']);
        exit();

    } else {

        $_SESSION['login_error'] = "Invalid password";
        header("Location: ../login.php");
        exit();
    }

} else {

    $_SESSION['login_error'] = "Username not found";
    header("Location: ../login.php");
    exit();
}
?>