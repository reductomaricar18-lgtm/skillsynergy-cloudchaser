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
    <title>C++ Intermediate - SkillSynergy</title>
    
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
          <h2>Classes and Objects</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>Classes are user-defined types that encapsulate data and functions. Objects are instances of classes.</p>
          <div class="code-block">
<span class="code-comment">// Class definition</span>
<span class="code-keyword">class</span> Person {
    <span class="code-keyword">public</span>:
        std::string name;
        <span class="code-keyword">int</span> age;
        <span class="code-keyword">void</span> greet() {
            std::cout << "Hello, my name is " << name << std::endl;
        }
};

Person p;
p.name = "Alice";
p.age = 25;
p.greet();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>public</code>, <code>private</code>, and <code>protected</code> to control access to members.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Constructors and Destructors</h2>
          <p>Constructors initialize objects. Destructors clean up resources when objects are destroyed.</p>
          <div class="code-block">
<span class="code-comment">// Constructor and Destructor</span>
<span class="code-keyword">class</span> Car {
    <span class="code-keyword">public</span>:
        Car() { std::cout << "Car created!" << std::endl; }
        ~Car() { std::cout << "Car destroyed!" << std::endl; }
};

Car c; <span class="code-comment">// Output: Car created! ... Car destroyed! (when c goes out of scope)</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use destructors to release memory or close files.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Operator Overloading</h2>
          <p>Operators can be redefined for user-defined types to make code more intuitive.</p>
          <div class="code-block">
<span class="code-comment">// Overloading + operator</span>
<span class="code-keyword">class</span> Point {
    <span class="code-keyword">public</span>:
        <span class="code-keyword">int</span> x, y;
        Point(<span class="code-keyword">int</span> x, <span class="code-keyword">int</span> y) : x(x), y(y) {}
        Point operator+(<span class="code-keyword">const</span> Point& other) {
            return Point(x + other.x, y + other.y);
        }
};

Point p1(1,2), p2(3,4);
Point p3 = p1 + p2; <span class="code-comment">// p3.x = 4, p3.y = 6</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Overload operators to make your classes behave like built-in types.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Inheritance</h2>
          <p>Inheritance allows a class to acquire properties and methods of another class.</p>
          <div class="code-block">
<span class="code-comment">// Inheritance example</span>
<span class="code-keyword">class</span> Animal {
    <span class="code-keyword">public</span>:
        <span class="code-keyword">void</span> speak() { std::cout << "Some sound" << std::endl; }
};

<span class="code-keyword">class</span> Dog : <span class="code-keyword">public</span> Animal {
    <span class="code-keyword">public</span>:
        <span class="code-keyword">void</span> speak() { std::cout << "Woof!" << std::endl; }
};

Dog d;
d.speak(); <span class="code-comment">// Output: Woof!</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>virtual</code> functions for polymorphism.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Polymorphism</h2>
          <p>Polymorphism allows you to use a unified interface for different data types.</p>
          <div class="code-block">
<span class="code-comment">// Virtual function example</span>
<span class="code-keyword">class</span> Shape {
    <span class="code-keyword">public</span>:
        <span class="code-keyword">virtual</span> <span class="code-keyword">void</span> draw() { std::cout << "Drawing shape" << std::endl; }
};

<span class="code-keyword">class</span> Circle : <span class="code-keyword">public</span> Shape {
    <span class="code-keyword">public</span>:
        <span class="code-keyword">void</span> draw() <span class="code-keyword">override</span> { std::cout << "Drawing circle" << std::endl; }
};

Shape* s = <span class="code-keyword">new</span> Circle();
s->draw(); <span class="code-comment">// Output: Drawing circle</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>override</code> to avoid mistakes in overriding virtual functions.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Templates</h2>
          <p>Templates allow writing generic and reusable code for any data type.</p>
          <div class="code-block">
<span class="code-comment">// Function template</span>
template <typename T>
T add(T a, T b) {
    return a + b;
}

<span class="code-comment">// Class template</span>
template <typename T>
class Box {
    T value;
    <span class="code-keyword">public</span>:
        Box(T v) : value(v) {}
        T get() { return value; }
};

Box<int> b(5);
std::cout << b.get() << std::endl; <span class="code-comment">// Output: 5</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> STL containers are implemented using templates.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>STL: vector, map, set</h2>
          <p>The Standard Template Library (STL) provides powerful containers and algorithms.</p>
          <div class="code-block">
<span class="code-comment">// Using vector</span>
#include <vector>
std::vector<int> v = {1,2,3};
v.push_back(4);
for (int x : v) std::cout << x << " ";

<span class="code-comment">// Using map</span>
#include <map>
std::map<std::string, int> m;
m["apple"] = 3;

<span class="code-comment">// Using set</span>
#include <set>
std::set<int> s;
s.insert(10);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> STL saves time and reduces bugs. Learn its containers and algorithms!
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
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C++ Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Classes and Objects</button>
          <button class="topic-btn" data-index="1">Constructors and Destructors</button>
          <button class="topic-btn" data-index="2">Operator Overloading</button>
          <button class="topic-btn" data-index="3">Inheritance</button>
          <button class="topic-btn" data-index="4">Polymorphism</button>
          <button class="topic-btn" data-index="5">Templates</button>
          <button class="topic-btn" data-index="6">STL: vector, map, set</button>
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