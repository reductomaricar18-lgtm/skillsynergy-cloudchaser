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
    <title>C Intermediate - SkillSynergy</title>
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
          <h2>Structs and Unions C Intermediate</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p><strong>Structs</strong> group variables of different types under a single name. <strong>Unions</strong> allow storing different data types in the same memory location (only one at a time).</p>
          <div class="code-block">
<span class="code-comment">// Struct example</span>
<span class="code-keyword">struct</span> Student {
    <span class="code-keyword">char</span> name[50];
    <span class="code-keyword">int</span> age;
    <span class="code-keyword">float</span> gpa;
};

<span class="code-keyword">struct</span> Student s1;
strcpy(s1.name, <span class="code-string">"Alice"</span>);
s1.age = <span class="code-number">20</span>;
s1.gpa = <span class="code-number">3.8</span>;
          </div>
          <div class="code-block">
<span class="code-comment">// Union example</span>
<span class="code-keyword">union</span> Data {
    <span class="code-keyword">int</span> i;
    <span class="code-keyword">float</span> f;
    <span class="code-keyword">char</span> str[20];
};

<span class="code-keyword">union</span> Data d;
d.i = <span class="code-number">10</span>;
d.f = <span class="code-number">220.5</span>;
strcpy(d.str, <span class="code-string">"C Language"</span>);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> In a union, all members share the same memory. Changing one member changes the others.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Enums</h2>
          <p><strong>Enums</strong> are user-defined types consisting of named integer constants, improving code readability.</p>
          <div class="code-block">
<span class="code-comment">// Enum example</span>
<span class="code-keyword">enum</span> Day { MON, TUE, WED, THU, FRI, SAT, SUN };
<span class="code-keyword">enum</span> Day today = WED;

<span class="code-keyword">if</span> (today == WED) {
    printf(<span class="code-string">"It's Wednesday!\n"</span>);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Enum values start from 0 by default, but you can assign custom values.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Dynamic Memory Management</h2>
          <p>Use <code>malloc</code>, <code>calloc</code>, <code>realloc</code>, and <code>free</code> to manage memory at runtime.</p>
          <div class="code-block">
<span class="code-comment">// malloc and free</span>
<span class="code-keyword">int</span> *arr = (<span class="code-keyword">int</span>*)malloc(<span class="code-number">5</span> * <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>));
<span class="code-keyword">if</span> (arr == NULL) {
    printf(<span class="code-string">"Memory allocation failed\n"</span>);
}
<span class="code-keyword">for</span> (<span class="code-keyword">int</span> i = <span class="code-number">0</span>; i < <span class="code-number">5</span>; i++) {
    arr[i] = i * <span class="code-number">2</span>;
}
free(arr);
          </div>
          <div class="code-block">
<span class="code-comment">// realloc</span>
arr = (<span class="code-keyword">int</span>*)realloc(arr, <span class="code-number">10</span> * <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always <code>free</code> memory allocated with <code>malloc</code> or <code>realloc</code> to avoid memory leaks.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Pointers to Functions</h2>
          <p>Pointers can also point to functions, enabling callbacks and flexible APIs.</p>
          <div class="code-block">
<span class="code-comment">// Function pointer example</span>
<span class="code-keyword">int</span> add(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) { <span class="code-keyword">return</span> a + b; }
<span class="code-keyword">int</span> (*funcPtr)(<span class="code-keyword">int</span>, <span class="code-keyword">int</span>) = add;
<span class="code-keyword">int</span> result = funcPtr(<span class="code-number">2</span>, <span class="code-number">3</span>); <span class="code-comment">// result is 5</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Function pointers are used in libraries like <code>qsort</code> and for implementing callbacks.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Binary File I/O</h2>
          <p>Read and write binary files using <code>fread</code> and <code>fwrite</code>.</p>
          <div class="code-block">
<span class="code-comment">// Writing to a binary file</span>
FILE *f = fopen(<span class="code-string">"data.bin"</span>, <span class="code-string">"wb"</span>);
<span class="code-keyword">int</span> nums[3] = {1, 2, 3};
fwrite(nums, <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>), 3, f);
fclose(f);

<span class="code-comment">// Reading from a binary file</span>
f = fopen(<span class="code-string">"data.bin"</span>, <span class="code-string">"rb"</span>);
<span class="code-keyword">int</span> arr[3];
fread(arr, <span class="code-keyword">sizeof</span>(<span class="code-keyword">int</span>), 3, f);
fclose(f);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check if <code>fopen</code> returns <code>NULL</code> before using the file pointer.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Preprocessor Directives</h2>
          <p>Preprocessor directives are instructions processed before compilation, such as <code>#define</code>, <code>#include</code>, <code>#ifdef</code>, etc.</p>
          <div class="code-block">
<span class="code-comment">// Macro definition</span>
<span class="code-keyword">#define</span> PI 3.14159

<span class="code-comment">// Conditional compilation</span>
<span class="code-keyword">#ifdef</span> DEBUG
    printf(<span class="code-string">"Debug mode\n"</span>);
<span class="code-keyword">#endif</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use macros for constants and conditional compilation for debugging or platform-specific code.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Modular Programming</h2>
          <p>Split code into multiple files for better organization and reusability. Use header files (<code>.h</code>) for declarations and source files (<code>.c</code>) for definitions.</p>
          <div class="code-block">
<span class="code-comment">// mathutils.h</span>
<span class="code-keyword">int</span> add(<span class="code-keyword">int</span>, <span class="code-keyword">int</span>);

<span class="code-comment">// mathutils.c</span>
<span class="code-keyword">int</span> add(<span class="code-keyword">int</span> a, <span class="code-keyword">int</span> b) { <span class="code-keyword">return</span> a + b; }

<span class="code-comment">// main.c</span>
<span class="code-keyword">#include</span> <span class="code-string">"mathutils.h"</span>
<span class="code-keyword">int</span> main() {
    printf(<span class="code-string">"%d\n"</span>, add(<span class="code-number">2</span>, <span class="code-number">3</span>));
    <span class="code-keyword">return</span> <span class="code-number">0</span>;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>#ifndef</code>, <code>#define</code>, and <code>#endif</code> in header files to prevent multiple inclusion.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other C Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Structs and Unions</button>
          <button class="topic-btn" data-index="1">Enums</button>
          <button class="topic-btn" data-index="2">Dynamic Memory Management</button>
          <button class="topic-btn" data-index="3">Pointers to Functions</button>
          <button class="topic-btn" data-index="4">Binary File I/O</button>
          <button class="topic-btn" data-index="5">Preprocessor Directives</button>
          <button class="topic-btn" data-index="6">Modular Programming</button>
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