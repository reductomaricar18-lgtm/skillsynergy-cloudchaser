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
    <title>JavaScript Basics - SkillSynergy</title>
    
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
          <h2>Introduction to JavaScript</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>JavaScript is a high-level, interpreted programming language that is one of the core technologies of the World Wide Web. It enables interactive web pages and is an essential part of web applications.</p>
          <h3>Why JavaScript?</h3>
          <ul>
            <li><strong>Web Development:</strong> Essential for interactive websites</li>
            <li><strong>Versatile:</strong> Runs on browsers, servers, and mobile devices</li>
            <li><strong>Dynamic:</strong> Supports dynamic typing and functional programming</li>
            <li><strong>Ecosystem:</strong> Massive library and framework ecosystem</li>
            <li><strong>Universal:</strong> The language of the web</li>
          </ul>
          <h3>Your First JavaScript Program</h3>
          <div class="code-block">
// This is your first JavaScript program
console.log("Hello, World!");

// In HTML, you would use:
&lt;script&gt;
    console.log("Hello, World!");
&lt;/script&gt;
          </div>
          <div class="example-box">
            <h4>Output:</h4>
            <p>Hello, World!</p>
          </div>
          <h3>JavaScript in HTML</h3>
          <div class="code-block">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;My First JavaScript&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Hello World&lt;/h1&gt;
    &lt;script&gt;
        console.log("JavaScript is running!");
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> JavaScript is case-sensitive. Use camelCase for variable names and PascalCase for constructors.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Variables and Data Types</h2>
          <p>JavaScript is dynamically typed, meaning you don't need to declare variable types. Variables can hold different types of data.</p>
          <h3>Declaring Variables</h3>
          <div class="code-block">
// Variable declaration and initialization
let name = "Alice";
const age = 25;
var height = 5.6; // Old way (avoid)

// Declaration first, then assignment
let score;
score = 95;

// Multiple declarations
let x = 1, y = 2, z = 3;
          </div>
          <h3>Data Types</h3>
          <ul>
            <li><strong>String:</strong> Text data - "Hello", 'JavaScript'</li>
            <li><strong>Number:</strong> Numbers - 42, 3.14, -10</li>
            <li><strong>Boolean:</strong> true or false</li>
            <li><strong>Undefined:</strong> Variable declared but not assigned</li>
            <li><strong>Null:</strong> Intentional absence of value</li>
            <li><strong>Object:</strong> Collection of key-value pairs</li>
            <li><strong>Array:</strong> Ordered collection of values</li>
            <li><strong>Function:</strong> Reusable block of code</li>
          </ul>
          <h3>Type Checking</h3>
          <div class="code-block">
// Check the type of a variable
let x = 42;
console.log(typeof x); // Output: "number"

let y = "Hello";
console.log(typeof y); // Output: "string"

let z = true;
console.log(typeof z); // Output: "boolean"
          </div>
          <h3>Type Conversion</h3>
          <div class="code-block">
// String to Number
let str = "42";
let num = parseInt(str); // 42
let num2 = Number(str); // 42

// Number to String
let number = 42;
let string = number.toString(); // "42"
let string2 = "" + number; // "42"
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>let</code> for variables that can change and <code>const</code> for constants. Avoid <code>var</code> due to hoisting issues.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Control Flow</h2>
          <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
          <h3>If Statements</h3>
          <div class="code-block">
// Simple if statement
let age = 18;

if (age >= 18) {
    console.log("You are an adult");
} else if (age >= 13) {
    console.log("You are a teenager");
} else {
    console.log("You are a child");
}
          </div>
          <h3>Switch Statement</h3>
          <div class="code-block">
// Switch statement
let day = 3;

switch (day) {
    case 1:
        console.log("Monday");
        break;
    case 2:
        console.log("Tuesday");
        break;
    default:
        console.log("Other day");
}
          </div>
          <h3>Loops</h3>
          <div class="code-block">
// For loop
for (let i = 0; i < 5; i++) {
    console.log("Count: " + i);
}

// While loop
let count = 0;
while (count < 3) {
    console.log("Count: " + count);
    count++;
}

