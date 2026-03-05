<?php
// Temporary script to fix truncated passwords
require_once("includes/dbconn.php");

// Reset admin password to 'admin123'
$adminHash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE username = 'admin'");
mysqli_stmt_bind_param($stmt, "s", $adminHash);
mysqli_stmt_execute($stmt);
echo "Admin password reset. Length: " . strlen($adminHash) . "<br>";

// Reset Gaurav password to 'Gaurav' (or a default)
$gauravHash = password_hash('Gaurav', PASSWORD_DEFAULT);
$stmt2 = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE username = 'Gaurav'");
mysqli_stmt_bind_param($stmt2, "s", $gauravHash);
mysqli_stmt_execute($stmt2);
echo "Gaurav password reset. Length: " . strlen($gauravHash) . "<br>";

// Verify
$result = mysqli_query($conn, "SELECT id, username, LENGTH(password) as pass_length FROM users");
echo "<br>Updated users:<br>";
while($row = mysqli_fetch_assoc($result)){
    echo "ID: {$row['id']} | Username: {$row['username']} | Password length: {$row['pass_length']}<br>";
}

echo "<br><b>Done! Now delete this file (fix_passwords.php) for security.</b>";
echo "<br><br>Login credentials:<br>";
echo "admin / admin123<br>";
echo "Gaurav / Gaurav<br>";
?>
