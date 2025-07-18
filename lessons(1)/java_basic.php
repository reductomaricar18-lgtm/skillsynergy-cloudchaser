    
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

<div class="lesson-card">
  <div class="lesson-scrollable">
    <div class="lesson-section active" data-index="0">
      <!-- Introduction to Java -->
      <h2>Introduction to Java</h2>
      <p>Java is a high-level, object-oriented programming language developed by Sun Microsystems (now Oracle) in 1995. It's known for its "Write Once, Run Anywhere" capability and is widely used in enterprise applications, Android development, and web services.</p>
      <h3>Why Java?</h3>
      <ul>
        <li><strong>Platform Independent:</strong> Java bytecode runs on any platform with a JVM</li>
        <li><strong>Object-Oriented:</strong> Built around objects and classes</li>
        <li><strong>Robust and Secure:</strong> Strong type checking and memory management</li>
        <li><strong>Large Ecosystem:</strong> Extensive libraries and frameworks</li>
        <li><strong>Enterprise Standard:</strong> Widely used in corporate environments</li>
      </ul>
      <h3>Your First Java Program</h3>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
      <div class="code-block">
<span class="code-comment">// This is your first Java program</span>
<span class="code-keyword">public class</span> HelloWorld {
    <span class="code-keyword">public static void</span> main(String[] args) {
        System.out.println(<span class="code-string">"Hello, World!"</span>);
    }
}
      </div>
      <div class="example-box">
        <h4>Output:</h4>
        <p>Hello, World!</p>
      </div>
      <h3>Java Program Structure</h3>
      <ul>
        <li><strong>Class Declaration:</strong> Every Java program must be inside a class</li>
        <li><strong>Main Method:</strong> Entry point of the program</li>
        <li><strong>Package Declaration:</strong> Optional organization mechanism</li>
        <li><strong>Import Statements:</strong> To use classes from other packages</li>
      </ul>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Java is case-sensitive. The class name must match the filename exactly, including capitalization.
      </div>
    </div>
    <div class="lesson-section" data-index="1">
      <!-- Variables and Data Types -->
      <h2>Variables and Data Types</h2>
      <p>Variables in Java must be declared with a specific data type. Java is a statically-typed language, meaning variable types are checked at compile time.</p>
      <h3>Declaring Variables</h3>
      <div class="code-block">
        <span class="code-comment">// Variable declaration and initialization</span>
        <span class="code-keyword">int</span> age = <span class="code-number">25</span>;
        <span class="code-keyword">double</span> height = <span class="code-number">5.9</span>;
        <span class="code-keyword">String</span> name = <span class="code-string">"Alice"</span>;
        <span class="code-keyword">boolean</span> isStudent = <span class="code-keyword">true</span>;

        <span class="code-comment">// Declaration first, then assignment</span>
        <span class="code-keyword">int</span> score;
        score = <span class="code-number">95</span>;
      </div>
      <h3>Primitive Data Types</h3>
      <ul>
        <li><strong>byte:</strong> 8-bit integer (-128 to 127)</li>
        <li><strong>short:</strong> 16-bit integer (-32,768 to 32,767)</li>
        <li><strong>int:</strong> 32-bit integer (-2^31 to 2^31-1)</li>
        <li><strong>long:</strong> 64-bit integer (-2^63 to 2^63-1)</li>
        <li><strong>float:</strong> 32-bit floating point</li>
        <li><strong>double:</strong> 64-bit floating point</li>
        <li><strong>char:</strong> 16-bit Unicode character</li>
        <li><strong>boolean:</strong> true or false</li>
      </ul>
      <h3>Reference Data Types</h3>
      <div class="code-block">
<span class="code-comment">// String (reference type)</span>
String message = <span class="code-string">"Hello, Java!"</span>;

<span class="code-comment">// Arrays</span>
<span class="code-keyword">int</span>[] numbers = <span class="code-keyword">new int</span>[<span class="code-number">5</span>];
String[] names = {<span class="code-string">"Alice"</span>, <span class="code-string">"Bob"</span>, <span class="code-string">"Charlie"</span>};

<span class="code-comment">// Custom objects</span>
Person person = <span class="code-keyword">new</span> Person();
      </div>
      <h3>Type Conversion</h3>
      <div class="code-block">
        <span class="code-comment">// Implicit conversion (widening)</span>
        <span class="code-keyword">int</span> smallNumber = <span class="code-number">10</span>;
        <span class="code-keyword">double</span> bigNumber = smallNumber; <span class="code-comment">// Automatic conversion</span>

        <span class="code-comment">// Explicit conversion (casting)</span>
        <span class="code-keyword">double</span> decimal = <span class="code-number">10.5</span>;
        <span class="code-keyword">int</span> whole = (<span class="code-keyword">int</span>) decimal; <span class="code-comment">// Casting required</span>
      </div>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Use meaningful variable names that describe what the variable contains. Follow camelCase convention for variable names.
      </div>
    </div>
    <div class="lesson-section" data-index="2">
      <!-- Control Flow -->
      <h2>Control Flow</h2>
      <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
      <h3>If Statements</h3>
      <div class="code-block">
<span class="code-comment">// Simple if statement</span>
<span class="code-keyword">int</span> age = <span class="code-number">18</span>;

<span class="code-keyword">if</span> (age >= <span class="code-number">18</span>) {
    System.out.println(<span class="code-string">"You are an adult"</span>);
} <span class="code-keyword">else if</span> (age >= <span class="code-number">13</span>) {
    System.out.println(<span class="code-string">"You are a teenager"</span>);
} <span class="code-keyword">else</span> {
    System.out.println(<span class="code-string">"You are a child"</span>);
}
      </div>
      <h3>Switch Statement</h3>
      <div class="code-block">
