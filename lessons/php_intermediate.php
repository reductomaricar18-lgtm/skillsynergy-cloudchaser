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
    <title>PHP Intermediate - SkillSynergy</title>
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
          <h2>Arrays in PHP</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>PHP supports indexed, associative, and multidimensional arrays for flexible data storage.</p>
          <div class="code-block">
<span class="code-comment">// Indexed array</span>
$colors = array("red", "green", "blue");
echo $colors[0]; // red

<span class="code-comment">// Associative array</span>
$ages = array("Peter" => 22, "Jane" => 19);
echo $ages["Jane"]; // 19

<span class="code-comment">// Multidimensional array</span>
$matrix = array(
  array(1, 2, 3),
  array(4, 5, 6)
);
echo $matrix[1][2]; // 6
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>print_r()</code> or <code>var_dump()</code> to inspect arrays.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Functions in PHP</h2>
          <p>Functions help organize code, accept parameters, return values, and support variable scope and closures.</p>
          <div class="code-block">
<span class="code-comment">// Basic function</span>
function greet($name) {
    return "Hello, $name!";
}
echo greet("Alice");

<span class="code-comment">// Anonymous function (closure)</span>
$square = function($n) { return $n * $n; };
echo $square(5); // 25
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Functions can access global variables using <code>global</code> or <code>use</code> for closures.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Superglobals</h2>
          <p>Superglobals are built-in variables accessible from anywhere in PHP scripts.</p>
          <ul>
            <li><code>$_GET</code> and <code>$_POST</code> - Form data</li>
            <li><code>$_SESSION</code> - Session variables</li>
            <li><code>$_COOKIE</code> - Cookies</li>
            <li><code>$_FILES</code> - File uploads</li>
            <li><code>$_SERVER</code> - Server info</li>
            <li><code>$_ENV</code> - Environment variables</li>
          </ul>
          <div class="code-block">
<span class="code-comment">// Accessing a GET parameter</span>
echo $_GET['user'];
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always validate and sanitize user input from superglobals.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>File Handling</h2>
          <p>Read and write files using <code>fopen</code>, <code>fread</code>, <code>fwrite</code>, and <code>fclose</code>.</p>
          <div class="code-block">
<span class="code-comment">// Writing to a file</span>
$file = fopen("test.txt", "w");
fwrite($file, "Hello, PHP!\n");
fclose($file);

<span class="code-comment">// Reading from a file</span>
$file = fopen("test.txt", "r");
echo fread($file, filesize("test.txt"));
fclose($file);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check if <code>fopen</code> returns <code>false</code> before using the file handle.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Sessions & Cookies</h2>
          <p>Sessions and cookies store user data across requests.</p>
          <div class="code-block">
<span class="code-comment">// Starting a session</span>
session_start();
$_SESSION['user'] = 'Alice';
echo $_SESSION['user'];

<span class="code-comment">// Setting a cookie</span>
setcookie("favcolor", "blue", time()+3600);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use sessions for sensitive data; cookies are visible to the client.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Error Handling</h2>
          <p>Handle errors using <code>try-catch</code>, <code>throw</code>, and custom error handlers.</p>
          <div class="code-block">
<span class="code-comment">// Exception handling</span>
try {
    throw new Exception("Something went wrong!");
} catch (Exception $e) {
    echo $e->getMessage();
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>error_reporting()</code> and <code>set_error_handler()</code> for custom error management.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other PHP Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Arrays in PHP</button>
          <button class="topic-btn" data-index="1">Functions in PHP</button>
          <button class="topic-btn" data-index="2">Superglobals</button>
          <button class="topic-btn" data-index="3">File Handling</button>
          <button class="topic-btn" data-index="4">Sessions & Cookies</button>
          <button class="topic-btn" data-index="5">Error Handling</button>
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