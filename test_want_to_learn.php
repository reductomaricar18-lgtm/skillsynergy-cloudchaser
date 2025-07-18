<?php
// Simple test script to check if the want_to_learn column exists and works
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Database Schema Check</h2>";

// Check if want_to_learn column exists in initial_assessment table
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'want_to_learn'");
if ($result->num_rows > 0) {
    echo "✅ want_to_learn column exists in initial_assessment table<br>";
} else {
    echo "❌ want_to_learn column does NOT exist in initial_assessment table<br>";
    echo "Run this SQL to add it:<br>";
    echo "<code>ALTER TABLE initial_assessment ADD COLUMN want_to_learn VARCHAR(100) DEFAULT NULL;</code><br>";
}

// Check current data
echo "<h3>Current initial_assessment data:</h3>";
$result = $conn->query("SELECT user_id, want_to_learn, created_at FROM initial_assessment WHERE want_to_learn IS NOT NULL");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "User ID: " . $row['user_id'] . " | Want to learn: " . $row['want_to_learn'] . " | Created: " . $row['created_at'] . "<br>";
    }
} else {
    echo "No records with want_to_learn data found.<br>";
}

$conn->close();
?>