<span class="code-comment">// Switch statement</span>
<span class="code-keyword">int</span> day = <span class="code-number">3</span>;

<span class="code-keyword">switch</span> (day) {
    <span class="code-keyword">case</span> <span class="code-number">1</span>:
        System.out.println(<span class="code-string">"Monday"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">case</span> <span class="code-number">2</span>:
        System.out.println(<span class="code-string">"Tuesday"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">default</span>:
        System.out.println(<span class="code-string">"Other day"</span>);
}
      </div>
      <h3>Loops</h3>
      <div class="code-block">
<span class="code-comment">// For loop</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {
    System.out.println(<span class="code-string">"Count: "</span> + i);
}

<span class="code-comment">// While loop</span>
<span class="code-keyword">int</span> count = <span class="code-number">0</span>;
<span class="code-keyword">while</span> (count < <span class="code-number">3</span>) {
    System.out.println(<span class="code-string">"Count: "</span> + count);
    count++;
}

<span class="code-comment">// Do-while loop</span>
<span class="code-keyword">int</span> num = <span class="code-number">1</span>;
<span class="code-keyword">do</span> {
    System.out.println(<span class="code-string">"Number: "</span> + num);
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
        <strong>💡 Tip:</strong> Use <code>&&</code> (AND), <code>||</code> (OR), and <code>!</code> (NOT) for logical operations in Java.
      </div>
    </div>
    <div class="lesson-section" data-index="3">
      <!-- Methods -->
      <h2>Methods</h2>
      <p>Methods are blocks of code that perform specific tasks. They help organize code and promote reusability.</p>
      <h3>Defining Methods</h3>
      <div class="code-block">
<span class="code-comment">// Simple method</span>
<span class="code-keyword">public static void</span> greet(String name) {
    System.out.println(<span class="code-string">"Hello, "</span> + name + <span class="code-string">"!"</span>);
}

<span class="code-comment">// Method with return value</span>
<span class="code-keyword">public static int</span> addNumbers(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) {
    <span class="code-keyword">return</span> a + b;
}

<span class="code-comment">// Method overloading</span>
<span class="code-keyword">public static int</span> multiply(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) {
    <span class="code-keyword">return</span> a * b;
}

<span class="code-keyword">public static double</span> multiply(<span class="code-keyword">double</span> a, <span class="code-keyword">double</span> b) {
    <span class="code-keyword">return</span> a * b;
}
      </div>
      <h3>Method Parameters</h3>
      <div class="code-block">
<span class="code-comment">// Method with multiple parameters</span>
<span class="code-keyword">public static void</span> printInfo(String name, <span class="code-keyword">int</span> age, String city) {
    System.out.println(<span class="code-string">"Name: "</span> + name);
    System.out.println(<span class="code-string">"Age: "</span> + age);
    System.out.println(<span class="code-string">"City: "</span> + city);
}

<span class="code-comment">// Method with variable arguments (varargs)</span>
<span class="code-keyword">public static int</span> sum(<span class="code-keyword">int</span>... numbers) {
    <span class="code-keyword">int</span> total = <span class="code-number">0</span>;
    <span class="code-keyword">for</span> (<span class="code-keyword">int</span> num : numbers) {
        total += num;
    }
    <span class="code-keyword">return</span> total;
}
      </div>
      <h3>Access Modifiers</h3>
      <ul>
        <li><strong>public:</strong> Accessible from anywhere</li>
        <li><strong>private:</strong> Accessible only within the class</li>
        <li><strong>protected:</strong> Accessible within package and subclasses</li>
        <li><strong>default:</strong> Accessible only within the package</li>
      </ul>
      <h3>Static vs Instance Methods</h3>
      <div class="code-block">
<span class="code-comment">// Static method (belongs to class)</span>
<span class="code-keyword">public static void</span> staticMethod() {
    System.out.println(<span class="code-string">"This is a static method"</span>);
}

<span class="code-comment">// Instance method (belongs to object)</span>
<span class="code-keyword">public void</span> instanceMethod() {
    System.out.println(<span class="code-string">"This is an instance method"</span>);
}

<span class="code-comment">// Usage</span>
staticMethod(); <span class="code-comment">// Call static method directly</span>
MyClass obj = <span class="code-keyword">new</span> MyClass();
obj.instanceMethod(); <span class="code-comment">// Call instance method on object</span>
      </div>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Use descriptive method names that explain what the method does. Method names should be camelCase and start with a verb.
      </div>
    </div>
    <div class="lesson-section" data-index="4">
      <!-- Classes and Objects -->
      <h2>Classes and Objects</h2>
      <p>Java is an object-oriented language. Classes are blueprints for creating objects, which are instances of classes.</p>
      <h3>Creating a Class</h3>
      <div class="code-block">
<span class="code-comment">// Simple class definition</span>
<span class="code-keyword">public class</span> Person {
    <span class="code-comment">// Instance variables (fields)</span>
    <span class="code-keyword">private</span> String name;
    <span class="code-keyword">private int</span> age;
    
    <span class="code-comment">// Constructor</span>
    <span class="code-keyword">public</span> Person(String name, <span class="code-keyword">int</span> age) {
        <span class="code-keyword">this</span>.name = name;
        <span class="code-keyword">this</span>.age = age;
    }
    
    <span class="code-comment">// Getter methods</span>
    <span class="code-keyword">public</span> String getName() {
        <span class="code-keyword">return</span> name;
    }
    
    <span class="code-keyword">public int</span> getAge() {
        <span class="code-keyword">return</span> age;
    }
    
    <span class="code-comment">// Setter methods</span>
    <span class="code-keyword">public void</span> setName(String name) {
        <span class="code-keyword">this</span>.name = name;
    }
    
    <span class="code-keyword">public void</span> setAge(<span class="code-keyword">int</span> age) {
        <span class="code-keyword">this</span>.age = age;
    }
}
      </div>
      <h3>Creating Objects</h3>
      <div class="code-block">
<span class="code-comment">// Creating objects</span>
Person person1 = <span class="code-keyword">new</span> Person(<span class="code-string">"Alice"</span>, <span class="code-number">25</span>);
Person person2 = <span class="code-keyword">new</span> Person(<span class="code-string">"Bob"</span>, <span class="code-number">30</span>);

<span class="code-comment">// Accessing object methods</span>
System.out.println(person1.getName()); <span class="code-comment">// Output: Alice</span>
System.out.println(person2.getAge());  <span class="code-comment">// Output: 30</span>

<span class="code-comment">// Modifying object state</span>
person1.setAge(<span class="code-number">26</span>);
      </div>
      <h3>Inheritance</h3>
      <div class="code-block">
<span class="code-comment">// Parent class</span>
<span class="code-keyword">public class</span> Animal {
    <span class="code-keyword">protected</span> String name;
    
    <span class="code-keyword">public</span> Animal(String name) {
        <span class="code-keyword">this</span>.name = name;
    }
    
    <span class="code-keyword">public void</span> makeSound() {
        System.out.println(<span class="code-string">"Some sound"</span>);
    }
}

<span class="code-comment">// Child class</span>
<span class="code-keyword">public class</span> Dog <span class="code-keyword">extends</span> Animal {
    <span class="code-keyword">public</span> Dog(String name) {
        <span class="code-keyword">super</span>(name); <span class="code-comment">// Call parent constructor</span>
    }
    
    @Override
    <span class="code-keyword">public void</span> makeSound() {
        System.out.println(<span class="code-string">"Woof!"</span>);
    }
}
      </div>
      <h3>Encapsulation</h3>
      <ul>
        <li><strong>Private Fields:</strong> Hide data from outside access</li>
        <li><strong>Public Methods:</strong> Provide controlled access to data</li>
        <li><strong>Getters/Setters:</strong> Allow reading and writing of private fields</li>
        <li><strong>Data Validation:</strong> Ensure data integrity</li>
      </ul>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Use encapsulation to protect your data. Make fields private and provide public methods to access them.
      </div>
    </div>
    <div class="lesson-section" data-index="5">
      <!-- Arrays -->
      <h2>Arrays</h2>
      <p>Arrays in Java are fixed-size collections of elements of the same type. They provide efficient storage and access to multiple values.</p>
      <h3>Creating Arrays</h3>
      <div class="code-block">
<span class="code-comment">// Array declaration and initialization</span>
<span class="code-keyword">int</span>[] numbers = <span class="code-keyword">new int</span>[<span class="code-number">5</span>]; <span class="code-comment">// Creates array of size 5</span>

<span class="code-comment">// Array with initial values</span>
<span class="code-keyword">int</span>[] scores = {<span class="code-number">85</span>, <span class="code-number">92</span>, <span class="code-number">78</span>, <span class="code-number">96</span>};

<span class="code-comment">// String array</span>
String[] names = {<span class="code-string">"Alice"</span>, <span class="code-string">"Bob"</span>, <span class="code-string">"Charlie"</span>};
      </div>
      <h3>Accessing Array Elements</h3>
      <div class="code-block">
<span class="code-comment">// Accessing elements by index</span>
<span class="code-keyword">int</span>[] numbers = {<span class="code-number">10</span>, <span class="code-number">20</span>, <span class="code-number">30</span>, <span class="code-number">40</span>};

System.out.println(numbers[<span class="code-number">0</span>]); <span class="code-comment">// Output: 10</span>
System.out.println(numbers[<span class="code-number">2</span>]); <span class="code-comment">// Output: 30</span>

<span class="code-comment">// Modifying array elements</span>
numbers[<span class="code-number">1</span>] = <span class="code-number">25</span>;

<span class="code-comment">// Array length</span>
System.out.println(numbers.length); <span class="code-comment">// Output: 4</span>
      </div>
      <h3>Iterating Through Arrays</h3>
      <div class="code-block">
<span class="code-comment">// Traditional for loop</span>
<span class="code-keyword">int</span>[] numbers = {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>};

<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < numbers.length; i++) {
    System.out.println(<span class="code-string">"Element "</span> + i + <span class="code-string">": "</span> + numbers[i]);
}

<span class="code-comment">// Enhanced for loop (for-each)</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> number : numbers) {
    System.out.println(<span class="code-string">"Number: "</span> + number);
}
      </div>
      <h3>Multi-dimensional Arrays</h3>
      <div class="code-block">
