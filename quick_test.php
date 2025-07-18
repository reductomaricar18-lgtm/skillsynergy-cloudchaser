<!DOCTYPE html>
<html>
<head>
    <title>Quick Assessment Test</title>
</head>
<body>
    <h1>Quick Assessment Test</h1>
    
    <div id="result"></div>
    
    <script>
        console.log('Testing assessment bank...');
        
        fetch('assessments_bank.json')
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                return response.json();
            })
            .then(data => {
                console.log('Assessment bank loaded successfully!');
                console.log('Data keys:', Object.keys(data));
                console.log('Python available:', !!data.Python);
                console.log('Python beginner available:', !!(data.Python && data.Python.beginner));
                console.log('Python beginner questions:', data.Python && data.Python.beginner ? data.Python.beginner.multipleChoice.length : 0);
                
                document.getElementById('result').innerHTML = `
                    <h2>✅ Assessment Bank Test Results</h2>
                    <p><strong>Status:</strong> Successfully loaded</p>
                    <p><strong>Topics:</strong> ${Object.keys(data).join(', ')}</p>
                    <p><strong>Python Available:</strong> ${data.Python ? 'YES' : 'NO'}</p>
                    <p><strong>Python Beginner Available:</strong> ${data.Python && data.Python.beginner ? 'YES' : 'NO'}</p>
                    <p><strong>Python Beginner Questions:</strong> ${data.Python && data.Python.beginner ? data.Python.beginner.multipleChoice.length : 0}</p>
                    <h3>Sample Question:</h3>
                    <pre>${JSON.stringify(data.Python && data.Python.beginner ? data.Python.beginner.multipleChoice[0] : 'No questions found', null, 2)}</pre>
                `;
            })
            .catch(error => {
                console.error('Error loading assessment bank:', error);
                document.getElementById('result').innerHTML = `
                    <h2>❌ Assessment Bank Test Failed</h2>
                    <p><strong>Error:</strong> ${error.message}</p>
                `;
            });
    </script>
</body>
</html> 