<?php
// Fix missing columns in initial_assessment table
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Adding Missing Columns to initial_assessment Table</h2>";

// Check if database_type column exists
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'database_type'");
if ($result->num_rows > 0) {
    echo "✅ database_type column already exists<br>";
} else {
    echo "❌ database_type column missing - adding it now<br>";
    if ($conn->query("ALTER TABLE initial_assessment ADD COLUMN database_type VARCHAR(100) DEFAULT NULL")) {
        echo "✅ Successfully added database_type column<br>";
    } else {
        echo "❌ Failed to add database_type column: " . $conn->error . "<br>";
    }
}

// Check if specific_database_skill column exists
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'specific_database_skill'");
if ($result->num_rows > 0) {
    echo "✅ specific_database_skill column already exists<br>";
} else {
    echo "❌ specific_database_skill column missing - adding it now<br>";
    if ($conn->query("ALTER TABLE initial_assessment ADD COLUMN specific_database_skill VARCHAR(100) DEFAULT NULL")) {
        echo "✅ Successfully added specific_database_skill column<br>";
    } else {
        echo "❌ Failed to add specific_database_skill column: " . $conn->error . "<br>";
    }
}

// Show final table structure
echo "<h3>Final Table Structure:</h3>";
$result = $conn->query("DESCRIBE initial_assessment");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

$conn->close();

echo "<h3>Next Steps:</h3>";
echo "1. The missing columns have been added<br>";
echo "2. Your profile_setup.php page should now work without errors<br>";
echo "3. Test the application to confirm everything works<br>";
?> 