<span class="code-comment">// 2D array</span>
<span class="code-keyword">int</span>[][] matrix = {
    {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>},
    {<span class="code-number">4</span>, <span class="code-number">5</span>, <span class="code-number">6</span>},
    {<span class="code-number">7</span>, <span class="code-number">8</span>, <span class="code-number">9</span>}
};

<span class="code-comment">// Accessing 2D array elements</span>
System.out.println(matrix[<span class="code-number">0</span>][<span class="code-number">1</span>]); <span class="code-comment">// Output: 2</span>

<span class="code-comment">// Iterating through 2D array</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < matrix.length; i++) {
    <span class="code-keyword">for</span> (<span class="code-keyword">int</span> j = <span class="code-number">0</span>; j < matrix[i].length; j++) {
        System.out.print(matrix[i][j] + <span class="code-string">" "</span>);
    }
    System.out.println();
}
      </div>
      <h3>Array Methods</h3>
      <div class="code-block">
<span class="code-comment">// Arrays utility class</span>
<span class="code-keyword">import</span> java.util.Arrays;

<span class="code-keyword">int</span>[] numbers = {<span class="code-number">5</span>, <span class="code-number">2</span>, <span class="code-number">8</span>, <span class="code-number">1</span>, <span class="code-number">9</span>};

