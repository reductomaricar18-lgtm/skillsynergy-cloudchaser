<?php
// Test script to check assessment results data
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Assessment Results Check</h2>";

// Check if assessment_results table exists
$table_check = $conn->query("SHOW TABLES LIKE 'assessment_results'");
if ($table_check->num_rows > 0) {
    echo "✅ assessment_results table exists<br><br>";
    
    // Show table structure
    echo "<h3>Table Structure:</h3>";
    $structure = $conn->query("DESCRIBE assessment_results");
    while ($row = $structure->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "<br>";
    }
    
    echo "<br><h3>Sample Data:</h3>";
    $sample_data = $conn->query("
        SELECT ar.*, 
               p1.first_name as rater_name, 
               p2.first_name as rated_name
        FROM assessment_results ar
        LEFT JOIN users u1 ON ar.rater_id = u1.user_id
        LEFT JOIN users_profile p1 ON u1.user_id = p1.user_id
        LEFT JOIN users u2 ON ar.rated_user_id = u2.user_id
        LEFT JOIN users_profile p2 ON u2.user_id = p2.user_id
        ORDER BY ar.created_at DESC 
        LIMIT 10
    ");
    
    if ($sample_data->num_rows > 0) {
        while ($row = $sample_data->fetch_assoc()) {
            echo "Rater: " . ($row['rater_name'] ?: 'Unknown') . 
                 " | Rated: " . ($row['rated_name'] ?: 'Unknown') . 
                 " | Score: " . $row['score'] . "/" . $row['max_score'] . 
                 " (" . $row['percentage'] . "%) | Date: " . $row['created_at'] . "<br>";
        }
    } else {
        echo "No assessment results found.<br>";
    }
    
    echo "<br><h3>Top Assessment Performers Preview:</h3>";
    $top_assessments = $conn->query("
        SELECT u.user_id,
               p.first_name, p.last_name,
               AVG(ar.percentage) as avg_score,
               COUNT(ar.id) as assessment_count,
               MAX(ar.percentage) as best_score
        FROM users u
        INNER JOIN users_profile p ON u.user_id = p.user_id
        INNER JOIN assessment_results ar ON u.user_id = ar.rated_user_id
        GROUP BY u.user_id, p.first_name, p.last_name
        HAVING assessment_count >= 1
        ORDER BY avg_score DESC
        LIMIT 5
    ");
    
    if ($top_assessments->num_rows > 0) {
        $rank = 1;
        while ($row = $top_assessments->fetch_assoc()) {
            echo "#" . $rank . " " . $row['first_name'] . " " . $row['last_name'] . 
                 " - Avg: " . round($row['avg_score'], 1) . "% | Best: " . round($row['best_score'], 1) . 
                 "% | Tests: " . $row['assessment_count'] . "<br>";
            $rank++;
        }
    } else {
        echo "No assessment data available for ranking.<br>";
    }
    
} else {
    echo "❌ assessment_results table does NOT exist<br>";
    echo "The table will be created automatically when the first assessment is submitted.<br>";
}

$conn->close();
?>
