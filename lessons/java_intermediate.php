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
    <title>Java Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
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
          <p>Java is an object-oriented language. Classes are blueprints for objects, and objects are instances of classes.</p>
          <div class="code-block">
<span class="code-comment">// Class and object example</span>
<span class="code-keyword">class</span> Dog {
    String name;
    <span class="code-keyword">void</span> bark() {
        System.out.println("Woof!");
    }
}

Dog d = <span class="code-keyword">new</span> Dog();
d.name = "Buddy";
d.bark();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use constructors to initialize objects.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Inheritance</h2>
          <p>Inheritance allows a class to acquire properties and methods of another class.</p>
          <div class="code-block">
<span class="code-comment">// Inheritance example</span>
<span class="code-keyword">class</span> Animal {
    <span class="code-keyword">void</span> eat() { System.out.println("Eating..."); }
}
<span class="code-keyword">class</span> Dog <span class="code-keyword">extends</span> Animal {
    <span class="code-keyword">void</span> bark() { System.out.println("Barking..."); }
}

Dog d = <span class="code-keyword">new</span> Dog();
d.eat();
d.bark();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>super</code> to call parent class methods or constructors.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Polymorphism</h2>
          <p>Polymorphism allows objects to be treated as instances of their parent class, enabling dynamic method binding.</p>
          <div class="code-block">
<span class="code-comment">// Polymorphism example</span>
<span class="code-keyword">class</span> Animal {
    <span class="code-keyword">void</span> sound() { System.out.println("Animal sound"); }
}
<span class="code-keyword">class</span> Cat <span class="code-keyword">extends</span> Animal {
    <span class="code-keyword">void</span> sound() { System.out.println("Meow"); }
}

Animal a = <span class="code-keyword">new</span> Cat();
a.sound(); <span class="code-comment">// Output: Meow</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use method overriding for runtime polymorphism.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Abstract Classes & Interfaces</h2>
          <p>Abstract classes can have abstract (unimplemented) methods. Interfaces define contracts for classes to implement.</p>
          <div class="code-block">
<span class="code-comment">// Abstract class</span>
<span class="code-keyword">abstract class</span> Shape {
    <span class="code-keyword">abstract void</span> draw();
}
<span class="code-keyword">class</span> Circle <span class="code-keyword">extends</span> Shape {
    <span class="code-keyword">void</span> draw() { System.out.println("Drawing Circle"); }
}

<span class="code-comment">// Interface</span>
<span class="code-keyword">interface</span> Drawable {
    <span class="code-keyword">void</span> draw();
}
<span class="code-keyword">class</span> Square <span class="code-keyword">implements</span> Drawable {
    <span class="code-keyword">public void</span> draw() { System.out.println("Drawing Square"); }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> A class can implement multiple interfaces but only extend one class.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Exception Handling</h2>
          <p>Handle runtime errors using try-catch blocks, and create custom exceptions for specific error cases.</p>
          <div class="code-block">
<span class="code-comment">// Exception handling example</span>
<span class="code-keyword">try</span> {
    <span class="code-keyword">int</span> x = 5 / 0;
} <span class="code-keyword">catch</span> (ArithmeticException e) {
    System.out.println("Cannot divide by zero");
} <span class="code-keyword">finally</span> {
    System.out.println("Done");
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>throws</code> to declare exceptions and <code>throw</code> to raise them.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Collections Framework</h2>
          <p>Java Collections provide data structures like List, Set, and Map for storing and manipulating groups of objects.</p>
          <div class="code-block">
<span class="code-comment">// Using ArrayList</span>
ArrayList<String> list = <span class="code-keyword">new</span> ArrayList<>();
list.add("Apple");
list.add("Banana");
for (String fruit : list) {
    System.out.println(fruit);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use generics for type safety in collections.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>File I/O</h2>
          <p>Read and write files using classes like FileReader, FileWriter, FileInputStream, and FileOutputStream.</p>
          <div class="code-block">
<span class="code-comment">// Writing to a file</span>
<span class="code-keyword">try</span> (FileWriter fw = <span class="code-keyword">new</span> FileWriter("output.txt")) {
    fw.write("Hello, Java!");
}

<span class="code-comment">// Reading from a file</span>
<span class="code-keyword">try</span> (BufferedReader br = <span class="code-keyword">new</span> BufferedReader(<span class="code-keyword">new</span> FileReader("output.txt"))) {
    String line;
    <span class="code-keyword">while</span> ((line = br.readLine()) != null) {
        System.out.println(line);
    }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always close file streams or use try-with-resources for automatic closing.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Java Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Classes and Objects</button>
          <button class="topic-btn" data-index="1">Inheritance</button>
          <button class="topic-btn" data-index="2">Polymorphism</button>
          <button class="topic-btn" data-index="3">Abstract Classes & Interfaces</button>
          <button class="topic-btn" data-index="4">Exception Handling</button>
          <button class="topic-btn" data-index="5">Collections Framework</button>
          <button class="topic-btn" data-index="6">File I/O</button>
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