<span class="code-comment">// Sorting</span>
Arrays.sort(numbers);

<span class="code-comment">// Searching</span>
<span class="code-keyword">int</span> index = Arrays.binarySearch(numbers, <span class="code-number">8</span>);

<span class="code-comment">// Filling</span>
Arrays.fill(numbers, <span class="code-number">0</span>);

<span class="code-comment">// Converting to string</span>
String arrayString = Arrays.toString(numbers);
      </div>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Remember that array indices start at 0. The last element is at index length-1.
      </div>
    </div>
    <div class="lesson-section" data-index="6">
      <!-- Exception Handling -->
      <h2>Exception Handling</h2>
      <p>Exception handling allows your program to gracefully handle errors and unexpected situations.</p>
      <h3>Try-Catch Blocks</h3>
      <div class="code-block">
<span class="code-comment">// Basic exception handling</span>
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> number = Integer.parseInt(<span class="code-string">"abc"</span>);
    System.out.println(number);
} <span class="code-keyword">catch</span> (NumberFormatException e) {
    System.out.println(<span class="code-string">"That's not a valid number!"</span>);
}

<span class="code-comment">// Multiple catch blocks</span>
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> result = <span class="code-number">10</span> / <span class="code-number">0</span>;
} <span class="code-keyword">catch</span> (ArithmeticException e) {
    System.out.println(<span class="code-string">"Cannot divide by zero!"</span>);
} <span class="code-keyword">catch</span> (Exception e) {
    System.out.println(<span class="code-string">"An error occurred: "</span> + e.getMessage());
}
      </div>
      <h3>Try-Catch-Finally</h3>
      <div class="code-block">
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> number = Integer.parseInt(<span class="code-string">"42"</span>);
    System.out.println(<span class="code-string">"Number: "</span> + number);
} <span class="code-keyword">catch</span> (NumberFormatException e) {
    System.out.println(<span class="code-string">"Invalid number format"</span>);
} <span class="code-keyword">finally</span> {
    System.out.println(<span class="code-string">"This always executes"</span>);
}
      </div>
      <h3>Custom Exceptions</h3>
      <div class="code-block">
<span class="code-comment">// Creating custom exception</span>
<span class="code-keyword">public class</span> AgeException <span class="code-keyword">extends</span> Exception {
    <span class="code-keyword">public</span> AgeException(String message) {
        <span class="code-keyword">super</span>(message);
    }
}

<span class="code-comment">// Using custom exception</span>
<span class="code-keyword">public static void</span> checkAge(<span class="code-keyword">int</span> age) <span class="code-keyword">throws</span> AgeException {
    <span class="code-keyword">if</span> (age < <span class="code-number">0</span>) {
        <span class="code-keyword">throw new</span> AgeException(<span class="code-string">"Age cannot be negative"</span>);
    } <span class="code-keyword">else if</span> (age > <span class="code-number">150</span>) {
        <span class="code-keyword">throw new</span> AgeException(<span class="code-string">"Age seems unrealistic"</span>);
    }
}

<span class="code-keyword">try</span> {
    checkAge(-<span class="code-number">5</span>);
} <span class="code-keyword">catch</span> (AgeException e) {
    System.out.println(e.getMessage());
}
      </div>
      <ul>
        <li><strong>NullPointerException:</strong> Accessing null object</li>
        <li><strong>ArrayIndexOutOfBoundsException:</strong> Invalid array index</li>
        <li><strong>NumberFormatException:</strong> Invalid number conversion</li>
        <li><strong>ArithmeticException:</strong> Division by zero</li>
        <li><strong>ClassCastException:</strong> Invalid type casting</li>
        <li><strong>FileNotFoundException:</strong> File not found</li>
      </ul>
      <div class="tip-box">
        <strong>💡 Tip:</strong> Always handle exceptions appropriately. Don't catch exceptions unless you can handle them meaningfully.
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
    <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Java Topics</button>
    <div id="otherTopicsDropdown">
      <button class="topic-btn" data-index="0">Introduction to Java</button>
      <button class="topic-btn" data-index="1">Variables and Data Types</button>
      <button class="topic-btn" data-index="2">Control Flow</button>
      <button class="topic-btn" data-index="3">Methods</button>
      <button class="topic-btn" data-index="4">Classes and Objects</button>
      <button class="topic-btn" data-index="5">Arrays</button>
      <button class="topic-btn" data-index="6">Exception Handling</button>
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
    // Add Other Topics button logic
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
    // Hide dropdown if click outside
    document.addEventListener('click', function(e) {
        var dropdown = document.getElementById('otherTopicsDropdown');
        var btn = document.getElementById('otherTopicsBtn');
        if (!dropdown.contains(e.target) && e.target !== btn) {
            dropdown.style.display = 'none';
        }
    });
})();
</script>
            <!-- Introduction Section -->
            <div id="introduction" class="topic-section">
                <h2>Introduction to Java</h2>
                
                <p>Java is a high-level, object-oriented programming language developed by Sun Microsystems (now Oracle) in 1995. It's known for its "Write Once, Run Anywhere" capability and is widely used in enterprise applications, Android development, and web services.</p>
                
                <h3>Why Java?</h3>
                <ul>
                    <li><strong>Platform Independent:</strong> Java bytecode runs on any platform with a JVM</li>
                    <li><strong>Object-Oriented:</strong> Built around objects and classes</li>
                    <li><strong>Robust and Secure:</strong> Strong type checking and memory management</li>
                    <li><strong>Large Ecosystem:</strong> Extensive libraries and frameworks</li>
                    <li><strong>Enterprise Standard:</strong> Widely used in corporate environments</li>
                </ul>
                
                <h3>Your First Java Program</h3>
                <div class="code-block">
