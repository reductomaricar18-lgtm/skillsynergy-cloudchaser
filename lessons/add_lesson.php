<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Map skill names to lesson file names
$skillToFile = [
    'MySQL' => 'mysql_basic.php',
    'MongoDB' => 'mongodb_basic.php',
    'Redis' => 'redis_basic.php',
    'Cassandra' => 'cassandra_basic.php',
    'NoSQL' => 'nosql_basic.php',
    'Oracle Database' => 'oracle_basic.php',
    'PostgreSQL' => 'postgresql_basic.php',
    'SQL' => 'sql_basic.php',
    'SQL Server' => 'sqlserver_basic.php',
    'C' => 'c_basic.php',
    'C++' => 'c++_basic.php',
    'PHP' => 'php_basic.php',
    'JavaScript' => 'javascript_basic.php',
    'HTML' => 'html_basic.php',
    'CSS' => 'css_basic.php',
    'NodeJS' => 'nodejs_basic.php',
    'Python' => 'python_basic.php',
    'DynamoDB' => 'dynamodb_basic.php',
    'Java' => 'java_basic.php',
    'Laravel' => 'laravel_basic.php',
    'React' => 'react_basic.php',
];

// Get the skill from the URL parameter
$skill = isset($_GET['skill']) ? trim($_GET['skill']) : '';
$lessonFile = isset($skillToFile[$skill]) ? $skillToFile[$skill] : '';

?><!DOCTYPE html>
<html>
<head>
    <title>Lesson for <?php echo htmlspecialchars($skill); ?></title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f7fafc; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 32px; }
        h2 { color: #2d0252; }
        .back-btn { display: inline-block; margin-bottom: 18px; color: #fff; background: #4a90e2; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem; }
        .back-btn:hover { background: #222; }
    </style>
</head>
<body>
<div class="container">
    <a href="../admin_manage_skills.php" class="back-btn">&larr; Back to Manage Skills</a>
    <h2>Lesson for <?php echo htmlspecialchars($skill); ?></h2>
    <div style="margin-top:24px;">
        <?php
        // Check if the lesson file exists and include it
        if ($lessonFile && file_exists(__DIR__ . '/' . $lessonFile)) {
            include __DIR__ . '/' . $lessonFile;
        } else {
            echo "<h3>No lesson found for this skill.</h3>";
        }
        ?>
    </div>
</div>
</body>
</html>
