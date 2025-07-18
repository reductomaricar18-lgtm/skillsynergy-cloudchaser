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
    <title>Node.js Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
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
          <h2>Advanced Async: Events & EventEmitter</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Node.js uses the <code>EventEmitter</code> class for custom events and advanced async patterns.</p>
          <div class="code-block">
const EventEmitter = require('events');
const emitter = new EventEmitter();
emitter.on('greet', name => {
  console.log('Hello,', name);
});
emitter.emit('greet', 'Alice');
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>EventEmitter</code> for decoupled, event-driven code.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Async/Await Patterns</h2>
          <p>Async/await makes asynchronous code easier to read and maintain.</p>
          <div class="code-block">
<span class="code-comment">// Async/await with Promises</span>
const fs = require('fs').promises;
(async () => {
  try {
    const data = await fs.readFile('file.txt', 'utf8');
    console.log(data);
  } catch (err) {
    console.error('Error:', err.message);
  }
})();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use try/catch with async/await to handle errors.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Express Middleware & Routing</h2>
          <p>Middleware functions in Express.js process requests and responses. Routing defines endpoints for your app.</p>
          <div class="code-block">
const express = require('express');
const app = express();

// Middleware
app.use((req, res, next) => {
  console.log('Request:', req.method, req.url);
  next();
});

// Routing
app.get('/hello', (req, res) => {
  res.send('Hello, world!');
});

app.listen(3000);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Middleware can be used for logging, authentication, error handling, and more.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>REST APIs</h2>
          <p>Build RESTful APIs with Express.js for CRUD operations.</p>
          <div class="code-block">
const express = require('express');
const app = express();
app.use(express.json());

let items = [];
app.post('/items', (req, res) => {
  items.push(req.body);
  res.status(201).send('Item added');
});
app.get('/items', (req, res) => {
  res.json(items);
});
app.listen(3000);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use HTTP status codes and JSON for API responses.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Authentication (JWT & Sessions)</h2>
          <p>Secure your Node.js apps using JWT (JSON Web Tokens) or session-based authentication.</p>
          <div class="code-block">
<span class="code-comment">// JWT example (using jsonwebtoken package)</span>
const jwt = require('jsonwebtoken');
const token = jwt.sign({ userId: 123 }, 'secret', { expiresIn: '1h' });
const decoded = jwt.verify(token, 'secret');

<span class="code-comment">// Express-session example</span>
const session = require('express-session');
app.use(session({ secret: 'mysecret', resave: false, saveUninitialized: true }));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Never store sensitive data in JWT payloads. Always use HTTPS in production.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Testing (Mocha & Chai)</h2>
          <p>Write automated tests for your Node.js code using Mocha (test runner) and Chai (assertion library).</p>
          <div class="code-block">
<span class="code-comment">// test.js</span>
const assert = require('chai').assert;
describe('Math', () => {
  it('should add numbers', () => {
    assert.equal(2 + 3, 5);
  });
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Run tests with <code>mocha</code> and use <code>chai</code> for expressive assertions.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Debugging & Performance</h2>
          <ul>
            <li>Use <code>console.log</code>, <code>debug</code> module, and Node.js Inspector (<code>node --inspect</code>).</li>
            <li>Profile with <code>node --prof</code> and analyze with Chrome DevTools.</li>
            <li>Monitor memory usage and event loop lag.</li>
            <li>Optimize code: avoid blocking, use clustering, and cache results.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Efficient debugging and profiling are essential for scalable Node.js applications.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Node.js Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Advanced Async: Events & EventEmitter</button>
          <button class="topic-btn" data-index="1">Async/Await Patterns</button>
          <button class="topic-btn" data-index="2">Express Middleware & Routing</button>
          <button class="topic-btn" data-index="3">REST APIs</button>
          <button class="topic-btn" data-index="4">Authentication (JWT & Sessions)</button>
          <button class="topic-btn" data-index="5">Testing (Mocha & Chai)</button>
          <button class="topic-btn" data-index="6">Debugging & Performance</button>
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