// For...of loop (for arrays)
let fruits = ["apple", "banana", "orange"];
for (let fruit of fruits) {
    console.log(fruit);
}
          </div>
          <h3>Comparison Operators</h3>
          <ul>
            <li><strong>==</strong> Equal to (with type coercion)</li>
            <li><strong>===</strong> Strictly equal to (no type coercion)</li>
            <li><strong>!=</strong> Not equal to</li>
            <li><strong>!==</strong> Strictly not equal to</li>
            <li><strong>&lt;</strong> Less than</li>
            <li><strong>&gt;</strong> Greater than</li>
            <li><strong>&lt;=</strong> Less than or equal to</li>
            <li><strong>&gt;=</strong> Greater than or equal to</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use <code>===</code> and <code>!==</code> for comparisons to avoid unexpected type coercion.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Functions</h2>
          <p>Functions are reusable blocks of code that perform specific tasks. JavaScript supports multiple ways to define functions.</p>
          <h3>Function Declarations</h3>
          <div class="code-block">
// Function declaration
function greet(name) {
    return "Hello, " + name + "!";
}

// Function expression
const add = function(a, b) {
    return a + b;
};

// Arrow function
const multiply = (a, b) => a * b;

// Arrow function with multiple lines
const divide = (a, b) => {
    if (b === 0) {
        return "Cannot divide by zero";
    }
    return a / b;
};
          </div>
          <h3>Function Parameters</h3>
          <div class="code-block">
// Default parameters
function greetWithTitle(name, title = "Mr.") {
    return "Hello, " + title + " " + name;
}

// Rest parameters
function sum(...numbers) {
    return numbers.reduce((total, num) => total + num, 0);
}

// Destructuring parameters
function printInfo({name, age, city}) {
    console.log("Name: " + name);
    console.log("Age: " + age);
    console.log("City: " + city);
}
          </div>
          <h3>Function Scope and Closures</h3>
          <div class="code-block">
// Function scope
function outer() {
    let outerVar = "I'm from outer";
    function inner() {
        let innerVar = "I'm from inner";
        console.log(outerVar); // Can access outerVar
    }
    inner();
}

// Closure example
function createCounter() {
    let count = 0;
    return function() {
        return ++count;
    };
}
const counter = createCounter();
console.log(counter()); // 1
console.log(counter()); // 2
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use arrow functions for short, simple functions. Use function declarations for more complex functions that need hoisting.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Arrays</h2>
          <p>Arrays in JavaScript are ordered collections that can store multiple values of any type.</p>
          <h3>Creating Arrays</h3>
          <div class="code-block">
// Array declaration and initialization
let fruits = ["apple", "banana", "orange"];
let numbers = [1, 2, 3, 4, 5];
let mixed = ["hello", 42, true, null];

// Using Array constructor
let emptyArray = new Array();
let sizedArray = new Array(5);
          </div>
          <h3>Accessing Array Elements</h3>
          <div class="code-block">
// Accessing elements by index
let fruits = ["apple", "banana", "orange"];
console.log(fruits[0]); // "apple"
console.log(fruits[1]); // "banana"

// Modifying array elements
fruits[1] = "grape";

// Array length
console.log(fruits.length); // 3
          </div>
          <h3>Array Methods</h3>
          <div class="code-block">
// Adding elements
let fruits = ["apple", "banana"];
fruits.push("orange"); // Add to end
fruits.unshift("grape"); // Add to beginning

// Removing elements
let lastFruit = fruits.pop(); // Remove from end
let firstFruit = fruits.shift(); // Remove from beginning

// Splicing
fruits.splice(1, 1); // Remove 1 element at index 1
fruits.splice(1, 0, "mango"); // Insert at index 1
          </div>
          <h3>Array Iteration</h3>
          <div class="code-block">
// forEach method
let numbers = [1, 2, 3, 4, 5];
numbers.forEach(function(number) {
    console.log(number * 2);
});

// map method
let doubled = numbers.map(number => number * 2);

// filter method
let evenNumbers = numbers.filter(number => number % 2 === 0);

// reduce method
let sum = numbers.reduce((total, number) => total + number, 0);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use array methods like <code>map</code>, <code>filter</code>, and <code>reduce</code> for functional programming approaches.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Objects</h2>
          <p>Objects in JavaScript are collections of key-value pairs. They are used to represent real-world entities and organize data.</p>
          <h3>Creating Objects</h3>
          <div class="code-block">
