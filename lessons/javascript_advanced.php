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
    <title>JavaScript Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
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
          <h2>Classes & Inheritance</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>ES6 introduced <code>class</code> syntax for defining objects and inheritance in a more readable way.</p>
          <div class="code-block">
class Animal {
  constructor(name) {
    this.name = name;
  }
  speak() {
    console.log(`${this.name} makes a noise.`);
  }
}
class Dog extends Animal {
  speak() {
    console.log(`${this.name} barks.`);
  }
}
const d = new Dog('Rex');
d.speak(); // Rex barks.
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Classes are syntactic sugar over JavaScript's prototype-based inheritance.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Async/Await</h2>
          <p><code>async</code> and <code>await</code> make working with Promises easier and code more readable.</p>
          <div class="code-block">
function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
async function run() {
  await delay(1000);
  console.log('1 second passed');
}
run();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> <code>await</code> can only be used inside <code>async</code> functions.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Generators & Iterators</h2>
          <p>Generators are functions that can be paused and resumed, making it easy to create iterators.</p>
          <div class="code-block">
function* gen() {
  yield 1;
  yield 2;
  yield 3;
}
const g = gen();
console.log(g.next().value); // 1
console.log(g.next().value); // 2
console.log(g.next().value); // 3
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>for...of</code> to iterate over generator results.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Modules (import/export)</h2>
          <p>JavaScript modules allow you to split code into reusable files using <code>import</code> and <code>export</code>.</p>
          <div class="code-block">
// math.js
export function add(a, b) { return a + b; }

// main.js
import { add } from './math.js';
console.log(add(2, 3));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Modules are supported in modern browsers and Node.js (with <code>type="module"</code> or <code>.mjs</code> extension).
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Error Handling</h2>
          <p>Use <code>try...catch</code> for error handling and create custom error types for better debugging.</p>
          <div class="code-block">
try {
  throw new Error('Something went wrong!');
} catch (e) {
  console.error(e.message);
}

class CustomError extends Error {
  constructor(msg) {
    super(msg);
    this.name = 'CustomError';
  }
}
throw new CustomError('Custom error occurred');
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always handle errors in async code using <code>catch</code> or <code>try...catch</code> with <code>async/await</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Event Loop & Microtasks</h2>
          <p>The event loop handles asynchronous operations, microtasks (promises), and macrotasks (timers, I/O).</p>
          <div class="code-block">
console.log('Start');
setTimeout(() => console.log('Timeout'), 0);
Promise.resolve().then(() => console.log('Promise'));
console.log('End');
// Output: Start, End, Promise, Timeout
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Microtasks (promises) run before macrotasks (timers) after the current call stack.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Performance Optimization</h2>
          <ul>
            <li>Minimize DOM manipulation</li>
            <li>Use event delegation</li>
            <li>Debounce/throttle expensive operations</li>
            <li>Profile code with browser dev tools</li>
            <li>Use web workers for heavy computation</li>
          </ul>
          <div class="code-block">
// Debounce example
function debounce(fn, delay) {
  let timer;
  return function(...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always measure before optimizing. Use <code>console.time</code> and browser profilers.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other JS Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Classes & Inheritance</button>
          <button class="topic-btn" data-index="1">Async/Await</button>
          <button class="topic-btn" data-index="2">Generators & Iterators</button>
          <button class="topic-btn" data-index="3">Modules</button>
          <button class="topic-btn" data-index="4">Error Handling</button>
          <button class="topic-btn" data-index="5">Event Loop</button>
          <button class="topic-btn" data-index="6">Performance Optimization</button>
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