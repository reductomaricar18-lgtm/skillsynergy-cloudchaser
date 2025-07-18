<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MongoDB Advanced - SkillSynergy</title>
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
          <h2>Sharding & Horizontal Scaling</h2>
          <p>Learn how MongoDB distributes data across multiple servers for scalability and high availability.</p>
          <ul>
            <li>Sharding architecture</li>
            <li>Choosing a shard key</li>
            <li>Balancing and chunk migration</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Replication & High Availability</h2>
          <ul>
            <li>Replica sets</li>
            <li>Automatic failover</li>
            <li>Read and write concerns</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Security (Authentication, Authorization, Encryption)</h2>
          <ul>
            <li>Enabling authentication</li>
            <li>Role-based access control (RBAC)</li>
            <li>Encryption at rest and in transit</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Aggregations & Pipelines</h2>
          <p>Explore advanced aggregation stages, custom expressions, and pipeline optimizations.</p>
          <div class="code-block">
db.sales.aggregate([
  { $match: { status: "A" } },
  { $bucket: { groupBy: "$amount", boundaries: [0, 100, 200, 300], default: "Other", output: { count: { $sum: 1 } } } }
]);
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Backup, Restore & Disaster Recovery</h2>
          <ul>
            <li>mongodump and mongorestore</li>
            <li>Point-in-time recovery</li>
            <li>Disaster recovery planning</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Monitoring & Troubleshooting</h2>
          <ul>
            <li>Using MongoDB Atlas monitoring</li>
            <li>Interpreting logs and metrics</li>
            <li>Common issues and solutions</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Upgrades & Maintenance</h2>
          <ul>
            <li>Rolling upgrades</li>
            <li>Schema changes</li>
            <li>Maintenance best practices</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other MongoDB Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Sharding & Horizontal Scaling</button>
          <button class="topic-btn" data-index="1">Replication & High Availability</button>
          <button class="topic-btn" data-index="2">Security (Authentication, Authorization, Encryption)</button>
          <button class="topic-btn" data-index="3">Advanced Aggregations & Pipelines</button>
          <button class="topic-btn" data-index="4">Backup, Restore & Disaster Recovery</button>
          <button class="topic-btn" data-index="5">Monitoring & Troubleshooting</button>
          <button class="topic-btn" data-index="6">Upgrades & Maintenance</button>
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