<span class="code-comment">// This is your first Java program</span>
<span class="code-keyword">public class</span> HelloWorld {
    <span class="code-keyword">public static void</span> main(String[] args) {
        System.out.println(<span class="code-string">"Hello, World!"</span>);
    }
}
                </div>
                
                <div class="example-box">
                    <h4>Output:</h4>
                    <p>Hello, World!</p>
                </div>
                
                <h3>Java Program Structure</h3>
                <ul>
                    <li><strong>Class Declaration:</strong> Every Java program must be inside a class</li>
                    <li><strong>Main Method:</strong> Entry point of the program</li>
                    <li><strong>Package Declaration:</strong> Optional organization mechanism</li>
                    <li><strong>Import Statements:</strong> To use classes from other packages</li>
                </ul>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Java is case-sensitive. The class name must match the filename exactly, including capitalization.
                </div>
            </div>
            
            <!-- Variables Section -->
            <div id="variables" class="topic-section" style="display: none;">
                <h2>Variables and Data Types</h2>
                
                <p>Variables in Java must be declared with a specific data type. Java is a statically-typed language, meaning variable types are checked at compile time.</p>
                
                <h3>Declaring Variables</h3>
                <div class="code-block">
<span class="code-comment">// Variable declaration and initialization</span>
<span class="code-keyword">int</span> age = <span class="code-number">25</span>;
<span class="code-keyword">double</span> height = <span class="code-number">5.9</span>;
<span class="code-keyword">String</span> name = <span class="code-string">"Alice"</span>;
<span class="code-keyword">boolean</span> isStudent = <span class="code-keyword">true</span>;

<span class="code-comment">// Declaration first, then assignment</span>
<span class="code-keyword">int</span> score;
score = <span class="code-number">95</span>;
                </div>
                
                <h3>Primitive Data Types</h3>
                <ul>
                    <li><strong>byte:</strong> 8-bit integer (-128 to 127)</li>
                    <li><strong>short:</strong> 16-bit integer (-32,768 to 32,767)</li>
                    <li><strong>int:</strong> 32-bit integer (-2^31 to 2^31-1)</li>
                    <li><strong>long:</strong> 64-bit integer (-2^63 to 2^63-1)</li>
                    <li><strong>float:</strong> 32-bit floating point</li>
                    <li><strong>double:</strong> 64-bit floating point</li>
                    <li><strong>char:</strong> 16-bit Unicode character</li>
                    <li><strong>boolean:</strong> true or false</li>
                </ul>
                
                <h3>Reference Data Types</h3>
                <div class="code-block">
<span class="code-comment">// String (reference type)</span>
String message = <span class="code-string">"Hello, Java!"</span>;

<span class="code-comment">// Arrays</span>
<span class="code-keyword">int</span>[] numbers = <span class="code-keyword">new int</span>[<span class="code-number">5</span>];
String[] names = {<span class="code-string">"Alice"</span>, <span class="code-string">"Bob"</span>, <span class="code-string">"Charlie"</span>};

<span class="code-comment">// Custom objects</span>
Person person = <span class="code-keyword">new</span> Person();
                </div>
                
                <h3>Type Conversion</h3>
                <div class="code-block">
<span class="code-comment">// Implicit conversion (widening)</span>
<span class="code-keyword">int</span> smallNumber = <span class="code-number">10</span>;
<span class="code-keyword">double</span> bigNumber = smallNumber; <span class="code-comment">// Automatic conversion</span>

<span class="code-comment">// Explicit conversion (casting)</span>
<span class="code-keyword">double</span> decimal = <span class="code-number">10.5</span>;
<span class="code-keyword">int</span> whole = (<span class="code-keyword">int</span>) decimal; <span class="code-comment">// Casting required</span>
                </div>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Use meaningful variable names that describe what the variable contains. Follow camelCase convention for variable names.
                </div>
            </div>
            
            <!-- Control Flow Section -->
            <div id="control-flow" class="topic-section" style="display: none;">
                <h2>Control Flow</h2>
                
                <p>Control flow statements allow you to make decisions and repeat code based on conditions.</p>
                
                <h3>If Statements</h3>
                <div class="code-block">
<span class="code-comment">// Simple if statement</span>
<span class="code-keyword">int</span> age = <span class="code-number">18</span>;

<span class="code-keyword">if</span> (age >= <span class="code-number">18</span>) {
    System.out.println(<span class="code-string">"You are an adult"</span>);
} <span class="code-keyword">else if</span> (age >= <span class="code-number">13</span>) {
    System.out.println(<span class="code-string">"You are a teenager"</span>);
} <span class="code-keyword">else</span> {
    System.out.println(<span class="code-string">"You are a child"</span>);
}
                </div>
                
                <h3>Switch Statement</h3>
                <div class="code-block">
<span class="code-comment">// Switch statement</span>
<span class="code-keyword">int</span> day = <span class="code-number">3</span>;

<span class="code-keyword">switch</span> (day) {
    <span class="code-keyword">case</span> <span class="code-number">1</span>:
        System.out.println(<span class="code-string">"Monday"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">case</span> <span class="code-number">2</span>:
        System.out.println(<span class="code-string">"Tuesday"</span>);
        <span class="code-keyword">break</span>;
    <span class="code-keyword">default</span>:
        System.out.println(<span class="code-string">"Other day"</span>);
}
                </div>
                
                <h3>Loops</h3>
                <div class="code-block">
<span class="code-comment">// For loop</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {
    System.out.println(<span class="code-string">"Count: "</span> + i);
}

<span class="code-comment">// While loop</span>
<span class="code-keyword">int</span> count = <span class="code-number">0</span>;
<span class="code-keyword">while</span> (count < <span class="code-number">3</span>) {
    System.out.println(<span class="code-string">"Count: "</span> + count);
    count++;
}

