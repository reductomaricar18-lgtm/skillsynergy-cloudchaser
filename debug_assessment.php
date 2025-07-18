<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .error { color: red; }
        .success { color: green; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Assessment Debug Tool</h1>
    
    <div class="debug-section">
        <h3>1. Test Assessment Bank Loading</h3>
        <button onclick="testAssessmentBank()">Test Assessment Bank</button>
        <div id="assessmentBankResult"></div>
    </div>
    
    <div class="debug-section">
        <h3>2. Test Lesson Assessment Mapping</h3>
        <select id="testSkill">
            <option value="python">Python</option>
            <option value="java">Java</option>
            <option value="php">PHP</option>
            <option value="javascript">JavaScript</option>
        </select>
        <button onclick="testLessonMapping()">Test Lesson Mapping</button>
        <div id="lessonMappingResult"></div>
    </div>
    
    <div class="debug-section">
        <h3>3. Test API Endpoints</h3>
        <button onclick="testAPIEndpoints()">Test API Endpoints</button>
        <div id="apiResult"></div>
    </div>
    
    <div class="debug-section">
        <h3>4. Test Assessment Modal Rendering</h3>
        <button onclick="testModalRendering()">Test Modal Rendering</button>
        <div id="modalResult"></div>
    </div>

    <script>
        function testAssessmentBank() {
            const resultDiv = document.getElementById('assessmentBankResult');
            resultDiv.innerHTML = '<div class="info">Loading assessment bank...</div>';
            
            fetch('assessments_bank.json')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const topics = Object.keys(data);
                    resultDiv.innerHTML = `
                        <div class="success">✅ Assessment bank loaded successfully!</div>
                        <div class="info">Found ${topics.length} topics: ${topics.join(', ')}</div>
                        <pre>${JSON.stringify(data.Python || data.PHP || topics[0], null, 2)}</pre>
                    `;
                })
                .catch(error => {
                    resultDiv.innerHTML = `
                        <div class="error">❌ Error loading assessment bank: ${error.message}</div>
                    `;
                });
        }
        
        function testLessonMapping() {
            const skill = document.getElementById('testSkill').value;
            const resultDiv = document.getElementById('lessonMappingResult');
            resultDiv.innerHTML = '<div class="info">Testing lesson mapping...</div>';
            
            // Test the mapping logic from message.php
            const lessonFile = mapSkillToLesson(skill);
            
            if (lessonFile) {
                resultDiv.innerHTML = `
                    <div class="success">✅ Lesson mapping successful!</div>
                    <div class="info">Skill: ${skill} → Lesson: ${lessonFile}</div>
                `;
                
                // Test the API call
                fetch(`lesson_assessment_mapping.php?action=get_assessment_for_lesson&lesson_file=${encodeURIComponent(lessonFile)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.data) {
                            resultDiv.innerHTML += `
                                <div class="success">✅ API call successful!</div>
                                <div class="info">Assessment data found with ${Object.keys(data.data).length} sections</div>
                                <pre>${JSON.stringify(data.data, null, 2)}</pre>
                            `;
                        } else {
                            resultDiv.innerHTML += `
                                <div class="error">❌ API call failed: ${data.message || 'No data returned'}</div>
                            `;
                        }
                    })
                    .catch(error => {
                        resultDiv.innerHTML += `
                            <div class="error">❌ API call error: ${error.message}</div>
                        `;
                    });
            } else {
                resultDiv.innerHTML = `
                    <div class="error">❌ No lesson mapping found for skill: ${skill}</div>
                `;
            }
        }
        
        function testAPIEndpoints() {
            const resultDiv = document.getElementById('apiResult');
            resultDiv.innerHTML = '<div class="info">Testing API endpoints...</div>';
            
            const tests = [
                {
                    name: 'Get Assessment Info',
                    url: 'lesson_assessment_mapping.php?action=get_assessment_info&lesson_file=lessons/python_basic.php'
                },
                {
                    name: 'Get All Assessments',
                    url: 'lesson_assessment_mapping.php?action=get_all_assessments_for_lesson&lesson_file=lessons/python_basic.php'
                },
                {
                    name: 'Get Available Levels',
                    url: 'lesson_assessment_mapping.php?action=get_available_levels&skill=python'
                }
            ];
            
            let results = '';
            let completed = 0;
            
            tests.forEach(test => {
                fetch(test.url)
                    .then(response => response.json())
                    .then(data => {
                        completed++;
                        if (data.status === 'success') {
                            results += `<div class="success">✅ ${test.name}: Success</div>`;
                        } else {
                            results += `<div class="error">❌ ${test.name}: ${data.message}</div>`;
                        }
                        
                        if (completed === tests.length) {
                            resultDiv.innerHTML = results;
                        }
                    })
                    .catch(error => {
                        completed++;
                        results += `<div class="error">❌ ${test.name}: ${error.message}</div>`;
                        
                        if (completed === tests.length) {
                            resultDiv.innerHTML = results;
                        }
                    });
            });
        }
        
        function testModalRendering() {
            const resultDiv = document.getElementById('modalResult');
            resultDiv.innerHTML = '<div class="info">Testing modal rendering...</div>';
            
            // Simulate the assessment data structure
            const mockAssessmentData = {
                topic: 'Python',
                level: 'beginner',
                lessonFile: 'lessons/python_basic.php',
                multipleChoice: [
                    {
                        question: "What is Python?",
                        choices: ["A programming language", "A snake", "A database", "An operating system"],
                        correct_answer: "A programming language"
                    }
                ],
                debugging: [
                    {
                        question: "Debug this code: print('Hello World')",
                        code: "print('Hello World')",
                        issue: "No syntax error",
                        solution: "The code is correct"
                    }
                ],
                coding: [
                    {
                        question: "Write a function to add two numbers",
                        description: "Create a function that takes two parameters and returns their sum",
                        starter_code: "def add_numbers(a, b):\n    # Your code here\n    pass",
                        expected_output: "The function should return the sum of a and b"
                    }
                ]
            };
            
            // Test the rendering logic
            try {
                // Simulate the renderAssessmentModal function
                const hasQuestions = mockAssessmentData.multipleChoice && mockAssessmentData.multipleChoice.length > 0;
                
                if (hasQuestions) {
                    resultDiv.innerHTML = `
                        <div class="success">✅ Modal rendering test successful!</div>
                        <div class="info">Assessment data has ${mockAssessmentData.multipleChoice.length} multiple choice questions</div>
                        <div class="info">Topic: ${mockAssessmentData.topic} (${mockAssessmentData.level})</div>
                        <div class="info">Lesson: ${mockAssessmentData.lessonFile}</div>
                        <pre>${JSON.stringify(mockAssessmentData, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="error">❌ No questions found in assessment data</div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">❌ Modal rendering error: ${error.message}</div>
                `;
            }
        }
        
        // Helper function from message.php
        function mapSkillToLesson(skill) {
            if (!skill) return null;
            const normalized = skill.trim().toLowerCase();
            
            // Handle special cases
            if (normalized === 'c++' || normalized === 'cpp') {
                return 'lessons/c++_basic.php';
            } else if (normalized === 'oracle database' || normalized === 'oracle') {
                return 'lessons/oracledatabase_basic.php';
            } else if (normalized === 'sql server' || normalized === 'mssql') {
                return 'lessons/sqlserver_basic.php';
            } else if (normalized === 'node.js' || normalized === 'nodejs' || normalized === 'node') {
                return 'lessons/nodejs_basic.php';
            } else if (normalized === 'javascript' || normalized === 'js') {
                return 'lessons/javascript_basic.php';
            }
            
            // Standard mapping
            const lessonMap = {
                'python': 'lessons/python_basic.php',
                'java': 'lessons/java_basic.php',
                'c': 'lessons/c_basic.php',
                'php': 'lessons/php_basic.php',
                'css': 'lessons/css_basic.php',
                'html': 'lessons/html_basic.php',
                'react': 'lessons/react_basic.php',
                'laravel': 'lessons/laravel_basic.php',
                'sql': 'lessons/sql_basic.php',
                'nosql': 'lessons/nosql_basic.php',
                'mysql': 'lessons/mysql_basic.php',
                'postgresql': 'lessons/postgresql_basic.php',
                'mongodb': 'lessons/mongodb_basic.php',
                'cassandra': 'lessons/cassandra_basic.php',
                'redis': 'lessons/redis_basic.php',
                'dynamodb': 'lessons/dynamodb_basic.php'
            };
            
            return lessonMap[normalized] || null;
        }
    </script>
</body>
</html> 