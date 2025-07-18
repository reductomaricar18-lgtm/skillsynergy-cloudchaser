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
    <title>Python Basics - SkillSynergy</title>
    <style>
      body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
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
          <h2>Introduction to Python</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Python is a high-level, interpreted programming language known for its simplicity and readability. Created by Guido van Rossum in 1991, Python has become one of the most popular programming languages worldwide.</p>
          <h3>Why Python?</h3>
          <ul>
            <li><strong>Readable Syntax:</strong> Python's syntax is clean and easy to understand</li>
            <li><strong>Versatile:</strong> Used in web development, data science, AI, automation, and more</li>
            <li><strong>Large Community:</strong> Extensive libraries and frameworks available</li>
            <li><strong>Cross-platform:</strong> Runs on Windows, macOS, and Linux</li>
          </ul>
          <h3>Your First Python Program</h3>
          <div class="code-block">
<span class="code-comment"># This is your first Python program</span>
<span class="code-keyword">print</span>(<span class="code-string">"Hello, World!"</span>)
          </div>
          <div class="example-box">
            <h4>Output:</h4>
            <p>Hello, World!</p>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Python uses indentation to define code blocks. Always use consistent indentation (spaces or tabs, but not both).
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Variables and Data Types</h2>
          <p>Variables are containers for storing data values. In Python, you don't need to declare variable types explicitly.</p>
          <h3>Creating Variables</h3>
          <div class="code-block">
<span class="code-comment"># Creating variables</span>
name = <span class="code-string">"Alice"</span>
age = <span class="code-number">25</span>
height = <span class="code-number">5.6</span>
is_student = <span class="code-keyword">True</span>

<span class="code-comment"># Printing variables</span>
<span class="code-keyword">print</span>(name)
<span class="code-keyword">print</span>(age)
<span class="code-keyword">print</span>(height)
<span class="code-keyword">print</span>(is_student)
          </div>
          <h3>Data Types</h3>
          <ul>
            <li><strong>String (str):</strong> Text data - "Hello", 'Python'</li>
            <li><strong>Integer (int):</strong> Whole numbers - 42, -10, 0</li>
            <li><strong>Float (float):</strong> Decimal numbers - 3.14, -0.001</li>
            <li><strong>Boolean (bool):</strong> True or False</li>
            <li><strong>List:</strong> Ordered collection - [1, 2, 3]</li>
            <li><strong>Dictionary (dict):</strong> Key-value pairs - {"name": "Alice"}</li>
          </ul>
          <h3>Type Checking</h3>
          <div class="code-block">
<span class="code-comment"># Check the type of a variable</span>
x = <span class="code-number">42</span>
<span class="code-keyword">print</span>(<span class="code-keyword">type</span>(x))  <span class="code-comment"># Output: &lt;class 'int'&gt;</span>

y = <span class="code-string">"Hello"</span>
<span class="code-keyword">print</span>(<span class="code-keyword">type</span>(y))  <span class="code-comment"># Output: &lt;class 'str'&gt;</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use descriptive variable names that explain what the variable contains. Avoid single letters except for simple counters.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Control Flow</h2>
          <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
          <h3>If Statements</h3>
          <div class="code-block">
<span class="code-comment"># Simple if statement</span>
age = <span class="code-number">18</span>

<span class="code-keyword">if</span> age >= <span class="code-number">18</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"You are an adult"</span>)
<span class="code-keyword">elif</span> age >= <span class="code-number">13</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"You are a teenager"</span>)
<span class="code-keyword">else</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"You are a child"</span>)
          </div>
          <h3>Loops</h3>
          <div class="code-block">
<span class="code-comment"># For loop</span>
<span class="code-keyword">for</span> i <span class="code-keyword">in</span> <span class="code-keyword">range</span>(<span class="code-number">5</span>):
    <span class="code-keyword">print</span>(i)  <span class="code-comment"># Prints 0, 1, 2, 3, 4</span>

