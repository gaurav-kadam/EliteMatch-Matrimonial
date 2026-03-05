<?php
require_once("includes/dbconn.php");

echo "<h2>EliteMatch Unique Features - Database Update</h2>";

$queries = [
    "ALTER TABLE customer ADD COLUMN IF NOT EXISTS trust_score INT(3) NOT NULL DEFAULT 0",
    "ALTER TABLE photos ADD COLUMN IF NOT EXISTS is_blurred TINYINT(1) NOT NULL DEFAULT 0",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) NOT NULL DEFAULT 0",
    "ALTER TABLE interests ADD COLUMN IF NOT EXISTS icebreaker_msg TEXT DEFAULT NULL"
];

foreach ($queries as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>SUCCESS: $sql</p>";
    } else {
        echo "<p style='color:red;'>ERROR: " . mysqli_error($conn) . " (Query: $sql)</p>";
    }
}

echo "<br><b>Database schema is now updated for unique features!</b>";
echo "<br><br><a href='index.php'>Go to Homepage</a>";
?>
