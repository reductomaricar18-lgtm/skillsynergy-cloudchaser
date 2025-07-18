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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>C Basics - SkillSynergy</title>
    
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
          <h2>Introduction to C</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>C is a general-purpose, procedural programming language developed by Dennis Ritchie at Bell Labs in 1972. It's known for its efficiency, portability, and low-level memory access, making it ideal for system programming and embedded systems.</p>
          <h3>Why C?</h3>
          <ul>
            <li><strong>Efficiency:</strong> Direct memory access and minimal overhead</li>
            <li><strong>Portability:</strong> Code can run on different platforms</li>
            <li><strong>System Programming:</strong> Used for operating systems and drivers</li>
            <li><strong>Foundation:</strong> Many modern languages are based on C</li>
            <li><strong>Control:</strong> Fine-grained control over hardware resources</li>
          </ul>
          <h3>Your First C Program</h3>
          <div class="code-block">
<span class="code-comment">/* This is your first C program */</span>
<span class="code-keyword">#include</span> &lt;stdio.h&gt;

<span class="code-keyword">int</span> main() {
    printf(<span class="code-string">"Hello, World!\n"</span>);
    <span class="code-keyword">return</span> <span class="code-number">0</span>;
}
          </div>
          <div class="example-box">
            <h4>Output:</h4>
            <p>Hello, World!</p>
          </div>
          <h3>C Program Structure</h3>
          <ul>
            <li><strong>Preprocessor Directives:</strong> #include, #define</li>
            <li><strong>Function Declarations:</strong> Prototypes</li>
            <li><strong>Main Function:</strong> Entry point of the program</li>
            <li><strong>Other Functions:</strong> User-defined functions</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Every C program must have a main() function. The return value indicates program success (0) or failure (non-zero).
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Variables and Data Types</h2>
          <p>C is a statically-typed language, meaning you must declare variable types before using them. C provides both basic and derived data types.</p>
          <h3>Basic Data Types</h3>
          <div class="code-block">
<span class="code-comment">/* Variable declaration and initialization */</span>
<span class="code-keyword">int</span> age = <span class="code-number">25</span>;
<span class="code-keyword">float</span> height = <span class="code-number">5.9</span>;
<span class="code-keyword">double</span> pi = <span class="code-number">3.14159</span>;
<span class="code-keyword">char</span> grade = <span class="code-string">'A'</span>;

<span class="code-comment">/* Declaration first, then assignment */</span>
<span class="code-keyword">int</span> score;
score = <span class="code-number">95</span>;
          </div>
          <h3>Data Type Sizes</h3>
          <ul>
            <li><strong>char:</strong> 1 byte (-128 to 127)</li>
            <li><strong>int:</strong> 4 bytes (-2,147,483,648 to 2,147,483,647)</li>
            <li><strong>float:</strong> 4 bytes (6-7 decimal digits)</li>
            <li><strong>double:</strong> 8 bytes (15-16 decimal digits)</li>
            <li><strong>void:</strong> No value (used for functions)</li>
          </ul>
          <h3>Type Modifiers</h3>
          <div class="code-block">
<span class="code-comment">/* Type modifiers */</span>
<span class="code-keyword">short int</span> small = <span class="code-number">100</span>;
<span class="code-keyword">long int</span> large = <span class="code-number">1000000</span>;
<span class="code-keyword">unsigned int</span> positive = <span class="code-number">50</span>;
<span class="code-keyword">const int</span> constant = <span class="code-number">42</span>;
          </div>
          <h3>Type Conversion</h3>
          <div class="code-block">
<span class="code-comment">/* Implicit conversion (automatic) */</span>
<span class="code-keyword">int</span> i = <span class="code-number">10</span>;
<span class="code-keyword">float</span> f = i; <span class="code-comment">/* i is converted to float */</span>

<span class="code-comment">/* Explicit conversion (casting) */</span>
<span class="code-keyword">float</span> decimal = <span class="code-number">10.5</span>;
<span class="code-keyword">int</span> whole = (<span class="code-keyword">int</span>) decimal; <span class="code-comment">/* Casting required */</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always initialize variables when declaring them to avoid undefined behavior. Use meaningful variable names.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Control Flow</h2>
          <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
          <h3>If Statements</h3>
          <div class="code-block">