<span class="code-comment"># While loop</span>
count = <span class="code-number">0</span>
<span class="code-keyword">while</span> count < <span class="code-number">3</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"Count:"</span>, count)
    count += <span class="code-number">1</span>
          </div>
          <h3>Comparison Operators</h3>
          <ul>
            <li><strong>==</strong> Equal to</li>
            <li><strong>!=</strong> Not equal to</li>
            <li><strong>&lt;</strong> Less than</li>
            <li><strong>&gt;</strong> Greater than</li>
            <li><strong>&lt;=</strong> Less than or equal to</li>
            <li><strong>&gt;=</strong> Greater than or equal to</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>and</code>, <code>or</code>, and <code>not</code> to combine conditions in if statements.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Functions</h2>
          <p>Functions are reusable blocks of code that perform specific tasks. They help organize code and avoid repetition.</p>
          <h3>Defining Functions</h3>
          <div class="code-block">
<span class="code-comment"># Simple function</span>
<span class="code-keyword">def</span> greet(name):
    <span class="code-keyword">print</span>(<span class="code-string">f"Hello, {name}!"</span>)

<span class="code-comment"># Calling the function</span>
greet(<span class="code-string">"Alice"</span>)
          </div>
          <h3>Functions with Return Values</h3>
          <div class="code-block">
<span class="code-comment"># Function that returns a value</span>
<span class="code-keyword">def</span> add_numbers(a, b):
    <span class="code-keyword">return</span> a + b

<span class="code-comment"># Using the return value</span>
result = add_numbers(<span class="code-number">5</span>, <span class="code-number">3</span>)
<span class="code-keyword">print</span>(result)  <span class="code-comment"># Output: 8</span>
          </div>
          <h3>Default Parameters</h3>
          <div class="code-block">
<span class="code-comment"># Function with default parameter</span>
<span class="code-keyword">def</span> greet_with_title(name, title=<span class="code-string">"Mr."</span>):
    <span class="code-keyword">print</span>(<span class="code-string">f"Hello, {title} {name}!"</span>)

greet_with_title(<span class="code-string">"Smith"</span>)  <span class="code-comment"># Uses default title</span>
greet_with_title(<span class="code-string">"Johnson"</span>, <span class="code-string">"Dr."</span>)  <span class="code-comment"># Custom title</span>
          </div>
          <h3>Multiple Return Values</h3>
          <div class="code-block">
<span class="code-comment"># Function returning multiple values</span>
<span class="code-keyword">def</span> get_name_and_age():
    <span class="code-keyword">return</span> <span class="code-string">"Alice"</span>, <span class="code-number">25</span>

name, age = get_name_and_age()
<span class="code-keyword">print</span>(name, age)  <span class="code-comment"># Output: Alice 25</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use descriptive function names that explain what the function does. Function names should be lowercase with underscores.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Lists and Dictionaries</h2>
          <p>Lists and dictionaries are fundamental data structures in Python for storing collections of data.</p>
          <h3>Lists</h3>
          <div class="code-block">
<span class="code-comment"># Creating a list</span>
fruits = [<span class="code-string">"apple"</span>, <span class="code-string">"banana"</span>, <span class="code-string">"orange"</span>]

<span class="code-comment"># Accessing elements</span>
<span class="code-keyword">print</span>(fruits[<span class="code-number">0</span>])  <span class="code-comment"># Output: apple</span>

<span class="code-comment"># Adding elements</span>
fruits.append(<span class="code-string">"grape"</span>)

<span class="code-comment"># Removing elements</span>
fruits.remove(<span class="code-string">"banana"</span>)

<span class="code-comment"># List length</span>
<span class="code-keyword">print</span>(<span class="code-keyword">len</span>(fruits))
          </div>
          <h3>List Operations</h3>
          <div class="code-block">
<span class="code-comment"># List comprehension</span>
numbers = [<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>]
squares = [x**<span class="code-number">2</span> <span class="code-keyword">for</span> x <span class="code-keyword">in</span> numbers]
<span class="code-keyword">print</span>(squares)  <span class="code-comment"># Output: [1, 4, 9, 16, 25]</span>

<span class="code-comment"># Filtering with comprehension</span>
even_numbers = [x <span class="code-keyword">for</span> x <span class="code-keyword">in</span> numbers <span class="code-keyword">if</span> x % <span class="code-number">2</span> == <span class="code-number">0</span>]
<span class="code-keyword">print</span>(even_numbers)  <span class="code-comment"># Output: [2, 4]</span>
          </div>
          <h3>Dictionaries</h3>
          <div class="code-block">
