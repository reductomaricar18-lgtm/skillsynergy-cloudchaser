    <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        margin: 0; 
        padding: 0; 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        min-height: 100vh; 
    }
    .lesson-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 100px;
        max-height: 400px;
        position: relative;
    }
    .lesson-header {
        min-height: 25px;
        max-height: 70px;
        flex: 0 0 10px;
        display: flex;
        align-items: center;
        padding: 0 10px;
        background: #667eea;
        color: #fff;
        border-bottom: 1px solid #e2e8f0;
        font-size: 1.3rem;
        font-weight: 700;
        justify-content: space-between;
    }
    .lesson-scrollable {
        flex: 1 1 auto;
        overflow-y: auto;
        background: #f8f9fa;
        padding: 16px;
        border-radius: 0 0 0 0;
        margin-bottom: 0;
        min-height: 160px;
        height: auto;
        position: relative;
        max-height: 400px;
    }
    .lesson-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #333;
    }
    .lesson-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border-top: 5px solid #e2e8f0;
        padding: 16px 48px 16px 48px;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
        z-index: 2;
        flex: 0 0 auto;
        box-sizing: border-box;
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
    }
    .lesson-nav button {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 18px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
        margin: 0 16px;
    }
    .lesson-nav button:disabled {
        background: #b0b0b0;
        cursor: not-allowed;
    }
    .lesson-nav button:hover:not(:disabled) {
        background: #0056b3;
    }
    .lesson-section { display: none; }
    .lesson-section.active { display: block; }
    #otherTopicsDropdown {
        display: none;
        position: absolute;
        bottom: 48px;
        right: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        z-index: 10;
        padding: 6px 0;
    }
    #otherTopicsDropdown .topic-btn {
        display: block;
        width: 100%;
        border: none;
        background: none;
        padding: 10px 20px;
        text-align: left;
        font-size: 1rem;
        color: #333;
        cursor: pointer;
        transition: background 0.2s;
    }
    #otherTopicsDropdown .topic-btn:hover {
        background: #f0f4ff;
        color: #4a63ff;
    }
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
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
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
  <!-- Removed duplicate navigation card here -->
</div>
<script>
// Show only the first section (Introduction) by default
(function() {
    const sections = document.querySelectorAll('.topic-section');
    sections.forEach((section, index) => {
        if (index === 0) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
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