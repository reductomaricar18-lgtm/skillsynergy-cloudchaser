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
    <title>JavaScript Basics - SkillSynergy</title>
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
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
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
          <h2>Introduction to JavaScript</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
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
      <!-- Removed duplicate navigation card here -->
    </div>
    <script>
    // Show only the first section (Introduction) by default
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        sections.forEach((section, index) => {
            if (index === 0) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html>