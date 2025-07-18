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
    <title>JavaScript Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
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
          <h2>Objects & Prototypes</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Objects are collections of key-value pairs. Prototypes allow inheritance and sharing methods between objects.</p>
          <div class="code-block">
<span class="code-comment">// Object literal</span>
const person = {
  name: 'Alice',
  greet() { console.log(`Hello, I'm ${this.name}`); }
};
person.greet();

<span class="code-comment">// Prototypes</span>
function Animal(name) { this.name = name; }
Animal.prototype.speak = function() {
  console.log(`${this.name} makes a noise.`);
};
const dog = new Animal('Rex');
dog.speak();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> All objects inherit from <code>Object.prototype</code> unless you specify otherwise.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Closures</h2>
          <p>A closure is a function that remembers its lexical scope even when executed outside that scope.</p>
          <div class="code-block">
function makeCounter() {
  let count = 0;
  return function() {
    count++;
    return count;
  };
}
const counter = makeCounter();
console.log(counter()); // 1
console.log(counter()); // 2
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Closures are used for data privacy and function factories.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Higher-Order Functions</h2>
          <p>Functions that take other functions as arguments or return them are called higher-order functions.</p>
          <div class="code-block">
function greet(name) {
  return `Hello, ${name}!`;
}
function processUser(name, callback) {
  return callback(name);
}
console.log(processUser('Bob', greet));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Array methods like <code>map</code>, <code>filter</code>, and <code>reduce</code> are higher-order functions.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Array Methods: map, filter, reduce</h2>
          <p>Modern JavaScript provides powerful array methods for transformation and aggregation.</p>
          <div class="code-block">
const nums = [1, 2, 3, 4, 5];
const squares = nums.map(x => x * x); // [1, 4, 9, 16, 25]
const evens = nums.filter(x => x % 2 === 0); // [2, 4]
const sum = nums.reduce((acc, x) => acc + x, 0); // 15
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> These methods are chainable and do not mutate the original array.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Asynchronous JavaScript: Callbacks & Promises</h2>
          <p>JavaScript handles async operations using callbacks and promises.</p>
          <div class="code-block">
<span class="code-comment">// Callback</span>
setTimeout(() => {
  console.log('Hello after 1 second');
}, 1000);

<span class="code-comment">// Promise</span>
function asyncAdd(a, b) {
  return new Promise(resolve => {
    setTimeout(() => resolve(a + b), 500);
  });
}
asyncAdd(2, 3).then(result => console.log(result));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Promises make async code easier to read and maintain than nested callbacks.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>ES6 Features</h2>
          <ul>
            <li><strong>let/const:</strong> Block-scoped variable declarations</li>
            <li><strong>Arrow Functions:</strong> <code>(a) =&gt; a * 2</code></li>
            <li><strong>Template Literals:</strong> <code>\`Hello, ${name}\`</code></li>
            <li><strong>Destructuring:</strong> <code>const [a, b] = arr;</code>, <code>const {x, y} = obj;</code></li>
          </ul>
          <div class="code-block">
const person = { name: 'Eve', age: 30 };
const { name, age } = person;
console.log(`${name} is ${age}`);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>let</code> for variables that change, <code>const</code> for constants.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other JS Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Objects & Prototypes</button>
          <button class="topic-btn" data-index="1">Closures</button>
          <button class="topic-btn" data-index="2">Higher-Order Functions</button>
          <button class="topic-btn" data-index="3">Array Methods</button>
          <button class="topic-btn" data-index="4">Async JS</button>
          <button class="topic-btn" data-index="5">ES6 Features</button>
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