<span class="code-comment">// Do-while loop</span>
<span class="code-keyword">int</span> num = <span class="code-number">1</span>;
<span class="code-keyword">do</span> {
    System.out.println(<span class="code-string">"Number: "</span> + num);
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
                    <strong>💡 Tip:</strong> Use <code>&&</code> (AND), <code>||</code> (OR), and <code>!</code> (NOT) for logical operations in Java.
                </div>
            </div>
            
            <!-- Methods Section -->
            <div id="methods" class="topic-section" style="display: none;">
                <h2>Methods</h2>
                
                <p>Methods are blocks of code that perform specific tasks. They help organize code and promote reusability.</p>
                
                <h3>Defining Methods</h3>
                <div class="code-block">
<span class="code-comment">// Simple method</span>
<span class="code-keyword">public static void</span> greet(String name) {
    System.out.println(<span class="code-string">"Hello, "</span> + name + <span class="code-string">"!"</span>);
}

<span class="code-comment">// Method with return value</span>
<span class="code-keyword">public static int</span> addNumbers(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) {
    <span class="code-keyword">return</span> a + b;
}

<span class="code-comment">// Method overloading</span>
<span class="code-keyword">public static int</span> multiply(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) {
    <span class="code-keyword">return</span> a * b;
}

<span class="code-keyword">public static double</span> multiply(<span class="code-keyword">double</span> a, <span class="code-keyword">double</span> b) {
    <span class="code-keyword">return</span> a * b;
}
                </div>
                
                <h3>Method Parameters</h3>
                <div class="code-block">
<span class="code-comment">// Method with multiple parameters</span>
<span class="code-keyword">public static void</span> printInfo(String name, <span class="code-keyword">int</span> age, String city) {
    System.out.println(<span class="code-string">"Name: "</span> + name);
    System.out.println(<span class="code-string">"Age: "</span> + age);
    System.out.println(<span class="code-string">"City: "</span> + city);
}

<span class="code-comment">// Method with variable arguments (varargs)</span>
<span class="code-keyword">public static int</span> sum(<span class="code-keyword">int</span>... numbers) {
    <span class="code-keyword">int</span> total = <span class="code-number">0</span>;
    <span class="code-keyword">for</span> (<span class="code-keyword">int</span> num : numbers) {
        total += num;
    }
    <span class="code-keyword">return</span> total;
}
                </div>
                
                <h3>Access Modifiers</h3>
                <ul>
                    <li><strong>public:</strong> Accessible from anywhere</li>
                    <li><strong>private:</strong> Accessible only within the class</li>
                    <li><strong>protected:</strong> Accessible within package and subclasses</li>
                    <li><strong>default:</strong> Accessible only within the package</li>
                </ul>
                
                <h3>Static vs Instance Methods</h3>
                <div class="code-block">
<span class="code-comment">// Static method (belongs to class)</span>
<span class="code-keyword">public static void</span> staticMethod() {
    System.out.println(<span class="code-string">"This is a static method"</span>);
}

<span class="code-comment">// Instance method (belongs to object)</span>
<span class="code-keyword">public void</span> instanceMethod() {
    System.out.println(<span class="code-string">"This is an instance method"</span>);
}

<span class="code-comment">// Usage</span>
staticMethod(); <span class="code-comment">// Call static method directly</span>
MyClass obj = <span class="code-keyword">new</span> MyClass();
obj.instanceMethod(); <span class="code-comment">// Call instance method on object</span>
                </div>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Use descriptive method names that explain what the method does. Method names should be camelCase and start with a verb.
                </div>
            </div>
            
            <!-- Classes and Objects Section -->
            <div id="classes-objects" class="topic-section" style="display: none;">
                <h2>Classes and Objects</h2>
                
                <p>Java is an object-oriented language. Classes are blueprints for creating objects, which are instances of classes.</p>
                
                <h3>Creating a Class</h3>
                <div class="code-block">
<span class="code-comment">// Simple class definition</span>
<span class="code-keyword">public class</span> Person {
    <span class="code-comment">// Instance variables (fields)</span>
    <span class="code-keyword">private</span> String name;
    <span class="code-keyword">private int</span> age;
    
    <span class="code-comment">// Constructor</span>
    <span class="code-keyword">public</span> Person(String name, <span class="code-keyword">int</span> age) {
        <span class="code-keyword">this</span>.name = name;
        <span class="code-keyword">this</span>.age = age;
    }
    
    <span class="code-comment">// Getter methods</span>
    <span class="code-keyword">public</span> String getName() {
        <span class="code-keyword">return</span> name;
    }
    
    <span class="code-keyword">public int</span> getAge() {
        <span class="code-keyword">return</span> age;
    }
    
    <span class="code-comment">// Setter methods</span>
    <span class="code-keyword">public void</span> setName(String name) {
        <span class="code-keyword">this</span>.name = name;
    }
    
    <span class="code-keyword">public void</span> setAge(<span class="code-keyword">int</span> age) {
        <span class="code-keyword">this</span>.age = age;
    }
}
                </div>
                
                <h3>Creating Objects</h3>
                <div class="code-block">
<span class="code-comment">// Creating objects</span>
Person person1 = <span class="code-keyword">new</span> Person(<span class="code-string">"Alice"</span>, <span class="code-number">25</span>);
Person person2 = <span class="code-keyword">new</span> Person(<span class="code-string">"Bob"</span>, <span class="code-number">30</span>);

<span class="code-comment">// Accessing object methods</span>
System.out.println(person1.getName()); <span class="code-comment">// Output: Alice</span>
System.out.println(person2.getAge());  <span class="code-comment">// Output: 30</span>

<span class="code-comment">// Modifying object state</span>
person1.setAge(<span class="code-number">26</span>);
                </div>
                
                <h3>Inheritance</h3>
                <div class="code-block">
<span class="code-comment">// Parent class</span>
<span class="code-keyword">public class</span> Animal {
    <span class="code-keyword">protected</span> String name;
    
    <span class="code-keyword">public</span> Animal(String name) {
        <span class="code-keyword">this</span>.name = name;
    }
    
    <span class="code-keyword">public void</span> makeSound() {
        System.out.println(<span class="code-string">"Some sound"</span>);
    }
}

<span class="code-comment">// Child class</span>
<span class="code-keyword">public class</span> Dog <span class="code-keyword">extends</span> Animal {
    <span class="code-keyword">public</span> Dog(String name) {
        <span class="code-keyword">super</span>(name); <span class="code-comment">// Call parent constructor</span>
    }
    
    @Override
    <span class="code-keyword">public void</span> makeSound() {
        System.out.println(<span class="code-string">"Woof!"</span>);
    }
}
                </div>
                
                <h3>Encapsulation</h3>
                <ul>
                    <li><strong>Private Fields:</strong> Hide data from outside access</li>
                    <li><strong>Public Methods:</strong> Provide controlled access to data</li>
                    <li><strong>Getters/Setters:</strong> Allow reading and writing of private fields</li>
                    <li><strong>Data Validation:</strong> Ensure data integrity</li>
                </ul>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Use encapsulation to protect your data. Make fields private and provide public methods to access them.
                </div>
            </div>
            
            <!-- Arrays Section -->
            <div id="arrays" class="topic-section" style="display: none;">
                <h2>Arrays</h2>
                
                <p>Arrays in Java are fixed-size collections of elements of the same type. They provide efficient storage and access to multiple values.</p>
                
                <h3>Creating Arrays</h3>
                <div class="code-block">
<span class="code-comment">// Array declaration and initialization</span>
<span class="code-keyword">int</span>[] numbers = <span class="code-keyword">new int</span>[<span class="code-number">5</span>]; <span class="code-comment">// Creates array of size 5</span>

<span class="code-comment">// Array with initial values</span>
<span class="code-keyword">int</span>[] scores = {<span class="code-number">85</span>, <span class="code-number">92</span>, <span class="code-number">78</span>, <span class="code-number">96</span>};

<span class="code-comment">// String array</span>
String[] names = {<span class="code-string">"Alice"</span>, <span class="code-string">"Bob"</span>, <span class="code-string">"Charlie"</span>};
                </div>
                
                <h3>Accessing Array Elements</h3>
                <div class="code-block">
<span class="code-comment">// Accessing elements by index</span>
<span class="code-keyword">int</span>[] numbers = {<span class="code-number">10</span>, <span class="code-number">20</span>, <span class="code-number">30</span>, <span class="code-number">40</span>};

System.out.println(numbers[<span class="code-number">0</span>]); <span class="code-comment">// Output: 10</span>
System.out.println(numbers[<span class="code-number">2</span>]); <span class="code-comment">// Output: 30</span>

<span class="code-comment">// Modifying array elements</span>
numbers[<span class="code-number">1</span>] = <span class="code-number">25</span>;

<span class="code-comment">// Array length</span>
System.out.println(numbers.length); <span class="code-comment">// Output: 4</span>
                </div>
                
                <h3>Iterating Through Arrays</h3>
                <div class="code-block">
<span class="code-comment">// Traditional for loop</span>
<span class="code-keyword">int</span>[] numbers = {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>, <span class="code-number">4</span>, <span class="code-number">5</span>};

<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < numbers.length; i++) {
    System.out.println(<span class="code-string">"Element "</span> + i + <span class="code-string">": "</span> + numbers[i]);
}

