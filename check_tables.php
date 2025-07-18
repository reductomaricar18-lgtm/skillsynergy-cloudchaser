<?php
$conn = new mysqli('localhost', 'root', '', 'sia1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>Database Table Check</h1>";

$tables = ['users', 'users_profile', 'education', 'skills_offer', 'initial_assessment', 'user_likes', 'messages'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        
        // Check table structure
        $structure = $conn->query("DESCRIBE $table");
        echo "<ul>";
        while ($row = $structure->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
    }
}

$conn->close();
?> 