<span class="code-comment">/* Simple if statement */</span>
<span class="code-keyword">int</span> age = <span class="code-number">18</span>;

<span class="code-keyword">if</span> (age >= <span class="code-number">18</span>) {
    printf(<span class="code-string">"You are an adult\n"</span>);
} <span class="code-keyword">else if</span> (age >= <span class="code-number">13</span>) {
    printf(<span class="code-string">"You are a teenager\n"</span>);
} <span class="code-keyword">else</span> {
    printf(<span class="code-string">"You are a child\n"</span>);
}
          </div>
          <h3>Switch Statement</h3>
          <div class="code-block">
<span class="code-comment">/* Switch statement */</span>
<span class="code-keyword">int</span> day = <span class="code-number">3</span>;

<span class="code-keyword">switch</span> (day) {
    <span class="code-keyword">case</span> <span class="code-number">1</span>:
        printf(<span class="code-string">"Monday\n"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">case</span> <span class="code-number">2</span>:
        printf(<span class="code-string">"Tuesday\n"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">default</span>:
        printf(<span class="code-string">"Other day\n"</span>);
}
          </div>
          <h3>Loops</h3>
          <div class="code-block">
<span class="code-comment">/* For loop */</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {
    printf(<span class="code-string">"Count: %d\n"</span>, i);
}

<span class="code-comment">/* While loop */</span>
<span class="code-keyword">int</span> count = <span class="code-number">0</span>;
<span class="code-keyword">while</span> (count < <span class="code-number">3</span>) {
    printf(<span class="code-string">"Count: %d\n"</span>, count);
    count++;
}

<span class="code-comment">/* Do-while loop */</span>
<span class="code-keyword">int</span> num = <span class="code-number">1</span>;
<span class="code-keyword">do</span> {
    printf(<span class="code-string">"Number: %d\n"</span>, num);
    num++;
} <span class="code-keyword">while</span> (num <= <span class="code-number">3</span>);
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
            <strong>💡 Tip:</strong> Use <code>&&</code> (AND), <code>||</code> (OR), and <code>!</code> (NOT) for logical operations in C.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Functions</h2>
          <p>Functions in C are blocks of code that perform specific tasks. They help organize code and promote reusability.</p>
          <h3>Function Declaration and Definition</h3>
          <div class="code-block">
<span class="code-comment">/* Function declaration (prototype) */</span>
<span class="code-keyword">int</span> add(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b);

<span class="code-comment">/* Function definition */</span>
<span class="code-keyword">int</span> add(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) {
    <span class="code-keyword">return</span> a + b;
}

<span class="code-comment">/* Function with no return value */</span>
<span class="code-keyword">void</span> greet(<span class="code-keyword">char</span> name[]) {
    printf(<span class="code-string">"Hello, %s!\n"</span>, name);
}
          </div>
          <h3>Function Parameters</h3>
          <div class="code-block">
<span class="code-comment">/* Function with multiple parameters */</span>
<span class="code-keyword">void</span> printInfo(<span class="code-keyword">char</span> name[], <span class="code-keyword">int</span> age, <span class="code-keyword">float</span> height) {
    printf(<span class="code-string">"Name: %s\n"</span>, name);
    printf(<span class="code-string">"Age: %d\n"</span>, age);
    printf(<span class="code-string">"Height: %.2f\n"</span>, height);
}

<span class="code-comment">/* Function with variable arguments */</span>
<span class="code-keyword">int</span> sum(<span class="code-keyword">int</span> count, ...) {
    <span class="code-comment">/* Implementation using stdarg.h */</span>
    <span class="code-keyword">return</span> <span class="code-number">0</span>;
}
          </div>
          <h3>Function Types</h3>
          <ul>
            <li><strong>Library Functions:</strong> Built-in functions (printf, scanf)</li>
            <li><strong>User-defined Functions:</strong> Functions you create</li>
            <li><strong>Recursive Functions:</strong> Functions that call themselves</li>
            <li><strong>Inline Functions:</strong> Functions expanded at call site</li>
          </ul>
          <h3>Function Scope</h3>
          <div class="code-block">
<span class="code-comment">/* Global variable */</span>
<span class="code-keyword">int</span> globalVar = <span class="code-number">10</span>;

<span class="code-keyword">void</span> function1() {
    <span class="code-comment">/* Local variable */</span>
    <span class="code-keyword">int</span> localVar = <span class="code-number">20</span>;
    printf(<span class="code-string">"Global: %d, Local: %d\n"</span>, globalVar, localVar);
}

<span class="code-keyword">void</span> function2() {
    <span class="code-comment">/* Can access globalVar but not localVar */</span>
    printf(<span class="code-string">"Global: %d\n"</span>, globalVar);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always declare function prototypes before using them. This helps the compiler check for correct function calls.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Arrays</h2>
          <p>Arrays in C are collections of elements of the same type stored in contiguous memory locations.</p>
          <h3>Creating Arrays</h3>
          <div class="code-block">
<span class="code-comment">/* Array declaration and initialization */</span>
<span class="code-keyword">int</span> numbers[<span class="code-number">5</span>]; <span class="code-comment">/* Declares array of size 5 */</span>

<span class="code-comment">/* Array with initial values */</span>
<span class="code-keyword">int</span> scores[] = {<span class="code-number">85</span>, <span class="code-number">92</span>, <span class="code-number">78</span>, <span class="code-number">96</span>};

<span class="code-comment">/* Character array (string) */</span>
<span class="code-keyword">char</span> name[] = <span class="code-string">"Alice"</span>;
          </div>
          <h3>Accessing Array Elements</h3>
          <div class="code-block">
<span class="code-comment">/* Accessing elements by index */</span>
<span class="code-keyword">int</span> numbers[] = {<span class="code-number">10</span>, <span class="code-number">20</span>, <span class="code-number">30</span>, <span class="code-number">40</span>};

printf(<span class="code-string">"First element: %d\n"</span>, numbers[<span class="code-number">0</span>]); <span class="code-comment">/* Output: 10 */</span>
printf(<span class="code-string">"Third element: %d\n"</span>, numbers[<span class="code-number">2</span>]); <span class="code-comment">/* Output: 30 */</span>

<span class="code-comment">/* Modifying array elements */</span>
numbers[<span class="code-number">1</span>] = <span class="code-number">25</span>;

<span class="code-comment">/* Array size */</span>
<span class="code-keyword">int</span> size = <span class="code-keyword">sizeof</span>(numbers) / <span class="code-keyword">sizeof</span>(numbers[<span class="code-number">0</span>]);
          </div>
          <h3>Iterating Through Arrays</h3>
          <div class="code-block">
<span class="code-comment">/* Traditional for loop */</span>
<span class="code-keyword">int</span> numbers[] = {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>};
<span class="code-keyword">int</span> size = <span class="code-keyword">sizeof</span>(numbers) / <span class="code-keyword">sizeof</span>(numbers[<span class="code-number">0</span>]);

<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < size; i++) {
    printf(<span class="code-string">"Element %d: %d\n"</span>, i, numbers[i]);
}

<span class="code-comment">/* Using pointers */</span>
<span class="code-keyword">int</span> *ptr = numbers;
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < size; i++) {
    printf(<span class="code-string">"Element %d: %d\n"</span>, i, *(ptr + i));
}
          </div>
          <h3>Multi-dimensional Arrays</h3>
          <div class="code-block">
<span class="code-comment">/* 2D array */</span>
<span class="code-keyword">int</span> matrix[<span class="code-number">3</span>][<span class="code-number">3</span>] = {
    {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>},
    {<span class="code-number">4</span>, <span class="code-number">5</span>, <span class="code-number">6</span>},
    {<span class="code-number">7</span>, <span class="code-number">8</span>, <span class="code-number">9</span>}
};

<span class="code-comment">/* Accessing 2D array elements */</span>
printf(<span class="code-string">"Element [0][1]: %d\n"</span>, matrix[<span class="code-number">0</span>][<span class="code-number">1</span>]); <span class="code-comment">/* Output: 2 */</span>

<span class="code-comment">/* Iterating through 2D array */</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">3</span>; i++) {
    <span class="code-keyword">for</span> (<span class="code-keyword">int</span> j = <span class="code-number">0</span>; j < <span class="code-number">3</span>; j++) {
        printf(<span class="code-string">"%d "</span>, matrix[i][j]);
    }
    printf(<span class="code-string">"\n"</span>);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Remember that array indices start at 0. The last element is at index size-1. Be careful not to access beyond array bounds.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Pointers</h2>
          <p>Pointers are variables that store memory addresses. They are one of the most powerful features of C, allowing direct memory manipulation.</p>
          <h3>Basic Pointers</h3>
          <div class="code-block">
<span class="code-comment">/* Declaring and using pointers */</span>
<span class="code-keyword">int</span> number = <span class="code-number">42</span>;
<span class="code-keyword">int</span> *ptr = &number; <span class="code-comment">/* ptr stores address of number */</span>

printf(<span class="code-string">"Value: %d\n"</span>, number); <span class="code-comment">/* Output: 42 */</span>
printf(<span class="code-string">"Address: %p\n"</span>, ptr); <span class="code-comment">/* Output: memory address */</span>
printf(<span class="code-string">"Value via pointer: %d\n"</span>, *ptr); <span class="code-comment">/* Output: 42 */</span>

<span class="code-comment">/* Modifying value via pointer */</span>
*ptr = <span class="code-number">100</span>;
printf(<span class="code-string">"New value: %d\n"</span>, number); <span class="code-comment">/* Output: 100 */</span>
          </div>
          <h3>Pointer Arithmetic</h3>
          <div class="code-block">
<span class="code-comment">/* Pointer arithmetic */</span>
<span class="code-keyword">int</span> numbers[] = {<span class="code-number">10</span>, <span class="code-number">20</span>, <span class="code-number">30</span>, <span class="code-number">40</span>};
<span class="code-keyword">int</span> *ptr = numbers;

printf(<span class="code-string">"First element: %d\n"</span>, *ptr); <span class="code-comment">/* Output: 10 */</span>
printf(<span class="code-string">"Second element: %d\n"</span>, *(ptr + <span class="code-number">1</span>)); <span class="code-comment">/* Output: 20 */</span>
printf(<span class="code-string">"Third element: %d\n"</span>, *(ptr + <span class="code-number">2</span>)); <span class="code-comment">/* Output: 30 */</span>

<span class="code-comment">/* Incrementing pointer */</span>
ptr++; <span class="code-comment">/* Move to next element */</span>
printf(<span class="code-string">"After increment: %d\n"</span>, *ptr); <span class="code-comment">/* Output: 20 */</span>
          </div>
          <h3>Pointers and Functions</h3>
          <div class="code-block">
<span class="code-comment">/* Function with pointer parameters */</span>
<span class="code-keyword">void</span> swap(<span class="code-keyword">int</span> *a, <span class="code-keyword">int</span> *b) {
    <span class="code-keyword">int</span> temp = *a;
    *a = *b;
    *b = temp;
}

<span class="code-keyword">int</span> main() {
    <span class="code-keyword">int</span> x = <span class="code-number">10</span>, y = <span class="code-number">20</span>;
    printf(<span class="code-string">"Before swap: x=%d, y=%d\n"</span>, x, y);
    swap(&x, &y);
    printf(<span class="code-string">"After swap: x=%d, y=%d\n"</span>, x, y);
    <span class="code-keyword">return</span> <span class="code-number">0</span>;
}
          </div>
          <h3>Dynamic Memory Allocation</h3>
          <div class="code-block">
<span class="code-comment">/* Dynamic memory allocation */</span>
<span class="code-keyword">#include</span> &lt;stdlib.h&gt;

<span class="code-keyword">int</span> *dynamicArray = (<span class="code-keyword">int</span>*)malloc(<span class="code-number">5</span> * <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>));

