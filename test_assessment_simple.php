<!DOCTYPE html>
<html>
<head>
    <title>Simple Assessment Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .question { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .choices { margin-top: 10px; }
        .choice { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Simple Assessment Test</h1>
    
    <button onclick="loadQuestions()">Load Python Questions</button>
    <button onclick="loadQuestionsDirect()">Load Questions Direct</button>
    
    <div id="questions"></div>
    
    <script>
        function loadQuestions() {
            console.log('Loading questions...');
            
            fetch('assessments_bank.json')
                .then(response => response.json())
                .then(data => {
                    console.log('Data loaded:', data);
                    
                    if (data.Python && data.Python.beginner && data.Python.beginner.multipleChoice) {
                        const questions = data.Python.beginner.multipleChoice;
                        console.log('Found questions:', questions.length);
                        
                        let html = '<h2>Python Beginner Questions</h2>';
                        questions.forEach((q, index) => {
                            html += `
                                <div class="question">
                                    <h3>Question ${index + 1}</h3>
                                    <p><strong>${q.question}</strong></p>
                                    <div class="choices">
                                        ${q.choices.map((choice, i) => `
                                            <div class="choice">
                                                <input type="radio" name="q${index}" value="${choice}">
                                                <label>${choice}</label>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;
                        });
                        
                        document.getElementById('questions').innerHTML = html;
                    } else {
                        document.getElementById('questions').innerHTML = '<p>No questions found</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('questions').innerHTML = '<p>Error loading questions: ' + error.message + '</p>';
                });
        }
        
        function loadQuestionsDirect() {
            console.log('Loading questions directly...');
            
            // Simulate the testAssessmentDirect function
            fetch('assessments_bank.json')
                .then(response => response.json())
                .then(data => {
                    console.log('Direct load - Data received:', data);
                    
                    if (data.Python && data.Python.beginner) {
                        const assessmentData = {
                            topic: 'Python',
                            level: 'beginner',
                            lessonFile: 'lessons/python_basic.php',
                            multipleChoice: data.Python.beginner.multipleChoice
                        };
                        
                        console.log('Direct load - Assessment data:', assessmentData);
                        
                        // Simulate rendering
                        let html = '<h2>Direct Load - Python Questions</h2>';
                        if (assessmentData.multipleChoice && assessmentData.multipleChoice.length > 0) {
                            assessmentData.multipleChoice.forEach((q, idx) => {
                                html += `
                                    <div class="question">
                                        <h3>Q${idx+1}: ${q.question}</h3>
                                        <div class="choices">
                                            ${q.choices.map((choice, cidx) => `
                                                <div class="choice">
                                                    <input type="radio" name="mcq_${idx}" value="${choice}">
                                                    <label>${choice}</label>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += '<p>No questions found in assessmentData.multipleChoice</p>';
                        }
                        
                        document.getElementById('questions').innerHTML = html;
                    } else {
                        document.getElementById('questions').innerHTML = '<p>No Python beginner data found</p>';
                    }
                })
                .catch(error => {
                    console.error('Direct load error:', error);
                    document.getElementById('questions').innerHTML = '<p>Error: ' + error.message + '</p>';
                });
        }
    </script>
</body>
</html> 