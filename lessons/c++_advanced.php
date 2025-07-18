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
    <title>C++ Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
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
          <h2>Smart Pointers</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Smart pointers (<code>std::unique_ptr</code>, <code>std::shared_ptr</code>, <code>std::weak_ptr</code>) manage memory automatically and help prevent leaks.</p>
          <div class="code-block">
#include <memory>
std::unique_ptr<int> p1 = std::make_unique<int>(10);
std::shared_ptr<int> p2 = std::make_shared<int>(20);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Prefer smart pointers over raw pointers for resource management.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Move Semantics</h2>
          <p>Move semantics allow efficient transfer of resources using <code>std::move</code> and move constructors.</p>
          <div class="code-block">
#include <utility>
std::vector<int> v1 = {1,2,3};
std::vector<int> v2 = std::move(v1); // v1 is now empty
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>std::move</code> to avoid unnecessary copies, especially with large objects.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Lambda Expressions</h2>
          <p>Lambdas are anonymous functions that can capture variables from their scope.</p>
          <div class="code-block">
auto add = [](int a, int b) { return a + b; };
printf("%d\n", add(2,3));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Lambdas are widely used with STL algorithms.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced STL: Algorithms & Iterators</h2>
          <p>STL provides powerful algorithms and iterator support for containers.</p>
          <div class="code-block">
#include <algorithm>
#include <vector>
std::vector<int> v = {3,1,4,1,5};
std::sort(v.begin(), v.end());
for (auto it = v.begin(); it != v.end(); ++it) std::cout << *it << " ";
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Learn STL algorithms like <code>sort</code>, <code>find</code>, <code>accumulate</code>, <code>for_each</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Exception Handling</h2>
          <p>Use <code>try</code>, <code>catch</code>, and <code>throw</code> to handle errors gracefully.</p>
          <div class="code-block">
try {
    throw std::runtime_error("Error!");
} catch (const std::exception& e) {
    std::cout << e.what() << std::endl;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always catch exceptions by reference to avoid slicing.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Multithreading</h2>
          <p>Use <code>std::thread</code> and <code>std::mutex</code> for concurrent programming.</p>
          <div class="code-block">
#include <thread>
#include <mutex>
std::mutex mtx;
void printMsg() {
    std::lock_guard<std::mutex> lock(mtx);
    std::cout << "Hello from thread!" << std::endl;
}
std::thread t1(printMsg);
t1.join();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>lock_guard</code> or <code>unique_lock</code> to manage mutexes safely.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Design Patterns & Best Practices</h2>
          <ul>
            <li>Use RAII (Resource Acquisition Is Initialization) for resource management.</li>
            <li>Prefer smart pointers and STL containers.</li>
            <li>Write exception-safe code.</li>
            <li>Understand and use the Singleton, Factory, and Observer patterns.</li>
            <li>Comment and document your code.</li>
            <li>Use <code>const</code> correctness and avoid raw pointers when possible.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Mastering modern C++ features and best practices leads to safer, faster, and more maintainable code.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C++ Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Smart Pointers</button>
          <button class="topic-btn" data-index="1">Move Semantics</button>
          <button class="topic-btn" data-index="2">Lambda Expressions</button>
          <button class="topic-btn" data-index="3">Advanced STL: Algorithms & Iterators</button>
          <button class="topic-btn" data-index="4">Exception Handling</button>
          <button class="topic-btn" data-index="5">Multithreading</button>
          <button class="topic-btn" data-index="6">Design Patterns & Best Practices</button>
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