<span class="code-comment">/* Check if allocation was successful */</span>
<span class="code-keyword">if</span> (dynamicArray == NULL) {
    printf(<span class="code-string">"Memory allocation failed\n"</span>);
    <span class="code-keyword">return</span> <span class="code-number">1</span>;
    }

<span class="code-comment">/* Use the allocated memory */</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {
    dynamicArray[i] = i * <span class="code-number">10</span>;
}

<span class="code-comment">/* Free the allocated memory */</span>
free(dynamicArray);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always initialize pointers to NULL and check for NULL before dereferencing. Always free dynamically allocated memory to prevent memory leaks.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>File Handling</h2>
          <p>C provides functions for reading and writing files, allowing programs to persist data.</p>
          <h3>Opening and Closing Files</h3>
          <div class="code-block">
<span class="code-comment">/* File handling */</span>
<span class="code-keyword">#include</span> &lt;stdio.h&gt;

FILE *file = fopen(<span class="code-string">"example.txt"</span>, <span class="code-string">"r"</span>);

<span class="code-comment">/* Check if file opened successfully */</span>
<span class="code-keyword">if</span> (file == NULL) {
    printf(<span class="code-string">"Error opening file\n"</span>);
    <span class="code-keyword">return</span> <span class="code-number">1</span>;
}