<span class="code-comment">// Enhanced for loop (for-each)</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> number : numbers) {
    System.out.println(<span class="code-string">"Number: "</span> + number);
}
                </div>
                
                <h3>Multi-dimensional Arrays</h3>
                <div class="code-block">
<span class="code-comment">// 2D array</span>
<span class="code-keyword">int</span>[][] matrix = {
    {<span class="code-number">1</span>, <span class="code-number">2</span>, <span class="code-number">3</span>},
    {<span class="code-number">4</span>, <span class="code-number">5</span>, <span class="code-number">6</span>},
    {<span class="code-number">7</span>, <span class="code-number">8</span>, <span class="code-number">9</span>}
};

<span class="code-comment">// Accessing 2D array elements</span>
System.out.println(matrix[<span class="code-number">0</span>][<span class="code-number">1</span>]); <span class="code-comment">// Output: 2</span>

<span class="code-comment">// Iterating through 2D array</span>
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < matrix.length; i++) {
    <span class="code-keyword">for</span> (<span class="code-keyword">int</span> j = <span class="code-number">0</span>; j < matrix[i].length; j++) {
        System.out.print(matrix[i][j] + <span class="code-string">" "</span>);
    }
    System.out.println();
}
                </div>
                
                <h3>Array Methods</h3>
                <div class="code-block">
<span class="code-comment">// Arrays utility class</span>
<span class="code-keyword">import</span> java.util.Arrays;

<span class="code-keyword">int</span>[] numbers = {<span class="code-number">5</span>, <span class="code-number">2</span>, <span class="code-number">8</span>, <span class="code-number">1</span>, <span class="code-number">9</span>};

<span class="code-comment">// Sorting</span>
Arrays.sort(numbers);

<span class="code-comment">// Searching</span>
<span class="code-keyword">int</span> index = Arrays.binarySearch(numbers, <span class="code-number">8</span>);

<span class="code-comment">// Filling</span>
Arrays.fill(numbers, <span class="code-number">0</span>);

<span class="code-comment">// Converting to string</span>
String arrayString = Arrays.toString(numbers);
                </div>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Remember that array indices start at 0. The last element is at index length-1.
                </div>
            </div>
            
            <!-- Exception Handling Section -->
            <div id="exception-handling" class="topic-section" style="display: none;">
                <h2>7. Exception Handling</h2>
                
                <p>Exception handling allows your program to gracefully handle errors and unexpected situations.</p>
                
                <h3>Try-Catch Blocks</h3>
                <div class="code-block">
