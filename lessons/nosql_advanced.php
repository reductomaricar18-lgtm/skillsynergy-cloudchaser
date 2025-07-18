<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoSQL Advanced - SkillSynergy</title>
    <style>
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
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
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
          <h2>Polyglot Persistence & Multi-Model Databases</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <ul>
            <li>Using multiple NoSQL types in one application</li>
            <li>Multi-model databases (e.g., ArangoDB, Cosmos DB)</li>
            <li>When to use polyglot persistence</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Advanced Performance Tuning</h2>
          <ul>
            <li>Query profiling and optimization</li>
            <li>Resource allocation</li>
            <li>Scaling for high throughput</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Distributed Systems Concepts</h2>
          <ul>
            <li>CAP theorem</li>
            <li>Eventual consistency</li>
            <li>Partition tolerance</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Change Data Capture & Streams</h2>
          <ul>
            <li>CDC concepts</li>
            <li>Streaming data with NoSQL</li>
            <li>Integration with Kafka, Kinesis, etc.</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Multi-Region & Global Deployments</h2>
          <ul>
            <li>Geo-replication</li>
            <li>Latency considerations</li>
            <li>Conflict resolution</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Monitoring, Logging & Troubleshooting</h2>
          <ul>
            <li>Monitoring tools</li>
            <li>Log analysis</li>
            <li>Common troubleshooting steps</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Migration & Integration with RDBMS</h2>
          <ul>
            <li>Data migration strategies</li>
            <li>Hybrid architectures</li>
            <li>ETL tools and pipelines</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other NoSQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Polyglot Persistence & Multi-Model Databases</button>
          <button class="topic-btn" data-index="1">Advanced Performance Tuning</button>
          <button class="topic-btn" data-index="2">Distributed Systems Concepts</button>
          <button class="topic-btn" data-index="3">Change Data Capture & Streams</button>
          <button class="topic-btn" data-index="4">Multi-Region & Global Deployments</button>
          <button class="topic-btn" data-index="5">Monitoring, Logging & Troubleshooting</button>
          <button class="topic-btn" data-index="6">Migration & Integration with RDBMS</button>
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