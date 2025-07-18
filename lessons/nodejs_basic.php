<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Node.js Basics - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .lesson-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 100px;
            max-height: 400px;
            position: relative;
        }
        .lesson-scrollable {
            flex: 1 1 auto;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 16px;
            border-radius: 0 0 0 0;
            margin-bottom: 0;
            min-height: 160px;
            height: auto;
            position: relative;
            max-height: 400px;
        }
        .lesson-section {
            display: none;
        }
        .lesson-section.active {
            display: block;
        }
        .lesson-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-top: 5px solid #e2e8f0;
            padding: 16px 48px 16px 48px;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
            z-index: 2;
            flex: 0 0 auto;
            box-sizing: border-box;
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .lesson-nav button {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 18px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
            margin: 0 16px;
        }
        .lesson-nav button:disabled {
            background: #b0b0b0;
            cursor: not-allowed;
        }
        .lesson-nav button:hover:not(:disabled) {
            background: #0056b3;
        }
        #otherTopicsDropdown {
            display: none;
            position: absolute;
            bottom: 48px;
            right: 0;
            min-width: 220px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            z-index: 10;
            padding: 6px 0;
        }
        #otherTopicsDropdown .topic-btn {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 10px 20px;
            text-align: left;
            font-size: 1rem;
            color: #333;
            cursor: pointer;
            transition: background 0.2s;
        }
        #otherTopicsDropdown .topic-btn:hover {
            background: #f0f4ff;
            color: #4a63ff;
        }
        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
        }
        .example-box {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }
        .tip-box {
            background: #fef5e7;
            border: 1px solid #f6ad55;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .tip-box strong {
            color: #c05621;
        }
        ul, ol {
            margin: 15px 0;
            padding-left: 30px;
        }
        li {
            margin: 8px 0;
        }
        strong {
            color: #2d3748;
        }
        .highlight {
            background: #fef5e7;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to Node.js</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Node.js is a runtime environment that allows you to run JavaScript on the server side. It is built on Chrome's V8 JavaScript engine and is widely used for building scalable network applications.</p>
          <h3>Why Node.js?</h3>
          <ul>
            <li><strong>Non-blocking I/O:</strong> Handles many connections efficiently</li>
            <li><strong>JavaScript Everywhere:</strong> Use JS for both client and server</li>
            <li><strong>Large Ecosystem:</strong> npm provides thousands of packages</li>
            <li><strong>Fast and Scalable:</strong> Great for real-time apps and APIs</li>
          </ul>
          <h3>Your First Node.js Program</h3>
          <div class="code-block">
// hello.js
console.log("Hello, World!");
          </div>
          <div class="example-box">
            <h4>Output:</h4>
            <p>Hello, World!</p>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Run Node.js files with <code>node filename.js</code> in your terminal.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Modules in Node.js</h2>
          <p>Modules help you organize your code into reusable files. Node.js has built-in modules and supports custom modules.</p>
          <h3>Importing Built-in Modules</h3>
          <div class="code-block">
const fs = require('fs'); // File system module
const http = require('http'); // HTTP server module
          </div>
          <h3>Creating Your Own Module</h3>
          <div class="code-block">
// math.js
exports.add = (a, b) => a + b;
exports.sub = (a, b) => a - b;

// app.js
const math = require('./math');
console.log(math.add(2, 3)); // 5
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>module.exports</code> or <code>exports</code> to export functions or objects from a module.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>File System (fs) Module</h2>
          <p>The <code>fs</code> module allows you to work with the file system: reading, writing, and managing files.</p>
          <h3>Reading a File</h3>
          <div class="code-block">
const fs = require('fs');
fs.readFile('example.txt', 'utf8', (err, data) => {
    if (err) throw err;
    console.log(data);
});
          </div>
          <h3>Writing to a File</h3>
          <div class="code-block">
fs.writeFile('output.txt', 'Hello, Node.js!', err => {
    if (err) throw err;
    console.log('File written!');
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>fs.promises</code> for promise-based file operations in modern Node.js.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Creating an HTTP Server</h2>
          <p>Node.js can create web servers using the <code>http</code> module.</p>
          <div class="code-block">
const http = require('http');
const server = http.createServer((req, res) => {
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('Hello from Node.js server!');
});
server.listen(3000, () => {
    console.log('Server running on port 3000');
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Visit <code>http://localhost:3000</code> in your browser to see your server in action.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>npm and Packages</h2>
          <p>npm (Node Package Manager) is used to install and manage packages (libraries) in Node.js projects.</p>
          <h3>Initializing a Project</h3>
          <div class="code-block">
npm init -y
          </div>
          <h3>Installing a Package</h3>
          <div class="code-block">
npm install express
          </div>
          <h3>Using a Package</h3>
          <div class="code-block">
const express = require('express');
const app = express();
app.get('/', (req, res) => res.send('Hello Express!'));
app.listen(3000);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>package.json</code> to manage your project's dependencies.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Node.js Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to Node.js</button>
          <button class="topic-btn" data-index="1">Modules</button>
          <button class="topic-btn" data-index="2">File System</button>
          <button class="topic-btn" data-index="3">HTTP Server</button>
          <button class="topic-btn" data-index="4">npm and Packages</button>
        </div>
      </div>
    </div>
    <script>
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        let current = 0;
        const prevBtn = document.getElementById('prevLessonBtn');
        const nextBtn = document.getElementById('nextLessonBtn');
        const pageInfo = document.getElementById('lessonPageInfo');
        function showSection(idx) {
            sections.forEach((sec, i) => {
                sec.classList.toggle('active', i === idx);
            });
            prevBtn.disabled = idx === 0;
            nextBtn.disabled = idx === sections.length - 1;
            pageInfo.textContent = `Section ${idx + 1} of ${sections.length}`;
            current = idx;
        }
        prevBtn.onclick = function() {
            if (current > 0) {
                current--;
                showSection(current);
            }
        };
        nextBtn.onclick = function() {
            if (current < sections.length - 1) {
                current++;
                showSection(current);
            }
        };
        showSection(current);
        document.getElementById('otherTopicsBtn').onclick = function() {
            var dropdown = document.getElementById('otherTopicsDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.querySelectorAll('.topic-btn').forEach(function(btn) {
            btn.onclick = function() {
                var idx = parseInt(btn.getAttribute('data-index'));
                showSection(idx);
                document.getElementById('otherTopicsDropdown').style.display = 'none';
            };
        });
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('otherTopicsDropdown');
            var btn = document.getElementById('otherTopicsBtn');
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html>