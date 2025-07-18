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
    <title>C Advanced - SkillSynergy</title>
    
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
          <h2>Advanced Pointers</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
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
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
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