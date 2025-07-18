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
    <title>Cassandra Advanced - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
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
          <h2>Advanced Data Modeling</h2>
          <p>Explore advanced data modeling techniques such as time series, multi-tenancy, and handling large partitions.</p>
          <ul>
            <li>Time series data patterns</li>
            <li>Multi-tenancy strategies</li>
            <li>Handling large partitions</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Compaction & Tombstones</h2>
          <p>Understand compaction strategies, tombstone management, and their impact on performance and storage.</p>
          <ul>
            <li>Types of compaction</li>
            <li>Tombstone creation and removal</li>
            <li>Repair and anti-entropy</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Advanced Consistency & Availability</h2>
          <ul>
            <li>Tunable consistency levels</li>
            <li>Quorum, local quorum, and ALL</li>
            <li>Handling network partitions</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Security</h2>
          <ul>
            <li>Authentication and authorization</li>
            <li>Role-based access control (RBAC)</li>
            <li>Encryption at rest and in transit</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Monitoring & Troubleshooting</h2>
          <ul>
            <li>Using nodetool and JMX</li>
            <li>Interpreting logs and metrics</li>
            <li>Common issues and solutions</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Multi-DC & Geo-Replication</h2>
          <ul>
            <li>NetworkTopologyStrategy</li>
            <li>Replication across data centers</li>
            <li>Read/write consistency in multi-DC</li>
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
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Cassandra Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Advanced Data Modeling</button>
          <button class="topic-btn" data-index="1">Compaction & Tombstones</button>
          <button class="topic-btn" data-index="2">Advanced Consistency & Availability</button>
          <button class="topic-btn" data-index="3">Security</button>
          <button class="topic-btn" data-index="4">Monitoring & Troubleshooting</button>
          <button class="topic-btn" data-index="5">Multi-DC & Geo-Replication</button>
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