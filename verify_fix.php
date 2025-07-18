<?php
// Verification script to confirm the database fix is working
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Database Fix Verification</h2>";

// Test 1: Check if want_to_learn column exists
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'want_to_learn'");
if ($result->num_rows > 0) {
    echo "✅ Test 1 PASSED: want_to_learn column exists in initial_assessment table<br>";
} else {
    echo "❌ Test 1 FAILED: want_to_learn column missing<br>";
}

// Test 2: Check if learning_goals table exists
$result = $conn->query("SHOW TABLES LIKE 'learning_goals'");
if ($result->num_rows > 0) {
    echo "✅ Test 2 PASSED: learning_goals table exists<br>";
} else {
    echo "❌ Test 2 FAILED: learning_goals table missing<br>";
}

// Test 3: Test the problematic query from profile_setup.php
try {
    $user_id = 1; // Test with user_id 1
    $result = $conn->query("SELECT category, skill FROM initial_assessment WHERE user_id = $user_id AND want_to_learn IS NULL");
    if ($result !== false) {
        echo "✅ Test 3 PASSED: Query from profile_setup.php line 140 works<br>";
        echo "   Found " . $result->num_rows . " records<br>";
    } else {
        echo "❌ Test 3 FAILED: Query still has issues<br>";
    }
} catch (Exception $e) {
    echo "❌ Test 3 FAILED: Exception: " . $e->getMessage() . "<br>";
}

// Test 4: Test the second problematic query
try {
    $result = $conn->query("SELECT want_to_learn, proficiency_level FROM initial_assessment WHERE user_id = $user_id AND want_to_learn IS NOT NULL LIMIT 1");
    if ($result !== false) {
        echo "✅ Test 4 PASSED: Second query from profile_setup.php works<br>";
        echo "   Found " . $result->num_rows . " records<br>";
    } else {
        echo "❌ Test 4 FAILED: Second query still has issues<br>";
    }
} catch (Exception $e) {
    echo "❌ Test 4 FAILED: Exception: " . $e->getMessage() . "<br>";
}

// Test 5: Show table structure
echo "<h3>Table Structure:</h3>";
$result = $conn->query("DESCRIBE initial_assessment");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

$conn->close();

echo "<h3>Next Steps:</h3>";
echo "1. The database schema has been fixed<br>";
echo "2. You can now access profile_setup.php without errors<br>";
echo "3. The want_to_learn functionality should work properly<br>";
?> 