<span class="code-comment">/* Close the file */</span>
fclose(file);
          </div>
          <h3>Reading Files</h3>
          <div class="code-block">
<span class="code-comment">/* Reading a file character by character */</span>
FILE *file = fopen(<span class="code-string">"input.txt"</span>, <span class="code-string">"r"</span>);
<span class="code-keyword">char</span> ch;

<span class="code-keyword">while</span> ((ch = fgetc(file)) != EOF) {
    printf(<span class="code-string">"%c"</span>, ch);
}
fclose(file);

<span class="code-comment">/* Reading line by line */</span>
FILE *file2 = fopen(<span class="code-string">"input.txt"</span>, <span class="code-string">"r"</span>);
<span class="code-keyword">char</span> line[<span class="code-number">256</span>];

<span class="code-keyword">while</span> (fgets(line, <span class="code-keyword">sizeof</span>(line), file2) != NULL) {
    printf(<span class="code-string">"%s"</span>, line);
}
fclose(file2);
          </div>
          <h3>Writing Files</h3>
          <div class="code-block">
<span class="code-comment">/* Writing to a file */</span>
FILE *file = fopen(<span class="code-string">"output.txt"</span>, <span class="code-string">"w"</span>);

<span class="code-keyword">if</span> (file == NULL) {
    printf(<span class="code-string">"Error opening file for writing\n"</span>);
    <span class="code-keyword">return</span> <span class="code-number">1</span>;
}

