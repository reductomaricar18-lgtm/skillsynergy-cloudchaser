<?php
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$result = $conn->query('DESCRIBE initial_assessment');
if ($result) {
    echo "<h2>initial_assessment Table Columns</h2>";
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Query failed: " . $conn->error;
}
$conn->close();
?> 