<span class="code-comment"># Creating a dictionary</span>
person = {
    <span class="code-string">"name"</span>: <span class="code-string">"Alice"</span>,
    <span class="code-string">"age"</span>: <span class="code-number">25</span>,
    <span class="code-string">"city"</span>: <span class="code-string">"New York"</span>
}

<span class="code-comment"># Accessing values</span>
<span class="code-keyword">print</span>(person[<span class="code-string">"name"</span>])  <span class="code-comment"># Output: Alice</span>

<span class="code-comment"># Adding/updating values</span>
person[<span class="code-string">"job"</span>] = <span class="code-string">"Developer"</span>

<span class="code-comment"># Safe access with get()</span>
age = person.get(<span class="code-string">"age"</span>, <span class="code-number">0</span>)  <span class="code-comment"># Returns 0 if key doesn't exist</span>
          </div>
          <h3>Dictionary Methods</h3>
          <div class="code-block">
<span class="code-comment"># Getting all keys</span>
keys = person.keys()
<span class="code-keyword">print</span>(keys)  <span class="code-comment"># Output: dict_keys(['name', 'age', 'city', 'job'])</span>

<span class="code-comment"># Getting all values</span>
values = person.values()
<span class="code-keyword">print</span>(values)  <span class="code-comment"># Output: dict_values(['Alice', 25, 'New York', 'Developer'])</span>

<span class="code-comment"># Getting key-value pairs</span>
items = person.items()
<span class="code-keyword">print</span>(items)  <span class="code-comment"># Output: dict_items([('name', 'Alice'), ('age', 25), ...])</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Lists are ordered and mutable, while dictionaries are unordered key-value pairs. Choose the right data structure for your needs.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>File Handling</h2>
          <p>Python provides built-in functions for reading and writing files, making it easy to work with data persistence.</p>
          <h3>Reading Files</h3>
          <div class="code-block">
<span class="code-comment"># Reading a file</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"example.txt"</span>, <span class="code-string">"r"</span>) <span class="code-keyword">as</span> file:
    content = file.read()
    <span class="code-keyword">print</span>(content)

<span class="code-comment"># Reading line by line</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"example.txt"</span>, <span class="code-string">"r"</span>) <span class="code-keyword">as</span> file:
    <span class="code-keyword">for</span> line <span class="code-keyword">in</span> file:
        <span class="code-keyword">print</span>(line.strip())  <span class="code-comment"># strip() removes trailing whitespace</span>
          </div>
          <h3>Writing Files</h3>
          <div class="code-block">
<span class="code-comment"># Writing to a file</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"output.txt"</span>, <span class="code-string">"w"</span>) <span class="code-keyword">as</span> file:
    file.write(<span class="code-string">"Hello, World!\n"</span>)
    file.write(<span class="code-string">"This is a test file."</span>)

<span class="code-comment"># Appending to a file</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"output.txt"</span>, <span class="code-string">"a"</span>) <span class="code-keyword">as</span> file:
    file.write(<span class="code-string">"\nThis line was appended."</span>)
          </div>
          <h3>File Modes</h3>
          <ul>
            <li><strong>"r"</strong> - Read mode (default)</li>
            <li><strong>"w"</strong> - Write mode (overwrites existing content)</li>
            <li><strong>"a"</strong> - Append mode (adds to existing content)</li>
            <li><strong>"r+"</strong> - Read and write mode</li>
            <li><strong>"b"</strong> - Binary mode (use with "rb", "wb", etc.)</li>
          </ul>
          <h3>Working with CSV Files</h3>
          <div class="code-block">
<span class="code-keyword">import</span> csv

<span class="code-comment"># Reading CSV</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"data.csv"</span>, <span class="code-string">"r"</span>) <span class="code-keyword">as</span> file:
    reader = csv.reader(file)
    <span class="code-keyword">for</span> row <span class="code-keyword">in</span> reader:
        <span class="code-keyword">print</span>(row)

