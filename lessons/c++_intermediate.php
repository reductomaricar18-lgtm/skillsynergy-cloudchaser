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
    <title>C++ Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
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
          <h2>Classes and Objects</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
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