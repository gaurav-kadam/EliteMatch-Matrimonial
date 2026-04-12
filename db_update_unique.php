<?php
require_once("functions.php");

echo "<h2>EliteMatch Unique Features - Database Update</h2>";

$queries = [
    "ALTER TABLE customer ADD COLUMN IF NOT EXISTS trust_score INT(3) NOT NULL DEFAULT 0",
    "ALTER TABLE photos ADD COLUMN IF NOT EXISTS is_blurred TINYINT(1) NOT NULL DEFAULT 0",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) NOT NULL DEFAULT 0"
];

foreach ($queries as $sql) {
    if (dbExecute($sql)) {
        echo "<p style='color:green;'>SUCCESS: $sql</p>";
    } else {
        echo "<p style='color:red;'>ERROR: Unable to execute schema update. Check the server logs for details.</p>";
    }
}

if (ensureCommunicationTables()) {
    echo "<p style='color:green;'>SUCCESS: interests and messages tables are ready for requests and chat.</p>";
} else {
    echo "<p style='color:red;'>ERROR: Unable to prepare interests/messages tables. Check the server logs for details.</p>";
}

echo "<br><b>Database schema is now updated for unique features!</b>";
echo "<br><br><a href='index.php'>Go to Homepage</a>";
?>