<span class="code-comment"># Writing CSV</span>
<span class="code-keyword">with</span> <span class="code-keyword">open</span>(<span class="code-string">"output.csv"</span>, <span class="code-string">"w"</span>, newline=<span class="code-string">""</span>) <span class="code-keyword">as</span> file:
    writer = csv.writer(file)
    writer.writerow([<span class="code-string">"Name"</span>, <span class="code-string">"Age"</span>, <span class="code-string">"City"</span>])
    writer.writerow([<span class="code-string">"Alice"</span>, <span class="code-number">25</span>, <span class="code-string">"New York"</span>])
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use the <code>with</code> statement when working with files. It automatically closes the file when you're done, even if an error occurs.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Error Handling</h2>
          <p>Error handling allows your program to gracefully handle unexpected situations and continue running.</p>
          <h3>Try-Except Blocks</h3>
          <div class="code-block">
<span class="code-comment"># Basic error handling</span>
<span class="code-keyword">try</span>:
    number = <span class="code-keyword">int</span>(<span class="code-string">"abc"</span>)
    <span class="code-keyword">print</span>(number)
<span class="code-keyword">except</span> ValueError:
    <span class="code-keyword">print</span>(<span class="code-string">"That's not a valid number!"</span>)

<span class="code-comment"># Multiple exception types</span>
<span class="code-keyword">try</span>:
    result = <span class="code-number">10</span> / <span class="code-number">0</span>
<span class="code-keyword">except</span> ZeroDivisionError:
    <span class="code-keyword">print</span>(<span class="code-string">"Cannot divide by zero!"</span>)
<span class="code-keyword">except</span> Exception <span class="code-keyword">as</span> e:
    <span class="code-keyword">print</span>(<span class="code-string">f"An error occurred: {e}"</span>)
          </div>
          <h3>Try-Except-Else-Finally</h3>
          <div class="code-block">
<span class="code-keyword">try</span>:
    number = <span class="code-keyword">int</span>(<span class="code-string">"42"</span>)
<span class="code-keyword">except</span> ValueError:
    <span class="code-keyword">print</span>(<span class="code-string">"Invalid number"</span>)
<span class="code-keyword">else</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"Conversion successful!"</span>)
<span class="code-keyword">finally</span>:
    <span class="code-keyword">print</span>(<span class="code-string">"This always runs"</span>)
          </div>
          <h3>Custom Exceptions</h3>
          <div class="code-block">
<span class="code-comment"># Creating a custom exception</span>
<span class="code-keyword">class</span> AgeError(Exception):
    <span class="code-keyword">pass</span>

<span class="code-comment"># Using custom exception</span>
<span class="code-keyword">def</span> check_age(age):
    <span class="code-keyword">if</span> age < <span class="code-number">0</span>:
        <span class="code-keyword">raise</span> AgeError(<span class="code-string">"Age cannot be negative"</span>)
    <span class="code-keyword">elif</span> age > <span class="code-number">150</span>:
        <span class="code-keyword">raise</span> AgeError(<span class="code-string">"Age seems unrealistic"</span>)
    <span class="code-keyword">return</span> <span class="code-string">"Age is valid"</span>

<span class="code-keyword">try</span>:
    result = check_age(-<span class="code-number">5</span>)
<span class="code-keyword">except</span> AgeError <span class="code-keyword">as</span> e:
    <span class="code-keyword">print</span>(e)
          </div>
          <h3>Common Exception Types</h3>
          <ul>
            <li><strong>ValueError:</strong> Invalid value for a function</li>
            <li><strong>TypeError:</strong> Wrong type for an operation</li>
            <li><strong>IndexError:</strong> Invalid list/dictionary index</li>
            <li><strong>KeyError:</strong> Invalid dictionary key</li>
            <li><strong>FileNotFoundError:</strong> File doesn't exist</li>
            <li><strong>ZeroDivisionError:</strong> Division by zero</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Be specific with exception types. Don't catch all exceptions with bare <code>except:</code> unless you have a good reason.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Python Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to Python</button>
          <button class="topic-btn" data-index="1">Variables and Data Types</button>
          <button class="topic-btn" data-index="2">Control Flow</button>
          <button class="topic-btn" data-index="3">Functions</button>
          <button class="topic-btn" data-index="4">Lists and Dictionaries</button>
          <button class="topic-btn" data-index="5">File Handling</button>
          <button class="topic-btn" data-index="6">Error Handling</button>
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