fprintf(file, <span class="code-string">"Hello, World!\n"</span>);
fprintf(file, <span class="code-string">"This is a test file.\n"</span>);

fclose(file);

<span class="code-comment">/* Appending to a file */</span>
FILE *appendFile = fopen(<span class="code-string">"output.txt"</span>, <span class="code-string">"a"</span>);
fprintf(appendFile, <span class="code-string">"This line was appended.\n"</span>);
fclose(appendFile);
          </div>
          <h3>File Modes</h3>
          <ul>
            <li><strong>"r"</strong> - Read mode (file must exist)</li>
            <li><strong>"w"</strong> - Write mode (creates new file, truncates existing)</li>
            <li><strong>"a"</strong> - Append mode (creates new file, appends to existing)</li>
            <li><strong>"r+"</strong> - Read and write mode</li>
            <li><strong>"w+"</strong> - Read and write mode (truncates)</li>
            <li><strong>"a+"</strong> - Read and append mode</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check if file operations are successful. Use proper error handling to make your programs robust.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to C</button>
          <button class="topic-btn" data-index="1">Variables and Data Types</button>
          <button class="topic-btn" data-index="2">Control Flow</button>
          <button class="topic-btn" data-index="3">Functions</button>
          <button class="topic-btn" data-index="4">Arrays</button>
          <button class="topic-btn" data-index="5">Pointers</button>
          <button class="topic-btn" data-index="6">File Handling</button>
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