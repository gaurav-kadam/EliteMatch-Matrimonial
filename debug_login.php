<?php
// Debug script - DELETE AFTER USE
require_once("includes/dbconn.php");

echo "<h2>Debug Login Issue</h2>";

// 1. Check password column size
$colResult = mysqli_query($conn, "SHOW COLUMNS FROM users WHERE Field = 'password'");
$col = mysqli_fetch_assoc($colResult);
echo "<b>Password column type:</b> " . $col['Type'] . "<br><br>";

// 2. Show all users and their password lengths
$result = mysqli_query($conn, "SELECT id, username, password, LENGTH(password) as pass_len FROM users");
echo "<b>Users in database:</b><br>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Username</th><th>Password (raw)</th><th>Password Length</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['username']."</td>";
    echo "<td style='word-break:break-all; max-width:300px; font-size:11px;'>".$row['password']."</td>";
    echo "<td>".$row['pass_len']."</td>";
    echo "</tr>";
}
echo "</table><br>";

// 3. Test password_verify with known values
echo "<b>Testing password_verify:</b><br>";
$users = mysqli_query($conn, "SELECT id, username, password FROM users");
while($u = mysqli_fetch_assoc($users)){
    $testPasswords = [$u['username'], 'admin123', '123456', 'password'];
    echo "User: <b>".$u['username']."</b> (hash length: ".strlen($u['password']).")<br>";
    echo "Hash starts with: " . substr($u['password'], 0, 7) . "<br>";
    $isValidHash = (substr($u['password'], 0, 4) === '$2y$' || substr($u['password'], 0, 4) === '$2a$');
    echo "Is valid bcrypt hash? " . ($isValidHash ? "YES" : "NO - THIS IS THE PROBLEM!") . "<br>";
    foreach($testPasswords as $tp){
        $verified = password_verify($tp, $u['password']);
        echo "  password_verify('$tp') = " . ($verified ? "TRUE ✅" : "FALSE ❌") . "<br>";
    }
    echo "<br>";
}

// 4. Fix: Generate fresh hashes and update
echo "<hr><h3>Fixing passwords now...</h3>";
$newAdminHash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE username = 'admin'");
mysqli_stmt_bind_param($stmt, "s", $newAdminHash);
$r1 = mysqli_stmt_execute($stmt);
echo "Admin → admin123: " . ($r1 ? "UPDATED ✅" : "FAILED ❌") . " | Hash length: ".strlen($newAdminHash)."<br>";

$newGauravHash = password_hash('123456', PASSWORD_DEFAULT);
$stmt2 = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE username = 'Gaurav'");
mysqli_stmt_bind_param($stmt2, "s", $newGauravHash);
$r2 = mysqli_stmt_execute($stmt2);
echo "Gaurav → 123456: " . ($r2 ? "UPDATED ✅" : "FAILED ❌") . " | Hash length: ".strlen($newGauravHash)."<br>";

// 5. Verify the fix
echo "<br><b>Verifying fix:</b><br>";
$verifyResult = mysqli_query($conn, "SELECT username, password FROM users");
while($v = mysqli_fetch_assoc($verifyResult)){
    $testPw = ($v['username'] === 'admin') ? 'admin123' : '123456';
    $ok = password_verify($testPw, $v['password']);
    echo $v['username'] . " login with '$testPw': " . ($ok ? "WORKS ✅" : "STILL BROKEN ❌") . "<br>";
}

echo "<br><br><b style='color:green;'>Done! Try logging in now:</b><br>";
echo "Username: <b>admin</b> | Password: <b>admin123</b><br>";
echo "Username: <b>Gaurav</b> | Password: <b>123456</b><br>";
echo "<br><b style='color:red;'>⚠️ DELETE THIS FILE (debug_login.php) AFTER USE!</b>";
?>
