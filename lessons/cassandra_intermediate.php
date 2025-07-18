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
    <title>Cassandra Intermediate - SkillSynergy</title>
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
          <h2>Data Modeling Best Practices</h2>
          <p>Learn how to design efficient data models in Cassandra, including denormalization, query-driven modeling, and anti-patterns to avoid.</p>
          <ul>
            <li>Denormalization for performance</li>
            <li>Query-based design</li>
            <li>Wide rows and partition size</li>
            <li>Common anti-patterns</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Secondary Indexes & Materialized Views</h2>
          <p>Understand when and how to use secondary indexes and materialized views, and their impact on performance.</p>
          <ul>
            <li>When to use secondary indexes</li>
            <li>Limitations and best practices</li>
            <li>Materialized views for alternate queries</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Batch Operations</h2>
          <p>Learn about batch statements in Cassandra, their use cases, and potential pitfalls.</p>
          <div class="code-block">
-- Batch insert example
BEGIN BATCH
INSERT INTO users (id, name) VALUES (uuid(), 'Bob');
INSERT INTO users (id, name) VALUES (uuid(), 'Carol');
APPLY BATCH;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use batches for atomicity, not for performance.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Lightweight Transactions (LWT)</h2>
          <p>Explore how to use lightweight transactions for conditional updates and their performance implications.</p>
          <div class="code-block">
-- Insert if not exists
INSERT INTO users (id, name) VALUES (uuid(), 'Dave') IF NOT EXISTS;
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Performance Tuning Basics</h2>
          <ul>
            <li>Read/write path overview</li>
            <li>Memtables and SSTables</li>
            <li>Compaction strategies</li>
            <li>Monitoring with nodetool</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Using Drivers & APIs</h2>
          <p>Introduction to using Cassandra drivers in various languages (Java, Python, Node.js, etc.).</p>
          <div class="code-block">
# Python example
from cassandra.cluster import Cluster
cluster = Cluster(['127.0.0.1'])
session = cluster.connect('mykeyspace')
rows = session.execute('SELECT * FROM users')
for row in rows:
    print(row)
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Backup & Restore</h2>
          <ul>
            <li>Snapshot backups</li>
            <li>Restoring from snapshots</li>
            <li>Best practices for disaster recovery</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Cassandra Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Data Modeling Best Practices</button>
          <button class="topic-btn" data-index="1">Secondary Indexes & Materialized Views</button>
          <button class="topic-btn" data-index="2">Batch Operations</button>
          <button class="topic-btn" data-index="3">Lightweight Transactions (LWT)</button>
          <button class="topic-btn" data-index="4">Performance Tuning Basics</button>
          <button class="topic-btn" data-index="5">Using Drivers & APIs</button>
          <button class="topic-btn" data-index="6">Backup & Restore</button>
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