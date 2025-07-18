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
    <title>HTML Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
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
          <h2>HTML Forms and Input Types</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>HTML forms collect user input. Modern HTML5 offers many input types for better UX and validation.</p>
          <div class="code-block">
<form>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <label for="age">Age:</label>
  <input type="number" id="age" name="age" min="1" max="120">
  <label for="color">Favorite Color:</label>
  <input type="color" id="color" name="color">
  <button type="submit">Submit</button>
</form>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>required</code>, <code>min</code>, <code>max</code>, <code>pattern</code> for built-in validation.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Semantic HTML5 Elements</h2>
          <p>Semantic elements describe their meaning, improving accessibility and SEO.</p>
          <div class="code-block">
<header>
  <nav>Navigation</nav>
</header>
<main>
  <article>
    <h1>Article Title</h1>
    <section>Section content</section>
  </article>
  <aside>Sidebar</aside>
</main>
<footer>Footer info</footer>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>&lt;main&gt;</code>, <code>&lt;section&gt;</code>, <code>&lt;article&gt;</code>, <code>&lt;aside&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;footer&gt;</code> for structure.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Embedding Media</h2>
          <p>HTML5 makes it easy to embed audio, video, and external content.</p>
          <div class="code-block">
<video controls width="320">
  <source src="movie.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
<audio controls>
  <source src="audio.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
<iframe src="https://www.example.com" width="300" height="200"></iframe>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always provide fallback text for unsupported browsers.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Tables and Accessibility</h2>
          <p>Tables organize data. Use proper structure and accessibility attributes.</p>
          <div class="code-block">
<table>
  <caption>Student Grades</caption>
  <thead>
    <tr><th>Name</th><th>Grade</th></tr>
  </thead>
  <tbody>
    <tr><td>Alice</td><td>A</td></tr>
    <tr><td>Bob</td><td>B</td></tr>
  </tbody>
</table>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>&lt;caption&gt;</code>, <code>&lt;thead&gt;</code>, <code>&lt;tbody&gt;</code>, <code>&lt;th&gt;</code> for structure. Use <code>scope</code> and <code>aria</code> attributes for accessibility.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>HTML APIs (Drag & Drop, Geolocation)</h2>
          <p>HTML5 APIs add interactivity. Example: Drag and Drop, Geolocation.</p>
          <div class="code-block">
<!-- Drag and Drop -->
<div id="drag1" draggable="true" ondragstart="event.dataTransfer.setData('text', event.target.id)">Drag me!</div>
<div id="dropzone" ondrop="event.preventDefault(); var data=event.dataTransfer.getData('text'); this.appendChild(document.getElementById(data));" ondragover="event.preventDefault()">Drop here</div>

<!-- Geolocation -->
<script>
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(pos) {
    console.log('Latitude:', pos.coords.latitude);
  });
}
</script>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check for API support before using advanced features.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Best Practices</h2>
          <ul>
            <li>Use semantic tags for structure and accessibility.</li>
            <li>Validate forms with both HTML and JavaScript.</li>
            <li>Always provide alt text for images and fallback for media.</li>
            <li>Test your pages in multiple browsers.</li>
            <li>Keep your HTML clean and well-indented.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Good HTML is accessible, semantic, and easy to maintain.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other HTML Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Forms and Input Types</button>
          <button class="topic-btn" data-index="1">Semantic HTML5 Elements</button>
          <button class="topic-btn" data-index="2">Media Embedding</button>
          <button class="topic-btn" data-index="3">Tables and Accessibility</button>
          <button class="topic-btn" data-index="4">HTML APIs</button>
          <button class="topic-btn" data-index="5">Best Practices</button>
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