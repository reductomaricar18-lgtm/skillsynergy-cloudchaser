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
    <title>Java Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #232526 0%, #ff512f 100%);
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
          <h2>Generics</h2>
          <p>Generics enable types (classes and methods) to operate on objects of various types while providing compile-time type safety.</p>
          <div class="code-block">
<span class="code-comment">// Generic class</span>
<span class="code-keyword">class</span> Box<T> {
    T value;
    Box(T value) { this.value = value; }
    T getValue() { return value; }
}

Box<Integer> intBox = <span class="code-keyword">new</span> Box<>(123);
System.out.println(intBox.getValue());
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use generics to avoid <code>ClassCastException</code> and for cleaner code.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Multithreading</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Java supports multithreading, allowing concurrent execution of two or more threads for maximum CPU utilization.</p>
          <div class="code-block">
<span class="code-comment">// Thread by extending Thread</span>
<span class="code-keyword">class</span> MyThread <span class="code-keyword">extends</span> Thread {
    <span class="code-keyword">public void</span> run() {
        System.out.println("Thread running");
    }
}
MyThread t = <span class="code-keyword">new</span> MyThread();
t.start();

<span class="code-comment">// Thread by implementing Runnable</span>
<span class="code-keyword">class</span> MyRunnable <span class="code-keyword">implements</span> Runnable {
    <span class="code-keyword">public void</span> run() {
        System.out.println("Runnable running");
    }
}
Thread t2 = <span class="code-keyword">new</span> Thread(<span class="code-keyword">new</span> MyRunnable());
t2.start();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>synchronized</code> blocks to avoid race conditions.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Lambda Expressions & Streams</h2>
          <p>Lambda expressions provide a clear and concise way to represent one method interface. Streams process sequences of elements.</p>
          <div class="code-block">
<span class="code-comment">// Lambda and Stream example</span>
List<Integer> nums = Arrays.asList(1, 2, 3, 4);
nums.stream().filter(n -> n % 2 == 0).forEach(System.out::println);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use lambdas for functional-style programming and cleaner code.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Annotations & Reflection</h2>
          <p>Annotations provide metadata. Reflection allows inspection and modification of classes at runtime.</p>
          <div class="code-block">
<span class="code-comment">// Custom annotation</span>
<span class="code-keyword">@interface</span> MyAnnotation {
    String value();
}

<span class="code-comment">// Using reflection</span>
Class<?> clazz = MyClass.class;
Method[] methods = clazz.getDeclaredMethods();
for (Method m : methods) {
    System.out.println(m.getName());
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Reflection is powerful but should be used sparingly for performance and security reasons.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Networking (Sockets)</h2>
          <p>Java provides APIs for network programming using sockets for client-server communication.</p>
          <div class="code-block">
<span class="code-comment">// Simple client socket</span>
Socket s = <span class="code-keyword">new</span> Socket("localhost", 1234);
OutputStream out = s.getOutputStream();
out.write("Hello".getBytes());
s.close();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always close sockets and handle IOExceptions.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Design Patterns</h2>
          <p>Common patterns like Singleton and Observer help solve recurring design problems.</p>
          <div class="code-block">
<span class="code-comment">// Singleton pattern</span>
<span class="code-keyword">class</span> Singleton {
    <span class="code-keyword">private static</span> Singleton instance;
    <span class="code-keyword">private</span> Singleton() {}
    <span class="code-keyword">public static</span> Singleton getInstance() {
        if (instance == null) instance = <span class="code-keyword">new</span> Singleton();
        return instance;
    }
}
          </div>
          <div class="code-block">
<span class="code-comment">// Observer pattern (simplified)</span>
<span class="code-keyword">interface</span> Observer { void update(); }
<span class="code-keyword">class</span> Subject {
    List<Observer> observers = <span class="code-keyword">new</span> ArrayList<>();
    void addObserver(Observer o) { observers.add(o); }
    void notifyAllObservers() { for (Observer o : observers) o.update(); }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Learn and apply design patterns for scalable and maintainable code.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Best Practices</h2>
          <ul>
            <li>Use meaningful class, method, and variable names.</li>
            <li>Follow Java naming conventions.</li>
            <li>Write unit tests and use assertions.</li>
            <li>Document code with Javadoc.</li>
            <li>Handle exceptions properly.</li>
            <li>Use design patterns where appropriate.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Writing clean, efficient, and maintainable Java code is the hallmark of an advanced developer.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Java Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Generics</button>
          <button class="topic-btn" data-index="1">Multithreading</button>
          <button class="topic-btn" data-index="2">Lambda Expressions & Streams</button>
          <button class="topic-btn" data-index="3">Annotations & Reflection</button>
          <button class="topic-btn" data-index="4">Networking (Sockets)</button>
          <button class="topic-btn" data-index="5">Design Patterns</button>
          <button class="topic-btn" data-index="6">Best Practices</button>
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