// Object literal
let person = {
    name: "Alice",
    age: 25,
    city: "New York",
    greet: function() {
        return "Hello, I'm " + this.name;
    }
};

// Using Object constructor
let car = new Object();
car.brand = "Toyota";
car.model = "Camry";
car.year = 2020;
          </div>
          <h3>Accessing Object Properties</h3>
          <div class="code-block">
// Dot notation
console.log(person.name); // "Alice"
console.log(person.age); // 25

// Bracket notation
console.log(person["name"]); // "Alice"
console.log(person["age"]); // 25

// Dynamic property access
let propertyName = "name";
console.log(person[propertyName]); // "Alice"

// Calling object methods
console.log(person.greet()); // "Hello, I'm Alice"
          </div>
          <h3>Object Methods</h3>
          <div class="code-block">
// Adding properties
person.job = "Developer";

// Deleting properties
delete person.city;

// Checking if property exists
console.log("name" in person); // true
console.log(person.hasOwnProperty("age")); // true

// Getting object keys, values, and entries
let keys = Object.keys(person);
let values = Object.values(person);
let entries = Object.entries(person);
          </div>
          <h3>Object Destructuring</h3>
          <div class="code-block">
// Basic destructuring
let {name, age} = person;
console.log(name); // "Alice"
console.log(age); // 25

// Destructuring with new variable names
let {name: personName, age: personAge} = person;
console.log(personName); // "Alice"

// Destructuring with default values
let {name, age, country = "USA"} = person;
console.log(country); // "USA"
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use object destructuring to extract multiple properties at once. It makes code cleaner and more readable.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>DOM Manipulation</h2>
          <p>The Document Object Model (DOM) allows JavaScript to interact with HTML elements on a web page.</p>
          <h3>Selecting Elements</h3>
          <div class="code-block">
// Selecting elements by ID
let element = document.getElementById("myId");

// Selecting elements by class
let elements = document.getElementsByClassName("myClass");

// Selecting elements by tag name
let paragraphs = document.getElementsByTagName("p");

// Modern selectors
let element2 = document.querySelector(".myClass");
let elements2 = document.querySelectorAll(".myClass");
          </div>
          <h3>Modifying Elements</h3>
          <div class="code-block">
// Changing content
let element = document.getElementById("myElement");
element.innerHTML = "New content";
element.textContent = "Plain text content";

// Changing attributes
element.setAttribute("class", "newClass");
element.className = "newClass";
element.id = "newId";

// Changing styles
element.style.backgroundColor = "red";
element.style.fontSize = "16px";
element.style.display = "none";
          </div>
          <h3>Creating and Adding Elements</h3>
          <div class="code-block">
// Creating new elements
let newDiv = document.createElement("div");
newDiv.textContent = "This is a new div";
newDiv.className = "new-element";

// Adding elements to the DOM
document.body.appendChild(newDiv);

// Inserting before an element
let referenceElement = document.getElementById("reference");
referenceElement.parentNode.insertBefore(newDiv, referenceElement);

// Removing elements
newDiv.remove();
referenceElement.parentNode.removeChild(referenceElement);
          </div>
          <h3>Event Handling</h3>
          <div class="code-block">
// Adding event listeners
let button = document.getElementById("myButton");

button.addEventListener("click", function(event) {
    console.log("Button clicked!");
    event.preventDefault(); // Prevent default behavior
});

// Arrow function event handler
button.addEventListener("mouseover", (event) => {
    button.style.backgroundColor = "yellow";
});

// Removing event listeners
function handleClick(event) {
    console.log("Handled click");
}

button.addEventListener("click", handleClick);
button.removeEventListener("click", handleClick);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>querySelector</code> and <code>querySelectorAll</code> for modern element selection. They support CSS selectors and are more flexible.
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
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other JavaScript Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to JavaScript</button>
          <button class="topic-btn" data-index="1">Variables and Data Types</button>
          <button class="topic-btn" data-index="2">Control Flow</button>
          <button class="topic-btn" data-index="3">Functions</button>
          <button class="topic-btn" data-index="4">Arrays</button>
          <button class="topic-btn" data-index="5">Objects</button>
          <button class="topic-btn" data-index="6">DOM Manipulation</button>
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