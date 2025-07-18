<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Assessment Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .error { color: red; }
        .success { color: green; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 300px; }
    </style>
</head>
<body>
    <h1>Simple Assessment Test</h1>
    
    <div class="test-section">
        <h3>Test 1: Direct API Call</h3>
        <button onclick="testDirectAPI()">Test Direct API</button>
        <div id="apiResult"></div>
    </div>
    
    <div class="test-section">
        <h3>Test 2: Assessment Bank Loading</h3>
        <button onclick="testAssessmentBank()">Test Assessment Bank</button>
        <div id="bankResult"></div>
    </div>
    
    <div class="test-section">
        <h3>Test 3: Simulate Assessment Modal</h3>
        <button onclick="testModal()">Test Modal</button>
        <div id="modalResult"></div>
    </div>

    <script>
        function testDirectAPI() {
            const resultDiv = document.getElementById('apiResult');
            resultDiv.innerHTML = '<div class="info">Testing direct API call...</div>';
            
            fetch('lesson_assessment_mapping.php?action=get_assessment_for_lesson&lesson_file=lessons/python_basic.php')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API response:', data);
                    if (data.status === 'success' && data.data) {
                        resultDiv.innerHTML = `
                            <div class="success">✅ API call successful!</div>
                            <div class="info">Data structure: ${Object.keys(data.data).join(', ')}</div>
                            <div class="info">Multiple choice questions: ${data.data.multipleChoice ? data.data.multipleChoice.length : 0}</div>
                            <pre>${JSON.stringify(data.data, null, 2)}</pre>
                        `;
                    } else {
                        resultDiv.innerHTML = `
                            <div class="error">❌ API call failed: ${data.message || 'No data returned'}</div>
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        `;
                    }
                })
                .catch(error => {
                    console.error('API error:', error);
                    resultDiv.innerHTML = `
                        <div class="error">❌ API call error: ${error.message}</div>
                    `;
                });
        }
        
        function testAssessmentBank() {
            const resultDiv = document.getElementById('bankResult');
            resultDiv.innerHTML = '<div class="info">Loading assessment bank...</div>';
            
            fetch('assessments_bank.json')
                .then(response => response.json())
                .then(data => {
                    console.log('Assessment bank:', data);
                    if (data.Python && data.Python.beginner) {
                        resultDiv.innerHTML = `
                            <div class="success">✅ Assessment bank loaded!</div>
                            <div class="info">Python beginner questions: ${data.Python.beginner.multipleChoice.length}</div>
                            <pre>${JSON.stringify(data.Python.beginner, null, 2)}</pre>
                        `;
                    } else {
                        resultDiv.innerHTML = `
                            <div class="error">❌ Python beginner data not found</div>
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Bank error:', error);
                    resultDiv.innerHTML = `
                        <div class="error">❌ Assessment bank error: ${error.message}</div>
                    `;
                });
        }
        
        function testModal() {
            const resultDiv = document.getElementById('modalResult');
            resultDiv.innerHTML = '<div class="info">Testing modal simulation...</div>';
            
            // Simulate the assessment data structure
            const assessmentData = {
                topic: 'Python',
                level: 'beginner',
                lessonFile: 'lessons/python_basic.php',
                multipleChoice: [
                    {
                        question: "Which keyword is used to define a function in Python?",
                        choices: ["def", "function", "fun", "define"],
                        answer: "def"
                    },
                    {
                        question: "Which function is used to print output in Python?",
                        choices: ["print", "echo", "printf", "write"],
                        answer: "print"
                    }
                ]
            };
            
            // Simulate the rendering logic
            let html = '';
            if (assessmentData.multipleChoice && assessmentData.multipleChoice.length > 0) {
                assessmentData.multipleChoice.forEach((q, idx) => {
                    html += `
                        <div class="question-container">
                            <div class="question-text"><b>Q${idx+1}:</b> ${q.question}</div>
                            <div class="question-choices">
                                ${q.choices.map((choice, cidx) => `
                                    <label class="choice-option">
                                        <input type="radio" name="mcq_${idx}" value="${choice}">
                                        ${choice}
                                    </label>
                                `).join('')}
                            </div>
                        </div>
                    `;
                });
            }
            
            resultDiv.innerHTML = `
                <div class="success">✅ Modal simulation successful!</div>
                <div class="info">Topic: ${assessmentData.topic} (${assessmentData.level})</div>
                <div class="info">Questions found: ${assessmentData.multipleChoice.length}</div>
                <div style="border: 1px solid #ccc; padding: 15px; margin: 10px 0;">
                    <h4>Rendered HTML:</h4>
                    ${html}
                </div>
            `;
        }
    </script>
</body>
</html> 