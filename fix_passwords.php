<?php
// Temporary script to fix truncated passwords
require_once("functions.php");

// Reset admin password to 'admin123'
$adminHash = password_hash('admin123', PASSWORD_DEFAULT);
dbExecute("UPDATE users SET password = ? WHERE username = ?", "ss", array($adminHash, 'admin'));
echo "Admin password reset. Length: " . strlen($adminHash) . "<br>";

// Reset Gaurav password to 'Gaurav' (or a default)
$gauravHash = password_hash('Gaurav', PASSWORD_DEFAULT);
dbExecute("UPDATE users SET password = ? WHERE username = ?", "ss", array($gauravHash, 'Gaurav'));
echo "Gaurav password reset. Length: " . strlen($gauravHash) . "<br>";

// Verify
$result = dbSelect("SELECT id, username, LENGTH(password) as pass_length FROM users");
echo "<br>Updated users:<br>";
while($row = mysqli_fetch_assoc($result)){
    echo "ID: {$row['id']} | Username: {$row['username']} | Password length: {$row['pass_length']}<br>";
}

echo "<br><b>Done! Now delete this file (fix_passwords.php) for security.</b>";
echo "<br><br>Login credentials:<br>";
echo "admin / admin123<br>";
echo "Gaurav / Gaurav<br>";
?>
