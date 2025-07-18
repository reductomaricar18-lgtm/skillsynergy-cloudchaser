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
    <title>CSS Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #cfd9df 0%, #e2ebf0 100%);
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
          <h2>CSS Grid</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>CSS Grid is a two-dimensional layout system for the web, allowing you to create complex layouts easily.</p>
          <div class="code-block">
<span class="code-comment">/* Grid Container Example */</span>
.container {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
  grid-gap: 20px;
}
.item {
  background: #fcb69f;
  padding: 20px;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>grid-template-areas</code> for semantic layouts.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Advanced Selectors</h2>
          <p>Use attribute selectors, pseudo-classes, and pseudo-elements for powerful targeting.</p>
          <div class="code-block">
<span class="code-comment">/* Attribute Selector */</span>
a[target="_blank"] {
  color: #4a63ff;
}

<span class="code-comment">/* Pseudo-class */</span>
li:first-child {
  font-weight: bold;
}

<span class="code-comment">/* Pseudo-element */</span>
p::first-line {
  color: #f6ad55;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Combine selectors for very specific styling (e.g., <code>ul li:last-child::after</code>).
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Advanced Animations</h2>
          <p>Use <code>@keyframes</code> and animation properties for complex, multi-step animations.</p>
          <div class="code-block">
<span class="code-comment">/* Keyframes Animation */</span>
@keyframes bounce {
  0%   { transform: translateY(0); }
  50%  { transform: translateY(-30px); }
  100% { transform: translateY(0); }
}
.bounce {
  animation: bounce 1s infinite;
}

<span class="code-comment">/* Animation Timing */</span>
.animated {
  animation: slide 2s cubic-bezier(.68,-0.55,.27,1.55) infinite alternate;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>animation-delay</code>, <code>animation-iteration-count</code>, and <code>animation-direction</code> for more control.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Custom Fonts & Icons</h2>
          <p>Use <code>@font-face</code> to load custom fonts and icon libraries like Font Awesome for scalable icons.</p>
          <div class="code-block">
<span class="code-comment">/* Custom Font Example */</span>
@font-face {
  font-family: 'MyFont';
  src: url('MyFont.woff2') format('woff2');
}
body {
  font-family: 'MyFont', sans-serif;
}

<span class="code-comment">/* Using Font Awesome */</span>
<i class="fa fa-home"></i>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always provide fallback fonts and check icon library documentation for usage.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>CSS Functions</h2>
          <p>Use functions like <code>calc()</code>, <code>clamp()</code>, <code>min()</code>, and <code>max()</code> for dynamic and responsive values.</p>
          <div class="code-block">
<span class="code-comment">/* calc() Example */</span>
.box {
  width: calc(100% - 40px);
}

<span class="code-comment">/* clamp() Example */</span>
.text {
  font-size: clamp(1rem, 2vw, 2rem);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> CSS functions help create fluid, adaptable layouts and typography.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Accessibility & Best Practices</h2>
          <ul>
            <li>Use semantic HTML and ARIA roles for better accessibility.</li>
            <li>Ensure sufficient color contrast for readability.</li>
            <li>Use <code>rem</code> and <code>em</code> units for scalable layouts.</li>
            <li>Test with screen readers and keyboard navigation.</li>
            <li>Minimize !important and avoid inline styles.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Accessible, maintainable CSS is essential for professional web development.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other CSS Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">CSS Grid</button>
          <button class="topic-btn" data-index="1">Advanced Selectors</button>
          <button class="topic-btn" data-index="2">Advanced Animations</button>
          <button class="topic-btn" data-index="3">Custom Fonts & Icons</button>
          <button class="topic-btn" data-index="4">CSS Functions</button>
          <button class="topic-btn" data-index="5">Accessibility & Best Practices</button>
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