<?php
// Comprehensive fix for proficiency column issues
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Fixing Proficiency Column Issues</h2>";

// Check if proficiency column exists in initial_assessment
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'proficiency'");
if ($result->num_rows > 0) {
    echo "❌ 'proficiency' column exists in initial_assessment table - this should be 'proficiency_level'<br>";
    
    // Check if proficiency_level also exists
    $result2 = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'proficiency_level'");
    if ($result2->num_rows > 0) {
        echo "✅ 'proficiency_level' column also exists - we can drop the old 'proficiency' column<br>";
        
        // Drop the old proficiency column
        if ($conn->query("ALTER TABLE initial_assessment DROP COLUMN proficiency")) {
            echo "✅ Successfully dropped the old 'proficiency' column<br>";
        } else {
            echo "❌ Failed to drop 'proficiency' column: " . $conn->error . "<br>";
        }
    } else {
        echo "❌ 'proficiency_level' column does not exist - we need to rename 'proficiency' to 'proficiency_level'<br>";
        
        // Rename proficiency to proficiency_level
        if ($conn->query("ALTER TABLE initial_assessment CHANGE proficiency proficiency_level VARCHAR(50) NOT NULL")) {
            echo "✅ Successfully renamed 'proficiency' to 'proficiency_level'<br>";
        } else {
            echo "❌ Failed to rename column: " . $conn->error . "<br>";
        }
    }
} else {
    echo "✅ No 'proficiency' column found in initial_assessment table<br>";
}

// Check if proficiency_level exists
$result = $conn->query("SHOW COLUMNS FROM initial_assessment LIKE 'proficiency_level'");
if ($result->num_rows > 0) {
    echo "✅ 'proficiency_level' column exists in initial_assessment table<br>";
} else {
    echo "❌ 'proficiency_level' column missing - adding it now<br>";
    
    if ($conn->query("ALTER TABLE initial_assessment ADD COLUMN proficiency_level VARCHAR(50) NOT NULL DEFAULT 'Beginner'")) {
        echo "✅ Successfully added 'proficiency_level' column<br>";
    } else {
        echo "❌ Failed to add 'proficiency_level' column: " . $conn->error . "<br>";
    }
}

// Check user_points table structure
echo "<h3>Checking user_points table:</h3>";
$result = $conn->query("SHOW TABLES LIKE 'user_points'");
if ($result->num_rows > 0) {
    echo "✅ user_points table exists<br>";
    
    $result = $conn->query("DESCRIBE user_points");
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
} else {
    echo "❌ user_points table does not exist<br>";
}

// Final verification
echo "<h3>Final Verification:</h3>";
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
echo "1. The database schema has been fixed<br>";
echo "2. All code should now use 'proficiency_level' instead of 'proficiency'<br>";
echo "3. Test your profile_setup.php page<br>";
?> 