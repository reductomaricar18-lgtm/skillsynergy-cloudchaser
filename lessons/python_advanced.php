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
    <title>Python Advanced - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #232526 0%, #ff512f 100%); min-height: 100vh; }
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
          <h2>Decorators</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Decorators are functions that modify the behavior of other functions or classes.</p>
          <div class="code-block">
<span class="code-comment"># Simple decorator</span>
def my_decorator(func):
    def wrapper(*args, **kwargs):
        print("Before call")
        result = func(*args, **kwargs)
        print("After call")
        return result
    return wrapper

@my_decorator
def greet(name):
    print(f"Hello, {name}!")

greet("Alice")
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>@property</code>, <code>@staticmethod</code>, and <code>@classmethod</code> for class decorators.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Generators</h2>
          <p>Generators yield values one at a time, saving memory and enabling lazy evaluation.</p>
          <div class="code-block">
<span class="code-comment"># Generator function</span>
def count_up(n):
    i = 0
    while i < n:
        yield i
        i += 1

for num in count_up(5):
    print(num)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>yield</code> instead of <code>return</code> to create a generator.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Context Managers</h2>
          <p>Context managers handle setup and cleanup actions, often used with <code>with</code> statements.</p>
          <div class="code-block">
<span class="code-comment"># Using a context manager</span>
with open('file.txt', 'w') as f:
    f.write('Hello!')

<span class="code-comment"># Custom context manager</span>
class MyContext:
    def __enter__(self):
        print("Entering")
        return self
    def __exit__(self, exc_type, exc_val, exc_tb):
        print("Exiting")

with MyContext():
    print("Inside context")
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>contextlib</code> for easy context manager creation.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced OOP (Multiple Inheritance, Metaclasses)</h2>
          <p>Python supports multiple inheritance and metaclasses for advanced object-oriented programming.</p>
          <div class="code-block">
<span class="code-comment"># Multiple inheritance</span>
class A:
    def foo(self): print("A")
class B:
    def foo(self): print("B")
class C(A, B):
    pass
c = C()
c.foo()  # A (method resolution order)

<span class="code-comment"># Metaclass example</span>
class MyMeta(type):
    def __new__(cls, name, bases, dct):
        print(f"Creating class {name}")
        return super().__new__(cls, name, bases, dct)
class MyClass(metaclass=MyMeta):
    pass
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>super()</code> and <code>__mro__</code> to understand method resolution order.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Concurrency (threading, asyncio)</h2>
          <p>Write concurrent code using threads or asynchronous programming.</p>
          <div class="code-block">
<span class="code-comment"># Threading</span>
import threading
def worker():
    print("Worker running")
t = threading.Thread(target=worker)
t.start()
t.join()

<span class="code-comment"># Asyncio</span>
import asyncio
async def main():
    print("Hello")
    await asyncio.sleep(1)
    print("World")
asyncio.run(main())
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>async</code> and <code>await</code> for non-blocking code.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Type Hints & Annotations</h2>
          <p>Type hints improve code readability and help with static analysis.</p>
          <div class="code-block">
<span class="code-comment"># Type hints</span>
def add(x: int, y: int) -> int:
    return x + y

<span class="code-comment"># Variable annotation</span>
name: str = "Alice"
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>mypy</code> to check type hints in your code.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Best Practices (PEP8, Testing)</h2>
          <ul>
            <li>Follow <a href="https://peps.python.org/pep-0008/" target="_blank">PEP8</a> for code style.</li>
            <li>Write docstrings for all public modules, functions, classes, and methods.</li>
            <li>Use <code>unittest</code> or <code>pytest</code> for testing.</li>
            <li>Use virtual environments for project isolation.</li>
            <li>Keep functions small and focused.</li>
            <li>Use version control (git).</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Clean, tested code is the mark of an advanced Python developer.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Python Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Decorators</button>
          <button class="topic-btn" data-index="1">Generators</button>
          <button class="topic-btn" data-index="2">Context Managers</button>
          <button class="topic-btn" data-index="3">Advanced OOP</button>
          <button class="topic-btn" data-index="4">Concurrency</button>
          <button class="topic-btn" data-index="5">Type Hints & Annotations</button>
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