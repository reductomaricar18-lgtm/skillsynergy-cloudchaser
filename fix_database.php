<?php
// Database fix script for want_to_learn column issue
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Fixing Database Schema for want_to_learn functionality</h2>";

// Read and execute the SQL fix script
$sql_file = 'fix_database_schema.sql';
if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^(DESCRIBE|SELECT|SHOW)/i', $statement)) {
            if ($conn->query($statement)) {
                echo "✅ Executed: " . substr($statement, 0, 50) . "...<br>";
            } else {
                echo "❌ Error executing: " . substr($statement, 0, 50) . "...<br>";
                echo "Error: " . $conn->error . "<br>";
            }
        }
    }
    
    echo "<h3>Verification:</h3>";
    
    // Check if want_to_learn column exists in initial_assessment
    $result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'want_to_learn'");
    if ($result->num_rows > 0) {
        echo "✅ want_to_learn column exists in initial_assessment table<br>";
    } else {
        echo "❌ want_to_learn column still missing in initial_assessment table<br>";
    }
    
    // Check if learning_goals table exists
    $result = $conn->query("SHOW TABLES LIKE 'learning_goals'");
    if ($result->num_rows > 0) {
        echo "✅ learning_goals table exists<br>";
    } else {
        echo "❌ learning_goals table missing<br>";
    }
    
} else {
    echo "❌ SQL fix file not found: $sql_file<br>";
}

echo "<h3>Test the fix:</h3>";
echo '<a href="test_want_to_learn.php" target="_blank">Run want_to_learn test</a><br>';
echo '<a href="profile_setup.php" target="_blank">Test profile_setup.php</a>';

$conn->close();
?> 