<span class="code-comment">// Basic exception handling</span>
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> number = Integer.parseInt(<span class="code-string">"abc"</span>);
    System.out.println(number);
} <span class="code-keyword">catch</span> (NumberFormatException e) {
    System.out.println(<span class="code-string">"That's not a valid number!"</span>);
}

<span class="code-comment">// Multiple catch blocks</span>
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> result = <span class="code-number">10</span> / <span class="code-number">0</span>;
} <span class="code-keyword">catch</span> (ArithmeticException e) {
    System.out.println(<span class="code-string">"Cannot divide by zero!"</span>);
} <span class="code-keyword">catch</span> (Exception e) {
    System.out.println(<span class="code-string">"An error occurred: "</span> + e.getMessage());
}
                </div>
                
                <h3>Try-Catch-Finally</h3>
                <div class="code-block">
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> number = Integer.parseInt(<span class="code-string">"42"</span>);
    System.out.println(<span class="code-string">"Number: "</span> + number);
} <span class="code-keyword">catch</span> (NumberFormatException e) {
    System.out.println(<span class="code-string">"Invalid number format"</span>);
} <span class="code-keyword">finally</span> {
    System.out.println(<span class="code-string">"This always executes"</span>);
}
                </div>
                
                <h3>Custom Exceptions</h3>
                <div class="code-block">
<span class="code-comment">// Creating custom exception</span>
<span class="code-keyword">public class</span> AgeException <span class="code-keyword">extends</span> Exception {
    <span class="code-keyword">public</span> AgeException(String message) {
        <span class="code-keyword">super</span>(message);
    }
}

<span class="code-comment">// Using custom exception</span>
<span class="code-keyword">public static void</span> checkAge(<span class="code-keyword">int</span> age) <span class="code-keyword">throws</span> AgeException {
    <span class="code-keyword">if</span> (age < <span class="code-number">0</span>) {
        <span class="code-keyword">throw new</span> AgeException(<span class="code-string">"Age cannot be negative"</span>);
    } <span class="code-keyword">else if</span> (age > <span class="code-number">150</span>) {
        <span class="code-keyword">throw new</span> AgeException(<span class="code-string">"Age seems unrealistic"</span>);
    }
}

<span class="code-keyword">try</span> {
    checkAge(-<span class="code-number">5</span>);
} <span class="code-keyword">catch</span> (AgeException e) {
    System.out.println(e.getMessage());
}
                </div>
                
                <h3>Common Exception Types</h3>
                <ul>
                    <li><strong>NullPointerException:</strong> Accessing null object</li>
                    <li><strong>ArrayIndexOutOfBoundsException:</strong> Invalid array index</li>
                    <li><strong>NumberFormatException:</strong> Invalid number conversion</li>
                    <li><strong>ArithmeticException:</strong> Division by zero</li>
                    <li><strong>ClassCastException:</strong> Invalid type casting</li>
                    <li><strong>FileNotFoundException:</strong> File not found</li>
                </ul>
                
                <div class="tip-box">
                    <strong>💡 Tip:</strong> Always handle exceptions appropriately. Don't catch exceptions unless you can handle them meaningfully.
                </div>
            </div>
        </div>
        
        <div class="navigation-bar">
            <div class="nav-buttons">
                <button class="nav-btn" onclick="previousTopic()" id="prevBtn">Previous</button>
                <button class="nav-btn" onclick="nextTopic()" id="nextBtn">Next</button>
            </div>
            
            <select class="topic-dropdown" onchange="changeTopic(this.value)">
                <option value="introduction">1. Introduction to Java</option>
                <option value="variables">2. Variables and Data Types</option>
                <option value="control-flow">3. Control Flow</option>
                <option value="methods">4. Methods</option>
                <option value="classes-objects">5. Classes and Objects</option>
                <option value="arrays">6. Arrays</option>
                <option value="exception-handling">7. Exception Handling</option>
            </select>
            
            <div class="progress-indicator">
                <span id="currentTopic">1</span> of <span id="totalTopics">7</span>
            </div>
        </div>
    </div>

    <script>
        let currentTopicIndex = 0;
        const topics = [
            'introduction',
            'variables', 
            'control-flow',
            'methods',
            'classes-objects',
            'arrays',
            'exception-handling'
        ];
        
        function showTopic(index) {
            // Hide all topics
            document.querySelectorAll('.topic-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show current topic
            document.getElementById(topics[index]).style.display = 'block';
            
            // Update navigation
            document.getElementById('prevBtn').disabled = index === 0;
            document.getElementById('nextBtn').disabled = index === topics.length - 1;
            
            // Update dropdown
            document.querySelector('.topic-dropdown').value = topics[index];
            
            // Update progress
            document.getElementById('currentTopic').textContent = index + 1;
            document.getElementById('totalTopics').textContent = topics.length;
        }
        
        function nextTopic() {
            if (currentTopicIndex < topics.length - 1) {
                currentTopicIndex++;
                showTopic(currentTopicIndex);
            }
        }
        
        function previousTopic() {
            if (currentTopicIndex > 0) {
                currentTopicIndex--;
                showTopic(currentTopicIndex);
            }
        }
        
        function changeTopic(topicName) {
            currentTopicIndex = topics.indexOf(topicName);
            showTopic(currentTopicIndex);
        }
        
        function closeLesson() {
            // Send message to parent window to close lesson
            if (window.parent && window.parent.postMessage) {
                window.parent.postMessage({ type: 'closeLesson' }, '*');
            }
        }
        
        // Initialize
        showTopic(0);
    </script>
</body>
</html> 