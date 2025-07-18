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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .lesson-card {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
        }

        .lesson-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .lesson-scrollable {
            flex: 1;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 30px;
            position: relative;
        }

        .lesson-section { 
            display: none; 
            animation: fadeIn 0.5s ease;
        }
        
        .lesson-section.active { 
            display: block; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lesson-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .lesson-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .lesson-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4a5568;
            margin: 30px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lesson-section h3::before {
            content: '🚀';
            font-size: 1.3rem;
        }

        .lesson-section p {
            line-height: 1.8;
            margin-bottom: 20px;
            color: #4a5568;
            font-size: 1.1rem;
        }

        .lesson-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 25px 40px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .lesson-nav button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .lesson-nav button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .lesson-nav button:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #otherTopicsDropdown {
            display: none;
            position: absolute;
            bottom: 60px;
            right: 20px;
            min-width: 250px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 10px 0;
            backdrop-filter: blur(20px);
        }

        #otherTopicsDropdown .topic-btn {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 12px 20px;
            text-align: left;
            font-size: 1rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #otherTopicsDropdown .topic-btn:hover {
            background: linear-gradient(135deg, #f0f4ff, #e6f3ff);
            color: #667eea;
            transform: translateX(5px);
        }

        /* Enhanced Code Blocks */
        .code-block { 
            background: linear-gradient(135deg, #2d3748, #1a202c);
            color: #e2e8f0; 
            padding: 30px; 
            border-radius: 15px; 
            margin: 25px 0; 
            overflow-x: auto; 
            font-family: 'Fira Code', 'Courier New', monospace; 
            font-size: 15px; 
            line-height: 1.6;
            position: relative;
            border: 1px solid #4a5568;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .code-block::before {
            content: '💻';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 18px;
        }

        .code-comment { color: #68d391; font-style: italic; }
        .code-keyword { color: #f6ad55; font-weight: 600; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; font-weight: 600; }
        .code-function { color: #81e6d9; }
        .code-class { color: #d6bcfa; }

        /* Enhanced Info Boxes */
        .example-box { 
            background: linear-gradient(135deg, #f0fff4, #c6f6d5);
            border: 2px solid #68d391; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .example-box::before {
            content: '✅';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .example-box h4 { 
            color: #2f855a; 
            margin-top: 0; 
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .tip-box { 
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border: 2px solid #81e6d9; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
        }

        .tip-box::before {
            content: '💡';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .tip-box strong { 
            color: #2c7a7b;
            font-weight: 600;
        }

        /* Enhanced Lists */
        .lesson-section ul, .lesson-section ol { 
            margin: 25px 0; 
            padding-left: 0;
            list-style: none;
        }

        .lesson-section li { 
            background: white;
            margin: 12px 0;
            padding: 18px 25px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .lesson-section li:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .lesson-section li::before {
            content: '🚀';
            font-size: 20px;
        }

        .lesson-section strong { 
            color: #2d3748; 
            font-weight: 600;
        }

        .highlight { 
            background: linear-gradient(135deg, #fef5e7, #fed7aa);
            padding: 4px 8px; 
            border-radius: 6px; 
            font-weight: bold;
            color: #c05621;
        }

        /* Interactive Elements */
        .interactive-link {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            margin: 15px 0;
        }

        .interactive-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .lesson-card {
                margin: 10px;
                height: calc(100vh - 20px);
            }

            .lesson-scrollable {
                padding: 20px;
            }

            .lesson-section h2 {
                font-size: 1.8rem;
            }

            .lesson-nav {
                flex-direction: column;
                gap: 15px;
            }

            .lesson-nav button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to Python</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
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
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
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