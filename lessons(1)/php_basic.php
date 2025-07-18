<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>PHP Basics - SkillSynergy</title>
    
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
          <h2>PHP Syntax Basics</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>PHP is a server-side scripting language that can be embedded in HTML.</p>
          <h3>1. PHP Tags</h3>
          <div class="code-block">&lt;?php<br>// Your PHP code goes here<br>echo "Hello, World!";<br>?&gt;</div>
          <h3>2. Echo Statement</h3>
          <div class="code-block">&lt;?php<br>echo "Hello World";  // Outputs: Hello World<br>echo "Hello", " World";  // Multiple values<br>echo "Hello" . " World";  // String concatenation<br>?&gt;</div>
          <h3>3. Comments</h3>
          <div class="code-block">&lt;?php<br>// This is a single-line comment<br># This is also a single-line comment<br><br>/*<br>This is a<br>multi-line comment<br>*/<br><br>echo "Hello World"; // Inline comment<br>?&gt;</div>
          <h3>4. Semicolons</h3>
          <div class="code-block">&lt;?php<br>$name = "John";  // Semicolon required<br>echo "Hello " . $name;  // Semicolon required<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Variables & Data Types in PHP</h2>
          <p>Variables in PHP start with a dollar sign ($) and are case-sensitive.</p>
          <h3>1. Variable Declaration</h3>
          <div class="code-block">&lt;?php<br>$name = "John";<br>$age = 25;<br>$height = 5.9;<br>$isStudent = true;<br><br>echo $name;  // Outputs: John<br>echo $age;   // Outputs: 25<br>?&gt;</div>
          <h3>2. Data Types</h3>
          <div class="code-block">&lt;?php<br>// String<br>$name = "John Doe";<br>// Integer<br>$age = 25;<br>// Float<br>$height = 5.9;<br>// Boolean<br>$isStudent = true;<br>// Null<br>$empty = null;<br>// Check data type<br>var_dump($name);  // string(8) "John Doe"<br>var_dump($age);   // int(25)<br>?&gt;</div>
          <h3>3. String Operations</h3>
          <div class="code-block">&lt;?php<br>$firstName = "John";<br>$lastName = "Doe";<br>// Concatenation<br>$fullName = $firstName . " " . $lastName;<br>// String length<br>$length = strlen($fullName);<br>// String functions<br>$upper = strtoupper($fullName);<br>$lower = strtolower($fullName);<br>$first = ucfirst($firstName);<br>?&gt;</div>
          <h3>4. Variable Scope</h3>
          <div class="code-block">&lt;?php<br>$globalVar = "I'm global";<br>function testFunction() {<br>    global $globalVar;  // Access global variable<br>    $localVar = "I'm local";<br>    echo $globalVar;  // Works<br>}<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Arrays in PHP</h2>
          <p>Arrays are used to store multiple values in a single variable.</p>
          <h3>1. Indexed Arrays</h3>
          <div class="code-block">&lt;?php<br>$colors = array("red", "green", "blue");<br>echo $colors[0]; // red<br>?&gt;</div>
          <h3>2. Associative Arrays</h3>
          <div class="code-block">&lt;?php<br>$ages = array("Peter" => 22, "Clark" => 32, "John" => 28);<br>echo $ages["Clark"]; // 32<br>?&gt;</div>
          <h3>3. Array Functions</h3>
          <div class="code-block">&lt;?php<br>$numbers = [1, 2, 3, 4, 5];<br>array_push($numbers, 6);<br>print_r($numbers);<br>echo count($numbers);<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Functions in PHP</h2>
          <p>Functions are blocks of code that can be reused.</p>
          <h3>1. Defining Functions</h3>
          <div class="code-block">&lt;?php<br>function sayHello($name) {<br>    echo "Hello, $name!";<br>}<br>sayHello("John");<br>?&gt;</div>
          <h3>2. Return Values</h3>
          <div class="code-block">&lt;?php<br>function add($a, $b) {<br>    return $a + $b;<br>}<br>$sum = add(5, 3);<br>echo $sum; // 8<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Control Structures in PHP</h2>
          <p>Control structures are used to control the flow of code execution.</p>
          <h3>1. If-Else</h3>
          <div class="code-block">&lt;?php<br>$age = 18;<br>if ($age >= 18) {<br>    echo "Adult";<br>} else {<br>    echo "Minor";<br>}<br>?&gt;</div>
          <h3>2. Switch</h3>
          <div class="code-block">&lt;?php<br>$color = "red";<br>switch ($color) {<br>    case "red":<br>        echo "Color is red";<br>        break;<br>    case "blue":<br>        echo "Color is blue";<br>        break;<br>    default:<br>        echo "Unknown color";<br>}<br>?&gt;</div>
          <h3>3. Loops</h3>
          <div class="code-block">&lt;?php<br>for ($i = 0; $i < 5; $i++) {<br>    echo $i;<br>}<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Forms & $_POST in PHP</h2>
          <p>PHP can collect form data sent with the HTTP POST method using the <code>$_POST</code> superglobal.</p>
          <h3>1. Simple Form Example</h3>
          <div class="code-block">&lt;form method="post" action=""&gt;<br>  Name: &lt;input type="text" name="name"&gt;<br>  &lt;input type="submit" value="Submit"&gt;<br>&lt;/form&gt;<br>&lt;?php<br>if ($_SERVER["REQUEST_METHOD"] == "POST") {<br>    $name = $_POST["name"];<br>    echo "Hello, $name!";<br>}<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>PHP Introduction</h2>
          <p>PHP (Hypertext Preprocessor) is a popular open-source server-side scripting language designed for web development. It can be embedded into HTML and is widely used to create dynamic web pages and applications.</p>
          <ul>
            <li>PHP code is executed on the server.</li>
            <li>Files usually have a <code>.php</code> extension.</li>
            <li>PHP can generate dynamic page content, handle forms, manage sessions, and interact with databases.</li>
          </ul>
          <div class="code-block">&lt;?php<br>echo "Hello, World!";<br>?&gt;</div>
          <p>To run PHP, you need a server with PHP installed (e.g., XAMPP, WAMP, or a live server).</p>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>Operators</h2>
          <p>Operators are used to perform operations on variables and values. PHP supports arithmetic, assignment, comparison, logical, and more.</p>
          <h3>Arithmetic Operators</h3>
          <div class="code-block">&lt;?php<br>$a = 10;<br>$b = 3;<br>echo $a + $b; // 13<br>echo $a - $b; // 7<br>echo $a * $b; // 30<br>echo $a / $b; // 3.333...<br>echo $a % $b; // 1<br>?&gt;</div>
          <h3>Assignment Operators</h3>
          <div class="code-block">&lt;?php<br>$x = 5;<br>$x += 3; // $x = $x + 3 = 8<br>$x -= 2; // $x = $x - 2 = 6<br>?&gt;</div>
          <h3>Comparison Operators</h3>
          <div class="code-block">&lt;?php<br>$a = 5;<br>$b = "5";<br>var_dump($a == $b); // true (equal value)<br>var_dump($a === $b); // false (equal value and type)<br>var_dump($a != $b); // false<br>var_dump($a !== $b); // true<br>?&gt;</div>
          <h3>Logical Operators</h3>
          <div class="code-block">&lt;?php<br>$x = true;<br>$y = false;<br>var_dump($x && $y); // false<br>var_dump($x || $y); // true<br>var_dump(!$x); // false<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="8">
          <h2>Loops</h2>
          <p>Loops are used to execute a block of code repeatedly. PHP supports several types of loops:</p>
          <h3>For Loop</h3>
          <div class="code-block">&lt;?php<br>for ($i = 0; $i &lt; 5; $i++) {<br>    echo $i . " ";<br>}<br>// Output: 0 1 2 3 4<br>?&gt;</div>
          <h3>While Loop</h3>
          <div class="code-block">&lt;?php<br>$i = 0;<br>while ($i &lt; 3) {<br>    echo $i;<br>    $i++;<br>}<br>?&gt;</div>
          <h3>Do...While Loop</h3>
          <div class="code-block">&lt;?php<br>$i = 0;<br>do {<br>    echo $i;<br>    $i++;<br>} while ($i &lt; 2);<br>?&gt;</div>
          <h3>Foreach Loop (for arrays)</h3>
          <div class="code-block">&lt;?php<br>$colors = ["red", "green", "blue"];<br>foreach ($colors as $color) {<br>    echo $color . " ";<br>}<br>// Output: red green blue<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="9">
          <h2>Superglobals</h2>
          <p>Superglobals are built-in variables in PHP that are always accessible, regardless of scope. Common superglobals include:</p>
          <ul>
            <li><code>$_GET</code> – Data sent via URL parameters</li>
            <li><code>$_POST</code> – Data sent via HTTP POST</li>
            <li><code>$_REQUEST</code> – Data from both GET and POST</li>
            <li><code>$_SESSION</code> – Session variables</li>
            <li><code>$_COOKIE</code> – Cookie variables</li>
            <li><code>$_FILES</code> – Uploaded files</li>
            <li><code>$_SERVER</code> – Server and execution environment info</li>
            <li><code>$_ENV</code> – Environment variables</li>
            <li><code>$_GLOBALS</code> – All global variables</li>
          </ul>
          <div class="code-block">&lt;?php<br>// Accessing GET data<br>echo $_GET["name"];
// Accessing POST data<br>echo $_POST["email"];
// Accessing server info<br>echo $_SERVER["HTTP_USER_AGENT"];
?&gt;</div>
        </div>
        <div class="lesson-section" data-index="10">
          <h2>File Handling</h2>
          <p>PHP can read, write, and manipulate files on the server.</p>
          <h3>Reading a File</h3>
          <div class="code-block">&lt;?php<br>$content = file_get_contents("example.txt");<br>echo $content;<br>?&gt;</div>
          <h3>Writing to a File</h3>
          <div class="code-block">&lt;?php<br>file_put_contents("example.txt", "Hello, file!");<br>?&gt;</div>
          <h3>Opening, Reading, and Closing a File</h3>
          <div class="code-block">&lt;?php<br>$handle = fopen("example.txt", "r");<br>while (($line = fgets($handle)) !== false) {<br>    echo $line . "<br>";<br>}<br>fclose($handle);<br>?&gt;</div>
          <p>Always close files after opening them to free up resources.</p>
        </div>
        <div class="lesson-section" data-index="11">
          <h2>Sessions & Cookies</h2>
          <p>Sessions and cookies are used to store user information across multiple pages.</p>
          <h3>Sessions</h3>
          <div class="code-block">&lt;?php<br>session_start();<br>$_SESSION["username"] = "John";<br>echo $_SESSION["username"];
?&gt;</div>
          <h3>Cookies</h3>
          <div class="code-block">&lt;?php<br>setcookie("user", "John", time() + 3600); // 1 hour expiry<br>if (isset($_COOKIE["user"])) {<br>    echo $_COOKIE["user"];<br>}<br>?&gt;</div>
          <p>Sessions are stored on the server, cookies on the client’s browser.</p>
        </div>
        <div class="lesson-section" data-index="12">
          <h2>Error Handling</h2>
          <p>PHP provides several ways to handle errors, including error reporting, try-catch blocks, and custom error handlers.</p>
          <h3>Basic Error Reporting</h3>
          <div class="code-block">&lt;?php<br>error_reporting(E_ALL);<br>ini_set('display_errors', 1);<br>?&gt;</div>
          <h3>Try-Catch Exception Handling</h3>
          <div class="code-block">&lt;?php<br>try {<br>    throw new Exception("Something went wrong!");<br>} catch (Exception $e) {<br>    echo "Caught exception: " . $e->getMessage();<br>}<br>?&gt;</div>
          <h3>Custom Error Handler</h3>
          <div class="code-block">&lt;?php<br>function myErrorHandler($errno, $errstr) {<br>    echo "Error: [$errno] $errstr";<br>}<br>set_error_handler("myErrorHandler");<br>echo $undefinedVar; // triggers error handler<br>?&gt;</div>
        </div>
        <div class="lesson-section" data-index="13">
          <h2>OOP Basics</h2>
          <p>Object-Oriented Programming (OOP) in PHP allows you to structure code using classes and objects.</p>
          <h3>Defining a Class</h3>
          <div class="code-block">&lt;?php<br>class Car {<br>    public $color;<br>    public function __construct($color) {<br>        $this->color = $color;<br>    }<br>    public function drive() {<br>        echo "Driving a $this->color car.";<br>    }<br>}<br>$myCar = new Car("red");<br>$myCar->drive(); // Driving a red car.<br>?&gt;</div>
          <h3>Inheritance</h3>
          <div class="code-block">&lt;?php<br>class ElectricCar extends Car {<br>    public function charge() {<br>        echo "Charging...";<br>    }<br>}<br>$tesla = new ElectricCar("blue");<br>$tesla->drive();<br>$tesla->charge();<br>?&gt;</div>
          <h3>Encapsulation & Access Modifiers</h3>
          <div class="code-block">&lt;?php<br>class Person {<br>    private $name;<br>    public function setName($name) {<br>        $this->name = $name;<br>    }<br>    public function getName() {<br>        return $this->name;<br>    }<br>}<br>$p = new Person();<br>$p->setName("Alice");<br>echo $p->getName();<br>?&gt;</div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other PHP Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">PHP Syntax Basics</button>
          <button class="topic-btn" data-index="1">Variables & Data Types</button>
          <button class="topic-btn" data-index="2">Arrays</button>
          <button class="topic-btn" data-index="3">Functions</button>
          <button class="topic-btn" data-index="4">Control Structures</button>
          <button class="topic-btn" data-index="5">Forms & $_POST</button>
          <button class="topic-btn" data-index="6">PHP Introduction</button>
          <button class="topic-btn" data-index="7">Operators</button>
          <button class="topic-btn" data-index="8">Loops</button>
          <button class="topic-btn" data-index="9">Superglobals</button>
          <button class="topic-btn" data-index="10">File Handling</button>
          <button class="topic-btn" data-index="11">Sessions & Cookies</button>
          <button class="topic-btn" data-index="12">Error Handling</button>
          <button class="topic-btn" data-index="13">OOP Basics</button>
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