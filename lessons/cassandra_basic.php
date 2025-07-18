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
    <title>Cassandra Basics - SkillSynergy</title>
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
          <h2>Introduction to Cassandra</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Apache Cassandra is a highly scalable, distributed NoSQL database designed for handling large amounts of data across many commodity servers. It offers high availability with no single point of failure.</p>
          <ul>
            <li><strong>Distributed:</strong> Data is distributed across multiple nodes</li>
            <li><strong>Scalable:</strong> Handles petabytes of data</li>
            <li><strong>Fault-tolerant:</strong> No single point of failure</li>
            <li><strong>Flexible Schema:</strong> Supports dynamic data models</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Architecture</h2>
          <p>Cassandra uses a peer-to-peer architecture with no master node. All nodes are equal and communicate with each other.</p>
          <ul>
            <li><strong>Nodes:</strong> Individual machines in the cluster</li>
            <li><strong>Data Centers:</strong> Group of related nodes</li>
            <li><strong>Commit Log:</strong> All writes are written to the commit log for durability</li>
            <li><strong>SSTables:</strong> Immutable data files stored on disk</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Data Model</h2>
          <p>Cassandra uses a table-based data model, but is more flexible than traditional RDBMS.</p>
          <ul>
            <li><strong>Keyspace:</strong> Similar to a database in RDBMS</li>
            <li><strong>Table:</strong> Collection of rows</li>
            <li><strong>Row:</strong> Identified by a primary key</li>
            <li><strong>Column:</strong> Name-value pair</li>
          </ul>
          <div class="code-block">
-- Creating a keyspace
CREATE KEYSPACE mykeyspace WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1};
-- Creating a table
CREATE TABLE users (id UUID PRIMARY KEY, name TEXT, age INT);
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>CQL (Cassandra Query Language)</h2>
          <p>CQL is the primary language for interacting with Cassandra. It is similar to SQL but designed for Cassandra's architecture.</p>
          <div class="code-block">
-- Select all users
SELECT * FROM users;
-- Insert a user
INSERT INTO users (id, name, age) VALUES (uuid(), 'Alice', 30);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>cqlsh</code> to interact with Cassandra from the command line.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Replication</h2>
          <p>Replication in Cassandra ensures data is copied across multiple nodes for fault tolerance and high availability.</p>
          <ul>
            <li><strong>Replication Factor:</strong> Number of copies of data</li>
            <li><strong>Strategy:</strong> SimpleStrategy, NetworkTopologyStrategy</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Consistency</h2>
          <p>Consistency determines how up-to-date and synchronized a row of data is on all of its replicas.</p>
          <ul>
            <li><strong>Eventual Consistency:</strong> Updates will propagate eventually</li>
            <li><strong>Consistency Levels:</strong> ONE, QUORUM, ALL, etc.</li>
          </ul>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Partitioning</h2>
          <p>Partitioning determines how data is distributed across nodes in the cluster.</p>
          <ul>
            <li><strong>Partition Key:</strong> Determines the node where data is stored</li>
            <li><strong>Token Ring:</strong> Logical ring for data distribution</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Cassandra Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to Cassandra</button>
          <button class="topic-btn" data-index="1">Architecture</button>
          <button class="topic-btn" data-index="2">Data Model</button>
          <button class="topic-btn" data-index="3">CQL (Cassandra Query Language)</button>
          <button class="topic-btn" data-index="4">Replication</button>
          <button class="topic-btn" data-index="5">Consistency</button>
          <button class="topic-btn" data-index="6">Partitioning</button>
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
