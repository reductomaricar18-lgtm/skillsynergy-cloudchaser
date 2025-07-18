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
    <title>C Advanced - SkillSynergy</title>
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
          <h2>Advanced Pointers</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Explore pointer to pointer, pointer arithmetic, and arrays of pointers for powerful memory manipulation.</p>
          <div class="code-block">
<span class="code-comment">// Pointer to pointer</span>
<span class="code-keyword">int</span> x = <span class="code-number">10</span>;
<span class="code-keyword">int</span> *p = &x;
<span class="code-keyword">int</span> **pp = &p;
printf(<span class="code-string">"%d\n"</span>, **pp); <span class="code-comment">// Output: 10</span>

<span class="code-comment">// Array of pointers</span>
<span class="code-keyword">int</span> a = 1, b = 2, c = 3;
<span class="code-keyword">int</span> *arr[3] = {&a, &b, &c};
for (<span class="code-keyword">int</span> i = 0; i < 3; i++) {
    printf(<span class="code-string">"%d\n"</span>, *arr[i]);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Pointer arithmetic lets you traverse arrays efficiently, but always stay within bounds!
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Dynamic Data Structures</h2>
          <p>Implement linked lists, stacks, and queues using pointers and structs.</p>
          <div class="code-block">
<span class="code-comment">// Singly linked list node</span>
<span class="code-keyword">struct</span> Node {
    <span class="code-keyword">int</span> data;
    <span class="code-keyword">struct</span> Node *next;
};

<span class="code-comment">// Creating a node</span>
<span class="code-keyword">struct</span> Node *head = (<span class="code-keyword">struct</span> Node*)malloc(<span class="code-keyword">sizeof</span>(<span class="code-keyword">struct</span> Node));
head->data = <span class="code-number">5</span>;
head->next = NULL;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Mastering dynamic data structures is key for advanced C programming and interviews.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Advanced File I/O</h2>
          <p>Use <code>fseek</code>, <code>ftell</code>, and error handling for random access and robust file operations.</p>
          <div class="code-block">
<span class="code-comment">// Random access in a file</span>
FILE *f = fopen(<span class="code-string">"data.bin"</span>, <span class="code-string">"rb"</span>);
fseek(f, <span class="code-number">2</span> * <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>), SEEK_SET);
<span class="code-keyword">int</span> value;
fread(&value, <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>), 1, f);
printf(<span class="code-string">"%d\n"</span>, value);
fclose(f);

<span class="code-comment">// Error handling</span>
FILE *file = fopen(<span class="code-string">"nofile.txt"</span>, <span class="code-string">"r"</span>);
if (!file) {
    perror(<span class="code-string">"File open error"</span>);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check file pointers and use <code>perror</code> or <code>strerror</code> for error messages.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Bit Manipulation</h2>
          <p>Manipulate individual bits using bitwise operators for performance and low-level programming.</p>
          <div class="code-block">
<span class="code-comment">// Bitwise operations</span>
<span class="code-keyword">int</span> x = <span class="code-number">5</span>; <span class="code-comment">// 0101</span>
<span class="code-keyword">int</span> y = <span class="code-number">3</span>; <span class="code-comment">// 0011</span>
printf(<span class="code-string">"AND: %d\n"</span>, x & y); <span class="code-comment">// 1</span>
printf(<span class="code-string">"OR: %d\n"</span>, x | y); <span class="code-comment">// 7</span>
printf(<span class="code-string">"XOR: %d\n"</span>, x ^ y); <span class="code-comment">// 6</span>
printf(<span class="code-string">"Left shift: %d\n"</span>, x << 1); <span class="code-comment">// 10</span>
printf(<span class="code-string">"Right shift: %d\n"</span>, x >> 1); <span class="code-comment">// 2</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Bit manipulation is essential for embedded, systems, and performance-critical code.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Memory Management & Tools</h2>
          <p>Detect and prevent memory leaks, and use tools like <code>valgrind</code> for debugging.</p>
          <div class="code-block">
<span class="code-comment">// Memory leak example</span>
<span class="code-keyword">int</span> *leak = (<span class="code-keyword">int</span>*)malloc(<span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>));
*leak = <span class="code-number">42</span>;
<span class="code-comment">// forgot to free(leak);</span>

<span class="code-comment">// Using valgrind (run in terminal)</span>
<span class="code-keyword">valgrind</span> --leak-check=full ./your_program
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always <code>free</code> what you <code>malloc</code> and use tools to check for leaks.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Multithreading (POSIX Threads)</h2>
          <p>Use <code>pthread</code> library for concurrent programming (on supported systems).</p>
          <div class="code-block">
<span class="code-comment">// Simple pthread example</span>
<span class="code-keyword">#include</span> <span class="code-string">&lt;pthread.h&gt;</span>

void* printMsg(void* arg) {
    printf(<span class="code-string">"Hello from thread!\n"</span>);
    return NULL;
}

<span class="code-keyword">int</span> main() {
    pthread_t tid;
    pthread_create(&tid, NULL, printMsg, NULL);
    pthread_join(tid, NULL);
    return 0;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always join threads and be careful with shared data (use mutexes for safety).
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Best Practices</h2>
          <ul>
            <li>Always initialize variables and pointers.</li>
            <li>Check return values of functions (especially malloc, fopen, etc.).</li>
            <li>Use <code>const</code> and <code>static</code> appropriately.</li>
            <li>Comment your code and use meaningful names.</li>
            <li>Modularize code for maintainability.</li>
            <li>Use tools (valgrind, static analyzers) for debugging and safety.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Writing safe, efficient, and maintainable C code is the hallmark of an advanced programmer.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Advanced Pointers</button>
          <button class="topic-btn" data-index="1">Dynamic Data Structures</button>
          <button class="topic-btn" data-index="2">Advanced File I/O</button>
          <button class="topic-btn" data-index="3">Bit Manipulation</button>
          <button class="topic-btn" data-index="4">Memory Management & Tools</button>
          <button class="topic-btn" data-index="5">Multithreading (POSIX Threads)</button>
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