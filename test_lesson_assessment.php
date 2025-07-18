<?php
session_start();
require_once 'lesson_assessment_mapping.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Assessment Mapping Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .test-section h3 {
            color: #555;
            margin-top: 0;
        }
        .result {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #4caf50;
        }
        .error {
            background: #ffe8e8;
            border-left-color: #f44336;
        }
        .info {
            background: #e3f2fd;
            border-left-color: #2196f3;
        }
        .question-list {
            margin-top: 15px;
        }
        .question-item {
            background: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .skill-selector {
            margin: 20px 0;
            text-align: center;
        }
        select, button {
            padding: 10px 15px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #4caf50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Lesson Assessment Mapping Test</h1>
        
        <div class="skill-selector">
            <select id="skillSelect">
                <option value="">Select a skill...</option>
                <option value="python">Python</option>
                <option value="java">Java</option>
                <option value="c">C</option>
                <option value="c++">C++</option>
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="css">CSS</option>
                <option value="html">HTML</option>
                <option value="node.js">Node.js</option>
                <option value="react">React</option>
                <option value="laravel">Laravel</option>
                <option value="sql">SQL</option>
                <option value="nosql">NoSQL</option>
                <option value="mysql">MySQL</option>
                <option value="postgresql">PostgreSQL</option>
                <option value="oracle database">Oracle Database</option>
                <option value="mongodb">MongoDB</option>
                <option value="sql server">SQL Server</option>
                <option value="cassandra">Cassandra</option>
                <option value="redis">Redis</option>
                <option value="dynamodb">DynamoDB</option>
            </select>
            <select id="levelSelect">
                <option value="basic">Basic</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
            <button onclick="testLessonMapping()">Test Lesson Mapping</button>
        </div>

        <div id="results"></div>

        <div class="test-section">
            <h3>📋 Available Lesson Files</h3>
            <div id="lessonFiles"></div>
        </div>

        <div class="test-section">
            <h3>🎯 Assessment Bank Status</h3>
            <div id="assessmentStatus"></div>
        </div>
    </div>

    <script>
        // Test lesson mapping functionality
        function testLessonMapping() {
            const skill = document.getElementById('skillSelect').value;
            const level = document.getElementById('levelSelect').value;
            const resultsDiv = document.getElementById('results');
            
            if (!skill) {
                resultsDiv.innerHTML = '<div class="result error">Please select a skill first.</div>';
                return;
            }

            resultsDiv.innerHTML = '<div class="result info">Testing lesson mapping...</div>';

            // Test getting lesson file
            fetch(`lesson_assessment_mapping.php?action=get_lesson_file&skill=${encodeURIComponent(skill)}&level=${encodeURIComponent(level)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '<div class="test-section">';
                    html += '<h3>🔍 Lesson Mapping Test Results</h3>';
                    
                    if (data.status === 'success' && data.data) {
                        const lessonFile = data.data;
                        html += `<div class="result">✅ Lesson file found: <strong>${lessonFile}</strong></div>`;
                        
                        // Test getting assessment for this lesson
                        return fetch(`lesson_assessment_mapping.php?action=get_assessment_for_lesson&lesson_file=${encodeURIComponent(lessonFile)}`)
                            .then(response => response.json())
                            .then(assessmentData => {
                                if (assessmentData.status === 'success' && assessmentData.data) {
                                    const assessment = assessmentData.data;
                                    html += `<div class="result">✅ Assessment found for lesson</div>`;
                                    
                                    // Count questions
                                    let totalQuestions = 0;
                                    if (assessment.multipleChoice) totalQuestions += assessment.multipleChoice.length;
                                    if (assessment.debugging) totalQuestions += assessment.debugging.length;
                                    if (assessment.coding) totalQuestions += assessment.coding.length;
                                    
                                    html += `<div class="result">📊 Total questions: ${totalQuestions}</div>`;
                                    
                                    // Show sample questions
                                    if (assessment.multipleChoice && assessment.multipleChoice.length > 0) {
                                        html += '<div class="question-list">';
                                        html += '<h4>Sample Multiple Choice Questions:</h4>';
                                        assessment.multipleChoice.slice(0, 3).forEach((q, idx) => {
                                            html += `<div class="question-item">
                                                <strong>Q${idx + 1}:</strong> ${q.question}<br>
                                                <small>Answer: ${q.answer}</small>
                                            </div>`;
                                        });
                                        html += '</div>';
                                    }
                                } else {
                                    html += `<div class="result error">❌ No assessment found for lesson</div>`;
                                }
                                
                                // Test getting all assessment levels
                                return fetch(`lesson_assessment_mapping.php?action=get_all_assessments_for_lesson&lesson_file=${encodeURIComponent(lessonFile)}`)
                                    .then(response => response.json())
                                    .then(allData => {
                                        if (allData.status === 'success' && allData.data) {
                                            const allAssessments = allData.data;
                                            html += '<div class="result">📚 All assessment levels available:</div>';
                                            html += '<ul>';
                                            if (allAssessments.beginner) html += '<li>✅ Beginner</li>';
                                            if (allAssessments.intermediate) html += '<li>✅ Intermediate</li>';
                                            if (allAssessments.advanced) html += '<li>✅ Advanced</li>';
                                            html += '</ul>';
                                        } else {
                                            html += '<div class="result error">❌ Could not fetch all assessment levels</div>';
                                        }
                                        
                                        resultsDiv.innerHTML = html;
                                    });
                            })
                            .catch(error => {
                                html += `<div class="result error">❌ Error testing assessment: ${error.message}</div>`;
                                resultsDiv.innerHTML = html;
                            });
                    } else {
                        html += `<div class="result error">❌ No lesson file found for skill: ${skill} (${level})</div>`;
                        resultsDiv.innerHTML = html;
                    }
                })
                .catch(error => {
                    resultsDiv.innerHTML = `<div class="result error">❌ Error testing lesson mapping: ${error.message}</div>`;
                });
        }

        // Load available lesson files
        function loadLessonFiles() {
            const lessonFiles = [
                'lessons/python_basic.php', 'lessons/python_intermediate.php', 'lessons/python_advanced.php',
                'lessons/java_basic.php', 'lessons/java_intermediate.php', 'lessons/java_advanced.php',
                'lessons/c_basic.php', 'lessons/c_intermediate.php', 'lessons/c_advanced.php',
                'lessons/c++_basic.php', 'lessons/c++_intermediate.php', 'lessons/c++_advanced.php',
                'lessons/php_basic.php', 'lessons/php_intermediate.php', 'lessons/php_advanced.php',
                'lessons/javascript_basic.php', 'lessons/javascript_intermediate.php', 'lessons/javascript_advanced.php',
                'lessons/css_basic.php', 'lessons/css_intermediate.php', 'lessons/css_advanced.php',
                'lessons/html_basic.php', 'lessons/html_intermediate.php', 'lessons/html_advanced.php',
                'lessons/nodejs_basic.php', 'lessons/nodejs_intermediate.php', 'lessons/nodejs_advanced.php',
                'lessons/react_basic.php', 'lessons/react_intermediate.php', 'lessons/react_advanced.php',
                'lessons/laravel_basic.php', 'lessons/laravel_intermediate.php', 'lessons/laravel_advanced.php',
                'lessons/sql_basic.php', 'lessons/sql_intermediate.php', 'lessons/sql_advanced.php',
                'lessons/nosql_basic.php', 'lessons/nosql_intermediate.php', 'lessons/nosql_advanced.php',
                'lessons/mysql_basic.php', 'lessons/mysql_intermediate.php', 'lessons/mysql_advanced.php',
                'lessons/postgresql_basic.php', 'lessons/postgresql_intermediate.php', 'lessons/postgresql_advanced.php',
                'lessons/oracledatabase_basic.php', 'lessons/oracledatabase_intermediate.php', 'lessons/oracledatabase_advanced.php',
                'lessons/mongodb_basic.php', 'lessons/mongodb_intermediate.php', 'lessons/mongodb_advanced.php',
                'lessons/sqlserver_basic.php', 'lessons/sqlserver_intermediate.php', 'lessons/sqlserver_advanced.php',
                'lessons/cassandra_basic.php', 'lessons/cassandra_intermediate.php', 'lessons/cassandra_advanced.php',
                'lessons/redis_basic.php', 'lessons/redis_intermediate.php', 'lessons/redis_advanced.php',
                'lessons/dynamodb_basic.php', 'lessons/dynamodb_intermediate.php', 'lessons/dynamodb_advanced.php',
                'lessons/relational_vs_nosql_basic.php'
            ];

            const lessonFilesDiv = document.getElementById('lessonFiles');
            lessonFilesDiv.innerHTML = `<div class="result">📁 Total lesson files: ${lessonFiles.length}</div>`;
            
            let html = '<div style="max-height: 300px; overflow-y: auto; background: white; padding: 15px; border-radius: 5px;">';
            lessonFiles.forEach(file => {
                html += `<div style="padding: 5px; border-bottom: 1px solid #eee;">${file}</div>`;
            });
            html += '</div>';
            lessonFilesDiv.innerHTML += html;
        }

        // Check assessment bank status
        function checkAssessmentBank() {
            fetch('assessments_bank.json')
                .then(response => response.json())
                .then(data => {
                    const assessmentStatusDiv = document.getElementById('assessmentStatus');
                    const topics = Object.keys(data);
                    
                    let html = `<div class="result">✅ Assessment bank loaded successfully</div>`;
                    html += `<div class="result">📊 Total topics: ${topics.length}</div>`;
                    html += '<div style="max-height: 300px; overflow-y: auto; background: white; padding: 15px; border-radius: 5px;">';
                    topics.forEach(topic => {
                        const levels = Object.keys(data[topic]);
                        html += `<div style="padding: 5px; border-bottom: 1px solid #eee;">
                            <strong>${topic}</strong>: ${levels.join(', ')}
                        </div>`;
                    });
                    html += '</div>';
                    
                    assessmentStatusDiv.innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('assessmentStatus').innerHTML = 
                        `<div class="result error">❌ Error loading assessment bank: ${error.message}</div>`;
                });
        }

        // Initialize page
        window.onload = function() {
            loadLessonFiles();
            checkAssessmentBank();
        };
    </script>
</body>
</html> 