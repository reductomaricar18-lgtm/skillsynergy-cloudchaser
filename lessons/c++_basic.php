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
    <title>C++ Basics - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to C++</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>C++ is a powerful, high-performance programming language created by Bjarne Stroustrup as an extension of C. It supports procedural, object-oriented, and generic programming.</p>
          <h3>Why C++?</h3>
          <ul>
            <li><strong>Performance:</strong> Used for system/software, game engines, and real-time applications</li>
            <li><strong>Object-Oriented:</strong> Supports classes, inheritance, and polymorphism</li>
            <li><strong>Standard Library:</strong> Rich set of functions and containers</li>
            <li><strong>Portability:</strong> Runs on many platforms</li>
          </ul>
          <h3>Your First C++ Program</h3>
          <div class="code-block">
<span class="code-comment">// This is your first C++ program</span>
<span class="code-keyword">#include &lt;iostream&gt;</span>

<span class="code-keyword">int</span> main() {
    std::cout << <span class="code-string">"Hello, World!"</span> << std::endl;
    <span class="code-keyword">return</span> 0;
}
          </div>
          <div class="example-box">
            <h4>Output:</h4>
            <p>Hello, World!</p>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Every C++ program must have a <code>main()</code> function as the entry point.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Variables and Data Types</h2>
          <p>Variables in C++ must be declared with a specific data type. C++ is statically typed, so types are checked at compile time.</p>
          <h3>Declaring Variables</h3>
          <div class="code-block">
<span class="code-comment">// Variable declaration and initialization</span>
<span class="code-keyword">int</span> age = <span class="code-number">25</span>;
<span class="code-keyword">double</span> height = <span class="code-number">5.9</span>;
<span class="code-keyword">std::string</span> name = <span class="code-string">"Alice"</span>;
<span class="code-keyword">bool</span> isStudent = <span class="code-keyword">true</span>;

<span class="code-comment">// Declaration first, then assignment</span>
<span class="code-keyword">int</span> score;
score = <span class="code-number">95</span>;
          </div>
          <h3>Primitive Data Types</h3>
          <ul>
            <li><strong>int:</strong> Integer</li>
            <li><strong>double:</strong> Double-precision floating point</li>
            <li><strong>float:</strong> Single-precision floating point</li>
            <li><strong>char:</strong> Character</li>
            <li><strong>bool:</strong> Boolean (true/false)</li>
          </ul>
          <h3>Strings</h3>
          <div class="code-block">
<span class="code-comment">// String (requires &lt;string&gt; header)</span>
#include &lt;string&gt;
std::string greeting = <span class="code-string">"Hello"</span>;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>auto</code> for type inference (C++11+): <code>auto x = 5;</code>
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Control Flow</h2>
          <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
          <h3>If Statements</h3>
          <div class="code-block">
<span class="code-comment">// Simple if statement</span>
<span class="code-keyword">int</span> age = <span class="code-number">18</span>;
if (age >= <span class="code-number">18</span>) {
    std::cout << <span class="code-string">"You are an adult"</span> << std::endl;
} else if (age >= <span class="code-number">13</span>) {
    std::cout << <span class="code-string">"You are a teenager"</span> << std::endl;
} else {
    std::cout << <span class="code-string">"You are a child"</span> << std::endl;
}
          </div>
          <h3>Loops</h3>
          <div class="code-block">
<span class="code-comment">// For loop</span>
for (<span class="code-keyword">int</span> i = 0; i < 5; i++) {
    std::cout << i << std::endl;
}

<span class="code-comment">// While loop</span>
<span class="code-keyword">int</span> count = 0;
while (count < 3) {
    std::cout << "Count: " << count << std::endl;
    count++;
}
          </div>
          <h3>Switch Statement</h3>
          <div class="code-block">
<span class="code-comment">// Switch statement</span>
<span class="code-keyword">int</span> day = 3;
switch (day) {
    case 1:
        std::cout << "Monday" << std::endl;
        break;
    case 2:
        std::cout << "Tuesday" << std::endl;
        break;
    default:
        std::cout << "Other day" << std::endl;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>break</code> to exit a switch case. If omitted, execution "falls through" to the next case.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Functions</h2>
          <p>Functions are reusable blocks of code that perform specific tasks. They help organize code and avoid repetition.</p>
          <div class="tip-box">
            <strong>💡 Tip:</strong> C++ supports default arguments and function overloading.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Classes and Objects</h2>
          <p>C++ is an object-oriented language. Classes are blueprints for creating objects.</p>
          <h3>Defining a Class</h3>
          <div class="code-block">
<span class="code-comment">// Class definition</span>
class Person {
public:
    std::string name;
    int age;
    void greet() {
        std::cout << "Hello, my name is " << name << std::endl;
    }
};

<span class="code-comment">// Creating and using an object</span>
Person p;
p.name = "Alice";
p.age = 25;
p.greet();
          </div>
          <h3>Constructors</h3>
          <div class="code-block">
<span class="code-comment">// Constructor example</span>
class Point {
public:
    int x, y;
    Point(int xVal, int yVal) {
        x = xVal;
        y = yVal;
    }
};
Point pt(3, 4);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>public:</code> and <code>private:</code> to control access to class members.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Standard Template Library (STL)</h2>
          <p>The STL provides useful classes and functions for data structures and algorithms.</p>
          <h3>Common STL Containers</h3>
          <ul>
            <li><strong>vector</strong> - Dynamic array</li>
            <li><strong>map</strong> - Key-value pairs</li>
            <li><strong>set</strong> - Unique values</li>
            <li><strong>queue</strong>, <strong>stack</strong> - FIFO/LIFO structures</li>
          </ul>
          <div class="code-block">
<span class="code-keyword">#include &lt;vector&gt;</span>
std::vector<int> nums = {1, 2, 3};
nums.push_back(4);
for (int n : nums) {
    std::cout << n << std::endl;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>#include &lt;algorithm&gt;</code> for common algorithms like <code>sort()</code>.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C++ Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to C++</button>
          <button class="topic-btn" data-index="1">Variables and Data Types</button>
          <button class="topic-btn" data-index="2">Control Flow</button>
          <button class="topic-btn" data-index="3">Functions</button>
          <button class="topic-btn" data-index="4">Classes and Objects</button>
          <button class="topic-btn" data-index="5">Standard Template Library (STL)</button>
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