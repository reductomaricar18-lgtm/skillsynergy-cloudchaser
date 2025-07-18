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
    <title>Node.js Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
        }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #0056b3; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #f0f4ff; color: #4a63ff; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .code-comment { color: #68d391; }
        .code-keyword { color: #f6ad55; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #2c7a7b; margin-top: 0; }
        .tip-box { background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #c05621; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #2d3748; }
        .highlight { background: #fef5e7; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Event Loop & Async Programming</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Node.js uses an event-driven, non-blocking I/O model. The event loop allows Node.js to handle many connections efficiently.</p>
          <div class="code-block">
<span class="code-comment">// Asynchronous example</span>
const fs = require('fs');
fs.readFile('file.txt', 'utf8', (err, data) => {
  if (err) throw err;
  console.log(data);
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use callbacks, promises, or async/await for asynchronous code.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Modules & require</h2>
          <p>Node.js code is organized into modules. Use <code>require()</code> to import modules and <code>module.exports</code> to export.</p>
          <div class="code-block">
<span class="code-comment">// math.js</span>
exports.add = (a, b) => a + b;

<span class="code-comment">// app.js</span>
const math = require('./math');
console.log(math.add(2, 3));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use built-in modules (fs, path, http) and npm packages for more features.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>npm & package.json</h2>
          <p>npm is the Node.js package manager. <code>package.json</code> describes your project and its dependencies.</p>
          <div class="code-block">
<span class="code-comment">// Initialize a project</span>
npm init -y

<span class="code-comment">// Install a package</span>
npm install express

<span class="code-comment">// package.json example</span>
{
  "name": "myapp",
  "version": "1.0.0",
  "dependencies": {
    "express": "^4.18.2"
  }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>npm install --save</code> for dependencies and <code>npm install --save-dev</code> for dev dependencies.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>File System (fs)</h2>
          <p>The <code>fs</code> module allows you to interact with the file system.</p>
          <div class="code-block">
const fs = require('fs');

<span class="code-comment">// Read a file</span>
fs.readFile('example.txt', 'utf8', (err, data) => {
  if (err) throw err;
  console.log(data);
});

<span class="code-comment">// Write a file</span>
fs.writeFile('output.txt', 'Hello, Node.js!', err => {
  if (err) throw err;
  console.log('File written!');
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>fs.promises</code> or <code>util.promisify</code> for promise-based file operations.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Streams</h2>
          <p>Streams are used for reading or writing data piece by piece, especially for large files.</p>
          <div class="code-block">
const fs = require('fs');

<span class="code-comment">// Read stream</span>
const readStream = fs.createReadStream('largefile.txt');
readStream.on('data', chunk => {
  console.log('Received:', chunk.length);
});

<span class="code-comment">// Write stream</span>
const writeStream = fs.createWriteStream('output.txt');
writeStream.write('Hello, stream!');
writeStream.end();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Streams are memory-efficient for large data processing.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Error Handling</h2>
          <p>Handle errors using callbacks, try/catch (with promises or async/await), and event listeners.</p>
          <div class="code-block">
<span class="code-comment">// Callback error handling</span>
fs.readFile('nofile.txt', (err, data) => {
  if (err) {
    console.error('Error:', err.message);
    return;
  }
  console.log(data);
});

<span class="code-comment">// Async/await error handling</span>
(async () => {
  try {
    const data = await fs.promises.readFile('nofile.txt');
    console.log(data);
  } catch (err) {
    console.error('Error:', err.message);
  }
})();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always handle errors to prevent crashes and improve reliability.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Express.js Basics</h2>
          <p>Express.js is a popular web framework for Node.js. It simplifies building web servers and APIs.</p>
          <div class="code-block">
const express = require('express');
const app = express();

app.get('/', (req, res) => {
  res.send('Hello, Express!');
});

app.listen(3000, () => {
  console.log('Server running on port 3000');
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Express middleware for parsing, logging, and authentication.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Node.js Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Event Loop & Async Programming</button>
          <button class="topic-btn" data-index="1">Modules & require</button>
          <button class="topic-btn" data-index="2">npm & package.json</button>
          <button class="topic-btn" data-index="3">File System (fs)</button>
          <button class="topic-btn" data-index="4">Streams</button>
          <button class="topic-btn" data-index="5">Error Handling</button>
          <button class="topic-btn" data-index="6">Express.js Basics</button>
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