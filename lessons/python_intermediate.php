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
    <title>Python Intermediate - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); min-height: 100vh; }
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
          <h2>List Comprehensions</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>List comprehensions provide a concise way to create lists.</p>
          <div class="code-block">
<span class="code-comment"># Create a list of squares</span>
squares = [x**2 for x in range(10)]
print(squares)  # [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> You can add conditions: <code>[x for x in range(10) if x % 2 == 0]</code>
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Lambda & Higher-Order Functions</h2>
          <p>Lambdas are small anonymous functions. Higher-order functions take functions as arguments.</p>
          <div class="code-block">
<span class="code-comment"># Lambda function</span>
double = lambda x: x * 2
print(double(5))  # 10

<span class="code-comment"># map, filter, reduce</span>
nums = [1, 2, 3, 4]
doubled = list(map(lambda x: x*2, nums))
evens = list(filter(lambda x: x%2==0, nums))
from functools import reduce
product = reduce(lambda x, y: x*y, nums)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>lambda</code> for short, throwaway functions.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Modules & Packages</h2>
          <p>Organize code into modules and packages for reusability.</p>
          <div class="code-block">
<span class="code-comment"># mymodule.py</span>
def greet(name):
    print(f"Hello, {name}!")

<span class="code-comment"># main.py</span>
import mymodule
mymodule.greet("Alice")
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>__init__.py</code> to make a directory a package.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Error Handling (try/except)</h2>
          <p>Handle exceptions gracefully using <code>try</code> and <code>except</code>.</p>
          <div class="code-block">
<span class="code-comment"># Error handling</span>
try:
    x = 1 / 0
except ZeroDivisionError as e:
    print("Error:", e)
finally:
    print("This always runs.")
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Catch specific exceptions for better error handling.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>File I/O (Text & CSV)</h2>
          <p>Read and write files, including CSV files, using built-in modules.</p>
          <div class="code-block">
<span class="code-comment"># Text file I/O</span>
with open('file.txt', 'w') as f:
    f.write('Hello!')
with open('file.txt', 'r') as f:
    print(f.read())

<span class="code-comment"># CSV file I/O</span>
import csv
with open('data.csv', 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['name', 'age'])
    writer.writerow(['Alice', 30])
with open('data.csv', 'r') as f:
    reader = csv.reader(f)
    for row in reader:
        print(row)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use <code>with</code> for file operations to ensure files are closed.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>OOP Basics (Classes & Inheritance)</h2>
          <p>Object-Oriented Programming lets you model real-world entities.</p>
          <div class="code-block">
<span class="code-comment"># Class and inheritance</span>
class Animal:
    def __init__(self, name):
        self.name = name
    def speak(self):
        print(f"{self.name} makes a sound.")

class Dog(Animal):
    def speak(self):
        print(f"{self.name} barks.")

d = Dog("Rex")
d.speak()  # Rex barks.
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>super()</code> to call parent methods.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Virtual Environments</h2>
          <p>Use virtual environments to manage dependencies for different projects.</p>
          <div class="code-block">
<span class="code-comment"># Create and activate a virtual environment</span>
python -m venv venv
# On Windows:
venv\Scripts\activate
# On Mac/Linux:
source venv/bin/activate

<span class="code-comment"># Install packages</span>
pip install requests
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>requirements.txt</code> to freeze and share dependencies.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Python Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">List Comprehensions</button>
          <button class="topic-btn" data-index="1">Lambda & Higher-Order Functions</button>
          <button class="topic-btn" data-index="2">Modules & Packages</button>
          <button class="topic-btn" data-index="3">Error Handling (try/except)</button>
          <button class="topic-btn" data-index="4">File I/O (Text & CSV)</button>
          <button class="topic-btn" data-index="5">OOP Basics (Classes & Inheritance)</button>
          <button class="topic-btn" data-index="6">Virtual Environments</button>
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