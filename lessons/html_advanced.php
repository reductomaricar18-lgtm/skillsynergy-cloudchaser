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
    <title>HTML Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #c9ffbf 0%, #ffafbd 100%);
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
          <h2>Forms with JavaScript Validation</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Enhance forms with custom JavaScript validation for better UX and security.</p>
          <div class="code-block">
<form id="myForm">
  <input type="text" id="username" required minlength="3">
  <span id="errorMsg"></span>
  <button type="submit">Submit</button>
</form>
<script>
document.getElementById('myForm').onsubmit = function(e) {
  var user = document.getElementById('username').value;
  if (user.length < 3) {
    document.getElementById('errorMsg').textContent = 'Username too short!';
    e.preventDefault();
  }
};
</script>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always validate on both client and server for security.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>ARIA and Accessibility</h2>
          <p>Use ARIA attributes to improve accessibility for users with assistive technologies.</p>
          <div class="code-block">
<button aria-label="Close" onclick="closeDialog()">&times;</button>
<div role="alert" aria-live="assertive">Error occurred!</div>
<nav aria-label="Main Navigation">
  <ul>
    <li><a href="#" aria-current="page">Home</a></li>
  </ul>
</nav>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>aria-label</code>, <code>aria-live</code>, <code>role</code>, and <code>aria-current</code> for better accessibility.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Custom Elements (Web Components)</h2>
          <p>Create reusable HTML elements with custom behavior using Web Components.</p>
          <div class="code-block">
<script>
class MyGreeting extends HTMLElement {
  connectedCallback() {
    this.innerHTML = '<b>Hello, Web Component!</b>';
  }
}
customElements.define('my-greeting', MyGreeting);
</script>
<my-greeting></my-greeting>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Web Components help you encapsulate and reuse UI logic.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Canvas and SVG</h2>
          <p>Draw graphics and animations using <code>&lt;canvas&gt;</code> and <code>&lt;svg&gt;</code>.</p>
          <div class="code-block">
<canvas id="myCanvas" width="100" height="100"></canvas>
<script>
var ctx = document.getElementById('myCanvas').getContext('2d');
ctx.fillStyle = 'red';
ctx.fillRect(10, 10, 80, 80);
</script>
<svg width="100" height="100">
  <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
</svg>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>canvas</code> for dynamic graphics, <code>svg</code> for scalable vector images.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Responsive Design</h2>
          <p>Make your site look great on all devices using meta tags and media queries.</p>
          <div class="code-block">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
@media (max-width: 600px) {
  body { background: #f0f0f0; }
}
</style>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use the viewport meta tag and test on different screen sizes.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Performance and SEO Best Practices</h2>
          <ul>
            <li>Minimize HTML, CSS, and JS file sizes.</li>
            <li>Use semantic tags for SEO.</li>
            <li>Lazy-load images and media.</li>
            <li>Use <code>alt</code> attributes for images.</li>
            <li>Set <code>lang</code> attribute on <code>&lt;html&gt;</code>.</li>
            <li>Use <code>meta</code> tags for description and keywords.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Fast, accessible, and semantic HTML ranks better and provides a better user experience.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other HTML Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Forms with JS Validation</button>
          <button class="topic-btn" data-index="1">ARIA & Accessibility</button>
          <button class="topic-btn" data-index="2">Custom Elements</button>
          <button class="topic-btn" data-index="3">Canvas & SVG</button>
          <button class="topic-btn" data-index="4">Responsive Design</button>
          <button class="topic-btn" data-index="5">Performance & SEO</button>
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